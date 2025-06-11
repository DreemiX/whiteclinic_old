<?php

namespace NinjaForms\CfConversionTool\Factories;

use NinjaForms\CfConversionTool\Contracts\FieldConstructorFactory as ContractsFieldConstructorFactory;
use NinjaForms\CfConversionTool\Contracts\FieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\CheckboxConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\HiddenConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\HtmlConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\ListRadioConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\ListSelectConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\PasswordTextboxConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\SubmitConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\TextboxConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\EmailFieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\NumberConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\TextareaConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\HrConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\StarRatingConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\PhoneConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\FileUploadConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\DateConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\NoteFieldConstructor;
use NinjaForms\CfConversionTool\Handlers\Fields\UtmFieldConstructor;

class FieldConstructorFactory implements ContractsFieldConstructorFactory
{
    /** @inheritDoc */
    public function getFieldConstructor(array $field): FieldConstructor
    {

        $fieldString = $this->determineFieldConstructor($field);

        $fieldConstructor = $this->makeFieldConstructor($fieldString);

        return $fieldConstructor;
    }
    /**
     * Convert Cf field types into NF field types
     *
     * @param string $incomingFieldType
     * @param array $field
     * @return string
     */
    protected function determineFieldConstructor(array $field): string
    {

        $incomingFieldType = $field['type'];

        switch ($incomingFieldType) {

            case 'radio':
            case 'toggle_switch':
                $fieldConstructor = 'radio';
                break;

            case 'checkbox':
            case 'gdpr':
                $fieldConstructor = 'checkbox';
                break;

            case 'dropdown':
            case 'states':
            case 'filtered_select2':
                $fieldConstructor = 'listselect';
                break;

            case 'button':
                //Convert to Submit button if Submit type is set, otherwise leave a note Field by default
                $fieldConstructor =  isset($field['config']['type']) && 'submit' === $field['config']['type'] ? 'submit' : 'note';
                break;

            case 'password':
                $fieldConstructor = 'password';
                break;

            case 'html':
            case 'calculation':
            case 'summary':
                $fieldConstructor = 'html';
                break;

            case 'hidden':
                $fieldConstructor = 'hidden';
                break;

            case 'email':
                $fieldConstructor = 'email';
                break;

            case 'number':
            case 'range_slider':
                $fieldConstructor = 'number';
                break;

            case 'paragraph':
            case 'wysiwyg':
                $fieldConstructor = 'textarea';
                break;

            case 'text';
            case 'url';
                $fieldConstructor = 'textbox';
                break;

            case 'section_break':
                $fieldConstructor = 'hr';
                break;
            
            case 'star_rating':
                $fieldConstructor = 'starrating';
                break;

            case 'phone':
            case 'phone_better':
                $fieldConstructor = 'phone';
                break;

            case 'file':
            case 'cf2_file':
                $fieldConstructor = 'file_upload';
                break;

            case 'date_picker':
                $fieldConstructor = 'date';
                break;

            case 'live_gravatar':
            case 'color_picker':
                $fieldConstructor = 'note';
                break;

            case 'utm':
                $fieldConstructor = 'user-analytics-utm-content';
                break;

            default:
                $fieldConstructor = 'unknown';
        }

        return $fieldConstructor;
    }


    /** @inheritDoc */
    public function makeFieldConstructor(string $fieldConstructorType): FieldConstructor
    {
        switch ($fieldConstructorType) {
            case 'radio':
                $return = new ListRadioConstructor();
                break;
            case 'checkbox':
                $return = new CheckboxConstructor();
                break;
            case 'listselect':
                $return = new ListSelectConstructor();
                break;
            case 'html':
                $return = new HtmlConstructor();
                break;
            case 'password':
                $return = new PasswordTextboxConstructor();
                break;
            case 'hidden':
                $return = new HiddenConstructor();
                break;
            case 'submit':
                $return = new SubmitConstructor();
                break;
            case 'email':
                $return = new EmailFieldConstructor();
                break;
            case 'number':
                $return = new NumberConstructor();
                break;
            case 'textarea':
                $return = new TextareaConstructor();
                break;
            case 'hr':
                $return = new  HrConstructor();
                break;
            case 'starrating':
                $return = new  StarRatingConstructor();
                break;
            case 'phone':
                $return = new  PhoneConstructor();
                break;
            case 'file_upload':
                $return = new  FileUploadConstructor();
                break;
            case 'date':
                $return = new  DateConstructor();
                break;
            case 'note':
                $return = new  NoteFieldConstructor();
                break;
            case 'user-analytics-utm-content':
                $return = new  UtmFieldConstructor();
                break;
            case 'textbox':
            default:
                $return = new TextboxConstructor();
                break;
        }


        return $return;
    }
}
