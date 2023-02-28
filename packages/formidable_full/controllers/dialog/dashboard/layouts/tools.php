<?php
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Layout;
use \Concrete\Core\Controller\Controller;
use \Concrete\Core\Http\Service\Json as Json;
use Database;
use Page;
use Permissions;
use Core;

class Tools extends Controller {

	protected $layouts = array();

	protected function validateAction($tk = 'formidable_layout') {
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
		$c = Page::getByPath('/dashboard/formidable/');
		$cp = new Permissions($c);
		return $cp->canRead();
	}

	public function __construct() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (!is_object($f)) return false;
			$this->layouts = $f->getLayout();
		}
		$this->set('errors', $r);
	}

	public function save() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error',
				'message' => t('Error: Layout can\'t be added or updated')
			);

			$suc = array(
				'type' => 'info',
				'message' => t('Layout is successfully added or updated')
			);

			$f = Form::getByID($this->post('formID'));
			if (is_object($f)) {

				$l = Layout::getByID($this->post('layoutID'));
				if (!is_object($l)) {
					if (intval($this->post('rowID')) < 0 && !$this->post('cols')) $this->json($r);

					if ($this->post('rowID') < 0) {
						$layouts = $this->layouts;
						$newRowID = max(array_keys((array)$layouts));

						$v = array(
							'layoutID' => 0,
							'formID' => intval($this->post('formID')),
							'rowID' => intval($newRowID)+1
						);
						for ($i=0; $i<$this->post('cols'); $i++) {
							$l = new Layout();
							$l->save($v);
						}
						$this->json($suc);
					}

					$layouts = $this->layouts;
					$row = $layouts[intval($this->post('rowID'))];
					$row_columns = intval(count($row));

					if ($row_columns < intval($this->post('cols'))) {
						$v = array(
							'layoutID' => 0,
							'formID' => intval($this->post('formID')),
							'rowID' => intval($this->post('rowID'))
						);

						for ($i=0; $i<( intval($this->post('cols')) - $row_columns); $i++) {
							$l = new Layout();
							if ($l->save($v)) $this->json($suc);
						}
					}
					elseif ($row_columns > intval($this->post('cols'))) {
						$delete = array_slice((array)$row, intval($this->post('cols')), NULL, true);
						foreach ($delete as $l) {
							$elements = $l->getElements();
							if ($elements && count($elements) && is_array($elements)) return false;
						}
						foreach($delete as $l) {
							$l = Layout::getByID($l->layoutID);
							if (is_object($l)) $l->delete();
						}
						$this->json($suc);
					}
				}
				if (intval($this->post('rowID')) < 0 && !$this->post('label')) $this->json($r);
				$v = array(
					'rowID' => intval($this->post('rowID')),
					'label' => h($this->post('label')),
					'appearance' => $this->post('appearance'),
					'css' => intval($this->post('css')),
					'css_value' => intval($this->post('css'))!=0?$this->post('css_value'):''
				);
				if ($l->save($v)) $this->json($suc);
			}
		}
		$this->json($r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$err = array(
				'type' => 'error',
				'message' => t('Error: Layout can\'t be deleted')
			);
			$suc = array(
				'type' => 'info',
				'message' => t('Layout is successfully deleted')
			);
			$l = Layout::getByID($this->post('layoutID'));
			if (!is_object($l)) {
				if (intval($this->post('rowID')) < 0 && !$this->post('cols')) $this->json($err);
				$row = $this->layouts[intval($this->post('rowID'))];
				foreach ($row as $layout) {
					$elements = $layout->getElements();
					if ($elements && count($elements) && is_array($elements)) {
						$this->json(array(
							'type' => 'error',
							'message' => t('Layout isn\'t empty and can\'t be deleted. Please move or delete elements'))
						);
					}
				}
				foreach($row as $layout) {
					$l = Layout::getByID($layout->layoutID);
					if (is_object($l)) $l->delete();
				}
				$this->json($suc);
			}
			$elements = $l->getElements();
			if ($elements && count($elements) && is_array($elements)) {
				$this->json(array(
					'type' => 'error',
					'message' => t('Layout isn\'t empty and can\'t be deleted. Please move or delete elements'))
				);
			}
			$l->delete();

			$this->json($suc);
		}
		$this->json($r);
	}

	public function order() {
		$r = $this->validateAction();
		if ($r === true) {
			$row_query = $sort_query = '';
			$f = Form::getByID($this->post('formID'));
			if (!is_object($f)) return false;
			$db = Database::connection();
			if (count($this->post('rows'))) {
				$i = 0;
				foreach ($this->post('rows') AS $key => $row) {
					$layouts = $db->fetchAll("SELECT layoutID FROM FormidableFormLayouts WHERE rowID = ? AND formID = ? ORDER BY rowID ASC, sort ASC", array($row, $f->getFormID()));
					if ($layouts && count($layouts) && is_array($layouts)) {
						foreach ($layouts as $layoutID) {
							$row_query .= ' WHEN layoutID = '.intval($layoutID['layoutID']).' THEN '.intval($key).' ';
							$sort_query .= ' WHEN layoutID = '.intval($layoutID['layoutID']).' THEN '.intval($i).' ';
							$i++;
						}
					}
				}
				if (!empty($row_query) && !empty($sort_query)) $db->executeQuery("UPDATE FormidableFormLayouts SET rowID = CASE ".$row_query." END, sort = CASE ".$sort_query." END WHERE formID = ?", array($f->getFormID()));
			}
			if (count($this->post('cols'))) {
				$i = 0;
				foreach ($this->post('cols') AS $key => $col) {
					$sort_query .= ' WHEN layoutID = '.intval($col).' THEN '.intval($i).' ';
					$i++;
				}
				if (!empty($sort_query)) $db->executeQuery("UPDATE FormidableFormLayouts SET sort = CASE ".$sort_query." END WHERE rowID = ? AND formID = ?", array(intval($this->post('rowID')), $f->getFormID()));
			}
			$r = array(
				'type' => 'info',
				'message' => t('Successfully moved layout')
			);
		}
		$this->json($r);
	}

	private function json($array) {
		echo Json::encode($array);
		die();
	}

}
