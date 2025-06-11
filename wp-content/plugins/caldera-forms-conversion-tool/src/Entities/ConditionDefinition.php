<?php

namespace NinjaForms\CfConversionTool\Entities;

use NinjaForms\CfConversionTool\Entities\SimpleEntity;

/**
 * Conditional When definition of a Ninja Forms
 *
 */
class ConditionDefinition extends SimpleEntity
{

	/**
	 * Display conditional as collapsed
	 *
	 * @var string
	 */
	protected $collapsed = '';

	/**
	 * Process this conditional?
	 *
	 * @var string
	 */
	protected $process = '1';

	/**
	 * Connection on conditions
	 *
	 * @var string
	 */
	protected $connector = 'all';

	/**
	 * Conditional When statements
	 *
	 * @var array
	 */
	protected $when = [];

	/**
	 * Conditional Then statements
	 *
	 * @var array
	 */
	protected $then = [];

	/**
	 * Conditional Else statements
	 *
	 * @var array
	 */
	protected $else = [];


	/**
	 * Get display conditional as collapsed
	 *
	 * @return  string
	 */
	public function getCollapsed(): string
	{
		return $this->collapsed;
	}

	/**
	 * Set display conditional as collapsed
	 * 
	 * Set to (string)'0' to disable display
	 *
	 * @param  string  $collapsed  Display conditional as collapsed
	 *
	 * @return  self
	 */
	public function setCollapsed(string $collapsed): SimpleEntity
	{
		if ('1' == $collapsed) {

			$this->collapsed = '1';
		} else {
			$this->collapsed = '';
		}

		return $this;
	}

	/**
	 * Get process this conditional?
	 *
	 * @return  string
	 */
	public function getProcess(): string
	{
		return $this->process;
	}

	/**
	 * Set process this conditional
	 * 
	 * Set to (string)'0' to disable conditional
	 *
	 * @param  string  $process  Process this conditional?
	 *
	 * @return  self
	 */
	public function setProcess(string $process): SimpleEntity
	{
		if ('0' == $process) {

			$this->process = '0';
		} else {
			$this->process = '1';
		}

		return $this;
	}

	/**
	 * Get connection on conditions
	 *
	 * @return  string
	 */
	public function getConnector(): string
	{
		return $this->connector;
	}

	/**
	 * Set connection on conditions
	 *
	 * @param  string  $connector  Connection on conditions
	 *
	 * @return  self
	 */
	public function setConnector(string $connector): SimpleEntity
	{
		$this->connector = $connector;

		return $this;
	}

	/**
	 * Get conditional When statements
	 *
	 * @return  array
	 */
	public function getWhen(): array
	{
		return $this->when;
	}

	/**
	 * Set all conditional When statements
	 *
	 * @param  array  $when  Conditional When statements
	 *
	 * @return  self
	 */
	public function setWhen(array $when): SimpleEntity
	{
		$this->when = $when;

		return $this;
	}
	/**
	 * Add a conditional When statements
	 *
	 * @param  array  $when  Conditional When statement
	 *
	 * @return  self
	 */
	public function addWhen(array $when): SimpleEntity
	{
		$this->when[] = $when;

		return $this;
	}



	/**
	 * Get conditional Then statement
	 *
	 * @return  array
	 */
	public function getThen(): array
	{
		return $this->then;
	}

	/**
	 * Set all conditional Then statements
	 *
	 * @param  array  $then  Conditional Then statements
	 *
	 * @return  self
	 */
	public function setThen(array $then): SimpleEntity
	{
		$this->then = $then;

		return $this;
	}
	/**
	 * Add a conditional Then statement
	 *
	 * @param  array  $then  Conditional Then statement
	 *
	 * @return  self
	 */
	public function addThen(array $then): SimpleEntity
	{
		$this->then[] = $then;

		return $this;
	}
	/**
	 * Get conditional Else statements
	 *
	 * @return  array
	 */
	public function getElse(): array
	{
		return $this->else;
	}

	/**
	 * Set all conditional Else statements
	 *
	 * @param  array  $else  Conditinal Else statements
	 *
	 * @return  self
	 */
	public function setElse(array $else): SimpleEntity
	{
		$this->else = $else;

		return $this;
	}
	/**
	 * Add a conditional else statement
	 *
	 * @param  array  $else  Conditional Else statement
	 *
	 * @return  self
	 */
	public function addElse(array $else): SimpleEntity
	{
		$this->else[] = $else;

		return $this;
	}
	/**
	 * Convert array into Ninja Field Definition
	 */
	public static function fromArray(array $items): ConditionDefinition
	{
		$obj = new static();
		foreach ($items as $property => $value) {

			$obj = $obj->__set($property, $value);
		}
		return $obj;
	}
}
