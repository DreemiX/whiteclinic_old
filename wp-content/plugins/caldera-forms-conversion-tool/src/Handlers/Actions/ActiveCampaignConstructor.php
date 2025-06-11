<?php

namespace NinjaForms\CfConversionTool\Handlers\Actions;

use NinjaForms\CfConversionTool\Contracts\ActionConstructor;
use NinjaForms\CfConversionTool\Handlers\Actions\AbstractActionConstructor;

/**
 * Construct ActiveCampaign Action from ActiveCampaign Processor
 */
class ActiveCampaignConstructor extends AbstractActionConstructor implements ActionConstructor
{
    /**
     * Id of currently mapped Mailchimp list
     *
     * @var string
     */
    protected $connectedListId = '';

    /**
     * Mailchimp audience definition
     *
     * @var array
     */
    protected $audienceDefinition = [];

    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedAction['type'] = 'nfacds';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->constructedAction['label']='ActiveCampaign';
        $this->convertConfig();
    }

    /**
     * Convert 'config' settings from processor to action settings
     * @return void 
     */
    protected function convertConfig( ): void
    {
        $conversionLookup=[
            'apikey'=>'api_key',
            'apiurl'=>'api_url',
            'list'=>'newsletter_list',
            // 'list-id'=>'', // @todo determine how this maps,
            'form-id'=>'ACfields_hidden_form_id', // NF fixed field
            'tags'=>'ACfields_tags', // NF fixed field
            'phone'=>'ACfields_phone', // NF fixed field
            'email'=>'ACfields_email', // NF fixed field
            'last-name'=>'ACfields_last_name', // NF fixed field
            'first-name'=>'ACfields_first_name', // NF fixed field
        ];

        $cfConfig = isset($this->cFProcessor['config'])?$this->cFProcessor['config']:[];

        foreach($cfConfig as $cfConfigKey =>$cfConfigValue){

            $unprefixedKey = \str_replace('cf-activecampaign-','',$cfConfigKey);

            if(isset($conversionLookup[$unprefixedKey])){
                $nfActionSettingKey = $conversionLookup[$unprefixedKey];
            }else{
                $nfActionSettingKey = \str_replace('-','_',$unprefixedKey);
            }

            if ('optin-form'===$unprefixedKey && isset($cfConfig['cf-activecampaign-apikey'])) {
                $cfConfigValue = $cfConfig['cf-activecampaign-list'].'-'.$cfConfigValue;
            }    

            $maybeWrapped = $this->magicMergeTagLookup->convertMagicTagString($cfConfigValue);

            $this->constructedAction[$nfActionSettingKey]=$maybeWrapped;
        }


    }
   
}
