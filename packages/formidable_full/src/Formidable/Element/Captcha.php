<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;

class Captcha extends Element {
	
	public $element_text = 'Captcha';
	public $element_type = 'captcha';
	public $element_group = 'Special Elements';		
	
	public $properties = array(
		'label' => true,
		'label_hide' => true,
		'required' => true,					
		'tooltip' => true,
		'css' => false,
		'handling' => false
	);
	
	public $dependency = array(
		'has_value_change' => false
	);
	
	public function generateInput() {			
		$captcha = Core::make("captcha");	

		// Stupid hey!	
		ob_start();
		$captcha->display();
		$display = ob_get_clean();

		ob_start();
		$captcha->showInput();
		$input = ob_get_clean();

		$attribs = $this->getAttributes();
		$aks = @implode(' ', array_map( function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $attribs, array_keys($attribs)));	

		$element  = '<div class="captcha_holder '.$aks.'">';
		$element .= '<div id="'.$this->getHandle().'_image" class="captcha_image">'.$display.'</div>';
		$element .= '<div id="'.$this->getHandle().'" class="captcha_input">'.$input.'</div>';
		$element .= '</div>';

		$this->setAttribute('input', $element);
	}

	public function validateResult() {
		$val = new ValidatorResult();
		$val->setElement($this);
		$val->setData($this->post());
		if ($this->getPropertyValue('required')) {
			$captcha = Core::make("captcha");
			if (!$captcha->check()) $val->addError('ERROR_EMPTY');
		}
		return $val->getList();	
	}
}