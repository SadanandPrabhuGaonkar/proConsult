<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Password extends Element {
	
	public $element_text = 'Password';
	public $element_type = 'password';
	public $element_group = 'Basic Elements';	
		
	public $properties = array(
		'label' => true,
		'label_hide' => true,	    
		'placeholder' => true,
	    'mask' => '',
	    'required' => true,
	    'min_max' => '',
	    'confirmation' => true,	
	    'tooltip' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true,
			'confirmation' => true
		)
	);
	
	public $dependency = array(
		'has_value_change' => true,
		'has_placeholder_change' => true
	);
	
	public function __construct($elementID = 0) {
		
		$this->properties['mask'] = array(
			'note' => array(
				'a - '.t('Represents an alpha character').'(A-Z,a-z)',
				'9 - '.t('Represents a numeric character').'(0-9)',
				'* - '.t('Represents an alphanumeric character').'(A-Z,a-z,0-9)',
				'? - '.t('Represents optional data, everything behind the questionmark is optional'),
				t('Examples:'),
				t('Phone').': (999) 999-9999',
				t('Product Code').': a*-999-a999',
				t('More information about masking: <a href="%s" target="_blank">click here</a>', 'http://digitalbush.com/projects/masked-input-plugin/')
			)
		);
		
		$this->properties['min_max'] = array(
			'chars' => t('Characters')
		);
	}
	
	public function generateInput() {				
		$this->setAttribute('input', Core::make('helper/form')->password($this->getHandle(), $this->getValue(), $this->getAttributes()));	
		if ($this->getPropertyValue('confirmation')) {
			$attribs = $this->getAttributes();
			if (strpos($attribs['class'], 'password_confirm') === false) $attribs['class'] = $attribs['class'].' password_confirm';
			if ($this->getPropertyValue('placeholder')) $attribs['placeholder'] = t('Confirm %s', $this->getLabel());
			$this->setAttribute('confirm', Core::make('helper/form')->password($this->getHandle().'_confirm', $this->getValue(), $attribs));
		}

		if (!empty($this->getPropertyValue('mask'))) $this->addJavascript("if ($.fn.mask) { $('#".$this->getHandle().", #".$this->getHandle()."_confirm').mask('".$this->getPropertyValue('mask_format')."') }");
	}
}