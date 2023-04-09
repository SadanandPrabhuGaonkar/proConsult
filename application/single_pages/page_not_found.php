<?php
defined('C5_EXECUTE') or die("Access Denied.");
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
?>

<section class="thank_you common-padding">
    <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni1"/>
    <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni2"/>
    <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni3"/>
    <h1>404</h1>
    <p>This page does not exist</p>
    <a href="<?php echo View::url('/'); ?>" class="btn-main btn-blue-background fadeuplate">Go back to homepage</a>
</section>
