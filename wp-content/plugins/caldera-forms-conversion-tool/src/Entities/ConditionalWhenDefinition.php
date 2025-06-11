<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Conditional When definition of a Ninja Forms
 *
 */
class ConditionalWhenDefinition extends SimpleEntity
{

	/**
	 * Conditional connector
	 *
	 * @var string
	 */
	protected $connector = 'AND';

	/**
	 * Reference key
	 *
	 * @var string
	 */
	protected $key = '';

	/**
	 * Conditional comparator
	 *
	 * @var string
	 */
	protected $comparator = 'equal';

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
	protected $modelType = 'when';

	/**
	 * Set connector
	 *
	 * @param string $connector
	 * @return SimpleEntity
	 */
	public function setConnector(string $connector): SimpleEntity
	{
		$this->connector = $connector;
		return $this;
	}

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
	 * Set the comparator
	 *
	 * @param string $comparator
	 * @return SimpleEntity
	 */
	public function setComparator(string $comparator): SimpleEntity
	{
		$this->comparator = $comparator;

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
		$this->modelType = 'when';

		return $this;
	}



	/**
	 * Get field connector
	 * @return string
	 */
	public function getConnector(): string
	{
		return isset($this->connector) ? $this->connector : '';
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
	 * Get field comparator
	 * @return string
	 */
	public function getComparator(): string
	{
		return isset($this->comparator) ? $this->comparator : '';
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
	public static function fromArray(array $items): ConditionalWhenDefinition
	{
		$obj = new static();
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;
	}
}
