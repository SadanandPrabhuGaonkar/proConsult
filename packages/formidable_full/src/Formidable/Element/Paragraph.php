<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Paragraph extends Element {
	
	public $element_text = 'Paragraph';
	public $element_type = 'paragraph';
	public $element_group = 'Layout Elements';	
	
	public $is_layout = true; // Is layout element, so change the view.... 
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'content' => true,
		'appearance' => array(
			'p' => 'Paragraph (default)',
			'div' => 'Div',
			'pre' => 'Preformatted text',
			'blockquote' => 'Blockquote',
			'span' => 'Span',
			'address' => 'Address',
			'code' => 'Code'
		),
		'handling' => false
	);
	
	public $dependency = array(
		'has_value_change' => false
	);

	public function generateInput() {
		$attribs = $this->getAttributes();
		$appearance = $this->getPropertyValue('appearance');				
		$this->setAttribute('input', '<'.$appearance.' class="'.$attribs['class'].'" name="'.$this->getHandle().'">' .t(Core::make('helper/text')->makenice($this->getPropertyValue('content'))).'</'
                                      .$appearance.'>');
	}
}