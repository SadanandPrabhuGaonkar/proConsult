<?php

namespace Application\Concrete\CustomModels;

class MultiSelectSearchHelper
{

    public static function buildMultiQuery($attrHandle, $array)
    {
        if (!$array) {
            return null;
        }
        if (!is_array($array)) {
            return null;
        }

        $attrHandle = "ak_" . $attrHandle;
        $query      = "(";

        foreach ($array as $index => $search) {
            $search        = self::getSearchTerm($search);
            $attrHandleTmp = $index == 0 ? $attrHandle : ' OR ' . $attrHandle;
            $query         .= $attrHandleTmp . " LIKE '{$search}'";
        }

        $query .= ")";

        return $query;
    }

    public static function getSearchTerm($search)
    {
        return '%' . preg_replace('!\s + !', '%', trim($search)) . '%';
    }
}