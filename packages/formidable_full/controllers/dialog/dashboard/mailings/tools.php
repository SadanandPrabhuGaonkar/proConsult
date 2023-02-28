<?php
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Mailing;
use \Concrete\Core\Controller\Controller;
use \Concrete\Core\Http\Service\Json as Json;
use Core;
use Page;
use Permissions;

class Tools extends Controller {

	protected function validateAction($tk = 'formidable_mailing') {
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

	public function save() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error',
				'message' => t('Error: Mailing can\'t be added or updated')
			);
			$suc = array(
				'type' => 'info',
				'message' => t('Mailing is successfully added or updated')
			);

			$f = Form::getByID($this->post('formID'));
			if (is_object($f)) {
				$m = new Mailing();
				if (intval($this->post('mailingID')) != 0) $m->load($this->post('mailingID'));
				$data = $this->post();
				$dependencies = array();
				if (!empty($data['dependency'])) {
					foreach ((array)$data['dependency'] as $dependency) {
						$_actions = $_elements = array();
						foreach ((array)$dependency['action'] as $action) {
							$_actions[] = array_filter(array(
								'action' => $action['action'],
						        'action_value' => $action['action_value'],
				 			    'action_select' => $action['action_select'])
							);
						}
						foreach ((array)$dependency['element'] as $element) {
							$_elements[] = array_filter(array(
								'element' => $element['element'],
				     			'element_value' => $element['element_value'],
				    			'condition' => $element['condition'],
								'condition_value' => $element['condition_value'])
							);
						}
						if (!empty($_actions) && !empty($_elements)) {
							$dependencies[] = array(
								'actions' => $_actions,
								'elements' => $_elements
							);
						}
					}
				}

				$attachment_files = array();
				if (count($data['attachment_files']) && is_array($data)) {
					foreach ($data['attachment_files'] as $fID) {
						if (intval($fID) != 0) $attachment_files[] = $fID;
					}
				}

				$v = array(
					'formID' => $f->getFormID(),
					'mailingID' => intval($data['mailingID']),
					'from_type' => $data['from_type'],
					'from_name' => h($data['from_name']),
					'from_email' => $data['from_email'],
					'reply_type' => $data['reply_type'],
					'reply_name' => h($data['reply_name']),
					'reply_email' => $data['reply_email'],
					'send' => @implode(',', $data['send']),
					'send_custom' => intval($data['send_custom']),
					'send_custom_value' => $data['send_custom_value'],
					'send_cc' => intval($data['send_cc']),
					'subject' => h($data['subject']),
					'message' => $data['message'],
					'templateID' => intval($data['templateID']),
					'discard_empty' => intval($data['discard_empty']),
					'discard_layout' => intval($data['discard_layout']),
					'dependencies' => !empty($dependencies)?serialize($dependencies):'',
					'attachment_elements' => @implode(',',$data['attachment_elements']),
					'attachment_files' => @implode(',', $attachment_files)
				);
				$m->save($v);
				$this->json($suc);
			}
		}
		$this->json($r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error',
				'message' => t('Error: Mailing can\'t be deleted')
			);
			$m = Mailing::getByID($this->post('mailingID'));
			if (is_object($m)) {
				if ($m->delete()) {
					$r = array('type' => 'info', 'message' => t('Mailing is successfully deleted'));
				}
			}
		}
		$this->json($r);
	}

	public function validate() {
		$r = $this->validateAction();
		if ($r === true) {
			$mailing = new Mailing();
			$prop = $mailing->validateProperty();
			$depe = $mailing->validateDependency();
			$errors = array_merge($prop!=false?$prop:array(), $depe!=false?$depe:array());
			if ($errors && count($errors) && is_array($errors)) $this->json(array('type' => 'error', 'message' => $errors));
		}
		$this->json(array('type' => 'success'));
	}

	public function duplicate() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error',
				'message' => t('Error: Mailing can\'t be duplicated')
			);

			$suc = array(
				'type' => 'info',
				'message' => t('Mailing is successfully duplicated')
			);

			$m = Mailing::getByID($this->post('mailingID'));
			if(is_object($m)) {
				$m->duplicate();
				$this->json($suc);
			}
		}
		$this->json($r);
	}


	private function json($array) {
		echo Json::encode($array);
		die();
	}

}
