<?php
namespace Concrete\Package\FormidableFull;

use Package;
use BlockType;
use SinglePage;
use \Concrete\Core\Job\Job as Job;
use Concrete\Package\FormidableFull\Src\Formidable as Formidable;
use AssetList;
use Page;
use Route;
use Database;
use URL;
use Request;
use Core;
use Symfony\Component\ClassLoader\Psr4ClassLoader as SymfonyClassLoader;

class Controller extends Package {

    protected $pkgHandle = 'formidable_full';
    protected $appVersionRequired = '8.0.0';
    protected $pkgVersion = '1.0.9';

    protected $singlePages = array(
        array('/dashboard/formidable'),
        array('/dashboard/formidable/forms', false),
        array('/dashboard/formidable/forms/elements', true),
        array('/dashboard/formidable/forms/mailings', true),
        array('/dashboard/formidable/results', false),
        array('/dashboard/formidable/templates', false),
        array('/dashboard/reports/formidable', false)
    );

    protected $jobs = array(
        'clean_formidable'
    );

    protected $blocks = array(
        'formidable'
        );

    public function getPackageDescription() {
        return t('Create awesome forms with a few clicks!');
    }

    public function getPackageName() {
        return t('Formidable (Full Version)');
    }

    public function on_start() {
        $strictLoader = new SymfonyClassLoader();
        $strictLoader->addPrefix('\Concrete\Package\FormidableFull\Src', DIR_PACKAGES . '/formidable_full/src');
        $strictLoader->register();

        $register = array(
            '/formidable/dialog/dashboard/forms/preview' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\Preview::view',
            '/formidable/dialog/dashboard/forms/preview/result' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\Preview::result',
            '/formidable/dialog/dashboard/forms/form_list' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\FormList::view',
            '/formidable/dialog/dashboard/forms/element_list' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\ElementList::view',
            '/formidable/dialog/dashboard/forms/mailing_list' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\MailingList::view',
            '/formidable/dialog/dashboard/forms/dialog/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\Dialog::delete',

            '/formidable/dialog/dashboard/forms/tools/duplicate' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\Tools::duplicate',
            '/formidable/dialog/dashboard/forms/tools/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Forms\Tools::delete',

            '/formidable/dialog/dashboard/elements/dialog' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dialog::view',
            '/formidable/dialog/dashboard/elements/dialog/select' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dialog::select',
            '/formidable/dialog/dashboard/elements/dialog/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dialog::delete',
            '/formidable/dialog/dashboard/elements/dialog/bulk' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dialog::bulk',

            '/formidable/dialog/dashboard/elements/tools/save' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::save',
            '/formidable/dialog/dashboard/elements/tools/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::delete',
            '/formidable/dialog/dashboard/elements/tools/duplicate' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::duplicate',
            '/formidable/dialog/dashboard/elements/tools/order' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::order',
            '/formidable/dialog/dashboard/elements/tools/validate' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::validate',
            '/formidable/dialog/dashboard/elements/tools/options' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::options',
            '/formidable/dialog/dashboard/elements/tools/bulk' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Tools::bulk',

            '/formidable/dialog/dashboard/elements/dependency/add' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dependency::add',
            '/formidable/dialog/dashboard/elements/dependency/action' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dependency::action',
            '/formidable/dialog/dashboard/elements/dependency/element' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dependency::element',
            '/formidable/dialog/dashboard/elements/dependency/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Elements\Dependency::delete',

            '/formidable/dialog/dashboard/layout/dialog' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Dialog::view',
            '/formidable/dialog/dashboard/layout/dialog/select' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Dialog::select',
            '/formidable/dialog/dashboard/layout/dialog/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Dialog::delete',

            '/formidable/dialog/dashboard/layout/tools/save' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Tools::save',
            '/formidable/dialog/dashboard/layout/tools/list' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Tools::list',
            '/formidable/dialog/dashboard/layout/tools/order' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Tools::order',
            '/formidable/dialog/dashboard/layout/tools/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Layouts\Tools::delete',

            '/formidable/dialog/dashboard/results/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Dialog::delete',
            '/formidable/dialog/dashboard/results/delete/submit' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Tools::delete',
            '/formidable/dialog/dashboard/results/resend' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Dialog::resend',
            '/formidable/dialog/dashboard/results/resend/submit' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Tools::resend',
            '/formidable/dialog/dashboard/results/customize' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Customize::view',
            '/formidable/dialog/dashboard/results/customize/submit' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Customize::submit',
            '/formidable/dialog/dashboard/results/csv' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Tools::csv',

            '/formidable/dialog/dashboard/results/search/basic' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Search::searchBasic',
            '/formidable/dialog/dashboard/results/search/current' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Search::searchCurrent',
            '/formidable/dialog/dashboard/results/search/preset/{presetID}' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Search::searchPreset',
            '/formidable/dialog/dashboard/results/search/clear' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\Search::clearSearch',

            '/formidable/dialog/dashboard/results/search/advanced_search' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\AdvancedSearch::View',
            '/formidable/dialog/dashboard/results/search/advanced_search/add_field' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\AdvancedSearch::addField',
            '/formidable/dialog/dashboard/results/search/advanced_search/submit' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\AdvancedSearch::submit',
            '/formidable/dialog/dashboard/results/search/advanced_search/save_preset' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Results\AdvancedSearch::savePreset',

            '/formidable/dialog/dashboard/mailings/tools/save' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Tools::save',
            '/formidable/dialog/dashboard/mailings/tools/duplicate' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Tools::duplicate',
            '/formidable/dialog/dashboard/mailings/tools/validate' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Tools::validate',
            '/formidable/dialog/dashboard/mailings/tools/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Tools::delete',

            '/formidable/dialog/dashboard/mailings/dependency/add' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Dependency::add',
            '/formidable/dialog/dashboard/mailings/dependency/action' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Dependency::action',
            '/formidable/dialog/dashboard/mailings/dependency/element' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Dependency::element',
            '/formidable/dialog/dashboard/mailings/dependency/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Dependency::delete',

            '/formidable/dialog/dashboard/mailings/dialog' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Dialog::view',
            '/formidable/dialog/dashboard/mailings/dialog/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Mailings\Dialog::delete',

            '/formidable/dialog/dashboard/templates/preview' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates\Preview::view',
            '/formidable/dialog/dashboard/templates/template_list' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates\TemplateList::view',
            '/formidable/dialog/dashboard/templates/dialog/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates\Dialog::delete',

            '/formidable/dialog/dashboard/templates/tools/duplicate' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates\Tools::duplicate',
            '/formidable/dialog/dashboard/templates/tools/delete' => '\Concrete\Package\FormidableFull\Controller\Dialog\Dashboard\Templates\Tools::delete',

            '/formidable/dialog/formidable' => '\Concrete\Package\FormidableFull\Controller\Dialog\Formidable::view',

            '/formidable/dialog/formidable/topjs' => '\Concrete\Package\FormidableFull\Controller\Dialog\Formidable::topJS',
        );

        if (is_array($register) && count($register)) {
            foreach ($register as $path => $controller) {
                Route::register($path, $controller);
            }
        }

        $al = AssetList::getInstance();
        $token = Core::make('token');

        $script = "var edit_content = '".t('Edit content')."';
        var add_content = '".t('Add content')."';
        var changed_values = '".t('You have made some changes to the Form Properties. Are you sure you want to discard these changes?')."';
        var formidable_security_token_form = '".$token->generate('formidable_form')."';                 
        var list_url = '".URL::to('/formidable/dialog/dashboard/forms/form_list')."';
        var dialog_url = '".URL::to('/formidable/dialog/dashboard/forms/dialog')."';
        var tools_url = '".URL::to('/formidable/dialog/dashboard/forms/tools')."';
        var title_message_delete = '".t('Delete Formidable Form')."'             
        $(function() {
            ccmFormidableLoadForms(); 
        });";
        $al->register('javascript-inline', 'formidable/inline/dashboard/forms/top', $script, array('minify' => true, 'combine' => true));

        $script = "var formidable_security_token_element = '".$token->generate('formidable_element')."';
        var formidable_security_token_mailing = '".$token->generate('formidable_mailing')."';
        var formidable_security_token_dependency = '".$token->generate('formidable_dependency')."';
        var formidable_security_token_layout = '".$token->generate('formidable_layout')."';
        var option_counter = 10000;
        var list_url = '".URL::to('/formidable/dialog/dashboard/forms/element_list')."';
        var dialog_url = '".URL::to('/formidable/dialog/dashboard/elements/dialog')."';
        var tools_url = '".URL::to('/formidable/dialog/dashboard/elements/tools')."';
        var dependency_url = '".URL::to('/formidable/dialog/dashboard/elements/dependency')."';
        var layout_dialog_url = '".URL::to('/formidable/dialog/dashboard/layout/dialog')."';
        var layout_tools_url = '".URL::to('/formidable/dialog/dashboard/layout/tools')."';
        var placeholder_name = '".t('Name')."';
        var placeholder_email = '".t('E-mailaddress')."';
        var placeholder_option = '".t('Option')."';
        var element_message_add = '".t('Add element to Formidable Form')."';
        var element_message_edit = '".t('Edit element on Formidable Form')."';
        var element_message_delete = '".t('Delete element from Formidable Form')."';
        var element_message_bulk = '".t('Add multiple options for Formidable Element')."';
        var layout_message_add = '".t('Add layout on Formidable Form')."';
        var layout_message_edit = '".t('Edit layout on Formidable Form')."';
        var layout_message_delete = '".t('Delete layout from Formidable Form')."';                  
        var dependency_action_placeholder_class = '".t('Classname to toggle')."';
        var dependency_action_placeholder_value = '".t('Value to set')."';
        var dependency_action_placeholder_placeholder = '".t('Placeholder to set')."';
        var dependency_values = [['any_value', '".t('any value')."'], ['no_value', '".t('no value')."']];
        var dependency_condition_placeholder = '".t('Value')."';
        var dependency_message_delete = '".t('Delete dependency from Formidable Element')."'
        var condition_values = [['empty', '".t('is empty')."'], ['not_empty', '".t('is not empty')."'], ['equals', '".t('equals')."'], ['not_equals', '".t('not equal to')."'], ['contains', '".t('contains')."'], ['not_contains', '".t('does not contain')."']];
        $(function() {
            ccmFormidableLoadElements(); 
        });";
        $al->register('javascript-inline', 'formidable/inline/dashboard/elements/top', $script, array('minify' => true, 'combine' => true));

        $script = "var formidable_security_token_element = '".$token->generate('formidable_element')."';
        var formidable_security_token_mailing = '".$token->generate('formidable_mailing')."';
        var formidable_security_token_dependency = '".$token->generate('formidable_dependency')."';
        var attachment_counter = 10000;          
        var list_url = '".URL::to('/formidable/dialog/dashboard/forms/mailing_list')."';
        var dialog_url = '".URL::to('/formidable/dialog/dashboard/mailings/dialog')."';
        var tools_url = '".URL::to('/formidable/dialog/dashboard/mailings/tools')."';           
        var dependency_url = '".URL::to('/formidable/dialog/dashboard/mailings/dependency')."';
        var element_dialog_url = '".URL::to('/formidable/dialog/dashboard/elements/dialog/select')."';
        var element_tools_url = '".URL::to('/formidable/dialog/dashboard/elements/tools')."';
        var choose_element = '".t('Choose an Element')."';
        var title_element_overlay = '".t('Choose an element')."';
        var title_sitemap_overlay = '".t('Choose a page')."';
        var title_message_add = '".t('Add mailing to Formidable Form')."';
        var title_message_edit = '".t('Edit mailing from Formidable Form')."';
        var title_message_delete = '".t('Delete mailing from Formidable Form')."';
        var dependency_values = [['any_value', '".t('any value')."'], ['no_value', '".t('no value')."']];
        var dependency_condition_placeholder = '".t('Value')."';
        var dependency_message_delete = '".t('Delete dependency from Formidable Mailing')."'
        var condition_values = [['empty', '".t('is empty')."'], ['not_empty', '".t('is not empty')."'], ['equals', '".t('equals')."'], ['not_equals', '".t('not equal to')."'], ['contains', '".t('contains')."'], ['not_contains', '".t('does not contain')."']];
        $(function() {
            ccmFormidableLoadMailings();
            ccmFormidableCreateMenu();
        });";
        $al->register('javascript-inline', 'formidable/inline/dashboard/mailings/top', $script, array('minify' => true, 'combine' => true));

        $script = "var formidable_security_token_form = '".$token->generate('formidable_form')."';                 
        var list_url = '".URL::to('/formidable/dialog/dashboard/templates/template_list')."';
        var dialog_url = '".URL::to('/formidable/dialog/dashboard/templates/dialog')."';
        var tools_url = '".URL::to('/formidable/dialog/dashboard/templates/tools')."';
        var title_message_delete = '".t('Delete Formidable Template')."'             
        $(function() {
            ccmFormidableLoadTemplates(); 
        });";
        $al->register('javascript-inline', 'formidable/inline/dashboard/templates/top', $script, array('minify' => true, 'combine' => true));

        $al->register('javascript', 'formidable/top', URL::to('/formidable/dialog/formidable/topjs'), array('local' => false, 'minify' => true, 'combine' => true));

        $al->register('javascript', 'formidable/dashboard/common', 'js/dashboard/common_functions.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dashboard/forms', 'js/dashboard/forms.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dashboard/layouts', 'js/dashboard/layouts.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dashboard/elements', 'js/dashboard/elements.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dashboard/mailings', 'js/dashboard/mailings.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dashboard/results', 'js/dashboard/results.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dashboard/templates', 'js/dashboard/templates.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/timepicker', 'js/plugins/timepicker.min.js', array('minify' => false, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/placeholder', 'js/plugins/placeholder.min.js', array('minify' => false, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dependson', 'js/plugins/dependson.min.js', array('minify' => false, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/mask', 'js/plugins/mask.min.js', array('minify' => false, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/countable', 'js/plugins/simplycountable.min.js', array('minify' => false, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/dropzone', 'js/plugins/dropzone.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/slider', 'js/plugins/slider.min.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/rating', 'js/plugins/rating.min.js', array('minify' => true, 'combine' => true), $this->pkgHandle);

        $al->register('javascript', 'formidable/editor', 'js/editor.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable/template', 'js/template.js', array('minify' => true, 'combine' => true), $this->pkgHandle);
        $al->register('javascript', 'formidable', 'js/formidable.js', array('minify' => true, 'combine' => true), $this->pkgHandle);

        $al->register('css', 'formidable/dashboard', 'css/dashboard/formidable.css', array('minify' => true, 'combine' => true), $this->pkgHandle);

    }

    public function install() {
        $pkg = parent::install();
        $this->checkCreateBlocks();
        $this->checkCreateJobs();
        $this->checkCreatePages();
    }

    public function upgrade() {
        $pkg = parent::upgrade();
        $this->checkCreateBlocks();
        $this->checkCreateJobs();
        $this->checkCreatePages();

        // Clear all columns...
        $forms = Formidable::getAllForms();
        if(is_array($forms)) {
            if (count($forms)) {
                foreach ($forms as $formID => $name) {
                    Formidable::clearColumnSet($formID);
                }
            }
        }
    }

    public function uninstall() {
        parent::uninstall();
        $r = Request::getInstance();
        if ($r->request->get('removeContent')) {
            $db = Database::connection();
            $db->executeQuery('DROP TABLE IF EXISTS FormidableForms');
            $db->executeQuery('DROP TABLE IF EXISTS FormidableFormElements');
            $db->executeQuery('DROP TABLE IF EXISTS FormidableFormMailings');
            $db->executeQuery('DROP TABLE IF EXISTS FormidableAnswerSets');
            $db->executeQuery('DROP TABLE IF EXISTS FormidableAnswers');
            $db->executeQuery('DROP TABLE IF EXISTS FormidableFormLayouts');
            $db->executeQuery('DROP TABLE IF EXISTS btFormidable');
        }
    }

    private function checkCreateBlocks() {
        if(is_array($this->blocks)) {
            if(count($this->blocks)) {
                $pkg = Package::getByHandle($this->pkgHandle);
                foreach($this->blocks as $block) {
                    $blockType = BlockType::getByHandle($block, $pkg);
                    if(!is_object($blockType)) {
                        BlockType::installBlockType($block, $pkg);
                    }
                }
            }
        }
    }

    private function checkCreateJobs() {
        if(is_array($this->jobs)) {
            if(count($this->jobs)) {
                $pkg = Package::getByHandle($this->pkgHandle);
                foreach($this->jobs as $job) {
                    $jb = Job::getByHandle($job);
                    if(!is_object($jb)) {
                        Job::installByPackage($job, $pkg);
                    }
                }
            }
        }

    }

    private function checkCreatePages() {
        if(is_array($this->singlePages)) {
            if(count($this->singlePages)) {
                $pkg = Package::getByHandle($this->pkgHandle);
                foreach($this->singlePages as $sp) {
                    $page = Page::getByPath($sp[0]);
                    if ($page->getCollectionID() <= 0) {
                        SinglePage::add($sp[0], $pkg);
                        $page = Page::getByPath($sp[0]);
                    }
                    if ($sp[1] === true) {
                        $page->setAttribute('exclude_nav', $sp[1]);
                    }
                }
            }
        }
    }
}

