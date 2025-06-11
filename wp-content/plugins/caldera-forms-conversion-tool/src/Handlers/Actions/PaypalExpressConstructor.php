<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;
/**
 * Construct email action from CF mailer
 */
class PaypalExpressConstructor extends AbstractActionConstructor implements ActionConstructor
{

    /** @inheritDoc */
     protected function setType(): void
    {
        $this->constructedAction['type'] = 'paypal-express';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {

        $this->constructedAction['label'] = 'Paypal Express';
        $this->constructedAction["payment_gateways"] = "paypal-express";

        $this->saveApiKeys();
        $this->saveSettings();
        
        //Process magic tags to merge tags conversion
        foreach($this->constructedAction as $ind => $val){
            if( isset($this->fieldSlugLookups[$val]) ) {
                $this->constructedAction[$ind] = $this->magicMergeTagLookup->wrapFieldMergeTag($this->fieldSlugLookups[$val]);
            }
        }
        $this->constructedAction = $this->magicMergeTagLookup->convertMagicTagArray($this->constructedAction);
    }

    /**
     * Save the API keys in NF plugin settings
     *
     * @return void
     *
     * */
    protected function saveApiKeys(): void
    {
        //Is CF in Test or live mode?
        $mode = $this->cFProcessor['config']['sandbox'] === 1 ? "test" : "live";

        //Get current NF Stripe API keys settings
        $nf_ppe_username = Ninja_Forms()->get_setting('ppe_' . $mode . '_api_username');
        $nf_ppe_password = Ninja_Forms()->get_setting('ppe_' . $mode . '_api_password');
        $nf_ppe_signature = Ninja_Forms()->get_setting('ppe_' . $mode . '_api_signature');
    
        //Get CF credentials or set them to false
        $cf_ppe_username = isset($this->cFProcessor['config']['username']) && '' !== $this->cFProcessor['config']['username'] ? $this->cFProcessor['config']['username'] : false;
        $cf_ppe_password =   isset($this->cFProcessor['config']['password']) && '' !== $this->cFProcessor['config']['password'] ? $this->cFProcessor['config']['password'] : false;
        $cf_ppe_signature =   isset($this->cFProcessor['config']['signature']) && '' !== $this->cFProcessor['config']['signature'] ? $this->cFProcessor['config']['signature'] : false;
        
        //Save API keys if none are already set globally
        if( $nf_ppe_username === false || $nf_ppe_password === false || $nf_ppe_signature ){
            Ninja_Forms()->update_setting('ppe_' . $mode . '_api_username', $cf_ppe_username);
            Ninja_Forms()->update_setting('ppe_' . $mode . '_api_password', $cf_ppe_password);
            Ninja_Forms()->update_setting('ppe_' . $mode . '_api_signature', $cf_ppe_signature);
        }

    }

    /**
     * Save the Action Settings
     *
     * @return void
     *
     * */
    protected function saveSettings(): void
    {

        /**
         * Amount data
         * */
        //Default amount type to field
        $this->constructedAction["payment_total_type"] = "field";
        // Add condition where CF is using 'variable'
        if( false !== strpos($this->cFProcessor['config']['price'],'variable:') ){
            $this->constructedAction["payment_total_type"] = "fixed";
        }
        //Set amount
        if(
            $this->cFProcessor['config']['price'] &&
            '' !== $this->cFProcessor['config']['price']
        ){
            $this->constructedAction["payment_total"] = $this->cFProcessor['config']['price'];
        }
        
        //Sandbox mode
        $this->constructedAction["ppe_sandbox"] = $this->cFProcessor['config']['sandbox'] === 1 ? 1 : 0;

        //Product - customer data
        if(
            $this->cFProcessor['config']['description'] &&
            '' !== $this->cFProcessor['config']['description']
        ){
            $this->constructedAction["ppe_details"] = $this->cFProcessor['config']['description'];
        }
    }

}
