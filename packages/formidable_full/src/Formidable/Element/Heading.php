<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;

class Heading extends Element {
	
	public $element_text = 'Heading';
	public $element_type = 'heading';
	public $element_group = 'Layout Elements';	
	
	public $is_layout = true; // Is layout element, so change the view.... 
	
	public $properties = array(
		'label' => true,
		'appearance' => array(
			'h1' => 'Heading 1 (h1)',
			'h2' => 'Heading 2 (h2)',
		    'h3' => 'Heading 3 (h3)',
		    'h4' => 'Heading 4 (h4)',
		    'h5' => 'Heading 5 (h5)',
		    'h6' => 'Heading 6 (h6)'
		),
		'handling' => false
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
		
	public function generateInput() {
		$attribs = $this->getAttributes();
		$appearance = $this->getPropertyValue('appearance');				
		$this->setAttribute('input', '<'.$appearance.' class="'.$attribs['class'].'" name="'.$this->getHandle().'">' .t($this->getLabel()).'</'.$appearance.'>');
	}
}