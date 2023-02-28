<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Property as ValidatorProperty;
use Core;

class Select extends Element {
	
	public $element_text = 'Selectbox';
	public $element_type = 'select';
	public $element_group = 'Basic Elements';	
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'required' => true,
		'options' => true,
		'placeholder' => array(
			'note' => array(
				'First choice in the selectbox. Leave empty for an empty option.'
			)
		),
		'option_other' => '',
		'appearance' => '',
		'min_max' => '',
		'tooltip' => true,
		'multiple' => true,
		'handling' => true,
		'errors' => array(
			'empty' => true
		)
	);
	
	public $dependency = array(
		'has_value_change' => true,
		'has_placeholder_change' => false
	);
	
	public function __construct() {		

		$this->properties['min_max'] = array(
			'options' => t('Selected options')
		);
			
		$this->properties['option_other'] = array(
			'text' => t('Single text'),
			'textarea' => t('Textarea')
		);

		$this->properties['appearance'] = array(
			'select' => t('Selectbox'),			
			'tags' => t('Tags (selectize)'),
		);
	}
	
	public function generateInput() {

		$form = Core::make('helper/form');

		$value = $this->getValue();

		$attribs = $this->getAttributes();
		if ($this->getPropertyValue('multiple')) $attribs['multiple'] = 'multiple';
		if (strpos($attribs['class'], 'form-control') === false) $attribs['class'] = $attribs['class'].' form-control';

		$aks = @implode(' ', array_map( function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $attribs, array_keys($attribs)));						
		
		$select[] = '<select name="'.$this->getHandle().'[]" id="'.$this->getHandle().'" '.$aks.'>';
		if (is_array($attribs) && array_key_exists('placeholder', $attribs)) $select[] = '<option value="">'.$attribs['placeholder'].'</option>';

		$options = $this->getPropertyValue('options');	
		if (!empty($options) && count($options)) {
			foreach ($options as $i => $o) {						
				if (empty($options[$i]['value'])) $options[$i]['value'] = $options[$i]['name'];						
				$selected = (@in_array($options[$i]['value'], (array)$value) || (empty($value) && $options[$i]['selected'] === true))?'selected="selected"':'';								
				$select[]= '<option value="'.$options[$i]['value'].'" '.$selected.'>'.$options[$i]['name'].'</option>';
			}
		}
		if ($this->getPropertyValue('option_other')) {
			$selected = (count($value) && @in_array('option_other', $value))?'selected="selected"':'';			
			$select[] .= '<option value="option_other" '.$selected.'>'.$this->getPropertyValue('option_other_value').'</option>';			
			$this->setAttribute('other', $form->{$this->getPropertyValue('option_other_type')}($this->getHandle().'_other', $this->getDisplayOtherValue(), $this->getAttributes()));
		}		
		$select[] = '</select>';		
		$this->setAttribute('input', @implode(PHP_EOL, $select));

		if ($this->getPropertyValue('appearance') == 'tags') {

			$max = 20;
			if ($this->getPropertyValue('min_max')) $max = $this->getPropertyValue('max_value');	

			$script .= '
				if ($.fn.selectize) { 
					$(\'#'.$this->getHandle().'\').selectize({
		                plugins: [\'remove_button\'],
						valueField: \'id\',
						labelField: \'text\',
						openOnFocus: false,
						create: false,
						createFilter: function(input) {
							return input.length >= 1;
						},	
						onChange: function(value) {
							var max = '.$max.';
							var current = 0;
							if (value !== null) current = value.length;							
							$(\'#'.$this->getHandle().'\').closest(\'.element\').find(\'.counter span\').text(max - current);
						},		
						delimiter: \',\',
						maxItems: '.$max.','.
						($this->getPropertyValue('placeholder')?'placeholder: \''.$this->getPropertyValue('placeholder_value').'\',':'').'
					});
				}';
			$this->addJavascript($script);
		}
	}

	public function getDisplayValue($seperator = ' ', $urlify = true) {
		return parent::getDisplayValue(', ', true);
	}

	// Use your own validation beacause placeholder is normally required
	// The selectbox don't need this to be required.
	public function validateProperty() {
		$val = new ValidatorProperty();
		$val->setData($this->post());
		if ($this->getProperty('label')) $val->label();
		if ($this->getProperty('min_max')) $val->minMax();
		if ($this->getProperty('tooltip')) $val->tooltip();		
		if ($this->getProperty('options')) $val->options();
		if ($this->getProperty('option_other')) $val->other();
		if ($this->getProperty('appearance')) $val->appearance();		
		if ($this->getProperty('css')) $val->css();
		if ($this->getProperty('submission_update')) $val->submissionUpdate();
		return $val->getList();	
	}	
}