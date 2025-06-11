<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Action definition of a Ninja Forms action
 *
 */
class ActionDefinition extends SimpleEntity
{


	/**
	 * What type of action
	 *
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Provides text to be used in the <label> element
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * Is the action active
	 *
	 * '1' is active, '0' is inactive
	 * @var string
	 */
	protected $active;


	/**
	 * Set the action type
	 * 
	 * @param string $type action type
	 * @return SimpleEntity
	 */
	public function setType(string $type): SimpleEntity
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * Set the action label
	 * @param string $label action label
	 * @return SimpleEntity
	 */
	public function setLabel(string $label): SimpleEntity
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * Set active setting
	 *
	 * @param  string  $active  '1' is active, '0' is inactive
	 *
	 * @return  self
	 */
	public function setActive(string $active)
	{
		$this->active = $active;

		return $this;
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
	 * Get field label
	 * @return string
	 */
	public function getLabel(): string
	{
		return isset($this->label) ? $this->label : '';
	}




	/**
	 * Get active setting
	 *
	 * @return  string
	 */
	public function getActive()
	{
		return isset($this->active) ? $this->active : '0';
	}


	/**
	 * Convert array into Ninja Field Definition
	 */
	public static function fromArray(array $items): ActionDefinition
	{
		$obj = new static();
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;
	}
}
