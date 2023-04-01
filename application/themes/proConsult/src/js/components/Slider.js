import Swiper from "swiper/swiper-bundle";

export default class Sliders {
    constructor() {
      this.heroSlider = ".bannerSlider";
      this.whatWeOfferSlider = ".what_we_offer";
      this.coreValuesSlider = ".corevalues";
      this.bindEvents();
    }
  
    bindEvents = () => {
      if (document.querySelectorAll(this.heroSlider).length) {
        this.heroSliderInit();
      }
      if (document.querySelectorAll(this.whatWeOfferSlider).length) {
        this.whatWeOfferInit();
      }
      if (document.querySelectorAll(this.coreValuesSlider).length) {
        this.coreValuesInit();
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

  coreValuesInit = () => {

    var coreValues = new Swiper ('.coreValuesSlider', {
      slidesPerView: 1,
      navigation: {
        nextEl: ".title .swiper-button-next",
        prevEl: ".title .swiper-button-prev",
      },
      breakpoints: {
       767: {
        slidesPerView: 2,
        spaceBetween: 40,
        },
        1200: {
          slidesPerView: 3,
          spaceBetween: 70,
        },
      },
      });

  };

  }