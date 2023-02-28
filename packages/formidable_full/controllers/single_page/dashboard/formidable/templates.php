<?php 
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard\Formidable;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\FormidableFull\Src\Formidable\Template;
use \Concrete\Core\Form\Service\Validation;
use Config;
use Core;
use AssetList;
use Concrete\Core\Editor\Plugin;

class Templates extends DashboardPageController {

	protected $pkgHandle = 'formidable_full';
	
	public function __construct($c) {	
		parent::__construct($c);	
		$this->requireAsset('javascript-inline', 'formidable/inline/dashboard/templates/top');	
		$this->requireAsset('javascript', 'formidable/dashboard/common');
		$this->requireAsset('javascript', 'formidable/dashboard/templates');

		$this->requireAsset('css', 'formidable/dashboard');
	}

	public function view() {

	}
						
	public function add() {	
		$t = new Template();		
		
		$sitename = Config::get('concrete.site');	

		$t->label = t('My Formidable Template');
		$t->setAttribute('template', sprintf('<p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>', t('Logo %s', $sitename), '{%formidable_mailing%}', t('Thank you!'), t('Regards,'), $sitename));				
		
		$this->set('t', $t);
		$this->set('create_template', true);
		$this->load_editor();
	}
	
	public function edit($id, $new = false) {			
		if ($new) $this->set('message', t('Template saved successfully'));
								
		$t = Template::getByID($id);		
		if (is_object($t)) {					
			$this->set('t', $t);		
			$this->set('create_template', true);
			$this->load_editor();
		}
		else {
			$this->message('notfound');
			$this->view();
		}
	}

	private function load_editor() {
		$editor = Core::make('editor');
		
		// Set editor
		$al = AssetList::getInstance();							
		$al->registerGroup('formidable/template', array(array('javascript', 'formidable/template')));

	    $plugin = new Plugin();
	    $plugin->setKey('formidable');
	    $plugin->setName('Formidable');
	    $plugin->requireAsset('formidable/template');
		
		$editor->getPluginManager()->register($plugin);		    
		$editor->getPluginManager()->select('formidable');

		$this->set('editor', $editor);
	}
		
	public function save() {	
		
		$val = new Validation();	
		$val->setData($this->post());
		
		$val->addRequired('label', t('Field "%s" is invalid', t('Name')));	
		$val->addRequired('content', t('Field "%s" is invalid', t('Content')));		
		if (!empty($this->post('content')) && !preg_match('/{%formidable_mailing%}/', $this->post('content'))) $val->addRequired('dummy', t('Field "%s" is missing the %s-tag', t('Content'), '{%formidable_mailing%}')); 
		if ($val->test()) {	
			$v = array(
				'templateID' => $this->post('templateID'),
				'label' => h($this->post('label')), 
				'content' => $this->post('content'),
			);			
			$t = new Template();
			if ($this->post('templateID')) $t->load($this->post('templateID'));						
			$t->save($v);			
			$this->redirect('/dashboard/formidable/templates/');
		}
		$this->set('errors', $val->getError()->getList());
		$this->set('create_template', true);

		if ($this->post('templateID')) {					
			$t = Template::getByID($this->post('templateID'));
			if (is_object($t)) {					
				$this->set('t', $t);
			}
		}
	}
	
	public function message($mode = 'deleted') {
		switch($mode) {
			case 'notfound': $this->set('errors', t('Template can\'t be found!')); break;
			case 'error': $this->set('errors', t('Oops, something went wrong!')); break;
			case 'saved': $this->set('message', t('Template saved successfully')); break;
			default: $this->set('message', t('Template deleted successfully')); break;
		}
		$this->view();
	}
}