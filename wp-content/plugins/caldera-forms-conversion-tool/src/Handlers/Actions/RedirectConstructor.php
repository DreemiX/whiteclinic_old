<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;

/**
 * Construct redirect action from CF redirect processor
 */
class RedirectConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'redirect';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->constructedAction['label']='Redirect';
        $this->constructedAction['success_msg']=$this->cFProcessor['config']['message'];
        $this->constructedAction['redirect_url']=$this->cFProcessor['config']['url'];
        
        $this->constructedAction=$this->magicMergeTagLookup->convertMagicTagArray($this->constructedAction);
    }
}
