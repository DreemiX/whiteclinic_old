<?php 
namespace NinjaForms\CfConversionTool\Contracts;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
/**
 * Provides classes to construct each NF action type
 */
interface ActionConstructorFactory{

    /**
     * Given an exported CF processor, provide the appropriate action constructor object 
     *
     * @param array $processor
     * @return ActionConstructor
     */
    public function getActionConstructor(array $processor ): ActionConstructor;
    
}