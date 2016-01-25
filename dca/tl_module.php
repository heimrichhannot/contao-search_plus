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

$dc = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Selector
 */
$dc['palettes']['__selector__'][] = 'searchPDF';

/**
 * Palettes
 */
$dc['palettes']['search'] = str_replace('searchType', 'searchType,searchPDF', $dc['palettes']['search']);

/**
 * Subpalettes
 */
$dc['subpalettes']['searchPDF'] = 'searchOrder';


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
);


$dc['fields'] = array_merge($dc['fields'], $arrFields);

