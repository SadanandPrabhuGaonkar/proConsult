<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Search\ResultItem;
use \Concrete\Core\Search\ItemList\Database\ItemList as DatabaseItemList;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use \Concrete\Core\Search\Pagination\Pagination;
use Database;
use Core;

class ResultList extends DatabaseItemList {
   
    protected $autoSortColumns = array(
        'a_submitted',
    );

    protected $formID = 0;

    protected $add_select = array();
    protected $add_search = array();
	protected $keyword = false;

	protected $elements = array();
	
	public function __construct($formID = 0) {
		$session = Core::make('app')->make('session');
		if ($formID == 0) $formID = $session->get('formidableFormID');
		$this->formID = $formID;

		$formID = $this->formID;
		$f = Form::getByID($formID);		
		if (!is_object($f)) return false;

		$elements = $f->getElements();				
		if (count($elements)) {
			foreach ($elements as $element) {
				if ($element->isLayout()) continue;
				$this->elements[$element->getElementID()] = $element;
			}
		}
		parent::__construct();
	}

	public function initialize() {	
		if (count($this->elements)) {
			foreach ($this->elements as $element) {
				$this->autoSortColumns[] = 'element_'.$element->getElementID();				
				$this->add_select[] = "(SELECT answer_formated FROM FormidableAnswers WHERE formID = :formID AND elementID = ".intval($element->getElementID())." AND answerSetID = fas.answerSetID) AS `element_".intval($element->getElementID())."` ";				
				$this->add_select[] = "(SELECT answer_unformated FROM FormidableAnswers WHERE formID = :formID AND elementID = ".intval($element->getElementID())." AND answerSetID = fas.answerSetID) AS `raw_element_".intval($element->getElementID())."` ";
			}
		}
		$this->query->setParameter('formID', intval($this->formID));
	}

    public function createQuery() {		
		$this->initialize();	
		$this->query->select('fas.formID AS formID, fas.answerSetID AS answerSetID, fas.submitted AS a_submitted, fas.ip AS a_ip, fas.collectionID AS a_collectionID, fas.answerSetID AS a_answerSetID, fas.userID AS a_userID, fas.browser AS a_browser, fas.platform AS a_platform, fas.resolution AS a_resolution, fas.locale AS a_locale '.($this->add_select?',':'').' '.@implode(', ', $this->add_select));	
		$this->query->from('FormidableAnswerSets', 'fas');
		$this->query->andWhere('fas.formID = :formID');
        $this->query->andWhere('temp != 1');
	}

    public function getTotalResults() {
        $this->initialize();
        $query = $this->deliverQueryObject();		
		$total = $query->select('count(distinct fas.answerSetID)')->orderBy('fas.answerSetID')->setMaxResults(1)->execute()->fetchColumn();
        return $total;			 
    }

    protected function createPaginationObject() {
        $adapter = new DoctrineDbalAdapter($this->deliverQueryObject(), function ($query) {                      
            $query->select('count(distinct fas.answerSetID)');
	        $query->setMaxResults(1);	
	        $query->orderBy('fas.answerSetID');	 
        }); 
        $pagination = new Pagination($this, $adapter);       
        return $pagination;
    }
    
    public function getResult($queryRow) {
       return new ResultItem($queryRow);
    }
    
    public function filterByKeyword($keyword) {
		$db = Database::connection();	
		if (strlen($keyword) > 0) $keyword = $db->quote('%' . $keyword . '%');
		if (!empty($keyword) && count($this->elements)) {
			foreach ($this->elements as $element) {
				$this->filter(false, "fas.answerSetID = (SELECT answerSetID FROM FormidableAnswers WHERE formID = :formID AND elementID = ".intval($element->getElementID())." AND answerSetID = fas.answerSetID AND answer_formated LIKE ".$keyword.")");
			}
		}
	}
	public function filterByIDs($ids) {
        $this->filter(false, "fas.answerSetID IN (".@implode(',', $ids).")");
    }

	public function filterByDateSubmitted($date, $comparison = '=') {
        $this->query->andWhere($this->query->expr()->comparison('fas.submitted', $comparison, $this->query->createNamedParameter($date)));
    }
    public function filterByPageID($collectionID) {
       	$this->query->andWhere($this->query->expr()->comparison('fas.collectionID', '=', ':collectionID'));
        $this->query->setParameter('collectionID', $collectionID, \PDO::PARAM_INT);
    }
    public function filterByUserID($userID) {
       	$this->query->andWhere($this->query->expr()->comparison('fas.userID', '=', ':userID'));
        $this->query->setParameter('userID', $userID, \PDO::PARAM_INT);
    }
    public function filterByIP($ip) {
       	$this->query->andWhere($this->query->expr()->comparison('fas.ip', 'LIKE', ':ip'));
        $this->query->setParameter('ip', '%'.$ip.'%');
    }
    public function filterByBrowser($browser) {
       	$this->query->andWhere($this->query->expr()->comparison('fas.browser', 'LIKE', ':browser'));
        $this->query->setParameter('browser', '%'.$browser.'%');
    }		
	public function filterByElementHandle($handle, $value, $comp = '=') {
		$db = Database::connection();	
		if ($handle == false) $this->filter(false, $value);
		else {
			$comp = (is_null($value) && stripos($comp, 'is') === false) ? (($comp == '!=' || $comp == '<>') ? 'IS NOT' : 'IS') : $comp; 
			$this->filter(false, "fas.answerSetID = (SELECT answerSetID FROM FormidableAnswers LEFT JOIN FormidableFormElements ON FormidableAnswers.elementID = FormidableFormElements.elementID WHERE FormidableAnswers.formID = :formID AND FormidableFormElements.label_import = '".$handle."' AND FormidableAnswers.answerSetID = fas.answerSetID AND FormidableAnswers.answer_formated ".$comp." ".$db->quote($value).")");	
		}
	}

	public function filterByElementID($id, $value, $comp = '=') {
		$db = Database::connection();	
		if ($id == false) $this->filter(false, $value);
		else {
			$comp = (is_null($value) && stripos($comp, 'is') === false) ? (($comp == '!=' || $comp == '<>') ? 'IS NOT' : 'IS') : $comp; 			
			$this->filter(false, "fas.answerSetID = (SELECT answerSetID FROM FormidableAnswers WHERE formID = :formID AND elementID = ".intval($id)." AND answerSetID = fas.answerSetID AND answer_formated ".$comp." ".$db->quote($value).")");	
		}
	}			
}
