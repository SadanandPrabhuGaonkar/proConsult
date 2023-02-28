<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use \Concrete\Core\Localization\Service\CountryList;
use \Concrete\Core\Localization\Service\StatesProvincesList;
use Core;

class Address extends Element {
	
	public $element_text = 'Address Field(s)';
	public $element_type = 'address';
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
		'handling' => false,
		'errors' => array(
			'empty' => true,
		)
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
	
	public function __construct($elementID = 0) {						
		$this->properties['format']['formats'] = array(
			'{address_1}{n}{address_2}{n}{city}{n}{state}{n}{country}{n}{postal_code}' => t('address_1 {n} address_2 {n} city {n} province {n} country {n} postal_code'),			
			'{address_1}{n}{address_2}{n}{city}{n}{province}{n}{country}{n}{postal_code}' => t('address_1 {n} address_2 {n} city {n} province {n} country {n} postal_code'),
			'{address_1}{n}{address_2}{n}{city}{n}{county}{n}{country}{n}{zipcode}' => t('address_1 {n} address_2 {n} city {n} county {n} country {n} zipcode'),
			
			'{address_1}{n}{address_2}{n}{city}{n}{state}{n}{postal_code}{n}{country}' => t('address_1 {n} address_2 {n} city {n} state {n} postal_code {n} country'),
			'{address_1}{n}{address_2}{n}{city}{n}{province}{n}{postal_code}{n}{country}' => t('address_1 {n} address_2 {n} city {n} province {n} postal_code {n} country'),
			'{address_1}{n}{address_2}{n}{city}{n}{county}{n}{zipcode}{n}{country}' => t('address_1 {n} address_2 {n} city {n} county {n} zipcode {n} country'),			
			
			'{address_1}{n}{address_2}{n}{city}{n}{country}{n}{state}{n}{postal_code}' => t('address_1 {n} address_2 {n} city {n} country {n} state {n} postal_code'),
			'{address_1}{n}{address_2}{n}{city}{n}{country}{n}{province}{n}{postal_code}' => t('address_1 {n} address_2 {n} city {n} country {n} province {n} postal_code'),
			'{address_1}{n}{address_2}{n}{city}{n}{country}{n}{county}{n}{zipcode}' => t('address_1 {n} address_2 {n} city {n} country {n} county {n} zipcode'),
			
			'{address_1} {address_2} {postal_code} {city}, {state}{n}{country}' => t('address_1 address_2 {n} postal_code city, state {n} country'),
			'{address_1} {address_2} {postal_code} {city}, {province}{n}{country}' => t('address_1 address_2 {n} postal_code city, province {n} country'),
			'{address_1} {address_2} {zipcode} {city}, {county}{n}{country}' => t('address_1 address_2 {n} zipcode city, county {n} country'),
			
			'{address_1} {postal_code} {city}, {state}{n}{country}' => t('address_1 {n} postal_code city, state {n} country'),
			'{address_1} {postal_code} {city}, {province}{n}{country}' => t('address_1 {n} postal_code city, province {n} country'),
			'{address_1} {zipcode} {city}, {county}{n}{country}' => t('address_1 {n} zipcode city, county {n} country'),
			'{street} {number}{n}{zipcode} {city}, {state}{n}{country}' => t('street, number {n} zipcode city, state {n} country'),
			'{street} {number}{n}{zipcode} {city}, {province}{n}{country}' => t('street, number {n} zipcode city, province {n} country'),
			'{street} {number}{n}{zipcode} {city}, {county}{n}{country}' => t('street, number {n} zipcode city, county {n} country'),
			'{street} {number}{n}{zipcode} {city}{n}{country}' => t('street, number {n} zipcode city{n} country'),
			'other' => t('Other format: ')
		);
		$this->properties['format']['note'] = array(
			'{street} - '.t('Street'),
			'{number} - '.t('Number'),
			'{address_1} - '.t('Address 1'),
			'{address_2} - '.t('Address 2'),
			'{city} - '.t('City'),
			'{state} - '.t('State'),
			'{province} - '.t('Province'),			
			'{county} - '.t('County'),
			'{country} - '.t('Country'),
			'{postal_code} - '.t('Postal Code'),
			'{zipcode} - '.t('Zipcode'),
			'{n} - '.t('Break / New line'),
			t('You can also use specialchars like ,.!;: etc...')
		);	
	}
	
