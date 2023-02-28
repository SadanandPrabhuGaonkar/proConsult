<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;

class Line extends Element {
	
	public $element_text = 'Line / Break';
	public $element_type = 'line';
	public $element_group = 'Layout Elements';	
	
	public $is_layout = true; // Is layout element, so change the view.... 
	
	public $properties = array(
		'label' => true,
		'handling' => false
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
		
	public function generateInput() {				
		$this->setAttribute('input', '<br />');
	}
}