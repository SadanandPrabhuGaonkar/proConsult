<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates;

use \Concrete\Package\FormidableFull\Src\Formidable\Template;
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
				'message' => t('Error: Template can\'t be duplicated')
			);
			$t = Template::getByID($this->post('templateID'));
			if (is_object($t)) {						
				if ($t->duplicate()) {
					$r = array(
						'type' => 'info', 
						'message' => t('Template successfully duplicated')
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
				'message' => t('Error: Template can\'t be deleted')
			);
			$t = Template::getByID($this->post('templateID'));
			if (is_object($t)) {						
				if ($t->delete()) {
					$r = array(
						'type' => 'info', 
						'message' => t('Template is successfully deleted')
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
