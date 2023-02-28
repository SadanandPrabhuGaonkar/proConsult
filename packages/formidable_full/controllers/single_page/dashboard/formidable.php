<?php 
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;

class Formidable extends DashboardPageController {

	public function view() { 
		$this->redirect('/dashboard/formidable/forms');
	}
	
}