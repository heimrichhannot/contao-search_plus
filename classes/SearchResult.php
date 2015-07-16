<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package contao-latest
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\SearchPlus;


class SearchResult
{
	protected $arrData = array();

	public function __construct(array $arrResult)
	{
		$this->arrData = $arrResult;
	}

	public function isDuplicate(array $arrCheckSums)
	{
		if(in_array($this->checksum, $arrCheckSums)) return true;

		return false;
	}

	public function isValidFile()
	{
		if($this->mime == 'text/html') return false;

		if(!is_file(TL_ROOT. '/' . $this->url)) return false;

		if(!file_exists(TL_ROOT. '/' . $this->url)) return false;

		return true;
	}

	public function hasAccess()
	{
		if (\Config::get('indexProtected') && !BE_USER_LOGGED_IN)
		{
			if(!$this->protected) return true;

			$objUser = \FrontendUser::getInstance();

			if (!FE_USER_LOGGED_IN) {

				return false;
			}
			else
			{
				$groups = deserialize($this->groups);

				if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $objUser->groups)))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Set an object property
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey
	 *
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return null;
	}


	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey
	 *
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}
}