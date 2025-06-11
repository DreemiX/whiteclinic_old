<?php

namespace NinjaForms\CfConversionTool\Contracts;

/**
 * Construct the Ninja Forms layout settings from CF
 */
interface LayoutConstructor
{

    /**
     * Set parameters for the layout
     *
     * @param array $params
     * @return LayoutConstructor
     */
    public function setParameters(array $params): LayoutConstructor;

    /**
     * Construct/return Ninja Forms layout as associative array
     *
     * @return array
     */
    public function handle(): array;
}
