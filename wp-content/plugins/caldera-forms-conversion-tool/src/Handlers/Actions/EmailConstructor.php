<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;
/**
 * Construct email action from CF email processor
 */
class EmailConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'email';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->constructedAction['label']='Email';
        $this->constructedAction['to']=$this->cFProcessor['config']['recipient_email'];
        $this->constructedAction['email_subject']=$this->cFProcessor['config']['subject'];
        $this->constructedAction['email_message']=$this->cFProcessor['config']['message'];
        $this->constructedAction['from_name']=$this->cFProcessor['config']['sender_name'];
        $this->constructedAction['from_address']=$this->cFProcessor['config']['sender_email'];
        $this->constructedAction['reply_to']=$this->cFProcessor['config']['reply_to'];
        $this->constructedAction['cc']=$this->cFProcessor['config']['cc'];
        $this->constructedAction['bcc']=$this->cFProcessor['config']['bcc'];
        $this->constructedAction['email_format']='html';

        $this->constructedAction=$this->magicMergeTagLookup->convertMagicTagArray($this->constructedAction);
    }
}
