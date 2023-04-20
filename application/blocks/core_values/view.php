<?php defined("C5_EXECUTE") or die("Access Denied."); 
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
?>

    <section class="corevalues services common-padding">
    <div class="title">
        <?php if (isset($title) && trim($title) != "") { ?>
        <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
        <?php } ?>
        <div class="fadeup content-up">
        <?php if (isset($desc_1) && trim($desc_1) != "") { ?>
        <p><?php echo h($desc_1); ?></p>
        <?php } ?>
        </div>
        <div class="swipeNav fadeup">
        <div class="swiper-button-prev">
        </div>
        <div class="swiper-button-next">
        </div>
        </div>
    </div>
    <div class="service-details">
      <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni1"/>
      <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni2"/>
      <div class="swiper-container slider coreValuesSlider fadeup">
        <div class="swiper-wrappers">
        <?php if (!empty($slides_items)) { ?>
        <?php foreach ($slides_items as $slides_item_key => $slides_item) { ?>
            <div class="swiper-slide">
            <?php if ($slides_item["logo"]) { ?>
                <img src="<?php echo $slides_item["logo"]->getURL(); ?>" alt="<?php echo h($slides_item["title"]); ?>"/>
              <?php } ?>
              <div class="content">
              <?php if (isset($slides_item["title"]) && trim($slides_item["title"]) != "") { ?>
                <h3><?php echo h($slides_item["title"]); ?></h3>
                <?php } ?>
                <?php if (isset($slides_item["desc_1"]) && trim($slides_item["desc_1"]) != "") { ?>
                <p><?php echo ($slides_item["desc_1"]); ?></p>
                <?php } ?>
            </div>
            </div>
            <?php } ?><?php } ?>
        </div>
    </div>
    </div>
</section>






<!-- <section class="corevalues services common-padding">
    <div class="title">
        <h3 class="js-chars-reveal">Core Values</h3>
        <p class="fadeup">ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services to their customer base.</p>
        <div class="swipeNav fadeup">
        <div class="swiper-button-prev">
        </div>
        <div class="swiper-button-next">
        </div>
        </div>
    </div>
    <div class="service-details">
      <img src="<//?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
      <img src="<//?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
      <div class="swiper-container slider coreValuesSlider fadeup">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Integrity</h3>
                <p>Honour all commitments to our customers, employees and shareholders while conducting business with unwavering high standards of honesty, trust, professionalism and ethical behaviour.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Integrity</h3>
                <p>Honour all commitments to our customers, employees and shareholders while conducting business with unwavering high standards of honesty, trust, professionalism and ethical behaviour.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Quality</h3>
                <p>Put the interests of our customers first and be dedicated to providing an individualized business experience that assures customer satisfaction and earns their unwavering loyalty.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Integrity</h3>
                <p>Honour all commitments to our customers, employees and shareholders while conducting business with unwavering high standards of honesty, trust, professionalism and ethical behaviour.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Teamwork</h3>
                <p>Work as one cohesive team from the smallest unit to the Board of directors while developing and retaining leaders who continually raise the bar, provide direction, remove barriers and empower people to successfully achieve goals.</p>
            </div>
            </div>
        </div>
    </div>
    </div>
</section> -->