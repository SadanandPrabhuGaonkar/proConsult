<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Result as Result;
use \Concrete\Package\FormidableFull\Src\Formidable\ResultList;
use \Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\Available as AvailableColumnSet;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Result\Result as SearchResult;
use \Concrete\Core\Controller\Controller;
use \Concrete\Core\Application\EditResponse;
use \Concrete\Core\Http\Service\Json as Json;
use URL;
use Exception; 
use Page;
use Permissions;
use Core;

class Tools extends Controller {

    const METHOD_RANGE = 'range';
    const METHOD_LATEST = 'latest';

	protected function validateAction($tk = 'formidable_result') {
		$token = Core::make('token');
		if (!$token->validate($tk)) {
			return array(
				'type' => 'error',
				'message' => $token->getErrorMessage()
			);
		}
		if (!$this->canAccess()) {
			return array(
				'type' => 'error',
				'message' => t('Access Denied')
			);
		}
		return true;
	}

	protected function canAccess() {
		$c = Page::getByPath('/dashboard/formidable/results');
		$cp = new Permissions($c);
		return $cp->canRead();
	}
	
	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$post = \Concrete\Core\Http\Request::getInstance()->post();
			$fr = new EditResponse();
			$fr->setRedirectURL(URL::to('/dashboard/formidable/results/'));
			if ($post['answerSetIDs'] && count($post['answerSetIDs'])) {
				foreach ($post['answerSetIDs'] as $answerSetID) {
					$result = Result::getByID($answerSetID);
					if (is_object($result))	{
						if (!$result->delete()) {
	                    	throw new Exception(t('Unable to delete one or more files.'));
						}
					}
				}
			}
			$fr->setMessage(t2('%s result deleted successfully.', '%s results deleted successfully.', count($post['answerSetIDs'])));
	        $fr->outputJSON();
	    }
	    $this->json($r);	
	}

	public function resend() {
		$r = $this->validateAction();
		if ($r === true) {	
			$post = \Concrete\Core\Http\Request::getInstance()->post();		
			if ($post['answerSetIDs'] && count($post['answerSetIDs'])) {
				foreach ($post['answerSetIDs'] as $answerSetID) {
					$result = Result::getByID($answerSetID);
					if (!is_object($result)) $r = array('type' => 'error', 'message' => t('Can\'t find result'));
					else {
						$f = Form::getByID($result->getFormID());
						if (!is_object($f)) $r = array('type' => 'error', 'message' => t('Can\'t find form'));
						else {
							$f->setResult($result);	
							
							$mailings = $f->getMailings();
                            $count_mailings = is_array($mailings) ? count($mailings) : 0 ;
							if ($count_mailings) {
								foreach ($mailings as $mailing) {								
									$mailing->send(true);
								}
							}
							$r = array(
								'type' => 'info', 
								'message' => t('Result successfully resend')
							);	
						}	
					}
				}
			}
	    }
	    $this->json($r);	    
	}

    public function csv() {

        //?method=latest?x=100
        //latest x(100)
        /** @var \Concrete\Core\Utility\Service\Text $th */
        $th = Core::make('helper/text');

        $method = $th->sanitize($this->get('method'));
        $x = $th->sanitize($this->get('x'));
        $r = $this->validateAction();
        if( $method == self::METHOD_LATEST && (!is_numeric($x)))
        {
            echo "Enter the latest number of items";
            die;
        }
        if ($r === true) {

            $date = date('Ymd');

            // Generate a dirty XLS (HTML though...)
            header("Content-Type: application/vnd.ms-excel");
            header("Cache-control: private");
            header("Pragma: public");
            header("Content-Disposition: inline; filename=formidable_{$date}.xls");
            header("Content-Title: Formidable Results - Run on {$date}");

            $html = array();
            $html[] = '<table>';
            $fldca = new AvailableColumnSet(true);
            $columns = $fldca->getColumns();
            $count_cols = is_array($columns) ? count($columns) : 0 ;
            if ($count_cols) {
                $html[] = '<tr>';
                foreach ($columns as $col) {
                    $html[] = '<td>'.$col->getColumnName().'</td>';
                }
                $html[] = '</tr>';
            }
            // Get rows
            $request = \Concrete\Core\Http\Request::getInstance()->request();
            $list = new ResultList();
            if ($request['item'] && count($request['item'])) $list->filterByIDs($request['item']);
            $col = $fldca->getDefaultSortColumn();
            $list->sanitizedSortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());

            if($method == self::METHOD_LATEST)
            {
                $list->setItemsPerPage($x);
            }
            else
            {
                //all items
                $list->setItemsPerPage(99999);
            }

            $result = new SearchResult($fldca , $list);

            foreach ($result->getItems() as $r) {
                $html[] = '<tr>';
                foreach ($r->getColumns() as $c) {


  			//new line chars not getting rendered during export
                    $v = str_replace('<br />', ' ',$c->getColumnValue());
                    $v = htmlentities($v,ENT_COMPAT);
                    $v = str_replace("\n", '&#10;',$v);

                     $html[] = '<td>'.$v.'</td>';
                    
                }
		$html[] = '</tr>';
            }
            $html[] = '</table>';

            echo @implode(PHP_EOL, $html);
            die();

            /*
            // Generate a proper CSV (no-HTML though...)
            $csv_delimiter = ';';
            $csv_enclosure = '"';

            $fp = fopen('php://output', 'w');

            header("Content-Type: text/csv");
            header("Cache-control: private");
            header("Pragma: public");
            header("Content-Disposition: attachment; filename=formidable_{$date}.csv");
            header("Content-Title: Formidable Results - Run on {$date}");

            // Columns
            $fldca = new AvailableColumnSet(true);
            $columns = $fldca->getColumns();
            foreach ($columns as $col) {
                $row[] = $col->getColumnName();
            }
            fputcsv($fp, $row, $csv_delimiter, $csv_enclosure);

            // Get rows
             $list = new ResultList();
             if (count($request['item'])) $list->filterByIDs($request['item']);
             $col = $fldca->getDefaultSortColumn();
            $list->sanitizedSortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
            $list->setItemsPerPage(99999);

            $result = new SearchResult($fldca, $list);
            foreach ($result->getItems() as $r) {
                $row = array();
                foreach ($r->getColumns() as $c) {
                    $row[] = $c->getColumnValue(); //strip_tags($c->getColumnValue());
                }
                fputcsv($fp, $row, $csv_delimiter, $csv_enclosure);
            }
            fclose($fp);
            die;
            */
        }
        $this->json($r);
    }
	
	private function json($array) {
		echo Json::encode($array);
		die();
	}

}
