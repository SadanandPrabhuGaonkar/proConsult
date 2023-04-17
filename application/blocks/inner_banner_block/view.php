<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<section class="PageBanner">
    <div class="swiper-container bannerSliderInner">
        <div class="swiper-wrapper">
        <?php if (!empty($bannerimgs_items)) { ?>
        <?php foreach ($bannerimgs_items as $bannerimgs_item_key => $bannerimgs_item) { ?>
            <div class="swiper-slide">
            <?php if ($bannerimgs_item["imgs"]) { ?>
            <img src="<?php echo $bannerimgs_item["imgs"]->getURL(); ?>" alt="<?php echo $title; ?>"/><?php } ?>
            </div>
            <?php } ?><?php } ?>
        </div>
    </div>

    <?php if (isset($type) && trim($type) != "") { ?>
    <div class="Typelabel">
        <h3><?php echo $type; ?></h3>
    </div>
    <?php } ?>
    <?php if (isset($title) && trim($title) != "") { ?>
    <h1><?php echo $title; ?></h1>
    <?php } ?>
    <?php
    if (isset($stack) && !empty($stack)) { ?>
        <?php foreach ($stack as $stack_stack) {
            $stack_stack->display();
        } ?><?php
    } ?>
</section>