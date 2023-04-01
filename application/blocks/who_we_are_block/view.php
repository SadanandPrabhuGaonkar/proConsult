<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<section class="whoWeAre common-padding">
    <div class="above-sec">
    <?php if (isset($title) && trim($title) != "") { ?>
        <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
    <?php } ?>
    <?php if (isset($desc_1) && trim($desc_1) != "") { ?>
        <p class="fadeup"><?php echo h($desc_1); ?></p>
        <?php } ?>
    </div>
    <div class="below-sec">
        <div class="fadeup">
        <?php if (isset($content) && trim($content) != "") { ?>
        <?php echo $content; ?><?php } ?>
        </div>
        <div class="whoWeAreImage reveal">
        <?php if ($image) { ?><img src="<?php echo $image->getURL(); ?>" alt="<?php echo h($title); ?>"/><?php } ?>
        </div>
    </div>
</section>











<!-- <section class="whoWeAre common-padding">
    <div class="above-sec">
        <h2 class="js-chars-reveal">WHO WE ARE</h2>
        <p class="fadeup">Proudly Indian, ProConsult is a service oriented company established two years ago by ISBTian with a vision to create a first class integrated services structure and other business solutions.</p>
    </div>
    <div class="below-sec">
        <div class="fadeup">
        <p> While various services are offered our core business is the Procurement/Sourcing & E-transformation Consulting services that puts the best management know-how, focused on Collaborative partnership that deliver sustainable result, efficient logistic follow-up to work for you and your company needs.<br><br>
        ProConsult is led by an experienced Procurement and E-transformation expert with hands-on managing Complex projects in Marine, Offshore and Oil & Gas in India, Far East as well as middle east and has a dedicated team of experts working around the clock to make sure that our customers get the best contacts, Strategic information, IT solutions, business process management which they need in order to become more profitable, better informed and competitive for all their business cycle.</p>
        </div>
        <div class="whoWeAreImage reveal">
        <img src="https://i.ibb.co/PCPsjzN/Photo.png" alt="title">
        </div>
    </div>
</section> -->