<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable\Validator;

use \Concrete\Package\FormidableFull\Src\Formidable;

class Dependency extends Formidable {
	
	protected $dependencies = '';

	protected $errors = array();

	public function setData($data) {
		$this->dependencies = $data;
	}
	public function addError($error) {		
		$this->errors[] = $error;	
	}
	public function getList() {		
		return count($this->errors)?$this->errors:false;	
	}

	public function validate() {		
		$rule = 1;
		foreach ((array)$this->dependencies as $dependency) {
			$actions = $elements = $tmp_action = array();
			foreach ((array)$dependency['action'] as $action) {
				$actions[] = array_filter(array(
					'action' => $action['action'],
					'action_value' => $action['action_value'],
					'action_select' => $action['action_select'])
				);
				$tmp_action[] = $action['action'];
			}
			foreach ((array)$dependency['element'] as $element) {
				$elements[] = array_filter(array(
					'element' => $element['element'],
					'element_value' => $element['element_value'],
					'condition' => $element['condition'],
					'condition_value' => $element['condition_value'])
				);
			}
			if (count($actions)) {
				if (array_unique($tmp_action) != $tmp_action) $this->addError(t('Dependency Rule #%s: %s is already used', $rule, $rule));
				else {	
					foreach ($actions as $a) {
						if (!empty($a['action'])) {	
							if ($a['action'] == 'class' || $a['action'] == 'placeholder')	{
								if (empty($a['action_value'])) $this->addError(t('Dependency Rule #%s: %s is invalid or not selected', $rule, $a['action']));
							} elseif ($a['action'] == 'value')	{
								if (empty($a['action_value']) && empty($a['action_select'])) $this->addError(t('Dependency Rule #%s: %s is invalid or not selected', $rule, $a['action']));							
							}						
							if (!count($elements)) $this->addError(t('Dependency Rule #%s: no depending element selected', $rule));	
							else {
								foreach ($elements as $_element) {			
									if (empty($_element['element'])) $this->addError(t('Dependency Rule #%s: no depending element selected', $rule));									
									if ($_element['condition'] != 'enabled' && $_element['condition'] != 'disabled' && $_element['condition'] != 'empty' && $_element['condition'] != 'not_empty' && !empty($_element['condition'])) {
										if (empty($_element['condition_value'])) $this->addError(t('Dependency Rule #%s: condition value is invalid', $rule));
									}
								}
							}								
						}
					}
				}
			}			
			$rule++;		
		}
	}
}