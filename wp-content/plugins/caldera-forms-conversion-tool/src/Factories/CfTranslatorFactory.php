<?php

namespace NinjaForms\CfConversionTool\Factories;

use NinjaForms\CfConversionTool\Entities\FormTranslation;
use NinjaForms\CfConversionTool\Handlers\FormTranslator;

use NinjaForms\CfConversionTool\Factories\FieldConstructorFactory;
use NinjaForms\CfConversionTool\Factories\ActionConstructorFactory;
use NinjaForms\CfConversionTool\Handlers\ConstructCfTranslation;
use NinjaForms\CfConversionTool\Handlers\ConditionalConstructor;
use NinjaForms\CfConversionTool\Handlers\LayoutConstructor;
use NinjaForms\CfConversionTool\Handlers\CalculationsConstructor;
use NinjaForms\CfConversionTool\Handlers\MagicMergeTagLookup;


/**
 * Construct the class that constructs the NF from CF
 *
 * This builder class reads through the incoming CF array to construct 
 */
class CfTranslatorFactory
{
    /**
     * Incoming array of an exported Caldera Form
     *
     * @var array
     */
    protected $cfArray;

    /** @var ConstructCfTranslation */
    protected $constructCfTranslation;

    /** @var FormTranslation */
    protected $formTranslation;

    /**
     * String construct of NF ready for import
     *
     * @var string
     */
    protected $nffConstruct;

    /** @var FormTranslator */
    protected $formTranslator;

    public function makeTranslator(array $cfArray): FormTranslator
    {
        $this->cfArray = $cfArray;

        $this->constructTranslation();

        $this->initializeFormTranslator();

        return $this->formTranslator;
    }

    /**
     * Initialize FormTranslator
     * 
     * Construct, set the fields processor, 
     * @return void 
     */
    protected function initializeFormTranslator(): void
    {
        $this->formTranslator = (new FormTranslator())
            ->setNffConstruct($this->nffConstruct);;
    }

    /**
     * Construct the FormTranslation entity from the exported CF
     * 
     * @return void 
     */
    protected function constructTranslation(): void
    {
        $this->constructCfTranslation = new ConstructCfTranslation();

        $this->constructCfTranslation->setExportedCf($this->cfArray);

        $this->constructCfTranslation->setFieldConstructorFactory(new FieldConstructorFactory());

        $this->constructCfTranslation->setActionConstructorFactory(new ActionConstructorFactory());

        $this->constructCfTranslation->setConditionalConstructor(new ConditionalConstructor());

        $this->constructCfTranslation->setLayoutConstructor(new LayoutConstructor());

        $this->constructCfTranslation->setCalculationsConstructor(new CalculationsConstructor());

        $this->constructCfTranslation->setMagicMergeTagLookup(new MagicMergeTagLookup());
        
        $this->constructCfTranslation->handle();

        $this->nffConstruct = $this->constructCfTranslation->getNffConstruct();
    }
}
