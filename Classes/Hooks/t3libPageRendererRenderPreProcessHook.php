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
				$compiledLessFile = $less->compileFile($currentFile);
				//@todo ugly fix, but it's needed to fix a path problen in the frontend, make this work in be too
				$compiledLessFile = str_replace(PATH_site, '', $compiledLessFile);
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
