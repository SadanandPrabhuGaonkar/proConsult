<?php     
namespace Concrete\Package\FormidableFull\Block\Formidable;

use \Concrete\Core\Block\BlockController;
use Page;
use Events;
use Core;
use \Concrete\Core\Validation\CSRF\Token;
use \Concrete\Package\FormidableFull\Src\Formidable;
use \Concrete\Package\FormidableFull\Src\Formidable\Form;

class Controller extends BlockController {
	
	protected $pkgHandle = 'formidable_full';

	protected $btInterfaceWidth = 500;
	protected $btInterfaceHeight = 300;
	protected $btTable = 'btFormidable';
	
	protected $btCacheBlockRecord = false;
	protected $btCacheBlockOutput = false;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = 300;
	
	protected $btDefaultSet = 'form';

	public $view_type = '';
	
	public function getBlockTypeDescription() {
		return t("Adds a Formidable Form to you page.");
	}
	
	public function getBlockTypeName() {
		return t("Formidable");
	}		
	
	public function getJavaScriptStrings() {
		return array(
			'form-required' => t('You must select a form.')
		);
	}
	
	public function on_start() {
	    parent::on_start();		
		$this->set('forms', Formidable::getAllForms());
	}
	
	public function view() {					
		
		$this->requireAsset('javascript', 'jquery');
		$this->requireAsset('javascript', 'jquery/ui');
		$this->requireAsset('javascript', 'bootstrap/tooltip');
		$this->requireAsset('css', 'bootstrap/*');
		$this->requireAsset('css', 'jquery/ui');
		$this->requireAsset('css', 'core/frontend/errors');

		$this->requireAsset('javascript', 'formidable/top');
		$this->requireAsset('javascript', 'formidable/placeholder');
		$this->requireAsset('javascript', 'formidable/dependson');
		$this->requireAsset('javascript', 'formidable/mask');
		$this->requireAsset('javascript', 'formidable/countable');
		$this->requireAsset('javascript', 'formidable/timepicker');
		$this->requireAsset('javascript', 'formidable/dropzone');
		$this->requireAsset('javascript', 'formidable/slider');
		$this->requireAsset('javascript', 'formidable/rating');
		$this->requireAsset('javascript', 'formidable');

		$this->requireAsset('selectize');				

		$c = Page::getCurrentPage();
		
		if (is_object($this->form)) $form = $this->form;
		else {
			if (!$this->formID) return false;
			$form = Form::getByID($this->formID);			
		}		
		if (!is_object($form)) return false;
		
		if (isset($this->view_type)) $this->set('view_type', $this->view_type);

		// When view_type is set, skip limits and scheduling...
		if (empty($this->view_type)) {
			if ($form->checkLimits()) {										
				if ($form->getAttribute('limits_redirect')) {
					$p = Page::getByID($form->getAttribute('limits_redirect_page'));
					if (is_object($p)) {
						$this->redirect($p->getCollectionPath());
						exit();
					}
				}
				$this->set('limits', $form->getAttribute('limits_redirect_content'));				
			}
				
			if ($form->checkSchedule()) {										
				if ($form->getAttribute('schedule_redirect')) {
					$p = Page::getByID($form->getAttribute('schedule_redirect_page'));
					if (is_object($p)) {
						$this->redirect($p->getCollectionPath());
						exit();
					}
				}
				$this->set('schedule', $form->getAttribute('schedule_redirect_content'));
			}
		}

		$form->setAttribute('block_id', $this->bID);	

		$cID = $this->post('cID');
		if (is_object($c)) $cID = $c->getCollectionID();
		$form->setAttribute('collection_id', $cID); 
		
		$valt = new Token();
		$form->setAttribute('token', $valt->generate('formidable_form'));		
		
		// Generate form layout and elements
		$form->generate();

		// Fire event
		Events::fire('on_formidable_load', $form);
																		
		$this->set('f', $form);		
		
	}
}
