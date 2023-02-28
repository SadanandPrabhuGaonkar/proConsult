<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Result;

use \Concrete\Package\FormidableFull\Src\Formidable\Search\Menu as ResultMenu;
use Concrete\Core\Search\Result\Item as SearchResultItem;
use Concrete\Core\Search\Result\Result as SearchResult;
use Concrete\Core\Search\Column\Set;

class Item extends SearchResultItem
{
    public function __construct(SearchResult $result, Set $columns, $item)
    {
        parent::__construct($result, $columns, $item);
        $this->populateDetails($item);
    }

    protected function populateDetails($item) {
    	$this->answerSetID = $item->answerSetID;
        $this->treeNodeTypeHandle = 'formidable';
        $this->treeNodeMenu = new ResultMenu($item);
    }
}
