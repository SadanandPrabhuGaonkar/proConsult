<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Mailing;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Core;
use Page;
use Permissions;

class Dependency extends BackendInterfaceController {
	
	protected $viewPath = '/dialogs/mailings/dependency';

	protected $mailing = '';

	protected function validateAction($tk = 'formidable_dependency') {
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
		$mailing = Mailing::getByID($this->get('mailingID'));			
		if (is_object($mailing)) {
			$this->mailing = $mailing;
			$this->set('current_mailing', $this->mailing);
		}
	}

	public function add() {		
		$r = $this->validateAction();
		if ($r === true) {
			$rule = intval($this->get('rule'));			
			$mailing = $this->mailing;
			if (is_object($mailing)) {
				$dependency = $mailing->getDependencyRule($rule);
				if (!$dependency) $dependency = array();
				$this->set('dependency', $dependency);
				$this->set('rule', $rule);		
			}
		}
	}

	public function action($dependency_rule = '', $rule = '') {
		$r = $this->validateAction();
		if ($r === true) {
			$mailing = $this->mailing;
			if (is_object($mailing)) {
				if (empty($dependency_rule)) $dependency_rule = intval($this->get('dependency_rule'));
				if (empty($rule)) $rule = intval($this->get('rule'));

				$dependency = $mailing->getDependencyRule($dependency_rule);
				if (!$dependency) $dependency = array();
				
				$dependency_action = !empty($dependency['actions'][$rule])?$dependency['actions'][$rule]:array();

				$actions = array(
					'' => t('Select behaviour'),
					'send' => t('Send')
				);

				$this->set('rule', $dependency_rule);
				$this->set('action_rule', $rule);

				$this->set('action', array(
						'dependency_action' => $dependency_action, 
						'actions' => $actions, 
					)
				);
			}
		}
	}

	public function element($dependency_rule = '', $rule = '') {
		$r = $this->validateAction();
		if ($r === true) {
			$mailing = $this->mailing;
			if (is_object($mailing)) {
				if (empty($dependency_rule)) $dependency_rule = intval($this->get('dependency_rule'));
				if (empty($rule)) $rule = intval($this->get('rule'));

				$dependency = $mailing->getDependencyRule($dependency_rule);
				if (!$dependency) $dependency = array();
				
				$dependency_element = !empty($dependency['elements'][$rule])?$dependency['elements'][$rule]:array();
	
				$conditions = array(
					'enabled' => t('is enabled'),
					'disabled' => t('is disabled'),
					'empty' => t('is empty'),
					'not_empty' => t('is not empty')
				);			
				$els = array(
					'' => t('Select an element')
				);

				$f = Form::getByID($mailing->getFormID());
				if (!is_object($f))	return false;

				$elements = $f->getElements();
                $elem_count = is_array($elements) ? count($elements) : 0 ;
				if ($elem_count) {
					foreach($elements as $element) {				
						if ($element->isLayout() || $element->getElementID() == $mailing->getElementID()) continue;						
						$els[$element->getElementID()] = $element->getLabel();												
						if ($element->getElementID() == $dependency_element['element']) {
							$options = $element->getProperty('options')?$element->getPropertyValue('options'):array();
                            $option_count = is_array($options) ? count($options) : 0 ;
							if ($option_count) {
								// unset empty conditions
								unset($conditions['empty'], $conditions['not_empty']);
								$element_values['any_value'] = t('any value');
								$element_values['no_value'] = t('no value');
								for ($i=0; $i<count($options); $i++) {							
									if (empty($options[$i]['value'])) $options[$i]['value'] = $options[$i]['name'];
									$element_values[$options[$i]['value']] = $options[$i]['name'];
								}
							} else {
								$conditions = array_merge($conditions, array(
									'equals' => t('equals'),
								   	'not_equals' => t('not equal to'),
								   	'contains' => t('contains'),
								   	'not_contains' => t('does not contain')
								));
							}	
						}				
					}
				}
				$this->set('rule', $dependency_rule);
				$this->set('element_rule', $rule);			
				$this->set('element', array(
						'dependency_element' => $dependency_element, 
						'elements' => $els,
						'conditions' => $conditions,
						'values' => $element_values,
					)
				);
			}
		}
	}

	public function delete() {		
		$r = $this->validateAction();
		if ($r === true) {
			$rule = intval($this->get('rule'));			
			$mailing = $this->mailing;
			if (is_object($mailing)) {
				$dependency = $mailing->getDependencyRule($dependency_rule);
				if (!$dependency) $dependency = array();
				$this->set('dependency', $dependency);
				$this->set('rule', $rule);		
			}
		}
	}
}
