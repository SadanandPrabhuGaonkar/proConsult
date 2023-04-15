<?php defined('C5_EXECUTE') or die("Access Denied.");
    $site = Config::get('concrete.site');
    $themePath = $this->getThemePath();
?>
    <div class="push"></div> <!-- Push div for making fixed footer -->

</div> <!-- closing of wrapper div from header_top.php -->

<footer>
    <div class="common-padding-side">
        <div class="row-1">
            <img class="logo" src="<?php echo $themePath; ?>/dist/images/logo.svg" alt="<?php echo $site; ?>"/>
            <div class="links">
                <?php $stack = Stack::getByName('Footer Links'); $stack && $stack->display(); ?>
            </div>
            <a href="<?php $stack = Stack::getByName('Footer Linkedin Link'); $stack && $stack->display(); ?>" class="linkedin" target="_blank">
            <img src="<?php echo $themePath; ?>/dist/images/linkedInFooter.svg" alt="<?php echo $site; ?>"/>
            </a>
        </div>
        <div class="row-2">
            <p>Copyright © <?php echo date("Y"); ?> ProConsult</p>
        </div>
    </div>
</footer>

<!-- Go to top button -->
<div id="gotoTop">
<svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1.5625 4H8.4375" stroke="#4D5053" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5.625 1.1875L8.4375 4L5.625 6.8125" stroke="#4D5053" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

</div>

<!-- For Landscape Alert -->
<div class="landscape-alert">
    <p>For better web experience, please use the website in portrait mode</p>
</div>

<?php $this->inc('elements/footer_bottom.php');?>
