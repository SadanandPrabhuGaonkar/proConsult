<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Field\Field;

use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Core;

class DateSubmittedField extends AbstractField
{

    protected $requestVariables = [
        'date_submitted_from_dt',
        'date_submitted_from_h',
        'date_submitted_from_m',
        'date_submitted_from_a',
        'date_submitted_to_dt',
        'date_submitted_to_h',
        'date_submitted_to_m',
        'date_submitted_to_a',
    ];

    public function getKey()
    {
        return 'date_submitted';
    }

    public function getDisplayName()
    {
        return t('Date Submitted');
    }

    public function renderSearchField()
    {
        $wdt = Core::make('helper/form/date_time');
        return $wdt->datetime('date_submitted_from', $wdt->translate('date_submitted_from', $this->data)) . t('to') . $wdt->datetime('date_submitted_to', $wdt->translate('date_submitted_to', $this->data));

    }

    public function filterList(ItemList $list)
    {
        $wdt = Core::make('helper/form/date_time');
        /* @var $wdt \Concrete\Core\Form\Service\Widget\DateTime */
        $dateFrom = $wdt->translate('date_submitted_from', $this->data);
        if ($dateFrom) {
            $list->filterByDateSubmitted($dateFrom, '>=');
        }
        $dateTo = $wdt->translate('date_submitted_to', $this->data);
        if ($dateTo) {
            if (preg_match('/^(.+\\d+:\\d+):00$/', $dateTo, $m)) {
                $dateTo = $m[1] . ':59';
            }
            $list->filterByDateSubmitted($dateTo, '<=');
        }
    }



}
