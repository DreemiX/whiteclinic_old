<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;
/**
 * Form entity providing the blueprint for translating a form
 */
class FormTranslation extends SimpleEntity
{

	/**
	 * Array of form settings
	 *
	 * @var array
	 */
	protected $formSettings = [];
	
	/**
	 * Collection of field translations (as arrays)
	 * 
	 *  @var array 
	 */
	protected $fieldTranslationCollection=[];

	/**
	 * Collection of action translations (as arrays)
	 *
	 * @var array
	 */
	protected $actionTranslationCollection=[];

	/**
	 * Set form settings
	 * 
	 * @param array $settings
	 * @return FormTranslation
	 */
	public function setFormSettings(array $settings): FormTranslation
	{
		$this->formSettings = $settings;
		return $this;
	}
	/**
	 * Set specific form setting
	 * 
	 * @param string $settingKey
	 * @param mixed $value
	 * @return FormTranslation
	 */
	public function setFormSetting(string $settingKey, $value): FormTranslation
	{
		$this->formSettings[$settingKey] = $value;
		return $this;
	}

	/**
	 * Set the Field Translation collection
	 *
	 * @param array $fieldTranslations
	 * @return FormTranslation
	 */
	public function setFieldTranslationCollection(array $fieldTranslations): FormTranslation
	{
		$this->fieldTranslationCollection = $fieldTranslations;

		return $this;
	}

	/**
	 * Set the action Translation collection
	 *
	 * @param array $actionTranslations
	 * @return FormTranslation
	 */
	public function setActionTranslationCollection(array $actionTranslations): FormTranslation
	{
		$this->actionTranslationCollection = $actionTranslations;

		return $this;
	}

	/**
	 * Get form settings
	 * @return string
	 */
	public function getFormSettings(): array
	{
		return isset($this->formSettings) ? $this->formSettings : [];
	}

	/**
	 * Get field translation collection
	 * @return array
	 */
	public function getFieldTranslationCollection(): array
	{
		return $this->fieldTranslationCollection;
	}

	/**
	 * Get action translation collection
	 * @return array
	 */
	public function getActionTranslationCollection(): array
	{
		return $this->actionTranslationCollection;
	}

	/**
	 * Return simple entity constructed from array of FormFields
	 * @param array $items
	 * @return FormBuilderEntity
	 */
	public static function fromArray(array $items): FormTranslation
	{
		$obj = new static();
	
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;


		return $obj;
	}
}
