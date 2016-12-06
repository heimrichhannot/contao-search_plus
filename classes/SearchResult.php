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


use HeimrichHannot\Haste\Util\Url;

class SearchResult
{
	protected $arrData = array();

	/**
	 * Primary key
	 * @var string
	 */
	protected static $strPk = 'id';

	public function __construct(array $arrResult)
	{
		$this->arrData = $arrResult;
	}

	public function isPage()
	{
		return ($this->mime == 'text/html');
	}

	public function isDuplicate(array $arrCheckSums)
	{
		if(in_array($this->checksum, $arrCheckSums)) return true;

		return false;
	}

	public function isValidFile()
	{
		if($this->mime == 'text/html') return false;

        $arrParts = Url::getParametersFromUri($this->url);

        if(!isset($arrParts['file'])) return false;

		if(!is_file(TL_ROOT. '/' . $arrParts['file'])) return false;

		if(!file_exists(TL_ROOT. '/' . $arrParts['file'])) return false;

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


	/**
	 * Return the current item as associative array
	 *
	 * @return array The data record
	 */
	public function row()
	{
		return $this->arrData;
	}


	/**
	 * Set the current record from an array
	 *
	 * @param array $arrData The data record
	 *
	 * @return \Model The SearchResult object
	 */
	public function setRow(array $arrData)
	{
		foreach ($arrData as $k=>$v)
		{
			if (strpos($k, '__') !== false)
			{
				unset($arrData[$k]);
			}
		}

		$this->arrData = $arrData;
		return $this;
	}

	/**
	 * Return the name of the primary key
	 *
	 * @return string The primary key
	 */
	public static function getPk()
	{
		return static::$strPk;
	}
}