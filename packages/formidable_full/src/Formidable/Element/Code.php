<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Code extends Element {
	
	public $element_text = 'Code';
	public $element_type = 'code';
	public $element_group = 'Layout Elements';	
	
	public $is_layout = true; // Is layout element, so change the view.... 
	
	public $properties = array(
		'label' => true,		
		'code' => true,
	);
				
	public function generateInput() {				
		$this->setAttribute('input', Core::make('helper/text')->decodeEntities($this->getPropertyValue('code_value')));
	}
}