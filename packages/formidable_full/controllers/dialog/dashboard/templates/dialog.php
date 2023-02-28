<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates;

use \Concrete\Package\FormidableFull\Src\Formidable\Template;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Core;
use Page;
use Permissions;

class Dialog extends BackendInterfaceController {

	protected $viewPath = '/dialogs/templates/dialog';

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

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$t = Template::getByID($this->get('templateID'));
			if (is_object($t)) $this->set('t', $t);		
		}
		$this->set('errors', $r);
	}	
}