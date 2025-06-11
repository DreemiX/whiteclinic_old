<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct email field from CF email field
 */
class EmailFieldConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'email';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        // no unique parameters
    }
}
