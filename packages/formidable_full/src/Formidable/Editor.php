<?php 
namespace Concrete\Package\FormidableFull\Src\Formidable;

use \Concrete\Core\Editor\RedactorEditor;

class Editor extends RedactorEditor {

	public function __construct() {
		parent::__construct();
		$this->pluginManager->register('formidable', t('Formidable'));
	}

	public function outputFormidableEditor($key, $content = null) {
        $plugins = $this->pluginManager->getSelectedPlugins();
        $plugins[] = 'formidable';
        return $this->getEditor($key, $content, array('plugins' => $plugins, 'minHeight' => 300));
    }
}
