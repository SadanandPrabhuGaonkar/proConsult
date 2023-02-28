<?php

namespace Application\Concrete\CustomModels;

class SelectOptionsHelper
{

    public static function getSelectOptions($attrHandle, $array)
    {
        if (!$array) {
            return null;
        }
        if (!is_array($array)) {
            return null;
        }

        $selectOptions = [];

        /** @var \Concrete\Core\Entity\Attribute\Key\PageKey $pageKey to getController() */
        $pageKey = CollectionKey::getByHandle($attrHandle);
        $pageKey->getController();

        /** To get the attribute select value */
        /** @var \Concrete\Core\Entity\Attribute\Value\Value\SelectValueOption $option */
        /** @var  $category */
        foreach ($pageKey->getController()->getOptions() as $option) {
            $selectOptions[$option->getSelectAttributeOptionValue()] = $option->getSelectAttributeOptionValue();
        }

        return $selectOptions;
    }
}