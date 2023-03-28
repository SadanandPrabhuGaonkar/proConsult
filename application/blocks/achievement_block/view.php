<?php defined("C5_EXECUTE") or die("Access Denied."); 
$themePath = $this->getThemePath();
?>

<section class="goals common-padding <?php if ($selecttype == 2 || $selecttype == 3) {?>dark<?php } ?>">
<?php if (isset($title) && trim($title) != "") { ?>
  <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
  <?php } ?>
  <?php if (isset($desc_1) && trim($desc_1) != "") { ?>
    <div class="fadeup fadeup-z">
  <?php echo $desc_1; ?>
  </div>
  <?php } ?>
  <?php if($selecttype == "3") {?>
    <?php if ($bgimage) { ?>
  <img src="<?php echo $bgimage->getURL(); ?>" alt="<?php echo h($title); ?>" class="bg"/>
  <?php } ?>
  <img src="<?php echo $themePath; ?>/dist/images/overlay.png" alt="overlay" class="overlay"/>
  <?php } ?>
  <div class="pointers fadeup">
  <?php if (!empty($points_items)) { ?>
    <?php foreach ($points_items as $points_item_key => $points_item) { ?>
    <div class="single-pointer">
      <img class="tick" src="<?php echo $themePath; ?>/dist/images/tick.svg" alt="tick">
      <?php if (isset($points_item["textpoints"]) && trim($points_item["textpoints"]) != "") { ?>
      <p><?php echo h($points_item["textpoints"]); ?></p>
      <?php } ?>
    </div>
    <?php } ?><?php } ?>
  </div>
</section>