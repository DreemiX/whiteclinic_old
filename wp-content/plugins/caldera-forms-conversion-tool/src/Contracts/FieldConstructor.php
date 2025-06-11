<?php

namespace NinjaForms\CfConversionTool\Contracts;

/**
 * Construct a specific Ninja Forms field type
 */
interface FieldConstructor
{

    /**
     * Set parameters for the field
     *
     * @param array $params
     * @return FieldConstructor
     */
    public function setParameters(array $params): FieldConstructor;

    /**
     * Construct Ninja Forms field definition as associative array
     *
     * @return array
     */
    public function handle(): array;
}
