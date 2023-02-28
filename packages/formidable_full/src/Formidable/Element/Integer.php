<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

class Integer extends Element {
	
	public $element_text = 'Integer';
	public $element_type = 'integer';
	public $element_group = 'Basic Elements';	
		
	public $properties = array(
		'label' => true,
		'label_hide' => true,
	    'default' => true,
		'placeholder' => true,						
		'required' => true,
		'min_max' => '',
		'tooltip' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true,
			'invalid_numeric' => true,
		)
	);
	
	public $dependency = array(
		'has_value_change' => true,
		'has_placeholder_change' => true
	);
	
	public function __construct($elementID = 0) {		
		$this->properties['min_max'] = array(
			'value' => t('Value'), 
			'chars' => t('Characters')
		);
	}
	
	public function generateInput() {	
		$attribs = $this->getAttributes();
		if ($this->getPropertyValue('min_max')) {			
			if (strpos($attribs['class'], 'counter_disabled') === false) $attribs['class'] = $attribs['class'].' counter_disabled';
			if (strpos($attribs['class'], 'form-control') === false) $attribs['class'] = $attribs['class'].' form-control';
			$attribs['value'] = $this->getValue();
			$attribs['step'] = 1;
			if ($this->getPropertyValue('min_max_type') == 'value') {
				$attribs['min'] = strlen($this->getPropertyValue('min_value'));
				$attribs['max'] = strlen($this->getPropertyValue('min_value')) - $attribs['min'];
			}
			elseif ($this->getPropertyValue('min_max_type') == 'chars') {
				$attribs['min'] = intval($this->getPropertyValue('min_value'));
				$attribs['max'] = intval($this->getPropertyValue('min_value'));
				if (!empty($attribs['max'])) $attribs['max'] = str_repeat('9', $attribs['max']);
			}
		}
		// HTML5
		 $this->setAttribute('input', Core::make('helper/form')->number($this->getHandle(), $this->getValue(), $attribs));

		// Non-HTML5
		//$this->setAttribute('input', Core::make('helper/form')->text($this->getHandle(), $this->getValue(), $attribs));
		//$format  = ($attribs['min']!=0)?str_repeat('9', $attribs['min']):'';
		//$format .= ($attribs['max']!=0)?'?'.str_repeat('9', $attribs['max']):'';
		//if (!empty($this->getPropertyValue('mask'))) $this->addJavascript("if ($.fn.mask) { $('#".$this->getHandle()."').mask('".$format."') }");
	}
	
	public function validateResult() {
		$val = new ValidatorResult();
		$val->setElement($this);
		$val->setData($this->post());
		if (strlen($this->post($this->getHandle())) != 0) $val->integer();
		if ($this->getPropertyValue('required')) $val->required();
		if ($this->getPropertyValue('min_max')) $val->minMax();
		return $val->getList();	
	}
}