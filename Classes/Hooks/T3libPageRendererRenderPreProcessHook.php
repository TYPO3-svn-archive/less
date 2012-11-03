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

class tx_Less_Hooks_T3libPageRendererRenderPreProcessHook  {
	/**
	 * This function iterates over the arrays and rebuild them to keep the order, as keynames may change.
	 *
	 * @param array $params
	 * @param t3lib_PageRenderer $ref
	 */
	public function execute(&$params, &$ref) {
		/**
		 * iterate $params['cssFiles']
		 */
		$cssFilesArray = array();
		foreach($params['cssFiles'] as $cssFile => $cssFileSettings) {
			// @todo handle extension added files with prepended backpath correctly!

			$currentFile = t3lib_div::getFileAbsFileName($cssFile);
			$pathInfo    = pathinfo($currentFile);

			if($pathInfo['extension'] === 'less') {
				$less = tx_Less_Less_ParserFactory::get();
				$less->setOverrides($this->getOverrides());
				$compiledLessFile = $less->compileFile($currentFile);
				$compiledLessFile = $this->fixPath($compiledLessFile);
				$cssFilesArray[$compiledLessFile] = $cssFileSettings;
			} else {
				$cssFilesArray[$cssFile] = $cssFileSettings;
			}
		}
		$params['cssFiles'] = $cssFilesArray;

		/**
		 * iterate $params['cssInline']
		 */
	}
	protected function fixPath($compiledLessFile) {
		if(TYPO3_MODE === 'FE') {
			//ugly fix, but it's needed to fix a path problem
			$compiledLessFile = str_replace(PATH_site, '', $compiledLessFile);
		} elseif(TYPO3_MODE === 'BE') {
			 //@todo make this ^^ work in be too
			die($compiledLessFile);
		}
		return $compiledLessFile;
	}
	protected function getOverrides() {
		$overrides = array();

		if(TYPO3_MODE === 'FE') {
			if((array_key_exists('plugin.',    $GLOBALS['TSFE']->tmpl->setup))
			&& (array_key_exists('tx_less.',   $GLOBALS['TSFE']->tmpl->setup['plugin.']))
			&& (array_key_exists('overrides.', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_less.']))) {
				// iterate of cObjects and render them to pass them into the vars
				foreach($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_less.']['overrides.'] as $varName => $varCObj) {
					if(substr($varName, -1, 1) !== '.') {
						$cObj = t3lib_div::makeInstance('tslib_cObj');
						$overrides[$varName] = $cObj->cObjGetSingle($varCObj, $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_less.']['overrides.'][$varName . '.']);
					}
				}
			}
			//
		} elseif(TYPO3_MODE === 'BE') {
			$configManager = t3lib_div::makeInstance('tx_less_Configuration_BeRegistry');
			$overrides = $configManager->getAll();
		}
		return $overrides;
	}
}