<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Package\FormidableFull\Src\Formidable;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Property as ValidatorProperty;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Dependency as ValidatorDependency;
use \Concrete\Package\FormidableFull\Src\Helpers\LinkHelper;
use Database;
use Core;
use User;

class Element extends Formidable {
	
	public $searchable = true;

	public static function getByID($elementID) {
		if (intval($elementID) == 0) return false;
		$db = Database::connection();					
		$element = $db->fetchAssoc("SELECT elementID, element_type FROM FormidableFormElements WHERE elementID = ?", array($elementID));	
		if ($element) {
			$f = new Formidable();
			return $f->loadElement($element['element_type'], $element['elementID']);	
		}	
		return false;
	}

	public function load($elementID) {		
		if (intval($elementID) == 0) return false;		
		$db = Database::connection();					
		$element = $db->fetchAssoc("SELECT * FROM FormidableFormElements WHERE elementID = ?", array($elementID));	
		if (!$element) return false;		
		
		$this->setAttributes($element);
		
		// Replace params for more readable vars
		$params = unserialize($this->getParams());
		$params['chars_allowed_value'] = @explode(',', $params['chars_allowed_value']);	
		$params['label_hide'] = $this->label_hide==1?true:false;
		//if (empty($params['options']) || !count($params['options'])) $params['options'] = array(array('selected' => 0, 'name' => ''));	
		$this->setAttribute('property_value', $params);
		unset($this->params);

		// Set some default attributes
		$attributes = array();
		if ($this->getPropertyValue('placeholder') && $this->getPropertyValue('placeholder_value')) $attributes['placeholder'] = $this->getPropertyValue('placeholder_value');
		if ($this->getPropertyValue('css') && $this->getPropertyValue('css_value')) $attributes['class'] = $this->getPropertyValue('css_value');
		if ($this->getPropertyValue('tooltip') && $this->getPropertyValue('tooltip_value')) {
			$attributes['title'] = $this->getPropertyValue('tooltip_value');
			$attributes['data-toggle'] = 'tooltip';
		};

		if(is_array($attributes)) {
            if (count($attributes)) $this->setAttribute('attributes', array_filter($attributes));
        }

		// Now initialize dependencies for this element
		$this->initializeDependency(unserialize($this->getDependencies()));

		return true;
	}

	public function getByHandle($handle) {
		$db = Database::connection();
		$elementID = $db->fetchColumn("SELECT elementID FROM FormidableFormElements WHERE label_import = ?", array($handle));
		if (intval($elementID) == 0) return false;	
		$item = new Element();
		if ($item->load($elementID)) return $item;
		return false;
	}

	public function getElementID() {
		return is_numeric($this->elementID)?$this->elementID:false;
	}
	public function getLayoutID() {
		return is_numeric($this->layoutID)?$this->layoutID:false;
	}

	public function isLayout() {
		return !empty($this->is_layout)?$this->is_layout:false;
	}

	public function getProperty() {
		$key = func_get_args();
		if(is_array($key)) {
            if (count($key) == 1) return !empty($this->properties[$key[0]])?$this->properties[$key[0]]:false;
            if (count($key) == 2) return !empty($this->properties[$key[0]][$key[1]])?$this->properties[$key[0]][$key[1]]:false;
            if (count($key) == 3) return !empty($this->properties[$key[0]][$key[1]][$key[2]])?$this->properties[$key[0]][$key[1]][$key[2]]:false;
        }
	}
	public function getPropertyValue($key) {
		if ($key=='min_max_type') return !empty($this->properties['min_max'][$key])?strtolower($this->properties['min_max'][$key]):t('characters');
		return array_key_exists($key, (array)$this->property_value)?$this->property_value[$key]:false;
	}
	public function setPropertyValue($key, $value) {
		$this->property_value[$key] = $value;	
	}

	public function getErrorText($errorCode, $args) {
		if (intval($this->getPropertyValue('errors')) == 0) return false;
		$errors = $this->getPropertyValue('errors_values');
		if (empty($errors)) return false;
		$error = $errors[str_replace('error_', '', strtolower($errorCode))];
		if (empty($error)) return false; 		
		$count = preg_match_all('/%s/', $error, $matches);
		if(is_array($args)) {
            if ($count > count($args)) {
                for ($i=count($args); $i<$count; $i++) $args[] = '%s';
            }
        }
		return vsprintf($error, $args);
	}
	
