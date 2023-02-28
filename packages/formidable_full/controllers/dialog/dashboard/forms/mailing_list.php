<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Page;
use Permissions;
use Core;

class MailingList extends BackendInterfaceController {
	
	protected $viewPath = '/dialogs/forms/mailing_list';

	protected function validateAction($tk = 'formidable_mailing') {
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
			$mailings = false;
			$f = Form::getByID(intval($this->post('formID')));		
			if (is_object($f)) $mailings = $f->getMailings(); 
			$this->set('mailings', $mailings);
		}
		$this->set('errors', $r);
	}
}
