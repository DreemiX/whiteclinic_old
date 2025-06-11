<?php

namespace NinjaForms\CfConversionTool\Handlers;

use NinjaForms\CfConversionTool\Contracts\ConditionalConstructor as ContractsConditionConstructor;

use NinjaForms\CfConversionTool\Entities\ConditionalWhenDefinition;
use NinjaForms\CfConversionTool\Entities\ConditionalThenDefinition;
use NinjaForms\CfConversionTool\Entities\ConditionalElseDefinition;

/**
 * Construct the Ninja Forms conditionals from CF conditionals
 */
class ConditionalConstructor implements ContractsConditionConstructor
{

    /**
     * Incoming params
     *
     * @param array $params
     * @return ConditionalConstructor
     */
    public $params = [];

    /**
     * Conditions array constructed as NF structure
     *
     * @var array
     */
    protected $conditionArray = [];

    /**
     * Array of origin field definitions
     *
     * @var array
     */
    protected $originFields = [];

    /**
     * Array of origin condition definitions
     *
     * @var array
     */
    protected $originConditionArray = [];

    /**
     * Lookup between origin condition keys and fields
     *
     * [conditionalSlug]=>[collection of field Ids]
     * @var array
     */
    public $originFieldConditionLookup = [];

    /**
     * Array of value lookups
     * 
     * Option keys, possible others(?)
     *
     * @var array
     */
    public $valueLookups = [];

    /**
     * Lookup origin field slug from origin field key
     *
     * @var array
     */
    public $fieldSlugLookups = [];

    /**
     * In process construction
     * 
     * Work in process while constructing the final array
     *
     * @var array
     */
    public $workInProcess = [];

    /**
     * Set parameters for the condition constructor
     * 
     * Required keys 'fields', 'conditions', 'fieldInterpreter'
     *
     * @todo Replace keyed parameters with setters after data requirements finalized
     * @param array $params
     * @return ConditionalConstructor
     */
    public function setParameters(array $params): ContractsConditionConstructor
    {
        $this->params = $params;

        $this->originFields = $this->params['fields'];
        $this->originConditionArray = $this->params['conditions'];

        return $this;
    }

    /**
     * Construct Ninja Forms conditionals as associative array
     *
     * @return array
     */
    public function handle(): array
    {
        $this->constructOriginFieldLookups();
        $this->setThenElses();
        $this->assembleWhensThenElse();
        $this->consolidateCommonWhens();

        $this->conditionArray = $this->workInProcess['consolidatedConditionals'];

        return $this->conditionArray;
    }

    /**
     * Check for identical 'when',merge then/else's into single conditional
     *
     * @return void
     */
    protected function consolidateCommonWhens(): void
    {
        if(isset($this->workInProcess['singleConditionalCollection'])){
            $singleConditionalCollection = $this->workInProcess['singleConditionalCollection'];
            $this->workInProcess['consolidatedConditionals']=$this->workInProcess['singleConditionalCollection'];
            return;
        }else{
            $this->workInProcess['consolidatedConditionals']=[];
            return;
        }
  }
    

    /**
     * Given field Id, returns field key
     * 
     * CF `slug` maps too NF `key` and `field_key`
     *
     * @param string $fieldId
     * @return string
     */
    protected function getFieldKeyById(string $fieldId): string
    {
        $fieldKey = $fieldId;

        if (isset($this->fieldSlugLookups[$fieldId])) {
            $fieldKey = $this->fieldSlugLookups[$fieldId];
        }

        return $fieldKey;
    }

    /**
     * Assemble a single conditional as a when-then-else
     *
     * @return void
     */
    protected function assembleWhensThenElse(): void
    {
        if(isset($this->workInProcess['when'])){

            foreach ($this->workInProcess['when'] as $doKey => $group) {
                foreach ($group as $whenKey => $whenArray) {
                    $thenArray = [];
                    if (isset($this->workInProcess['then'][$doKey])) {
                        $thenArray = $this->workInProcess['then'][$doKey];
                    }
                    $elseArray = [];
                    if (isset($this->workInProcess['else'][$doKey])) {
                        $elseArray = $this->workInProcess['else'][$doKey];
                    }

                    $this->workInProcess['singleConditionalCollection'][] = [
                        'collapsed' => '1',
                        'process' => '1',
                        'connector' => 'all',
                        'when' => $whenArray,
                        'then' => $thenArray,
                        'else' => $elseArray
                    ];
                }
            }
        }
    }

