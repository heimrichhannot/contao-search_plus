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

$dc = &$GLOBALS['TL_DCA']['tl_search'];


$arrFields = array
(
	'mime' => array
	(
		'sql'                     => "varchar(255) NOT NULL default 'text/html'"
	),
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);