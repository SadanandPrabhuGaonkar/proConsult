<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use Core;

class Radio extends Element {

	public $element_text = 'Radiobuttons';
	public $element_type = 'radio';
	public $element_group = 'Basic Elements';

	protected $format = '<div class="radio {SIZE}"><label for="{ID}">{ELEMENT} {TITLE}</label></div>';

	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'required' => true,
		'options' => true,
		'option_other' => '',
		'appearance' => '',
		'tooltip' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true
		)
	);

	public $dependency = array(
		'has_value_change' => true
	);

	public function __construct() {

		$this->properties['option_other'] = array(
			'text' => t('Single text'),
			'textarea' => t('Textarea')
		);

		$this->properties['appearance'] = array(
			'w100' => t('One column'),
			'w50' => t('Two columns'),
			'w33' => t('Three columns'),
			'w25' => t('Four columns'),
			'w20' => t('Five columns'),
			'auto' => t('Automatically (let the width decide)')
		);
	}

	public function generateInput() {
		$form = Core::make('helper/form');
		$th = Core::make('helper/text');

		$attribs = $this->getAttributes();
		if (strpos($attribs['class'], 'counter_disabled') === false) $attribs['class'] = $attribs['class'].' counter_disabled';

		$aks = @implode(' ', array_map( function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $attribs, array_keys($attribs)));

		$value = $this->getValue();

		$options = $this->getPropertyValue('options');
		if (!empty($options) && count($options) && is_array($options)) {
			foreach ($options as $i => $o) {
				$id = $th->sanitizeFileSystem($this->getHandle()).($i+1);
				if (empty($options[$i]['value'])) $options[$i]['value'] = $options[$i]['name'];
				$checked = (@in_array($options[$i]['value'], (array)$value) || (empty($value) && $options[$i]['selected'] === true))?'checked="checked"':'';
				$radio = '<input type="radio" name="'.$this->getHandle().'[]" id="'.$id.'" value="'.$options[$i]['value'].'" '.$checked.' '.$aks.'>';
				$input[] = str_replace(array('{ID}','{TITLE}','{ELEMENT}','{SIZE}'), array($id, $options[$i]['name'], $radio, $this->getPropertyValue('appearance')), $this->format);
			}
		}
		if ($this->getPropertyValue('option_other') != 0) {
			$checked = (count($value) && @in_array('option_other', $value))?'checked="checked"':'';
			$id = $th->sanitizeFileSystem($this->getHandle()).'_other';
			$radio = '<input type="radio" name="'.$this->getHandle().'[]" id="'.$id.'" value="option_other" '.$checked.' '.$aks.'>';
			$input[] = str_replace(array('{ID}','{TITLE}','{ELEMENT}','{SIZE}'), array($id, $this->getPropertyValue('option_other_value'), $radio, $this->getPropertyValue('appearance')), $this->format);
			$this->setAttribute('other', $form->{$this->getPropertyValue('option_other_type')}($this->getHandle().'_other', $this->getDisplayOtherValue(), $this->getAttributes()));
		}
		$this->setAttribute('input', @implode(PHP_EOL, $input));
	}
}
