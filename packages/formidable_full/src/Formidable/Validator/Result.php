<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Validator;

use \Concrete\Package\FormidableFull\Src\Formidable;
use \Concrete\Core\Utility\Service\Validation\Strings;
use \Concrete\Core\Utility\Service\Validation\Numbers;

class Result extends Formidable {

	protected $element = '';

	protected $post = array();
	protected $errors = array();

	public function getErrorText($errorCode, $args = array()) {
		$errorText = $this->element->getErrorText($errorCode, $args);
		if (!empty($errorText)) return $errorText;
		return $this->getDefaultErrorText($errorCode, $args);
	}

	public function getDefaultErrorText($errorCode, $args = array()) {
		switch($errorCode) {
			case 'ERROR_EMPTY': $text = t('Field \'%s\' is empty'); break;
			case 'ERROR_INVALID_NUMERIC': $text = t('Field \'%s\' is an invalid numeric value'); break;
			case 'ERROR_INVALID_EMAIL': $text = t('Field \'%s\' is an invalid Email Address'); break;
			case 'ERROR_INVALID_URL': $text = t('Field \'%s\' is an invalid URL'); break;
			case 'ERROR_INVALID_DATE': $text = t('Field \'%s\' is an invalid date'); break;
			case 'ERROR_INVALID_TIME': $text = t('Field \'%s\' is an invalid time'); break;
			case 'ERROR_CONFIRMATION': $text = t('Fields \'%s\' and it\'s confirmation don\'t match'); break;
			case 'ERROR_ALLOWED': $text = t('Fields \'%s\' only allows the following characters: %s'); break;
			case 'ERROR_WORDS_BETWEEN': $text = t('Field \'%s\' should be between %s and %s words'); break;
			case 'ERROR_WORDS_MINIMAL': $text = t('Field \'%s\' should have at least %s words'); break;
			case 'ERROR_CHARS_BETWEEN': $text = t('Field \'%s\' should be between %s and %s charachters'); break;
			case 'ERROR_CHARS_MINIMAL': $text = t('Field \'%s\' should be at least %s charachters'); break;
			case 'ERROR_VALUE_BETWEEN': $text = t('Field \'%s\' should be a numeric value between %s and %s'); break;
			case 'ERROR_VALUE_MINIMAL': $text = t('Field \'%s\' should be a numeric value equal or greater than %s'); break;
			case 'ERROR_OPTION_COUNT': $text = t('Field \'%s\' should have %s options selected'); break;
			case 'ERROR_OPTION_BETWEEN': $text = t('Field \'%s\' should have between %s and %s options selected'); break;
			case 'ERROR_FILES_COUNT': $text = t('Field \'%s\' should have %s files'); break;
			case 'ERROR_FILES_BETWEEN': $text = t('Field \'%s\' should have between %s and %s files'); break;
			case 'ERROR_OTHER': $text = t('Field \'%s\' is invalid or empty'); break;
			case 'ERROR_EXTENSION': $text = t('Field \'%s\' has invalid file-extensions'); break;
			default: $text = t('Field \'%s\' is invalid'); break;
		}
		$count = preg_match_all('/%s/', $text, $matches);
		if ((is_array($count)) && $count > count($args)) {
			for ($i=count($args); $i<$count; $i++) $args[] = '%s';
		}
        return (is_array($args) && count($args)) ? vsprintf($text, $args) : "";
    }

	public function setElement($element) {
		$this->element = $element;
	}

	private function getCheck() {
		if (isset($this->do_check)) return $this->do_check;
		if (!is_object($this->element)) {
			$this->do_check = true;
			return $this->do_check;
		}

		// Setup dependencies and check them before validation
		$this->do_check = true;
		$dependencies = $this->element->getDependency('validate');
		if (empty($dependencies)) return $this->do_check;

		$or = array();
		foreach((array)$dependencies as $rule) {
			if (!empty($rule)) {
				$and = array();
				foreach((array)$rule as $dependency) {
					$check = false;
					$value = (array)$this->data[$dependency['element']];
					if (!empty($dependency['value'])) {
						if (array_intersect((array)$dependency['value'], (array)$value)) $check = true;
					} else {
						if (!empty($value)) $check = true;
					}
					if ($dependency['inverse']) {
						if ($check) $check = false;
						else $check = true;
					}
					$and[] = $check;
				}
				if (in_array(false, (array)$and)) $or[] = false;
				else $or[] = true;
			}
		}
		if (!in_array(true, (array)$or)) $this->do_check = false;
		return $this->do_check;
	}

