<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Package\FormidableFull\Src\Formidable;
use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Template;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Property as ValidatorProperty;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Dependency as ValidatorDependency;
use Concrete\Core\Mail\Service as Mail;
use Core;
use Database;
use File;


class Mailing extends Formidable {	
	
	public $elements = array();

	public static function getByID($mailingID) {
		$item = new Mailing();
		if ($item->load($mailingID)) return $item;
		return false;
	}

	public function load($mailingID) {		
		if (intval($mailingID) == 0) return false;		
		$db = Database::connection();					
		$mailing = $db->fetchAssoc("SELECT * FROM FormidableFormMailings WHERE mailingID = ?", array($mailingID));	
		if (!$mailing) return false;		
		
		$this->setAttributes($mailing);
		
		// Make some variables readable
		$this->setAttribute('send', @explode(',', $this->send));	
		$this->setAttribute('send_type', intval($this->send_cc)==1?'cc':'bcc');
		unset($this->send_cc);

		// Attachments
		$this->setAttribute('attachment_elements', @explode(',', $this->attachment_elements));
		$this->setAttribute('attachment_files', @explode(',', $this->attachment_files));

		// Now initialize dependencies for this element
		$this->initializeDependency(unserialize($this->getDependencies()));

		return true;
	}

	public function getMailingID() {
		return is_numeric($this->mailingID)?$this->mailingID:false;
	}
	public function getFromDisplay() {				
		if ($this->getFromType() == 'other') return $this->getFromName().' ('.$this->getFromEmail().')';	
		$element = $this->getElementByID($this->getFromType());
		if (is_object($element)) return $element->getLabel().' ('.$element->getElementText().')';
		return t('Unknown');
	}

	public function setFormID($formID) {
		if (!isset($this->formID)) $this->formID = $formID;
	}

	public function getTemplates() {
		$db = Database::connection();	
		$result = $db->fetchAll("SELECT templateID, label FROM FormidableTemplates");	
		if (!$result) return false;
		$templates = array();
		foreach($result as $r) {
			$templates[$r['templateID']] = $r['label'];
		}
		return $templates;	
	}
	public function getTemplateID() {
		return (intval($this->templateID) != 0)?$this->templateID:false;
	}
	public function getTemplate() {
		return Template::getByID($this->getTemplateID());
	}

	public function setElements($elements) {
		$this->elements = $elements;
	}
	public function getElements() {
		return (isset($this->elements))?$this->elements:false;
	}

	public function setResult($result, $force = false) {	
		if (is_object($result)) parent::setResult($result);
		$elements = $this->getElements();
		if(is_array($elements)) {
            if (count($elements)) {
                foreach ($elements as $e) {
                    if (in_array($e->getElementType(), array('captcha', 'buttons'))) continue;
                    $this->elements[$e->getElementID()]->setValue($result->getAnswerByElementID($e->getElementID()), $force);
                    $this->elements[$e->getElementID()]->generateInput();
                }
            }
        }
	}

	public function save($data) {
	    if(is_array($data)) {
            if (count($data)) {
                foreach ($data as $key => $value) {
                    if (in_array($key, array('dependencies'))) continue;
                    $data[$key] = h($value);
                }
            }
        }
		if (!$this->getMailingID()) return $this->add($data);
		else return $this->update($data);	 
	}			
	
	private function add($data) {									
		$db = Database::connection();	
		$db->insert('FormidableFormMailings', $data);
		$mailingID = $db->lastInsertId();	
		if (empty($mailingID)) return false;
		$this->load($mailingID);		
		return true;
	}
	
	private function update($data) {					
		$db = Database::connection();	
		$db->update('FormidableFormMailings', $data, array('mailingID' => $this->getMailingID()));	
		$this->load($this->getMailingID());		
		return true;
	}
	
	public function updateElementHandle($old, $new) {
		$pattern = array('/{%'.$old.'.label%}/', '/{%'.$new.'.value%}/');
		$replace = array('{%'.$new.'.label%}', '{%'.$new.'.value%}');
		$message = preg_replace($pattern, $replace, $this->getMessage());
		$this->update(array('message' => $message));
	}

