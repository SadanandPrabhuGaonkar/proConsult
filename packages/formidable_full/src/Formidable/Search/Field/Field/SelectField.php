<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field;

use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\Field\FieldInterface;
use Concrete\Core\Search\ItemList\ItemList;
use Core;

class SelectField extends AbstractField
{
    protected $key = 'keywords';
    protected $name = 'Keywords';
    protected $method = 'filterByKeyword';
    protected $comparison = '=';
    protected $options = array();
    protected $other = false;

    public function __construct($key = null, $name = null, $options = null, $other = false, $method = null, $comparison = null, $value = null)
    {
        if ($key) $this->key = $key;
        if ($name) $this->name = $name;
        if ($method) $this->method = $method; 
        if ($options) $this->options = $options;   
        if ($other) $this->other = $other;      
        if ($comparison) $this->comparison = $comparison;
        if ($value) $this->data[$this->key] = $value;

        $this->requestVariables = [
            $this->key,
            $this->key.'_other'
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
        if ($this->data[$this->key] == 'option_other') $value = $this->data[$this->key.'_other'];
        if (strtolower($this->comparison) == 'like') $value = '%'.$value.'%';
        if (in_array($this->method, array('filterByElementHandle', 'filterByElementID'))) $list->{$this->method}($this->key, $value, $this->comparison);
        else $list->{$this->method}($value);
    }

    public function renderSearchField()
    {
        if (count($this->options)) {     
            $form = Core::make('helper/form');    
            $html  = '<div class="select">';
            $html .= $form->select($this->key, $this->options, $this->data[$this->key]);
            if ($this->other) {
                $html .= '<div class="other">';
                $html .= $form->text($this->key.'_other', $this->data[$this->key.'_other']);
                $html .= '</div>';
                $html .= '<script>$(function() { $(\'select[id="'.$this->key.'"]\').on(\'change\', function() { var other = $(this).next(\'div.other\'); if ($(this).val() == \'option_other\') other.show(); else other.hide().val(\'\'); }).trigger(\'change\'); });</script>';
            }
            $html .= '</div>';
            return $html;
        }
        return '';
    }
}
