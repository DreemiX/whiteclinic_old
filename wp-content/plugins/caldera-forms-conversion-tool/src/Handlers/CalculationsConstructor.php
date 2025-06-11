<?php

namespace NinjaForms\CfConversionTool\Handlers;
use NinjaForms\CfConversionTool\Contracts\CalculationsConstructor as ContractsCalculationsConstructor;
use NinjaForms\CfConversionTool\Contracts\MagicMergeTagLookup;

/**
 * Construct the Ninja Forms calculations from CF calculation fields
 */
class CalculationsConstructor implements ContractsCalculationsConstructor
{

    /**
     * Incoming params
     *
     * @param array $params
     */
    public $params = [];

    /**
     * @var MagicMergeTagLookup
     */
    public $magicMergeTagLookup;

    public function setParameters(array $params): ContractsCalculationsConstructor
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Construct Ninja Forms calculations as associative array
     *
     * @return array
     */
    public function handle(): array
    {
        $this->magicMergeTagLookup = $this->params['magicMergeTagLookup'];
        return $this->buildCalculations();
    }

    /**
     * Build the NF calculations array
     * 
     * @return array
     */
    public function buildCalculations(): array
    {
        $calcsArray = [];

        foreach($this->params['fields'] as $field) {
            // Skip the field if it's not a calculation.
            if( 'calculation' !== $field['type'] ) continue;

            $calc = [
                'name' => $field['slug'],
                'dec' => '',
                'eq' => ''
            ];

            if(isset($field['config']['fixed']) && (int)$field['config']['fixed']) {
                $calc['dec'] = '2';
            }

            if(isset($field['config']['manual']) && (int)$field['config']['manual']) {
                $calc['eq'] = $this->extractFromFormula( $field['config']['manual_formula'] );
            } else {
                $calc['eq'] = $this->extractFromGroup( $field['config']['config']['group'] );
            }
            $calcsArray[$calc['name']] = $calc;
        }

        return $this->sortCalcs( $calcsArray );
    }

    /**
     * Get an equation from a CF formula
     * 
     * @param string $formula The user defined formula
     * 
     * @return string
     */
    public function extractFromFormula( $formula ): string
    {
        return $this->magicMergeTagLookup->convertCalcString( $formula );
    }

    /**
     * Get an equation from a CF calculation group
     * 
     * @param array $group The CF calculation group
     * 
     * @return string
     */
    public function extractFromGroup( $group ): string
    {
        $eq = '';
        foreach( $group as $segment ) {
            if(isset($segment['operator'])) {
                $eq .= $segment['operator'];
                continue;
            }

            $eq .= '(';
            $first = true;
            foreach($segment['lines'] as $line) {
                if( ! $first ) {
                    $eq .= $line['operator'];
                } else {
                    $first = false;
                }
                $eq .= '%' . $this->getFieldKey( $line['field'] ) . '%';
            }
            $eq .= ')';
        }
        return $this->magicMergeTagLookup->convertCalcString( $eq );
    }

    /**
     * Sort the new calculations to avoid definition errors
     * 
     * @param array $unsorted The unsorted array of calcs
     * 
     * @return array
     */
    public function sortCalcs( $unsorted ): array
    {
        $order = 0;
        $sorted = [];
        // while unsorted isn't empty
        while( ! empty( $unsorted ) ) {
            // for each item in unsorted
            $calc = array_shift( $unsorted );
            $contains = $this->getCalcNames( $calc['eq'] );
            // if it contains calculations, verify that none of them are left in unsorted.
            foreach( $contains as $name ) {
                if( isset( $unsorted[ $name ] ) ) {
                    $unsorted[ $calc['name'] ] = $calc;
                    continue 2;
                }
            }
            $calc['order'] = (string)$order;
            $order++;
            $sorted[] = $calc;
        }
        return $sorted;
    }

    /**
     * Get the list of any calculations used in an equation
     * 
     * @param string $eq The equation to search
     * 
     * @return array
     */
    public function getCalcNames( $eq ): array
    {
        $calcs = [];
        $items = explode( '{calc:', $eq );
        $size = count($items);
        for($i = 1; $i < $size; $i++) {
            $calcs[] = substr( $items[$i], 0, strpos( $items[$i], '}') );
        }
        return $calcs;
    }

    /**
     * Fetch the new field key by the origin ID
     * 
     * @param string $id The origin ID
     * 
     * @return string
     */
    public function getFieldKey( $id ): string
    {
        foreach( $this->params[ 'convertedFields' ] as $field ) {
            if ( $id == $field[ 'originId' ] ) {
                return $field[ 'ninjaFieldDefinition' ][ 'field_key' ];
            }
        }
        return '';
    }
}
