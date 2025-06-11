<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Data concerning the translation of a field into a Ninja Field
 *
 */
class ActionTranslation extends SimpleEntity
{

	/**
	 * Ninja Action Id
	 * @var string
	 */
	protected $ninjaId;

	/**
	 * Origin Id
	 * 
	 * Id of the action defined in its form builder of origin
	 *
	 * @var mixed
	 */
	protected $originId;

	/**
	 * Ninja Action Definition Array
	 * 
	 * Ninja action definition as an array; this definition can build an action
	 *
	 * @var array
	 */
	protected $ninjaActionDefinition;


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
	 * Set the Ninja Action Definition
	 * 
	 * @param array $definition Ninja action definition
	 * @return SimpleEntity
	 */
	public function setNinjaActionDefinition(array $definition): SimpleEntity
	{
		$this->ninjaActionDefinition = $definition;


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
	 * Get Ninja action definition
	 * 
	 * @return array
	 */
	public function getNinjaActionDefinition():array
	{
		return isset($this->ninjaActionDefinition) ? $this->ninjaActionDefinition : [];
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
