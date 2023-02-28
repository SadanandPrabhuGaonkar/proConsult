/** Custom JavaScript */
(function($) {

        var $el = {},
            _screenWidth,
            _screenHeight,
            _footerHeight;

        $(document).ready(domReady);

        function cacheDom() {
            $el.htmlNbody = $('body, html');
            $el.siteLoader = $('.site-loader');
            $el.header = $('header');
            $el.siteBody = $('.site-body');
            $el.footer = $('footer');
            $el.gotoTop = $('#gotoTop');
            $el.wrapper = $('.wrapper');
            $el.pushDiv = $el.wrapper.find(".push");
        }

        function domReady() {
            cacheDom();
            setEvents();
            
            handleSplashScreen();

            screenResize();

            //Add focus in class for labels to move
            $(".formidable .element input, .formidable .element textarea").each(function(){
                $(this).focusin(function() {
                    $(this).parents('.input').prev("label").addClass("focus");
                });
                $(this).focusout(function() {
                    if($(this).val().length < 1){
                        $(this).parents('.input').prev("label").removeClass("focus");
                    }
                });
            });

        }

        function setEvents() {
            $(window)
                .load(handleWidgetsLoading)
                .resize(screenResize)
                .scroll(windowScroll);

            $el.header.find('.mobile-menu').on('click', handleMobileMenu);

            //scroll to top
            $el.gotoTop.click(function(){
                $('body,html').animate({
                    scrollTop: 0
                });
            });

            //close IE9 overlay
            $(".ie-overlay span").click(function(){
                $(".ie-overlay").fadeOut("slow");
            });

        }

        function screenResize() {
            _screenWidth = $(window).width();
            _screenHeight = $(window).height();

            //calculate footer height and assign it to wrapper and push/footer div
            _footerHeight = $el.footer.outerHeight();
            $el.wrapper.css("margin-bottom",-_footerHeight);
            $el.pushDiv.height(_footerHeight);

        }

        function windowScroll() {
            //toggle goto top button
            $el.gotoTop.toggleClass("active",$(window).scrollTop() > (_screenHeight/2));
        }

        function handleMobileMenu() {
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
            } else {
                $(this).addClass("active");
            }
        }

        function handleSplashScreen() {
            /* loading screen */
            $('.logo-middle').fadeIn(500);
            $el.siteLoader.delay(1500).fadeOut(500);
        }

        function handleWidgetsLoading() {
            
        }


        (function init() {
            //detect mobile platform
            if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
                $("body").addClass("ios-device");
            }
            if (navigator.userAgent.match(/Android/i)) {
                $("body").addClass("android-device");
            }

            //detect desktop platform
            if (navigator.appVersion.indexOf("Win") != -1) {
                $('body').addClass("win-os");
            }
            if (navigator.appVersion.indexOf("Mac") != -1) {
                $('body').addClass("mac-os");
            }

            //detect IE 10 and 11
            if (navigator.userAgent.indexOf('MSIE') !== -1 || navigator.appVersion.indexOf('Trident/') > 0) {
                $("html").addClass("ie10");
            }

            //detect IE Edge
            if(/Edge\/\d./i.test(navigator.userAgent)){
                $("html").addClass("ieEdge");
            }

            //Specifically for IE8 (for replacing svg with png images)
            if ($("html").hasClass("ie8")) {
                var imgPath = "/themes/theedge/images/";
                $("header .logo a img,.loading-screen img").attr("src", imgPath + "logo.png");
            }

            //show ie overlay popup for incompatible browser
            if($('html').hasClass('ie9')) {
                var message = $('<div class="no-support"> You are using outdated browser. Please <a href="https://browsehappy.com/" target="_blank">update</a> your browser or <a href="https://browsehappy.com/" target="_blank">install</a> modern browser like Google Chrome or Firefox.<div>');
                $('body').prepend(message);
            }
            
        })();

})(jQuery);

/* Uncomment below if you need to add google captcha (also in includes/script.php) => Make sure the SITEKEY is changed below
var CaptchaCallback = function(){
    $('.g-recaptcha').each(function(index, el) {
        grecaptcha.render(el, {'sitekey' : '6LeB3QwUAAAAADQMo87RIMbq0ZnUbPShlwCPZDTv'});
    });
};
*/

function showFormErrors($form, errors) {
    if (!$form || !($form instanceof jQuery) || $form.length < 1 || !errors || errors.constructor !== Array || errors.length < 1) {
        return;
    }

    var $errors = $('<ul>').attr({'class': "ccm-error"});
    errors.forEach(function (error) {
        $errors.append(
            $('<li>').text(error)
        )
    });
    $form.before($errors);
}

function removeFormErrors($form) {
    if (!$form || !($form instanceof jQuery) || $form.length < 1) {
        return;
    }

    $form.prevAll('.ccm-error').remove();
}
