<?php defined("C5_EXECUTE") or die("Access Denied."); 
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
?>

<section class="service-quality services common-padding">
    <div class="title">
    <?php if (isset($title) && trim($title) != "") { ?>
        <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
        <?php } ?>
        <?php if (isset($desc_1) && trim($desc_1) != "") { ?>
        <div class="content-up">
        <p class="fadeup"><?php echo h($desc_1); ?></p>
        </div>
        <?php } ?>
    </div>
    <div class="service-details client">
      <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
      <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
      <?php if (!empty($txts_items)) { ?>
    <?php foreach ($txts_items as $txts_item_key => $txts_item) { ?><?php if (isset($txts_item["content"]) && trim($txts_item["content"]) != "") { ?>
      <div class="card fadeup">
      <?php echo $txts_item["content"]; ?>
        </div>
        <?php } ?><?php } ?><?php } ?>
    </div>
</section>





<!-- <section class="service-quality services common-padding">
    <div class="title">
        <h3 class="js-chars-reveal">Our Services</h3>
        <p class="fadeup">ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services to their customer base.</p>
    </div>
    <div class="service-details client">
      <img src="<//?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
      <img src="<//?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
      <div class="card fadeup">
        <p>Enhancing client’s profitability by improving the management of their purchased goods and services is achieved through a unique combination of:</p>
        <ul>
          <li>Team of outstanding procurement professionals.</li>
          <li>High-level procurement expertise.</li>
          <li>Experience in improving procurement performance of companies.</li>
          <li>Experience in a broad range of supply markets.</li>
          <li>Tried and tested innovative procurement methodologies.</li>
          </ul>
        </div>
      <div class="card fadeup">
        <p>Enhancing client’s profitability by improving the management of their purchased goods and services is achieved through a unique combination of:</p>
        <ul>
          <li>Team of outstanding procurement professionals.</li>
          <li>High-level procurement expertise.</li>
          <li>Experience in improving procurement performance of companies.</li>
        </ul>
      </div>
    </div>
</section> -->