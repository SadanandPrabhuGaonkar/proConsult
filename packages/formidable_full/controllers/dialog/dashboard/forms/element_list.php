<?php
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Page;
use Permissions;
use Core;

class ElementList extends BackendInterfaceController {

	protected $viewPath = '/dialogs/forms/element_list';

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
			$f = Form::getByID(intval($this->post('formID')));
			if (is_object($f)) $layout = $f->getLayout();
			if (!count($layout) && is_array($layout)) $r = array('message' => t('Form is empty or corrupt. Please remove form and create a new one.'));
			$this->set('layouts', $layout);
		}
		$this->set('errors', $r);
	}
}
