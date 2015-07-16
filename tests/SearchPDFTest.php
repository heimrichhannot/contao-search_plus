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
class SearchPDFTest extends \PHPUnit_Framework_TestCase
{
	protected static $arrValidHostNames = array('contao.hundh.local');

	public function testGetValidPathForExistingAbsoluteExternalPDF()
	{
		$strFile = 'http://anwaltverein.de/files/anwaltverein.de/downloads/mitgliedschaft/mitglied-werden/Beitrittserklaerung%20AV%20-%20Homepage%20-%20ab%2025.02.2015.pdf';

		$this->assertEquals(null, \HeimrichHannot\SearchPlus\SearchPDF::getValidPath($strFile, static::$arrValidHostNames));
	}


	public function testGetValidPathForNonExistingAbsolutePDF()
	{
		$strFile = 'http://contao.hundh.local/files/anwaltverein.de/downloads/mitgliedschaft/mitglied-werden/Beitrittserklaerung%20AV%20-%20Homepage%20-%20ab%2025.02.2015.pdf';

		$this->assertEquals(null, \HeimrichHannot\SearchPlus\SearchPDF::getValidPath($strFile, static::$arrValidHostNames));
	}

	public function testGetValidPathForExistingAbsolutePDF()
	{
		$strFile = 'http://contao.hundh.local/files/dev/DAV-SN_27-15.pdf';

		$this->assertEquals('files/dev/DAV-SN_27-15.pdf', \HeimrichHannot\SearchPlus\SearchPDF::getValidPath($strFile, static::$arrValidHostNames));
	}


	public function testGetValidPathForExistingRelativePDF()
	{
		$strFile = 'files/dev/DAV-SN_27-15.pdf';

		$this->assertEquals('files/dev/DAV-SN_27-15.pdf', \HeimrichHannot\SearchPlus\SearchPDF::getValidPath($strFile, static::$arrValidHostNames));
	}

	public function testGetValidPathForExistingDownloadPDF()
	{
		$strFile = '?file=files/dev/DAV-SN-33-15.pdf';

		$this->assertEquals('files/dev/DAV-SN-33-15.pdf', \HeimrichHannot\SearchPlus\SearchPDF::getValidPath($strFile, static::$arrValidHostNames));
	}
}
