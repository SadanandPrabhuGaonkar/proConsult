<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Range extends Element {

	public $element_text = 'Range';
	public $element_type = 'range';
	public $element_group = 'Basic Elements';

	public $properties = array(
		'label' => true,
		'label_hide' => true,
	    'default' => true,
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
	);

	public function __construct($elementID = 0) {

		$this->properties['min_max'] = array(
			'value' => t('Value')
		);
	}

	public function generateInput() {

		$attribs = $this->getAttributes();

		$class = $attribs['class'];
		if (strpos($class, 'counter_disabled') === false) $class = $class.' counter_disabled';

		$attribs['min'] = 0;
		$attribs['max'] = 999;
		if ($this->getPropertyValue('min_max')) {
			$attribs['min'] = $this->getPropertyValue('min_value');
			$attribs['max'] = $this->getPropertyValue('max_value');
		}

		$attributes = '';
		if (count($attribs) && is_array($attribs)) {
			foreach ($attribs as $k => $v) {
				$attributes .= $k.'="'.$v.'" ';
			}
		}

		$html  = '<div class="range '.$class.'">';
		$html .= '<div class="row">';
 		$html .= '<div class="col-xs-10">';
		$html .= '<input type="range" name="'.$this->getHandle().'" id="'.$this->getHandle().'" value="'.$this->getValue().'" '.$attributes.'>';
		$html .= '</div>';
		$html .= '<div class="col-xs-2">';
		$html .= Core::make('helper/form')->text($this->getHandle().'_value', $this->getValue(), array('readonly' => true));
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		$this->setAttribute('input', $html);
		$this->addJavascript("$('#".$this->getHandle()."').on('change', function() { $('#".$this->getHandle()."_value').val($(this).val()); }).trigger('change');");

	}
}
