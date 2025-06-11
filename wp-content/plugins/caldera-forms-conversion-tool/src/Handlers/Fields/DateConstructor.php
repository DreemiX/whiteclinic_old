<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct email field from CF email field
 */
class DateConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'date';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setDateFormat();
    }

     /**
     * Convert CF "Date Format"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setDateFormat(): void
    {

        if (!empty($this->cFField['config']['format'])) {
            $this->constructedField['date_format'] = strtoupper( (string)$this->cFField['config']['format'] );
        }

    }
}
