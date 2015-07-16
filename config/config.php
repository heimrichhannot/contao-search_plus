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
 * Front end modules
 */
$GLOBALS['FE_MOD']['application']['search'] = '\\HeimrichHannot\\SearchPlus\\ModuleSearchPlus';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['indexPage'][] = array('\\HeimrichHannot\\SearchPlus\\Hooks', 'indexPageHook');
