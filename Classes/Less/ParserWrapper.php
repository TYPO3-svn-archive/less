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

class tx_Less_Less_ParserWrapper {
	/**
	 * @var null|lessc
	 */
	protected $lessParser = null;

	/**
	 * @var array
	 */
	protected $overrides = array();
	/**
	 * sets the used lessparser
	 *
	 * @param lessc $object
	 */
	function setLessParser(lessc &$object) {
		$this->lessParser = $object;
	}

	/**
	 * @return lessc|null
	 */
	function getLessParser() {
		return $this->lessParser;
	}

	function setOverrides($overrides) {
		$this->overrides = t3lib_div::array_merge_recursive_overrule($this->overrides, $overrides);
	}

	/**
	 * @param $string
	 * @param null|string $name
	 * @return string
	 */
	function compile($string, $name = null) {
		$string = $this->prepareCompile($string);
		return $this->getLessParser()->compile($string);
	}

	/**
	 * @param $fname
	 * @param null|string $outFname
	 */
	function compileFile($inputFilename, $outputFilename = null) {
		if(!$this->prepareEnvironment($inputFilename)) {
			return $inputFilename;
		}

		if($outputFilename === null) {
			$outputFilename = PATH_site . 'typo3temp/Cache/Data/Less/' . basename($inputFilename);
		}
		$preparedFilename = $outputFilename . '-' . hash('crc32b', $inputFilename) . '-' . hash('crc32b', serialize($this->overrides)) . '.less';
		$cacheFilename    = $outputFilename . '-' . hash('crc32b', $inputFilename) . '-' . hash('crc32b', serialize($this->overrides)) . '.cache';
		$outputFilename   = $outputFilename . '-' . hash('crc32b', $inputFilename) . '-' . hash('crc32b', serialize($this->overrides)) . '.css';

		if(file_exists($cacheFilename)) {
			$cache = unserialize(file_get_contents($cacheFilename));
		} else {
			$cache = $preparedFilename;
			if(filemtime($preparedFilename) < filemtime($inputFilename)) {
				file_put_contents($preparedFilename, $this->prepareCompile(file_get_contents($inputFilename)));
			}
		}

		$this->getLessParser()->setImportDir(array(dirname($inputFilename)));
		$newCache = $this->getLessParser()->cachedCompile($cache);
		if((!is_array($cache)) ||  ($newCache["updated"] > $cache["updated"])) {
			file_put_contents($cacheFilename,  serialize($newCache));
			file_put_contents($outputFilename, $newCache['compiled']);
		}

		return $outputFilename;
	}

	function prepareCompile($string) {
		/**
		 * Change the initial value of a less constant before compiling the file
		 */
		if(is_array($this->overrides)) {
			foreach($this->overrides as $key => $value) {
				$string = preg_replace(
					'/@' . $key . ':(.*);/U',
					 '@' . $key . ': ' . $value . ';',
					 $string,
					 1
				);
			}
		}
		return $string;
	}
	function prepareEnvironment($fname) {
		t3lib_div::mkdir_deep(PATH_site . 'typo3temp/', 'Cache/Data/Less/');
		if(!is_dir(PATH_site . 'typo3temp/Cache/Data/Less/')) {
			throw new Exception('Can´t create cache directory PATH_site/typo3temp/Cache/Data/Less/');
		}
		if(!is_file($fname)) {
			return false;
		}
		return true;
	}
}