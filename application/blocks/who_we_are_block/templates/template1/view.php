<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<section class="whoWeAre common-padding whoWeAreDark">
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