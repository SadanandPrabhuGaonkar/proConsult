<?php namespace Application\Block\NewTextBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btTable = 'btNewTextBlock';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $pkg = false;
    
    public function getBlockTypeName()
    {
        return t("New Simple Text Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->Desc_1;
        return implode(" ", $content);
    }

    public function view()
    {
        $this->set('Desc_1', LinkAbstractor::translateFrom($this->Desc_1));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('Desc_1', LinkAbstractor::translateFromEditMode($this->Desc_1));
    }

    protected function addEdit()
    {
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $args['Desc_1'] = LinkAbstractor::translateTo($args['Desc_1']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("title", $this->btFieldsRequired) && (trim($args["title"]) == "")) {
            $e->add(t("The %s field is required.", t("Title")));
        }
        if (in_array("Desc_1", $this->btFieldsRequired) && (trim($args["Desc_1"]) == "")) {
            $e->add(t("The %s field is required.", t("Desc")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}