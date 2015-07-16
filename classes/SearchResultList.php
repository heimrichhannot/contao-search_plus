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


class SearchResultList
{
	protected $arrData = array();

	protected $arrResults = array();

	protected $arrCheckSums = array();

	protected $objModule;

	public function __construct(array $arrResults, $objModule = null)
	{
		$this->arrResults = $arrResults;
		$this->objModule  = $objModule;

		foreach ($this->arrResults as $key => $arrResult) {
			$objResult = new SearchResult($arrResult);
			$this->arrData[$key] = $objResult;
			$this->arrCheckSums[$key] = $objResult->checksum;
		}
	}


	public function generate()
	{
		$arrResults = array();
		$arrDuplicates = array();

		foreach ($this->arrData as $id => $objResult) {

			// check if user has access
			if (!$objResult->hasAccess()) {
				continue;
			}
			
			if (!$objResult->isValidFile())
			{
				continue;
			}
			else
			{
				// check for duplicates
				if($objResult->isDuplicate($this->arrCheckSums))
				{
					// store checksum and key of first duplicate in local array
					if(!isset($arrDuplicates[$objResult->checksum]))
					{
						$arrDuplicates[$objResult->checksum] = $id;
					}
					// add pid to first duplicate and skip the result to avoid duplicates
					else if(($idSibling = $arrDuplicates[$objResult->checksum]) !== null)
					{
						$objSibling = &$arrResults[$idSibling];
						$objSibling->pid = array_merge(!is_array($objSibling->pid) ? array($objSibling->pid) : $objSibling->pid, array($objResult->pid));
						continue;
					}
				}
			}
			
			$arrResults[$id] = $objResult;
		}

		return $arrResults;
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
		if (isset($this->arrData[$strKey])) {
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
	 * Return an array containing the result
	 *
	 * @return array of results
	 */
	public function getResults()
	{
		return $this->arrResults;
	}

}