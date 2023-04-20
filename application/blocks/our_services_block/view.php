<?php defined("C5_EXECUTE") or die("Access Denied."); 
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
?>


<section class="services common-padding">
    <div class="title">
    <?php if (isset($title) && trim($title) != "") { ?>
    <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
    <?php } ?>
    <?php if (isset($desc_1) && trim($desc_1) != "") { ?>
    <p class="fadeup"><?php echo h($desc_1); ?></p>
    <?php } ?>
    </div>
    <div class="service-details">
    <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni1"/>
    <img src="<?php echo $themePath; ?>/dist/images/Union.png" alt="union" class="uni2"/>
    <?php if (!empty($services_items)) { ?>
    <?php foreach ($services_items as $services_item_key => $services_item) { ?>
        <div class="service-card fadeup">
            <div class="image-card">
            <?php
            if (trim($services_item["btn_URL"]) != "") { ?>
                <?php
                $services_itembtn_Attributes = [];
                $services_itembtn_Attributes['href'] = $services_item["btn_URL"];
                $services_item["btn_AttributesHtml"] = join(' ', array_map(function ($key) use ($services_itembtn_Attributes) {
                    return $key . '="' . $services_itembtn_Attributes[$key] . '"';
                }, array_keys($services_itembtn_Attributes)));
                echo sprintf('<a class="absul" %s>%s</a>', $services_item["btn_AttributesHtml"], $services_item["btn_Title"]); ?><?php
            } ?>
            <?php if ($services_item["img"]) { ?>
            <img src="<?php echo $services_item["img"]->getURL(); ?>" alt="<?php echo $services_item["img"]->getTitle(); ?>"/><?php } ?>
            <div class="content">
            <?php if (isset($services_item["descservice"]) && trim($services_item["descservice"]) != "") { ?>
                <p><?php echo ($services_item["descservice"]); ?></p>
                <?php } ?>
            </div>
            </div>
            <?php if (isset($services_item["servicetitle"]) && trim($services_item["servicetitle"]) != "") { ?>
            <h4><?php echo h($services_item["servicetitle"]); ?></h4>
            <?php } ?>
            <?php
            if (trim($services_item["btn_URL"]) != "") { ?>
                <?php
                $services_itembtn_Attributes = [];
                $services_itembtn_Attributes['href'] = $services_item["btn_URL"];
                $services_item["btn_AttributesHtml"] = join(' ', array_map(function ($key) use ($services_itembtn_Attributes) {
                    return $key . '="' . $services_itembtn_Attributes[$key] . '"';
                }, array_keys($services_itembtn_Attributes)));
                echo sprintf('<a %s>%s</a>', $services_item["btn_AttributesHtml"], $services_item["btn_Title"]); ?><?php
            } ?>
        </div>
    <?php } ?>
    <?php } ?>
    </div>
</section>








<!-- <section class="services common-padding">
    <div class="title">
    <h3 class="js-chars-reveal">Our Services</h3>
    <p class="fadeup">ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services to their customer base.</p>
    </div>
    <div class="service-details">
    <img src="/dist/images/union.png" alt="union" class="uni1"/>
    <img src="/dist/images/union.png" alt="union" class="uni2"/>
        <div class="service-card fadeup">
            <div class="image-card">
            <a href="#" class="absul"></a>
            <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            <div class="content">
                <p>ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services.</p>
            </div>
            </div>
            <h4>Procurement & Sourcing</h4>
            <a href="#">Continue Reading</a>
        </div>
        <div class="service-card fadeup">
            <div class="image-card">
            <a href="#" class="absul"></a>
            <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            <div class="content">
                <p>ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services.</p>
            </div>
            </div>
            <h4>Management Consultancy</h4>
            <a href="#">Continue Reading</a>
        </div>
        <div class="service-card fadeup">
            <div class="image-card">
            <a href="#" class="absul"></a>
            <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            <div class="content">
                <p>ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services.</p>
            </div>
            </div>
            <h4>E-Transformation</h4>
            <a href="#">Continue Reading</a>
        </div>
    </div>
</section> -->