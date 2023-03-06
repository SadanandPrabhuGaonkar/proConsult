<?php namespace Application\Block\CoreValues;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use Concrete\Core\Block\BlockController;
use Core;
use Database;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['slides' => []];
    protected $btExportFileColumns = ['logo'];
    protected $btExportTables = ['btCoreValues', 'btCoreValuesSlidesEntries'];
    protected $btTable = 'btCoreValues';
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
        return t("Core Values Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->desc_1;
        $db = Database::connection();
        $slides_items = $db->fetchAll('SELECT * FROM btCoreValuesSlidesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slides_items as $slides_item_k => $slides_item_v) {
            if (isset($slides_item_v["title"]) && trim($slides_item_v["title"]) != "") {
                $content[] = $slides_item_v["title"];
            }
            if (isset($slides_item_v["desc_1"]) && trim($slides_item_v["desc_1"]) != "") {
                $content[] = $slides_item_v["desc_1"];
            }
        }
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $slides = [];
        $slides_items = $db->fetchAll('SELECT * FROM btCoreValuesSlidesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slides_items as $slides_item_k => &$slides_item_v) {
            if (isset($slides_item_v['logo']) && trim($slides_item_v['logo']) != "" && ($f = File::getByID($slides_item_v['logo'])) && is_object($f)) {
                $slides_item_v['logo'] = $f;
            } else {
                $slides_item_v['logo'] = false;
            }
        }
        $this->set('slides_items', $slides_items);
        $this->set('slides', $slides);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btCoreValuesSlidesEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $slides_items = $db->fetchAll('SELECT * FROM btCoreValuesSlidesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slides_items as $slides_item) {
            unset($slides_item['id']);
            $slides_item['bID'] = $newBID;
            $db->insert('btCoreValuesSlidesEntries', $slides_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $slides = $this->get('slides');
        $this->set('slides_items', []);
        $this->set('slides', $slides);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $slides = $this->get('slides');
        $slides_items = $db->fetchAll('SELECT * FROM btCoreValuesSlidesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($slides_items as &$slides_item) {
            if (!File::getByID($slides_item['logo'])) {
                unset($slides_item['logo']);
            }
        }
        $this->set('slides', $slides);
        $this->set('slides_items', $slides_items);
    }

    protected function addEdit()
    {
        $slides = [];
        $this->set('slides', $slides);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/core_values/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/core_values/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/core_values/js_form/handlebars-helpers.js', [], $this->pkg);
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
        $rows = $db->fetchAll('SELECT * FROM btCoreValuesSlidesEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $slides_items = isset($args['slides']) && is_array($args['slides']) ? $args['slides'] : [];
        $queries = [];
        if (!empty($slides_items)) {
            $i = 0;
            foreach ($slides_items as $slides_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($slides_item['logo']) && trim($slides_item['logo']) != '') {
                    $data['logo'] = trim($slides_item['logo']);
                } else {
                    $data['logo'] = null;
                }
                if (isset($slides_item['title']) && trim($slides_item['title']) != '') {
                    $data['title'] = trim($slides_item['title']);
                } else {
                    $data['title'] = null;
                }
                if (isset($slides_item['desc_1']) && trim($slides_item['desc_1']) != '') {
                    $data['desc_1'] = trim($slides_item['desc_1']);
                } else {
                    $data['desc_1'] = null;
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
                                $db->update('btCoreValuesSlidesEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btCoreValuesSlidesEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btCoreValuesSlidesEntries', ['id' => $value]);
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
            $e->add(t("The %s field is required.", t("description")));
        }
        $slidesEntriesMin = 0;
        $slidesEntriesMax = 0;
        $slidesEntriesErrors = 0;
        $slides = [];
        if (isset($args['slides']) && is_array($args['slides']) && !empty($args['slides'])) {
            if ($slidesEntriesMin >= 1 && count($args['slides']) < $slidesEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Slides"), $slidesEntriesMin, count($args['slides'])));
                $slidesEntriesErrors++;
            }
            if ($slidesEntriesMax >= 1 && count($args['slides']) > $slidesEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Slides"), $slidesEntriesMax, count($args['slides'])));
                $slidesEntriesErrors++;
            }
            if ($slidesEntriesErrors == 0) {
                foreach ($args['slides'] as $slides_k => $slides_v) {
                    if (is_array($slides_v)) {
                        if (in_array("logo", $this->btFieldsRequired['slides']) && (!isset($slides_v['logo']) || trim($slides_v['logo']) == "" || !is_object(File::getByID($slides_v['logo'])))) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Logo"), t("Slides"), $slides_k));
                        }
                        if (in_array("title", $this->btFieldsRequired['slides']) && (!isset($slides_v['title']) || trim($slides_v['title']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Title"), t("Slides"), $slides_k));
                        }
                        if (in_array("desc_1", $this->btFieldsRequired['slides']) && (!isset($slides_v['desc_1']) || trim($slides_v['desc_1']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Description"), t("Slides"), $slides_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Slides'), $slides_k));
                    }
                }
            }
        } else {
            if ($slidesEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Slides"), $slidesEntriesMin));
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
}