	public function generateInput() {			
		
		$form = Core::make('helper/form');

		$value = $this->getValue();
		$handle = $this->getHandle();
		$attribs = $this->getAttributes();
		
		$class = $attribs['class'];

		$attribs['class'] = $class.' address_1';
		$attribs['placeholder'] = t('Address 1');
		$address1 = $form->text($handle.'[address_1]', isset($value['address_1'])?$value['address_1']:'', $attribs);

		$attribs['class'] = $class.' address_2';
		$attribs['placeholder'] = t('Address 2');
		$address2 = $form->text($handle.'[address_2]', isset($value['address_2'])?$value['address_2']:'', $attribs);
		
		$attribs['class'] = $class.' city';
		$attribs['placeholder'] = t('City');		
		$city = $form->text($handle.'[city]', isset($value['city'])?$value['city']:'', $attribs);
				
		$attribs['class'] = $class.' county county_input';
		$attribs['placeholder'] = t('County');
		$attribs['ccm-attribute-address-field-name'] = $handle.'[province]';		
		$county = $form->text($handle.'[province]', isset($value['province'])?$value['province']:'', $attribs);
				
		$attribs['class'] = $class.' county county_select';
		$attribs['placeholder'] = t('County');
		$attribs['ccm-attribute-address-field-name'] = $handle.'[province]';		
		$county .= $form->select($handle.'[province]', array(), isset($value['province'])?$value['province']:'', $attribs);
				
		$attribs['class'] = $class.' province province_input';
		$attribs['placeholder'] = t('Province');
		$attribs['ccm-attribute-address-field-name'] = $handle.'[province]';		
		$province = $form->text($handle.'[province]', isset($value['province'])?$value['province']:'', $attribs);
				
		$attribs['class'] = $class.' province province_select';
		$attribs['ccm-attribute-address-field-name'] = $handle.'[province]';		
		$province .= $form->select($handle.'[province]', array(), isset($value['province'])?$value['province']:'', $attribs);
				
		$attribs['class'] = $class.' state state_input';
		$attribs['placeholder'] = t('State');
		$attribs['ccm-attribute-address-field-name'] = $handle.'[province]';		
		$state = $form->text($handle.'[province]', isset($value['province'])?$value['province']:'', $attribs);
				
		$attribs['class'] = $class.' state state_select';
		$attribs['placeholder'] = t('State');
		$attribs['ccm-attribute-address-field-name'] = $handle.'[province]';		
		$state .= $form->select($handle.'[province]', array(), isset($value['province'])?$value['province']:'', $attribs);
		
		$attribs['class'] = $class.' country country_select';
		$attribs['data-name'] .= $handle;
		$country = $form->select($handle.'[country]', $this->getCountries(), isset($value['country'])?$value['country']:'', $attribs);
				
		$attribs['class'] = $class.' postal_code';
		$attribs['placeholder'] = t('Postal Code');		
		$postal_code = $form->text($handle.'[zipcode]', isset($value['zipcode'])?$value['zipcode']:'', $attribs);
				
		$attribs['class'] = $class.' zipcode';
		$attribs['placeholder'] = t('Zipcode');		
		$zipcode = $form->text($handle.'[zipcode]', isset($value['zipcode'])?$value['zipcode']:'', $attribs);
				
		$attribs['class'] = $class.' street';
		$attribs['placeholder'] = t('Street');		
		$street = $form->text($handle.'[street]', isset($value['street'])?$value['street']:'', $attribs);
				
		$attribs['class'] = $class.' number';
		$attribs['placeholder'] = t('Number');		
		$number = $form->text($handle.'[number]', isset($value['number'])?$value['number']:'', $attribs);
				
		$find = array('/{n}/', '/[,.:;!?]/', '/{address_1}/', '/{address_2}/', '/{city}/', '/{country}/', '/{zipcode}/', '/{postal_code}/', '/{province}/', '/{county}/', '/{state}/', '/{street}/', '/{number}/');
		$replace = array('<br />', '', $address1, $address2, $city, $country, $zipcode, $postal_code, $province, $county, $state, $street, $number);
		
		$this->setAttribute('input', preg_replace($find, $replace, $this->getFormat()));
		
		$this->addJavascript($this->loadProvinceJS(), false);			
	}
	
