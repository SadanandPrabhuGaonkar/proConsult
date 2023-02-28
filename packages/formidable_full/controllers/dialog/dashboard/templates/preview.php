<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates;

use \Concrete\Package\FormidableFull\Src\Formidable\Template;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use BlockType;
use Core;
use Page;
use Permissions;

class Preview extends BackendInterfaceController {
	
	protected $pkgHandle = 'formidable_full';

	protected $viewPath = '/dialogs/templates/preview';

	protected function validateAction($tk = 'formidable_preview') {
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

	public function __construct() {
		parent::__construct();
		$t = Template::getByID($this->get('templateID'));
		if (is_object($t)) $this->set('template', $t);			
	}

	public function view() {
		$this->preview();
	}

	private function preview() {		
		$r = $this->validateAction();
		$this->set('errors', $r);
	}
}