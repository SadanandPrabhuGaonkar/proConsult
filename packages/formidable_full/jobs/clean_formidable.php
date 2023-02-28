<?php   
namespace Concrete\Package\FormidableFull\Job;

use \Concrete\Core\Job\Job as AbstractJob;
use Concrete\Core\File\Service\File as FileService;

class CleanFormidable extends AbstractJob {

	public function getJobName() {
		return t('Clean Formidable');
	}
	
	public function getJobDescription() {
		return t("Removes temporary files.");
	}
	
	public function run() {
								
		$f = new FileService();
		$f->removeAll(DIR_FILES_UPLOADED_STANDARD.'/formidable_tmp/');
		return t('All temporary files deleted');
		
	}

}