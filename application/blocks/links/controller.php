<?php namespace Application\Block\Links;

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
    public $btFieldsRequired = ['links' => []];
    protected $btExportTables = ['btLinks', 'btLinksLinksEntries'];
    protected $btTable = 'btLinks';
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
        return t("Links");
    }

    public function view()
    {
        $db = Database::connection();
        $links = [];
        $links_items = $db->fetchAll('SELECT * FROM btLinksLinksEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($links_items as $links_item_k => &$links_item_v) {
            $links_item_v["linkdetail_Object"] = null;
			$links_item_v["linkdetail_Title"] = trim($links_item_v["linkdetail_Title"]);
			if (isset($links_item_v["linkdetail"]) && trim($links_item_v["linkdetail"]) != '') {
				switch ($links_item_v["linkdetail"]) {
					case 'page':
						if ($links_item_v["linkdetail_Page"] > 0 && ($links_item_v["linkdetail_Page_c"] = Page::getByID($links_item_v["linkdetail_Page"])) && !$links_item_v["linkdetail_Page_c"]->error && !$links_item_v["linkdetail_Page_c"]->isInTrash()) {
							$links_item_v["linkdetail_Object"] = $links_item_v["linkdetail_Page_c"];
							$links_item_v["linkdetail_URL"] = $links_item_v["linkdetail_Page_c"]->getCollectionLink();
							if ($links_item_v["linkdetail_Title"] == '') {
								$links_item_v["linkdetail_Title"] = $links_item_v["linkdetail_Page_c"]->getCollectionName();
							}
						}
						break;
				    case 'file':
						$links_item_v["linkdetail_File_id"] = (int)$links_item_v["linkdetail_File"];
						if ($links_item_v["linkdetail_File_id"] > 0 && ($links_item_v["linkdetail_File_object"] = File::getByID($links_item_v["linkdetail_File_id"])) && is_object($links_item_v["linkdetail_File_object"])) {
							$fp = new Permissions($links_item_v["linkdetail_File_object"]);
							if ($fp->canViewFile()) {
								$links_item_v["linkdetail_Object"] = $links_item_v["linkdetail_File_object"];
								$links_item_v["linkdetail_URL"] = $links_item_v["linkdetail_File_object"]->getRelativePath();
								if ($links_item_v["linkdetail_Title"] == '') {
									$links_item_v["linkdetail_Title"] = $links_item_v["linkdetail_File_object"]->getTitle();
								}
							}
						}
						break;
				    case 'url':
						if ($links_item_v["linkdetail_Title"] == '') {
							$links_item_v["linkdetail_Title"] = $links_item_v["linkdetail_URL"];
						}
						break;
				    case 'relative_url':
						if ($links_item_v["linkdetail_Title"] == '') {
							$links_item_v["linkdetail_Title"] = $links_item_v["linkdetail_Relative_URL"];
						}
						$links_item_v["linkdetail_URL"] = $links_item_v["linkdetail_Relative_URL"];
						break;
				    case 'image':
						if ($links_item_v["linkdetail_Image"] > 0 && ($links_item_v["linkdetail_Image_object"] = File::getByID($links_item_v["linkdetail_Image"])) && is_object($links_item_v["linkdetail_Image_object"])) {
							$links_item_v["linkdetail_URL"] = $links_item_v["linkdetail_Image_object"]->getURL();
							$links_item_v["linkdetail_Object"] = $links_item_v["linkdetail_Image_object"];
							if ($links_item_v["linkdetail_Title"] == '') {
								$links_item_v["linkdetail_Title"] = $links_item_v["linkdetail_Image_object"]->getTitle();
							}
						}
						break;
				}
			}
        }
        $this->set('links_items', $links_items);
        $this->set('links', $links);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btLinksLinksEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $links_items = $db->fetchAll('SELECT * FROM btLinksLinksEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($links_items as $links_item) {
            unset($links_item['id']);
            $links_item['bID'] = $newBID;
            $db->insert('btLinksLinksEntries', $links_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $links = $this->get('links');
        $this->set('links_items', []);
        $this->set('links', $links);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $links = $this->get('links');
        $links_items = $db->fetchAll('SELECT * FROM btLinksLinksEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $this->set('links', $links);
        $this->set('links_items', $links_items);
    }

    protected function addEdit()
    {
        $links = [];
        $this->set("linkdetail_Options", $this->getSmartLinkTypeOptions([
  'page',
  'file',
  'image',
  'url',
  'relative_url',
], true));
        $this->set('links', $links);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/links/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/links/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/links/js_form/handlebars-helpers.js', [], $this->pkg);
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
        $rows = $db->fetchAll('SELECT * FROM btLinksLinksEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $links_items = isset($args['links']) && is_array($args['links']) ? $args['links'] : [];
        $queries = [];
        if (!empty($links_items)) {
            $i = 0;
            foreach ($links_items as $links_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($links_item['linkdetail']) && trim($links_item['linkdetail']) != '') {
					$data['linkdetail_Title'] = $links_item['linkdetail_Title'];
					$data['linkdetail'] = $links_item['linkdetail'];
					switch ($links_item['linkdetail']) {
						case 'page':
							$data['linkdetail_Page'] = $links_item['linkdetail_Page'];
							$data['linkdetail_File'] = '0';
							$data['linkdetail_URL'] = '';
							$data['linkdetail_Relative_URL'] = '';
							$data['linkdetail_Image'] = '0';
							break;
                        case 'file':
							$data['linkdetail_File'] = $links_item['linkdetail_File'];
							$data['linkdetail_Page'] = '0';
							$data['linkdetail_URL'] = '';
							$data['linkdetail_Relative_URL'] = '';
							$data['linkdetail_Image'] = '0';
							break;
                        case 'url':
							$data['linkdetail_URL'] = $links_item['linkdetail_URL'];
							$data['linkdetail_Page'] = '0';
							$data['linkdetail_File'] = '0';
							$data['linkdetail_Relative_URL'] = '';
							$data['linkdetail_Image'] = '0';
							break;
                        case 'relative_url':
							$data['linkdetail_Relative_URL'] = $links_item['linkdetail_Relative_URL'];
							$data['linkdetail_Page'] = '0';
							$data['linkdetail_File'] = '0';
							$data['linkdetail_URL'] = '';
							$data['linkdetail_Image'] = '0';
							break;
                        case 'image':
							$data['linkdetail_Image'] = $links_item['linkdetail_Image'];
							$data['linkdetail_Page'] = '0';
							$data['linkdetail_File'] = '0';
							$data['linkdetail_URL'] = '';
							$data['linkdetail_Relative_URL'] = '';
							break;
                        default:
							$data['linkdetail'] = '';
							$data['linkdetail_Page'] = '0';
							$data['linkdetail_File'] = '0';
							$data['linkdetail_URL'] = '';
							$data['linkdetail_Relative_URL'] = '';
							$data['linkdetail_Image'] = '0';
							break;	
					}
				}
				else {
					$data['linkdetail'] = '';
					$data['linkdetail_Title'] = '';
					$data['linkdetail_Page'] = '0';
					$data['linkdetail_File'] = '0';
					$data['linkdetail_URL'] = '';
					$data['linkdetail_Relative_URL'] = '';
					$data['linkdetail_Image'] = '0';
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
                                $db->update('btLinksLinksEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btLinksLinksEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btLinksLinksEntries', ['id' => $value]);
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
        $linksEntriesMin = 0;
        $linksEntriesMax = 0;
        $linksEntriesErrors = 0;
        $links = [];
        if (isset($args['links']) && is_array($args['links']) && !empty($args['links'])) {
            if ($linksEntriesMin >= 1 && count($args['links']) < $linksEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Links"), $linksEntriesMin, count($args['links'])));
                $linksEntriesErrors++;
            }
            if ($linksEntriesMax >= 1 && count($args['links']) > $linksEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Links"), $linksEntriesMax, count($args['links'])));
                $linksEntriesErrors++;
            }
            if ($linksEntriesErrors == 0) {
                foreach ($args['links'] as $links_k => $links_v) {
                    if (is_array($links_v)) {
                        if ((in_array("linkdetail", $this->btFieldsRequired['links']) && (!isset($links_v['linkdetail']) || trim($links_v['linkdetail']) == "")) || (isset($links_v['linkdetail']) && trim($links_v['linkdetail']) != "" && !array_key_exists($links_v['linkdetail'], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url'])))) {
							$e->add(t("The %s field has an invalid value.", t("Link Detail")));
						} elseif (array_key_exists($links_v['linkdetail'], $this->getSmartLinkTypeOptions(['page', 'file', 'image', 'url', 'relative_url']))) {
							switch ($links_v['linkdetail']) {
								case 'page':
									if (!isset($links_v['linkdetail_Page']) || trim($links_v['linkdetail_Page']) == "" || $links_v['linkdetail_Page'] == "0" || (($page = Page::getByID($links_v['linkdetail_Page'])) && $page->error !== false)) {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("Page"), t("Link Detail"), t("Links"), $links_k));
									}
									break;
				                case 'file':
									if (!isset($links_v['linkdetail_File']) || trim($links_v['linkdetail_File']) == "" || !is_object(File::getByID($links_v['linkdetail_File']))) {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("File"), t("Link Detail"), t("Links"), $links_k));
									}
									break;
				                case 'url':
									if (!isset($links_v['linkdetail_URL']) || trim($links_v['linkdetail_URL']) == "" || !filter_var($links_v['linkdetail_URL'], FILTER_VALIDATE_URL)) {
										$e->add(t("The %s field for '%s' does not have a valid URL (%s, row #%s).", t("URL"), t("Link Detail"), t("Links"), $links_k));
									}
									break;
				                case 'relative_url':
									if (!isset($links_v['linkdetail_Relative_URL']) || trim($links_v['linkdetail_Relative_URL']) == "") {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("Relative URL"), t("Link Detail"), t("Links"), $links_k));
									}
									break;
				                case 'image':
									if (!isset($links_v['linkdetail_Image']) || trim($links_v['linkdetail_Image']) == "" || !is_object(File::getByID($links_v['linkdetail_Image']))) {
										$e->add(t("The %s field for '%s' is required (%s, row #%s).", t("Image"), t("Link Detail"), t("Links"), $links_k));
									}
									break;	
							}
						}
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Links'), $links_k));
                    }
                }
            }
        } else {
            if ($linksEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Links"), $linksEntriesMin));
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