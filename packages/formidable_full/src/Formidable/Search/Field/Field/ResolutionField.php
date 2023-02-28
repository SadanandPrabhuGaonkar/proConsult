<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field;

use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Core;

class ResolutionField extends AbstractField
{

    protected $requestVariables = [
        'resolution_width',
        'resolution_height',
    ];

    public function getKey()
    {
        return 'resolution';
    }

    public function getDisplayName()
    {
        return t('Resolution');
    }

    public function renderSearchField()
    {
        $form = Core::make('helper/form');
        return $form->number('resolution_width', $this->data['resolution_width'], array('class' => 'small')) . ' X ' . $form->number('resolution_height', $this->data['resolution_height'], array('class' => 'small')).' (in pixels)';
    }

    public function filterList(ItemList $list)
    {
        $from = intval($this->data['resolution_width']);  
        $to = intval($this->data['resolution_height']);                      
        
        if ($from == 0) $from = '.*';        
        if ($to == 0) $to = '.*';

        $list->filter(false, 'fas.resolution REGEXP "^'.$from.'x'.$to.'$"');  
    }
}