	public function duplicate($formID = 0) {
		$db = Database::connection();					
		$mailing = $db->fetchAssoc("SELECT * FROM FormidableFormMailings WHERE mailingID = ?", array($this->getMailingID()));	
		if (!$mailing) return false;

		unset($mailing['mailingID']);

		// Set new params	
		if (intval($formID) != 0) {
			$mailing['formID'] = $formID;
			$mailing['send'] = preg_replace_callback('/([0-9])/', array(&$this, '_replaceSingleElementID'), $mailing['send']);
			$mailing['from_type'] = preg_replace_callback('/([0-9])/', array(&$this, '_replaceSingleElementID'), $mailing['from_type']);	
			$mailing['reply_type'] = preg_replace_callback('/([0-9])/', array(&$this, '_replaceSingleElementID'), $mailing['reply_type']);			
			$mailing['message'] = preg_replace_callback('/({%.*)_([0-9])(.*%})/', array(&$this, '_replaceMessageElementID'), $mailing['message']);

			$elements = @explode(',', $mailing['attachment_elements']);
			if (is_array($elements) && count($elements)) {
				$mailing['attachment_elements'] = array();
				foreach ($elements as $elementID) {
					$mailing['attachment_elements'][] = preg_replace_callback('/([0-9]+)/', array(&$this, '_replaceStringElementID'), $elementID);
				}
			}
		}
		else {
			$th = Core::make('helper/text');	
			$mailing['subject'] = t('%s (copy)', $mailing['subject']);
		}
		$nm = new Mailing();			
		if ($nm->add($mailing)) return $nm;
		return false;
	}
	
	public function delete() {
		$db = Database::connection();	
		$db->delete('FormidableFormMailings', array('mailingID' => $this->getMailingID(), 'formID' => $this->getFormID()));
		return true;
	}
	
	public function validateProperty() {
		$val = new ValidatorProperty();
		$val->setData($this->post());
		$val->from();
		$val->sendTo();
		$val->subject();
		$val->message();		
		return $val->getList();	
	}	

