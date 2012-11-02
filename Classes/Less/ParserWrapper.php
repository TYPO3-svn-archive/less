<?php

class tx_less_Less_ParserWrapper {
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
	function compileFile($fname, $outFname = null) {
		/**
		 * build filenames
		 */
		$this->getLessParser()->setImportDir(array(dirname($fname)));
		if($outFname === null) {
			$outFname = PATH_site . 'typo3temp/Cache/Data/Less/' . basename($fname);
		}
		$outFname = $outFname . '-' . crc32($fname) . '-' . crc32(serialize($this->overrides)) . '.css';
		/**
		 * ensure directory exists
		 */
		t3lib_div::mkdir_deep(PATH_site . 'typo3temp/', 'Cache/Data/Less/');
		file_put_contents($outFname, $this->compile(file_get_contents(t3lib_div::getFileAbsFileName($fname))));
		return $outFname;
	}
	function setOverrides($overrides) {
		$this->overrides = t3lib_div::array_merge_recursive_overrule($this->overrides, $overrides);
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
}