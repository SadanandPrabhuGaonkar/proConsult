<?php namespace Application\Block\MoreInfoBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use Concrete\Core\Block\BlockController;
use Core;
use File;
use Page;
use Permissions;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btTable = 'btMoreInfoBlock';
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
        return t("More Info Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->desc_1;
        return implode(" ", $content);
    }

    public function view()
    {
        $btn_URL = null;
		$btn_Object = null;
		$btn_Title = trim($this->btn_Title);
		if (trim($this->btn) != '') {
			switch ($this->btn) {
				case 'page':
					if ($this->btn_Page > 0 && ($btn_Page_c = Page::getByID($this->btn_Page)) && !$btn_Page_c->error && !$btn_Page_c->isInTrash()) {
						$btn_Object = $btn_Page_c;
						$btn_URL = $btn_Page_c->getCollectionLink();
						if ($btn_Title == '') {
							$btn_Title = $btn_Page_c->getCollectionName();
						}
					}
					break;
				case 'file':
					$btn_File_id = (int)$this->btn_File;
					if ($btn_File_id > 0 && ($btn_File_object = File::getByID($btn_File_id)) && is_object($btn_File_object)) {
						$fp = new Permissions($btn_File_object);
						if ($fp->canViewFile()) {
							$btn_Object = $btn_File_object;
							$btn_URL = $btn_File_object->getRelativePath();
							if ($btn_Title == '') {
								$btn_Title = $btn_File_object->getTitle();
							}
						}
					}
					break;
				case 'url':
					$btn_URL = $this->btn_URL;
					if ($btn_Title == '') {
						$btn_Title = $btn_URL;
					}
					break;
				case 'relative_url':
					$btn_URL = $this->btn_Relative_URL;
					if ($btn_Title == '') {
						$btn_Title = $this->btn_Relative_URL;
					}
					break;
				case 'image':
					if ($this->btn_Image && ($btn_Image_object = File::getByID($this->btn_Image)) && is_object($btn_Image_object)) {
						$btn_URL = $btn_Image_object->getURL();
						$btn_Object = $btn_Image_object;
						if ($btn_Title == '') {
							$btn_Title = $btn_Image_object->getTitle();
						}
					}
					break;
			}
		}
		$this->set("btn_URL", $btn_URL);
		$this->set("btn_Object", $btn_Object);
		$this->set("btn_Title", $btn_Title);
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    protected function addEdit()
    {
        $this->set("btn_Options", $this->getSmartLinkTypeOptions([
  'page',
  'file',
  'image',
  'url',
  'relative_url',
], true));
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        if (isset($args["btn"]) && trim($args["btn"]) != '') {
			switch ($args["btn"]) {
				case 'page':
					$args["btn_File"] = '0';
					$args["btn_URL"] = '';
					$args["btn_Relative_URL"] = '';
					$args["btn_Image"] = '0';
					break;
				case 'file':
					$args["btn_Page"] = '0';
					$args["btn_URL"] = '';
					$args["btn_Relative_URL"] = '';
					$args["btn_Image"] = '0';
					break;
				case 'url':
					$args["btn_Page"] = '0';
					$args["btn_Relative_URL"] = '';
					$args["btn_File"] = '0';
					$args["btn_Image"] = '0';
					break;
				case 'relative_url':
					$args["btn_Page"] = '0';
					$args["btn_URL"] = '';
					$args["btn_File"] = '0';
					$args["btn_Image"] = '0';
					break;
				case 'image':
					$args["btn_Page"] = '0';
					$args["btn_File"] = '0';
					$args["btn_URL"] = '';
					$args["btn_Relative_URL"] = '';
					break;
				default:
					$args["btn_Title"] = '';
					$args["btn_Page"] = '0';
					$args["btn_File"] = '0';
					$args["btn_URL"] = '';
					$args["btn_Relative_URL"] = '';
					$args["btn_Image"] = '0';
					break;	
			}
		}
		else {
			$args["btn_Title"] = '';
			$args["btn_Page"] = '0';
			$args["btn_File"] = '0';
			$args["btn_URL"] = '';
			$args["btn_Relative_URL"] = '';
			$args["btn_Image"] = '0';
		}
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("title", $this->btFieldsRequired) && (trim($args["title"]) == "")) {
            $e->add(t("The %s field is required.", t("Title")));
        }
        if (in_array("desc_1", $this->btFieldsRequired) && (trim($args["desc_1"]) == "")) {
            $e->add(t("The %s field is required.", t("Description")));
        }
        if ((in_array("btn", $this->btFieldsRequired) && (!isset($args["btn"]) || trim($args["btn"]) == "")) || (isset($args["btn"]) && trim($args["btn"]) != "" && !array_key_exists($args["btn"], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url'])))) {
			$e->add(t("The %s field has an invalid value.", t("Button")));
		} elseif (array_key_exists($args["btn"], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url']))) {
			switch ($args["btn"]) {
				case 'page':
					if (!isset($args["btn_Page"]) || trim($args["btn_Page"]) == "" || $args["btn_Page"] == "0" || (($page = Page::getByID($args["btn_Page"])) && $page->error !== false)) {
						$e->add(t("The %s field for '%s' is required.", t("Page"), t("Button")));
					}
					break;
				case 'file':
					if (!isset($args["btn_File"]) || trim($args["btn_File"]) == "" || !is_object(File::getByID($args["btn_File"]))) {
						$e->add(t("The %s field for '%s' is required.", t("File"), t("Button")));
					}
					break;
				case 'url':
					if (!isset($args["btn_URL"]) || trim($args["btn_URL"]) == "" || !filter_var($args["btn_URL"], FILTER_VALIDATE_URL)) {
						$e->add(t("The %s field for '%s' does not have a valid URL.", t("URL"), t("Button")));
					}
					break;
				case 'relative_url':
					if (!isset($args["btn_Relative_URL"]) || trim($args["btn_Relative_URL"]) == "") {
						$e->add(t("The %s field for '%s' is required.", t("Relative URL"), t("Button")));
					}
					break;
				case 'image':
					if (!isset($args["btn_Image"]) || trim($args["btn_Image"]) == "" || !is_object(File::getByID($args["btn_Image"]))) {
						$e->add(t("The %s field for '%s' is required.", t("Image"), t("Button")));
					}
					break;	
			}
		}
        return $e;
    }

    public function composer()
    {
        $al = AssetList::getInstance();
        $al->register('javascript', 'auto-js-' . $this->btHandle, 'blocks/' . $this->btHandle . '/auto.js', [], $this->pkg);
        $this->requireAsset('javascript', 'auto-js-' . $this->btHandle);
        $this->edit();
    }

    protected function getSmartLinkTypeOptions($include = [], $checkNone = false)
	{
		$options = [
			''             => sprintf("-- %s--", t("None")),
			'page'         => t("Page"),
			'url'          => t("External URL"),
			'relative_url' => t("Relative URL"),
			'file'         => t("File"),
			'image'        => t("Image")
		];
		if ($checkNone) {
            $include = array_merge([''], $include);
        }
		$return = [];
		foreach($include as $v){
		    if(isset($options[$v])){
		        $return[$v] = $options[$v];
		    }
		}
		return $return;
	}
}