<?php

namespace NinjaForms\CfConversionTool\Entities;

/**
 * Simple entity abstract upon which all entities are built
 *
 * Entities are classes that pass well defined data honoring contracts.
 * Single parameters and arrays, when passed into an entity, can be relied
 * upon to provide the data defined by the entity, even if the original data
 * did not fully define values.
 */
class SimpleEntity implements \JsonSerializable
{

	/**
	 * Constructs an array representation of an object
	 *
	 * Returns all properties; if properties are not set, then values defined by
	 * setter method ensures required values are set.  Undefined properties are
	 * returned as stored in object.  This enables passing of undefined
	 * properties, enabling extension of object.
	 */
	public function toArray(): array
	{
		$vars = get_object_vars($this);
		$array = [];
		
		foreach ($vars as $property => $value) {
			if (is_object($value) && is_callable([$value, 'toArray'])) {
				$value = $value->toArray();
			}
			
			if(\is_null($value)){
				$getter = 'get' . ucfirst($property);

				if (method_exists($this, $getter)) {
					$value= call_user_func([$this, $getter]);
				}
			}

			$array[$property] = $value;
		}
		return $array;
	}

	/**
	 * Sets data for json_encode
	 *
	 * @return void
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * Magic method getter for properties
	 *
	 * @param string $name
	 * @return void
	 */
	public function __get($name)
	{
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return call_user_func([$this, $getter]);
		}
		if (property_exists($this, $name)) {
			return $this->$name;
		}

		if(isset($this->$name)){
			return $this->$name;
		}
	}

	/**
	 * Magic method setter for properties
	 *
	 * Usually does not support setting undefined properties, but this class is
	 * enabling that, although it is kept as a separate command in case it must
	 * be modified.  This is because field definitions can have an undetermined
	 * collection of settings and this class will enable setting of all those
	 * values, while maintaining the ability to define sets and gets that filter
	 * values for proper types.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return Void
	 */
	public function __set($name, $value)
	{
		$setter = 'set' . ucfirst($name);
		if (method_exists($this, $setter)) {
			try{
				return call_user_func([$this, $setter], $value);		
			}
			catch(\TypeError $e){
				// Do not set invalid type
				return $this;
			}
		}

		if (property_exists($this, $name)) {
			$this->$name = $value;
			return $this;
		}

		$this->$name = $value;

		return $this;
	}
}
