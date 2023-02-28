<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

class Time extends Element {
	
	public $element_text = 'Time Field';
	public $element_type = 'time';
	public $element_group = 'Special Elements';	
		
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'default' => array(
			'type' => 'input',
			'note' => '',
			'mask' => '99:99:99'
		),
		'required' => true,
		'appearance' => '',
		'format' => array(
			'formats' => '',
			'note' => ''
		),
		'tooltip' => true,
		'advanced' => array(
			'note' => ''
		),
		'handling' => true,
		'errors' => array(
			'empty' => true,
			'invalid_time' => true,
		)
	);
	
	public $dependency = array(
		'has_value_change' => true
	);
	
	private $hours24 = true;

	public function __construct() {				
		
		$this->properties['default']['note'] = array(
			t('Default time format (24 hours): HH:mm:ss')
		);
		
		$this->properties['appearance'] = array(
			'slider' => t('Create element with timeslider'),
			'select' => t('Selectboxes for hours, minutes, etc.'),
			'input' => t('Create masked input textfield (only 24-hours notation)')
		);
			
		$this->properties['format']['formats'] = array(
			'hh:mm TT' => 'hh:mm AM/PM',
			'hh:mm:ss TT' => 'hh:mm:ss AM/PM',
			'hh:mm tt' => 'hh:mm am/pm',
			'hh:mm:ss tt' => 'hh:mm:ss am/pm',
			'hh:mm' => 'hh:mm',									     
			'hh:mm:ss' => 'hh:mm:ss',
			'other' => t('Other format: ')
		);
		
		$this->properties['format']['note'] = array(
			'h - '.t('hour (no leading zero)'),
			'hh - '.t('hour (two digit)'),
			'm - '.t('minutes (no leading zero)'),
			'mm - '.t('minutes (two digit)'),
			's - '.t('seconds (no leading zero)'),
			'ss - '.t('seconds (two digit)'),
			'tt - '.t('am or pm for AM/PM'),
			'TT - '.t('AM or PM for AM/PM'),
			t('More information about timeformat: ').'<a href="http://trentrichardson.com/examples/timepicker/" target="_blank">'.t('click here').'</a>'
		);
		
		$this->properties['advanced']['note'] = array(
			t('Manage some advanced options of the timepicker'),
			t('Comma seperate options'),
			t('Example: hourMin: 8, hourMax: 16'),
			t('Possible options: ').'<a href="http://trentrichardson.com/examples/timepicker/" target="_blank">'.t('click here').'</a>'
		);
	}
	
	public function generateInput() {	

		$this->setHours();

		$form = Core::make('helper/form');
		
		$attribs = $this->getAttributes();
		if (strpos($attribs['class'], 'form-control') === false) $attribs['class'] = $attribs['class'].' form-control';

		$value = $this->getValue();

		// Generate selectboxes
		switch ($this->getPropertyValue('appearance')) {
			case 'select':				
				if (!empty($value)) {
					$hour = date($this->getHours()?"H":"h", strtotime($value));
					$minute = date("i", strtotime($value));
					$second = date("s", strtotime($value));
					$ampm = date("A", strtotime($value));
				}

				// Hours
				$hour_selector  = '<select name="'.$this->getHandle().'_hour" id="'.$this->getHandle().'_hour" class="'.(string)$attribs['class'].' hour">';				
				$hour_selector .= '<option value=""' . $selected . '></option>';				
									
				for ($i = ($this->getHours()?0:1); $i <= ($this->getHours()?23:12); $i++) {
					$selected = ($hour==sprintf('%02d', $i))?'selected':'';						
					$hour_selector .= '<option value="' . ($this->getHours()?sprintf('%02d', $i):$i) . '"' . $selected . '>' . sprintf('%02d', $i) . '</option>';
				}
				$hour_selector .= '</select>';

				// Minutes
				$minute_selector  = '<select name="'.$this->getHandle().'_minute" id="'.$this->getHandle().'_minute" class="'.(string)$attribs['class'].' minute">';				
				$minute_selector .= '<option value=""' . $selected . '></option>';				
				for ($i = 0; $i <= 59; $i++) {
					$selected = ($minute==sprintf('%02d', $i))?'selected':'';						
					$minute_selector .= '<option value="' . sprintf('%02d', $i) . '"' . $selected . '>' . sprintf('%02d', $i) . '</option>';
				}
				$minute_selector .= '</select>';

				// Seconds
				$second_selector  = '<select name="'.$this->getHandle().'_second" id="'.$this->getHandle().'_second" class="'.(string)$attribs['class'].' second">';				
				$second_selector .= '<option value=""' . $selected . '></option>';				
				for ($i = 0; $i <= 59; $i++) {
					$selected = ($second==sprintf('%02d', $i))?'selected':'';						
					$second_selector .= '<option value="' . sprintf('%02d', $i) . '"' . $selected . '>' . sprintf('%02d', $i) . '</option>';
				}
				$second_selector .= '</select>';

				// AM / PM
				$ampm_selector  = '<select name="'.$this->getHandle().'_ampm" id="'.$this->getHandle().'_ampm" class="'.(string)$attribs['class'].' ampm">';				
				$ampm_selector .= '<option value=""' . $selected . '></option>';				
				foreach (array('AM', 'PM') as $i) {
					$selected = ($ampm==$i)?'selected':'';						
					$ampm_selector .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				$ampm_selector .= '</select>';

				$format = preg_replace(array('/hh/', '/mm/', '/ss/', '/tt/', '/TT/'), array(' {qq} ', ' {ww} ', ' {ee} ', ' {rr} ', ' {rr} '), $this->getFormat());
				$input = preg_replace(array('/{qq}/', '/{ww}/', '/{ee}/', '/{rr}/'), array($hour_selector, $minute_selector, $second_selector, $ampm_selector), $format);
			break;

			case 'input':				
				$placeholder = preg_replace(array('/hh/', '/ii/', '/mm/', '/ss/', '/tt/i', '/h/', '/i/', '/m/', '/s/', '/t/i'), array('{qq}', '{ww}', '{ww}', '{ee}', '{rr}', '{qq}', '{ww}', '{ww}', '{ee}', '{rr}'), $this->getFormat());	
				$attribs['placeholder'] = preg_replace(array('/{qq}/', '/{ww}/', '/{ee}/', '/{rr}/'), array('hh', 'mm', 'ss', 'am/pm'), $placeholder);	
				
				// Check if hours are with or without zero
				if (!empty($value)) {
					$hour = substr($value, 0, strpos($value, ':'));
					if (strlen($value) < 2) $value = '0'.$value;
				}
				$input = $form->text($this->getHandle(), $value, $attribs);				
				$mask = preg_replace(array('/{qq}/', '/{ww}/', '/{ee}/', '/{rr}/'), array('99', '99', '99', 'aa'), $placeholder);
				$this->addJavascript("if ($.fn.mask) { $('#".$this->getHandle()."').mask('".$mask."') }");
			break;

			case 'slider':
				if (strpos($attribs['class'], 'timepicker') === false) $attribs['class'] = $attribs['class'].' timepicker';
				$input = $form->text($this->getHandle(), $value, $attribs);	
				if ($this->getPropertyValue('advanced') == 1) $options = preg_replace('/"/', '\'', Core::make('helper/text')->decodeEntities($this->getPropertyValue('advanced_value')));
				else {
					$options = array();
					if (!$this->getHours())	$options[] ='ampm: true';	
					if (strpos($this->getFormat(), 's') !== false) $options[] ='showSecond: true';			
					$options = @implode(',', $options);
				}				
				$this->addJavascript("if ($.fn.timepicker) { $('#".$this->getHandle()."').timepicker({ timeFormat: '".$this->getFormat(false)."', ".$options." }); }");
			break;
		}			
		$this->setAttribute('input', $input);	
	}
	
	public function validateResult() {
		$val = new ValidatorResult();
		$val->setElement($this);
		$val->setData($this->post());
		if ($this->getPropertyValue('required')) {
			switch ($this->getPropertyValue('appearance')) {
				case 'select':
					$time = array(
						$this->post($this->getHandle().'_hour'), 
						$this->post($this->getHandle().'_minute'), 
						$this->post($this->getHandle().'_second'),
						$this->post($this->getHandle().'_ampm')
					);	
					$value = @implode(':', array_filter(array($time[0], $time[1], $time[2])));
					$value = @implode(' ', array_filter(array($time, $time[3])));
					$error = false;
					if (empty($time) || strtolower(date($this->getFormatTranslation(), strtotime($value))) != strtolower($value)) {
						$val->add('ERROR_INVALID_TIME');
						break;
					}
				break;
				default:
					$val->required();
				break;
			}
		}
		return $val->getList();	
	}

	public function getValue() {
		$value = $this->value;
		$value = is_array($value)?$value['value']:(string)$value;
		if (!empty($value)) {
			if ($this->getPropertyValue('appearance') == 'select') {
				$time = array(
					$this->post($this->getHandle().'_hour'), 
					$this->post($this->getHandle().'_minute'), 
					$this->post($this->getHandle().'_second'),
					$this->post($this->getHandle().'_ampm')
				);	
				$value = @implode(':', array_filter(array($time[0], $time[1], $time[2])));
				$value = @implode(' ', array_filter(array($value, $date[3])));
			}
		}
		return $value;
	}
			
	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$value = $this->getValue();	

		// Weird code to split values and other sh*t...		
		if (is_array($value) && array_key_exists('value', $value)) $value = $value['value'];

		$value = date($this->getFormatTranslation(), strtotime($value));	
		return h($value);
	}
	
	private function getFormat($preg_replace = true) {
		$format = $this->getPropertyValue('format');
		if ($format == 'other') $format = $this->getPropertyValue('format_other');		
		if ($preg_replace) return preg_replace('/(\w\1+)/', '$1', $format);
		return $format;	
	}
	
	private function getFormatTranslation() {			
		$this->setHours();
		$replace = array('h', 'g', 'i', 'i', 's', 's', 'A', 'a');	
		if ($this->getHours()) $replace = array('H', 'G', 'i', 'i', 's', 's', 'A', 'a');			
		$pattern = array('/hh/', '/h/', '/mm/', '/m/', '/ss/', '/s/', '/TT/', '/tt/');
		return preg_replace($pattern, $replace, $this->getFormat());
	}

	private function setHours() {
		if (strpos(strtolower($this->getFormat(false)), 't') !== false) $this->hours24 = false;
	}

	private function getHours() {
		return $this->hours24;
	}
}