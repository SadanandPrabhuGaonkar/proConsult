<?php 
namespace Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Package\FormidableFull\Src\Formidable\Mailing;
use \Concrete\Controller\Backend\UserInterface as BackendInterfaceController;
use Concrete\Core\Editor\Plugin;
use UserInfo;
use Config;
use Page;
use Permissions;
use Core;
use AssetList;

class Dialog extends BackendInterfaceController {

	protected $viewPath = '/dialogs/mailings/dialog';

	protected $form = '';

	protected function validateAction($tk = 'formidable_mailing') {
		$token = Core::make('token');
		if (!$token->validate($tk)) {
			return array(
				'type' => 'error',
				'message' => $token->getErrorMessage()
			);
		}
		return true;
	}

	protected function canAccess() {
		$c = Page::getByPath('/dashboard/formidable/');
		$cp = new Permissions($c);
		return $cp->canRead();
	}

	public function view() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) {		
				$mailing = new Mailing();		
				if(intval($this->get('mailingID')) != 0) $mailing->load($this->get('mailingID'));	

				// Load default mailing if non is selected
				if (!$mailing->getMailingID()) {
					$ui = UserInfo::getByID(USER_SUPER_ID);
					$sitename = Config::get('concrete.site');					
					$defaults = array(
						'from_type' => 'other',
					    'from_name' => $sitename,
					    'from_email' => $ui->getUserEmail(),
					    'reply_email' => $ui->getUserEmail(),
					    'send_custom' => 1,
					    'send_custom_value' => $ui->getUserEmail(),
					    'subject' => t('%s submission', $f->getLabel()),
					    'message' => sprintf('<p>%s<br />%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>',
											 t('You successfully sent our %s on our Concrete5 website.', $sitename),
											 t('The following information was sent to us:'),
											 '{%all_elements%}',
											 t('Thank you!'),
											 t('Regards,'),
											 $sitename));	

					$mailing->setFormID($f->getFormID());
					$mailing->setAttributes($defaults);					
				}
				// Load send to elements
				$els = array();						
				$elements = $f->getElements('send_to');
				$elem_count = is_array($elements) ? count($elements) : 0 ;
				if ($elem_count) {
					foreach ($elements as $e) {
						$els[$e->getElementID()] = t('%s (%s)', $e->getLabel(), $e->getElementText());
					}
					$mailing->setAttribute('send_to', $els);
				}	
				$mailing->setAttribute('from', $els + array('other' => t('Send from custom sender:')));
				$mailing->setAttribute('reply', array('from' => t('Use the "From"-details')) + $els + array('other' => t('Use custom "Reply to"-details:')));	

				$this->set('uploadElements', $f->getElements('upload'));

				$this->set('mailing', $mailing);

				$editor = Core::make('editor');
				
				// Set editor
				$al = AssetList::getInstance();							
				$al->registerGroup('formidable/editor', array(array('javascript', 'formidable/editor')));

			    $plugin = new Plugin();
			    $plugin->setKey('formidable');
			    $plugin->setName('Formidable');
			    $plugin->requireAsset('formidable/editor');
				
				$editor->getPluginManager()->register($plugin);		    
				$editor->getPluginManager()->select('formidable');

				$this->set('editor', $editor);
			}
		}
		$this->set('errors', $r);
	}

	public function delete() {
		$r = $this->validateAction();
		if ($r === true) {
			$f = Form::getByID($this->get('formID'));
			if (is_object($f)) {				
				$mailing = Mailing::getByID($this->get('mailingID'));	
				if (is_object($mailing)) $this->set('mailing', $mailing);
			}
		}
		$this->set('errors', $r);
	}
}