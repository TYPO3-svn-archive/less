<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] =
	'EXT:less/Classes/Hooks/t3libPageRendererRenderPreProcessHook.php:tx_less_Hooks_t3libPageRendererRenderPreProcessHook->execute';