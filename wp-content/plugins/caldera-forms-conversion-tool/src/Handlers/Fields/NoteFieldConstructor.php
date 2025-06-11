<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;
/**
 * Construct listselect field from CF select field
 */
class NoteFieldConstructor extends AbstractFieldConstructor implements FieldConstructor
{

    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'note';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setNote();
    }


    /**
     * Set a note to leave in the Note Field depending on the field of origin
     */
    protected function setNote( ): void
    {
        if(!isset($this->cFField['type'])){
            return;
        }
        
        if( $this->cFField['type'] === "button" ){
            if(isset($this->cFField['config']['type']) && "reset" === $this->cFField['config']['type']){
                $note = __("This was a Caldera Forms Reset button field, it won't be displayed in this Form", "cf_conversion_tool");
            } else if(isset($this->cFField['config']['type']) && "button" === $this->cFField['config']['type']){
                $note = __("This was a Caldera Forms Button button field, it won't be displayed in this Form", "cf_conversion_tool");
            }
        } else if( $this->cFField['type'] === "live_gravatar" ){
            $note = __("This was a Caldera Forms gravatar field, it won't be displayed in this Form", "cf_conversion_tool");
        } else if( $this->cFField['type'] === "color_picker" ){
            $note = __("This was a Caldera Forms Color Picker field, it won't be displayed in this Form", "cf_conversion_tool");
        } else {
            $note = __("This was a Caldera Forms field that doesn't currently have an exixting match, it won't be displayed in this Form", "cf_conversion_tool");
        }

        $this->constructedField['label'] .= __(" (This won't be displayed in this Form.)", "cf_conversion_tool");
        $this->constructedField['default'] = $note;
        $this->constructedField['value_mirror'] = $note;
    }

}
