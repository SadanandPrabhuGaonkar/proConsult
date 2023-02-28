<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates;

use \Concrete\Package\FormidableFull\Src\Formidable\TemplateList AS FTList;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Page;
use Permissions;
use Core;

class TemplateList extends BackendInterfaceController {
	
	protected $viewPath = '/dialogs/templates/template_list';

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
			$list = new FTList();
			$templates = $list->getResults();
			$this->set('templates', $templates);
		}
		$this->set('errors', $r);		
	}
}
