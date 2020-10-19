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


use Contao\Controller;
use Contao\FrontendTemplate;
use Contao\ModuleSearch;

class ModuleSearchPlus extends ModuleSearch
{

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Mark the x and y parameter as used (see #4277)
		if (isset($_GET['x'])) {
			\Input::get('x');
			\Input::get('y');
		}

		// Trigger the search module from a custom form
		if (!isset($_GET['keywords']) && \Input::post('FORM_SUBMIT') == 'tl_search') {
			$_GET['keywords']   = \Input::post('keywords');
			$_GET['query_type'] = \Input::post('query_type');
			$_GET['per_page']   = \Input::post('per_page');
		}

		$blnFuzzy     = $this->fuzzy;
		$strQueryType = \Input::get('query_type') ?: $this->queryType;

		$strKeywords = trim(\Input::get('keywords'));

        $templateName = ($this->searchType == 'advanced') ? 'mod_search_advanced' : 'mod_search_simple';
        if (version_compare(VERSION, "4.0") >= 0) {
            try {
                Controller::getTemplate($templateName);
                /** @var \FrontendTemplate|object $objFormTemplate */
                $objFormTemplate = new \FrontendTemplate($templateName);
            } catch (\Exception $exception) {
                $objFormTemplate = $this->Template;
                $objFormTemplate->advanced = ($this->searchType == 'advanced');
            }
        } else {
            /** @var \FrontendTemplate|object $objFormTemplate */
            $objFormTemplate = new \FrontendTemplate($templateName);
        }


//        $templateName = ($this->searchType == 'advanced') ? 'mod_search_advanced' : 'mod_search_simple';
//        if (version_compare(VERSION, "4.0") >= 0) {
//            try {
//                Controller::getTemplate($templateName);
//            } catch (\Exception $exception) {
//                $templateName = $this->strTemplate;
//            }
//        }

//        /** @var \FrontendTemplate|object $objFormTemplate */
//        $objFormTemplate = new \FrontendTemplate($templateName);
		$objFormTemplate->uniqueId     = $this->id;
		$objFormTemplate->queryType    = $strQueryType;
		$objFormTemplate->keyword      = specialchars($strKeywords);
		$objFormTemplate->keywordLabel = $GLOBALS['TL_LANG']['MSC']['keywords'];
		$objFormTemplate->optionsLabel = $GLOBALS['TL_LANG']['MSC']['options'];
		$objFormTemplate->search       = specialchars($GLOBALS['TL_LANG']['MSC']['searchLabel']);
		$objFormTemplate->matchAll     = specialchars($GLOBALS['TL_LANG']['MSC']['matchAll']);
		$objFormTemplate->matchAny     = specialchars($GLOBALS['TL_LANG']['MSC']['matchAny']);
		$objFormTemplate->id           = (\Config::get('disableAlias') && \Input::get('id')) ? \Input::get('id') : false;
		$objFormTemplate->action       = ampersand(\Environment::get('indexFreeRequest'));

        // Redirect page
        if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) instanceof \PageModel)
        {
            /** @var PageModel $objTarget */
            $objFormTemplate->action = $objTarget->getFrontendUrl();
        }

		$this->Template->form       = $objFormTemplate->parse();
		$this->Template->pagination = '';
		$this->Template->results    = '';

		// Execute the search if there are keywords
		if ($strKeywords != '' && $strKeywords != '*' && !$this->jumpTo) {
			// Reference page
			if ($this->rootPage > 0) {
				$intRootId = $this->rootPage;
				$arrPages  = $this->Database->getChildRecords($this->rootPage, 'tl_page');
				array_unshift($arrPages, $this->rootPage);
			} // Website root
			else {
				$intRootId = $objPage->rootId;
				$arrPages  = $this->Database->getChildRecords($objPage->rootId, 'tl_page');
			}
			
			// customize by module
			$arrFilterPages = deserialize($this->filterPages, true);

			if (!empty($arrFilterPages) && $this->pageMode)
			{
				if ($this->addPageDepth)
				{
					$arrFilterPages = array_merge($arrFilterPages, \Database::getInstance()->getChildRecords($arrFilterPages, 'tl_page'));
				}

				switch ($this->pageMode)
				{
					case 'include':
						$arrPages = $arrFilterPages;
						break;
					case 'exclude':
						$arrPages = array_diff($arrPages, $arrFilterPages);
						break;
				}
			}
				
			// HOOK: add custom logic (see #5223)
			if (isset($GLOBALS['TL_HOOKS']['customizeSearch']) && is_array($GLOBALS['TL_HOOKS']['customizeSearch'])) {
				foreach ($GLOBALS['TL_HOOKS']['customizeSearch'] as $callback) {
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($arrPages, $strKeywords, $strQueryType, $blnFuzzy, $this);
				}
			}

			// Return if there are no pages
			if (!is_array($arrPages) || empty($arrPages)) {
				$this->log('No searchable pages found', __METHOD__, TL_ERROR);

				return;
			}

			$arrResult       = null;
			$strChecksum     = md5($strKeywords . $strQueryType . $intRootId . $blnFuzzy. $this->searchOrder . implode(',', $arrPages));
			$query_starttime = microtime(true);
			$strCacheFile    = 'system/cache/search/' . $strChecksum . '.json';

//			 Load the cached result
			if (file_exists(TL_ROOT . '/' . $strCacheFile)) {
				$objFile = new \File($strCacheFile, true);

				if ($objFile->mtime > time() - 1800) {
					$arrResult = json_decode($objFile->getContent(), true);
				} else {
					$objFile->delete();
				}
			}

			// Cache the result
			if ($arrResult === null) {
				try {
					$objSearch = \Search::searchFor($strKeywords, ($strQueryType == 'or'), $arrPages, 0, 0, $blnFuzzy);
					$arrResult = $objSearch->fetchAllAssoc();
				} catch (\Exception $e) {
					$this->log('Website search failed: ' . $e->getMessage(), __METHOD__, TL_ERROR);
					$arrResult = array();
				}

				\File::putContent($strCacheFile, json_encode($arrResult));
			}

			$query_endtime = microtime(true);

			$objSearchResults = new SearchResultList(array_values($arrResult), $this->objModel);

			$count = $objSearchResults->count();

			$this->Template->count    = $count;
			$this->Template->page     = null;
			$this->Template->keywords = $strKeywords;

			// No results
			if ($count < 1) {
				$this->Template->header   = sprintf($GLOBALS['TL_LANG']['MSC']['sEmpty'], $strKeywords);
				$this->Template->duration = substr($query_endtime - $query_starttime, 0, 6) . ' ' . $GLOBALS['TL_LANG']['MSC']['seconds'];

				return;
			}

			$from = 1;
			$to   = $count;

			// Pagination
			if ($this->perPage > 0) {
				$id       = 'page_s' . $this->id;
				$page     = (\Input::get($id) !== null) ? \Input::get($id) : 1;
				$per_page = \Input::get('per_page') ?: $this->perPage;

				// Do not index or cache the page if the page number is outside the range
				if ($page < 1 || $page > max(ceil($count / $per_page), 1)) {
					/** @var \PageError404 $objHandler */
					$objHandler = new $GLOBALS['TL_PTY']['error_404']();
					$objHandler->generate($objPage->id);
				}

				$from = (($page - 1) * $per_page) + 1;
				$to   = (($from + $per_page) > $count) ? $count : ($from + $per_page - 1);

				// Pagination menu
				if ($to < $count || $from > 1) {
					$objPagination              = new \Pagination($count, $per_page, \Config::get('maxPaginationLinks'), $id);
					$this->Template->pagination = $objPagination->generate("\n  ");
				}

				$this->Template->page = $page;
			}

			// Get the results
			for ($i = ($from - 1); $i < $to && $i < $count; $i++) {
				/** @var \FrontendTemplate|object $objTemplate */
				$objTemplate = new \FrontendTemplate($this->searchTpl ?: 'search_default');

				if(!$objSearchResults->offsetExists($i)) continue;

				$objResult = $objSearchResults->offsetGet($i);

				$objTemplate->url       = $objResult->url;
				$objTemplate->link      = $objResult->title;
				$objTemplate->href      = $objResult->url;
				$objTemplate->target    = ($objResult->isValidFile() ? (($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"') : '');
				$objTemplate->title     = specialchars($objResult->title);
				$objTemplate->class     = (($i == ($from - 1)) ? 'first ' : '') . (($i == ($to - 1) || $i == ($count - 1)) ? 'last ' : '') . (($i % 2 == 0) ? 'even' : 'odd');
				
				$objTemplate->relevance = sprintf(
					$GLOBALS['TL_LANG']['MSC']['relevance'],
					number_format($objResult->relevance / $arrResult[0]['relevance'] * 100, 2) . '%'
				);

                $objTemplate->filesize  = $objResult->filesize;
				$objTemplate->matches   = $objResult->matches;

				$arrContext = array();
				$arrMatches = trimsplit(',', $objResult->matches);

                $contextLength = $this->contextLength;
                $totalLength = $this->totalLength;
                if (version_compare(VERSION, "4.9") >= 0) {
                    $contextLength = 48;
                    $totalLength = 360;
                    $lengths = deserialize($this->contextLength, true);
                    if ($lengths[0] > 0)
                    {
                        $contextLength = $lengths[0];
                    }

                    if ($lengths[1] > 0)
                    {
                        $totalLength = $lengths[1];
                    }
                }

				// Get the context
				foreach ($arrMatches as $strWord) {
					$arrChunks = array();

					if (version_compare(VERSION, "4.6") >= 0)
					{
                        preg_match_all('/(^|\b.{0,' . $contextLength . '}(?:\PL|\p{Hiragana}|\p{Katakana}|\p{Han}|\p{Myanmar}|\p{Khmer}|\p{Lao}|\p{Thai}|\p{Tibetan}))' . preg_quote($strWord, '/') . '((?:\PL|\p{Hiragana}|\p{Katakana}|\p{Han}|\p{Myanmar}|\p{Khmer}|\p{Lao}|\p{Thai}|\p{Tibetan}).{0,' . $contextLength . '}\b|$)/ui', $objResult->text, $arrChunks);
                    } else {
                        preg_match_all(
                            '/(^|\b.{0,' . $this->contextLength . '}\PL)' . str_replace('+', '\\+', $strWord) . '(\PL.{0,' . $this->contextLength
                            . '}\b|$)/ui',
                            $objResult->text,
                            $arrChunks
                        );
                    }

					foreach ($arrChunks[0] as $strContext) {
						$arrContext[] = ' ' . $strContext . ' ';
					}
				}

				// Shorten the context and highlight all keywords
				if (!empty($arrContext)) {
					$objTemplate->context = trim(\StringUtil::substrHtml(implode('…', $arrContext), $totalLength));
					$objTemplate->context = preg_replace(
						'/(\PL)(' . implode('|', $arrMatches) . ')(\PL)/ui',
						'$1<span class="highlight">$2</span>$3',
						$objTemplate->context
					);

					$objTemplate->hasContext = true;
				}

				$this->Template->results .= $objTemplate->parse();
			}

			$this->Template->header   = vsprintf($GLOBALS['TL_LANG']['MSC']['sResults'], array($from, $to, $count, $strKeywords));
			$this->Template->duration = substr($query_endtime - $query_starttime, 0, 6) . ' ' . $GLOBALS['TL_LANG']['MSC']['seconds'];
		}
	}

}
