<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Axel Brand <info@brandnewmedia.biz>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['less']);
require_once (t3lib_extMgm::extPath('less') . 'lib/lessc-' . $confArr['version'] . '.inc.php');

/**
 * Plugin 'Less compiler' for the 'less' extension.
 *
 * @author	Axel Brand <info@brandnewmedia.biz>
 * @package	TYPO3
 * @subpackage	tx_less
 */
class tx_less_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_less_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_less_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'less';	// The extension key.
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf)	{
		$this->conf = $conf;
		$this->pi_USER_INT_obj = 1;

		if(!$this->conf['enable']) {
			return;
		}
		
			// find all less-files in directory
		if($this->conf['lessDir']
		&& $this->conf['recursivityLevel']) {
			$lessFiles = t3lib_div::getAllFilesAndFoldersInPath(array(), $this->conf['lessDir'], 'less', 0, $this->conf['recursivityLevel']);
			if(!empty($lessFiles)) {
				foreach($lessFiles as $lessFile) {
					$lessFileInfo = t3lib_div::split_fileref($lessFile);
					$cssFile = $lessFileInfo['path'] . $lessFileInfo['filebody'] . '.css';
					$error = $this->compileLess($lessFile, $cssFile);
					if($this->conf['debug'] && $error) {
						t3lib_div::debug($error);
					}
				}
			}
		}
		
			// less-file-array
		foreach($this->conf['lessFiles.'] as $fileRow) {
			$error = $this->compileLess($fileRow['less'], str_replace('.less', '.css', $fileRow['less']));
			if($this->conf['debug'] && $error) {
				t3lib_div::debug($error);
			}
		}
		return;
	}
	
	/**
	 * Compile the LESS-File zo CSS-File
	 * 
	 * @param	string	$lessFile: less-file
	 * @param	string	$cssFile: compiled css-file
	 * @return 	string 	Error-Message
	 */
	function compileLess($lessFile, $cssFile) {
		if($this->conf['debug']) {
			t3lib_div::debug(array('less' => $lessFile, 'css' => $cssFile));
		}
		@unlink($cssFile);
		try {
			lessc::ccompile($lessFile, $cssFile);
			return;
		}
		catch (exception $ex) {
			t3lib_div::debug("ERROR: " . $ex);
			return $ex->getMessage();
		}		
	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/less/pi1/class.tx_less_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/less/pi1/class.tx_less_pi1.php']);
}

?>
