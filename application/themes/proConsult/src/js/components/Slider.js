import Swiper from "swiper/swiper-bundle";

export default class Sliders {
    constructor() {
      this.heroSlider = ".bannerSlider";
      this.whatWeOfferSlider = ".what_we_offer";
      this.BannerSlider = ".bannerSliderInner";
      this.bindEvents();
    }
  
    bindEvents = () => {
      if (document.querySelectorAll(this.heroSlider).length) {
        this.heroSliderInit();
      }
      if (document.querySelectorAll(this.whatWeOfferSlider).length) {
        this.whatWeOfferInit();
      }
      if (document.querySelectorAll(this.BannerSlider).length) {
        this.BannerSliderInit();
      }
    };
  
    heroSliderInit = () => {

        var HomepageSlider = new Swiper ('.bannerSlider', {
            slidesPerView: 1,
            loop: true,
            speed: 1000,
            autoplay: {
              delay: 4000,
              disableOnInteraction: false,
            },
            effect: 'fade',
            pagination: {
                el: '.left .swiper-pagination',
                clickable: true
            },
        });
  
    };

    whatWeOfferInit = () => {

      var Whatweoffer = new Swiper ('.whatWeDoSlider', {
        slidesPerView: 1,
        loop: true,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
          pagination: {
              el: '.swiper-btns .swiper-pagination',
              clickable: true
          },
          navigation: {
            nextEl: ".swiper-nav .swiper-button-next",
            prevEl: ".swiper-nav .swiper-button-prev",
          },
      });

  };

  BannerSliderInit = () => {
    var bannerSliderMain = new Swiper ('.bannerSliderInner', {
      slidesPerView: 1,
      loop: true,
      speed: 1000,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      effect: 'fade',
      });
  };

  }

