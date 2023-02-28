<?php 
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard\Formidable;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Mailing;
use \Concrete\Core\Form\Service\Validation;
use \Concrete\Core\Form\Service\Widget\DateTime;
use UserInfo;
use Config;

class Forms extends DashboardPageController {

	protected $pkgHandle = 'formidable_full';

	private $default_mailing = '';
	
	public function __construct($c) {	
		parent::__construct($c);
				
		// Default mailing on creating ne form		
		$ui = UserInfo::getByID(USER_SUPER_ID);
		
		$sitename = Config::get('concrete.site');		
		$this->default_mailing = array(
			'mailingID' => 0,
		    'from_type' => 'other',
		    'from_name' => $sitename,
		    'from_email' => $ui->getUserEmail(),
		    'send_custom' => 1,
		    'send_custom_value' => $ui->getUserEmail(),
		    'subject' => t('%s submission', $sitename),
		    'message' => sprintf('<p>%s<br />%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>',
								 t('You successfully sent our %s on our Concrete5 website.', $sitename),
								 t('The following information was sent to us:'),
								 '{%all_elements%}',
								 t('Thank you!'),
								 t('Regards,'),
								 $sitename)
		);		
		
		$this->requireAsset('javascript-inline', 'formidable/inline/dashboard/forms/top');
		$this->requireAsset('javascript', 'formidable/dashboard/common');
		$this->requireAsset('javascript', 'formidable/dashboard/forms');
		$this->requireAsset('css', 'formidable/dashboard');
	}
				
	public function add() {	
		$f = new Form();		
		
		$f->label = t('My Formidable Form');
		$f->setAttribute('submission_redirect', $this->submission_redirect);
		$f->setAttribute('submission_redirect_content', t('Thank you!'));					
		
		$this->set('f', $f);
		$this->set('create_form', true);
	}
	
	public function edit($id, $form_new = false) {			
		if ($form_new) $this->set('message', t('Form saved successfully'));
								
		$f = Form::getByID($id);		
		if (is_object($f)) {					
			$this->set('f', $f);		
			$this->set('create_form', true);
		}
		else {
			$this->message('notfound');
			$this->view();
		}
	}
		
	public function save() {	
		
		$date_time = new DateTime();

		$val = new Validation();	
		$val->setData($this->post());
		
		$val->addRequired('label', t('Field "%s" is invalid', t('From name')));						
		if (intval($this->post('css')) == 1) $val->addRequired('css_value', t('Field "%s" is invalid', t('CSS value')));		
		$val->addRequired('submission_redirect', t('Field "%s" is invalid', t('Action after submission')));		
		if (intval($this->post('submission_redirect')) == 1) $val->addRequired('submission_redirect_page', t('Field "%s" is invalid', t('Page (submission)')));
		else $val->addRequired('submission_redirect_content', t('Field "%s" is invalid', t('Message (submission)')));		
		if (intval($this->post('limits')) == 1) { 
			$val->addInteger('limits_value', t('Field "%s" is invalid number', t('Limit (value)')));
			$val->addRequired('limits_type', t('Field "%s" is invalid', t('Limit (type)')));	
			if (intval($this->post('limits_redirect')) == 1) $val->addRequired('limits_redirect_page', t('Field "%s" is invalid', t('Page (limits)')));
			else $val->addRequired('limits_redirect_content', t('Field "%s" is invalid', t('Message (limits)')));
		}
		if (intval($this->post('schedule')) == 1) { 
			if ($this->post('schedule_start_activate')) $val->addRequired('schedule_start_dt', t('Field "%s" is invalid date', t('Start date (schedule)')));
			if ($this->post('schedule_end_activate')) $val->addRequired('schedule_end_dt', t('Field "%s" is invalid date', t('End date (schedule)')));
			if (intval($this->post('schedule_redirect')) == 1) $val->addRequired('schedule_redirect_page', t('Field "%s" is invalid', t('Page (schedule)')));
			else $val->addRequired('schedule_redirect_content', t('Field "%s" is invalid', t('Message (schedule)')));
		}

		if ($val->test()) {	
			$v = array(
				'formID' => $this->post('formID'),
				'label' => h($this->post('label')), 
				'submission_redirect' => intval($this->post('submission_redirect')), 
				'submission_redirect_page' => intval($this->post('submission_redirect'))==1?intval($this->post('submission_redirect_page')):0, 
				'submission_redirect_content' => $this->post('submission_redirect_content'),

				'limits' => intval($this->post('limits')), 
				'limits_value' => intval($this->post('limits'))==1?intval($this->post('limits_value')):0,
				'limits_type' => intval($this->post('limits'))==1?$this->post('limits_type'):'',
				'limits_redirect' => intval($this->post('limits_redirect')), 
				'limits_redirect_page' => intval($this->post('limits_redirect'))==1?intval($this->post('limits_redirect_page')):0, 
				'limits_redirect_content' => $this->post('limits_redirect_content'),
				
				'schedule' => intval($this->post('schedule')), 
				'schedule_start' => intval($this->post('schedule_start_activate'))==1?$date_time->translate('schedule_start'):date("Y-m-d H:i:s"),
				'schedule_end' => intval($this->post('schedule_end_activate'))==1?$date_time->translate('schedule_end'):date("Y-m-d H:i:s"),
				'schedule_redirect' => intval($this->post('schedule_redirect')), 
				'schedule_redirect_page' => intval($this->post('schedule_redirect'))==1?intval($this->post('schedule_redirect_page')):0, 
				'schedule_redirect_content' => $this->post('schedule_redirect_content'),

				'css' => intval($this->post('css')), 
				'css_value' => intval($this->post('css'))==1?h($this->post('css_value')):''
			);
			
			$f = new Form();
			if ($this->post('formID')) $f->load($this->post('formID'));						
			$f->save($v);

			if (intval($this->post('formID')) == 0)	{	
				// Add default mailing to Formidable
				$default_mailing = $this->default_mailing;
				$default_mailing['formID'] = $f->getFormID();
				
				$fm = new Mailing();								
				$fm->save($default_mailing);
								
				$this->redirect('/dashboard/formidable/forms/elements/'.$f->getFormID().'/true/');
			}
			$this->redirect('/dashboard/formidable/forms/edit/'.$f->getFormID().'/true/');
		}
					
		$this->set('errors', $val->getError()->getList());
		$this->set('create_form', true);

		if ($this->post('formID')) {					
			$f = Form::getByID($this->post('formID'));
			if (is_object($f)) {					
				$this->set('f', $f);
			}
		}
	}
	
	public function message($mode = 'deleted') {
		switch($mode) {
			case 'notfound': $this->set('errors', t('Form can\'t be found!')); break;
			case 'error': $this->set('errors', t('Oops, something went wrong!')); break;
			case 'saved': $this->set('message', t('Form saved successfully')); break;
			default: $this->set('message', t('Form deleted successfully')); break;
		}
		$this->view();
	}
}