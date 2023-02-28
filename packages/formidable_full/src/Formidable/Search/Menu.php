<?php
namespace Concrete\Package\FormidableFull\Src\Formidable\Search;

use \Concrete\Core\Application\UserInterface\ContextMenu\Item\DialogLinkItem;
use \Concrete\Core\Application\UserInterface\ContextMenu\Item\DividerItem;
use \Concrete\Core\Application\UserInterface\ContextMenu\Item\LinkItem;
use URL;
use Core;

class Menu extends \Concrete\Core\Application\UserInterface\ContextMenu\Menu
{

    protected $menuAttributes = ['class' => 'ccm-popover-result-menu'];
    protected $minItemThreshold = 0; // because we already have clear and the divider, we just hide them with JS

    public function __construct($result)
    {
        parent::__construct();
        $this->setAttribute('data-search-menu', $result->answerSetID);

        $token = Core::make('token');
        $preview_token = $token->generate('formidable_preview');
        $result_token = $token->generate('formidable_result');
        
        $this->addItem(new DialogLinkItem(
            URL::to('formidable/dialog/dashboard/forms/preview/result').'?formID='.$result->formID.'&answerSetID='.$result->answerSetID.'&ccm_token='.$preview_token,
            t('View'), t('View'), '750', '75%')
        );  

        $this->addItem(new DialogLinkItem(
            URL::to('formidable/dialog/dashboard/forms/preview/').'?formID='.$result->formID.'&answerSetID='.$result->answerSetID.'&ccm_token='.$preview_token,
            t('Edit'), t('Edit'), '750', '75%')
        );  

        $this->addItem(new DividerItem());                
        
        $this->addItem(new DialogLinkItem(
            URL::to('formidable/dialog/dashboard/results/resend').'?formID='.$result->formID.'&item[]='.$result->answerSetID.'&ccm_token='.$result_token,
            t('Resend mail'), t('Resend mail'), '520', '100')
        );  

        $this->addItem(new DividerItem()); 

        $this->addItem(new DialogLinkItem(
            URL::to('formidable/dialog/dashboard/results/delete').'?formID='.$result->formID.'&item[]='.$result->answerSetID.'&ccm_token='.$result_token, 
            t('Delete'), t('Delete'), '520', '100')
        );
    }
}

