import { gsap, Power2 } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
gsap.registerPlugin(ScrollTrigger);
import { SplitText } from "../vendor/SplitText.min";

export default class Animation {
    constructor() {
      this.MarqeeAnimation = ".marquee";
      this.CharsRevealAnimation = ".js-chars-reveal";
      this.FadeUpAnimation = ".fadeup";
      this.ImageSlideAnimation = ".reveal";
      this.ImageSlideAnimationDelay = ".reveallate";
      this.TitleAnimationDelay = ".text-title";
      this.FadeUpAnimationDelay = ".fadeuplate";
      this.CharsAnimationDelay = ".js-chars-reveal-late";
      this.HeaderAnim = "header";
      this.Tabs = ".tabs";
      this.bindEvents();
    }
  
    bindEvents = () => {
      if (document.querySelectorAll(this.MarqeeAnimation).length) {
        this.MarqeeAnimationInit();
      }
      if (document.querySelectorAll(this.CharsRevealAnimation).length) {
        this.CharsRevealAnimationInit();
      }
      if (document.querySelectorAll(this.FadeUpAnimation).length) {
        this.FadeUpAnimationInit();
      }
      if (document.querySelectorAll(this.ImageSlideAnimation).length) {
        this.ImageSlideAnimationInit();
      }
      if (document.querySelectorAll(this.ImageSlideAnimationDelay).length) {
        this.ImageSlideAnimationDelayInit();
      }
      if (document.querySelectorAll(this.TitleAnimationDelay).length) {
        this.TitleAnimationDelayInit();
      }
      if (document.querySelectorAll(this.FadeUpAnimationDelay).length) {
        this.FadeUpAnimationDelayInit();
      }
      if (document.querySelectorAll(this.CharsAnimationDelay).length) {
        this.CharsAnimationDelayInit();
      }
      if (document.querySelectorAll(this.HeaderAnim).length) {
        this.HeaderAnimInit();
      }
      if (document.querySelectorAll(this.Tabs).length) {
        this.TabsAnimInit();
      }
    };
  
    MarqeeAnimationInit = () => {

        let currentScroll = 0;
        let isScrollingDown = true;

        let tween = gsap.to(".marquee__part", {xPercent: -100, repeat: -1, duration: 10, ease: "linear"}).totalProgress(0.5);

        gsap.set(".marquee__inner", {xPercent: -50});

        window.addEventListener("scroll", function(){
        
        if ( window.pageYOffset > currentScroll ) {
            isScrollingDown = true;
        } else {
            isScrollingDown = false;
        }
        
        gsap.to(tween, {
            timeScale: isScrollingDown ? 1 : -1
        });
        
        currentScroll = window.pageYOffset
        });

    };

    CharsRevealAnimationInit = () => {

          //Chars anim
      $(".js-chars-reveal").each((index, item) => {
        let childClass = "split-child";
        let childArr = $(item).find(`.${childClass}`);

        if (childArr.length === 0) {
          let childSplit = new SplitText($(item), {
            type: "words,chars",
            linesClass: childClass,
          });
          let parentSplit = new SplitText($(item), {
            type: "lines",
            linesClass: "split-parent",
          });
          childArr = childSplit.chars;
        }

        let tl = gsap.timeline({
          scrollTrigger: {
            trigger: item,
            start: "top bottom",
          },
        });

        tl.to(item, {
          autoAlpha: 1
        })

        tl.from(childArr,{
          duration: 0.5,
          opacity: 0,
          stagger: 0.02,
        });
    });

    };

    FadeUpAnimationInit = () =>{

      //fadeup
      const boxes = gsap.utils.toArray('.fadeup');

      // Set things up
      gsap.set(boxes, {autoAlpha: 0, y: 50});

      boxes.forEach((box, i) => {
        // Set up your animation
        const anim = gsap.to(box, {duration: 0.7, autoAlpha: 1, y: 0, paused: true});
        
        // Use callbacks to control the state of the animation
        ScrollTrigger.create({
          trigger: box,
          start: "top 100%",
          once: true,
          onEnter: self => {
            // If it's scrolled past, set the state
            // If it's scrolled to, play it
            self.progress === 1 ? anim.progress(1) : anim.play()
          }
        });
      });

    };


    ImageSlideAnimationInit = () => {

      //Image reveal
      let revealContainers = document.querySelectorAll(".reveal");

      revealContainers.forEach((container) => {
        let image = container.querySelector("img");
        let tl = gsap.timeline({
          scrollTrigger: {
            trigger: container,
            start: "top 100%",
          }
        });

        tl.set(container, { autoAlpha: 1 });
        tl.from(container, 1.5, {
          xPercent: -100,
          ease: Power2.out
        });
        tl.from(image, 1.5, {
          xPercent: 100,
          delay: -1.5,
          ease: Power2.out
        });
      });

    };

