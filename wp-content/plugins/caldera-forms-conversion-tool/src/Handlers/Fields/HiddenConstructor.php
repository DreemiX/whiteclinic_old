<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct hidden field from CF hidden field
 */
class HiddenConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'hidden';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        // no unique parameters
    }
}
