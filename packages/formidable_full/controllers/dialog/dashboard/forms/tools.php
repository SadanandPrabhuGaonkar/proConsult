<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Core\Controller\Controller;
use \Concrete\Core\Http\Service\Json as Json;
use Page;
use Permissions;
use Core;

class Tools extends Controller {
	
	protected function validateAction($tk = 'formidable_form') {
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
	
	public function duplicate() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error', 
				'message' => t('Error: Form can\'t be duplicated')
			);
			$f = Form::getByID($this->post('formID'));					
			if (is_object($f)) {					
				if ($f->duplicate()) {
					$r = array(
						'type' => 'info', 
						'message' => t('Form successfully duplicated')
					);
				}
			}			
		}
		$this->json($r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error', 
				'message' => t('Error: Form can\'t be deleted')
			);
			$f = Form::getByID($this->post('formID'));	
			if (is_object($f)) {
				if ($f->delete()) {
					$r = array(
						'type' => 'info', 
						'message' => t('Form is successfully deleted')
					);
				}
			}
		}
		$this->json($r);
	}
	
	private function json($array) {
		echo Json::encode($array);
		die();
	}

}
