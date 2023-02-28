<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search;

use Concrete\Package\FormidableFull\Src\Formidable\ResultList;
use Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\DefaultSet;
use Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\Available;
use Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\ColumnSet;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Result\Result;
use Concrete\Package\FormidableFull\Src\Formidable\Form; 

use Concrete\Core\Entity\Search\Query;
use Concrete\Core\Search\AbstractSearchProvider;
use Concrete\Core\Search\ProviderInterface;
use Concrete\Core\Search\QueryableInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SearchProvider extends AbstractSearchProvider implements QueryableInterface
{
    protected $formID = 0;

    public function __construct($formID = null, Session $session)
    {
        $this->formID = $formID;
        parent::__construct($session);
    }

    public function getAllColumnSet() {
        return parent::getAllColumnSet();
    }

    /*
    public function setSessionCurrentQuery(Query $query) {
        parent::setSessionCurrentQuery($query);
    }

    public function clearSessionCurrentQuery() {
        parent::clearSessionCurrentQuery();
    }

    

    public function getSessionCurrentQuery() {
        return parent::getSessionCurrentQuery();
    }

    public function getItemsPerPage() {
        return parent::getItemsPerPage();
    }

    public function getItemsPerPageOptions() {
        return parent::getItemsPerPageOptions();
    }
    */

    public function getSessionNamespace()
    {
        $session = new Session();
        if ($this->formID == 0) $this->formID = $session->get('formidableFormID');    
        return 'formidable-'.$this->formID;
    }

    public function getCustomAttributeKeys()
    {
        return array();
    }

    public function getAvailableColumnSet()
    {
        return new Available();
    }

    public function getCurrentColumnSet()
    {
        return ColumnSet::getCurrent();
    }

    public function getBaseColumnSet()
    {
        return new ColumnSet();
    }

    public function getDefaultColumnSet()
    {
        return new DefaultSet();
    }

    public function getItemList()
    {
        return new ResultList();
    }

    public function createSearchResultObject($columns, $list)
    {
        return new Result($columns, $list);
    }
    
    public function getSearchResultFromQuery(Query $query)
    {
        $set = $this->getCurrentColumnSet();        
        $query->setColumns($set);

        $list = $this->getItemList();
        foreach($query->getFields() as $field) {
            // To solve incomplete classes
            if (!is_object($field) && gettype($field) == 'object')
            $field = unserialize(serialize($field));
            // Filter
            $field->filterList($list);
        }

        if (!$list->getActiveSortColumn()) {
            $columns = $query->getColumns();
            if (is_object($columns)) {    
                $request = \Concrete\Core\Http\Request::getInstance()->request();            
                $column = $columns->getDefaultSortColumn();
                if ($request['ccm_order_by']) $list->sanitizedSortBy($request['ccm_order_by'], $request['ccm_order_by_direction']?$request['ccm_order_by_direction']:'asc');
                else $list->sanitizedSortBy($column->getColumnKey(), $column->getColumnDefaultSortDirection());
            } else {
                $columns = $this->getDefaultColumnSet();
            }
        }
        $result = $this->createSearchResultObject($columns, $list);
        $result->setQuery($query);
        return $result;
    }

    public function getSavedSearch() {
        return parent::getSavedSearch();
    }
    
}