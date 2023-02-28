<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Package\FormidableFull\Src\Formidable\Template;
use \Pagerfanta\Adapter\DoctrineDbalAdapter;
use \Concrete\Core\Search\Pagination\Pagination;
use \Concrete\Core\Search\ItemList\Database\ItemList;

class TemplateList extends ItemList {
	
	public function createQuery() {
        $this->query->select('ft.templateID AS templateID, ft.label AS label')->from('FormidableTemplates', 'ft');
    }

    public function getResult($queryRow) {
        return Template::getByID($queryRow['templateID']);
    }

    protected function createPaginationObject() {
        $adapter = new DoctrineDbalAdapter($this->deliverQueryObject(), function ($query) {
            $query->select('count(distinct ft.templateID)')->setMaxResults(1);
        });
        $pagination = new Pagination($this, $adapter);
        return $pagination;
    }

    public function getTotalResults() {
        $query = $this->deliverQueryObject();
        return $query->select('count(distinct ft.templateID)')->setMaxResults(1)->execute()->fetchColumn();
    }
}