	public function validateDependency() {
		$val = new ValidatorDependency();
		$val->setData($this->post('dependency'));
		$val->validate();			
		return $val->getList();		
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
				if ($a['action'] == 'send') $actions['send'] = true;			
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
					if (count($options)) {	
						foreach ($options as $i => $o) {						
							if (empty($options[$i]['value'])) $options[$i]['value'] = $options[$i]['name'];
							if ($e->getElementType() == 'select') $elements[$er]['options'][$options[$i]['value']] = $options[$i]['value'];
							else $elements[$er]['options'][$options[$i]['value']] = $th->sanitizeFileSystem($e->getHandle()).($i+1);
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
				if (is_array($dep['actions']) && array_key_exists('send', $dep['actions'])) {						
					$rule = array();
					foreach ($dep['elements'] as $e) {
						$value = (array)$e['values'];
						if (is_array($e['options']) && count($e['options'])) {
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

	public function checkDependencies() {			
		$send = true;		
		$dependencies = $this->getDependency('validate');
		if (empty($dependencies)) return $send;
		$or = array();
		foreach((array)$dependencies as $rule) {
			if (!empty($rule)) {					
				$and = array();				
				foreach((array)$rule as $dependency) {						
					$check = false;						
					$value = (array)$this->elements[$dependency['elementID']]->getValue();
					if (!empty($dependency['value'])) {
						if (array_intersect((array)$dependency['value'], (array)$value)) $check = true;
					} else {
						if (!empty($value)) $check = true;
					}						
					if ($dependency['inverse']) {
						if ($check) $check = false;
						else $check = true;
					}						
					$and[] = $check;
				}	
				if (in_array(false, (array)$and)) $or[] = false;
				else $or[] = true;
			}
		}
		if (!in_array(true, (array)$or)) $send = false;
		return $send;
	}

	public function send() {					

		// First check dependencies...
		$skip = $this->checkDependencies();
		if (!$skip) return false;

		$th = Core::make('helper/text');
		$mh = Core::make('helper/mail');									
					
		// Set Subject
		$mh->setSubject(html_entity_decode($this->prepareSubject(), ENT_QUOTES, 'UTF-8'));
								
		// Set From 
		$from_name = $this->getFromName();
		$from_email = $this->getFromEmail();		
		if (intval($this->getFromType()) != 0) {
			$element = $this->getElementByID($this->getFromType());
			if (is_object($element)) {
				$from_name = '';
				$from_email = $element->getValue();
			}
		}	
		$mh->from($from_email, html_entity_decode($from_name, ENT_QUOTES, 'UTF-8'));

		// Set Reply To 
		$reply_name = $this->getFromName();
		$reply_email = $this->getFromEmail();
		if ($this->getReplyType() == 'other') {
			$reply_name = $this->getReplyName();
			$reply_email = $this->getReplyEmail();
		}
		elseif (intval($this->getReplyType()) != 0) {
			$element = $this->getElementByID($this->getReplyType());
			if (is_object($element)) {
				$reply_name = '';
				$reply_email = $element->getValue();
			}
		}
		$mh->replyto($reply_email, html_entity_decode($reply_name, ENT_QUOTES, 'UTF-8'));
				
		// Set Custom send to	
		$sendTo = array();
		if (intval($this->getSendCustom()) != 0) $sendTo = @explode(',', $this->getSendCustomValue());

		// Find any elements to send to.
		$send = $this->getSend();
		if(is_array($send)) {
            if (count($send)) {
                foreach ($send as $s) {
                    $element = $this->getElementByID($s);
                    if (!is_object($element)) continue;
                    $value = $element->getValue();
                    if (empty($value)) continue;

                    // See if it's some sort of selector
                    if (!$element->getProperty('options')) $sendTo[] = $value;
                    else {
                        $options = $element->getPropertyValue('options');
                        if (empty($options)) continue;
                        $selected = array();
                        if (is_array($value)) $selected[] = $value[0];
                        else $selected = $value;
                        foreach ($options as $option) {
                            if (is_array($option) && in_array($option['name'], $selected)) $sendTo[] = array($option['value'], $option['name']);
                            elseif (!is_array($option) && in_array($option, $selected)) $sendTo[] = $option;
                        }
                    }
                }
            }
        }
				
		// Clean up sendTo array
		$sendTo = array_filter($sendTo);	

		// Set send to to the mailing
		if (is_array($sendTo) && count($sendTo)) {
			$first = true;
			foreach ($sendTo as $send) {
				$to_name = '';
				$to_mail = $send;				
				if (is_array($send)) {							
					$to_mail = $send[0];
					$to_name = $send[1];
				}
				if (!empty($to_mail)) {
					if ($first) $mh->to(trim($to_mail), trim($to_name));	
					else $mh->{$this->getSendType()}(trim($to_mail), trim($to_name));
				}
				$first = false;
			}
		}
				
		// Set Message
		$message = $this->prepareMessage();
		$mh->setBodyHTML($message);		
		//$mh->setBody($th->sanitize($message));				

		// Set attachments
		$files = $this->getAttachmentFiles();
		if(is_array($files)) {
            if (count($files)) {
                foreach ($files as $fID) {
                    $f = File::getByID($fID);
                    if (!is_object($f)) continue;
                    $fv = $f->getApprovedVersion();
                    $mh->addAttachment($f);
                }
            }
        }
		$elements = $this->getAttachmentElements();
		if(is_array($elements)) {
            if (count($elements)) {
                foreach ($elements as $elementID) {
                    $e = $this->elements[$elementID];
                    if (!is_object($e)) continue;
                    $result = $e->getValue();
                    if (!empty($result) && count($result)) {
                        foreach ($result as $f) {
                            if (!isset($f['fileID'])) continue;
                            $f = File::getByID($f['fileID']);
                            if (!is_object($f)) continue;
                            $fv = $f->getApprovedVersion();
                            $mh->addAttachment($f);
                        }
                    }
                }
            }
        }

		// Send the mail!				
		$mh->sendMail(true);
		$mh->reset();
						
		return true;
	}
	
	private function prepareSubject($format = '') {
		$th = Core::make('helper/text');
		$subject = $this->getSubject();
		$subject = $th->sanitize($subject);
		$subject = $this->prepareContent($subject, $format);
		return $subject;
	}

	private function prepareMessage($format = '') {
		$th = Core::make('helper/text');
		$message = $this->getMessage();
		$message = $th->decodeEntities($message);		
		// Check for a template, if there is add it to the message
		$template = $this->getTemplate();
		if (is_object($template)) {
			$content = $template->getContent();
			$content = $th->decodeEntities($content);
			$message = str_replace(array('<p>{%formidable_mailing%}</p>', '{%formidable_mailing%}'), $message, $content); 
		}
		$message = $this->prepareContent($message, $format);
		return $message;
	}

	private function prepareContent($content, $format = '') {				
		$_format = '%s: %s <br />';
		if ($format != '') $_format = $format;
		
		$labels = $values = array();

		// Change all paths to full urls						
		$content = $this->setAbsoluteURLs($content);
		
		// Load result
		$result = $this->getResult();

		// Convert all advanced elements in message				
		$string = '';
		$advanced = $this->getAdvancedElements();
		if(is_array($advanced)) {
            if (count($advanced)) {
                foreach ($advanced as $a) {

                    $labels[] = '/{%'.$a['handle'].'.label%}/';
                    $values[] = preg_quote($a['label']);

                    $labels[] = '/{%'.$a['handle'].'.value%}/';
                    $values[] = preg_quote($result->{$a['callback']}());

                    $string .= sprintf($_format, preg_quote($a['label']), preg_quote($a['value']));
                }
            }
        }

		$labels[] = '/{%all_advanced_data%}/';
		$values[] = $string;

		// Convert all page attributes in message				
		$string = '';
		$attributes = $this->getPageVariable();
		if(is_array($attributes)) {
            if (count($attributes)) {
                foreach ($attributes as $a) {
                    $labels[] = '/{%'.$a['handle'].'.label%}/';
                    $values[] = preg_quote($a['label']);

                    $labels[] = '/{%'.$a['handle'].'.value%}/';
                    $values[] = preg_quote($result->{$a['callback']}($a['handle']));
                }
            }
        }

		// Convert all user attributes in message				
		$string = '';
		$attributes = $this->getUserVariable();
		if(is_array($attributes)) {
            if (count($attributes)) {
                foreach ($attributes as $a) {
                    $labels[] = '/{%'.$a['handle'].'.label%}/';
                    $values[] = preg_quote($a['label']);

                    $labels[] = '/{%'.$a['handle'].'.value%}/';
                    $values[] = preg_quote($result->{$a['callback']}($a['handle']));
                }
            }
        }
				
		// Convert all form elements in message									
		$string = '';
		$elements = $this->getElements();
		if(is_array($elements)) {
            if (count($elements))  {
                foreach ($elements as $element) {
                    if (in_array($element->getElementType(), array('captcha', 'buttons'))) continue;

                    // Should show or not...
                    $show = true;
                    $dependency = $element->getDependency('validate');
                    if($dependency && is_array($dependency)) {
                        if (count($dependency)) {
                            foreach($dependency as $row) {
                                if (!empty($row)) {
                                    $show = false;
                                    foreach((array)$row as $dep) {
                                        $el = $this->getElementByID(intval($dep['elementID']));
                                        if (is_object($el) && !empty($dep['value']) && count(array_intersect((array)$dep['value'], (array)$el->getValue()))) $show = true;
                                        if ($dep['inverse']) {
                                            if ($show) $show = false;
                                            else $show = true;
                                        }
                                    }
                                }
                            }
                        }

                    }

                    if (!$show || (trim($element->getDisplayValue()) == '' && $this->getDiscardEmpty())) continue;

                    $labels[] = '/{%'.$element->getHandle().'.label%}/';
                    if ($element->isLayout()) $values[] = preg_quote($element->getInput());
                    else $values[] = preg_quote($element->getLabel());

                    $labels[] = '/{%'.$element->getHandle().'.value%}/';
                    $values[] = preg_quote($element->getDisplayValue());

                    if ($element->isLayout()) {
                        if (!$this->getDiscardLayout()) $string .= preg_quote($element->getInput());
                    } else {
                        $string .= sprintf($_format, $element->getLabel(), preg_quote($element->getDisplayValue()));
                    }
                }

                // Add all elements labels
                $labels[] = '/{%all_elements%}/';
                $values[] = $string;
            }
        }

		
		// Remove empty labels / values
		$labels[] = '/{%(.*)%}(|:)/';
		$values[] = '';
		
		$labels[] = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";	
		$values[] = '';			

		// Now do your magic!
		$content = preg_replace($labels, $values, $content);		
		$content = $this->inversePregQuote($content);	

		// Remove empty tags
		//$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";		
		//$content = preg_replace($pattern, '', $content);
		 
		return $content;
	}
	
	private function setAbsoluteURLs($text) { 			
		$text = str_ireplace(array(' href=" http',' src=" http'), array(' href="http',' src="http'), $text);		 
		
		// Replace relative urls by absolute (prefix them with BASE_URL)
		$pattern = '/href=[\'|"](?!http|https|ftp|irc|feed|mailto|#)([\/]?)([^\'|"]*)[\'|"]/i';
		$replace = 'href="'.BASE_URL.'/$2"';
		$text = preg_replace($pattern, $replace, $text); 
		 
		// Replace relative img urls by absolute (prefix them with BASE_URL)
		$pattern = '/src=[\'|"](?!http|https|ftp|irc|feed|mailto|#)([\/]?)([^\'|"]*)[\'|"]/i';
		$replace = 'src="'.BASE_URL.'/$2"';
		$text = preg_replace($pattern, $replace, $text); 		
		
		return $text; 
	}	

	private function _replaceSingleElementID($matches) {
		return $this->new_elements[$matches[0]];
	}
	
	private function _replaceStringElementID($matches) {
		return $this->new_elements[$matches[0]];
	}
	
	private function _replaceMessageElementID($matches) {
		return $matches[1].'_'.$this->new_elements[$matches[2]].$matches[3];
	}
	
	private function inversePregQuote($str) {
		return strtr($str, array(
			'\\.'  => '.',
			'\\\\' => '\\',
			'\\+'  => '+',
			'\\*'  => '*',
			'\\?'  => '?',
			'\\['  => '[',
			'\\^'  => '^',
			'\\]'  => ']',
			'\\$'  => '$',
			'\\('  => '(',
			'\\)'  => ')',
			'\\{'  => '{',
			'\\}'  => '}',
			'\\='  => '=',
			'\\!'  => '!',
			'\\<'  => '<',
			'\\>'  => '>',
			'\\|'  => '|',
			'\\:'  => ':',
			'\\-'  => '-'
		));
	}
}