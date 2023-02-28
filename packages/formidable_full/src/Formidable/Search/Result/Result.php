<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search\Result;

use \Concrete\Core\Application\UserInterface\ContextMenu\BulkMenu;
use \Concrete\Core\Application\UserInterface\ContextMenu\Item\LinkItem;
use \Concrete\Core\Application\UserInterface\ContextMenu\Item\DividerItem;
use \Concrete\Core\Application\UserInterface\ContextMenu\Menu;
use \Concrete\Core\Search\Result\Result as SearchResult;
use URL;
use Core;

class Result extends SearchResult
{
    public function getJSONObject()
    {
        $r = parent::getJSONObject();       
        return $r;
    }

    public function getSearchResultBulkMenus()
    {
        $token = Core::make('token');
        $result_token = $token->generate('formidable_result');

        $group = new BulkMenu();
        $group->setPropertyName('treeNodeTypeHandle');
        $group->setPropertyValue('formidable');
        $menu = new Menu();

        $menu->addItem(new LinkItem('#', t('Resend mail'), [
            'data-bulk-action-type' => 'dialog',
            'data-bulk-action-title' => t('Resend mail'),
            'data-bulk-action-url' => URL::to('/formidable/dialog/dashboard/results/resend').'?formID='.$result->formID.'&ccm_token='.$result_token,
            'data-bulk-action-dialog-width' => '520',
            'data-bulk-action-dialog-height' => '400',
        ]));

        $menu->addItem(new LinkItem('#', t('Export'), [
            'data-bulk-action-type' => 'link',
            'data-bulk-action-url' => URL::to('/formidable/dialog/dashboard/results/csv').'?formID='.$result->formID.'&ccm_token='.$result_token,
        ]));

        $menu->addItem(new DividerItem()); 

        $menu->addItem(new LinkItem('#', t('Delete'), [
            'data-bulk-action-type' => 'dialog',
            'data-bulk-action-title' => t('Delete'),
            'data-bulk-action-url' => URL::to('/formidable/dialog/dashboard/results/delete').'?formID='.$result->formID.'&ccm_token='.$result_token,
            'data-bulk-action-dialog-width' => '520',
            'data-bulk-action-dialog-height' => '400',
        ]));

        $group->setMenu($menu);
        return $group;
    }

    public function getItemDetails($item) {
        $node = new Item($this, $this->listColumns, $item);
        return $node;
    }

    public function getColumnDetails($column) {
        $node = new Column($this, $column);
        return $node;
    }
}
