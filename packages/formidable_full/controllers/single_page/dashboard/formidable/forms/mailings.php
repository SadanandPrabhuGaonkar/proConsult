<?php 
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard\Formidable\Forms;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\FormidableFull\Src\Formidable\Form;

class Mailings extends DashboardPageController {
		
	function __construct($c) {			
		parent::__construct($c);

		$this->requireAsset('javascript-inline', 'formidable/inline/dashboard/mailings/top');
		$this->requireAsset('javascript', 'formidable/dashboard/common');
		$this->requireAsset('javascript', 'formidable/dashboard/mailings');
		$this->requireAsset('css', 'formidable/dashboard');
	}
		
	public function view($formID = '') {		
		$f = Form::getByID($formID);
		if (!is_object($f)) $this->redirect('/dashboard/formidable/forms', 'message', 'notfound');				
		$this->set('f', $f);
	}	
				
	public function message($mode = 'error', $formID) {
		switch($mode) {
			case 'error':		$this->set('error', 	t('Oops, something went wrong!'));			break;
			case 'saved':		$this->set('message', 	t('Mailing saved successfully'));			break;
			case 'deleted':
			default:			$this->set('message', 	t('Mailing deleted successfully'));			break;
		}
		$this->view($formID);
	}
}