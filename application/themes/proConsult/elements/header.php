<?php defined('C5_EXECUTE') or die("Access Denied.");

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();

?>

<?php $this->inc('elements/header_top.php'); ?>

<?php
$stack = \Concrete\Core\Page\Stack\Stack::getByName('Header Navigation');
$stack && $stack->display();
?>

//to be removed
<!--<div class="mobile-nav">
    <div class="common-padding-side">
    <img class="mobile-bg" src="<?php /*echo $themePath; */?>/dist/images/graph.png" alt="graph"/>
        <nav>
                <ul>
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Industries</a>
                    </li>
                    <li class="has-submenu">
                        <a>Services</a>
                        <ul>
                            <li>
                                <a href="">Procurement & Sourcing</a>
                            </li>
                            <li>
                                <a href="">Management Consultancy</a>
                            </li>
                            <li>
                                <a href="">E-Transformation</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <a class="btn-main btn-blue-background">Contact Us</a>
        </div>
</div>
<header>
    <div class="common-padding-side">
        <div class="header-inner">
            <div class="logo">
                <a href="<?php /*echo View::url('/'); */?>">
                    <img src="<?php /*echo $themePath; */?>/dist/images/logo.svg" alt="<?php /*echo $site; */?>"/>
                </a>
            </div>
            <nav>
                <ul>
                    <li class="no-submenu">
                        <a href="#">Home</a>
                    </li>
                    <li class="no-submenu">
                        <a href="#">About</a>
                    </li>
                    <li class="no-submenu">
                        <a href="#">Industries</a>
                    </li>
                    <li class="has-submenu">
                        <a>Services</a>
                        <ul>
                            <li class="sub">
                            <div class="inner">
                                <img src="https://i.ibb.co/hHs6h6r/15032148271582884281-1.png" alt="15032148271582884281-1" border="0">
                            </div>
                                <a href="">Procurement & Sourcing</a>
                                <a href="" class="absolute-a"></a>
                            </li>
                            <li class="sub">
                            <div class="inner">
                                <img src="https://i.ibb.co/hHs6h6r/15032148271582884281-1.png" alt="15032148271582884281-1" border="0">
                            </div>
                                <a href="">Management Consultancy</a>
                                <a href="" class="absolute-a"></a>
                            </li>
                            <li class="sub">
                            <div class="inner">
                                <img src="https://i.ibb.co/hHs6h6r/15032148271582884281-1.png" alt="15032148271582884281-1" border="0">
                            </div>
                                <a href="">E-Transformation</a>
                                <a href="" class="absolute-a"></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            </div>
            <div class="header-btn">
                <a class="btn-main btn-blue-background">Contact Us</a>
            </div>
            <nav class="mobile">
                <div class="mobile-menu">
                    <span class="nav-icon"></span>
                </div>
            </nav>
    </div>
</header>-->