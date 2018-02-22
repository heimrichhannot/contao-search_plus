<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package search_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


/**
 * Settings Default values
 */

$GLOBALS['TL_CONFIG']['search_enablePdfIndexing'] = true;
$GLOBALS['TL_CONFIG']['search_pdfMaxParsingSize'] = 0;

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['application']['search'] = '\\HeimrichHannot\\SearchPlus\\ModuleSearchPlus';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['indexPage'][] = array('\\HeimrichHannot\\SearchPlus\\Hooks', 'indexPageHook');


/**
 * Javascript
 */
if(TL_MODE == 'BE')
{
	$GLOBALS['TL_JAVASCRIPT']['searchplus-be'] =  'system/modules/search_plus/assets/js/searchplus_be.js|static';
}

/**
 * Css
 */

if(TL_MODE == 'BE')
{
	$GLOBALS['TL_CSS']['searchplus-be'] = 'system/modules/search_plus/assets/css/searchplus_be.css';
}

/**
 * Maintenance
 */
if(($idx = array_search('RebuildIndex' ,$GLOBALS['TL_MAINTENANCE'])) !== false)
{
	$GLOBALS['TL_MAINTENANCE'][$idx] = 'HeimrichHannot\SearchPlus\Backend\RebuildIndex';
}