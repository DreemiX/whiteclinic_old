<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Data concerning the translation of a field into a Ninja Field
 *
 */
class FieldTranslation extends SimpleEntity
{

	/**
	 * Ninja Field Id
	 * @var string
	 */
	protected $ninjaId;

	/**
	 * Origin Id
	 * 
	 * Id of the field defined in its form builder of origin
	 *
	 * @var mixed
	 */
	protected $originId;

	/**
	 * Ninja Field Definition Array
	 * 
	 * Ninja field definition as an array; this definition can build a field
	 *
	 * @var array
	 */
	protected $ninjaFieldDefinition;


	/**
	 * Set the Ninja field Id
	 * @param string $id Field Id
	 * @return SimpleEntity
	 */
	public function setNinjaId(string $id): SimpleEntity
	{
		$this->ninjaId = $id;
		return $this;
	}

	/**
	 * Set the Origin field Id
	 * @param string $id Field Id
	 * @return SimpleEntity
	 */
	public function setOriginId(string $id): SimpleEntity
	{
		$this->originId = $id;
		return $this;
	}

	/**
	 * Set the Ninja Field Definition
	 * @param array $definition Ninja field definition
	 * @return SimpleEntity
	 */
	public function setNinjaFieldDefinition(array $definition): SimpleEntity
	{
		$this->ninjaFieldDefinition = $definition;
		return $this;
	}


	/**
	 * Get Ninja field id
	 * @return string
	 */
	public function getNinjaId(): string
	{
		return isset($this->ninjaId) ? $this->ninjaId : '';
	}
	
	/**
	 * Get Ninja field id
	 * @return string
	 */
	public function getOriginId(): string
	{
		return isset($this->originId) ? $this->originId : '';
	}
	
	/**
	 * Get Ninja field definition
	 * @return array
	 */
	public function getNinjaFieldDefinition():array
	{
		return isset($this->ninjaFieldDefinition) ? $this->ninjaFieldDefinition : [];
	}


	/** @inheritdoc */
	public static function fromArray(array $items): SimpleEntity
	{
		$obj = new static();
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;
	}
}
