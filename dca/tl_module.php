<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package search_plus
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Selector
 */
$arrDca['palettes']['__selector__'][] = 'searchPDF';

/**
 * Palettes
 */
$arrDca['palettes']['search'] = str_replace('searchType', 'searchType,searchPDF', $arrDca['palettes']['search']);
$arrDca['palettes']['search'] = str_replace('rootPage', 'rootPage,pageMode,filterPages,addPageDepth', $arrDca['palettes']['search']);

/**
 * Subpalettes
 */
$arrDca['subpalettes']['searchPDF'] = 'searchOrder';


/**
 * Fields
 */

$arrFields = array
(
	'searchPDF'  => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['searchPDF'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'eval'      => array('submitOnChange' => true, 'tl_class' => 'clr'),
		'sql'       => "char(1) NOT NULL default ''",
	),
	'searchOrder' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['searchOrder'],
		'exclude'   => true,
		'default'   => 'default',
		'options'   => array('default', 'pagesFirst', 'filesFirst'),
		'reference' => $GLOBALS['TL_LANG']['tl_module']['references']['searchOrder'],
		'inputType' => 'select',
		'eval'      => array('tl_class' => 'clr'),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'pageMode'         => array(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['pageMode'],
		'exclude'   => true,
		'inputType' => 'radio',
		'options'   => array('exclude', 'include'),
		'default'   => 'exclude',
		'reference' => &$GLOBALS['TL_LANG']['tl_module'],
		'eval'      => array('tl_class' => 'w50'),
		'sql'       => "varchar(32) NOT NULL default 'exclude'",
	),
	'filterPages'   => $arrDca['fields']['pages'],
	'addPageDepth'          => array(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['addPageDepth'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'default'   => true,
		'eval'      => array('tl_class' => 'w50 clr'),
		'sql'       => "char(1) NOT NULL default '1'",
	)
);


$arrDca['fields'] += $arrFields;

$arrDca['fields']['filterPages']['label'] = &$GLOBALS['TL_LANG']['tl_module']['filterPages'];
$arrDca['fields']['filterPages']['eval']['mandatory'] = false;
$arrDca['fields']['filterPages']['eval']['tl_class'] = 'long clr';