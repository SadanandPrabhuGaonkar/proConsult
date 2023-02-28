<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Phone extends Element {
	
	public $element_text = 'Phone number';
	public $element_type = 'phone';
	public $element_group = 'Special Elements';	
		
	public $properties = array(
		'label' => true,
		'label_hide' => true,
	    'default' => true,
		'placeholder' => true,
	    'mask' => array(
			'placeholder' => '(999) 999-9999',
			'formats' => array(
				'(999) 999-9999',
				'+99 (999) 999-9999',
				'+99(9)999-999999',
				'(+99) 9999 9999',
				'9999 9999',
				'(9999) 999999',
				'(9999) 99 99 99',
				'9999 99 99 99',
				'999-999-9999'
			),
			'note' => ''
		),
	    'required' => true,
	    'min_max' => '',
	    'tooltip' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true
		)
	);
	
	public $dependency = array(
		'has_value_change' => true,
		'has_placeholder_change' => true
	);
	
	public function __construct() {						
		$this->properties['mask']['note'] = array(
			t('Your mask not available? Add a text field and create your own mask'),
			t('Send your mask to %s', '<a href="mailto:wim@dewebmakers.nl">wim@dewebmakers.nl</a>'),
			t('More information about masking: <a href="%s" target="_blank">click here</a>', 'http://digitalbush.com/projects/masked-input-plugin/')
		);
	}
	
	public function generateInput() {	
		$this->setAttribute('input', Core::make('helper/form')->telephone($this->getHandle(), $this->getValue(), $this->getAttributes()));
		if (!empty($this->getPropertyValue('mask'))) $this->addJavascript("if ($.fn.mask) { $('#".$this->getHandle()."').mask('".$this->getPropertyValue('mask_format')."') }");
	}
}
