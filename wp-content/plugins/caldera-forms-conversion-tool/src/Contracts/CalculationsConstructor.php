<?php

namespace NinjaForms\CfConversionTool\Contracts;

/**
 * Construct the Ninja Forms calculations settings from CF
 */
interface CalculationsConstructor
{

    /**
     * Set parameters for the calculations
     *
     * @param array $params
     * @return CalculationsConstructor
     */
    public function setParameters(array $params): CalculationsConstructor;

    /**
     * Construct/return Ninja Forms calculations as associative array
     *
     * @return array
     */
    public function handle(): array;
}
