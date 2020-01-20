<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\SearchPlus\EventListener;


use Contao\Input;

class GetSearchablePagesListener
{
    /**
     * @Hook("getSearchablePages")
     */
    public function onGetSearchablePages(array $pages, int $root = null, bool $isSitemap = false, string $language = null): array
    {
        if(Input::get('limitsearchablepages'))
        {
            $arrSelectedPages = Input::get('searchablepages');

            if(is_array($arrSelectedPages) && !empty($arrSelectedPages))
            {
                $arrPages = array_keys(array_intersect(array_flip($pages), $arrSelectedPages));
            }
        }
        return $pages;
    }
}