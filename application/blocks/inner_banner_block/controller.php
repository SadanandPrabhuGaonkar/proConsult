<?php namespace Application\Block\InnerBannerBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use CollectionVersion;
use Concrete\Core\Block\BlockController;
use Core;
use Database;
use File;
use Page;
use Stack;
use StackList;

class Controller extends BlockController
{
    public $btFieldsRequired = ['bannerimgs' => []];
    protected $btExportFileColumns = ['imgs'];
    protected $btExportTables = ['btInnerBannerBlock', 'btInnerBannerBlockBannerimgsEntries'];
    protected $btTable = 'btInnerBannerBlock';
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
        return t("Inner Banner Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->type;
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $stack = [];
        if ($stack_entries = $db->fetchAll('SELECT * FROM btInnerBannerBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID])) {
            foreach ($stack_entries as $stack_entry) {
                $stack[$stack_entry['stID']] = Stack::getByID($stack_entry['stID']);
            }
        }
        $this->set('stack', $stack);
        $bannerimgs = [];
        $bannerimgs_items = $db->fetchAll('SELECT * FROM btInnerBannerBlockBannerimgsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($bannerimgs_items as $bannerimgs_item_k => &$bannerimgs_item_v) {
            if (isset($bannerimgs_item_v['imgs']) && trim($bannerimgs_item_v['imgs']) != "" && ($f = File::getByID($bannerimgs_item_v['imgs'])) && is_object($f)) {
                $bannerimgs_item_v['imgs'] = $f;
            } else {
                $bannerimgs_item_v['imgs'] = false;
            }
        }
        $this->set('bannerimgs_items', $bannerimgs_items);
        $this->set('bannerimgs', $bannerimgs);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btInnerBannerBlockStackEntries', ['bID' => $this->bID]);
        $db->delete('btInnerBannerBlockBannerimgsEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $stack_entries = $db->fetchAll('SELECT * FROM btInnerBannerBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID]);
        foreach ($stack_entries as $stack_entry) {
            unset($stack_entry['id']);
            $db->insert('btInnerBannerBlockStackEntries', $stack_entry);
        }
        $bannerimgs_items = $db->fetchAll('SELECT * FROM btInnerBannerBlockBannerimgsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($bannerimgs_items as $bannerimgs_item) {
            unset($bannerimgs_item['id']);
            $bannerimgs_item['bID'] = $newBID;
            $db->insert('btInnerBannerBlockBannerimgsEntries', $bannerimgs_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $stack_selected = [];
        $stack_options = $this->getStacks();
        $this->set('stack_options', $stack_options);
        $this->set('stack_selected', $stack_selected);
        $bannerimgs = $this->get('bannerimgs');
        $this->set('bannerimgs_items', []);
        $this->set('bannerimgs', $bannerimgs);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $stack_selected = [];
        $stack_ordered = [];
        $stack_options = $this->getStacks();
        if ($stack_entries = $db->fetchAll('SELECT * FROM btInnerBannerBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID])) {
            foreach ($stack_entries as $stack_entry) {
                $stack_selected[] = $stack_entry['stID'];
            }
            foreach ($stack_selected as $key) {
                if (array_key_exists($key, $stack_options)) {
                    $stack_ordered[$key] = $stack_options[$key];
                    unset($stack_options[$key]);
                }
            }
            $stack_options = $stack_ordered + $stack_options;
        }
        $this->set('stack_options', $stack_options);
        $this->set('stack_selected', $stack_selected);
        $bannerimgs = $this->get('bannerimgs');
        $bannerimgs_items = $db->fetchAll('SELECT * FROM btInnerBannerBlockBannerimgsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($bannerimgs_items as &$bannerimgs_item) {
            if (!File::getByID($bannerimgs_item['imgs'])) {
                unset($bannerimgs_item['imgs']);
            }
        }
        $this->set('bannerimgs', $bannerimgs);
        $this->set('bannerimgs_items', $bannerimgs_items);
    }

    protected function addEdit()
    {
        $bannerimgs = [];
        $this->set('bannerimgs', $bannerimgs);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('javascript', 'select2sortable', 'blocks/inner_banner_block/js_form/select2.sortable.js', [], $this->pkg);
        $al->register('css', 'repeatable-ft.form', 'blocks/inner_banner_block/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/inner_banner_block/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/inner_banner_block/js_form/handlebars-helpers.js', [], $this->pkg);
        $al->register('css', 'auto-css-' . $this->btHandle, 'blocks/' . $this->btHandle . '/auto.css', [], $this->pkg);
        $this->requireAsset('css', 'select2');
        $this->requireAsset('javascript', 'select2');
        $this->requireAsset('javascript', 'select2sortable');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('css', 'repeatable-ft.form');
        $this->requireAsset('javascript', 'handlebars');
        $this->requireAsset('javascript', 'handlebars-helpers');
        $this->requireAsset('core/file-manager');
        $this->requireAsset('css', 'auto-css-' . $this->btHandle);
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $db = Database::connection();
        $stack_entries_db = [];
        $stack_queries = [];
        if ($stack_entries = $db->fetchAll('SELECT * FROM btInnerBannerBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID])) {
            foreach ($stack_entries as $stack_entry) {
                $stack_entries_db[] = $stack_entry['id'];
            }
        }
        if (isset($args['stack']) && is_array($args['stack'])) {
            $stack_options = $this->getStacks();
            $i = 0;
            foreach ($args['stack'] as $stackID) {
                if ($stackID > 0 && array_key_exists($stackID, $stack_options)) {
                    $stack_data = [
                        'stID'      => $stackID,
                        'sortOrder' => $i,
                    ];
                    if (!empty($stack_entries_db)) {
                        $stack_entry_db_key = key($stack_entries_db);
                        $stack_entry_db_value = $stack_entries_db[$stack_entry_db_key];
                        $stack_queries['update'][$stack_entry_db_value] = $stack_data;
                        unset($stack_entries_db[$stack_entry_db_key]);
                    } else {
                        $stack_data['bID'] = $this->bID;
                        $stack_queries['insert'][] = $stack_data;
                    }
                    $i++;
                }
            }
        }
        if (!empty($stack_entries_db)) {
            foreach ($stack_entries_db as $stack_entry_db) {
                $stack_queries['delete'][] = $stack_entry_db;
            }
        }
        if (!empty($stack_queries)) {
            foreach ($stack_queries as $type => $values) {
                if (!empty($values)) {
                    switch ($type) {
                        case 'update':
                            foreach ($values as $id => $data) {
                                $db->update('btInnerBannerBlockStackEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btInnerBannerBlockStackEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btInnerBannerBlockStackEntries', ['id' => $value]);
                            }
                            break;
                    }
                }
            }
        }
        $rows = $db->fetchAll('SELECT * FROM btInnerBannerBlockBannerimgsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $bannerimgs_items = isset($args['bannerimgs']) && is_array($args['bannerimgs']) ? $args['bannerimgs'] : [];
        $queries = [];
        if (!empty($bannerimgs_items)) {
            $i = 0;
            foreach ($bannerimgs_items as $bannerimgs_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($bannerimgs_item['imgs']) && trim($bannerimgs_item['imgs']) != '') {
                    $data['imgs'] = trim($bannerimgs_item['imgs']);
                } else {
                    $data['imgs'] = null;
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
                                $db->update('btInnerBannerBlockBannerimgsEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btInnerBannerBlockBannerimgsEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btInnerBannerBlockBannerimgsEntries', ['id' => $value]);
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
        if (in_array("type", $this->btFieldsRequired) && (trim($args["type"]) == "")) {
            $e->add(t("The %s field is required.", t("Type")));
        }
        if (in_array("stack", $this->btFieldsRequired) && (!isset($args['stack']) || (!is_array($args['stack']) || empty($args['stack'])))) {
            $e->add(t("The %s field is required.", t("Breadcrumb")));
        } else {
            $stacksPosted = 0;
            $stacksMin = null;
            $stacksMax = null;
            if (isset($args['stack']) && is_array($args['stack'])) {
                $args['stack'] = array_unique($args['stack']);
                foreach ($args['stack'] as $stID) {
                    if ($st = Stack::getByID($stID)) {
                        $stacksPosted++;
                    }
                }
            }
            if ($stacksMin != null && $stacksMin >= 1 && $stacksPosted < $stacksMin) {
                $e->add(t("The %s field needs a minimum of %s stacks.", t("Breadcrumb"), $stacksMin));
            } elseif ($stacksMax != null && $stacksMax >= 1 && $stacksMax > $stacksMin && $stacksPosted > $stacksMax) {
                $e->add(t("The %s field has a maximum of %s stacks.", t("Breadcrumb"), $stacksMax));
            }
        }
        $bannerimgsEntriesMin = 0;
        $bannerimgsEntriesMax = 0;
        $bannerimgsEntriesErrors = 0;
        $bannerimgs = [];
        if (isset($args['bannerimgs']) && is_array($args['bannerimgs']) && !empty($args['bannerimgs'])) {
            if ($bannerimgsEntriesMin >= 1 && count($args['bannerimgs']) < $bannerimgsEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Banner Images"), $bannerimgsEntriesMin, count($args['bannerimgs'])));
                $bannerimgsEntriesErrors++;
            }
            if ($bannerimgsEntriesMax >= 1 && count($args['bannerimgs']) > $bannerimgsEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Banner Images"), $bannerimgsEntriesMax, count($args['bannerimgs'])));
                $bannerimgsEntriesErrors++;
            }
            if ($bannerimgsEntriesErrors == 0) {
                foreach ($args['bannerimgs'] as $bannerimgs_k => $bannerimgs_v) {
                    if (is_array($bannerimgs_v)) {
                        if (in_array("imgs", $this->btFieldsRequired['bannerimgs']) && (!isset($bannerimgs_v['imgs']) || trim($bannerimgs_v['imgs']) == "" || !is_object(File::getByID($bannerimgs_v['imgs'])))) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Images"), t("Banner Images"), $bannerimgs_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Banner Images'), $bannerimgs_k));
                    }
                }
            }
        } else {
            if ($bannerimgsEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Banner Images"), $bannerimgsEntriesMin));
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

    private function getStacks()
    {
        $stacksOptions = [];
        $stm = new StackList();
        $stm->filterByUserAdded();
        $stacks = $stm->get();
        foreach ($stacks as $st) {
            $sv = CollectionVersion::get($st, 'ACTIVE');
            $stacksOptions[$st->getCollectionID()] = $sv->getVersionName();
        }
        return $stacksOptions;
    }
}