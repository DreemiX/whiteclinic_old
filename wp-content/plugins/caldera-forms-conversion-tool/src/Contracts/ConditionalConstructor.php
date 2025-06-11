<?php

namespace NinjaForms\CfConversionTool\Contracts;

/**
 * Construct the Ninja Forms conditionals
 */
interface ConditionalConstructor
{

    /**
     * Set parameters for the conditions
     *
     * @param array $params
     * @return ConditionalConstructor
     */
    public function setParameters(array $params): ConditionalConstructor;

    /**
     * Construct Ninja Forms conditionals as associative array
     *
     * @return array
     */
    public function handle(): array;
}
