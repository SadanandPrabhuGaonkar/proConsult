<?php  
namespace Concrete\Package\FormidableFull\Src\Helpers;

class LinkHelper {

	public function url_and_email_ify($string) {
		if (empty($string)) return $string;
		
		if (preg_match("/<a [^>]*href=\"(.+)\"/", $string) <= 0) return html_entity_decode($string);

		if (preg_match("/<[^<]+>/", $string, $m) <= 0) {
			$string = $this->urlify($string);	
			$string = $this->emailify($string);	
		}
		return $string;		
	}
	
	
	public function urlify($string) {
		if (empty($string))	return $string;		
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match_all($reg_exUrl, $string, $matches);
		foreach($matches[0] as $pattern) {
			$string = str_replace($pattern, "<a href=\"{$pattern}\" rel=\"nofollow\" target=\"_blank\">{$pattern}</a>", $string);   
		}
		return $string;            
	}
	
	public function emailify($string) {
		if (empty($string))	return $string;			
		$reg_exUrl ="/(?:[a-zA-Z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-zA-Z0-9-]*[a-zA-Z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

		preg_match_all($reg_exUrl, $string, $matches);
		foreach($matches[0] as $pattern) {
			$string = str_replace($pattern, "<a href=\"mailto:{$pattern}\" rel=\"nofollow\">{$pattern}</a>", $string);   
		}
		return $string;         
	}
}
