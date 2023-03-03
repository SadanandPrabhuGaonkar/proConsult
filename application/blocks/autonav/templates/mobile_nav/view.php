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
    }

    $ni->classes = implode(" ", $classes);
}

if (count($navItems) > 0) {

    echo '<div class="mobile-nav">';
    echo '<div class="common-padding-side">';
    echo '<img class="mobile-bg" src="' . $themePath . '/dist/images/graph.png" alt="graph"/>';
    echo '<nav>';
    echo '<ul>'; //opens the top-level menu

    foreach ($navItems as $ni) {

        if ($ni->level == 1) {
            echo '<li class="' . $ni->classes . '">'; //opens a nav item
            echo '<a href="' . $ni->url . '">' . h($ni->name) . '</a>';
        } else {
            echo '<li>';
            echo '<a href="' . $ni->url . '">' . h($ni->name) . '</a>';
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
    echo '<a class="btn-main btn-blue-background">Contact Us</a>';
    echo '</div>';
    echo '</div>';
    echo '</header>';

} elseif (is_object($c) && $c->isEditMode()) {
    ?>
    <div class="ccm-edit-mode-disabled-item"><?= t('Empty Auto-Nav Block.') ?></div>
    <?php
}
?>
