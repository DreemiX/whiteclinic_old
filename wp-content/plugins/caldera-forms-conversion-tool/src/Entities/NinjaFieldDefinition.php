<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Field definition of a Ninja Forms field
 *
 */
class NinjaFieldDefinition extends SimpleEntity
{

	/**
	 * Provides text to be used in the <label> element
	 *
	 * Required argument -- can be empty string for hidden fields.
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * What type of field.
	 *
	 * Optional argument.
	 * Default is simple which is an <input>
	 * CF Options: simple|checkbox|advanced|dropdown
	 *
	 * @var string
	 */
	protected $type;


	/**
	 * Set the field Id
	 * @param string $id Field Id
	 * @return SimpleEntity
	 */
	public function setId(string $id): SimpleEntity
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Set the field label
	 * @param string $label Field label
	 * @return SimpleEntity
	 */
	public function setLabel(string $label): SimpleEntity
	{

		$this->label = $label;
		return $this;
	}

	/**
	 * Set the field type
	 * @param string $type Field type
	 * @return SimpleEntity
	 */
	public function setType(string $type): SimpleEntity
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * Get field id
	 * @return string
	 */
	public function getId(): string
	{
		return isset($this->id) ? $this->id : '';
	}

	/**
	 * Get field label
	 * @return string
	 */
	public function getLabel(): string
	{
		return isset($this->label) ? $this->label : '';
	}

	/**
	 * Get field type
	 * @return string
	 */
	public function getType(): string
	{
		return isset($this->type) ? $this->type : '';
	}

	/**
	 * Convert array into Ninja Field Definition
	 */
	public static function fromArray(array $items): NinjaFieldDefinition
	{
		$obj = new static();
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;
	}
}
