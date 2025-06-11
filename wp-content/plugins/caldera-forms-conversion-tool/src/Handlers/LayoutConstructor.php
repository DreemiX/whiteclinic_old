<?php

namespace NinjaForms\CfConversionTool\Handlers;
use NinjaForms\CfConversionTool\Contracts\LayoutConstructor as ContractsLayoutConstructor;

/**
 * Construct the Ninja Forms conditionals from CF layout
 */
class LayoutConstructor implements ContractsLayoutConstructor
{

    /**
     * Incoming params
     *
     * @param array $params
     */
    public $params = [];
    public $map = [];

    public function setParameters(array $params): ContractsLayoutConstructor
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Construct Ninja Forms layout as associative array
     *
     * @return array
     */
    public function handle(): array
    {
        $this->mapData();
        return $this->buildLayout();
    }

    /**
     * Build a dataset we can use to fetch fields for each cell of the layout
     * 
     * @return void
     */
    public function mapData(): void
    {
        foreach( $this->params['lookup'] as $id => $ref ) {
            $split = explode( ':', $ref );
            $this->map[ $split[0] ][ $split[1] ][] = $this->getFieldKey( $id );
        }
    }

    /**
     * Build the layout array
     * 
     * @return array
     */
    public function buildLayout(): array
    {
        $layoutArray = [];

        $parts = explode( '#', $this->params['structure'] );
        foreach( $parts as $part ) {
            $partArray = $this->buildPart($part);
            $partArray[ 'order' ] = (string) count( $layoutArray );
            $layoutArray[] = $partArray;
        }

        return $layoutArray;

    }

    /**
     * Build a part array
     * 
     * @param string $structure The row structure of the part
     * @return array
     */
    public function buildPart( $structure ): array
    {
        $rows = explode( '|', $structure );
        $partArray = array(
            'order' => '',
            'type' => 'part',
            'title' => 'Part Title',
            'key' => $this->newPartKey(),
            'formContentData' => array()
        );
        foreach( $rows as $row ) {
            $rowArray = $this->buildRow( $row );
            $rowArray[ 'order' ] = (string) count( $partArray[ 'formContentData' ] );
            $partArray[ 'formContentData' ][] = $rowArray;
        }
        return $partArray;
    }

    /**
     * Build a row array
     * 
     * @param string $structure The cell structure of the row
     * @return array
     */
    public function buildRow( $structure ): array
    {
        // Preset the width of each cell as a percentage of the pixels on the page.
        $pixel = array( 8, 8, 9, 8, 8, 9, 9, 8, 8, 9, 8, 8 );
        $cells = explode( ':', $structure );
        $row = array_shift( $this->map );
        $rowArray = array(
            'cells' => array(),
        );
        foreach( $cells as $key => $cell ) {
            $fields = isset( $row[ $key + 1 ] ) ? $row[ $key + 1 ] : array();
            $width = 0;
            for( $i = 0; $i < intval( $cell ); $i++ ){
                $width += array_shift( $pixel );
            }
            $rowArray['cells'][] = array(
                'order' => (string) count( $rowArray['cells'] ),
                'fields' => $fields,
                'width' => (string) $width
            );
        }
        return $rowArray;
    }

    /**
     * Generate a random key for the newly created part
     * 
     * @return string
     */
    public function newPartKey(): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $chars .= $chars . $chars;
        return substr(str_shuffle($chars), 0, 8);
    }

    /**
     * Fetch the new field key by the origin ID
     * 
     * @param string $id The origin ID
     * @return string
     */
    public function getFieldKey( $id ): string
    {
        foreach( $this->params[ 'fields' ] as $field ) {
            if ( $id == $field[ 'originId' ] ) {
                return $field[ 'ninjaFieldDefinition' ][ 'field_key' ];
            }
        }
        return '';
    }
}
