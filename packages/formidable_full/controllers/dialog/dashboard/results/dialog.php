<?php
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results;

use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use \Concrete\Package\FormidableFull\Src\Formidable\Result as Result;
use Page;
use Permissions;
use Core;

class Dialog extends BackendInterfaceController {

	protected $viewPath = '/dialogs/results/dialog';

	protected function validateAction($tk = 'formidable_result') {
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
		$c = Page::getByPath('/dashboard/formidable/results');
		$cp = new Permissions($c);
		return $cp->canRead();
	}

	public function view() {
		$r = $this->validateAction();
		if ($r === true) {
			$results = array();
			$request = \Concrete\Core\Http\Request::getInstance()->request();
			if ($request['item'] && count($request['item'])) {
				foreach ($request['item'] as $answerSetID) {
					$result = Result::getByID($answerSetID);
					if (is_object($result))	$results[] = $result;
				}
			}
			$this->set('results', $results);
		}
		$this->set('errors', $r);
	}

	public function delete() {
		$this->view();
	}

	public function resend() {
		$this->view();
	}
}
