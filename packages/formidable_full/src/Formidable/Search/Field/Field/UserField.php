<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field;

use Concrete\Core\Page\PageList;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Core;

class UserField extends AbstractField
{

    protected $requestVariables = [
        'userID',
    ];

    public function getKey()
    {
        return 'userID';
    }

    public function getDisplayName()
    {
        return t('User (submitted by)');
    }

    public function filterList(ItemList $list)
    {
        if ($this->data['userID'] > 0) {           
            $list->filterByUserID($this->data['userID']);
        }
    }

    public function renderSearchField()
    {
        $us = Core::make("helper/form/user_selector");
        $form = Core::make("helper/form");
        $html = $us->selectUser('userID', $this->data['userID']);
        return $html;
    }


}
