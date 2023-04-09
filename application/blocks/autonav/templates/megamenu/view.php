<?php defined('C5_EXECUTE') or die("Access Denied.");
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
$ih = new \Application\Concrete\Helpers\ImageHelper();
$navItems = $controller->getNavItems();
$c = Page::getCurrentPage();

foreach ($navItems as $ni) {
    $classes = array();

    if ($ni->isCurrent) {
        //class for the page currently being viewed
        $classes[] = 'nav-selected';
    }

    if ($ni->inPath) {
        //class for parent items of the page currently being viewed
        $classes[] = 'nav-path-selected';
    }

    if ($ni->hasSubmenu) {
        $classes[] = 'has-submenu';
    } else {
        $classes[] = 'no-submenu';
    }

    $ni->classes = implode(" ", $classes);
}

if (count($navItems) > 0) {

    echo '<header>';
    echo '<div class="common-padding-side">';
    echo '<div class="header-inner">';
    echo '<div class="logo">';
    echo '<a href="' . View::url('/') . '">';
    echo '<img src="' . $themePath . '/dist/images/logo.svg" alt="' . $site . '"/>';
    echo '</a>';
    echo '</div>';
    echo '<nav>';
    echo '<ul>'; //opens the top-level menu

    foreach ($navItems as $ni) {
        $url = $ni->hasSubmenu ? "#" : $ni->url;

        if($ni->level == 1){
            echo '<li class="' . $ni->classes . '">'; //opens a nav item
            echo '<a href="' . $url . '">' . h($ni->name) . '</a>';
        }else{
            echo '<li class="sub">';
            echo '<div class="inner">';
            echo '<img src="' . $ih->getThumbnail($ni->cObj->getAttribute("logo_img")) . '" alt="' . $ni->name . '" border="0">';
            echo '</div>';
            echo '<a href="' . $ni->url . '">' . h($ni->name) . '</a>';
            echo '<a href="' . $ni->url . '" class="absolute-a"></a>';
        }

        if ($ni->hasSubmenu) {
            echo '<ul>'; //opens a sub nav item
        } else {
            echo '</li>'; //closes a nav item

            echo str_repeat('</ul></li>', $ni->subDepth); //closes dropdown sub-menu(s) and their top-level nav item(s)
        }
    }

    echo '</ul>'; //closes the top-level menu
    echo '</nav>';
    echo '</div>';
    echo '<div class="header-btn">';
    echo '<div class="header-btn-inner">
            <div class="svg">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.2" d="M8.67187 11.6998C9.44364 13.2935 10.7324 14.579 12.3281 15.3467C12.4458 15.4024 12.576 15.4265 12.7059 15.4167C12.8358 15.4068 12.9608 15.3633 13.0687 15.2904L15.4125 13.7248C15.516 13.6546 15.6357 13.6117 15.7603 13.6002C15.8849 13.5887 16.0104 13.609 16.125 13.6592L20.5125 15.5435C20.6625 15.6059 20.7877 15.7159 20.869 15.8565C20.9504 15.9971 20.9832 16.1606 20.9625 16.3217C20.8234 17.407 20.2937 18.4046 19.4723 19.1276C18.6509 19.8506 17.5943 20.2495 16.5 20.2498C13.1185 20.2498 9.87548 18.9065 7.48439 16.5154C5.0933 14.1243 3.75 10.8813 3.75 7.49979C3.75025 6.40553 4.1492 5.34886 4.87221 4.5275C5.59522 3.70613 6.59274 3.17635 7.67812 3.03729C7.83922 3.01659 8.00266 3.04943 8.14326 3.13074C8.28386 3.21206 8.39384 3.33733 8.45625 3.48729L10.3406 7.88416C10.3896 7.99699 10.4101 8.12013 10.4003 8.24275C10.3905 8.36537 10.3507 8.48369 10.2844 8.58729L8.71875 10.9685C8.64905 11.0762 8.60814 11.2 8.59993 11.328C8.59172 11.4561 8.61649 11.5841 8.67187 11.6998Z" fill="#263790"/>
            <path d="M8.67187 11.6998C9.44364 13.2935 10.7324 14.579 12.3281 15.3467C12.4458 15.4024 12.576 15.4265 12.7059 15.4167C12.8358 15.4068 12.9608 15.3633 13.0687 15.2904L15.4125 13.7248C15.516 13.6546 15.6357 13.6117 15.7603 13.6002C15.8849 13.5887 16.0104 13.609 16.125 13.6592L20.5125 15.5435C20.6625 15.6059 20.7877 15.7159 20.869 15.8565C20.9504 15.9971 20.9832 16.1606 20.9625 16.3217C20.8234 17.407 20.2937 18.4046 19.4723 19.1276C18.6509 19.8506 17.5943 20.2495 16.5 20.2498C13.1185 20.2498 9.87548 18.9065 7.48439 16.5154C5.0933 14.1243 3.75 10.8813 3.75 7.49979C3.75025 6.40553 4.1492 5.34886 4.87221 4.5275C5.59522 3.70613 6.59274 3.17635 7.67812 3.03729C7.83922 3.01659 8.00266 3.04943 8.14326 3.13074C8.28386 3.21206 8.39384 3.33733 8.45625 3.48729L10.3406 7.88416C10.3896 7.99699 10.4101 8.12013 10.4003 8.24275C10.3905 8.36537 10.3507 8.48369 10.2844 8.58729L8.71875 10.9685C8.64905 11.0762 8.60814 11.2 8.59993 11.328C8.59172 11.4561 8.61649 11.5841 8.67187 11.6998Z" stroke="#263790" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14.9438 3.75C16.216 4.09141 17.3759 4.76142 18.3073 5.69279C19.2387 6.62416 19.9087 7.78412 20.2501 9.05625" stroke="#263790" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14.1655 6.64648C14.9306 6.84968 15.6284 7.25153 16.1882 7.8113C16.748 8.37107 17.1498 9.06887 17.353 9.83398" stroke="#263790" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </div>
            <div class="num">
            <a href="tel:+917218386120" target="_blank">+917218386120</a>
            </div>
            </div>
            ';
    echo '<a class="search">Search</a>';
    echo '</div>';
    echo '<nav class="mobile">';
    echo '<a class="search">Search</a>';
    echo '<div class="mobile-menu">';
    echo '<span class="nav-icon"></span>';
    echo '</div>';
    echo '</nav>';
    echo '</div>';
    echo '</header>';

} elseif (is_object($c) && $c->isEditMode()) {
    ?>
    <div class="ccm-edit-mode-disabled-item"><?= t('Empty Auto-Nav Block.') ?></div>
    <?php
}
?>
