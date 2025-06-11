<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct textbox field from CF text field
 */
class TextboxConstructor extends AbstractFieldConstructor implements FieldConstructor
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
