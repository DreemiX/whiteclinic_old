<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;

/**
 * Construct placeholder for unknown action
 */
class UnknownActionConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'unknown';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {

    }
}
