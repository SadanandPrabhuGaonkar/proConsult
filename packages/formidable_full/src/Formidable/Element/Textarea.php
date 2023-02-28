<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Textarea extends Element {
	
	public $element_text = 'Text Area';
	public $element_type = 'textarea';
	public $element_group = 'Basic Elements';	
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'default' => array(
			'type' => 'textarea'
		),
		'placeholder' => true,
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
						
		$this->properties['min_max'] = array(
			'words' => t('Words'),
			'chars' => t('Characters')
		);
	}	
	
	public function generateInput() {				
		$this->setAttribute('input', Core::make('helper/form')->textarea($this->getHandle(), $this->getValue(), $this->getAttributes()));		
	}

	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$value = parent::getDisplayValue($seperator, $urlify);		
		return Core::make('helper/text')->makenice(h($value));
	}
}