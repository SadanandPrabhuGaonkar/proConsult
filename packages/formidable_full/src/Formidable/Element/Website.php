<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

class Website extends Element {
	
	public $element_text = 'Website (URL)';
	public $element_type = 'website';
	public $element_group = 'Special Elements';	
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'default' => true,
		'placeholder' => true,
		'required' => true,
		'confirmation' => true,						
		'tooltip' => true,
		'handling' => true
	);
	
	public $dependency = array(
		'has_value_change' => true,
		'has_placeholder_change' => true
	);
	
	public function generateInput() {				
		$input  = Core::make('helper/form')->url($this->getHandle(), $this->getValue(), $this->getAttributes());
		$input .= '<div class="counter">'.t('Including http(s):// or (s)ftp://').'</div>';
		$this->setAttribute('input', $input);
		if ($this->getPropertyValue('confirmation')) {
			$attribs = $this->getAttributes();
			if (strpos($attribs['class'], 'website_confirm') === false) $attribs['class'] = $attribs['class'].' website_confirm';
			if ($this->getPropertyValue('placeholder')) $attribs['placeholder'] = t('Confirm %s', $this->getLabel());
			$this->setAttribute('confirm', Core::make('helper/form')->url($this->getHandle().'_confirm', $this->getValue(), $attribs));
		}
	}

	public function validateResult() {
		$val = new ValidatorResult();
		$val->setElement($this);
		$val->setData($this->post());
		if (strlen($this->post($this->getHandle())) != 0) $val->url();
		if ($this->getPropertyValue('required')) $val->required();
		if ($this->getPropertyValue('confirmation')) $val->confirmation();
		return $val->getList();	
	}		
}