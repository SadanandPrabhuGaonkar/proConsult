<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field;

use Concrete\Core\Page\PageList;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Core;

class PageField extends AbstractField
{

    protected $requestVariables = [
        'collectionID',
    ];

    public function getKey()
    {
        return 'collectionID';
    }

    public function getDisplayName()
    {
        return t('Page (submitted from)');
    }

    public function filterList(ItemList $list)
    {
        if ($this->data['collectionID'] > 0) {           
            $list->filterByPageID($this->data['collectionID']);
        }
    }

    public function renderSearchField()
    {
        $ps = Core::make("helper/form/page_selector");
        $form = Core::make("helper/form");
        $html = $ps->selectPage('collectionID', $this->data['collectionID']);
        return $html;
    }


}
