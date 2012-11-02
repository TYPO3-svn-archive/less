<?php

class tx_less_Hooks_t3libPageRendererRenderPreProcessHook  {
	/**
	 * This function iterates over the arrays and rebuild them to keep the order, as keynames may change.
	 *
	 * @param array $params
	 * @param t3lib_PageRenderer $ref
	 */
	function execute(&$params, &$ref) {
		/**
		 * iterate $params['cssFiles']
		 */
		$cssFilesArray = array();
		foreach($params['cssFiles'] as $cssFile => $cssFileSettings) {
			$currentFile = t3lib_div::getFileAbsFileName($cssFile);
			$pathInfo    = pathinfo($currentFile);
			if($pathInfo['extension'] === 'less') {
				$less = tx_less_Less_ParserFactory::get();
				if(TYPO3_MODE === 'FE') {
					//@todo inject dynamic values ;)
					if((array_key_exists('plugin.',    $GLOBALS['TSFE']->tmpl->setup))
					&& (array_key_exists('tx_less.',   $GLOBALS['TSFE']->tmpl->setup['plugin.']))
					&& (array_key_exists('overrides.', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_less.']))) {
						$overrides = array();
						// iterate of cObjects and render them to pass them into the vars
						foreach($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_less.']['overrides.'] as $varName => $varCObj) {
							if(substr($varName, -1, 1) !== '.') {
								$cObj = t3lib_div::makeInstance('tslib_cObj');
								$overrides[$varName] = $cObj->cObjGetSingle($varCObj, $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_less.']['overrides.'][$varName . '.']);
							}
						}
						$less->setOverrides($overrides);
					}
					//
				}
				$compiledLessFile = $less->compileFile($currentFile);
				if(TYPO3_MODE === 'FE') {
					//ugly fix, but it's needed to fix a path problem
					$compiledLessFile = str_replace(PATH_site, '', $compiledLessFile);
				} elseif(TYPO3_MODE === 'BE') {
					 //@todo make this ^^ work in be too
				}
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
}
