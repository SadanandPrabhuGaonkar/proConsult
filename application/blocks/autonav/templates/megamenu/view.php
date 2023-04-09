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
    echo '<a class="search"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.94975 0H8.95261V0.00128549C11.4232 0.00202005 13.6602 1.02986 15.2791 2.69071C16.8953 4.34917 17.8965 6.64174 17.8981 9.17378H17.8993V9.17984V9.18719H17.8981C17.8972 10.2077 17.7339 11.1898 17.4336 12.1056C17.3833 12.2593 17.3307 12.407 17.2766 12.548V12.5493C17.0177 13.2214 16.6831 13.8568 16.2835 14.4421L21.4903 19.2423L21.4935 19.2452L21.5216 19.272L21.5236 19.274C21.8176 19.5611 21.9776 19.9454 21.9979 20.3355C22.0175 20.7211 21.9006 21.1156 21.6435 21.432L21.6416 21.4349L21.6102 21.4722L21.604 21.4786L21.5778 21.508L21.575 21.5119C21.2948 21.8134 20.9208 21.9772 20.5401 21.9978C20.1647 22.0182 19.7798 21.8986 19.4711 21.6346L19.4684 21.6325L19.4319 21.6004L19.4267 21.596L14.1012 16.6865C13.9437 16.8004 13.7829 16.9086 13.6201 17.0107C13.3994 17.1493 13.1707 17.2799 12.9375 17.3991C11.7381 18.0128 10.3828 18.358 8.94993 18.358V18.3593H8.94706V18.358C6.47652 18.3573 4.23908 17.3295 2.62022 15.6686C1.00351 14.0101 0.00286461 11.7172 0.00125327 9.1859H0V9.17984V9.1769H0.00125327C0.00196942 6.64284 1.00404 4.34788 2.62326 2.6874C4.24016 1.02986 6.47526 0.00293825 8.94384 0.00128549V0H8.94975ZM8.95261 2.05861V2.0599H8.94975H8.94384V2.05861C7.03064 2.0599 5.29648 2.85745 4.04106 4.14459C2.78583 5.43173 2.00755 7.21231 2.00701 9.17709H2.00827V9.18003V9.18609H2.00701C2.00827 11.1485 2.78529 12.9263 4.04071 14.2144C5.29558 15.502 7.03154 16.3003 8.94688 16.3007V16.2994H8.94975H8.95566V16.3007C10.8689 16.2994 12.6023 15.5022 13.8581 14.2147C15.1133 12.9276 15.8916 11.1476 15.8919 9.18296H15.8909V9.18003V9.17397H15.8919C15.8909 7.21158 15.1131 5.43283 13.8584 4.14514C12.6036 2.85745 10.8681 2.05917 8.95261 2.05861Z" fill="white"/>
    </svg>
    </a>';
    echo '</div>';
    echo '<nav class="mobile">';
    echo '<a class="search"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.94975 0H8.95261V0.00128549C11.4232 0.00202005 13.6602 1.02986 15.2791 2.69071C16.8953 4.34917 17.8965 6.64174 17.8981 9.17378H17.8993V9.17984V9.18719H17.8981C17.8972 10.2077 17.7339 11.1898 17.4336 12.1056C17.3833 12.2593 17.3307 12.407 17.2766 12.548V12.5493C17.0177 13.2214 16.6831 13.8568 16.2835 14.4421L21.4903 19.2423L21.4935 19.2452L21.5216 19.272L21.5236 19.274C21.8176 19.5611 21.9776 19.9454 21.9979 20.3355C22.0175 20.7211 21.9006 21.1156 21.6435 21.432L21.6416 21.4349L21.6102 21.4722L21.604 21.4786L21.5778 21.508L21.575 21.5119C21.2948 21.8134 20.9208 21.9772 20.5401 21.9978C20.1647 22.0182 19.7798 21.8986 19.4711 21.6346L19.4684 21.6325L19.4319 21.6004L19.4267 21.596L14.1012 16.6865C13.9437 16.8004 13.7829 16.9086 13.6201 17.0107C13.3994 17.1493 13.1707 17.2799 12.9375 17.3991C11.7381 18.0128 10.3828 18.358 8.94993 18.358V18.3593H8.94706V18.358C6.47652 18.3573 4.23908 17.3295 2.62022 15.6686C1.00351 14.0101 0.00286461 11.7172 0.00125327 9.1859H0V9.17984V9.1769H0.00125327C0.00196942 6.64284 1.00404 4.34788 2.62326 2.6874C4.24016 1.02986 6.47526 0.00293825 8.94384 0.00128549V0H8.94975ZM8.95261 2.05861V2.0599H8.94975H8.94384V2.05861C7.03064 2.0599 5.29648 2.85745 4.04106 4.14459C2.78583 5.43173 2.00755 7.21231 2.00701 9.17709H2.00827V9.18003V9.18609H2.00701C2.00827 11.1485 2.78529 12.9263 4.04071 14.2144C5.29558 15.502 7.03154 16.3003 8.94688 16.3007V16.2994H8.94975H8.95566V16.3007C10.8689 16.2994 12.6023 15.5022 13.8581 14.2147C15.1133 12.9276 15.8916 11.1476 15.8919 9.18296H15.8909V9.18003V9.17397H15.8919C15.8909 7.21158 15.1131 5.43283 13.8584 4.14514C12.6036 2.85745 10.8681 2.05917 8.95261 2.05861Z" fill="white"/>
    </svg>
    </a>';
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
