<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Rating extends Element {
	
	public $element_text = 'Rating';
	public $element_type = 'rating';
	public $element_group = 'Special Elements';	
		
	public $properties = array(
		'label' => true,
		'label_hide' => true,
	    'default' => true,
	    'required' => true,
	    'tooltip' => true,
	    'advanced' => array(
			'value' => 'min: 0, max: 5, step: 1, showClear:true, showCaption:false, hoverOnClear: false, theme: \'formidable-fa\'',
			'note' => ''
		),
		'handling' => true,
		'errors' => array(
			'empty' => true
		)
	);
	
	public $dependency = array(
		'has_value_change' => true,
	);
	
	public function __construct($elementID = 0) {
		
		$this->properties['advanced']['note'] = array(
			t('Manage some advanced options for the rating'),
			t('Comma seperate options'),
			t('Example: "min: 0, max: 5, step: 1, showClear:true, showCaption:false, hoverOnClear: false, theme: \'formidable-fa\'"'),
			t('Possible options: ').'<a href="http://plugins.krajee.com/star-rating#option-size" target="_blank">'.t('click here').'</a>'
		);	
	}
	
	public function generateInput() {				
		
		$attribs = $this->getAttributes();
		
		$class = $attribs['class'];
		if (strpos($class, 'counter_disabled') === false) $class = $class.' counter_disabled';	
		
		$html  = '<div class="rating '.$class.'">';
		$html .= '<input id="'.$this->getHandle().'" name="'.$this->getHandle().'" value="'.$this->getValue().'" class="kv-ltr-theme-fa-star rating-loading">';
		$html .= '</div>';
		$this->setAttribute('input', $html);

		$options = "min: 0, max: 5, step: 1, showClear: true, showCaption: false, size: 'xs', hoverOnClear: false, theme: 'formidable-fa'";
		if ($this->getPropertyValue('advanced') == 1) $options = preg_replace('/"/', '\'', Core::make('helper/text')->decodeEntities($this->getPropertyValue('advanced_value')));
		$this->addJavascript("if ($.fn.rating) { $('#".$this->getHandle()."').rating({ ".$options." }); }");
		
	}
}