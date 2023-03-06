<?php namespace Application\Block\AchievementBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use Database;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['points' => []];
    protected $btExportFileColumns = ['bgimage'];
    protected $btExportTables = ['btAchievementBlock', 'btAchievementBlockPointsEntries'];
    protected $btTable = 'btAchievementBlock';
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
        return t("Achievement Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->desc_1;
        $db = Database::connection();
        $points_items = $db->fetchAll('SELECT * FROM btAchievementBlockPointsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($points_items as $points_item_k => $points_item_v) {
            if (isset($points_item_v["textpoints"]) && trim($points_item_v["textpoints"]) != "") {
                $content[] = $points_item_v["textpoints"];
            }
        }
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $selecttype_options = [
            '' => "-- " . t("None") . " --",
            '1' => "Light",
            '2' => "Dark",
            '3' => "With Background Image"
        ];
        $this->set("selecttype_options", $selecttype_options);
        $this->set('desc_1', LinkAbstractor::translateFrom($this->desc_1));
        
        if ($this->bgimage && ($f = File::getByID($this->bgimage)) && is_object($f)) {
            $this->set("bgimage", $f);
        } else {
            $this->set("bgimage", false);
        }
        $points = [];
        $points_items = $db->fetchAll('SELECT * FROM btAchievementBlockPointsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $this->set('points_items', $points_items);
        $this->set('points', $points);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btAchievementBlockPointsEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $points_items = $db->fetchAll('SELECT * FROM btAchievementBlockPointsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        foreach ($points_items as $points_item) {
            unset($points_item['id']);
            $points_item['bID'] = $newBID;
            $db->insert('btAchievementBlockPointsEntries', $points_item);
        }
        parent::duplicate($newBID);
    }

    public function add()
    {
        $this->addEdit();
        $points = $this->get('points');
        $this->set('points_items', []);
        $this->set('points', $points);
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        
        $this->set('desc_1', LinkAbstractor::translateFromEditMode($this->desc_1));
        $points = $this->get('points');
        $points_items = $db->fetchAll('SELECT * FROM btAchievementBlockPointsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $this->set('points', $points);
        $this->set('points_items', $points_items);
    }

    protected function addEdit()
    {
        $this->set("selecttype_options", [
                '' => "-- " . t("None") . " --",
                '1' => "Light",
                '2' => "Dark",
                '3' => "With Background Image"
            ]
        );
        $points = [];
        $this->set('points', $points);
        $this->set('identifier', new \Concrete\Core\Utility\Service\Identifier());
        $al = AssetList::getInstance();
        $al->register('css', 'repeatable-ft.form', 'blocks/achievement_block/css_form/repeatable-ft.form.css', [], $this->pkg);
        $al->register('javascript', 'handlebars', 'blocks/achievement_block/js_form/handlebars-v4.0.4.js', [], $this->pkg);
        $al->register('javascript', 'handlebars-helpers', 'blocks/achievement_block/js_form/handlebars-helpers.js', [], $this->pkg);
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/sitemap');
        $this->requireAsset('css', 'repeatable-ft.form');
        $this->requireAsset('javascript', 'handlebars');
        $this->requireAsset('javascript', 'handlebars-helpers');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $db = Database::connection();
        $args['desc_1'] = LinkAbstractor::translateTo($args['desc_1']);
        $rows = $db->fetchAll('SELECT * FROM btAchievementBlockPointsEntries WHERE bID = ? ORDER BY sortOrder', [$this->bID]);
        $points_items = isset($args['points']) && is_array($args['points']) ? $args['points'] : [];
        $queries = [];
        if (!empty($points_items)) {
            $i = 0;
            foreach ($points_items as $points_item) {
                $data = [
                    'sortOrder' => $i + 1,
                ];
                if (isset($points_item['textpoints']) && trim($points_item['textpoints']) != '') {
                    $data['textpoints'] = trim($points_item['textpoints']);
                } else {
                    $data['textpoints'] = null;
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
                                $db->update('btAchievementBlockPointsEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btAchievementBlockPointsEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btAchievementBlockPointsEntries', ['id' => $value]);
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
        if ((in_array("selecttype", $this->btFieldsRequired) && (!isset($args["selecttype"]) || trim($args["selecttype"]) == "")) || (isset($args["selecttype"]) && trim($args["selecttype"]) != "" && !in_array($args["selecttype"], ["1", "2", "3"]))) {
            $e->add(t("The %s field has an invalid value.", t("Select type")));
        }
        if (in_array("title", $this->btFieldsRequired) && (trim($args["title"]) == "")) {
            $e->add(t("The %s field is required.", t("Title")));
        }
        if (in_array("desc_1", $this->btFieldsRequired) && (trim($args["desc_1"]) == "")) {
            $e->add(t("The %s field is required.", t("Description")));
        }
        if (in_array("bgimage", $this->btFieldsRequired) && (trim($args["bgimage"]) == "" || !is_object(File::getByID($args["bgimage"])))) {
            $e->add(t("The %s field is required.", t("Background Image")));
        }
        $pointsEntriesMin = 0;
        $pointsEntriesMax = 0;
        $pointsEntriesErrors = 0;
        $points = [];
        if (isset($args['points']) && is_array($args['points']) && !empty($args['points'])) {
            if ($pointsEntriesMin >= 1 && count($args['points']) < $pointsEntriesMin) {
                $e->add(t("The %s field requires at least %s entries, %s entered.", t("Points"), $pointsEntriesMin, count($args['points'])));
                $pointsEntriesErrors++;
            }
            if ($pointsEntriesMax >= 1 && count($args['points']) > $pointsEntriesMax) {
                $e->add(t("The %s field is set to a maximum of %s entries, %s entered.", t("Points"), $pointsEntriesMax, count($args['points'])));
                $pointsEntriesErrors++;
            }
            if ($pointsEntriesErrors == 0) {
                foreach ($args['points'] as $points_k => $points_v) {
                    if (is_array($points_v)) {
                        if (in_array("textpoints", $this->btFieldsRequired['points']) && (!isset($points_v['textpoints']) || trim($points_v['textpoints']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("Text points"), t("Points"), $points_k));
                        }
                    } else {
                        $e->add(t("The values for the %s field, row #%s, are incomplete.", t('Points'), $points_k));
                    }
                }
            }
        } else {
            if ($pointsEntriesMin >= 1) {
                $e->add(t("The %s field requires at least %s entries, none entered.", t("Points"), $pointsEntriesMin));
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