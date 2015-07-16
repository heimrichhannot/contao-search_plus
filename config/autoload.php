<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Tests
	'SearchPDFTest'                              => 'system/modules/search_plus/tests/SearchPDFTest.php',

	// Modules
	'HeimrichHannot\SearchPlus\ModuleSearchPlus' => 'system/modules/search_plus/modules/ModuleSearchPlus.php',

	// Classes
	'HeimrichHannot\SearchPlus\SearchResultList' => 'system/modules/search_plus/classes/SearchResultList.php',
	'HeimrichHannot\SearchPlus\Search'           => 'system/modules/search_plus/classes/Search.php',
	'HeimrichHannot\SearchPlus\Hooks'            => 'system/modules/search_plus/classes/Hooks.php',
	'HeimrichHannot\SearchPlus\SearchResult'     => 'system/modules/search_plus/classes/SearchResult.php',
	'HeimrichHannot\SearchPlus\Validator'        => 'system/modules/search_plus/classes/Validator.php',
));
