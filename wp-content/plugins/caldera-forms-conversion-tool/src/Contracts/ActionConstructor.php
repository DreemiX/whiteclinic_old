<?php

namespace NinjaForms\CfConversionTool\Contracts;

/**
 * Construct a specific Ninja Forms action type
 */
interface ActionConstructor
{

    /**
     * Set parameters for the action
     *
     * @param array $params
     * @return ActionConstructor
     */
    public function setParameters(array $params): ActionConstructor;

    /**
     * Construct Ninja Forms action as associative array
     *
     * @return array
     */
    public function handle(): array;
}
