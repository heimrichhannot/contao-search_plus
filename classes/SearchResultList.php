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


class SearchResultList implements \ArrayAccess, \Countable, \IteratorAggregate
{
	protected $arrResults = array();

	protected $arrCheckSums = array();

	protected $objModule;

	/**
	 * Current index
	 * @var integer
	 */
	protected $intIndex = -1;

	/**
	 * Create a new SearchResultList
	 *
	 * @param array  $arrModels An array of models
	 * @param object $objModule The module
	 *
	 */
	public function __construct(array $arrResults, $objModule = null)
	{
		$arrResults = array_values($arrResults);

		foreach ($arrResults as $key => $arrResult)
		{
			$objResult = new SearchResult($arrResult);
			$this->arrResults[$key] = $objResult;
			$this->arrCheckSums[$key] = $objResult->checksum;
		}

		$this->objModule  = $objModule;
		$this->filter();
	}


	protected function filter()
	{
		$arrResults = array();
		$arrDuplicates = array();

		foreach ($this->arrResults as $id => $objResult) {

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
					else if(($idSibling = $arrDuplicates[$objResult->checksum]) !== null && $arrResults[$idSibling] instanceof SearchResult)
					{
						$objSibling = &$arrResults[$idSibling];
						$objSibling->pid = array_merge(!is_array($objSibling->pid) ? array($objSibling->pid) : $objSibling->pid, array($objResult->pid));
						continue;
					}
				}
			}
			
			$arrResults[] = $objResult;
		}

		return $arrResults;
	}


	/**
	 * Set an object property
	 *
	 * @param string $strKey   The property name
	 * @param mixed  $varValue The property value
	 */
	public function __set($strKey, $varValue)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrResults[$this->intIndex]->$strKey = $varValue;
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey The property name
	 *
	 * @return mixed|null The property value or null
	 */
	public function __get($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		if (isset($this->arrResults[$this->intIndex]->$strKey))
		{
			return $this->arrResults[$this->intIndex]->$strKey;
		}

		return null;
	}


	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey The property name
	 *
	 * @return boolean True if the property is set
	 */
	public function __isset($strKey)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return isset($this->arrResults[$this->intIndex]->$strKey);
	}


	/**
	 * Return the current row as associative array
	 *
	 * @return array The current row as array
	 */
	public function row()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrResults[$this->intIndex]->row();
	}


	/**
	 * Set the current row from an array
	 *
	 * @param array $arrData The row data as array
	 *
	 * @return \Model\Collection The model collection object
	 */
	public function setRow(array $arrData)
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		$this->arrResults[$this->intIndex]->setRow($arrData);
		return $this;
	}


	/**
	 * Return the results as array
	 *
	 * @return array An array of models
	 */
	public function getResults()
	{
		return $this->arrResults;
	}


	/**
	 * Return the number of rows in the result set
	 *
	 * @return integer The number of rows
	 */
	public function count()
	{
		return count($this->arrResults);
	}


	/**
	 * Go to the first row
	 *
	 * @return \HeimrichHannot\SearchPlus\SearchResult The SearchResult list object
	 */
	public function first()
	{
		$this->intIndex = 0;
		return $this;
	}


	/**
	 * Go to the previous row
	 *
	 * @return \HeimrichHannot\SearchPlus\SearchResult|false The SearchResult list object or false if there is no previous row
	 */
	public function prev()
	{
		if ($this->intIndex < 1)
		{
			return false;
		}

		--$this->intIndex;
		return $this;
	}


	/**
	 * Return the current SearchResult
	 *
	 * @return \HeimrichHannot\SearchPlus\SearchResult The SearchResult object
	 */
	public function current()
	{
		if ($this->intIndex < 0)
		{
			$this->first();
		}

		return $this->arrResults[$this->intIndex];
	}


	/**
	 * Go to the next row
	 *
	 * @return \HeimrichHannot\SearchPlus\SearchResult|boolean The SearchResult list object or false if there is no next row
	 */
	public function next()
	{
		if (!isset($this->arrResults[$this->intIndex + 1]))
		{
			return false;
		}

		++$this->intIndex;
		return $this;
	}


	/**
	 * Go to the last row
	 *
	 * @return \HeimrichHannot\SearchPlus\SearchResult The SearchResult list object
	 */
	public function last()
	{
		$this->intIndex = count($this->arrResults) - 1;
		return $this;
	}


	/**
	 * Reset the SearchResult
	 *
	 * @return \Model\Collection The model collection object
	 */
	public function reset()
	{
		$this->intIndex = -1;
		return $this;
	}


	/**
	 * Fetch a column of each row
	 *
	 * @param string $strKey The property name
	 *
	 * @return array An array with all property values
	 */
	public function fetchEach($strKey)
	{
		$this->reset();
		$return = array();

		while ($this->next())
		{
			$strPk = $this->current()->getPk();

			if ($strKey != 'id' && isset($this->$strPk))
			{
				$return[$this->$strPk] = $this->$strKey;
			}
			else
			{
				$return[] = $this->$strKey;
			}
		}

		return $return;
	}


	/**
	 * Fetch all columns of every SearchResult
	 *
	 * @return array An array with all rows and columns
	 */
	public function fetchAll()
	{
		$this->reset();
		$return = array();

		while ($this->next())
		{
			$return[] = $this->row();
		}

		return $return;
	}


	/**
	 * Check whether an offset exists
	 *
	 * @param integer $offset The offset
	 *
	 * @return boolean True if the offset exists
	 */
	public function offsetExists($offset)
	{
		return isset($this->arrResults[$offset]);
	}


	/**
	 * Retrieve a particular offset
	 *
	 * @param integer $offset The offset
	 *
	 * @return \Model|null The SearchResult or null
	 */
	public function offsetGet($offset)
	{
		return $this->arrResults[$offset];
	}


	/**
	 * Set a particular offset
	 *
	 * @param integer $offset The offset
	 * @param mixed   $value  The value to set
	 *
	 * @throws \RuntimeException The SearchResultList is immutable
	 */
	public function offsetSet($offset, $value)
	{
		throw new \RuntimeException('This SearchResultList is immutable');
	}


	/**
	 * Unset a particular offset
	 *
	 * @param integer $offset The offset
	 *
	 * @throws \RuntimeException The SearchResultList is immutable
	 */
	public function offsetUnset($offset)
	{
		throw new \RuntimeException('This SearchResultList is immutable');
	}


	/**
	 * Retrieve the iterator object
	 *
	 * @return \ArrayIterator The iterator object
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->arrResults);
	}


}