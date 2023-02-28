<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable\Search;

use \Concrete\Package\FormidableFull\Src\Formidable\Element;
use \Concrete\Core\Foundation\ConcreteObject;
use Core;

class ResultItem extends ConcreteObject {

	public function __construct($attr = array()) {		
		foreach ($attr as $key => $value)  {        	
        	if (strpos($key, 'element_') !== false) {  		
        		$element = Element::getByID(intval(substr($key, 8)));
        		if (is_object($element)) {
        			if (!empty($attr['raw_element_'.$element->getElementID()])) $value = unserialize($attr['raw_element_'.$element->getElementID()]);
					$element->setValue($value, true); // Force setting value
					$this->{'element-'.$element->getElementID()} = $element;
				}	        	
	        } else $this->{$key} = $value;      
        }
	}
	public function __call($nm, $a) {			
		if (substr($nm, 0, 21) == 'getDisplayValueExport') {
			if (!method_exists($this, $nm)) {
		    	$var = 'element-'.substr($nm, 21);		    	
		    	return $this->{$var}->getDisplayValueExport();
	    	}
	    }
		if (substr($nm, 0, 15) == 'getDisplayValue') {
			if (!method_exists($this, $nm)) {	    	
		    	$var = 'element-'.substr($nm, 15);	
		    	return $this->{$var}->getDisplayValue();
	    	}
	    }	   
	    return $this->{$nm};        
    }    
}