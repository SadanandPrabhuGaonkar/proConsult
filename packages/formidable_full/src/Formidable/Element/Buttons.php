<?php         
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Buttons extends Element {
	
	public $element_text = 'Buttons';
	public $element_type = 'buttons';
	public $element_group = 'Layout Elements';	
	
	public $is_layout = true; // Is layout element, so change the view.... 
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'handling' => false
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
	
	public function generateInput() {				
		$attribs = $this->getAttributes();
		if (!$this->getProperty('css')) $attribs['class'] = 'btn btn-success'; 
		$html  = Core::make('helper/form')->submit('submit', Core::make('helper/text')->specialchars($this->getLabel()), array(), 'submit '.$attribs['class']);
        $html .= '<div class="please_wait_loader"><img src="'.BASE_URL.'/packages/formidable_full/images/loader.gif"></div>';
        $this->setAttribute('input', $html);

	}
}