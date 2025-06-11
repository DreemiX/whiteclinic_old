<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct checkbox field from CF checkbox field
 */
class CheckboxConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = $this->isListCheckboxType() ? 'listcheckbox' :'checkbox';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setOptions();
        if($this->cFField['type'] === "gdpr"){
            $this->setConsentSettings();
        }

    }
    //Check if it is a single checkbox type
    protected function isCheckboxType()
    {
        return isset($this->cFField['config']['option']) && is_array($this->cFField['config']['option']) && 1 >= count($this->cFField['config']['option']);
    }
    //Check if it is a list checkbox type
    protected function isListCheckboxType()
    {
        return isset($this->cFField['config']['option']) && is_array($this->cFField['config']['option']) && 1 < count($this->cFField['config']['option']);
    }

    /**
     * Set list checbox options
     */
    protected function setOptions(): void
    {
        if(!isset($this->cFField['config']['option'])){
            return;
        }
        if( $this->isCheckboxType() ){

            foreach( $this->cFField['config']['option'] as $opt => $option){
                $option = $option;
                $opt = $opt;
            }
            $this->constructedField['checked_value'] = $option['value'];
            $this->constructedField['unchecked_value'] = ''; //CF does not have unchecked value
            $this->constructedField['default_value'] = isset($this->cFField['config']['default']) && $this->cFField['config']['default'] === $opt ? 'checked' : '';
            
        } else {
            $options=[];

            foreach($this->cFField['config']['option'] as $opt => $option){
                $calc = isset($option['calc_value'])?(string)$option['calc_value']:'';
                $selected = isset($this->cFField['config']['default']) && $this->cFField['config']['default'] === $opt ? '1' : '';
                $newOption =[
                    'label'     =>  $option['label'],
                    'value'     =>  $option['value'],
                    'calc'      =>  $calc,
                    'selected'  =>  $selected
                ];

                $options[]=$newOption;
            }

            $this->constructedField['options']=$options;
        }
        
    }

    /**
     * Set checkbox as the CF GDPR field
     */
    protected function setConsentSettings(): void
    {
        //Set field as required
        $this->constructedField['required'] = "1";
        //Get Privacy Page URL
        $url = function_exists('get_privacy_policy_url') ? get_privacy_policy_url() : '';
       //Add agreement text to field label
        if( !empty( $this->cFField['config']["agreement"] ) ){
            $this->constructedField['label'] .= ' ' . $this->cFField['config']["agreement"];
        }
        //Add linked text
        if(!empty($this->cFField['config']["linked_text"])){
            $this->constructedField['label'] .= ' <a href="' . esc_url($url) . '" title="' . __('Privacy Page Link', 'cf_conversion_tool'). '">' . $this->cFField['config']["linked_text"] . '</a>';
        }

    }
}
