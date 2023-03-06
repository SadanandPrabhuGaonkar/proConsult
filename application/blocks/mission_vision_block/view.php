<?php defined("C5_EXECUTE") or die("Access Denied."); ?>


<section class="tabs vision-mission common-padding">
  <div id="tabs-content" class="main-content fadeup">
  <?php if (isset($mission) && trim($mission) != "") { ?>
    <div id="tab1" class="tab-content">
      <p><?php echo $mission; ?></p>
    </div>
    <?php } ?>
    <div id="tab2" class="tab-content">
    <?php if (isset($vision) && trim($vision) != "") { ?>
      <p><?php echo $vision; ?></p>
      <?php } ?>
    </div>
    <div id="tab3" class="tab-content">
    <?php if (isset($objective) && trim($objective) != "") { ?>
      <p><?php echo $objective; ?></p>
      <?php } ?>
    </div>
  </div>
  <ul id="tabs-nav" class="tabs-navigation fadeup">
    <li class=""><a href="#tab1">Mission</a></li>
    <li class=""><a href="#tab2">Vision</a></li>
    <li class=""><a href="#tab3">Objective</a></li>
  </ul>
</section>




<!-- <section class="tabs vision-mission common-padding">
  <div id="tabs-content" class="main-content fadeup">
    <div id="tab1" class="tab-content">
      <p>Committed to provide a stress-free business experience with superior services that caters as our customers “individual and/or corporate needs ... always conveying the Constant and Never Ending Improvement” spirit mixed with passion for excellence and exceeds client expectations.</p>
    </div>
    <div id="tab2" class="tab-content">
      <p>Committed to provide a stress-free business experience with superior services that caters as our customers “individual and/or corporate needs ... always conveying the Constant and Never Ending Improvement” spirit mixed with passion for excellence and exceeds client expectations.</p>
    </div>
    <div id="tab3" class="tab-content">
      <p>Committed to provide a stress-free business experience with superior services that caters as our customers “individual and/or corporate needs ... always conveying the Constant and Never Ending Improvement” spirit mixed with passion for excellence and exceeds client expectations.</p>
    </div>
  </div>
  <ul id="tabs-nav" class="tabs-navigation fadeup">
    <li class=""><a href="#tab1">Mission</a></li>
    <li class=""><a href="#tab2">Vision</a></li>
    <li class=""><a href="#tab3">Objective</a></li>
  </ul>
</section> -->