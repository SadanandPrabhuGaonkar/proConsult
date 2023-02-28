<?php
defined('C5_EXECUTE') or die("Access Denied.");
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
?>

<section class="thank_you common-padding">
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni3"/>
    <h1 class="text-title">404</h1>
    <p class="js-chars-reveal-late">This page does not exist</p>
    <a href="<?php echo View::url('/'); ?>" class="btn-main btn-blue-background fadeuplate">Go back to homepage</a>
</section>
