<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Hidden extends Element {
	
	public $element_text = 'Hidden Field';
	public $element_type = 'hidden';
	public $element_group = 'Basic Elements';	
	
	public $properties = array(
		'label' => true,
		'default' => true,
		'css' => false,
		'handling' => true
	);
	
	public $dependency = array(
		'has_value_change' => true
	);
	
	public function generateInput() {				
		$this->setAttribute('input', Core::make('helper/form')->hidden($this->getHandle(), $this->getValue(), $this->getAttributes()));
	}
}