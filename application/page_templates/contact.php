<?php defined('C5_EXECUTE') or die("Access Denied.");

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
?>

<?php $a = new Area("Contact Content Area"); $a->display($c); ?>