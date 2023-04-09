<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="simple-text">
    <?php if (isset($title) && trim($title) != "") { ?>
    <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
    <?php } ?>
    <?php if (isset($desc_1) && trim($desc_1) != "") { ?>
    <p class="fadeup"><?php echo $desc_1; ?></p>
    <?php } ?>
</div>