	public function save($data) {
		$th = Core::make('helper/text');
		// Do some checking before saving
        if(is_array($data)) {
            if (count($data)) {
                foreach ($data as $key => $value) {
                    if (in_array($key, array('dependencies', 'params', 'attributes'))) continue;
                    $data[$key] = $th->sanitize($value);
                }
            }
        }

		if (!$this->getElementID()) return $this->add($data);
		else return $this->update($data);	 
	}
	
	private function add($data) {			
		if(!$data['sort']) $data['sort'] = self::getNext(intval($data['formID']));		
		$db = Database::connection();
		$db->insert('FormidableFormElements', $data);
		$elementID = $db->lastInsertId();		
		if (empty($elementID)) return false;

		// Remove current column listing.
		self::clearColumnSet($this->getFormID());
		
		// Now setup a new handle	
		$this->elementID = $elementID;
		return $this->update(array('label' => $data['label']));		
	}
	
	private function update($data)	{					
		$db = Database::connection();	
		$th = Core::make('helper/text');		
		if ($data['label']) $data['label_import'] = $th->sanitizeFileSystem($data['label'].'_'.$this->getElementID());
		$db->update('FormidableFormElements', $data, array('elementID' => $this->getElementID()));		
		$this->load($this->getElementID());		
		return true;
	}
	
	public function duplicate($formID = 0, $layoutID = 0) {
		
		$db = Database::connection();					
		$element = $db->fetchAssoc("SELECT * FROM FormidableFormElements WHERE elementID = ?", array($this->getElementID()));	
		if (!$element) return false;
		
		// Set new params	
		if (intval($formID) != 0) $element['formID'] = $formID;
		if (intval($layoutID) != 0) $element['layoutID'] = $layoutID;
		if (intval($layoutID) == 0 && intval($layoutID) == 0) {
			$th = Core::make('helper/text');	
			$label = t('%s (copy)', $element['label']);
			$element['label'] = $label;
			$element['label_import'] = $th->sanitizeFileSystem($label);
		}		
		$element['sort'] = self::getNext($element['formID']);
		
		// Unset current elementID
		unset($element['elementID']);
				
		$ne = new Element();			
		if ($ne->add($element)) return $ne;
		return false;
	}
	
	public function delete() {
		$db = Database::connection();	
		$db->delete('FormidableFormElements', array('elementID' => $this->getElementID(), 'formID' => $this->getFormID()));
		
		// Reorder elements on form
		$this->orderElements();
		
		// Remove current column listing.
		self::clearColumnSet($this->getFormID());					
		return true;
	}
	
	public function validateResult() {
		$val = new ValidatorResult();
		$val->setElement($this);
		$val->setData($this->post());
		if ($this->getPropertyValue('required')) $val->required();
		if ($this->getPropertyValue('min_max')) $val->minMax();
		if ($this->getPropertyValue('option_other')) $val->other();
		if ($this->getPropertyValue('confirmation')) $val->confirmation();
		return $val->getList();	
	}

	public function validateProperty() {
		$val = new ValidatorProperty();
		$val->setData($this->post());
		if ($this->getProperty('label')) $val->label();
		if ($this->getProperty('placeholder')) $val->placeholder();
		if ($this->getProperty('default')) $val->defaultValue();
		if ($this->getProperty('mask')) $val->mask();
		if ($this->getProperty('min_max')) $val->minMax();
		if ($this->getProperty('tooltip')) $val->tooltip();
		if ($this->getProperty('tinymce')) $val->tinymce();
		if ($this->getProperty('html_code')) $val->htmlCode();
		if ($this->getProperty('options')) $val->options();
		if ($this->getProperty('option_other')) $val->other();
		if ($this->getProperty('appearance')) $val->appearance();
		if ($this->getProperty('format')) $val->format();
		if ($this->getProperty('advanced')) $val->advanced();
		if ($this->getProperty('allowed_extensions')) $val->allowedExtensions();
		if ($this->getProperty('fileset')) $val->fileset();
		if ($this->getProperty('css')) $val->css();
		if ($this->getProperty('submission_update')) $val->submissionUpdate();
		return $val->getList();	
	}
	
	public function validateDependency() {
		$val = new ValidatorDependency();
		$val->setElement($this);
		$val->setData($this->post('dependency'));
		$val->validate();			
		return $val->getList();		
	}