	public function validateResult() {		
		if ($this->getPropertyValue('required')) {									
			$val = new ValidatorResult();
			$val->setElement($this);
			$val->setData($this->post($this->getHandle()));
			$val->setReturn(false);

			$format = $this->getFormat();
			$error = false;				
			
			if (!$error && preg_match('/{address_1}/', $format) > 0) $error = $val->required('address_1');						
			if (!$error && preg_match('/{city}/', $format) > 0) $error = $val->required('city');					
			if (!$error && preg_match('/{province}|{county}|{state}/', $format) > 0) $error = $val->required('province');								
			if (!$error && preg_match('/{country}/', $format) > 0) $error = $val->required('country');					
			if (!$error && preg_match('/{postal_code}|{zipcode}/', $format) > 0) $error = $val->required('zipcode');										
			if (!$error && preg_match('/{street}/', $format) > 0) $error = $val->required('street');						
			if (!$error && preg_match('/{number}/', $format) > 0) $error = $val->required('number');	
			if ($error) $val->add($val->getErrorText('ERROR_EMPTY'));		
			return $val->getList();	
		}
		return false;		
	}
	
	public function getDisplayValue($seperator = ' ', $urlify = true) {
		$value = $this->getValue();								
		$find = array(
			'/{n}/', 
			'/([,.:;!?])/', 
			'/{address_1}/', 
			'/{address_2}/', 
			'/{city}/', 
			'/{country}/', 
			'/{zipcode}/', 
			'/{postal_code}/', 
			'/{province}/', 
			'/{county}/', 
			'/{state}/', 
			'/{street}/', 
			'/{number}/'
		);
		$replace = array(
			', ', 
			'$1', 
			isset($value['address_1'])?$value['address_1']:'', 
			isset($value['address_2'])?$value['address_2']:'',  
			isset($value['city'])?$value['city']:'',  
			isset($value['country'])?$this->getCountryName($value['country']):'', 
			isset($value['zipcode'])?$value['zipcode']:'',  
			isset($value['zipcode'])?$value['zipcode']:'',  
			isset($value['province'])?$this->getProvinceName($value['province'], $value['country']):'',
			isset($value['province'])?$this->getProvinceName($value['province'], $value['country']):'',
			isset($value['province'])?$this->getProvinceName($value['province'], $value['country']):'',
			isset($value['street'])?$value['street']:'', 
			isset($value['number'])?$value['number']:''
		);							 
		$value = preg_replace($find, $replace, $this->getFormat());
		return h($value);
	}
	
	private function getFormat() {
		$format = strtolower($this->getPropertyValue('format'));
		if ($format == 'other') $format = strtolower($this->getPropertyValue('format_other'));		
		return $format;
	}
	
	private function getCountries() {
		$cl = new CountryList();
		$countries = $cl->getCountries();
		asort($countries, SORT_LOCALE_STRING);
		return array_merge(array('' => t('Choose country')), $countries);
	}
	
	private function getCountryName($country) {
		$cl = new CountryList();
		return $cl->getCountryName($country);
	}
	
	private function getProvinceName($province, $country) {
		$spl = new StatesProvincesList();
		$val = $spl->getStateProvinceName($province, $country);
		if ($val == '') return $province;
		return $val;
	}
	
	private function loadProvinceJS() {
		$return = "var ccmFormidableAddressStatesTextList = '";
		$spl = new StatesProvincesList();
		$all = $spl->getAll();
		foreach($all as $country => $countries) {
			foreach($countries as $value => $text) {
				$return .= addslashes($country) . ':' . addslashes($value) . ':' . addslashes($text) . "|";
			}
		}
		$return .= "';";
		return $return;
	}
	
}
