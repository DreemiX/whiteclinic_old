<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Conditional When definition of a Ninja Forms
 *
 */
class ConditionalThenDefinition extends SimpleEntity
{

	/**
	 * Reference key
	 *
	 * @var string
	 */
	protected $key = '';

	/**
	 * Conditional trigger
	 *
	 * @var string
	 */
	protected $trigger = 'equal';

	/**
	 * Evaluation value
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Comparing type
	 *
	 * @var string
	 */
	protected $type = 'field';

	/**
	 * Conditional model
	 *
	 * @var string
	 */
	protected $modelType = 'then';


	/**
	 * Set the field key
	 *
	 * @param string $key
	 * @return SimpleEntity
	 */
	public function setKey(string $key): SimpleEntity
	{
		$this->key = $key;
		return $this;
	}

	/**
	 * Set the trigger
	 *
	 * @param string $trigger
	 * @return SimpleEntity
	 */
	public function setTrigger(string $trigger): SimpleEntity
	{
		$this->trigger = $trigger;

		return $this;
	}

	/**
	 * Set the value of value
	 *
	 * @return  SimpleEntity
	 */
	public function setValue($value): SimpleEntity
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * Set the value of type
	 *
	 * @return  SimpleEntity
	 */
	public function setType($type): SimpleEntity
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Set the value of type
	 *
	 * @return  SimpleEntity
	 */
	public function setModelType($modelType): SimpleEntity
	{
		$this->modelType = 'then';

		return $this;
	}


	/**
	 * Get field key
	 * @return string
	 */
	public function getKey(): string
	{
		return isset($this->key) ? $this->key : '';
	}

	/**
	 * Get field trigger
	 * @return string
	 */
	public function getTrigger(): string
	{
		return isset($this->trigger) ? $this->trigger : '';
	}

	/**
	 * Get the value of value
	 */
	public function getValue()
	{
		return $this->value;
	}


	/**
	 * Get the value of type
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * Get the value of modelType
	 */
	public function getModelType(): string
	{
		return $this->modelType;
	}

	/**
	 * Convert array into Ninja Field Definition
	 */
	public static function fromArray(array $items): ConditionalThenDefinition
	{
		$obj = new static();
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;
	}
}
