<?php
namespace Concrete\Package\FormidableFull\Controller\Element\Search;

use Concrete\Core\Controller\ElementController;
use Concrete\Core\Entity\Search\Query;
use Core;

class Header extends ElementController
{
    protected $pkgHandle = 'formidable_full';
    protected $query;

    public function __construct(Query $query = null) {
        $this->query = $query;
        parent::__construct();
    }

    public function getElement() {
        return 'dashboard/result/search_header';
    }

    public function view() {
        $this->set('query', $this->query);
        $this->set('form', Core::make('helper/form'));
        $this->set('token', Core::make('token'));
    }

}
