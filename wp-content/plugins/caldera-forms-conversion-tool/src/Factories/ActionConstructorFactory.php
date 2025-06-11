<?php

namespace NinjaForms\CfConversionTool\Factories;

use NinjaForms\CfConversionTool\Contracts\ActionConstructorFactory as ContractsActionConstructorFactory;
use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\ActiveCampaignConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\EmailConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\MailerConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\RedirectConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\UnknownActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\MailchimpConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\SuccessMessageConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\StripeConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\PaypalExpressConstructor;

class ActionConstructorFactory implements ContractsActionConstructorFactory
{

    /** @inheritDoc */
    public function getActionConstructor(array $processor): ActionConstructor
    {

        $actionString = $this->determineActionConstructor($processor);

        $fieldConstructor = $this->makeFieldConstructor($actionString);

        return $fieldConstructor;
    }
    /**
     * Convert Cf processors into NF action types
     *
     * @param array $processor
     * @return string
     */
    protected function determineActionConstructor(array $processor): string
    {
        $incomingProcessorType = $processor['type'];

        switch ($incomingProcessorType) {
            case 'success_message';
                $actionType = 'successmessage';
                break;
            case 'form_redirect';
                $actionType = 'redirect';
                break;
            case 'auto_responder':
                $actionType = 'email';
                break;
            case 'mailer':
                $actionType = 'mailer';
                break;
            case 'cf-mailchimp-2':
                $actionType = 'mailchimp';
                break;
            case 'cf-activecampaign':
                $actionType = 'nfacds';
                break;
            case 'stripe':
                $actionType = 'stripe';
                break;
            case 'paypal_express':
                $actionType = 'paypal-express';
                break;
            default:
                $actionType = 'unknown';
        }

        return $actionType;
    }

    /**
     * Create a specific ActionConstructor from given action type
     *
     * @param string $actionType
     * @return ActionConstructor
     */
    protected function makeFieldConstructor(string $actionType): ActionConstructor
    {
        switch ($actionType) {

            case 'successmessage';
                $return  = new SuccessMessageConstructor();
                break;
            case 'email':
                $return = new EmailConstructor();
                break;
            case 'mailer':
                $return = new MailerConstructor();
                break;
            case 'mailchimp':
                $return = new MailchimpConstructor();
                break;
            case 'nfacds':
                $return = new ActiveCampaignConstructor();
                break;
            case 'redirect':
                $return = new RedirectConstructor();
                break;
            case 'stripe':
                $return = new StripeConstructor();
                break;
            case 'paypal-express':
                $return = new PaypalExpressConstructor();
                break;
            default:
                $return = new UnknownActionConstructor();
        }

        return $return;
    }
}
