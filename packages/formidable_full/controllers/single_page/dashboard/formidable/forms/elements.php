<?php 
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard\Formidable\Forms;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\FormidableFull\Src\Formidable\Form;

class Elements extends DashboardPageController {
	
	public function __construct($c) {			
		parent::__construct($c);
		
		$this->requireAsset('javascript-inline', 'formidable/inline/dashboard/elements/top');
		$this->requireAsset('javascript', 'formidable/dashboard/common');
		$this->requireAsset('javascript', 'formidable/dashboard/layouts');
		$this->requireAsset('javascript', 'formidable/dashboard/elements');
		$this->requireAsset('javascript', 'formidable/mask');
		$this->requireAsset('css', 'formidable/dashboard');

		$this->requireAsset('ace');
	}
	
	public function view($formID = '', $from_new = false) {
		$f = Form::getByID($formID);
		if (!is_object($f)) $this->redirect('/dashboard/formidable/forms', 'message', 'notfound');
		if($from_new) $this->set('message', t('Form created successfully! Please add layouts and elements to the form'));					
		$this->set('f', $f);
	}
	
	public function message($mode = 'deleted', $formID) {
		switch($mode) {
			case 'notfound':	$this->set('error', 	t('Form or element can\'t be found!'));		break;
			case 'error':		$this->set('error', 	t('Oops, something went wrong!'));			break;
			case 'saved':		$this->set('message', 	t('Element saved successfully'));			break;
			case 'deleted':
			default:			$this->set('message', 	t('Element deleted successfully'));			break;
		}
		$this->view($formID);
	}	
}
