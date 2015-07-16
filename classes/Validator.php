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

namespace HeimrichHannot\SearchPlus;

class Validator extends \Validator
{

	public static function isValidPDF($objFile)
	{
		if($objFile->mime != $GLOBALS['TL_MIME']['pdf'][0])
		{
			return false;
		}

		return true;
	}

	public static function hasAccessToSearchResult(array $arrResult)
	{
		if (\Config::get('indexProtected') && !BE_USER_LOGGED_IN)
		{
			if(!$arrResult['protected']) return true;

			$objUser = \FrontendUser::getInstance();

			if (!FE_USER_LOGGED_IN) {

				return false;
			}
			else
			{
				$groups = deserialize($arrResult['groups']);

				if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $objUser->groups)))
				{
					return false;
				}
			}
		}

		return true;
	}
}