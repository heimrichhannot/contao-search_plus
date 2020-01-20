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


use Contao\BackendTemplate;

class ParseTemplateListener
{
    /**
     * @Hook("parseTemplate")
     * @param BackendTemplate $template
     */
    public function onParseTemplate($template): void
    {
        if ('be_rebuild_index' === $template->getName()) {
            $template->pageSelection = $this->generatePageSelection();

        }
    }

    protected function generatePageSelection()
    {
        $objTemplate = new BackendTemplate('be_rebuild_index_pageselection');
        $objTemplate->limitSearchablePagesLabel = $GLOBALS['TL_LANG']['tl_maintenance']['limitsearchablepages'];
        return $objTemplate->parse();
    }
}