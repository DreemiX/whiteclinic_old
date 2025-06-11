<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Contracts\MagicMergeTagLookup;

/**
 * Construct standard parameters of NF action from CF processor
 */
abstract class AbstractActionConstructor implements ActionConstructor
{

    /**
     * Parameters for constructing textbox field
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * CF processor array
     *
     * @var array
     */
    protected $cFProcessor = [];

    /** @var MagicMergeTagLookup */
    protected $magicMergeTagLookup;

    /**
     * Lookup origin field slug from origin field key
     *
     * @var array
     */
    public $fieldSlugLookups = [];

    /**
     * Constructed action in array structure
     *
     * @var array
     */
    protected $constructedAction = [];


    /** 
     * Set required parameters to construct action
     * 
     * Requires `processor` key providing CF processor array
     * Required 'magicMergeTagLookup' key providing MagicMergeTagLookup
     * @inheritDoc 
     */
    public function setParameters(array $params): ActionConstructor
    {
        $this->parameters = \array_merge($this->parameters, $params);

        if (isset($this->parameters['processor'])) {
            $this->cFProcessor = $this->parameters['processor'];
        }

        if (isset($this->parameters['magicMergeTagLookup'])) {
            $this->magicMergeTagLookup = $this->parameters['magicMergeTagLookup'];
        }

        if (isset($this->parameters['fieldSlugLookups'])) {
            $this->fieldSlugLookups = $this->parameters['fieldSlugLookups'];
        }

        return $this;
    }

    /** @inheritDoc */
    public function handle(): array
    {

        $this->constructAction();

        return $this->constructedAction;
    }

    /**
     * Construct standard parameters for action
     *
     * @return array
     */
    protected function constructAction(): void
    {
        $this->constructedAction = [
            'active' => '1',
            'objectType' => 'Action',
            'objectDomain' => 'actions'
        ];

        $this->setType();

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
}
