<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_settings'];

$strPalette = '{search_plus_legend},search_pdfMaxParsingSize';

$dc['palettes']['default'] = str_replace('{chmod_legend', $strPalette . ';{chmod_legend', $dc['palettes']['default']);

$arrFields = [
    'search_pdfMaxParsingSize'               => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['search_pdfMaxParsingSize'],
        'inputType' => 'text',
        'eval'      => ['tl_class' => 'w50', 'rgxp'=>'natural', 'nospace'=>true],
        'default'   => 0
    ],
];


$dc['fields'] = array_merge($dc['fields'], $arrFields);