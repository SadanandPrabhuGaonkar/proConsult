<?php defined('C5_EXECUTE') or die("Access Denied.");

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
$this->inc('includes/banner.php'); 

?>

<?php $a = new Area("About Content Area"); $a->display($c); ?>


<section class="contact_us common-padding">
  <div class="left">
    <div>
      <h3 class="js-chars-reveal">Let’s get in touch!</h3>
      <p class="fadeup">Got questions about our Services? Our team is here to help. Contact us for quick and friendly support.</p>
      <div class="navs fadeup">
      <a href="#">
      <span><img src="<?php echo $themePath; ?>/dist/images/PhoneCall.svg" alt="call"></span>  
      +012 345 6789</a>
      <a href="#">
      <span><img src="<?php echo $themePath; ?>/dist/images/email.svg" alt="call"></span>  
      contact@proconsult.com</a>
      </div>
    </div>
    <div class="fadeup">
      <h4>Connect with us</h4>
      <img class="social" src="<?php echo $themePath; ?>/dist/images/linkedin-blue.svg" alt="linkedin">
    </div>
  </div>
  <div class="right fadeup">
  <?php $stack = Stack::getByName('Contact Form'); $stack && $stack->display(); ?>
  </div>
</section>