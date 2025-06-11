<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;
/**
 * Convert CF passord field to NF textbox field
 * 
 */
class PasswordTextboxConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'textbox';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        // no unique parameters
    }
}