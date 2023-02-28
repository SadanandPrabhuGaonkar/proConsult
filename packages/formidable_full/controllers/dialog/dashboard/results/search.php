<?php
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results;

use Concrete\Controller\Dialog\Search\AdvancedSearch;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\DefaultField;
use Concrete\Controller\Search\Standard;
use Permissions;
use Page;

class Search extends Standard
{
    protected function canAccess() {
        $c = Page::getByPath('/dashboard/formidable/results');
        $cp = new Permissions($c);
        return $cp->canRead();
    }

    protected function getAdvancedSearchDialogController()
    {
        return $this->app->make('\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\AdvancedSearch');
    }

    protected function getSavedSearchPreset($presetID)
    {
        $em = \Database::connection()->getEntityManager();
        $preset = $em->find('Concrete\Package\FormidableFull\Src\Formidable\Search\SavedResultSearch', $presetID);
        return $preset;
    }

    protected function getBasicSearchFieldsFromRequest()
    {
        $fields = parent::getBasicSearchFieldsFromRequest();
        $keywords = htmlentities($this->request->get('fKeywords'), ENT_QUOTES, APP_CHARSET);
        if ($keywords) $fields[] = new DefaultField('keywords', t('Keywords'), 'filterByKeyword', '=', $keywords);
        return $fields;
    }
}
