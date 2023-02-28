<?php
namespace Application\Theme\proConsult;

use Concrete\Core\Area\Layout\Preset\Provider\ThemeProviderInterface;
use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme
{
	public function registerAssets()
    {
    	$this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript-conditional', 'html5-shiv');
        $this->requireAsset('javascript-conditional', 'respond');
    }

	protected $pThemeGridFrameworkHandle = 'bootstrap3';

}
