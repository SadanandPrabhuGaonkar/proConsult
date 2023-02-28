<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field;

use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\Field\FieldInterface;
use Concrete\Core\Search\ItemList\ItemList;
use Core;

class DefaultField extends AbstractField
{
    protected $key = 'keywords';
    protected $name = 'Keywords';
    protected $method = 'filterByKeyword';
    protected $comparison = '=';

    public function __construct($key = null, $name = null, $method = null, $comparison = null, $value = null)
    {
        if ($key) $this->key = $key;
        if ($name) $this->name = $name;
        if ($method) $this->method = $method;        
        if ($comparison) $this->comparison = $comparison;
        if ($value) $this->data[$this->key] = $value;

        $this->requestVariables = [
            $this->key
        ];
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getDisplayName()
    {
        return $this->name;
    }

    public function filterList(ItemList $list)
    {
        $value = $this->data[$this->key];
        if (strtolower($this->comparison) == 'like') $value = '%'.$value.'%';
        if (in_array($this->method, array('filterByElementHandle', 'filterByElementID'))) $list->{$this->method}($this->key, $value, $this->comparison);
        else $list->{$this->method}($value);
    }

    public function renderSearchField()
    {
        $form = Core::make('helper/form');
        return $form->text($this->key, $this->data[$this->key]);
    }


}