	public function setReturn($return = false) {
		$this->return = $return;
	}

	private function getReturn() {
		return isset($this->return)?$this->return:false;
	}

	public function setData($data) {
		$this->data = $data;
	}
	public function getData($key = '') {
		if ($key == null) return $this->data;
		return is_array($this->data) && array_key_exists($key, $this->data)?$this->data[$key]:'';
	}
	public function addError($errorCode, $args = array()) {
		if ($this->getCheck()) {
			if ($this->getReturn())	return false;
			if (array_key_exists($this->element->getHandle(), $this->errors)) return false;
			if (empty($args)) $args = array($this->element->getLabel());
			$this->errors[$this->element->getHandle()] = $this->getErrorText($errorCode, $args);
		}
	}

	public function required($key = '') {
        $app = \Core::make('app');
        $str = new Strings($app);
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		if ((!is_array($value) && !$str->notempty($value)) || (is_array($value) && !count(array_filter($value)))) $this->addError('ERROR_EMPTY');
	}

	public function integer($key = '') {
		$nbr = new Numbers();
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		if (!$nbr->integer($value)) $this->addError('ERROR_INVALID_INTEGER');
	}

	public function email($key = '') {
        $app = \Core::make('app');
        $str = new Strings($app);
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		//if (!$str->email($value)) $this->addError('ERROR_INVALID_EMAIL');
		if (empty($value) || !preg_match('/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD', $value)) $this->addError('ERROR_INVALID_EMAIL');
	}

