<?php
/***************************************************************
*  Copyright notice
*
*  (c) Kay Strobach
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_Less_Configuration_BeRegistry implements t3lib_Singleton {
	/**
	 * @var array
	 */
	protected $values = array();

	/**
	 * get an override value
	 *
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
	 * set an override value
	 *
	 * @param string $name
	 * @param $value
	 */
	function set($name, $value) {
		$this->values[$name] = $value;
	}

	/**
	 * get all override values
	 *
	 * @return array
	 */
	function getAll() {
		return $this->values;
	}
}