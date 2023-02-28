<?php defined('C5_EXECUTE') or die("Access Denied.");

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();

?>

<section class="PageBanner">
    <img src="https://picsum.photos/id/10/200/300" alt="Banner Images" class="banner-image">
    <div class="Typelabel fadeuplate">
        <h3>Core Business</h3>
    </div>
    <h1 class="text-title">About US</h1>
    <?php $stack = Stack::getByName('Breadcrumb Trail'); $stack && $stack->display(); ?>
</section>


<section class="what_we_offer common-padding">
  <div class="left_div">
    <h3 class="js-chars-reveal">What we offer</h3>
    <p class="fadeup">ProConsult offers expertise in Procurement/Sourcing advisory, Quality Management, Project Procurement management, E-Transformation consulting and Technical Services.<br><br>We are able to ensure best value transactions and intermediation as we base our decisions on a constantly updated database of contacts, suppliers in fulfilling our customer orders.<br>
    Client Groups include and not limited to:</p>
    <div class="swiper-container slider whatWeDoSlider">
        <div class="swiper-btns fadeup">
          <div class="swiper-pagination"></div>
          <div class="swiper-nav">
          <div class="swiper-button-prev">
            </div>
            <div class="swiper-button-next">
            </div>
          </div>
        </div>
        <div class="swiper-wrapper fadeup">
            <div class="swiper-slide">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="15" cy="15" r="15" fill="#263790" fill-opacity="0.2"/>
            <path d="M15.2941 7.02734C10.7532 7.02734 7.05884 10.6544 7.05884 15.1125C7.05884 19.5705 10.7532 23.1976 15.2941 23.1976C19.835 23.1976 23.5294 19.5705 23.5294 15.1125C23.5294 10.6544 19.835 7.02734 15.2941 7.02734ZM19.58 12.4028L14.2588 18.6221C14.2004 18.6903 14.1278 18.7455 14.0458 18.7838C13.9638 18.8221 13.8744 18.8426 13.7837 18.844H13.773C13.6842 18.844 13.5964 18.8257 13.5153 18.7902C13.4342 18.7547 13.3616 18.7028 13.3022 18.638L11.0217 16.1503C10.9638 16.09 10.9187 16.0189 10.8892 15.9413C10.8596 15.8636 10.8462 15.781 10.8496 15.6981C10.8531 15.6153 10.8734 15.534 10.9093 15.459C10.9453 15.384 10.9961 15.3168 11.0588 15.2614C11.1216 15.2059 11.195 15.1633 11.2748 15.1361C11.3545 15.109 11.439 15.0977 11.5233 15.103C11.6075 15.1083 11.6898 15.1301 11.7654 15.1671C11.8409 15.2041 11.9082 15.2556 11.9632 15.3185L13.7563 17.2744L18.61 11.6028C18.7189 11.4792 18.8729 11.4027 19.0388 11.3897C19.2047 11.3767 19.3692 11.4284 19.4966 11.5335C19.624 11.6385 19.7042 11.7887 19.7198 11.9513C19.7355 12.114 19.6852 12.2762 19.58 12.4028Z" fill="#263790"/>
            </svg>
                <p>Marine</p>
            </div>
            <div class="swiper-slide">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="15" cy="15" r="15" fill="#263790" fill-opacity="0.2"/>
            <path d="M15.2941 7.02734C10.7532 7.02734 7.05884 10.6544 7.05884 15.1125C7.05884 19.5705 10.7532 23.1976 15.2941 23.1976C19.835 23.1976 23.5294 19.5705 23.5294 15.1125C23.5294 10.6544 19.835 7.02734 15.2941 7.02734ZM19.58 12.4028L14.2588 18.6221C14.2004 18.6903 14.1278 18.7455 14.0458 18.7838C13.9638 18.8221 13.8744 18.8426 13.7837 18.844H13.773C13.6842 18.844 13.5964 18.8257 13.5153 18.7902C13.4342 18.7547 13.3616 18.7028 13.3022 18.638L11.0217 16.1503C10.9638 16.09 10.9187 16.0189 10.8892 15.9413C10.8596 15.8636 10.8462 15.781 10.8496 15.6981C10.8531 15.6153 10.8734 15.534 10.9093 15.459C10.9453 15.384 10.9961 15.3168 11.0588 15.2614C11.1216 15.2059 11.195 15.1633 11.2748 15.1361C11.3545 15.109 11.439 15.0977 11.5233 15.103C11.6075 15.1083 11.6898 15.1301 11.7654 15.1671C11.8409 15.2041 11.9082 15.2556 11.9632 15.3185L13.7563 17.2744L18.61 11.6028C18.7189 11.4792 18.8729 11.4027 19.0388 11.3897C19.2047 11.3767 19.3692 11.4284 19.4966 11.5335C19.624 11.6385 19.7042 11.7887 19.7198 11.9513C19.7355 12.114 19.6852 12.2762 19.58 12.4028Z" fill="#263790"/>
            </svg>
                <p>Offshore</p>
            </div>
            <div class="swiper-slide">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="15" cy="15" r="15" fill="#263790" fill-opacity="0.2"/>
            <path d="M15.2941 7.02734C10.7532 7.02734 7.05884 10.6544 7.05884 15.1125C7.05884 19.5705 10.7532 23.1976 15.2941 23.1976C19.835 23.1976 23.5294 19.5705 23.5294 15.1125C23.5294 10.6544 19.835 7.02734 15.2941 7.02734ZM19.58 12.4028L14.2588 18.6221C14.2004 18.6903 14.1278 18.7455 14.0458 18.7838C13.9638 18.8221 13.8744 18.8426 13.7837 18.844H13.773C13.6842 18.844 13.5964 18.8257 13.5153 18.7902C13.4342 18.7547 13.3616 18.7028 13.3022 18.638L11.0217 16.1503C10.9638 16.09 10.9187 16.0189 10.8892 15.9413C10.8596 15.8636 10.8462 15.781 10.8496 15.6981C10.8531 15.6153 10.8734 15.534 10.9093 15.459C10.9453 15.384 10.9961 15.3168 11.0588 15.2614C11.1216 15.2059 11.195 15.1633 11.2748 15.1361C11.3545 15.109 11.439 15.0977 11.5233 15.103C11.6075 15.1083 11.6898 15.1301 11.7654 15.1671C11.8409 15.2041 11.9082 15.2556 11.9632 15.3185L13.7563 17.2744L18.61 11.6028C18.7189 11.4792 18.8729 11.4027 19.0388 11.3897C19.2047 11.3767 19.3692 11.4284 19.4966 11.5335C19.624 11.6385 19.7042 11.7887 19.7198 11.9513C19.7355 12.114 19.6852 12.2762 19.58 12.4028Z" fill="#263790"/>
            </svg>
                <p>Oil And Gas</p>
            </div>
            <div class="swiper-slide">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="15" cy="15" r="15" fill="#263790" fill-opacity="0.2"/>
            <path d="M15.2941 7.02734C10.7532 7.02734 7.05884 10.6544 7.05884 15.1125C7.05884 19.5705 10.7532 23.1976 15.2941 23.1976C19.835 23.1976 23.5294 19.5705 23.5294 15.1125C23.5294 10.6544 19.835 7.02734 15.2941 7.02734ZM19.58 12.4028L14.2588 18.6221C14.2004 18.6903 14.1278 18.7455 14.0458 18.7838C13.9638 18.8221 13.8744 18.8426 13.7837 18.844H13.773C13.6842 18.844 13.5964 18.8257 13.5153 18.7902C13.4342 18.7547 13.3616 18.7028 13.3022 18.638L11.0217 16.1503C10.9638 16.09 10.9187 16.0189 10.8892 15.9413C10.8596 15.8636 10.8462 15.781 10.8496 15.6981C10.8531 15.6153 10.8734 15.534 10.9093 15.459C10.9453 15.384 10.9961 15.3168 11.0588 15.2614C11.1216 15.2059 11.195 15.1633 11.2748 15.1361C11.3545 15.109 11.439 15.0977 11.5233 15.103C11.6075 15.1083 11.6898 15.1301 11.7654 15.1671C11.8409 15.2041 11.9082 15.2556 11.9632 15.3185L13.7563 17.2744L18.61 11.6028C18.7189 11.4792 18.8729 11.4027 19.0388 11.3897C19.2047 11.3767 19.3692 11.4284 19.4966 11.5335C19.624 11.6385 19.7042 11.7887 19.7198 11.9513C19.7355 12.114 19.6852 12.2762 19.58 12.4028Z" fill="#263790"/>
            </svg>
                <p>Industrial Manufacturing</p>
            </div>
        </div>
    </div>
  </div>
  <div class="right_div reveal">
  <img src="https://i.ibb.co/J29zzhM/Photo.jpg" alt="Photo">
  </div>
