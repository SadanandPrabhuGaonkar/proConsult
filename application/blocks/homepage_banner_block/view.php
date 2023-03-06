<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<section class="homepageBanner">
    <div class="left">
        <div class="left-inner">
			<?php if (isset($title) && trim($title) != "") { ?>
            <h1 class="text-title"><span><?php echo h($title); ?></span></h1>
			<?php } ?>
			<?php if (isset($tagline) && trim($tagline) != "") { ?>
            <h3 class="red js-chars-reveal-late"><?php echo h($tagline); ?></h3>
			<?php } ?>
			<?php if (isset($desc_1) && trim($desc_1) != "") { ?>
            <p class="small blue fadeuplate"><?php echo h($desc_1); ?></p>
			<?php } ?>
            <div class="banner-buttons fadeuplate" class="note">
				<?php
				if (trim($btnone_URL) != "") { ?>
					<?php
					$btnone_Attributes = [];
					$btnone_Attributes['href'] = $btnone_URL;
					$btnone_AttributesHtml = join(' ', array_map(function ($key) use ($btnone_Attributes) {
						return $key . '="' . $btnone_Attributes[$key] . '"';
					}, array_keys($btnone_Attributes)));
					echo sprintf('<a class="btn-main btn-blue-background home-btns" %s>%s</a>', $btnone_AttributesHtml, $btnone_Title); ?><?php
				} ?>
				<?php
				if (trim($btntwo_URL) != "") { ?>
					<?php
					$btntwo_Attributes = [];
					$btntwo_Attributes['href'] = $btntwo_URL;
					$btntwo_AttributesHtml = join(' ', array_map(function ($key) use ($btntwo_Attributes) {
						return $key . '="' . $btntwo_Attributes[$key] . '"';
					}, array_keys($btntwo_Attributes)));
					echo sprintf('<a class="btn-main btn-trans-background home-btns" %s>%s</a>', $btntwo_AttributesHtml, $btntwo_Title); ?><?php
				} ?>
            </div>
        </div>
        <div class="swiper-pagination fadeuplate"></div>
    </div>
    <div class="right reveallate">
    <div class="swiper-container slider bannerSlider">
        <div class="swiper-wrapper">
		<?php if (!empty($slideimages_items)) { ?>
    	<?php foreach ($slideimages_items as $slideimages_item_key => $slideimages_item) { ?>
            <div class="swiper-slide">
			<?php if ($slideimages_item["img"]) { ?>
			<img src="<?php echo $slideimages_item["img"]->getURL(); ?>" alt="<?php echo $slideimages_item["img"]->getTitle(); ?>"/>
			<?php } ?>
            </div>
			<?php } ?>
			<?php } ?>
        </div>
    </div>
    </div>
</section>




<!-- <section class="homepageBanner">
    <div class="left">
        <div class="left-inner">
            <h1 class="text-title"><span>ProConsult</span></h1>
            <h3 class="red js-chars-reveal-late">“Maximise Your Network”</h3>
            <p class="small blue fadeuplate">Procurement & Sourcing Services, Management Consultancy and E-Transformation Services</p>
            <div class="banner-buttons fadeuplate" class="note">
                <a href="#" class="btn-main btn-blue-background home-btns">About Us</a>
                <a href="#" class="btn-main btn-trans-background home-btns">Get In Touch</a>
            </div>
        </div>
        <div class="swiper-pagination fadeuplate"></div>
    </div>
    <div class="right reveallate">
    <div class="swiper-container slider bannerSlider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/11/200/300" alt="Banner Images">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/12/200/300" alt="Banner Images">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/13/200/300" alt="Banner Images">
            </div>
        </div>
    </div>
    </div>
</section> -->