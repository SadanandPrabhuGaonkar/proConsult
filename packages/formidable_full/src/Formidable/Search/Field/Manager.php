<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field;

use Concrete\Package\FormidableFull\Src\Formidable\Form; 

use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\DefaultField;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\SelectField;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\PageField;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\UserField;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\DateSubmittedField;
use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\ResolutionField;
//use Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field\LocaleField;

use Concrete\Core\Search\Field\Manager as FieldManager;
use Doctrine\ORM\EntityManagerInterface;
use Core;

class Manager extends FieldManager
{
    protected static $formID = false;

    public function getFormID() {
        if (self::$formID) return self::$formID;
        $session = Core::make('app')->make('session');
        $formID = $session->get('formidableFormID');        
        return $formID;
    }

    public function __construct()
    {        
        $this->addGroup(t('Core Properties'), [
            new DefaultField('keywords', t('Keywords'), 'filterByKeyword'),
            new PageField(),
            new UserField(),
            new DateSubmittedField(), 
            new DefaultField('ip', t('IP'), 'filterByIP'),
            new DefaultField('browser', t('Browser'), 'filterByBrowser'),
            new DefaultField('platform', t('Platform'), 'filterByPlatform'),
            new ResolutionField(), 
            //new LocaleField('locale', t('Locale'), 'filterByLocale'),          
        ]);

        $f = Form::getByID($this->getFormID());  
        if (!is_object($f)) return false;
        
        $others = [];
        $elements = $f->getElements();
        if (count($elements)) {
            foreach ($elements as $element) {
                if ($element->isLayout()) continue;
                if (in_array($element->getElementType(), array('select', 'radio', 'checkbox'))) {
                    $opts = array();
                    $options = $element->getPropertyValue('options');   
                    if (!empty($options) && count($options)) {
                        foreach ($options as $o) {                        
                            if (empty($o['value'])) $o['value'] = $o['name'];
                            $opts[$o['value']] = $o['name'];
                        }
                        $other = $element->getPropertyValue('option_other');
                        if ($other) $opts['option_other'] = $element->getPropertyValue('option_other_value'); 
                    }
                    $others[] = new SelectField($element->getHandle(), $element->getLabel(), $opts, $other, 'filterByElementHandle', 'LIKE');
                } 
                else $others[] = new DefaultField($element->getHandle(), $element->getLabel(), 'filterByElementHandle', 'LIKE');
            }
        }

        $this->addGroup(t('Other Elements'), $others);
        
    }


}