</section>


<section class="tabs vision-mission common-padding">
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
</section>

<section class="service-quality services common-padding">
    <div class="title">
        <h3 class="js-chars-reveal">Our Services</h3>
        <p class="fadeup">ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services to their customer base.</p>
    </div>
    <div class="service-details client">
      <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
      <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
      <div class="card fadeup">
        <p>Enhancing client’s profitability by improving the management of their purchased goods and services is achieved through a unique combination of:</p>
        <ul>
          <li>Team of outstanding procurement professionals.</li>
          <li>High-level procurement expertise.</li>
          <li>Experience in improving procurement performance of companies.</li>
          <li>Experience in a broad range of supply markets.</li>
          <li>Tried and tested innovative procurement methodologies.</li>
        </div>
      </ul>
      <div class="card fadeup">
        <p>Enhancing client’s profitability by improving the management of their purchased goods and services is achieved through a unique combination of:</p>
        <ul>
          <li>Team of outstanding procurement professionals.</li>
          <li>High-level procurement expertise.</li>
          <li>Experience in improving procurement performance of companies.</li>
        </ul>
      </div>
    </div>
</section>

<section class="corevalues services common-padding">
    <div class="title">
        <h3 class="js-chars-reveal">Core Values</h3>
        <p class="fadeup">ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services to their customer base.</p>
        <div class="swipeNav fadeup">
        <div class="swiper-button-prev">
        </div>
        <div class="swiper-button-next">
        </div>
        </div>
    </div>
    <div class="service-details">
      <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
      <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
      <div class="swiper-container slider coreValuesSlider fadeup">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Integrity</h3>
                <p>Honour all commitments to our customers, employees and shareholders while conducting business with unwavering high standards of honesty, trust, professionalism and ethical behaviour.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Integrity</h3>
                <p>Honour all commitments to our customers, employees and shareholders while conducting business with unwavering high standards of honesty, trust, professionalism and ethical behaviour.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Quality</h3>
                <p>Put the interests of our customers first and be dedicated to providing an individualized business experience that assures customer satisfaction and earns their unwavering loyalty.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Integrity</h3>
                <p>Honour all commitments to our customers, employees and shareholders while conducting business with unwavering high standards of honesty, trust, professionalism and ethical behaviour.</p>
            </div>
            </div>
            <div class="swiper-slide">
              <img src="https://i.ibb.co/GWZpz6W/8250041411656683092-1.png" alt="8250041411656683092-1" border="0">
              <div class="content">
                <h3>Teamwork</h3>
                <p>Work as one cohesive team from the smallest unit to the Board of directors while developing and retaining leaders who continually raise the bar, provide direction, remove barriers and empower people to successfully achieve goals.</p>
            </div>
            </div>
        </div>
    </div>
    </div>
