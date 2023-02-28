<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Core;
use Page;
use Permissions;

class Dialog extends BackendInterfaceController {

	protected $viewPath = '/dialogs/elements/dialog';

	protected function validateAction($tk = 'formidable_element') {
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
			if (!is_object($f)) $r = array('message' => t('Form can\'t be found!'));
			else {
				$el = $f->loadElement($this->get('element_type'), $this->get('elementID'));					
				if (is_object($el)) {
					if (!$el->getFormID()) $el->formID = $this->get('formID');					
					if (!$el->getLayoutID()) $el->layoutID = $this->get('layoutID');
					$this->set('element', $el);
				}
			}				
		}
		$this->set('errors', $r);
	}

	public function select() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) {		
				$this->set('elements', $f->getElements());
				$this->set('advanced', $f->getAdvancedElements());
				$this->set('page', $f->getPageVariable());
				$this->set('user', $f->getUserVariable());
			}
		}
		$this->set('errors', $r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) {				
				$el = Element::getByID($this->get('elementID'));					
				if (is_object($el)) $this->set('element', $el);	
			}		
		}
		$this->set('errors', $r);
	}

	public function bulk() {
		$r = $this->validateAction();		
		$this->set('errors', $r);
	}		
}