	public function getSerializedValue() {
		$value = $this->getValue();
		if(is_array($value)) {
            if (!count($value)) return '';
        }
		$result['value'] = $value;
		$other = $this->getOtherValue();
		if (!empty($other)) $result['value_other'] = $other;
		return serialize($result);
	}

	public function setValue($value = '', $force = false) {
		
		$this->setAttribute('value', $value);
		if ($force) { 			
			// Weird code to split values and other sh*t...
			if (is_array($value) && array_key_exists('value_other', $value)) $this->setAttribute('other_value', $value['value_other']);
			if (is_array($value) && array_key_exists('value', $value)) $this->setAttribute('value', $value['value']);
			return true;
		}
		// First find a post
		if ($this->post()) {			
			$value = $this->post($this->getHandle());
			// Do some checking before saving
			if (is_array($value)) {
			    if(count($value)) {
                    foreach ($value as $key => $v) {
                        if (is_string($v) ) $value[$key] = h($v);
                        else $value[$key] = array_map(function ($val) { return h($val); }, $v);
                    }
                }
            }
			// Now get other value (if there is)
			$other = $this->post($this->getHandle().'_other');
			if (!empty($other)) $this->setAttribute('other_value', h($other));

			$this->setAttribute('value', $value);
			return true;
		}
		
		// Find value based on result....
		$result = $this->getResult();
		if (!empty($result)) {
			$answer = $result->getAnswerByElementID($this->getElementID());
			if (!empty($answer)) {
				// Weird code to split values and other sh*t...
				if (is_array($answer) && array_key_exists('value_other', $answer)) $this->setAttribute('other_value', $answer['value_other']);
				if (is_array($answer) && array_key_exists('value', $answer)) $this->setAttribute('value', $answer['value']);
			}
			return true;
		}

		// If not found, set some default values
		$obj = false;
		$value = '';		
		if ($this->getPropertyValue('default_value_type') == 'value') $value = $this->getPropertyValue('default_value_value');
		if ($this->getPropertyValue('default_value_type') == 'request') $value = $this->post('default_value_value');
		if ($this->getPropertyValue('default_value_type') == 'collection_attribute') $obj = $this->getCollection();	
		if ($this->getPropertyValue('default_value_type') == 'user_attribute') $obj = $this->getUser();	
		if (is_object($obj)) {
			if (strpos($this->getPropertyValue('default_value_value'), 'ak_') !== false) {
				$value = $obj->getAttribute(substr($this->getPropertyValue('default_value_value'), 3));
				if (is_object($value)) {
					if (get_class($value) == 'DateTime') $value = $value->format('Y-m-d H:i:s');
					else $value = (string)$value;
				}
			}
			else {
				$th = Core::make('helper/text');
				$class = 'get'.$th->camelcase($this->getPropertyValue('default_value_value'));
				if (method_exists($obj, $class)) $value = $obj->{$class}();	
			}	
		}
		$this->setAttribute('value', $value);
		return true;
	}

	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$value = $this->getValue();	
		
		// Check if there is an other value
		if ($this->getProperty('options') && is_array($value) && @in_array('option_other', $value)) {
			$other = array_pop($value); 
			if (!empty($other)) array_push($value, $this->getPropertyValue('option_other_value').' '.$this->getDisplayOtherValue());
		}	

