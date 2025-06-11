<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct <hr /> field
 */
class StarRatingConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'starrating';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setStarNumber();
    }

    /**
     * Set number of Stars in the Star rating field
     */
    protected function setStarNumber(): void
    {
        $value = '5';

        if (isset($this->cFField['config']['number'])) {
            $value = (string)$this->cFField['config']['number'];
        }

        $this->constructedField['number_of_stars'] = $value;
    }
}
