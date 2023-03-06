<?php namespace Application\Block\ServiceBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use Database;

class Controller extends BlockController
{
    public $btFieldsRequired = ['txts' => []];
    protected $btExportTables = ['btServiceBlock', 'btServiceBlockTxtsEntries'];
    protected $btTable = 'btServiceBlock';
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
        return t("Service Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->desc_1;
        $db = Database::connection();
        $txts_items = $db->fetchAll('SELECT * FROM btServiceBlockTxtsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($txts_items as $txts_item_k => $txts_item_v) {
            if (isset($txts_item_v["content"]) && trim($txts_item_v["content"]) != "") {
                $content[] = $txts_item_v["content"];
            }
        }
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $txts = [];
        $txts_items = $db->fetchAll('SELECT * FROM btServiceBlockTxtsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($txts_items as $txts_item_k => &$txts_item_v) {
            $txts_item_v["content"] = isset($txts_item_v["content"]) ? LinkAbstractor::translateFrom($txts_item_v["content"]) : null;
        }
        $this->set('txts_items', $txts_items);
        $this->set('txts', $txts);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btServiceBlockTxtsEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $txts_items = $db->fetchAll('SELECT * FROM btServiceBlockTxtsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($txts_items as $txts_item) {
            unset($txts_item['id']);
            $txts_item['bID'] = $newBID;
            $db->insert('btServiceBlockTxtsEntries', $txts_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $txts = $this->get('txts');
        $this->set('txts_items', []);
        $this->set('txts', $txts);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $txts = $this->get('txts');
        $txts_items = $db->fetchAll('SELECT * FROM btServiceBlockTxtsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        
        foreach ($txts_items as &$txts_item) {
            $txts_item['content'] = isset($txts_item['content']) ? LinkAbstractor::translateFromEditMode($txts_item['content']) : null;
        }
        $this->set('txts', $txts);
        $this->set('txts_items', $txts_items);
    }

    protected function addEdit()
    {
        $txts = [];
        $this->set('txts', $txts);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/service_block/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/service_block/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/service_block/js_form/handlebars-helpers.js', [], $this->pkg);
        $this->requireAsset('core/sitemap');
        $this->requireAsset('css', 'repeatable-ft.form');
        $this->requireAsset('javascript', 'handlebars');
        $this->requireAsset('javascript', 'handlebars-helpers');
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $db = Database::connection();
        $rows = $db->fetchAll('SELECT * FROM btServiceBlockTxtsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $txts_items = isset($args['txts']) && is_array($args['txts']) ? $args['txts'] : [];
        $queries = [];
        if (!empty($txts_items)) {
            $i = 0;
            foreach ($txts_items as $txts_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                $data['content'] = isset($txts_item['content']) ? LinkAbstractor::translateTo($txts_item['content']) : null;
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
                                $db->update('btServiceBlockTxtsEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btServiceBlockTxtsEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btServiceBlockTxtsEntries', ['id' => $value]);
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
            $e->add(t("The %s field is required.", t("Description")));
        }
        $txtsEntriesMin = 0;
        $txtsEntriesMax = 0;
        $txtsEntriesErrors = 0;
        $txts = [];
        if (isset($args['txts']) && is_array($args['txts']) && !empty($args['txts'])) {
            if ($txtsEntriesMin >= 1 && count($args['txts']) < $txtsEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Texts"), $txtsEntriesMin, count($args['txts'])));
                $txtsEntriesErrors++;
            }
            if ($txtsEntriesMax >= 1 && count($args['txts']) > $txtsEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Texts"), $txtsEntriesMax, count($args['txts'])));
                $txtsEntriesErrors++;
            }
            if ($txtsEntriesErrors == 0) {
                foreach ($args['txts'] as $txts_k => $txts_v) {
                    if (is_array($txts_v)) {
                        if (in_array("content", $this->btFieldsRequired['txts']) && (!isset($txts_v['content']) || trim($txts_v['content']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("content"), t("Texts"), $txts_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Texts'), $txts_k));
                    }
                }
            }
        } else {
            if ($txtsEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Texts"), $txtsEntriesMin));
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