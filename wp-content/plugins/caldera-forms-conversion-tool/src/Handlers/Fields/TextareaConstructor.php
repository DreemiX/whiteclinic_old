<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct textarea field from CF paragraph and rich text fields
 */
class TextareaConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'textarea';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setRichTextEditor();
    }

    /**
     * Convert CF "wysisyg" field in  paragraph field with Rich editor set
     */
    protected function setRichTextEditor(): void
    {

        if (isset($this->cFField['type']) && $this->cFField['type'] === "wysiwyg") {
            $this->constructedField["textarea_rte"] = "1";
            $this->constructedField["disable_rte_mobile"] = "";
            $this->constructedField["textarea_media"] = "1";
        }

    }
}
