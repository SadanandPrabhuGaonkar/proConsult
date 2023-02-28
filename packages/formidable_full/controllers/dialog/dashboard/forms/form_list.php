<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms;

use \Concrete\Package\FormidableFull\Src\Formidable\FormList AS FFList;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Page;
use Permissions;
use Core;

class FormList extends BackendInterfaceController {
	
	protected $viewPath = '/dialogs/forms/form_list';

	protected function validateAction($tk = 'formidable_form') {
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
			$list = new FFList();
			$forms = $list->getResults();
			$this->set('forms', $forms);
		}
		$this->set('errors', $r);		
	}
}