	public function url($key = '') {
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		$value = filter_var($value, FILTER_SANITIZE_URL);
		//if (empty($value) || !filter_var($value, FILTER_VALIDATE_URL)) $this->addError('ERROR_INVALID_URL');
		if (empty($value) || !preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$value)) $this->addError('ERROR_INVALID_URL');
	}

	public function confirmation($key = '') {
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		$confirm = $this->getData(!empty($key)?$key:$this->element->getHandle().'_confirm');
		if (empty($value) || empty($confirm) || strtolower($value) != strtolower($confirm)) $this->addError('ERROR_CONFIRMATION');
	}

	public function charsAllowed($key = '') {
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		if (empty($value) || !$this->element->getPropertyValue('chars_allowed')) return false;
		$check_for = array();
		$allowed = $this->element->getPropertyValue('chars_allowed_value');
		foreach ((array)$allowed as $allow) {
			$check_for[] = ($allow=='lcase')?'a-z':'';
			$check_for[] = ($allow=='ucase')?'A-Z':'';
			$check_for[] = ($allow=='digits')?'0-9':'';
			$check_for[] = ($allow=='symbols')?'\!\#$%&()\*+-=?\[\]{}|~':'';
		}
		if (count($check_for)) {
			if (!preg_match('/^['.@implode('', $check_for).']*$/', $value)) $this->addError('ERROR_ALLOWED', array(@implode(' ', $check_for)));
		}
	}

	public function minMax($key = '') {
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		if (empty($value) || !$this->element->getPropertyValue('min_max')) return false;
		$app = \Core::make('app');
		$str = new Strings($app);
        $newLines = substr_count( $value, "\n" );
        switch ($this->element->getPropertyValue('min_max_type')) {
            case 'words':
                $words = array_filter(explode(" ", $value));
                if (count($words) < $this->element->getPropertyValue('min_value') || (count($words) > $this->element->getPropertyValue('max_value') && $this->element->getPropertyValue('max_value') > 0)) {
                    if ($this->element->getPropertyValue('max_value') > 0) $this->addError('ERROR_WORDS_BETWEEN', array($this->element->getLabel(), $this->element->getPropertyValue('min_value'), $this->element->getPropertyValue('max_value')));
                    else $this->addError('ERROR_WORDS_MINIMAL', array($this->element->getLabel(), $this->element->getPropertyValue('min_value')));
                }
                break;
            case 'characters':
                if (!$str->min($value, $this->element->getPropertyValue('min_value')) || (!$str->max($value, $this->element->getPropertyValue('max_value') + $newLines) && $this->element->getPropertyValue('max_value') > 0)) {
                    if ($this->element->getPropertyValue('max_value') > 0) $this->addError('ERROR_CHARS_BETWEEN', array($this->element->getLabel(), $this->element->getPropertyValue('min_value'), $this->element->getPropertyValue('max_value')));
                    else $this->addError('ERROR_CHARS_MINIMAL', array($this->element->getLabel(), $this->element->getPropertyValue('min_value')));
                }
                break;
            case 'value':
                if ($value < $this->element->getPropertyValue('min_value') || ($value > $this->element->getPropertyValue('max_value') && $this->element->getPropertyValue('max_value') > 0)) {
                    if ($this->element->getPropertyValue('max_value') > 0) $this->addError('ERROR_VALUE_BETWEEN', array($this->element->getLabel(), $this->element->getPropertyValue('min_value'), $this->element->getPropertyValue('max_value')));
                    else $this->addError('ERROR_VALUE_MINIMAL', array($this->element->getLabel(), $this->element->getPropertyValue('min_value')));
                }
                break;
            case 'options':
                $value = array_filter($value);
                if (count($value) < $this->element->getPropertyValue('min_value') || (count($value) > $this->element->getPropertyValue('max_value') && $this->element->getPropertyValue('max_value') > 0)) {
                    if ($this->element->getPropertyValue('min_value') == $this->element->getPropertyValue('max_value') && $this->element->getPropertyValue('max_value') > 0) $this->addError('ERROR_OPTION_COUNT', array($this->element->getLabel(), $this->element->getPropertyValue('min_value')));
                    else $this->addError('ERROR_OPTION_BETWEEN', array($this->element->getLabel(), $this->element->getPropertyValue('min_value'), $this->element->getPropertyValue('max_value')));
                }
                break;
            case 'files':
                $value = array_filter($value);
                if (count($value) < $this->element->getPropertyValue('min_value') || (count($value) > $this->element->getPropertyValue('max_value') && $this->element->getPropertyValue('max_value') > 0)) {
                    if (($this->element->getPropertyValue('min_value') == $this->element->getPropertyValue('max_value') || $this->element->getPropertyValue('min_value') <= 0) && $this->element->getPropertyValue('max_value') > 0) $this->addError('ERROR_FILES_COUNT', array($this->element->getLabel(), $this->element->getPropertyValue('max_value')));
                    else $this->addError('ERROR_FILES_BETWEEN', array($this->element->getLabel(), $this->element->getPropertyValue('min_value'), $this->element->getPropertyValue('max_value')));
                }
                break;
        }
    }

	public function other($key = '') {
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		if (!is_array($value)) return false;
		$other = array_pop($value);
		if ($other == 'option_other') {
			$other = $this->getData(!empty($key)?$key:$this->element->getHandle().'_other');
            $app = \Core::make('app');
            $str = new Strings($app);
			if (!$str->notempty($other)) $this->addError('ERROR_OTHER');
		}
	}

	public function allowedExtensions($key = '') {
		$value = $this->getData(!empty($key)?$key:$this->element->getHandle());
		if (!is_array($value)) return false;
		foreach($value as $v) {
			if (!preg_match('/'.$v['ext'].'/i', $this->getAllowedExtensionsValue())) $this->addError('ERROR_EXTENSION');
		}
	}

	public function getList() {
		if (!empty($this->errors)) return $this->errors;
		return false;
	}
}
