<?php defined('C5_EXECUTE') or die("Access Denied.");

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();

?>

<section class="homepageBanner">
    <div class="left">
        <div class="left-inner">
            <h1 class="text-title"><span>ProConsult</span></h1>
            <h3 class="red js-chars-reveal-late">“Maximise Your Network”</h3>
            <p class="small blue fadeuplate">Procurement & Sourcing Services, Management Consultancy and E-Transformation Services</p>
            <div class="banner-buttons fadeuplate" class="note">
                <a href="#" class="btn-main btn-blue-background home-btns">About Us</a>
                <a href="#" class="btn-main btn-trans-background home-btns">Get In Touch</a>
            </div>
        </div>
        <div class="swiper-pagination fadeuplate"></div>
    </div>
    <div class="right reveallate">
    <div class="swiper-container slider bannerSlider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/11/200/300" alt="Banner Images">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/12/200/300" alt="Banner Images">
            </div>
            <div class="swiper-slide">
                <img src="https://picsum.photos/id/13/200/300" alt="Banner Images">
            </div>
        </div>
    </div>
    </div>
</section>


<section class="marquee">
    <div class="marquee__inner" aria-hidden="true" ref="inner">
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
      <div class="marquee__part">
      Maximise Your Network &nbsp; &nbsp; &nbsp; &nbsp;
      </div>
    </div>
</section>


<section class="whoWeAre common-padding">
    <div class="above-sec">
        <h2 class="js-chars-reveal">WHO WE ARE</h2>
        <p class="fadeup">Proudly Indian, ProConsult is a service oriented company established two years ago by ISBTian with a vision to create a first class integrated services structure and other business solutions.</p>
    </div>
    <div class="below-sec">
        <p class="fadeup"> While various services are offered our core business is the Procurement/Sourcing & E-transformation Consulting services that puts the best management know-how, focused on Collaborative partnership that deliver sustainable result, efficient logistic follow-up to work for you and your company needs.<br><br>
        ProConsult is led by an experienced Procurement and E-transformation expert with hands-on managing Complex projects in Marine, Offshore and Oil & Gas in India, Far East as well as middle east and has a dedicated team of experts working around the clock to make sure that our customers get the best contacts, Strategic information, IT solutions, business process management which they need in order to become more profitable, better informed and competitive for all their business cycle.</p>
        <div class="whoWeAreImage reveal">
        <img src="https://i.ibb.co/PCPsjzN/Photo.png" alt="title">
        </div>
    </div>
</section>


<section class="services common-padding">
    <div class="title">
    <h3 class="js-chars-reveal">Our Services</h3>
    <p class="fadeup">ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services to their customer base.</p>
    </div>
    <div class="service-details">
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
        <div class="service-card fadeup">
            <div class="image-card">
            <a href="#" class="absul"></a>
            <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            <div class="content">
                <p>ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services.</p>
            </div>
            </div>
            <h4>Procurement & Sourcing</h4>
            <a href="#">Continue Reading</a>
        </div>
        <div class="service-card fadeup">
            <div class="image-card">
            <a href="#" class="absul"></a>
            <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            <div class="content">
                <p>ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services.</p>
            </div>
            </div>
            <h4>Management Consultancy</h4>
            <a href="#">Continue Reading</a>
        </div>
        <div class="service-card fadeup">
            <div class="image-card">
            <a href="#" class="absul"></a>
            <img src="https://picsum.photos/id/10/200/300" alt="Banner Images">
            <div class="content">
                <p>ProConsult aims to be a trustful, long-life partner to business entities interested in providing fast, high quality and personalized services.</p>
            </div>
            </div>
            <h4>E-Transformation</h4>
            <a href="#">Continue Reading</a>
        </div>
    </div>
</section>


<section class="contact common-padding">
    <h2 class="js-chars-reveal">Want to partner with ProConsult?</h2>
    <p class="fadeup">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
    <a  href="#" class="btn-main btn-trans-background-white fadeup">Get In Touch</a>
</section>

<section class="thank_you common-padding">
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni1"/>
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni2"/>
    <img src="<?php echo $themePath; ?>/dist/images/union.png" alt="union" class="uni3"/>
    <h1 class="text-title">Thank You</h1>
    <p class="js-chars-reveal-late">Your message has been submitted successfully</p>
    <a href="<?php echo View::url('/'); ?>" class="btn-main btn-blue-background fadeuplate">Go back to homepage</a>
</section>