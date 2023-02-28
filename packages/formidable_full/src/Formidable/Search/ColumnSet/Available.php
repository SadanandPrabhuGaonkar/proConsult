<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet;

use \Concrete\Package\FormidableFull\Src\Formidable\Form;
use Concrete\Core\Search\Column\Column;

class Available extends DefaultSet
{
    public function __construct($export = false) {
		$this->loadColumns($export);
	}
	
	public function loadColumns($export = false) {		
		
		$this->clearColumns();
				
		$callback = 'getDisplayValue';
        if ($export) $callback = 'getDisplayValueExport';
        
        $formID = self::getFormID();
        $f = Form::getByID($formID);	
		if (!is_object($f)) return false;

		$elements = $f->getElements();	
		if (count($elements)) {
			foreach ($elements as $element) {
				if ($element->isLayout() || in_array($element->getElementType(), ColumnSet::getElementsToSkip())) continue;
				$this->addColumn(new Column('element_'.$element->getElementID(), $element->getLabel(), $callback.$element->getElementID()));   
			}
		}
		$this->addColumn(new Column('a_ip', t('IP'), 'a_ip'));
		$this->addColumn(new Column('a_collectionID', t('Page'), array('Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\ColumnSet', 'callbackPage')));
		$this->addColumn(new Column('a_userID', t('User'), array('Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\ColumnSet', 'callbackUser')));
		$this->addColumn(new Column('a_answerSetID', t('Answerset ID'), 'a_answerSetID'));
		$this->addColumn(new Column('a_submitted', t('Submitted'), array('Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\ColumnSet', 'callbackSubmitted')));
		$this->addColumn(new Column('a_browser', t('Browser'), 'a_browser'));
		$this->addColumn(new Column('a_platform', t('Platform'), 'a_platform'));
		$this->addColumn(new Column('a_resolution', t('Resolution'), 'a_resolution'));	
		$this->addColumn(new Column('a_locale', t('Locale'), array('Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet\ColumnSet', 'callbackLocale')));	
		
		$submitted = $this->getColumnByKey('a_submitted');
        $this->setDefaultSortColumn($submitted, 'desc');

	}
}
