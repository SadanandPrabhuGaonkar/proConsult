
<?php defined("C5_EXECUTE") or die("Access Denied."); 
$themePath = $this->getThemePath();?>


<section class="contact_us common-padding">
  <div class="left">
    <div>
    <?php if (isset($title) && trim($title) != "") { ?>
      <h3 class="js-chars-reveal"><?php echo h($title); ?></h3>
      <?php } ?>
      <?php if (isset($subtitle) && trim($subtitle) != "") { ?>
      <p class="fadeup"><?php echo h($subtitle); ?></p><?php } ?>
      <?php if (isset($contentArea) && trim($contentArea) != "") { ?>
    <?php echo $contentArea; ?><?php } ?>
      <div class="navs fadeup">
      <?php if (isset($number) && trim($number) != "") { ?>
      <a href="tel:<?php echo h($number); ?>" target="_blank">
      <span><img src="<?php echo $themePath; ?>/dist/images/PhoneCall.svg" alt="call"></span>  
      <?php echo h($number); ?></a>
      <?php } ?>
      <?php if (isset($email) && trim($email) != "") { ?>
      <a href="mailto:<?php echo h($email); ?>" target="_blank">
      <span><img src="<?php echo $themePath; ?>/dist/images/email.svg" alt="call"></span>  
      <?php echo h($email); ?></a>
      <?php } ?>
      
      </div>
    </div>
    <?php if (isset($linkedin) && trim($linkedin) != "") { ?>
    <div class="fadeup">
      <h4>Connect with us</h4>
      <a href="<?php echo h($linkedin); ?>" target="_blank">
        <img class="social" src="<?php echo $themePath; ?>/dist/images/linkedin-blue.svg" alt="linkedin">
      </a>
    </div>
    <?php } ?>
  </div>
  <div class="right fadeup">
  <?php
    if (isset($stack) && !empty($stack)) { ?>
        <?php foreach ($stack as $stack_stack) {
            $stack_stack->display();
        } ?><?php
    } ?>
  </div>
</section>