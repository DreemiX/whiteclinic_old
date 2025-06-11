<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;

/**
 * Construct standard parameters of NF field from CF field
 */
abstract class AbstractFieldConstructor implements FieldConstructor
{

    /**
     * Parameters for constructing textbox field
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * CF field array
     *
     * @var array
     */
    protected $cFField = [];

    /**
     * Constructed field in array structure
     *
     * @var array
     */
    protected $constructedField = [];


    /** @inheritDoc */
    public function setParameters(array $params): FieldConstructor
    {
        $this->parameters = \array_merge($this->parameters, $params);

        if (isset($this->parameters['field'])) {
            $this->cFField = $this->parameters['field'];
        }

        return $this;
    }

    /** @inheritDoc */
    public function handle(): array
    {

        $this->constructField();

        return $this->constructedField;
    }

    /**
     * Construct a textbox field
     *
     * @param array $field
     * @return array
     */
    protected function constructField(): void
    {
        $this->constructedField = [];

        $this->setType();

        //$this->setId(); !!!! Setting the field ID breaks the process
        $this->setLabels();
        $this->setKeys();
        $this->setPlaceholder();
        $this->setDefault();
        $this->setDescription();
        $this->setPersonallyIdentifiable();
        $this->setRequired();
        $this->setLabelPosition();
        $this->setContainerClass();
        $this->setOrder();

        $this->setUnique();
    }


    /**
     * Set the NF field type
     */
    abstract  protected function setType(): void;

    /**
     * Set NF parameters not shared across all fields
     */
    abstract  protected function setUnique(): void;


    /**
     * Set field ID
     */
    protected function setId(): void
    {
        $value = '';

        if (isset($this->cFField['ID'])) {
            $value = (string)$this->cFField['ID'];
        }
        
        $this->constructedField['id'] = $value;
    }
    /**
     * Set field label
     */
    protected function setLabels(): void
    {
        $value = '';

        if (isset($this->cFField['label'])) {
            $value = (string)$this->cFField['label'];
        }

        $this->constructedField['label'] = $value;
        $this->constructedField['field_label'] = $value;
    }

    /**
     * Set field keys
     */
    protected function setKeys(): void
    {
        $value = '';

        if (isset($this->cFField['slug'])) {
            $value = (string)$this->cFField['slug'];
        }

        $this->constructedField['key'] = $value;
        $this->constructedField['field_key'] = $value;
    }
    /**
     * Set description
     */
    protected function setDescription(): void
    {
        $value = '';

        if (isset($this->cFField['caption'])) {
            $value = (string)$this->cFField['caption'];
        }

        $this->constructedField['desc_text'] = $value;
    }

    /**
     * Set placeholder
     *
     * @return void
     */
    protected function setPlaceholder(): void
    {
        $value = '';

        if (isset($this->cFField['config']['placeholder'])) {
            $value = (string)$this->cFField['config']['placeholder'];
        }

        $this->constructedField['placeholder'] = $value;
    }
    /**
     * Set default
     *
     * @return void
     */
    protected function setDefault(): void
    {
        $value = '';

        if (isset($this->cFField['config']['default'])) {
            $value = (string)$this->cFField['config']['default'];
        }

        $this->constructedField['default'] = $value;
    }

    /**
     * Convert CF Personal Identify to NF
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setPersonallyIdentifiable(): void
    {
        $value = '0';

        if (isset($this->cFField['config']['personally_identifying'])) {
            $value = (string)$this->cFField['config']['personally_identifying'];
        }

        $this->constructedField['personally_identifiable'] = $value;
    }

     /**
     * Convert CF Required field setting to NF
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setRequired(): void
    {
        $value = '0';

        if (isset($this->cFField['required'])) {
            $value = (string)$this->cFField['required'];
        }

        $this->constructedField['required'] = $value;
    }

    /**
     * Convert CF "Hide label" option to NF "Label Position -> Hidden"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setLabelPosition(): void
    {
        $value = 'default';

        if (isset($this->cFField['hide_label']) && $this->cFField['hide_label'] === 1) {
            $value = "hidden";
        }

        $this->constructedField['label_pos'] = $value;
    }

    /**
     * Convert CF "Hide label" option to NF "Label Position -> Hidden"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setContainerClass(): void
    {
        $value = '';

        if (isset($this->cFField['config']['custom_class'])) {
            $value = (string)$this->cFField['config']['custom_class'];
        }

        $this->constructedField['container_class'] = $value;
    }

     /**
     * Set default setting "order" required by $fields_db_columns (we don't have a CF equivalent)
     *
     * @return void
     */
    protected function setOrder(): void
    {
        $this->constructedField['order'] = '';
    }
}
