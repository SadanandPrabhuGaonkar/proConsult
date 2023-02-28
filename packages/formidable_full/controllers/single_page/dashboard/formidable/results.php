<?php
namespace Concrete\Package\FormidableFull\Controller\SinglePage\Dashboard\Formidable;

use \Concrete\Package\FormidableFull\Controller\Element\Search\Header;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\FormidableFull\Src\Formidable;
use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use URL;
use Core;

class Results extends DashboardPageController
{
    public $helpers = array('form');

    private $formID = 0;

    public function __construct($c) {	
		parent::__construct($c);

		$session = Core::make('app')->make('session');
		$this->formID = $session->get('formidableFormID');
		if (intval($this->get('formID')) != 0) $this->formID = intval($this->get('formID'));

		$f = Form::getByID($this->formID);
		if (!is_object($f)) $f = Formidable::getFirstForm();		
		if (!is_object($f)) return false;

		$_REQUEST['formID'] = $f->getFormID();
		$session->set('formidableFormID', $f->getFormID());
    }

    public function view()
    {
        $header = new Header();
        $this->set('headerMenu', $header);

        $this->requireAsset('javascript', 'formidable/dashboard/results');
        $this->requireAsset('css', 'formidable/dashboard');
        
        if ($this->formID == 0) return false;

        $search = $this->app->make('\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Search');
        $result = $search->getCurrentSearchObject();

        $script = "";
        if (is_object($result)) {            
            $list = $result->getItemListObject();
            if ($list->getTotalResults() > 0) {
                $this->set('result', $result);
                $result = json_encode($result->getJSONObject());
                $script .=  "function FormidableResultWaitWhileLoading() { 
                                if ($.isFunction(ConcreteAjaxSearch) && $.isFunction(FormidableResultLoader)) {
                                    FormidableResultLoader();
                                    $('#ccm-dashboard-content').concreteFormidableResult({result: " . $result . "});                                 
                                }
                                else setTimeout(function() { FormidableResultWaitWhileLoading(); }, 50);
                            };
                            FormidableResultWaitWhileLoading();
                            ";
            }
            $script .= "$('.ccm-header-search-form-select select').on('change', function() { window.location.href = '".URL::to('/dashboard/formidable/results/')."?formID='+$(this).val(); });";
        }
        if (!empty($script)) $this->addFooterItem("<script type=\"text/javascript\">$(function() {".$script."});</script>");

    }
}