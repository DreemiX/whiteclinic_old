<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;
/**
 * Construct HTML field from CF HTMl field
 */
class HtmlConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'html';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        if(isset($this->cFField['type']) && $this->cFField['type'] === "summary"){
            $this->setSummaryContent();
        }
    }

    /**
     * Set a note to leave in the Note Field depending on the field of origin
     */
    protected function setSummaryContent( ): void
    {
        $this->constructedField['default'] = "{all_fields_table}";
    }

    /**
     * Override setDefault if we have a calculation
     *
     * @return void
     */
    protected function setDefault(): void
    {
        if( 'calculation' === $this->cFField['type'] ) {
            $this->buildCalc();
        } else {
            parent::setDefault();
        }
    }

    /**
     * Construct the contents of an HTML field that was converted from a calculation
     * 
     * @return void
     */
    protected function buildCalc(): void
    {
        $value = '';

        if (isset($this->cFField['config']['before'])) {
            $value .= (string)$this->cFField['config']['before'] . ' ';
        }
        if (isset($this->cFField['slug'])) {
            $value .= '{calc:' . (string)$this->cFField['slug'] . '}';
        }
        if (isset($this->cFField['config']['after'])) {
            $value .= ' ' . (string)$this->cFField['config']['after'];
        }

        $this->constructedField['default'] = $value;
    }
}

