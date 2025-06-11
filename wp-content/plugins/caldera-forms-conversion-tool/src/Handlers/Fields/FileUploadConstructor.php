<?php

namespace NinjaForms\CfConversionTool\Handlers\Fields;

use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\AbstractFieldConstructor;

/**
 * Construct Phone field
 */
class FileUploadConstructor extends AbstractFieldConstructor implements FieldConstructor
{
    /** @inheritDoc */
    protected function setType(): void
    {
        $this->constructedField['type'] = 'file_upload';
    }

    /** @inheritDoc */
    protected function setUnique(): void
    {
        $this->setLabelText();
        $this->setMultiUpload();
        $this->setMediaLibraryUpload();
        $this->setFileTypesAllowed();
        $this->setMaxSize();
        
    }

     /**
     * Convert CF "File types allowed"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setFileTypesAllowed(): void
    {

        if (isset($this->cFField['config']['allowed'])) {
            $this->constructedField['upload_types'] = (string)$this->cFField['config']['allowed'];
        }

    }

    /**
     * Convert CF "Media Library Upload"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setMediaLibraryUpload(): void
    {

        if (isset($this->cFField['config']['media_lib']) && $this->cFField['config']['media_lib'] === 1) {
            $this->constructedField['media_library'] = "1";
        }
    }

    /**
     * Convert CF "Multi upload" to NF 10 files limit
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setMultiUpload(): void
    {

        if (isset($this->cFField['config']['multi_upload']) && $this->cFField['config']['multi_upload'] === 1) {
            $this->constructedField['upload_multi_count'] = "10";
        }
    }

    /**
     * Convert CF "Button Text"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setLabelText(): void
    {

        if (!empty($this->cFField['config']['multi_upload_text'])) {
            $this->constructedField['select_files_text'] = (string)$this->cFField['config']['multi_upload_text'];
        }

    }

    /**
     * Convert CF "Max upload size"
     *
     * @param mixed $incoming
     * @return void
     */
    protected function setMaxSize(): void
    {
        $value = '';

        if (isset($this->cFField['config']['max_upload'])) {
            $value = (string)$this->cFField['config']['max_upload'];
        }

        $this->constructedField['max_file_size'] = $value;
    }
    
}
