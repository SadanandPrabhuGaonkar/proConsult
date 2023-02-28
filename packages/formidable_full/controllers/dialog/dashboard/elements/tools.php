<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Core\Controller\Controller;
use \Concrete\Core\Http\Service\Json as Json;
use Core;
use Page;
use Permissions;

class Tools extends Controller {
	
	protected function validateAction($tk = 'formidable_element') {
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
				'message' => t('Error: "%s" can\'t be added or updated', $this->post('label'))
			);

			$f = Form::getByID($this->post('formID'));
			if (is_object($f)) {			
				$el = $f->loadElement($this->post('element_type'), $this->post('elementID'));	
				if (is_object($el)) {			
					$th = Core::make('helper/text');
					if (!empty($el->handle)) $old_handle = $el->handle;					
					$data = $this->post();								
					if ($data['options_name'] && count($data['options_name'])) {
						foreach($data['options_name'] as $key => $value) {
							if (trim($value) != '') {
								$data['options'][] = array(
									'selected' => @in_array($key, $data['options_selected']),
								    'name' => $th->entities($th->sanitize($value)),
								    'value' => $th->entities($th->sanitize($data['options_value'][$key]))
								); 	
							}
						}
					}
                    $data_errors = is_array($data['error']) ? count($data['error']) : 0 ;
					if (is_array($data_errors) && count($data_errors)) {
						foreach($data['error'] as $key => $value) {
							if (trim($value) != '') {
								$data['errors_values'][$key] = h($value);  	
							}
						}
					}							
					$params = array(
						'placeholder' => intval($data['placeholder']),
					    'placeholder_value' => $data['placeholder_value'],
					    'default_value' => intval($data['default_value']),
						'default_value_type' => $data['default_value_type'],
					    'default_value_value' => $data['default_value_'.$data['default_value_type']],
					    'html_value' => $data['html_value'],
					    'code_value' => $data['code_value'],
						'content' => $data['content'],
					    'required' => intval($data['required']),
					    'min_max' => intval($data['min_max']),
					    'min_value' => intval($data['min_value']),
					    'max_value' => intval($data['max_value']),
					    'min_max_type' => $data['min_max_type'],	
					    'confirmation' => intval($data['confirmation']),	
					    'chars_allowed' => intval($data['chars_allowed']),
					    'chars_allowed_value' => @implode(',',$data['chars_allowed_value']),			   
					    'mask' => intval($data['mask']),
					    'mask_format' => $data['mask_format'],
					    'tooltip' => intval($data['tooltip']),
					    'tooltip_value' => $data['tooltip_value'],
					    'options' => is_array($data["options"]) ? count($data['options'])?$data['options']:'':"",
					    'option_other' => intval($data['option_other']),
					    'option_other_value' => $data['option_other_value'],
					    'option_other_type' => $data['option_other_type'],
					    'multiple' => intval($data['multiple']),
					    'format' => $data['format'],
					    'format_other' => $data['format_other'],
					    'appearance' => $data['appearance'],				   
					    'advanced' => intval($data['advanced']),
					    'advanced_value' => $th->entities($data['advanced_value']),
					    'allowed_extensions' => intval($data['allowed_extensions']),
					    'allowed_extensions_value' => $data['allowed_extensions_value'],
					    'fileset' => intval($data['fileset']),
					    'fileset_value' => intval($data['fileset_value']),
					    'css' => intval($data['css']),
					    'css_value' => $data['css_value'],
						'submission_update' => intval($data['submission_update']),
						'submission_update_type' => $data['submission_update_type'],
					    'submission_update_value' => $data['submission_update_'.$data['submission_update_type']],
						'submission_update_empty' => intval($data['submission_update_empty']),
						'errors' => intval($data['errors']),
						'errors_values' => is_array($data["errors_values"]) ? count($data['errors_values'])?$data['errors_values']:'':"",
					);
					
					// Do some checking before saving
					foreach ($params as $key => $value) {
						if (!is_array($value)) {
							$params[$key] = h($value);
						} 
					}

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
														   
					$v = array(
						'formID' => $f->getFormID(),
					    'layoutID' => intval($data['layoutID']),
					    'element_type' => h($data['element_type']),
					    'element_text' => h($data['element_text']),
					    'label' => h($data['label'].$data['label_sufix']),
					    'label_hide' => intval($data['label_hide']),
					    'params' => serialize($params),
					    'dependencies' => serialize($dependencies)
					);
				
					if ($el->save($v)) {		
						//Convert new element in mailing		
						$mailings = $f->getMailings();
                        $mailings_count =   $elem_count = is_array($mailings) ? count($mailings) : 0 ;
						if ($mailings_count) {
							foreach ($mailings as $mailing) {
								$mailing->updateElementHandle($old_handle, $el->getHandle());						
							}
						}
						$r = array(
							'type' => 'info', 
							'message' => t('Field "%s" is successfully added or updated', h($data['label'].$data['label_sufix']))
						);
					}
				}
			}			
		}
		$this->json($r);
	}
	
	public function bulk() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error', 
				'message' => t('Error: Field can\'t be updated')
			);						
				
			$th = Core::make('helper/text');								
			$data = $this->post();	

			$options = explode(PHP_EOL, $data['options']);
            $option_count = is_array($options) ? count($options) : 0 ;
			if ($option_count) {
				$rows = array();
				foreach($options as $value) {
					if (trim($value) != '') {
						$rows[] = $th->entities($th->sanitize($value));							    
					}
				}
				$r = array(
					'type' => 'success', 
					'options' => $rows,
					'clear' => intval($data['clear'])
				);
			}							
		}
		$this->json($r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error', 
				'message' => t('Error: Field can\'t be deleted')
			);
			$el = Element::getByID($this->post('elementID'));					
			if (is_object($el)) {					
				if ($el->delete()) {
					$r = array(
						'type' => 'info', 
						'message' => t('Field successfully deleted')
					);
				}
			}			
		}
		$this->json($r);
	}

	public function duplicate() {
		$r = $this->validateAction();
		if ($r === true) {
			$r = array(
				'type' => 'error', 
				'message' => t('Error: Field can\'t be duplicated')
			);
			$el = Element::getByID($this->post('elementID'));					
			if (is_object($el)) {					
				if ($el->duplicate()) {
					$r = array(
						'type' => 'info', 
						'message' => t('Field successfully duplicated')
					);
				}
			}			
		}
		$this->json($r);
	}

	public function order() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (!is_object($f)) return false;
			$f->orderElements($this->get('elements'), $this->get('layout'));
			$r = array(
				'type' => 'info',
				'message' => t('Successfully moved element')
			);
		}
		$this->json($r);
	}
	
	public function validate() {	
		$r = $this->validateAction();
		if ($r === true) {
			$r = false;
			$f = new Form();					
			$el = $f->loadElement($this->post('element_type'), $this->post('elementID'));	
			if (!is_object($el)) $r = array(t('Error: Field can\'t be validated'));
			else {		
				$prop = $el->validateProperty();				
				$depe = $el->validateDependency();				
				$errors = array_merge($prop!=false?$prop:array(), $depe!=false?$depe:array());
				$count_errors = is_array($errors) ? count($errors) : 0 ;
				if ($errors && $count_errors) $this->json(array('type' => 'error', 'message' => $errors));
			} 
		}
		$this->json(array('type' => 'success'));
	}

	public function options() {				
		$th = Core::make('helper/text');
		$r = false;
		$el = Element::getByID($this->get('elementID'));
		if (is_object($el)) {			
			$options = $el->getPropertyValue('options')?$el->getPropertyValue('options'):array();
            $opt_count = is_array($options) ? count($options) : 0 ;
			if ($opt_count) {
				$values = array();
				for ($i=0; $i<count($options); $i++) {							
					if (!$options[$i]['value']) $options[$i]['value'] = $options[$i]['name'];
					$values[] = array(
						'value' => $options[$i]['value'],
						'name' => $th->decodeEntities($options[$i]['name'])
					);
				}
				$r = $values;
			}
		}
		$this->json($r);
	}

	private function json($array) {
		echo Json::encode($array);
		die();
	}

}
