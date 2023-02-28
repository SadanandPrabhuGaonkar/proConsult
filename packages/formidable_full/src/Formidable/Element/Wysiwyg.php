<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Wysiwyg extends Element {
	
	public $element_text = 'Content (WYSIWYG)';
	public $element_type = 'wysiwyg';
	public $element_group = 'Layout Elements';	
	
	public $is_layout = true; // Is layout element, so change the view.... 
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'html' => true,
		'css' => true,
		'handling' => false
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
	
	public function generateInput() {				
		$attribs = $this->getAttributes();
		$appearance = $this->getPropertyValue('appearance');				
		$this->setAttribute('input', '<div class="'.$attribs['class'].'" name="'.$this->getHandle().'">'.Core::make('helper/text')->decodeEntities($this->getPropertyValue('html_value')).'</div>');
	}
}