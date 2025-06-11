<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;
/**
 * Construct number field from CF number field
 */
class NumberConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'number';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setMin();
        $this->setMax();
        $this->setStep();    
    }


   /**
     * Convert CF "min" number option to NF "num_min" number option
     */
    protected function setMin(): void
    {
        $value = '';

        if (isset($this->cFField['config']['min'])) {
            $value = (string)$this->cFField['config']['min'];
        }

        $this->constructedField['num_min'] = $value;
        $this->constructedField['number_min'] = $value;
    }

     /**
     * Convert CF "max" number option to NF "num_max" number option
     */
    protected function setMax(): void
    {
        $value = '';

        if (isset($this->cFField['config']['max'])) {
            $value = (string)$this->cFField['config']['max'];
        }

        $this->constructedField['num_max'] = $value;
        $this->constructedField['number_max'] = $value;
    }

     /**
     * Convert CF "step" number option to NF "num_step" number option
     */
    protected function setStep(): void
    {
        $value = '';

        if (isset($this->cFField['config']['step'])) {
            $value = (string)$this->cFField['config']['step'];
        }

        $this->constructedField['num_step'] = $value;
        $this->constructedField['number_step'] = $value;
    }
}
