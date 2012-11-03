<?php

class tx_Less_Less_ParserFactory {
	/**
	 * @return tx_less_Less_ParserWrapper
	 */
	static function get() {
		// ensure no one else has loaded lessc already ;)
		if(!class_exists('lessc')) {
			$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['less']);
			include_once(t3lib_extMgm::extPath('less') . 'lib/lessc-' . $confArr['version'] . '.inc.php');
		}
		// build instance to usage
		$lessWrapper = t3lib_div::makeInstance('tx_Less_Less_ParserWrapper');
		$lessWrapper->setLessParser(new lessc());
		return $lessWrapper;
	}
}