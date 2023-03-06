<?php namespace Application\Block\OurServicesBlock;

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
    public $btFieldsRequired = ['services' => []];
    protected $btExportFileColumns = ['img'];
    protected $btExportTables = ['btOurServicesBlock', 'btOurServicesBlockServicesEntries'];
    protected $btTable = 'btOurServicesBlock';
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
        return t("Our Services Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->desc_1;
        $db = Database::connection();
        $services_items = $db->fetchAll('SELECT * FROM btOurServicesBlockServicesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($services_items as $services_item_k => $services_item_v) {
            if (isset($services_item_v["servicetitle"]) && trim($services_item_v["servicetitle"]) != "") {
                $content[] = $services_item_v["servicetitle"];
            }
            if (isset($services_item_v["descservice"]) && trim($services_item_v["descservice"]) != "") {
                $content[] = $services_item_v["descservice"];
            }
        }
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $services = [];
        $services_items = $db->fetchAll('SELECT * FROM btOurServicesBlockServicesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($services_items as $services_item_k => &$services_item_v) {
            $services_item_v["btn_Object"] = null;
			$services_item_v["btn_Title"] = trim($services_item_v["btn_Title"]);
			if (isset($services_item_v["btn"]) && trim($services_item_v["btn"]) != '') {
				switch ($services_item_v["btn"]) {
					case 'page':
						if ($services_item_v["btn_Page"] > 0 && ($services_item_v["btn_Page_c"] = Page::getByID($services_item_v["btn_Page"])) && !$services_item_v["btn_Page_c"]->error && !$services_item_v["btn_Page_c"]->isInTrash()) {
							$services_item_v["btn_Object"] = $services_item_v["btn_Page_c"];
							$services_item_v["btn_URL"] = $services_item_v["btn_Page_c"]->getCollectionLink();
							if ($services_item_v["btn_Title"] == '') {
								$services_item_v["btn_Title"] = $services_item_v["btn_Page_c"]->getCollectionName();
							}
						}
						break;
				    case 'file':
						$services_item_v["btn_File_id"] = (int)$services_item_v["btn_File"];
						if ($services_item_v["btn_File_id"] > 0 && ($services_item_v["btn_File_object"] = File::getByID($services_item_v["btn_File_id"])) && is_object($services_item_v["btn_File_object"])) {
							$fp = new Permissions($services_item_v["btn_File_object"]);
							if ($fp->canViewFile()) {
								$services_item_v["btn_Object"] = $services_item_v["btn_File_object"];
								$services_item_v["btn_URL"] = $services_item_v["btn_File_object"]->getRelativePath();
								if ($services_item_v["btn_Title"] == '') {
									$services_item_v["btn_Title"] = $services_item_v["btn_File_object"]->getTitle();
								}
							}
						}
						break;
				    case 'url':
						if ($services_item_v["btn_Title"] == '') {
							$services_item_v["btn_Title"] = $services_item_v["btn_URL"];
						}
						break;
				    case 'relative_url':
						if ($services_item_v["btn_Title"] == '') {
							$services_item_v["btn_Title"] = $services_item_v["btn_Relative_URL"];
						}
						$services_item_v["btn_URL"] = $services_item_v["btn_Relative_URL"];
						break;
				    case 'image':
						if ($services_item_v["btn_Image"] > 0 && ($services_item_v["btn_Image_object"] = File::getByID($services_item_v["btn_Image"])) && is_object($services_item_v["btn_Image_object"])) {
							$services_item_v["btn_URL"] = $services_item_v["btn_Image_object"]->getURL();
							$services_item_v["btn_Object"] = $services_item_v["btn_Image_object"];
							if ($services_item_v["btn_Title"] == '') {
								$services_item_v["btn_Title"] = $services_item_v["btn_Image_object"]->getTitle();
							}
						}
						break;
				}
			}
            if (isset($services_item_v['img']) && trim($services_item_v['img']) != "" && ($f = File::getByID($services_item_v['img'])) && is_object($f)) {
                $services_item_v['img'] = $f;
            } else {
                $services_item_v['img'] = false;
            }
        }
        $this->set('services_items', $services_items);
        $this->set('services', $services);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btOurServicesBlockServicesEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $services_items = $db->fetchAll('SELECT * FROM btOurServicesBlockServicesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($services_items as $services_item) {
            unset($services_item['id']);
            $services_item['bID'] = $newBID;
            $db->insert('btOurServicesBlockServicesEntries', $services_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $services = $this->get('services');
        $this->set('services_items', []);
        $this->set('services', $services);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $services = $this->get('services');
        $services_items = $db->fetchAll('SELECT * FROM btOurServicesBlockServicesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($services_items as &$services_item) {
            if (!File::getByID($services_item['img'])) {
                unset($services_item['img']);
            }
        }
        $this->set('services', $services);
        $this->set('services_items', $services_items);
    }

    protected function addEdit()
    {
        $services = [];
        $this->set("btn_Options", $this->getSmartLinkTypeOptions([
  'page',
  'file',
  'image',
  'url',
  'relative_url',
], true));
        $this->set('services', $services);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/our_services_block/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/our_services_block/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/our_services_block/js_form/handlebars-helpers.js', [], $this->pkg);
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
        $rows = $db->fetchAll('SELECT * FROM btOurServicesBlockServicesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $services_items = isset($args['services']) && is_array($args['services']) ? $args['services'] : [];
        $queries = [];
        if (!empty($services_items)) {
            $i = 0;
            foreach ($services_items as $services_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($services_item['servicetitle']) && trim($services_item['servicetitle']) != '') {
                    $data['servicetitle'] = trim($services_item['servicetitle']);
                } else {
                    $data['servicetitle'] = null;
                }
                if (isset($services_item['descservice']) && trim($services_item['descservice']) != '') {
                    $data['descservice'] = trim($services_item['descservice']);
                } else {
                    $data['descservice'] = null;
                }
                if (isset($services_item['btn']) && trim($services_item['btn']) != '') {
					$data['btn_Title'] = $services_item['btn_Title'];
					$data['btn'] = $services_item['btn'];
					switch ($services_item['btn']) {
						case 'page':
							$data['btn_Page'] = $services_item['btn_Page'];
							$data['btn_File'] = '0';
							$data['btn_URL'] = '';
							$data['btn_Relative_URL'] = '';
							$data['btn_Image'] = '0';
							break;
                        case 'file':
							$data['btn_File'] = $services_item['btn_File'];
							$data['btn_Page'] = '0';
							$data['btn_URL'] = '';
							$data['btn_Relative_URL'] = '';
							$data['btn_Image'] = '0';
							break;
                        case 'url':
							$data['btn_URL'] = $services_item['btn_URL'];
							$data['btn_Page'] = '0';
							$data['btn_File'] = '0';
							$data['btn_Relative_URL'] = '';
							$data['btn_Image'] = '0';
							break;
                        case 'relative_url':
							$data['btn_Relative_URL'] = $services_item['btn_Relative_URL'];
							$data['btn_Page'] = '0';
							$data['btn_File'] = '0';
							$data['btn_URL'] = '';
							$data['btn_Image'] = '0';
							break;
                        case 'image':
							$data['btn_Image'] = $services_item['btn_Image'];
							$data['btn_Page'] = '0';
							$data['btn_File'] = '0';
							$data['btn_URL'] = '';
							$data['btn_Relative_URL'] = '';
							break;
                        default:
							$data['btn'] = '';
							$data['btn_Page'] = '0';
							$data['btn_File'] = '0';
							$data['btn_URL'] = '';
							$data['btn_Relative_URL'] = '';
							$data['btn_Image'] = '0';
							break;	
					}
				}
				else {
					$data['btn'] = '';
					$data['btn_Title'] = '';
					$data['btn_Page'] = '0';
					$data['btn_File'] = '0';
					$data['btn_URL'] = '';
					$data['btn_Relative_URL'] = '';
					$data['btn_Image'] = '0';
				}
                if (isset($services_item['img']) && trim($services_item['img']) != '') {
                    $data['img'] = trim($services_item['img']);
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
                                $db->update('btOurServicesBlockServicesEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btOurServicesBlockServicesEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btOurServicesBlockServicesEntries', ['id' => $value]);
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
        if (in_array("desc_1", $this->btFieldsRequired) && (trim($args["desc_1"]) == "")) {
            $e->add(t("The %s field is required.", t("Desc")));
        }
        $servicesEntriesMin = 0;
        $servicesEntriesMax = 0;
        $servicesEntriesErrors = 0;
        $services = [];
        if (isset($args['services']) && is_array($args['services']) && !empty($args['services'])) {
            if ($servicesEntriesMin >= 1 && count($args['services']) < $servicesEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Services"), $servicesEntriesMin, count($args['services'])));
                $servicesEntriesErrors++;
            }
            if ($servicesEntriesMax >= 1 && count($args['services']) > $servicesEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Services"), $servicesEntriesMax, count($args['services'])));
                $servicesEntriesErrors++;
            }
            if ($servicesEntriesErrors == 0) {
                foreach ($args['services'] as $services_k => $services_v) {
                    if (is_array($services_v)) {
                        if (in_array("servicetitle", $this->btFieldsRequired['services']) && (!isset($services_v['servicetitle']) || trim($services_v['servicetitle']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Title"), t("Services"), $services_k));
                        }
                        if (in_array("descservice", $this->btFieldsRequired['services']) && (!isset($services_v['descservice']) || trim($services_v['descservice']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Description"), t("Services"), $services_k));
                        }
                        if ((in_array("btn", $this->btFieldsRequired['services']) && (!isset($services_v['btn']) || trim($services_v['btn']) == "")) || (isset($services_v['btn']) && trim($services_v['btn']) != "" && !array_key_exists($services_v['btn'], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url'])))) {
							$e->add(t("The %s field has an invalid value.", t("Button")));
						} elseif (array_key_exists($services_v['btn'], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url']))) {
							switch ($services_v['btn']) {
								case 'page':
									if (!isset($services_v['btn_Page']) || trim($services_v['btn_Page']) == "" || $services_v['btn_Page'] == "0" || (($page = Page::getByID($services_v['btn_Page'])) && $page->error !== false)) {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("Page"), t("Button"), t("Services"), $services_k));
									}
									break;
				                case 'file':
									if (!isset($services_v['btn_File']) || trim($services_v['btn_File']) == "" || !is_object(File::getByID($services_v['btn_File']))) {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("File"), t("Button"), t("Services"), $services_k));
									}
									break;
				                case 'url':
									if (!isset($services_v['btn_URL']) || trim($services_v['btn_URL']) == "" || !filter_var($services_v['btn_URL'], FILTER_VALIDATE_URL)) {
										$e->add(t("The %s field for '%s' does not have a valid URL (%s, row #%s).", t("URL"), t("Button"), t("Services"), $services_k));
									}
									break;
				                case 'relative_url':
									if (!isset($services_v['btn_Relative_URL']) || trim($services_v['btn_Relative_URL']) == "") {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("Relative URL"), t("Button"), t("Services"), $services_k));
									}
									break;
				                case 'image':
									if (!isset($services_v['btn_Image']) || trim($services_v['btn_Image']) == "" || !is_object(File::getByID($services_v['btn_Image']))) {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("Image"), t("Button"), t("Services"), $services_k));
									}
									break;	
							}
						}
                        if (in_array("img", $this->btFieldsRequired['services']) && (!isset($services_v['img']) || trim($services_v['img']) == "" || !is_object(File::getByID($services_v['img'])))) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Image"), t("Services"), $services_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Services'), $services_k));
                    }
                }
            }
        } else {
            if ($servicesEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Services"), $servicesEntriesMin));
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