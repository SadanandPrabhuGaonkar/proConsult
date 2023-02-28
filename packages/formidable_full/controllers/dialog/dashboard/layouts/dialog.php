<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Layout;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Page;
use Permissions;
use Core; 

class Dialog extends BackendInterfaceController {

	protected $viewPath = '/dialogs/layouts/dialog';

	protected function validateAction($tk = 'formidable_layout') {
		$token = Core::make('token');
		if (!$token->validate($tk)) {
			return array(
				'type' => 'error',
				'message' => $token->getErrorMessage()
			);
		}
		return true;
	}

	protected function canAccess() {
		$c = Page::getByPath('/dashboard/formidable/');
		$cp = new Permissions($c);
		return $cp->canRead();
	}

	public function view() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) {
				$layouts = $f->getLayout();				
				if ($this->get('layoutID') == -1 && intval($this->get('rowID')) > -1) $layout = $layouts[intval($this->get('rowID'))];
				elseif ($this->get('layoutID') > -1) $layout = Layout::getByID($this->get('layoutID'));	
				else $layout = new Layout();			
				$this->set('layout', $layout);

				$this->set('appearances', array(
					'default' => t('Div'), 
					'fieldset' => t('Fieldset (with legend, if label exists)')
				));
			}
		}
		$this->set('errors', $r);
	}

	public function select() {
		$r = $this->validateAction('formidable_element');
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) $this->set('f', $f);
		}
		$this->set('errors', $r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) {				
				$layouts = $f->getLayout();				
				if ($this->get('layoutID') == -1 && intval($this->get('rowID')) > -1) $layout = $layouts[intval($this->get('rowID'))];
				elseif ($this->get('layoutID') > -1) $layout = Layout::getByID($this->get('layoutID'));	
				if (is_array($layout)) {
					foreach ($layout as $l) {
						if (isset($l->elements) && count($l->elements)) {
							$this->set('errors', array('message' => t('Layout isn\'t empty and can\'t be deleted. Please move or delete elements')));
							return;
						}
					}	
				} else {
					if (isset($layout->elements) && count($layout->elements)) {
						$this->set('errors', array('message' => t('Layout isn\'t empty and can\'t be deleted. Please move or delete elements')));
						return;
					}
				}
				$this->set('layout', $layout);
				$this->set('layoutID', $this->get('layoutID'));
				$this->set('rowID', $this->get('rowID'));
			}
		}
		$this->set('errors', $r);
	}
}