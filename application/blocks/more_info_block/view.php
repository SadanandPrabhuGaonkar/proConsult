<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<section class="contact common-padding">
	<?php if (isset($title) && trim($title) != "") { ?>
    <h2 class="js-chars-reveal"><?php echo h($title); ?></h2>
	<?php } ?>
	<?php if (isset($desc_1) && trim($desc_1) != "") { ?>
    <p class="fadeup"><?php echo h($desc_1); ?></p>
	<?php } ?>
	<?php
	if (trim($btn_URL) != "") { ?>
		<?php
		$btn_Attributes = [];
		$btn_Attributes['href'] = $btn_URL;
		$btn_AttributesHtml = join(' ', array_map(function ($key) use ($btn_Attributes) {
			return $key . '="' . $btn_Attributes[$key] . '"';
		}, array_keys($btn_Attributes)));
		echo sprintf('<a class="btn-main btn-trans-background-white box" %s>%s</a>', $btn_AttributesHtml, $btn_Title); ?><?php
	} ?>
</section>




<!-- <section class="contact common-padding">
    <h2 class="js-chars-reveal">Want to partner with ProConsult?</h2>
    <p class="fadeup">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
    <a  href="#" class="btn-main btn-trans-background-white fadeup">Get In Touch</a>
</section> -->