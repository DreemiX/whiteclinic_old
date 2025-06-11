<?php 
namespace NinjaForms\CfConversionTool\Contracts;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
/**
 * Provides classes to construct each NF field type
 */
interface FieldConstructorFactory{

    /**
     * Given an exported CF field array, provide the appropriate field constructor object 
     *
     * @param array $field
     * @return FieldConstructor
     */
    public function getFieldConstructor(array $field ): FieldConstructor;
    
}