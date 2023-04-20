<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="simple-text box">
    <?php if (isset($title) && trim($title) != "") { ?>
    <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
    <?php } ?>
    <?php if (isset($Desc_1) && trim($Desc_1) != "") { ?>
    <?php echo $Desc_1; ?><?php } ?>
</div>