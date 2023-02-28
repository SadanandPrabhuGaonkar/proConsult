<?php         
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;

class Hr extends Element {
	
	public $element_text = 'Horizontal Rule';
	public $element_type = 'hr';
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
		$this->setAttribute('input', '<hr />');
	}
}