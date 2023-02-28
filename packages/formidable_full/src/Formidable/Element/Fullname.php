<?php     
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

class Fullname extends Element {
	
	public $element_text = 'Full Name';
	public $element_type = 'fullname';
	public $element_group = 'Special Elements';	
		
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'required' => true,
		'tooltip' => true,
		'format' => array(
			'formats' => '',
			'note' => ''
		),
		'handling' => true,
		'errors' => array(
			'empty' => true,
		)
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
	
	public function __construct() {				
	
		$this->properties['format']['formats'] = array(
			'{firstname} {prefix} {lastname}' => t('firstname prefix lastname'),
			'{firstname} {lastname}' => t('firstname lastname'),
			'{prefix} {lastname}' => t('prefix lastname'),
			'other' => t('Other format: ')
		);
		$this->properties['format']['note'] = array(
			'{firstname} - '.t('Firstname'),
			'{prefix} - '.t('Prefix'),
			'{lastname} - '.t('Lastname'),			
			'{n} - '.t('Break / New line'),
			t('You can also use specialchars like ,.!;: etc...')
		);	
	}
	
	public function generateInput() {				
		
		$form = Core::make('helper/form');

		$value = $this->getValue();
		$handle = $this->getHandle();
		$attribs = $this->getAttributes();

		$classes = $attribs['class'];

		$attribs['class'] = $classes.' firstname';
		$attribs['placeholder'] = t('Firstname');
		$firstname = $form->text($handle.'[firstname]', isset($value['firstname'])?$value['firstname']:'', $attribs);

		$attribs['class'] = $classes.' prefix';
		$attribs['placeholder'] = t('Prefix');
		$prefix = $form->text($handle.'[prefix]', isset($value['prefix'])?$value['prefix']:'', $attribs);

		$attribs['class'] = $classes.' lastname';
		$attribs['placeholder'] = t('Lastname');
		$lastname = $form->text($handle.'[lastname]', isset($value['lastname'])?$value['lastname']:'', $attribs);

		$find = array('/{n}/', '/[,.:;!?]/', '/{firstname}/', '/{prefix}/', '/{lastname}/');
		$replace = array('<br />', '', $firstname, $prefix, $lastname);
		
		$this->setAttribute('input', preg_replace($find, $replace, $this->getFormat()));
	}

	public function validateResult() {		
		if ($this->getPropertyValue('required')) {			
			$format = $this->getFormat();				
			$error = false;				
			$val = new ValidatorResult();
			$val->setElement($this);
			$val->setData($this->post($this->getHandle()));
			$val->setReturn(false);
			if (!$error && preg_match('/{firstname}/', $format)) $error = $val->required('firstname');						
			if (!$error && preg_match('/{lastname}/', $format)) $error = $val->required('lastname');							
			if ($error) $val->addError(t('Field "%s" is invalid'));		
			return $val->getList();	
		}
		return false;
	}
	
	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$value = $this->getValue();								
		$find = array(
			'/{n}/', 
			'/([,.:;!?])/', 
			'/{firstname}/', 
			'/{prefix}/', 
			'/{lastname}/'
		);
		$replace = array(
			', ', 
			'$1', 
			isset($value['firstname'])?$value['firstname']:'', 
			isset($value['prefix'])?$value['prefix']:'',  
			isset($value['lastname'])?$value['lastname']:''
		);							 
		$value = preg_replace($find, $replace, $this->getFormat());
		return h($value);
	}
		
	private function getFormat() {
		$format = strtolower($this->getPropertyValue('format'));
		if ($format == 'other') $format = strtolower($this->getPropertyValue('format_other'));		
		return $format;
	}	
}