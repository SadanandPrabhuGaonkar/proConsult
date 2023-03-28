<?php namespace Application\Block\ContactBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use AssetList;
use CollectionVersion;
use Concrete\Core\Block\BlockController;
use Core;
use Database;
use Stack;
use StackList;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btTable = 'btContactBlock';
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
        return t("Contact Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->title;
        $content[] = $this->subtitle;
        $content[] = $this->number;
        $content[] = $this->email;
        $content[] = $this->linkedin;
        return implode(" ", $content);
    }

    public function view()
    {
        $db = Database::connection();
        $stack = [];
        if ($stack_entries = $db->fetchAll('SELECT * FROM btContactBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID])) {
            foreach ($stack_entries as $stack_entry) {
                $stack[$stack_entry['stID']] = Stack::getByID($stack_entry['stID']);
            }
        }
        $this->set('stack', $stack);
    }

    public function delete()
    {
        $db = Database::connection();
        $db->delete('btContactBlockStackEntries', ['bID' => $this->bID]);
        parent::delete();
    }

    public function duplicate($newBID)
    {
        $db = Database::connection();
        $stack_entries = $db->fetchAll('SELECT * FROM btContactBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID]);
        foreach ($stack_entries as $stack_entry) {
            unset($stack_entry['id']);
            $db->insert('btContactBlockStackEntries', $stack_entry);
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
    }

    public function edit()
    {
        $db = Database::connection();
        $this->addEdit();
        $stack_selected = [];
        $stack_ordered = [];
        $stack_options = $this->getStacks();
        if ($stack_entries = $db->fetchAll('SELECT * FROM btContactBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID])) {
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
    }

    protected function addEdit()
    {
        $al = AssetList::getInstance();
        $al->register('javascript', 'select2sortable', 'blocks/contact_block/js_form/select2.sortable.js', [], $this->pkg);
        $al->register('css', 'auto-css-' . $this->btHandle, 'blocks/' . $this->btHandle . '/auto.css', [], $this->pkg);
        $this->requireAsset('css', 'select2');
        $this->requireAsset('javascript', 'select2');
        $this->requireAsset('javascript', 'select2sortable');
        $this->requireAsset('css', 'auto-css-' . $this->btHandle);
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $db = Database::connection();
        $stack_entries_db = [];
        $stack_queries = [];
        if ($stack_entries = $db->fetchAll('SELECT * FROM btContactBlockStackEntries WHERE bID = ? ORDER BY sortOrder ASC', [$this->bID])) {
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
                                $db->update('btContactBlockStackEntries', $data, ['id' => $id]);
                            }
                            break;
                        case 'insert':
                            foreach ($values as $data) {
                                $db->insert('btContactBlockStackEntries', $data);
                            }
                            break;
                        case 'delete':
                            foreach ($values as $value) {
                                $db->delete('btContactBlockStackEntries', ['id' => $value]);
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
        if (in_array("subtitle", $this->btFieldsRequired) && (trim($args["subtitle"]) == "")) {
            $e->add(t("The %s field is required.", t("Subtitle")));
        }
        if (in_array("number", $this->btFieldsRequired) && (trim($args["number"]) == "")) {
            $e->add(t("The %s field is required.", t("Phone Number")));
        }
        if (in_array("email", $this->btFieldsRequired) && (trim($args["email"]) == "")) {
            $e->add(t("The %s field is required.", t("Email")));
        }
        if (in_array("linkedin", $this->btFieldsRequired) && (trim($args["linkedin"]) == "")) {
            $e->add(t("The %s field is required.", t("Linkedin URL")));
        }
        if (in_array("stack", $this->btFieldsRequired) && (!isset($args['stack']) || (!is_array($args['stack']) || empty($args['stack'])))) {
            $e->add(t("The %s field is required.", t("Stack")));
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
                $e->add(t("The %s field needs a minimum of %s stacks.", t("Stack"), $stacksMin));
            } elseif ($stacksMax != null && $stacksMax >= 1 && $stacksMax > $stacksMin && $stacksPosted > $stacksMax) {
                $e->add(t("The %s field has a maximum of %s stacks.", t("Stack"), $stacksMax));
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