<?php

namespace NinjaForms\CfConversionTool\Handlers;

use NinjaForms\CfConversionTool\Contracts\FieldConstructorFactory;
use NinjaForms\CfConversionTool\Contracts\ActionConstructorFactory;
use NinjaForms\CfConversionTool\Contracts\ConditionalConstructor;
use NinjaForms\CfConversionTool\Contracts\LayoutConstructor;
use NinjaForms\CfConversionTool\Contracts\CalculationsConstructor;
use NinjaForms\CfConversionTool\Contracts\MagicMergeTagLookup;

use NinjaForms\CfConversionTool\Entities\NinjaFieldDefinition;
use NinjaForms\CfConversionTool\Entities\FieldTranslation;
use NinjaForms\CfConversionTool\Entities\FormTranslation;


/**
 * Given an exported CF, translates it into a FormTranslation entity
 * 
 * The FormTranslation entity is used to create the entire form in Ninja Forms
 */
class ConstructCfTranslation
{

    /**
     * Array of exported Caldera Form
     * 
     * Decoded from JSON
     *
     * @var array
     */
    protected $exportedCf = [];

    /** @var FieldConstructorFactory */
    protected $fieldConstructorFactory;

    /** @var MagicMergeTagLookup */
    protected $magicMergeTagLookup;

    /** @var ActionConstructorFactory */
    protected $actionConstructorFactory;

    /** @var ConditionalConstructor */
    protected $conditionalConstructor;

    /** @var LayoutConstructor */
    protected $layoutConstructor;

    /** @var CalculationsConstructor */
    protected $calculationsConstructor;
    /**
     * Array of settings constructed for NF
     *
     * @var array
     */
    protected $settingsArray = [];

    /**
     * Array of actions constructed for NF
     *
     * @var array
     */
    protected $actionsArray = [];

    /** @var FormTranslation */
    protected $formTranslation;

    /**
     * Lookup origin field slug from origin field key
     *
     * @var array
     */
    public $fieldSlugLookups = [];

    /**
     * Collection of field translations
     *
     * Translation includes new field definition, original field id, and Ninja
     * Forms field id (which is not set until NF field is created)
     *
     * @var array
     */
    protected $fieldTranslationCollection = [];

    /**
     * Run the translation
     *
     * @return void
     */
    public function handle(): void
    {
        $this->formTranslation = new FormTranslation();

        $this->setFields();

        $this->setFormSettings();

        $this->setActions();
    }

    /**
     * Set form settings
     *
     * @return void
     */
    protected function setFormSettings(): void
    {

        $this->setFormDefaults();
        //Add CF form ID as a reference in NF Form Key
        if( isset( $this->exportedCf['ID'] ) ) {
            $this->settingsArray['key'] = $this->exportedCf['ID'];
        }

        $this->addConditionArrayToSettings();

        $this->addLayoutArrayToSettings();

        $this->addCalculationsArrayToSettings();

        $this->formTranslation->setFormSettings($this->settingsArray);
    }

    /**
     * Set form default settings based on $forms_db_columns
     *
     * @return void
     */
    protected function setFormDefaults(): void
    {
        //Default settings that don't have a CF equivalent
        $this->settingsArray['created_at'] = date("Y-m-d H:i:s");
        $this->settingsArray['default_label_pos'] = "above";
        $this->settingsArray['show_title'] = "1";
        $this->settingsArray['clear_complete'] = "1";
        $this->settingsArray['logged_in'] = "";
        $this->settingsArray['seq_num'] = "";
        //Default settings that can have a CF equivalent
        $this->settingsArray['title'] = isset($this->exportedCf['name']) ? $this->exportedCf['name'] : __("Form Title", "cf_conversion_tool");
        $this->settingsArray['form_title'] = isset($this->exportedCf['name']) ? $this->exportedCf['name'] : __("Form Title", "cf_conversion_tool");
        $this->settingsArray['hide_complete'] = isset( $this->exportedCf['hide_form'] ) ? (string)$this->exportedCf['hide_form'] : "1";
    }

    /**
     * Create all the fields based on type
     *
     * $this->fieldTranslationCollection has lookups from previous field Ids to
     * Ninja Forms field ids
     *
     * @return void
     */
    protected function setFields(): void
    {
        foreach ($this->exportedCf['fields'] as $field) {

            $fieldConstructor = $this->fieldConstructorFactory->getFieldConstructor($field);

            $fieldArray = $fieldConstructor
                ->setParameters(['field' => $field])
                ->handle();

            // Pass through entity for parameter validation
            $ninjaFieldDefinition = (NinjaFieldDefinition::fromArray($fieldArray))->toArray();

            $fieldTranslation = (FieldTranslation::fromArray(
                [
                    'originId' => $field['ID'],
                    'ninjaFieldDefinition' => $ninjaFieldDefinition
                ]
            ))->toArray();

            $this->fieldTranslationCollection[] = $fieldTranslation;

            if (isset($field['slug'])) {
                if(isset($field['type']) && 'calculation' === $field['type']) {
                    $isCalc = true;
                } else {
                    $isCalc = false;
                }
                $this->magicMergeTagLookup->addSlugKey($field['slug'], $isCalc);

                if (isset($field['ID'])) {
                    $this->fieldSlugLookups[$field['ID']] = $field['slug'];
                }
            }
        }

        $this->formTranslation->setFieldTranslationCollection($this->fieldTranslationCollection);
    }

    /**
     * Create all the actions
     * @return void 
     */
    protected function setActions(): void
    {
        if( isset($this->exportedCf['processors'])){
            foreach ($this->exportedCf['processors'] as $processor) {
                $this->buildAction($processor);
            }
        }
        
        $this->addMailer();
        $this->formSettingsToActions();
    }