</section>


<section class="goals common-padding dark">
  <h3 class="js-chars-reveal">Strategic Sourcing</h3>
  <p class="fadeup">We ensure that the client’s delivery strategy, performance and business terms are properly captured in a contract that is realistic and enforceable.</p>
  <img src="https://i.ibb.co/0Q2517F/Rectangle-4461.jpg" alt="Rectangle-4461" class="bg">
  <img src="<?php echo $themePath; ?>/dist/images/overlay.png" alt="overlay" class="overlay"/>
  <div class="pointers fadeup">
    <div class="single-pointer">
      <img class="tick" src="<?php echo $themePath; ?>/dist/images/tick.svg" alt="tick">
      <p>Contract Optimization</p>
    </div>
    <div class="single-pointer">
      <img class="tick" src="<?php echo $themePath; ?>/dist/images/tick.svg" alt="tick">
      <p>Contract Optimization</p>
    </div>
    <div class="single-pointer">
      <img class="tick" src="<?php echo $themePath; ?>/dist/images/tick.svg" alt="tick">
      <p>Contract Optimization</p>
    </div>
    <div class="single-pointer">
      <img class="tick" src="<?php echo $themePath; ?>/dist/images/tick.svg" alt="tick">
      <p>Contract Optimization</p>
    </div>
  </div>
</section>


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