		if (is_array($value)) $value = @implode($seperator, $value); 		
		if (!$urlify) return h($value);
		return h($value);
		//$lh = new LinkHelper(); 
		//return $lh->url_and_email_ify(h($value));
	}

	public function getDisplayOtherValue($urlify = true) {
		$value = $this->getOtherValue();
		if (empty($value)) return '';
		if (!$urlify) return h($value);
		return h($value);
		//$lh = new LinkHelper(); 
		//return $lh->url_and_email_ify(h($this->other_value));
	}

	public function getDisplayValueExport($seperator = ' ', $urlify = true) {
		return $this->getDisplayValue($seperator, $urlify);
	}

	public function getDisplayResult() {
		return $this->getDisplayValue();
	}

	public function generateInput() {			
		$this->setAttribute('input', Core::make('helper/form')->text($this->getHandle(), $this->getValue(), $this->getAttributes()));
	}

	public function setFormat($format) {
		$this->format = $format;
		$input = $this->getInput();
		if (!empty($input)) $this->generateInput();
		return true;
	}

	public function updateOnSubmission($cID = 0) {			
		if (!$this->getPropertyValue('submission_update')) return true;
		
		$value = $this->getDisplayValue();
		if (!$this->getPropertyValue('submission_empty') == 1 && empty($value)) return true;
							
		if ($this->getPropertyValue('submission_update_type') == 'user_attribute') $obj = Formidable::getUser();	
		elseif ($this->getPropertyValue('submission_update_type') == 'collection_attribute') $obj = Formidable::getCollection($cID);	
			
		if (is_object($obj)) {			
			if (strpos($this->getPropertyValue('submission_update_value'), 'ak_') !== false) $obj->setAttribute(substr($this->getPropertyValue('submission_update_value'), 3), $value);	
			else {
				switch ($this->getPropertyValue('submission_update_value')) {
					case 'user_name': $obj->update(array('uName' => $value)); break;
					case 'user_email': $obj->update(array('uEmail' => $value)); break;	
					case 'user_password': $obj->update(array('uPassword' => $value, 'uPasswordConfirm' => $value)); break;
					default:
						$th = Core::make('helper/text');
						$class = 'set'.$th->camelcase($this->getPropertyValue('submission_update_value'));
						if (method_exists($obj, $class)) $obj->{$class}($value);
					break;	
				}														
			}
		}
		return true;
	}

	public static function getNext($formID) {			
		return parent::getNextSort('element', $formID);
	}
	
	public function initializeDependency($deps = '') {
		
		if (empty($deps)) {
			$this->setAttribute('dependencies', false);
			return false;
		} 		

		$th = Core::make('helper/text');

		foreach ((array)$deps as $rule => $dep) {									
			$actions = $elements = $etmp = array();
			
			foreach ($dep['actions'] as $a) {
				if ($a['action'] == 'enable') $actions['enable'] = true;			
				if ($a['action'] == 'show') $actions['show'] = true;					
				if ($a['action'] == 'value') $actions['value'] = $a['action_value'].$a['action_select'];				
				if ($a['action'] == 'placeholder') $actions['placeholder'] = $a['action_value'];			
				if ($a['action'] == 'class') $actions['class'] = $a['action_value'];	
			}
			
			foreach ($dep['elements'] as $er => $ea) {

				$e = Element::getByID($ea['element']);
				if (!is_object($e)) continue;
				
				$key = array_search($e->getHandle(), (array)$etmp);
				if ($key !== false) $er = $key;			
				
				$etmp[$er] = $elements[$er]['handle'] = $e->getHandle();
				$elements[$er]['elementID'] = $e->getElementID();
				$elements[$er]['type'] = $e->getElementType();

				// TODO
				// Recipient selector in this list?
				if (in_array($e->getElementType(), array('radio', 'checkbox', 'select'))) {
					$options = @array_filter((array)$e->getPropertyValue('options'));
					if(is_array($options)) {
                        if (count($options)) {
                            foreach ($options as $i => $o) {
                                if (empty($options[$i]['value'])) $options[$i]['value'] = $options[$i]['name'];
                                if ($e->getElementType() == 'select') $elements[$er]['options'][$options[$i]['value']] = $options[$i]['value'];
                                else $elements[$er]['options'][$options[$i]['value']] = $th->sanitizeFileSystem($e->getHandle()).($i+1);
                            }
                        }
                    }
				}

				if (!empty($ea['element_value']) && !in_array($ea['element_value'], (array)$elements[$er]['values'])) {
					$elements[$er]['values'][] = $ea['element_value'];			
				}
				if (!empty($ea['condition']) && in_array($ea['condition'], array('empty', 'not_empty'))) {					
					if ($ea['condition'] == 'empty') $elements[$er]['empty'][] = 1;					
					if ($ea['condition'] == 'not_empty') $elements[$er]['not_empty'][] = 1;		
				}
				if (!empty($ea['condition']) && !empty($ea['condition_value']) && !in_array($ea['condition_value'], (array)$elements[$er]['values'])) {					
					if ($ea['condition'] == 'contains') $elements[$er]['match'][] = $ea['condition_value'];					
					if ($ea['condition'] == 'not_contains') $elements[$er]['not_match'][] = $ea['condition_value'];							
					if ($ea['condition'] == 'equals') $elements[$er]['values'][] = $ea['condition_value'];					
					if ($ea['condition'] == 'not_equals') $elements[$er]['not_values'][] = $ea['condition_value'];							
				}
				
				// inverse values when no_value is selected...
				$inverse = false;
				if (@in_array('no_value', (array)$elements[$er]['values'])) $inverse = true;
			}
			
			if (!empty($actions) && !empty($elements)) {
				$dependencies[] = array(
					'actions' => $actions,
					'elements' => $elements,
					'inverse' => $inverse
				);
			}
		}		
		
		$validate = array();
		if (!empty($dependencies)) {			
			// Setup dependencies for validation
			foreach ($dependencies as $dep) {
				if (is_array($dep['actions']) && (array_key_exists('show', $dep['actions']) || array_key_exists('enable', $dep['actions']))) {						
					$rule = array();
					foreach ($dep['elements'] as $e) {
						$value = (array)$e['values'];
						if (count($e['options'])) {						
							if (in_array('any_value', $value)) {
								$value = (array)$e['options'];							
								if (in_array($e['type'], array('radio', 'checkbox'))) $value = array_keys((array)$e['options']);		
							} 
							elseif (in_array('no_value', $value)) $value = array();	
						}
						$rule[] = array(
							'element' => $e['handle'],
							'elementID' => $e['elementID'],
							'value' => $value
						);
					}
				}
				if (!empty($rule)) $validate[] = $rule;
			}		
		}

		$dep = array(
			'raw' => $deps,
			'validate' => $validate,
			'initialized' => $dependencies
		);
		$this->setAttribute('dependencies', $dep);

	}
	
	public function javascriptDependency() {

		$dependencies = $this->getDependency('initialized');
		if (!$dependencies || !count($dependencies)) return false;

		$th = Core::make('helper/text');

		// Build action
		foreach ($dependencies as $dependency) {				
			$method .= 'if (';
			foreach ($dependency['elements'] as $key => $element) {					
				if ($key > 0) $method .= ' || ';
															
				$_multi = false;
                if ($element['type'] != 'select' && $element['type'] != 'recipientselector') {
                    if (is_array($element['options']) && count($element['options'])) {
                        if (@in_array('any_value', (array)$element['values']) || @in_array('no_value', (array)$element['values'])) $method .= '(selector == \''.@implode('\' || selector == \'', $element['options']).'\') ';
                        else {
                            $_tmp_options = array();
                            foreach ((array)$element['values'] as $_value) {
                                $_tmp_options[] = $element['options'][$_value];
                            }
                            $method .= '(selector == \''.@implode('\' || selector == \'', $_tmp_options).'\') ';
                        }
                        $_multi = true;
                    }
                }
				if (!$_multi) $method .= 'selector == $(\'[name="'.$element['handle'].'"], [name^="'.$element['handle'].'["], [name^="'.$element['handle'].'_"]:first\').eq(0).attr(\'id\')';
			}
			$method .= ') { ';
			$method .= 'ccmFormidableUpdateDependency(\''.$this->handle.'\', [';
			$method_not .= 'ccmFormidableUpdateDependency(\''.$this->handle.'\', [';
																		
			foreach ($dependency['actions'] as $action => $value) {					
				switch ($action) {
					case 'value':
						if ($dependency['inverse']) {
							$method .= '[\'value\', \'\'],';	
							$method_not .= '[\'value\', \''.$value.'\'],';									
						} else { 
							$method .= '[\'value\', \''.$value.'\'],';	
							$method_not .= '[\'value\', \'\'],';								
						}
					break;
					case 'class':
						if ($dependency['inverse']) {
							$method .= '[\'class\', \''.$value.'\', \'remove\'],';	
							$method_not .= '[\'class\', \''.$value.'\', \'add\'],';									
						} else { 
							$method .= '[\'class\', \''.$value.'\', \'add\'],';	
							$method_not .= '[\'class\', \''.$value.'\', \'remove\'],';								
						}
					break;
					case 'placeholder':
						if ($dependency['inverse']) {
							$method .= '[\'placeholder\', \'\'],';	
							$method_not .= '[\'placeholder\', \''.$value.'\'],';									
						} else { 
							$method .= '[\'placeholder\', \''.$value.'\'],';	
							$method_not .= '[\'placeholder\', \'\'],';								
						}							
					break;
					case 'show':
						if ($dependency['inverse']) {
							$method .= '[\'hide\', true],';
							$method_not .= '[\'show\', true],';	
						} else {
							$method .= '[\'show\', true],';
							$method_not .= '[\'hide\', true],';
						}	
					break;
					case 'enable':
						if ($dependency['inverse']) {
							$method .= '[\'disable\', true],';	
							$method_not .= '[\'enable\', true],';
						} else {
							$method .= '[\'enable\', true],';	
							$method_not .= '[\'disable\', true],';
						}
					break;	
				}
			}
			
			$method = substr($method, 0, -1);
			$method_not = substr($method_not, 0, -1);
			
			$method .= ']); ';
			$method_not .= ']); ';
			$method .= '} ';
		}
		
		$javascript .= 'if (($(\'[name="'.$this->handle.'"], [name^="'.$this->handle.'["]\').length > 0) && ($.fn.dependsOn)) { ';
		$javascript .= '$(\'[name="'.$this->handle.'"], [name^="'.$this->handle.'["]\').dependsOn(';	
		
		foreach ($dependencies as $rule => $dependency) {

			if ($rule > 0) $javascript .= ').or(';								
			$javascript .= '{ ';				

			if(is_array($dependency['elements'])) {
                $last_key =  count($dependency['elements']);
            }

			foreach ($dependency['elements'] as $key => $element) {
									
				$_multi = false;

				if(is_array($element['values'])) {
                    if (count($element['values'])) {
                        foreach ($element['values'] as $e => $v) {
                            $element['values'][$e] = $v;
                        }
                    }
                }
			
				if ($element['type'] != 'select' && $element['type'] != 'recipientselector') {
				    if(is_array($element['options'])) {
                        if (count($element['options']) && (@in_array('any_value', (array)$element['values']) || @in_array('no_value', (array)$element['values']))) {
                            $javascript .= '\'[id="'.@implode('"], [id="', $element['options']).'"]\' : { ';
                            $_multi = true;
                        }elseif (count($element['options']) && (!@in_array('any_value', (array)$element['values']) && !@in_array('no_value', (array)$element['values']))) {
                            $_tmp_options = array();
                            foreach ((array)$element['values'] as $_value) {
                                $_tmp_options[] = $element['options'][$_value];
                            }
                            $javascript .= '\'[id="'.@implode('"], [id="', $_tmp_options).'"]\' : { ';
                            $_multi = true;
                        }
                    }
				}
				
				if (!$_multi) $javascript .= '\'[name="'.$element['handle'].'"], [name^="'.$element['handle'].'["], [name^="'.$element['handle'].'_"]:first\' : { ';
										
				if (!empty($element['values'])) {
					if ($element['type'] == 'checkbox' || $element['type'] == 'radio') $javascript .= 'checked: true ';
					else {
						$options = array();
						if (@in_array('any_value', (array)$element['values'])) $values = (array)$element['options'];
						else $values = (array)$element['values'];								
						foreach ($values as $key => $val) {
							$options[] = addslashes($th->decodeEntities($val)); 
						}								
						$javascript .= 'values: [\''.@implode('\', \'', $options).'\'] ';
					}
				}
				
				if (!empty($element['not_values'])) {
					if ($element['type'] == 'checkbox' || $element['type'] == 'radio') $javascript .= 'checked: false ';
					else {
						$options = array();
						if (@in_array('no_value', (array)$element['values'])) $values = (array)$element['options'];
						else $values = (array)$element['not_values'];								
						foreach ($values as $key => $val) {
							$options[] = addslashes($th->decodeEntities($val)); 
						}								
						$javascript .= 'not: [\''.@implode('\', \'', $options).'\'] ';
					}
				}

				if (!empty($element['empty'])) $javascript .= 'notmatch: /([^\s])/ ';					
				if (!empty($element['not_empty'])) $javascript .= 'match: /([^\s])/ ';
				if (!empty($element['match'])) $javascript .= 'match: /'.@implode('|', (array)$element['match']).'/gi ';					
				if (!empty($element['not_match'])) $javascript .= 'notmatch: /'.@implode('|', (array)$element['not_match']).'/gi ';							
				
				$javascript .= '} ';				
				if ($key < $last_key - 1) $javascript .= ', ';
			}				
			$javascript .= '} ';
			
			if ($rule == 0) {
				$javascript .= ', { ';
				$javascript .= 'disable: false, hide: false, ';
				$javascript .= 'onEnable: function(e, selector) { ';
				$javascript .= $method;
				$javascript .= '}, ';
				$javascript .= 'onDisable: function(e) { ';
				$javascript .= $method_not;
				$javascript .= '} ';
				$javascript .= '} ';		
			}
		}
		$javascript .= '); ';	
		$javascript .= '} ';
		return $javascript;
	}
}