    /**
     * Return value lookup if present, incoming key if not
     *
     * Values for CF conditionals have a lookup key to the actual value; this
     * method retrieves the value by the provided key.
     * 
     * @see constructOriginFieldLookups()
     * @param string $key
     * @return void
     */
    protected function getLookupValue(string $key)
    {
        $return = $key;

        if (isset($this->valueLookups[$key])) {
            $return = $this->valueLookups[$key];
        }
        return $return;
    }

    /**
     * Construct the lookup between origin condition keys and field keys
     * 
     * @return void 
     */
    protected function constructOriginFieldLookups(): void
    {
        foreach ($this->originFields as $field) {
            if (
                isset($field['conditions']['type']) &&
                '' !== $field['conditions']['type'] &&
                isset($field['ID'])
            ) {
                $this->originFieldConditionLookup[$field['conditions']['type']][] = $field['ID'];
            }

            if (isset($field['config']['option'])) {
                foreach ($field['config']['option'] as $key => $optionArray) {
                    if (isset($optionArray['value'])) {
                        
                        $value = $optionArray['value'];

                        if(1===count($field['config']['option'])){
                            // NF checkbox, not list select
                            $value = 'checked';
                        }

                        $this->valueLookups[$key] = $value;
                    }
                }
            }

            if (isset($field['ID']) && isset($field['slug'])) {
                $this->fieldSlugLookups[$field['ID']] = $field['slug'];
            }
        }
    }

    /**
     * Set all the then/elses conditions using origin key for identification
     *
     *  @return void 
     */
    protected function setThenElses(): void
    {
        foreach ($this->originConditionArray as $doKey => $doThis) {
            
            if (
                !isset($doThis['type']) ||
                !isset($this->originFieldConditionLookup[$doKey]) ||
                !isset($doThis['group']) ||
                !isset($doThis['fields'])

            ) {
                continue;
            }

            $then = new ConditionalThenDefinition();
            $else = new ConditionalElseDefinition();

            $trigger = $this->determineNfTrigger($doThis['type']);
            $then->setTrigger($trigger);
            $elseTrigger = $this->determineCounteringElseTrigger($trigger);
            $else->setTrigger($elseTrigger);

            $fieldIds=$this->originFieldConditionLookup[$doKey];
            foreach ($fieldIds as $fieldId) {
                $fieldKey = $this->getFieldKeyById($fieldId);

                $then->setKey($fieldKey);
                $else->setKey($fieldKey);
            
                $this->workInProcess['then'][$doKey][] = $then->toArray();
                $this->workInProcess['else'][$doKey][] = $else->toArray();
            }

            $this->setWhens($doThis);
        }
    }

    /**
     * Set the When conditions
     *
     * @param array $cfCondition
     * @return void
     */
    protected function setWhens(array $cfCondition): void
    {
        $doKey = $cfCondition['id'];

        if(1<count($cfCondition['group'])){
            $this->setComplexWhens($cfCondition);
            return;
        }

        foreach ($cfCondition['group'] as $groupKey => $group) {

            foreach ($group as $cfWhenKey => $cfWhen) {
                $when = new ConditionalWhenDefinition();
                
                $when->setComparator($this->determineNfComparator($cfWhen['compare']));
                
                $fieldId = $cfWhen['field'];
                
                $fieldKey = $this->getFieldKeyById($fieldId);
                
                $when->setKey($fieldKey);

                $when->setValue($this->getLookupValue($cfWhen['value']));

                $this->workInProcess['when'][$doKey][$groupKey][] = $when->toArray();
            }
        }
    }

