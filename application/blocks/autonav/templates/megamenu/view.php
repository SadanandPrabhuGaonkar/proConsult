<?php defined('C5_EXECUTE') or die("Access Denied.");
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();

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

        if($ni->level == 1){
            echo '<li class="' . $ni->classes . '">'; //opens a nav item
            echo '<a href="' . $ni->url . '">' . h($ni->name) . '</a>';
        }else{
            echo '<li class="sub">';
            echo '<div class="inner">';
            echo '<img src="https://i.ibb.co/hHs6h6r/15032148271582884281-1.png" alt="15032148271582884281-1" border="0">';
            echo '</div>';
            echo '<a href="' . $ni->url . '">' . h($ni->name) . '</a>';
            echo '<a href="" class="absolute-a"></a>';
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
    echo '<a class="btn-main btn-blue-background">Contact Us</a>';
    echo '</div>';
    echo '<nav class="mobile">';
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