    /**
     * There are CF Form settings that need to be converted to NF actions
     * 
     * @return void 
     */
    protected function formSettingsToActions() : void
    {
        if( !empty( $this->exportedCf['success'] ) )  {

            $processor = [
                'type'              =>  __('success_message', 'cf_conversion_tool'),
                'success_message'   =>  $this->exportedCf['success']
            ];
            $this->buildAction($processor);
        } 

    }

    /**
     * Mailer is a dedicated CF email action with unique configuration
     *
     * If active on form, create array of mailer info and add type ='mailer'
     * such that the action constructor factory can create the mailer
     * constructor.
     * 
     * @return void 
     */
    protected function addMailer(): void
    {
        if (!isset($this->exportedCf['mailer']['on_insert'])) {
            return;
        }

        if (1 === $this->exportedCf['mailer']['on_insert']) {

            $processor = $this->exportedCf['mailer'];
            $processor['type'] = 'mailer';
            
            $this->buildAction($processor);
        }
    }

    /**
     * Build action based on a processor array
     * 
     * @return void
     */
    protected function buildAction( $processor ) : void 
    {
        $actionConstructor = $this->actionConstructorFactory->getActionConstructor($processor);

        $actionArray = $actionConstructor
            ->setParameters([
                'processor' => $processor,
                'magicMergeTagLookup' => $this->magicMergeTagLookup,
                'fieldSlugLookups' => $this->fieldSlugLookups
            ])
            ->handle();

        $this->actionsArray[] = $actionArray;
    }

    /**
     * Add condition array to settings
     *
     * @return void
     */
    protected function addConditionArrayToSettings(): void
    {
        if (!isset($this->exportedCf['conditional_groups']['conditions'])) {
            return;
        }

        $this->conditionalConstructor->setParameters(
            [
                'fields' => $this->exportedCf['fields'],
                'conditions' => $this->exportedCf['conditional_groups']['conditions']
            ]
        );

        $conditionArray = $this->conditionalConstructor->handle();

        $this->settingsArray['conditions'] = $conditionArray;
    }

    protected function addLayoutArrayToSettings(): void
    {
        $this->layoutConstructor->setParameters(
            [
                'lookup' => $this->exportedCf['layout_grid']['fields'],
                'structure' => $this->exportedCf['layout_grid']['structure'],
                'fields' => $this->fieldTranslationCollection
            ]

        );

        $layoutArray = $this->layoutConstructor->handle();

        $this->settingsArray['formContentData'] = $layoutArray;
    }

    protected function addCalculationsArrayToSettings(): void
    {
        $this->calculationsConstructor->setParameters(
            [
                'fields' => $this->exportedCf['fields'],
                'magicMergeTagLookup' => $this->magicMergeTagLookup,
                'convertedFields' => $this->fieldTranslationCollection
            ]

        );

        $calculationsArray = $this->calculationsConstructor->handle();

        $this->settingsArray['calculations'] = $calculationsArray;
    }

    /**
     * Get the NF import-ready string construct of the translation
     *
     * @return string
     */
    public function getNffConstruct(): string
    {
        $directExport = '';

        $formArray = [];
        $formArray['settings'] = $this->formTranslation->getFormSettings();

        $fields = [];
        foreach ($this->formTranslation->getFieldTranslationCollection() as $fieldTranslation) {
            $fields[] = $fieldTranslation['ninjaFieldDefinition'];
        }
        $formArray['fields'] = $fields;

        $formArray['actions'] = $this->actionsArray;

        $directExport = \json_encode($formArray);

        return $directExport;
    }

    /**
     * Get the CF formTranslation
     */
    public function getFormTranslation(): FormTranslation
    {
        return isset($this->formTranslation) ? $this->formTranslation : new FormTranslation();
    }

    /**
     * Set the FieldConstructorFactory
     *
     * @return  ConstructCfTranslation
     */
    public function setFieldConstructorFactory($fieldConstructorFactory): ConstructCfTranslation
    {
        $this->fieldConstructorFactory = $fieldConstructorFactory;

        return $this;
    }

    /**
     * Set the exported CF form as array
     *
     * @param array $array
     * @return ConstructCfTranslation
     */
    public function setExportedCf(array $array): ConstructCfTranslation
    {
        $this->exportedCf = $array;

        return $this;
    }

    /**
     * Set the ConditionalConstructor
     *
     * @return  ConstructCfTranslation
     */
    public function setConditionalConstructor($conditionalConstructor): ConstructCfTranslation
    {
        $this->conditionalConstructor = $conditionalConstructor;

        return $this;
    }

    /**
     * Set the LayoutConstructor
     *
     * @return  self
     */
    public function setLayoutConstructor($layoutConstructor): ConstructCfTranslation
    {
        $this->layoutConstructor = $layoutConstructor;

        return $this;
    }

    /**
     * Set the CalculationsConstructor
     *
     * @return  self
     */
    public function setCalculationsConstructor($calculationsConstructor): ConstructCfTranslation
    {
        $this->calculationsConstructor = $calculationsConstructor;

        return $this;
    }

    /**
     * Set the ActionConstructorFactory
     *
     * @return  self
     */
    public function setActionConstructorFactory($actionConstructorFactory)
    {
        $this->actionConstructorFactory = $actionConstructorFactory;

        return $this;
    }

    /**
     * Set the MagicMergeTagLookup
     *
     * @return  self
     */
    public function setMagicMergeTagLookup($magicMergeTagLookup)
    {
        $this->magicMergeTagLookup = $magicMergeTagLookup;

        return $this;
    }
}
