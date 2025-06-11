<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;
/**
 * Construct Success message action from CF Success message Form setting
 */
class SuccessMessageConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'successmessage';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->constructedAction["label"] = __("Success Message", "cf_conversion_tool");
        $this->constructedAction["success_msg"] = $this->parameters['processor']['success_message'];
        $this->constructedAction=$this->magicMergeTagLookup->convertMagicTagArray($this->constructedAction);
    }
}
