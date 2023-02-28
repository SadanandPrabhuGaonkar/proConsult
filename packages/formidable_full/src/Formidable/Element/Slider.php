<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Slider extends Element {

	public $element_text = 'Slider';
	public $element_type = 'slider';
	public $element_group = 'Special Elements';

	public $properties = array(
		'label' => true,
		'label_hide' => true,
	    'default' => true,
	    'required' => true,
	    'min_max' => '',
	    'tooltip' => true,
	    'advanced' => array(
			'value' => 'reversed:true, tooltip:\'always\'',
			'note' => ''
		),
		'handling' => true,
		'errors' => array(
			'empty' => true
		)
	);

	public function __construct($elementID = 0) {

		$this->properties['min_max'] = array(
			'value' => t('Value')
		);

		$this->properties['appearance'] = array(
			'horizontal_single' => t('Horizontal'),
			'horizontal_range' => t('Horizontal (Range)'),
			'vertical_single' => t('Vertical'),
			'vertical_range' => t('Vertical (Range)'),
		);

		$this->properties['advanced']['note'] = array(
			t('Manage some advanced options for the slider'),
			t('Comma seperate options'),
			t('Example: reversed:true, tooltip:\'always\''),
			t('Possible options: ').'<a href="https://github.com/seiyria/bootstrap-slider" target="_blank">'.t('click here').'</a>',
			t('Examples: ').'<a href="http://seiyria.com/bootstrap-slider/" target="_blank">'.t('click here').'</a>'
		);
	}

	public function generateInput() {

		$attribs = $this->getAttributes();

		$class = $attribs['class'];
		if (strpos($class, 'counter_disabled') === false) $class = $class.' counter_disabled';

		$attribs['data-slider-min'] = 0;
		$attribs['ata-slider-max'] = 999;
		if ($this->getPropertyValue('min_max')) {
			$attribs['data-slider-min'] = $this->getPropertyValue('min_value');
			$attribs['data-slider-max'] = $this->getPropertyValue('max_value');
		}

		$attribs['data-slider-step'] = 1;

		$val = $this->getValue();
		if (strpos($val, ',') !== false) $val = '['.$val.']';
		$attribs['data-slider-value'] = $val;

		list($type, $range) = @explode('_', $this->getPropertyValue('appearance'));
		if (empty($type)) $type = 'horizontal';
		$attribs['data-slider-orientation'] = $type;
		if (empty($range) || $range == 'single') $range = false;
		else $range = true;

		$attributes = '';
		if (count($attribs) && is_array($attribs)) {
			foreach ($attribs as $k => $v) {
				$attributes .= $k.'="'.$v.'" ';
			}
		}

		$html  = '<div class="slider '.$class.'">';
		$html .= '<input type="text" name="'.$this->getHandle().'" id="'.$this->getHandle().'" '.$attributes.'>';
		$html .= '</div>';

		$this->setAttribute('input', $html);

		$options = '';
		if ($this->getPropertyValue('advanced') == 1) $options = preg_replace('/"/', '\'', Core::make('helper/text')->decodeEntities($this->getPropertyValue('advanced_value')));
		$this->addJavascript("if ($.fn.bootstrapSlider) { $('#".$this->getHandle()."').bootstrapSlider({ range: ".($range?'true':'false').", formatter: function(value) { return '".t('Value').":'+value; }, ".$options." }); }");

	}
}
