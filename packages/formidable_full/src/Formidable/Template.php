<?php    
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Package\FormidableFull\Src\Formidable;
use \Concrete\Core\Http\Service\Json;
use Core;
use Database;
use URL;

class Template extends Formidable {

	public static function getByID($templateID) {
		$item = new Template();
		if ($item->load($templateID)) return $item;
		return false;
	}

	public function load($templateID) {		
		if (intval($templateID) == 0) return false;		
		$db = Database::connection();					
		$template = $db->fetchAssoc("SELECT * FROM FormidableTemplates WHERE templateID = ?", array($templateID));	
		if (!$template) return false;			
		$this->setAttributes($template);	
		return true;
	}

	public function getUsedCount() {		
		$db = Database::connection();	
		$q = "SELECT COUNT(mailingID) AS total FROM FormidableFormMailings WHERE templateID = ?";			  
		$p = array($this->getTemplateID());		
		$data = $db->fetchColumn($q, $p);
		return intval($data);
	}
	
	public function save($data) {
		if (!$this->getTemplateID()) return $this->add($data);
		return $this->update($data);	 
	}
	
	private function add($data) {		
		$db = Database::connection();	
		$db->insert('FormidableTemplates', $data);	
		$templateID = $db->lastInsertId();
		if (empty($templateID)) return false;
		$this->load($templateID);		
		return true;
	}
	
	private function update($data) {					
		$db = Database::connection();	
		$db->update('FormidableTemplates', $data, array('templateID' => $this->getTemplateID()));
		$this->load($this->getTemplateID());		
		return true;
	}

	public function duplicate() {	
		$db = Database::connection();					
		$template = $db->fetchAssoc("SELECT * FROM FormidableTemplates WHERE templateID = ?", array($this->getTemplateID()));	
		if (!$template) return false;

		// Set new params	
		$template['label'] = t('%s (copy)', $template['label']);

		// Unset current templateID
		unset($template['templateID']);

		$nt = new Template();			
		if (!$nt->add($template)) return false;
		return $nt;
	}

	public function delete() {
		// Remove template
		$db = Database::connection();	
		$db->delete('FormidableTemplates', array('templateID' => $this->getTemplateID()));		
		return true;
	}

	public function getTemplateID() {
		return is_numeric($this->templateID)?$this->templateID:false;
	}
}


