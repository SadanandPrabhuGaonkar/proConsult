<?php 
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard\Reports;

use \Concrete\Core\Page\Controller\DashboardPageController;

class Formidable extends DashboardPageController {

	public function view() {
		$this->redirect('/dashboard/formidable/results/');
	}
}