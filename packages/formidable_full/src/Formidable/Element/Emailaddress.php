<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

class Emailaddress extends Element {
	
	public $element_type = 'emailaddress';
	public $element_text = 'Email Address';
	public $element_group = 'Basic Elements';	
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'default' => true,
		'placeholder' => true,
		'required' => true,
		'confirmation' => true,						
		'tooltip' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true,
			'invalid_email' => true,
			'confirmation' => true,
		)
	);
	
	public $dependency = array(
		'has_value_change' => true,
		'has_placeholder_change' => true
	);
	
	public function generateInput() {	
		$form = Core::make('helper/form');	
		$this->setAttribute('input', $form->email($this->getHandle(), $this->getValue(), $this->getAttributes()));
		if ($this->getPropertyValue('confirmation')) {
			$attribs = $this->getAttributes();
			if (strpos($attribs['class'], 'emailaddress_confirm') === false) $attribs['class'] = $attribs['class'].' emailaddress_confirm';
			if ($this->getPropertyValue('placeholder')) $attribs['placeholder'] = t('Confirm %s', $this->getLabel());
			$this->setAttribute('confirm', $form->email($this->getHandle().'_confirm', $this->getValue(), $attribs));
		}
	}

	public function validateResult() {
		$val = new ValidatorResult();
		$val->setElement($this);
		$val->setData($this->post());
		if (strlen($this->post($this->getHandle())) != 0) $val->email();
		if ($this->getPropertyValue('required')) $val->required();
		if ($this->getPropertyValue('confirmation')) $val->confirmation();
		return $val->getList();	
	}	
}