<?php

class tx_Less_Configuration_BeRegistry implements t3lib_Singleton {
	/**
	 * @var array
	 */
	protected $values = array();

	/**
	 * @param string $name
	 * @return mixed|null
	 */
	function get($name) {
		if(array_key_exists($name, $this->values)) {
			return $this->values[$name];
		} else {
			return null;
		}
	}

	/**
	 * @param string $name
	 * @param $value
	 */
	function set($name, $value) {
		$this->values[$name] = $value;
	}

	/**
	 * @return array
	 */
	function getAll() {
		return $this->values;
	}
}