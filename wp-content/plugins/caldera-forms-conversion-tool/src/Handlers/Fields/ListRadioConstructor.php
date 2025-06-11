<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;
/**
 * Construct listradio field from CF radio field
 */
class ListRadioConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'listradio';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setOptions();
    }


    /**
     * Set list radio options
     */
    protected function setOptions( ): void
    {
        if(!isset($this->cFField['config']['option'])){
            return;
        }

        $options=[];

        foreach($this->cFField['config']['option'] as $opt => $option){

            $calc = isset($option['calc_value'])?(string)$option['calc_value']:'';
            $selected = isset($this->cFField['config']['default']) && $this->cFField['config']['default'] === $opt ? '1' : '';
            $newOption = [
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
