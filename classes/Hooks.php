<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package search_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\SearchPlus;


class Hooks
{
	public function initializeSystemHook()
	{
		if(TL_MODE == 'BE')
		{
		}
		Environment::allowOrigins();
	}

	public function indexPageHook($strContent, $arrData, $arrSet)
	{
		if (preg_match_all('/href="(?<links>[^\"<]+\.pdf)"/i', $strContent, $matches))
		{
			Search::indexFiles($matches['links'], $arrSet);
		}
	}
}