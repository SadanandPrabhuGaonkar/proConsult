<?php defined('C5_EXECUTE') or die("Access Denied.");
$htmlHelper = Loader::helper('html');
?>
	<?php
		// $this->addFooterItem($htmlHelper->javascript('all.js'));
		// $this->addFooterItem($htmlHelper->javascript('custom.js'));
		//$this->addFooterItem($htmlHelper->javascript('//maps.googleapis.com/maps/api/js?key='));
		//$this->addFooterItem($htmlHelper->javascript('map.js'));
	?>

	<!-- Uncomment below if you need to add google captcha (also in custom.js)
	<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
	-->

	<!-- core js files -->
	<?php View::element('footer_required'); ?>

