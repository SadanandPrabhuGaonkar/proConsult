<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

if (!defined('FORMIDABLE_ELEMENT_YEAR_SELECT')) define('FORMIDABLE_ELEMENT_YEAR_SELECT', date("Y")+10);

class Date extends Element {

	public $element_text = 'Date Field';
	public $element_type = 'date';
	public $element_group = 'Special Elements';

	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'default' => array(
			'type' => 'input',
			'note' => '',
			'mask' => '99/99/9999'
		),
		'required' => true,
		'placeholder' => true,
		'appearance' => '',
		'format' => array(
			'formats' => '',
			'note' => ''
		),
		'tooltip' => true,
		'advanced' => array(
			'value' => 'changeYear:true, yearRange:"1900:c+20", changeMonth:true',
			'note' => ''
		),
		'handling' => true,
		'errors' => array(
			'empty' => true,
			'invalid_date' => true,
		)
	);

	private $_formats = array(
		'j' => 'd',
		'd' => 'dd',
		'z' => 'o',
		'D' => 'D',
		'l' => 'DD',
		'n' => 'm',
		'm' => 'mm',
		'F' => 'MM',
		'M' => 'M',
		'y' => 'y',
		'Y' => 'yy'
	);

	public $dependency = array(
		'has_value_change' => false
	);

	public function __construct($elementID = 0) {

		$this->properties['default']['note'] = array(
			t('Default date format: mm/dd/yyyy')
		);

		$this->properties['appearance'] = array(
			'picker' => t('Create element with datepicker'),
			'select' => t('Selectboxes for years, months, days'),
			'input' => t('Create masked input textfield (only dd, mm and yyyy input)')
		);

		$this->properties['format']['formats'] = array(
			'mm/dd/yyyy' => 'mm/dd/yyyy',
			'mm/dd/yy' => 'mm/dd/yy',
			'dd/mm/yyyy' => 'dd/mm/yyyy',
			'dd/mm/yy' => 'dd/mm/yy',
			'dd-mm-yyyy' => 'dd-mm-yyyy',
			'dd-mm-yy' => 'dd-mm-yy',
			'yyyy/mm/dd' => 'yyyy/mm/dd',
			'yy/mm/dd' => 'yy/mm/dd',
			'DD MM d yy' => 'DD MM d yy',
			'other' => t('Other format: ')
		);

		$this->properties['format']['note'] = array(
			'd - '.t('day of month (no leading zero)'),
			'dd - '.t('day of month (two digit)'),
			'o - '.t('day of the year (no leading zeros)'),
			'oo - '.t('day of the year (three digit)'),
			'D - '.t('day name short'),
			'DD - '.t('day name long'),
			'm - '.t('month of year (no leading zero)'),
			'mm - '.t('month of year (two digit)'),
			'M - '.t('month name short'),
			'MM - '.t('month name long'),
			'y - '.t('year (two digit)'),
			'yy - '.t('year (four digit)'),
			t('More information about dateformat: ').'<a href="http://docs.jquery.com/UI/Datepicker/formatDate" target="_blank">'.t('click here').'</a>'
		);

		$this->properties['advanced']['note'] = array(
			t('Manage some advanced options of the datepicker'),
			t('Comma seperate options'),
			t('Example: changeYear:true, yearRange:"c-10:c+10"'),
			t('Possible options: ').'<a href="http://jqueryui.com/demos/datepicker/" target="_blank">'.t('click here').'</a>'
		);
	}

	public function generateInput() {
		$form = Core::make('helper/form');

		$attribs = $this->getAttributes();
		if (strpos($attribs['class'], 'form-control') === false) $attribs['class'] = $attribs['class'].' form-control';

		$value = $this->getValue();

		$input = '';

		// Generate selectboxes
		switch ($this->getPropertyValue('appearance')) {
			case 'select':
				if (!empty($value)) {
					$day = date("d", strtotime($value));
					$month = date("m", strtotime($value));
					$year = date("Y", strtotime($value));
				}

				// Days
				$day_selector  = '<select name="'.$this->getHandle().'_day" id="'.$this->getHandle().'_day" class="'.(string)$attribs['class'].' day">';
				$day_selector .= '<option value=""' . $selected . '>'.t('day').'</option>';
				for ($i = 1; $i <= 31; $i++) {
					$selected = ($day==sprintf('%02d', $i))?'selected':'';
					$day_selector .= '<option value="' . sprintf('%02d', $i) . '"' . $selected . '>' . sprintf('%02d', $i) . '</option>';
				}
				$day_selector .= '</select>';

				// Months
				$month_selector  = '<select name="'.$this->getHandle().'_month" id="'.$this->getHandle().'_month" class="'.(string)$attribs['class'].' month">';
				$month_selector .= '<option value=""' . $selected . '>'.t('month').'</option>';
				for ($i = 1; $i <= 12; $i++) {
					$selected = ($month==sprintf('%02d', $i))?'selected':'';
					$month_selector .= '<option value="' . sprintf('%02d', $i) . '"' . $selected . '>' . sprintf('%02d', $i) . '</option>';
				}
				$month_selector .= '</select>';

				// Years
				$year_selector  = '<select name="'.$this->getHandle().'_year" id="'.$this->getHandle().'_year" class="'.(string)$attribs['class'].' year">';
				$year_selector .= '<option value=""' . $selected . '>'.t('year').'</option>';
				for ($i = FORMIDABLE_ELEMENT_YEAR_SELECT; $i >= 1970; $i--) {
					$selected = ($year==sprintf('%02d', $i))?'selected':'';
					$year_selector .= '<option value="' . sprintf('%02d', $i) . '"' . $selected . '>' . sprintf('%02d', $i) . '</option>';
				}
				$year_selector .= '</select>';

				$format = preg_replace(array('/d/', '/m/', '/y/'), array(' {qq} ', ' {ww} ', ' {ee} '), $this->getFormat());
				$input = preg_replace(array('/{qq}/', '/{ww}/', '/{ee}/'), array($day_selector, $month_selector, $year_selector), $format);
			break;

			case 'input':
				$attribs['placeholder'] = preg_replace(array('/d/', '/m/', '/y/'), array('dd', 'mm', 'yyyy'), $this->getFormat());
				$input = $form->text($this->getHandle(), !empty($value)?date($this->translateFormatToPHP(), strtotime($value)):'', $attribs);
				$mask = preg_replace(array('/d/', '/m/', '/y/'), array('99', '99', '9999'), $this->getFormat());
				$this->addJavascript("if ($.fn.mask) { $('#".$this->getHandle()."').mask('".$mask."') }");
			break;

			case 'picker':
				if (strpos($attribs['class'], 'datepicker') === false) $attribs['class'] = $attribs['class'].' datepicker';
				$input  = $form->text($this->getHandle().'_date', !empty($value)?$this->translateValue():'', $attribs);
				$input .= $form->hidden($this->getHandle(), !empty($value)?date('Y-m-d', strtotime($value)):'', $attribs);

				$options = 'changeYear: true, showAnim: \'fadeIn\'';
				if ($this->getPropertyValue('advanced') == 1) $options = preg_replace('/"/', '\'', Core::make('helper/text')->decodeEntities($this->getPropertyValue('advanced_value')));
				$format = preg_replace('/yyyy/', 'yy', $this->getFormat(false));
				$this->addJavascript("$('#".$this->getHandle()."_date').datepicker({ altField:'#".$this->getHandle()."', altFormat:'yy-mm-dd', dateFormat:'".$format."',".$options."});");
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
					$date = array(
						$this->post($this->getHandle().'_month'),
						$this->post($this->getHandle().'_day'),
						$this->post($this->getHandle().'_year')
					);
					$error = false;
					if (empty($date[0]) || empty($date[1]) || empty($date[2])) {
						$val->addError('ERROR_INVALID_DATE');
						break;
					}
					if (!checkdate(intval($date[0]), intval($date[1]), intval($date[2]))) {
						$val->addError('ERROR_INVALID_DATE');
						break;
					}
				break;
				case 'input':
					if (!$this->parseDateToFormat($this->translateFormatToPHP(), $this->post($this->getHandle()))) $val->addError('ERROR_INVALID_DATE');
				break;
				case 'picker':
					if (!$this->parseDateToFormat('Y-m-d', $this->post($this->getHandle()))) $val->addError('ERROR_INVALID_DATE');
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
				$date = $this->parseDateToFormat($this->translateFormatToPHP(), $value);
				if (count($date)) $value = @implode('-', array($date['year'], $date['month'], $date['day']));
			}
		}
		return $value;
	}

	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$value = $this->getValue();

		// Weird code to split values and other sh*t...
		if (is_array($value) && array_key_exists('value', $value)) $value = $value['value'];

		switch ($this->getPropertyValue('appearance')) {
			case 'select':
				$selected = array(date("d", strtotime($value)), date("m", strtotime($value)), date("Y", strtotime($value)));
				$value = preg_replace(array('/d/', '/m/', '/y/'), $selected, $this->getFormat());
			break;
			case 'input':
				$date = $this->parseDateToFormat('Y-m-d', $value);
				if (is_array($_date)) $value = date($this->translateFormatToPHP(), strtotime($date['year'].'-'.$date['month'].'-'.$date['day']));
			break;
			case 'picker':
				$value = !empty($value)?$this->translateValue($value):'';
			break;
		}
		if (is_array($value)) $value = @implode($seperator, $value);
		if (!$urlify) return h($value);
		return h($value);
	}

	private function getFormat($preg_replace = true) {
		$format = $this->getPropertyValue('format');
		if ($format == 'other') $format = $this->getPropertyValue('format_other');
		if ($preg_replace) return preg_replace('/(\w)\1+/', '$1', strtolower($format));
		return $format;
	}

	private function translateFormatToPHP() {
		$format = preg_replace('/yyyy/', 'yy', $this->getFormat(false));
		if (count($this->_formats)) {
			foreach ($this->_formats as $cf) {
				$pattern[] = '/\b'.$cf.'\b/';
			}
		}
		return preg_replace($pattern, array_keys((array)$this->_formats), $format);
	}

	private function translateValue($value = '') {
		$format = $this->translateFormatToPHP();

		if (empty($value)) $value = $this->value;

		$advanced_value = Core::make('helper/text')->decodeEntities($this->getPropertyValue('advanced_value'));

		if (preg_match('/dayNames:\[(.*?)\]/', $advanced_value, $ret) && strstr($format, 'l')) {
			$days = explode(",", str_replace(array('"', '\''), '', $ret[1]));
			foreach ($days as $i => $day) {
				$adv_value[date('l', mktime(0, 0, 0, 8, $i, 2011))] = trim($day);
			}
		}

		if (preg_match('/dayNamesMin:\[(.*?)\]/', $advanced_value, $ret) && strstr($format, 'D')) {
			$days = explode(",", str_replace(array('"', '\''), '', $ret[1]));
			foreach ($days as $i => $day) {
				$adv_value[date('D', mktime(0, 0, 0, 8, $i, 2011))] = trim($day);
			}
		}

		if (preg_match('/monthNames:\[(.*?)\]/', $advanced_value, $ret) && strstr($format, 'F')) {
			$months = explode(",", str_replace(array('"', '\''), '', $ret[1]));
			foreach ($months as $i => $month) {
				$adv_value[date('F', mktime(0, 0, 0, $i+1, 1, 2011))] = trim($month);
			}
		}

		if (preg_match('/monthNamesMin:\[(.*?)\]/', $advanced_value, $ret) && strstr($format, 'M')) {
			$months = explode(",", str_replace(array('"', '\''), '', $ret[1]));
			foreach ($months as $i => $month) {
				$adv_value[date('M', mktime(0, 0, 0, $i+1, 1, 2011))] = trim($month);
			}
		}

		if (count($adv_value) && is_array($adv_value)) {
			$keys = array_keys((array)$adv_value);
			if (count($keys) && is_array($keys)) {
				foreach ($keys as $cf) {
					$pattern[] = '/\b'.$cf.'\b/';
				}
			}
		}
		return @preg_replace((array)$pattern, (array)array_values($adv_value), date($format, strtotime($value)));
	}

	private function parseDateToFormat($format, $date) {

		if (empty($date)) return '';

		$keys = array(
			'Y' => array('year', '\d{4}'),              //Année sur 4 chiffres
			'y' => array('year', '\d{2}'),              //Année sur 2 chiffres
			'm' => array('month', '\d{2}'),             //Mois au format numérique, avec zéros initiaux
			'n' => array('month', '\d{1,2}'),           //Mois sans les zéros initiaux
			'M' => array('month', '[A-Z][a-z]{3}'),     //Mois, en trois lettres, en anglais
			'F' => array('month', '[A-Z][a-z]{2,8}'),   //Mois, textuel, version longue; en anglais, comme January ou December
			'd' => array('day', '\d{2}'),               //Jour du mois, sur deux chiffres (avec un zéro initial)
			'j' => array('day', '\d{1,2}'),             //Jour du mois sans les zéros initiaux
			'D' => array('day', '[A-Z][a-z]{2}'),       //Jour de la semaine, en trois lettres (et en anglais)
			'l' => array('day', '[A-Z][a-z]{6,9}'),     //Jour de la semaine, textuel, version longue, en anglais
		);

		// convert format string to regex
		$regex = '';
		$chars = str_split($format);
		foreach ( $chars as $n => $char ) {
			$lastChar = isset($chars[$n-1]) ? $chars[$n-1] : '';
			$skipCurrent = '\\' == $lastChar;
			if ( !$skipCurrent && isset($keys[$char]) ) $regex .= '(?P<'.$keys[$char][0].'>'.$keys[$char][1].')';
			else if ( '\\' == $char ) $regex .= $char;
			else $regex .= preg_quote($char);

		}
		$dt = array();
		if (!preg_match('#^'.$regex.'$#', $date, $dt)) return false;
		foreach ($dt as $k => $v) {
			if (is_int($k)) unset($dt[$k]);
		}
		if (!isset($dt['year'])) $dt['year'] = date('Y');
		if (!isset($dt['month'])) $dt['month'] = date('m');
		if (!isset($dt['day'])) $dt['day'] = date('d');
		if (!checkdate(intval($dt['month']), intval($dt['day']), intval($dt['year']))) return false;
		return $dt;
	}
}
