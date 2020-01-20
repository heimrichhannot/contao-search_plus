<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\SearchPlus\Backend;

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\Config;
use Contao\Environment;
use Contao\Input;
use Contao\System;
use HeimrichHannot\SearchPlus\Substitution\AutomatorSubstitution;

class RebuildIndex extends \Contao\RebuildIndex
{
    public function run()
    {
        if (!Config::get('enableSearch'))
        {
            return '';
        }
        $this->registerEvents();
        return parent::run();
    }

    protected function registerEvents()
    {
        if(Environment::get('isAjaxRequest') && Input::get('action') == 'toggleSearchablePages')
        {
            $arrPages = Backend::findSearchablePages();

            // HOOK: take additional pages
            if (isset($GLOBALS['TL_HOOKS']['getSearchablePages']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePages']))
            {
                foreach ($GLOBALS['TL_HOOKS']['getSearchablePages'] as $callback)
                {
                    $arrPages = System::importStatic($callback[0])->{$callback[1]}($arrPages);
                }
            }

            $objTemplate = new BackendTemplate('be_rebuild_index_pageselection_tree');
            $objTemplate->pages = is_array($arrPages) ? $arrPages : array();
            $objTemplate->checkAllLegend = $GLOBALS['TL_LANG']['tl_maintenance']['checkAllLegend'];
            die($objTemplate->parse());
        }
    }

    protected function import($strClass, $strKey = null, $blnForce = false)
    {
        if(Input::get('limitsearchablepages'))
        {
            $arrSelectedPages = Input::get('searchablepages');

            if(is_array($arrSelectedPages) && !empty($arrSelectedPages))
            {
                parent::import(AutomatorSubstitution::class, $strClass, $blnForce);
                return;
            }
        }
        parent::import($strClass, $strKey, $blnForce);
    }


}