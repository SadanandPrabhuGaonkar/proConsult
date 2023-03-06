<?php namespace Application\Block\HomepageBannerBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use Concrete\Core\Block\BlockController;
use Core;
use Database;
use File;
use Page;
use Permissions;

class Controller extends BlockController
{
    public $btFieldsRequired = ['slideimages' => []];
    protected $btExportFileColumns = ['img'];
    protected $btExportTables = ['btHomepageBannerBlock', 'btHomepageBannerBlockSlideimagesEntries'];
    protected $btTable = 'btHomepageBannerBlock';
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
        return t("Homepage Banner Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->tagline;
        $content[] = $this->desc_1;
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $btnone_URL = null;
		$btnone_Object = null;
		$btnone_Title = trim($this->btnone_Title);
		if (trim($this->btnone) != '') {
			switch ($this->btnone) {
				case 'page':
					if ($this->btnone_Page > 0 && ($btnone_Page_c = Page::getByID($this->btnone_Page)) && !$btnone_Page_c->error && !$btnone_Page_c->isInTrash()) {
						$btnone_Object = $btnone_Page_c;
						$btnone_URL = $btnone_Page_c->getCollectionLink();
						if ($btnone_Title == '') {
							$btnone_Title = $btnone_Page_c->getCollectionName();
						}
					}
					break;
				case 'file':
					$btnone_File_id = (int)$this->btnone_File;
					if ($btnone_File_id > 0 && ($btnone_File_object = File::getByID($btnone_File_id)) && is_object($btnone_File_object)) {
						$fp = new Permissions($btnone_File_object);
						if ($fp->canViewFile()) {
							$btnone_Object = $btnone_File_object;
							$btnone_URL = $btnone_File_object->getRelativePath();
							if ($btnone_Title == '') {
								$btnone_Title = $btnone_File_object->getTitle();
							}
						}
					}
					break;
				case 'url':
					$btnone_URL = $this->btnone_URL;
					if ($btnone_Title == '') {
						$btnone_Title = $btnone_URL;
					}
					break;
				case 'relative_url':
					$btnone_URL = $this->btnone_Relative_URL;
					if ($btnone_Title == '') {
						$btnone_Title = $this->btnone_Relative_URL;
					}
					break;
				case 'image':
					if ($this->btnone_Image && ($btnone_Image_object = File::getByID($this->btnone_Image)) && is_object($btnone_Image_object)) {
						$btnone_URL = $btnone_Image_object->getURL();
						$btnone_Object = $btnone_Image_object;
						if ($btnone_Title == '') {
							$btnone_Title = $btnone_Image_object->getTitle();
						}
					}
					break;
			}
		}
		$this->set("btnone_URL", $btnone_URL);
		$this->set("btnone_Object", $btnone_Object);
		$this->set("btnone_Title", $btnone_Title);
        $btntwo_URL = null;
		$btntwo_Object = null;
		$btntwo_Title = trim($this->btntwo_Title);
		if (trim($this->btntwo) != '') {
			switch ($this->btntwo) {
				case 'page':
					if ($this->btntwo_Page > 0 && ($btntwo_Page_c = Page::getByID($this->btntwo_Page)) && !$btntwo_Page_c->error && !$btntwo_Page_c->isInTrash()) {
						$btntwo_Object = $btntwo_Page_c;
						$btntwo_URL = $btntwo_Page_c->getCollectionLink();
						if ($btntwo_Title == '') {
							$btntwo_Title = $btntwo_Page_c->getCollectionName();
						}
					}
					break;
				case 'file':
					$btntwo_File_id = (int)$this->btntwo_File;
					if ($btntwo_File_id > 0 && ($btntwo_File_object = File::getByID($btntwo_File_id)) && is_object($btntwo_File_object)) {
						$fp = new Permissions($btntwo_File_object);
						if ($fp->canViewFile()) {
							$btntwo_Object = $btntwo_File_object;
							$btntwo_URL = $btntwo_File_object->getRelativePath();
							if ($btntwo_Title == '') {
								$btntwo_Title = $btntwo_File_object->getTitle();
							}
						}
					}
					break;
				case 'url':
					$btntwo_URL = $this->btntwo_URL;
					if ($btntwo_Title == '') {
						$btntwo_Title = $btntwo_URL;
					}
					break;
				case 'relative_url':
					$btntwo_URL = $this->btntwo_Relative_URL;
					if ($btntwo_Title == '') {
						$btntwo_Title = $this->btntwo_Relative_URL;
					}
					break;
				case 'image':
					if ($this->btntwo_Image && ($btntwo_Image_object = File::getByID($this->btntwo_Image)) && is_object($btntwo_Image_object)) {
						$btntwo_URL = $btntwo_Image_object->getURL();
						$btntwo_Object = $btntwo_Image_object;
						if ($btntwo_Title == '') {
							$btntwo_Title = $btntwo_Image_object->getTitle();
						}
					}
					break;
			}
		}
		$this->set("btntwo_URL", $btntwo_URL);
		$this->set("btntwo_Object", $btntwo_Object);
		$this->set("btntwo_Title", $btntwo_Title);
        $slideimages = [];
        $slideimages_items = $db->fetchAll('SELECT * FROM btHomepageBannerBlockSlideimagesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slideimages_items as $slideimages_item_k => &$slideimages_item_v) {
            if (isset($slideimages_item_v['img']) && trim($slideimages_item_v['img']) != "" && ($f = File::getByID($slideimages_item_v['img'])) && is_object($f)) {
                $slideimages_item_v['img'] = $f;
            } else {
                $slideimages_item_v['img'] = false;
            }
        }
        $this->set('slideimages_items', $slideimages_items);
        $this->set('slideimages', $slideimages);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btHomepageBannerBlockSlideimagesEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $slideimages_items = $db->fetchAll('SELECT * FROM btHomepageBannerBlockSlideimagesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slideimages_items as $slideimages_item) {
            unset($slideimages_item['id']);
            $slideimages_item['bID'] = $newBID;
            $db->insert('btHomepageBannerBlockSlideimagesEntries', $slideimages_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $slideimages = $this->get('slideimages');
        $this->set('slideimages_items', []);
        $this->set('slideimages', $slideimages);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $slideimages = $this->get('slideimages');
        $slideimages_items = $db->fetchAll('SELECT * FROM btHomepageBannerBlockSlideimagesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slideimages_items as &$slideimages_item) {
            if (!File::getByID($slideimages_item['img'])) {
                unset($slideimages_item['img']);
            }
        }
        $this->set('slideimages', $slideimages);
        $this->set('slideimages_items', $slideimages_items);
    }

    protected function addEdit()
    {
        $this->set("btnone_Options", $this->getSmartLinkTypeOptions([
  'page',
  'file',
  'image',
  'url',
  'relative_url',
], true));
        $this->set("btntwo_Options", $this->getSmartLinkTypeOptions([
  'page',
  'file',
  'image',
  'url',
  'relative_url',
], true));
        $slideimages = [];
        $this->set('slideimages', $slideimages);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/homepage_banner_block/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/homepage_banner_block/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/homepage_banner_block/js_form/handlebars-helpers.js', [], $this->pkg);
        $this->requireAsset('core/sitemap');
        $this->requireAsset('css', 'repeatable-ft.form');
        $this->requireAsset('javascript', 'handlebars');
        $this->requireAsset('javascript', 'handlebars-helpers');
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $db = Database::connection();
        if (isset($args["btnone"]) && trim($args["btnone"]) != '') {
			switch ($args["btnone"]) {
				case 'page':
					$args["btnone_File"] = '0';
					$args["btnone_URL"] = '';
					$args["btnone_Relative_URL"] = '';
					$args["btnone_Image"] = '0';
					break;
				case 'file':
					$args["btnone_Page"] = '0';
					$args["btnone_URL"] = '';
					$args["btnone_Relative_URL"] = '';
					$args["btnone_Image"] = '0';
					break;
				case 'url':
					$args["btnone_Page"] = '0';
					$args["btnone_Relative_URL"] = '';
					$args["btnone_File"] = '0';
					$args["btnone_Image"] = '0';
					break;
				case 'relative_url':
					$args["btnone_Page"] = '0';
					$args["btnone_URL"] = '';
					$args["btnone_File"] = '0';
					$args["btnone_Image"] = '0';
					break;
				case 'image':
					$args["btnone_Page"] = '0';
					$args["btnone_File"] = '0';
					$args["btnone_URL"] = '';
					$args["btnone_Relative_URL"] = '';
					break;
				default:
					$args["btnone_Title"] = '';
					$args["btnone_Page"] = '0';
					$args["btnone_File"] = '0';
					$args["btnone_URL"] = '';
					$args["btnone_Relative_URL"] = '';
					$args["btnone_Image"] = '0';
					break;	
			}
		}
		else {
			$args["btnone_Title"] = '';
			$args["btnone_Page"] = '0';
			$args["btnone_File"] = '0';
			$args["btnone_URL"] = '';
			$args["btnone_Relative_URL"] = '';
			$args["btnone_Image"] = '0';
		}
        if (isset($args["btntwo"]) && trim($args["btntwo"]) != '') {
			switch ($args["btntwo"]) {
				case 'page':
					$args["btntwo_File"] = '0';
					$args["btntwo_URL"] = '';
					$args["btntwo_Relative_URL"] = '';
					$args["btntwo_Image"] = '0';
					break;
				case 'file':
					$args["btntwo_Page"] = '0';
					$args["btntwo_URL"] = '';
					$args["btntwo_Relative_URL"] = '';
					$args["btntwo_Image"] = '0';
					break;
				case 'url':
					$args["btntwo_Page"] = '0';
					$args["btntwo_Relative_URL"] = '';
					$args["btntwo_File"] = '0';
					$args["btntwo_Image"] = '0';
					break;
				case 'relative_url':
					$args["btntwo_Page"] = '0';
					$args["btntwo_URL"] = '';
					$args["btntwo_File"] = '0';
					$args["btntwo_Image"] = '0';
					break;
				case 'image':
					$args["btntwo_Page"] = '0';
					$args["btntwo_File"] = '0';
					$args["btntwo_URL"] = '';
					$args["btntwo_Relative_URL"] = '';
					break;
				default:
					$args["btntwo_Title"] = '';
					$args["btntwo_Page"] = '0';
					$args["btntwo_File"] = '0';
					$args["btntwo_URL"] = '';
					$args["btntwo_Relative_URL"] = '';
					$args["btntwo_Image"] = '0';
					break;	
			}
		}
		else {
			$args["btntwo_Title"] = '';
			$args["btntwo_Page"] = '0';
			$args["btntwo_File"] = '0';
			$args["btntwo_URL"] = '';
			$args["btntwo_Relative_URL"] = '';
			$args["btntwo_Image"] = '0';
		}
        $rows = $db->fetchAll('SELECT * FROM btHomepageBannerBlockSlideimagesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $slideimages_items = isset($args['slideimages']) && is_array($args['slideimages']) ? $args['slideimages'] : [];
        $queries = [];
        if (!empty($slideimages_items)) {
            $i = 0;
            foreach ($slideimages_items as $slideimages_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($slideimages_item['img']) && trim($slideimages_item['img']) != '') {
                    $data['img'] = trim($slideimages_item['img']);
                } else {
                    $data['img'] = null;
                }
                if (isset($rows[$i])) {
                    $queries['update'][$rows[$i]['id']] = $data;
                    unset($rows[$i]);
                } else {
                    $data['bID'] = $this->bID;
                    $queries['insert'][] = $data;
                }
                $i++;
            }
        }
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $queries['delete'][] = $row['id'];
            }
        }
        if (!empty($queries)) {
            foreach ($queries as $type => $values) {
                if (!empty($values)) {
                    switch ($type) {
                        case 'update':
                            foreach ($values as $id => $data) {
                                $db->update('btHomepageBannerBlockSlideimagesEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btHomepageBannerBlockSlideimagesEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btHomepageBannerBlockSlideimagesEntries', ['id' => $value]);
                            }
                            break;
                    }
                }
            }
        }
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("title", $this->btFieldsRequired) && (trim($args["title"]) == "")) {
            $e->add(t("The %s field is required.", t("Title")));
        }
        if (in_array("tagline", $this->btFieldsRequired) && (trim($args["tagline"]) == "")) {
            $e->add(t("The %s field is required.", t("Tag line")));
        }
        if (in_array("desc_1", $this->btFieldsRequired) && (trim($args["desc_1"]) == "")) {
            $e->add(t("The %s field is required.", t("Description")));
        }
        if ((in_array("btnone", $this->btFieldsRequired) && (!isset($args["btnone"]) || trim($args["btnone"]) == "")) || (isset($args["btnone"]) && trim($args["btnone"]) != "" && !array_key_exists($args["btnone"], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url'])))) {
			$e->add(t("The %s field has an invalid value.", t("Button one")));
		} elseif (array_key_exists($args["btnone"], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url']))) {
			switch ($args["btnone"]) {
				case 'page':
					if (!isset($args["btnone_Page"]) || trim($args["btnone_Page"]) == "" || $args["btnone_Page"] == "0" || (($page = Page::getByID($args["btnone_Page"])) && $page->error !== false)) {
						$e->add(t("The %s field for '%s' is required.", t("Page"), t("Button one")));
					}
					break;
				case 'file':
					if (!isset($args["btnone_File"]) || trim($args["btnone_File"]) == "" || !is_object(File::getByID($args["btnone_File"]))) {
						$e->add(t("The %s field for '%s' is required.", t("File"), t("Button one")));
					}
					break;
				case 'url':
					if (!isset($args["btnone_URL"]) || trim($args["btnone_URL"]) == "" || !filter_var($args["btnone_URL"], FILTER_VALIDATE_URL)) {
						$e->add(t("The %s field for '%s' does not have a valid URL.", t("URL"), t("Button one")));
					}
					break;
				case 'relative_url':
					if (!isset($args["btnone_Relative_URL"]) || trim($args["btnone_Relative_URL"]) == "") {
						$e->add(t("The %s field for '%s' is required.", t("Relative URL"), t("Button one")));
					}
					break;
				case 'image':
					if (!isset($args["btnone_Image"]) || trim($args["btnone_Image"]) == "" || !is_object(File::getByID($args["btnone_Image"]))) {
						$e->add(t("The %s field for '%s' is required.", t("Image"), t("Button one")));
					}
					break;	
			}
		}
        if ((in_array("btntwo", $this->btFieldsRequired) && (!isset($args["btntwo"]) || trim($args["btntwo"]) == "")) || (isset($args["btntwo"]) && trim($args["btntwo"]) != "" && !array_key_exists($args["btntwo"], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url'])))) {
			$e->add(t("The %s field has an invalid value.", t("Button two")));
		} elseif (array_key_exists($args["btntwo"], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url']))) {
			switch ($args["btntwo"]) {
				case 'page':
					if (!isset($args["btntwo_Page"]) || trim($args["btntwo_Page"]) == "" || $args["btntwo_Page"] == "0" || (($page = Page::getByID($args["btntwo_Page"])) && $page->error !== false)) {
						$e->add(t("The %s field for '%s' is required.", t("Page"), t("Button two")));
					}
					break;
				case 'file':
					if (!isset($args["btntwo_File"]) || trim($args["btntwo_File"]) == "" || !is_object(File::getByID($args["btntwo_File"]))) {
						$e->add(t("The %s field for '%s' is required.", t("File"), t("Button two")));
					}
					break;
				case 'url':
					if (!isset($args["btntwo_URL"]) || trim($args["btntwo_URL"]) == "" || !filter_var($args["btntwo_URL"], FILTER_VALIDATE_URL)) {
						$e->add(t("The %s field for '%s' does not have a valid URL.", t("URL"), t("Button two")));
					}
					break;
				case 'relative_url':
					if (!isset($args["btntwo_Relative_URL"]) || trim($args["btntwo_Relative_URL"]) == "") {
						$e->add(t("The %s field for '%s' is required.", t("Relative URL"), t("Button two")));
					}
					break;
				case 'image':
					if (!isset($args["btntwo_Image"]) || trim($args["btntwo_Image"]) == "" || !is_object(File::getByID($args["btntwo_Image"]))) {
						$e->add(t("The %s field for '%s' is required.", t("Image"), t("Button two")));
					}
					break;	
			}
		}
        $slideimagesEntriesMin = 0;
        $slideimagesEntriesMax = 0;
        $slideimagesEntriesErrors = 0;
        $slideimages = [];
        if (isset($args['slideimages']) && is_array($args['slideimages']) && !empty($args['slideimages'])) {
            if ($slideimagesEntriesMin >= 1 && count($args['slideimages']) < $slideimagesEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Slider Images"), $slideimagesEntriesMin, count($args['slideimages'])));
                $slideimagesEntriesErrors++;
            }
            if ($slideimagesEntriesMax >= 1 && count($args['slideimages']) > $slideimagesEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Slider Images"), $slideimagesEntriesMax, count($args['slideimages'])));
                $slideimagesEntriesErrors++;
            }
            if ($slideimagesEntriesErrors == 0) {
                foreach ($args['slideimages'] as $slideimages_k => $slideimages_v) {
                    if (is_array($slideimages_v)) {
                        if (in_array("img", $this->btFieldsRequired['slideimages']) && (!isset($slideimages_v['img']) || trim($slideimages_v['img']) == "" || !is_object(File::getByID($slideimages_v['img'])))) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Image"), t("Slider Images"), $slideimages_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Slider Images'), $slideimages_k));
                    }
                }
            }
        } else {
            if ($slideimagesEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Slider Images"), $slideimagesEntriesMin));
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