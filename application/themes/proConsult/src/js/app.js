import Header from './components/Header';
import Maps from './components/Maps';
import Slider from './components/Slider';
import Animation from './components/Animation';
import Select2_dropdown from './components/Select2';

export default new (class App {
  constructor() {
    this.setDomMap();
    this.previousScroll = 0;

      $.fn.isInViewport = function () {
          const elementTop = $(this).offset().top;
          const elementBottom = elementTop + $(this).outerHeight();
          const viewportTop = $(window).scrollTop();
          const viewportBottom = viewportTop + $(window).height();
          return elementBottom > viewportTop && elementTop < viewportBottom;
      };

    // dom ready shorthand
    $(() => {
      this.domReady();
    });

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        $('.init-overlay').fadeOut(500);
      }
    }, {passive: true});

    this.window.on('beforeunload', () => {
      $('.init-overlay').fadeIn(500);
    });

    this.window.on('load', () => {
      $('.init-overlay').fadeOut(500);
      $('.init-overlay').addClass('loaded');
    });
    this.captchaLoadMain = ".contact_us";
  }

  

  domReady = () => {
    this.initComponents();
    if (document.querySelectorAll(this.captchaLoadMain).length) {
      this.captchaLoad();
    }
    this.handleUserAgent();
    this.windowResize();
    this.bindEvents();
    this.handleSplashScreen();
  };
  

  initComponents = () => {
    new Header({
      header: this.header,
      htmlBody: this.htmlBody,
    });

    if (this.mapContainer.length) {
      new Maps({
        mapContainer: this.mapContainer,
      });
    }

    new Slider();
    new Select2_dropdown();
    new Animation();

  };

  captchaLoad = () => {
      $(window).on("scroll load", function(){
          if($('.formidable').isInViewport() && !$(".formidable").hasClass("formInview")) {
              $('.formidable').addClass('formInview');
          }
      });
  };

  setDomMap = () => {
    this.window = $(window);
    this.htmlNbody = $('body, html');
    this.html = $('html');
    this.htmlBody = $('body');
    this.siteLoader = $('.site-loader');
    this.header = $('header');
    this.siteBody = $('.site-body');
    this.footer = $('footer');
    this.gotoTop = $('#gotoTop');
    this.gRecaptcha = $('.g-recaptcha');
    this.wrapper = $('.wrapper');
    this.pushDiv = this.wrapper.find('.push');
    this.mapContainer = $('#map_canvas');
    this.inputs = $('input, textarea').not('[type="checkbox"], [type="radio"]');
  };

  bindEvents = () => {
    // Window Events
    this.window.resize(this.windowResize).scroll(this.windowScroll);

    // General Events
    const $container = this.wrapper;

    $container.on('click', '.disabled', () => false);

    // Specific Events
    this.gotoTop.on('click', () => {
      this.htmlNbody.animate({
        scrollTop: 0,
      });
    });

    this.inputs
      .on({
        focus: e => {
          const self = $(e.currentTarget);
          self.closest('.element').addClass('active');
        },
        blur: e => {
          const self = $(e.currentTarget);
          if (self.val() !== '') {
            self.closest('.element').addClass('active');
          } else {
            self.closest('.element').removeClass('active');
          }
        },
      })
      .trigger('blur');

    // Reload the current path when changing language instead of redirecting to landing page
    // Uncomment below and modify languages
    // $container.on('click', '.language-toggle', function(e) {
    //   e.preventDefault();
    //   const $this = $(this);
    //   const href = $this.attr('href');
    //   const isEnglish = href.indexOf('/ar') >= 0;
    //   const locArray = location.pathname.split('/');
    //   const indexOfIndex = locArray.indexOf('index.php');
    //   const isDev = indexOfIndex >= 0;
    //   const index = isDev ? indexOfIndex + 1 : 1;
    //   if(!isEnglish) {
    //     locArray = locArray.filter(item => item !== 'ar')
    //   }
    //   locArray.splice(index, 0, isEnglish ? 'ar' : '');
    //   const newHref = locArray.join('/').replace('//', '/');
    //   location.href = newHref;
    // });

    // Uncomment below if you need to add google captcha (also in includes/script.php)
    // => Make sure the SITEKEY is changed below
    // this.gRecaptcha.each((index, el) => {
    //   grecaptcha.render(el, {'sitekey' : '6LeB3QwUAAAAADQMo87RIMbq0ZnUbPShlwCPZDTv'});
    // });
  };

  windowResize = () => {
    this.screenWidth = this.window.width();
    this.screenHeight = this.window.height();

    // calculate footer height and assign it to wrapper and push/footer div
    if (this.pushDiv.length){
      this.footerHeight = this.footer.outerHeight();
      this.wrapper.css('margin-bottom', -this.footerHeight);
      this.pushDiv.height(this.footerHeight);
    }
  };

  windowScroll = () => {
    const topOffset = this.window.scrollTop();

    this.header.toggleClass('top', topOffset > 400);
    this.header.toggleClass('sticky', topOffset > 600);
    if (topOffset > this.previousScroll || topOffset < 500) {
      this.header.removeClass('sticky');
    } else if (topOffset < this.previousScroll) {
      this.header.addClass('sticky');
      // Additional checking so the header will not flicker
      if (topOffset > 250) {
        this.header.addClass('sticky');
      } else {
        this.header.removeClass('sticky');
      }
    }

    this.previousScroll = topOffset;
    this.gotoTop.toggleClass(
      'active',
      this.window.scrollTop() > this.screenHeight / 2,
    );
  };

  handleSplashScreen() {
    this.htmlBody.find('.logo-middle').fadeIn(500);
    this.siteLoader.delay(1500).fadeOut(500);
  }

  handleUserAgent = () => {
    // detect mobile platform
    if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
      this.htmlBody.addClass('ios-device');
    }
    if (navigator.userAgent.match(/Android/i)) {
      this.htmlBody.addClass('android-device');
    }

    // detect desktop platform
    if (navigator.appVersion.indexOf('Win') !== -1) {
      this.htmlBody.addClass('win-os');
    }
    if (navigator.appVersion.indexOf('Mac') !== -1) {
      this.htmlBody.addClass('mac-os');
    }

    // detect IE 10 and 11P
    if (
      navigator.userAgent.indexOf('MSIE') !== -1 ||
      navigator.appVersion.indexOf('Trident/') > 0
    ) {
      this.html.addClass('ie10');
    }

    // detect IE Edge
    if (/Edge\/\d./i.test(navigator.userAgent)) {
      this.html.addClass('ieEdge');
    }

    // Specifically for IE8 (for replacing svg with png images)
    if (this.html.hasClass('ie8')) {
      const imgPath = '/themes/theedge/images/';
      $('header .logo a img,.loading-screen img').attr(
        'src',
        `${imgPath}logo.png`,
      );
    }

    // show ie overlay popup for incompatible browser
    if (this.html.hasClass('ie9')) {
      const message = $(
        '<div class="no-support"> You are using outdated browser. Please <a href="https://browsehappy.com/" target="_blank">update</a> your browser or <a href="https://browsehappy.com/" target="_blank">install</a> modern browser like Google Chrome or Firefox.<div>',
      );
      this.htmlBody.prepend(message);
    }
  };
})();