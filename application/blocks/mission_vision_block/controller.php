<?php namespace Application\Block\MissionVisionBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btTable = 'btMissionVisionBlock';
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
        return t("Mission Vision Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->mission;
        $content[] = $this->vision;
        $content[] = $this->objective;
        return implode(" ", $content);
    }

    public function view()
    {
        $this->set('mission', LinkAbstractor::translateFrom($this->mission));
        $this->set('vision', LinkAbstractor::translateFrom($this->vision));
        $this->set('objective', LinkAbstractor::translateFrom($this->objective));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('mission', LinkAbstractor::translateFromEditMode($this->mission));
        
        $this->set('vision', LinkAbstractor::translateFromEditMode($this->vision));
        
        $this->set('objective', LinkAbstractor::translateFromEditMode($this->objective));
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
        $args['mission'] = LinkAbstractor::translateTo($args['mission']);
        $args['vision'] = LinkAbstractor::translateTo($args['vision']);
        $args['objective'] = LinkAbstractor::translateTo($args['objective']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("mission", $this->btFieldsRequired) && (trim($args["mission"]) == "")) {
            $e->add(t("The %s field is required.", t("Mission")));
        }
        if (in_array("vision", $this->btFieldsRequired) && (trim($args["vision"]) == "")) {
            $e->add(t("The %s field is required.", t("Vision")));
        }
        if (in_array("objective", $this->btFieldsRequired) && (trim($args["objective"]) == "")) {
            $e->add(t("The %s field is required.", t("Objective")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}