    ImageSlideAnimationDelayInit = () => {
      setTimeout(() => {

        let revealContainers = document.querySelectorAll(".reveallate");

          revealContainers.forEach((container) => {
            let image = container.querySelector("img");
            let tl = gsap.timeline({
              scrollTrigger: {
                trigger: container,
                start: "top 100%",
              }
            });

            tl.set(container, { autoAlpha: 1 });
            tl.from(container, 1.5, {
              xPercent: -100,
              ease: Power2.out
            });
            tl.from(image, 1.5, {
              xPercent: 100,
              delay: -1.5,
              ease: Power2.out
            });
          });

      }, "700")
    };

    TitleAnimationDelayInit = () => {
      setTimeout(() => {   

      const h1s = document.querySelectorAll('.text-title');

      const letters = new SplitText(h1s).chars;

      [...h1s].forEach((h1) => {
        h1.style.display = 'block'
      });

      const to = gsap.from(letters, {
        y: 100,
        rotation: 10,
        duration: 1,
        stagger: 0.1,
        ease: "power3.inOut"
      });

      }, "700")
    };


    FadeUpAnimationDelayInit = () =>{
      setTimeout(() => {
        const boxes = gsap.utils.toArray('.fadeuplate');

        // Set things up
        gsap.set(boxes, {autoAlpha: 0, y: 50});
        
        boxes.forEach((box, i) => {
          // Set up your animation
          const anim = gsap.to(box, {duration: 0.7, autoAlpha: 1, delay: 0.6, y: 0, paused: true});
          
          // Use callbacks to control the state of the animation
          ScrollTrigger.create({
            trigger: box,
            start: "top 100%",
            once: true,
            onEnter: self => {
              // If it's scrolled past, set the state
              // If it's scrolled to, play it
              self.progress === 1 ? anim.progress(1) : anim.play()
            }
          });
        });
      }, "700");
    };

    CharsAnimationDelayInit = () => {
      setTimeout(()=>{
        $(".js-chars-reveal-late").each((index, item) => {
          let childClass = "split-child";
          let childArr = $(item).find(`.${childClass}`);
        
          if (childArr.length === 0) {
            let childSplit = new SplitText($(item), {
              type: "words,chars",
              linesClass: childClass,
            });
            let parentSplit = new SplitText($(item), {
              type: "lines",
              linesClass: "split-parent",
            });
            childArr = childSplit.chars;
          }
        
          let tl = gsap.timeline({
            scrollTrigger: {
              trigger: item,
              start: "top bottom",
            },
          });
        
          tl.to(item, {
            autoAlpha: 1
          })
        
          tl.from(childArr,{
            duration: 2,
            opacity: 0,
            stagger: 0.02,
          });
        });
      }, "700")
    };


    HeaderAnimInit = () => {

      //mobile menu toggle
      $(".has-submenu").click(function(){
        $(".has-submenu ul").slideToggle();
      });

      $('.mobile').on('click', function () {
        $('header').toggleClass('light');
      });

      //megamenu
      if($(window).width() > 1025) {

      $(".header-inner nav ul .no-submenu").mouseover(function(){
        $(".has-submenu ul").removeClass('active');
        $(".has-submenu ul li").removeClass('active');
        $("header").removeClass('light');
        $('body').removeClass('active');
        $('main').removeClass('active');
      });

      $("main").mouseover(function(){
        $(".has-submenu ul").removeClass('active');
        $(".has-submenu ul li").removeClass('active');
        $("header").removeClass('light');
        $('body').removeClass('active');
        $('main').removeClass('active');
      });

      $(".has-submenu").mouseover(function(){
        $(".has-submenu ul").addClass('active');
        $("header").addClass('light');
        setTimeout(() => {
          $(".has-submenu ul li").addClass('active');
        }, 400)
        $('body').addClass('active');
        $('main').addClass('active');
      });
      }

    };

    TabsAnimInit = () => {
    //tabs
    // Show the first tab and hide the rest
    $('#tabs-nav li:first-child').addClass('active');
    $('.tab-content').hide();
    $('.tab-content:first').show();

    // Click function
    $('#tabs-nav li').click(function(){
      $('#tabs-nav li').removeClass('active');
      $(this).addClass('active');
      $('.tab-content').hide();
      
      var activeTab = $(this).find('a').attr('href');
      $(activeTab).fadeIn();
      return false;
    });
    }
     
  }


