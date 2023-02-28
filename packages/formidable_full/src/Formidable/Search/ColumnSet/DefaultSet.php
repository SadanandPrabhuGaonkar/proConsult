<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\ColumnSet;

class DefaultSet extends ColumnSet
{
    public function __construct($export = false) {
        $this->loadColumns($export);
    }
}
