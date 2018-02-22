<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrLang = &$GLOBALS['TL_LANG']['tl_settings'];

$arrLang['search_plus_legend'] = 'Search Plus Einstellungen';

$arrLang['search_pdfMaxParsingSize'] = [
    'Maximum pdf size for pdf parser',
    'The maximum file size of a pdf that can be processed by the pdf parser, to prevent memory overflow or process timeout. Specify in KiB. 0 means no file size limit. 1024KiB = 1MB'
];
$arrLang['search_enablePdfIndexing'] = [
    'Enable PDF-Indexing',
    'Enable or disable the indexing of pdf files for search.'
];
