<?php
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Core;
use Page;
use Permissions;

class Dependency extends BackendInterfaceController {

	protected $viewPath = '/dialogs/elements/dependency';

	protected $element = '';

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
		$el = Element::getByID($this->get('elementID'));
		if (is_object($el)) {
			$this->element = $el;
			$this->set('current_element', $this->element);
		}
	}

	public function add() {
		$r = $this->validateAction();
		if ($r === true) {
			$rule = intval($this->get('rule'));
			$el = $this->element;
			if (is_object($el)) {
				$dependency = $el->getDependencyRule($rule);
				if (!$dependency) $dependency = array();
				$this->set('dependency', $dependency);
				$this->set('rule', $rule);
			}
		}
	}

	public function action($dependency_rule = '', $rule = '') {
		$r = $this->validateAction();
		if ($r === true) {
			$el = $this->element;
			if (is_object($el)) {
				if (empty($dependency_rule)) $dependency_rule = intval($this->get('dependency_rule'));
				if (empty($rule)) $rule = intval($this->get('rule'));

				$dependency = $el->getDependencyRule($dependency_rule);
				if (!$dependency) $dependency = array();

				$dependency_action = !empty($dependency['actions'][$rule])?$dependency['actions'][$rule]:array();

				$actions = array(
					'' => t('Select behaviour'),
					'show' => t('Show'),
					'enable' => t('Enable'),
					'class' => t('Toggle classname')
				);

				if ($el->getDependencyProperty('has_placeholder_change') === true) $actions['placeholder'] = t('Change placeholder to');
				if ($el->getDependencyProperty('has_value_change') === true) $actions['value'] = t('Change value to');

				$values = array();
				$options = $el->getProperty('options')?$el->getPropertyValue('options'):array();
                $opt_count = is_array($options) ? count($options) : 0 ;
				if ($opt_count) {
					for ($i=0; $i<count($options); $i++) {
						if (empty($options[$i]['value'])) $options[$i]['value'] = $options[$i]['name'];
						$values[$options[$i]['value']] = $options[$i]['name'];
					}
				}
				$this->set('rule', $dependency_rule);
				$this->set('action_rule', $rule);

				$this->set('action', array(
						'dependency_action' => $dependency_action,
						'actions' => $actions,
						'values' => $values
					)
				);
			}
		}
	}

	public function element($dependency_rule = '', $rule = '') {
		$r = $this->validateAction();
		if ($r === true) {
			$el = $this->element;
			if (is_object($el)) {
				if (empty($dependency_rule)) $dependency_rule = intval($this->get('dependency_rule'));
				if (empty($rule)) $rule = intval($this->get('rule'));

				$dependency = $el->getDependencyRule($dependency_rule);
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

				$f = Form::getByID($el->getFormID());
				if (!is_object($f))	return false;

				$elements = $f->getElements();
                $elem_count = is_array($elements) ? count($elements) : 0 ;
				if ($elem_count) {
					foreach($elements as $element) {
						if ($element->isLayout() || $element->getElementID() == $el->getElementID()) continue;
						$els[$element->getElementID()] = $element->getLabel();
						if ($element->getElementID() == $dependency_element['element']) {
							$options = $element->getProperty('options')?$element->getPropertyValue('options'):array();
							if ($options && count($options) && is_array($options)) {
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
			$dependency_rule = intval($this->get('rule'));
			$el = $this->element;
			if (is_object($el)) {
				$dependency = $el->getDependencyRule($dependency_rule);
				if (!$dependency) $dependency = array();
				$this->set('dependency', $dependency);
				$this->set('rule', $dependency_rule);
			}
		}
	}
}
