<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Pagerfanta\Adapter\DoctrineDbalAdapter;
use \Concrete\Core\Search\Pagination\Pagination;
use \Concrete\Core\Search\ItemList\Database\ItemList;

class FormList extends ItemList {
	
	public function createQuery() {
        $this->query->select('ff.formID AS formID, ff.label AS label')->from('FormidableForms', 'ff');
    }

    public function getResult($queryRow) {
        return Form::getByID($queryRow['formID']);
    }

    protected function createPaginationObject() {
        $adapter = new DoctrineDbalAdapter($this->deliverQueryObject(), function ($query) {
            $query->select('count(distinct ff.formID)')->setMaxResults(1);
        });
        $pagination = new Pagination($this, $adapter);
        return $pagination;
    }

    public function getTotalResults() {
        $query = $this->deliverQueryObject();
        return $query->select('count(distinct ff.formID)')->setMaxResults(1)->execute()->fetchColumn();
    }
}
