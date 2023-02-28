<?php defined('C5_EXECUTE') or die("Access Denied.");
    $site = Config::get('concrete.site');
    $themePath = $this->getThemePath();
?>
    <div class="push"></div> <!-- Push div for making fixed footer -->

</div> <!-- closing of wrapper div from header_top.php -->

<footer>
    <div class="common-padding-side">
        <div class="row-1">
            <img class="logo" src="<?php echo $themePath; ?>/dist/images/footerLogo.svg" alt="<?php echo $site; ?>"/>
            <div class="links">
                <a href="#">About</a>
                <a href="#">Industries</a>
                <a href="#">Services</a>
            </div>
            <a href="#" class="linkedin">
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
    <span class="fa fa-chevron-up" aria-hidden="true"></span>
</div>

<!-- For Landscape Alert -->
<div class="landscape-alert">
    <p>For better web experience, please use the website in portrait mode</p>
</div>

<?php $this->inc('elements/footer_bottom.php');?>
