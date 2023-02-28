<?php      
namespace Concrete\Package\FormidableFull\Src\Formidable\Element;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;
use Core;
use Package;

class Invisiblecaptcha extends Element {
	
	public $element_text = 'Invisible Captcha';
	public $element_type = 'invisiblecaptcha';
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
        $config = Package::getByHandle('ec_recaptcha')->getConfig();

		$attribs = $this->getAttributes();
		$aks = @implode(' ', array_map( function ($v, $k) { return sprintf("%s='%s'", $k, $v); }, $attribs, array_keys($attribs)));	

		$element  = '<div class="captcha_holder '.$aks.'">';
		$element .= '<div id="'.$this->getHandle().'" class="captcha_input"></div>';
		$element .= '</div>';
		$element .= '<div class="dummyFormidableClick" style="display: none;"></div>';


        $element .= '
						 <script async defer>
								var head = document.getElementsByTagName(\'head\')[0];
								var js = document.createElement("script");
								js.type = "text/javascript";
								js.setAttribute("async", "");
								js.setAttribute("defer", "");
								js.src = "https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit";
								var initializedCaptcha = 0;
															
								$(window).on("scroll load", function() {
								    setTimeout(function() {
								        if($(".formidable").hasClass("formInview")) {
								            if(!initializedCaptcha){
								                head.appendChild(js);
								                initializedCaptcha = 1;
								                console.log("clicked");
								            }
								        }
								    },100);
								});
								
								 var widgetId = [];
								 function onloadCallback() {
									 var i=0;
									 console.log(":reached here");
									$(".captcha--buttons").each(function() {
										var object = $(this);
										widgetId[i] = grecaptcha.render(object.attr("id"), {
											"size" : "invisible",
											"sitekey" : "' . $config->get('captcha.site_key') . '",
											"callback" : function(token) {
												object.parents(\'form\').find(".g-recaptcha-response").val(token);
												//console.log(object.parents(\'form\').find(\'.dummyFormidableClick\'));
												object.parents(\'form\').find(\'.dummyFormidableClick\').trigger(\'click\');
											}
										});
										i++;
									});
								 }
						</script>';

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