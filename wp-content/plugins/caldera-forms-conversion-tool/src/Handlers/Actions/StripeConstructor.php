<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;
/**
 * Construct email action from CF mailer
 */
class StripeConstructor extends AbstractActionConstructor implements ActionConstructor
{

    /** @inheritDoc */
     protected function setType(): void
    {
        $this->constructedAction['type'] = 'stripe';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {

        $this->constructedAction['label'] = 'Stripe';
        $this->constructedAction["payment_gateways"] = "stripe";

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
     * Check Mode via CF API keys set
     * 
     * Set the $mode to string "test" or "live"
     *
     * @return string
     *
     * */
    protected function getMode(): string
    {
        $mode = false;
        if (
            isset($this->cFProcessor['config']['secret']) &&
            '' !== $this->cFProcessor['config']['secret'] &&
            isset($this->cFProcessor['config']['publishable']) &&
            '' !== $this->cFProcessor['config']['publishable']
        ) {
            if( 
                \strpos($this->cFProcessor['config']['publishable'], "_test_") !==false &&
                \strpos($this->cFProcessor['config']['secret'], "_test_") !== false
            ) {
                $mode = 'test';
            } else {
                $mode = 'live';
            }
        }
        return $mode;
    }

    /**
     * Save the API keys in NF plugin settings
     *
     * @return void
     *
     * */
    protected function saveApiKeys(): void
    {
        //Get current NF Stripe API keys settings
        $nf_stripe_test_secret = Ninja_Forms()->get_setting('stripe_test_secret_key');
        $nf_stripe_test_publishable = Ninja_Forms()->get_setting('stripe_test_publishable_key');
        $nf_stripe_live_secret = Ninja_Forms()->get_setting('stripe_live_secret_key');
        $nf_stripe_live_publishable = Ninja_Forms()->get_setting('stripe_live_publishable_key');
        //Check and/or set data for CF keys
        $cf_secret_key = isset($this->cFProcessor['config']['secret']) && '' !== $this->cFProcessor['config']['secret'] ? $this->cFProcessor['config']['secret'] : false;
        $cf_publishable_key =   isset($this->cFProcessor['config']['publishable']) && '' !== $this->cFProcessor['config']['publishable'] ? $this->cFProcessor['config']['publishable'] : false;
        
        //Set Keys depending on mode 
        switch($this->getMode()){
            case 'test':
                //Set Secret Key if NF doesn't have secret keys saved
                if( $nf_stripe_test_secret === false || $nf_stripe_test_publishable === false ){
                    Ninja_Forms()->update_setting('stripe_test_secret_key', $cf_secret_key);
                    Ninja_Forms()->update_setting('stripe_test_publishable_key', $cf_publishable_key);
                }
            break;
            case  'live':
                //Set Publishable Key if NF doesn't have live keys saved
                if( $nf_stripe_live_secret === false || $nf_stripe_live_publishable === false ){
                    Ninja_Forms()->update_setting('stripe_live_secret_key', $cf_secret_key);
                    Ninja_Forms()->update_setting('stripe_live_publishable_key', $cf_publishable_key);
                }
            break;
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
        //Test mode to enable test API keys
        $this->constructedAction["stripe_test_mode"] = $this->getMode() === "test" ? "1" : "0";

        /**
         * We convert settings that exist
         */
        /**
         * Fix amount or plan data
         * In CF we can set both a fix amout and a Plan ID then set a Radio option to decide which one to use 
         * but in NF I believe that having a Plan ID set overrides the fixed amount functionnality 
         * so we're going to import only the setting used based on the radio input choice plan or fixed amount.
         * 
         * */
        if(
            $this->cFProcessor['config']['type'] &&
            'plan' === $this->cFProcessor['config']['type']
        ){
            //Data for recurring plans
            if(
                $this->cFProcessor['config']['plan'] &&
                '' !== $this->cFProcessor['config']['plan']
            ){
                $this->constructedAction[ "stripe_recurring_plan" ] = $this->cFProcessor['config']['plan'];
            }
        } else {
            /**
             * Amount data
             * 
             * This a field or a variable in CF. We are not converting variable at the moment so we'll always fallback on a field type for the moment.
             * ( field ID converted during merge tag process in $this->setUnique() )
             * 
             * */
            $this->constructedAction["payment_total_type"] = "field";

            // Add condition where CF is using 'variable'
            if(0!==strpos($this->cFProcessor['config']['amount'],'variable:')){
                $this->constructedAction["payment_total_type"] = "fixed";
            }

            if(
                $this->cFProcessor['config']['amount'] &&
                '' !== $this->cFProcessor['config']['amount']
            ){
                $this->constructedAction["payment_total"] = $this->cFProcessor['config']['amount'];
            }
        }
        //Product - customer data
        if(
            $this->cFProcessor['config']['email'] &&
            '' !== $this->cFProcessor['config']['email']
        ){
            $this->constructedAction["stripe_customer_email"] = $this->cFProcessor['config']['email'];
        }
        if(
            $this->cFProcessor['config']['item'] &&
            '' !== $this->cFProcessor['config']['item']
        ){
            $this->constructedAction["stripe_product_name"] = $this->cFProcessor['config']['item'];
        }
        if(
            $this->cFProcessor['config']['description'] &&
            '' !== $this->cFProcessor['config']['description']
        ){
            $this->constructedAction["stripe_product_description"] = $this->cFProcessor['config']['description'];
        }
        if(
            $this->cFProcessor['config']['file'] &&
            '' !== $this->cFProcessor['config']['file']
        ){
            $this->constructedAction["stripe_product_image"] = $this->cFProcessor['config']['file'];
        }

    }

}
