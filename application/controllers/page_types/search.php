<?php

namespace Application\Controller\PageType;

use Application\Concrete\Helpers\MultiSelectSearchHelper;
use Application\Concrete\Helpers\SelectOptionsHelper;
use Application\Concrete\Helpers\Utils;
use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Page\PageList;
use Concrete\Core\Html\Service\Navigation;
use Application\Concrete\Helpers\ImageHelper;
use Concrete\Core\Page\Controller\PageTypeController;
use Concrete\Core\Utility\Service\Text as TextHelper;
use Concrete\Core\View\View;
use Concrete\Theme\Concrete\PageTheme;

class Search extends PageTypeController
{
    const ITEMS_PER_PAGE = 12;

    public function view()
    {
        $th = new TextHelper();
        $ih = new ImageHelper();
        $nh = new Navigation();
        $dh = new Date();
        $keywords = trim(urldecode($th->sanitize($_GET['keywords'])));

        $page     = $this->get('page');
        $isAjax   = $this->get('isAjax');
        // $keywords = urldecode($th->decodeEntities($th->sanitize($this->get('keywords'))));
        $page     = $page > 0 ? $page : 1;
        $searchResults    = [];

        $pl = new PageList();
        // $pl->filterByPageTypeHandle('search');
        // $pl->sortBy('ak_published_date', 'desc');
        
        if ($keywords) {
            $pl->filterByName($keywords);
        }
        $pl->setItemsPerPage(self::ITEMS_PER_PAGE);


        $pagination = $pl->getPagination();
        if ($pagination->getTotalPages() >= $page) {
            $pagination->setCurrentPage($page);
            $searchResults = $pagination->getCurrentPageResults();
        }

        if ($isAjax) {
            foreach ($searchResults as $searchResult) {
                View::element('search', ['searchResult' => $searchResult, 'ih' => $ih, 'themePath' => PageTheme::getSiteTheme()->getThemeURL()]);
            }
            exit();
        }

        $this->set('ih', $ih);
        $this->set('th', $th);
        $this->set('nh', $nh);
        $this->set('keywords', $keywords);
        $this->set('searchResults', $searchResults);
    }

}
