<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;

/**
 * Construct email action from CF mailer
 */
class MailerConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'email';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->constructedAction['label'] = 'Email';
        $this->constructedAction['to'] = isset($this->cFProcessor['recipients']) ? $this->cFProcessor['recipients'] : "";
        $this->constructedAction['email_subject'] = isset($this->cFProcessor['email_subject']) ? $this->cFProcessor['email_subject'] : "";
        $this->constructedAction['email_message'] = isset($this->cFProcessor['email_message']) ? $this->cFProcessor['email_message'] : "";
        $this->constructedAction['from_name'] = isset($this->cFProcessor['sender_name']) ? $this->cFProcessor['sender_name'] : "";
        $this->constructedAction['from_address'] = isset($this->cFProcessor['sender_email']) ? $this->cFProcessor['sender_email'] : "";
        $this->constructedAction['reply_to'] = isset($this->cFProcessor['reply_to']) ? $this->cFProcessor['reply_to'] : "";
        $this->constructedAction['cc'] = isset($this->cFProcessor['cc_to']) ? $this->cFProcessor['cc_to'] : "";
        $this->constructedAction['bcc'] = isset($this->cFProcessor['bcc_to']) ? $this->cFProcessor['bcc_to'] : "";
        $this->constructedAction['email_format'] = isset($this->cFProcessor['email_type']) ? $this->cFProcessor['email_type'] : "";

        $this->constructedAction = $this->magicMergeTagLookup->convertMagicTagArray($this->constructedAction);
    }
}