    /**
     * Set complex WHEN statements
     *
     * ANDs are grouped into individual conditions and only do the 'then'
     * portion; each condition thus does the same thing and effectively performs
     * the complex OR
     *
     * All the individual statements are then ANDed using their logical opposite
     * to perform the original ELSE as a 'then'
     *
     * Complex WHENs cannot have an ELSE, because the multiple ELSEs will oppose
     * each other and conflict
     *
     *
     * @param array $cfCondition
     * @return void
     */
    protected function setComplexWhens(array $cfCondition): void
    {
        $doKey = $cfCondition['id'];

        $complexDoKeyAnd = $doKey.'_complexDoKeyAnd';
            
        $newThens = $this->workInProcess['else'][$doKey];

        foreach($newThens as &$newThen){

            $newThen['modelType'] = 'then';
        }
        
        $this->workInProcess['then'][$complexDoKeyAnd] = $newThens;
        
        foreach ($cfCondition['group'] as $groupKey => $group) {
            
            foreach ($group as $cfWhenKey => $cfWhen) {

                $complexDoKeyOr = $cfWhenKey.'_complexDoKeyOr';
                $this->workInProcess['then'][$complexDoKeyOr]=$this->workInProcess['then'][$doKey];
                
                $when = new ConditionalWhenDefinition();
                
                $when->setComparator($this->determineNfComparator($cfWhen['compare']));
                
                $fieldId = $cfWhen['field'];
                
                $fieldKey = $this->getFieldKeyById($fieldId);
                
                $when->setKey($fieldKey);

                $when->setValue($this->getLookupValue($cfWhen['value']));

                $oppositeWhen = ConditionalWhenDefinition::fromArray($when->toArray());
                $oppositeWhen->setComparator($this->determineOppositeNfComparator($when->getComparator()));
                
                $this->workInProcess['when'][$complexDoKeyOr][$groupKey][] = $when->toArray();
                $this->workInProcess['when'][$complexDoKeyAnd]['combined'][] = $oppositeWhen->toArray();
            }
        }

    }


    /**
     * Determine the corresponding NF comparator for a CF conditional compare
     *
     * @param string $cfType
     * @return string
     */
    protected function determineNfComparator(?string $cfCompare): string
    {
        switch ($cfCompare) {
            case 'greater':
                $trigger = 'greater';
                break;
            case 'smaller':
                $trigger = 'less';
                break;
            case 'contains':
                $trigger = 'contains';
                break;
            case 'isnot':
                $trigger = 'notequal';
                break;
            case 'is':
            default:
                $trigger = 'equal';
        }

        return $trigger;
    }

    /**
     * Determine the corresponding NF comparator for a CF conditional compare
     *
     * @param string $nfComparator
     * @return string
     */
    protected function determineOppositeNfComparator(?string $nfComparator): string
    {
        switch ($nfComparator) {

            case 'greater':
                $trigger = 'smaller';
                break;
            case 'smaller':
                $trigger = 'greater';
                break;
            case 'contains':
                $trigger = 'notcontains';
                break;
            case 'notequal':
                $trigger = 'equal';
                break;
            case 'equal':
                $trigger = 'notequal';
                break;
            default:
            $trigger = $nfComparator;
        }

        return $trigger;
    }

    /**
     * Determine the corresponding NF trigger for a CF conditional type
     * 
     * NF does not have a 'disable' option; using 'hide' instead
     *
     * @param string $cfType
     * @return string
     */
    protected function determineNfTrigger(?string $cfType): string
    {
        switch ($cfType) {
            case 'hide':
            case 'disable';
                $trigger = 'hide_field';
                break;

            case 'show':
            default:
                $trigger = 'show_field';
        }

        return $trigger;
    }

    /**
     * Given an NF trigger for Then statement, return Else trigger
     * 
     * Some triggers have an implied countering trigger, e.g. show/hide
     * 
     *
     * @param string $nfTrigger
     * @return string
     */
    protected function determineCounteringElseTrigger(string $nfTrigger): string
    {
        switch ($nfTrigger) {

            case 'hide_field':
            case 'disable':
                $trigger = 'show_field';
                break;
            case 'show_field':
            default:
                $trigger = 'hide_field';
        }

        return $trigger;
    }
}
