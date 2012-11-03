<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] =
	'EXT:less/Classes/Hooks/T3libPageRendererRenderPreProcessHook.php:tx_Less_Hooks_T3libPageRendererRenderPreProcessHook->execute';