<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use \Concrete\Core\Search\Column\Set as Set;
use \Concrete\Core\Search\Column\Column;
use Localization;
use User;
use UserInfo;
use Page;
use Core;

class ColumnSet extends Set
{
    public $counter = 5; // Show max columns on default 

    public static function getFormID() {
        $session = Core::make('app')->make('session');
        $formID = $session->get('formidableFormID');        
        return $formID;
    }

    public static function getElementsToSkip() {
        return array('buttons', 'captcha');
    }

    public function clearColumns() {
        $this->columns = array();
    }

    public function callbackSubmitted($row) {       
        if (!empty($row)) return Core::make('helper/date')->formatDateTime(strtotime($row->a_submitted));
        return '';  
    }

    public function callbackLocale($row) { 
        if (!empty($row)) return Localization::getLanguageDescription($row->a_locale?$row->a_locale:'en_US');
        return t('Unknown');    
    }

    public function callbackPage($row) {     
        $p = Page::getByID($row->a_collectionID);
        if (is_object($p) && !empty($p->getCollectionID())) return t('%s (Page ID: %s)', $p->getCollectionName(), $p->getCollectionID());
        return t('Unknown or deleted page');
    }

    public function callbackUser($row) {     
        $u = UserInfo::getByID($row->a_userID);
        if (is_object($u) && !empty($u->getUserID())) return t('%s (User ID: %s)', $u->getUserName(), $u->getUserID());
        return t('Unknown or deleted user');
    }
    
    public function loadColumns($export = false) {        
        $count = $this->counter;
        
        $this->clearColumns();
        
        $callback = 'getDisplayValue';
        if ($export) $callback = 'getDisplayValueExport';
        
        $formID = self::getFormID();
        $f = Form::getByID($formID);  
        if (!is_object($f)) return false;

        $elements = $f->getElements();  
        if (count($elements)) {
            foreach ($elements as $element) {
                if ($element->isLayout() || in_array($element->getElementType(), self::getElementsToSkip()) || $count <= 0) continue;
                $this->addColumn(new Column('element_'.$element->getElementID(), $element->getLabel(), $callback.$element->getElementID()));    
                $count--;
            }
        } 
               
        $this->addColumn(new Column('a_submitted', t('Submitted'), array('\Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\ColumnSet', 'callbackSubmitted'))); 
        $submitted = $this->getColumnByKey('a_submitted');
        $this->setDefaultSortColumn($submitted, 'desc');
    }

    public function setCurrent($columnSet) {
        if (empty($columnSet)) return false;
        if (!($columnSet instanceof ColumnSet)) return false;
        $this->columnSet = $columnSet;
    }

    public function getCurrent($dashboard = true) {
        //if ($this->columnSet instanceof ColumnSet) return $this->columnSet;
        
        $formID = self::getFormID();
        $fldc = '';
        if ($dashboard) {
            $u = new User();            
            $fldc = $u->config('FORMIDABLE_LIST_DEFAULT_COLUMNS_'.$formID);
            if ($fldc != '') $fldc = unserialize($fldc);
        }

        // Check to see if elements are still valid
        $use_default = true;      
        if ($fldc instanceof ColumnSet) {   
            $f = Form::getByID(self::getFormID()); 
            if (is_object($f)) {
                $use_default = false;
                $elements = $f->getElements();

                foreach ($fldc->getColumns() as $col) {
                    if (strpos($col->getColumnKey(), 'a_') !== false) continue; 
                    // See if all still exist;
                    $not_found = true;
                    foreach ($elements as $element) {
                        if (is_object($element)) {
                            if ($col->getColumnKey() == 'element_'.$element->getElementID()) {
                                $not_found = false;
                                break;
                            }
                        }
                    }
                    if ($not_found) {
                        $use_default = true;
                        break;
                    }
                }               
            }
        } 

        if ($use_default) {
            $fldc = new DefaultSet();
            $u->saveConfig('FORMIDABLE_LIST_DEFAULT_COLUMNS_'.$formID, '');
        }

        return $fldc;
    }   
}
