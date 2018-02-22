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

$dc['palettes']['default'] = str_replace(
    '{chmod_legend',
    '{search_plus_legend},search_enablePdfIndexing;{chmod_legend',
    $dc['palettes']['default']
);

$dc['palettes']['__selector__'][]             = 'search_enablePdfIndexing';
$dc['subpalettes']['search_enablePdfIndexing'] = 'search_pdfMaxParsingSize';


$arrFields = [
    'search_enablePdfIndexing'  => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['search_enablePdfIndexing'],
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => '', 'submitOnChange' => true]
    ],
    'search_pdfMaxParsingSize' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['search_pdfMaxParsingSize'],
        'inputType' => 'text',
        'eval'      => ['tl_class' => 'w50', 'rgxp' => 'natural', 'nospace' => true]
    ],
];

$dc['fields'] = array_merge($dc['fields'], $arrFields);