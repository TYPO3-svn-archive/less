<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Kay Strobach <typo3@kay-strobach.de>
*
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * @author Kay Strobach
 * @package Less
 */

class Tx_Less_Less_ParserFactory {
	/**
	 * @return tx_less_Less_ParserWrapper
	 */
	static function get() {
		// ensure no one else has loaded lessc already ;)
		if(!class_exists('lessc')) {
			$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['less']);
			include_once(t3lib_extMgm::extPath('less') . 'Contrib/lessc.inc.php');
		}
		// build instance to usage
		$lessWrapper = t3lib_div::makeInstance('tx_Less_Less_ParserWrapper');
		$lessWrapper->setLessParser(new lessc());
		return $lessWrapper;
	}
}