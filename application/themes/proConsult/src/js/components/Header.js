export default class Header {
  constructor({
    header,
    htmlBody,
  }) {
    this.header = header;
    this.htmlBody = htmlBody;
    this.mobileMenu = this.header.find('.mobile-menu');
    this.mobileNav = this.htmlBody.find('.mobile-nav');
    this.mobileMenuMask = this.htmlBody.find('.mobile-menu-mask');
    this.bindEvents();
  }

  bindEvents = () => {
    const $container = this.htmlBody;
    $container.on('click', '.mobile-menu', this.handleMobileMenu);
  };

  handleMobileMenu = () => {
    if (this.mobileMenu.hasClass('active')) {
      this.mobileMenu.removeClass('active');
      this.htmlBody.removeClass('active');
      this.mobileNav.removeClass('active');
      this.mobileMenuMask.removeClass('active');
    } else {
      this.mobileMenu.addClass('active');
      this.htmlBody.addClass('active');
      this.mobileNav.addClass('active');
      this.mobileMenuMask.addClass('active');
    }
  };
}
