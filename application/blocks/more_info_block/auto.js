Concrete.event.bind('btMoreInfoBlock.btn.open', function (options, settings) {
    var container = $('#' + settings.id);

    $(container).on('change', '.ft-smart-link-type', function () {
        var me = this;
        var value = $(me).val();
        var ftSmartLink = $(me).parents('.ft-smart-link');
        var ftSmartLinkOptions = $(ftSmartLink).find('.ft-smart-link-options');
        var ftSmartLinkOptionsShow = false;
        if($(ftSmartLinkOptions).hasClass('hidden')){
            $(ftSmartLinkOptions).removeClass('hidden').hide();
        }
        $.each($(ftSmartLinkOptions).find('[data-link-type]'), function () {
            if ($(this).hasClass('hidden')) {
                $(this).removeClass('hidden').hide();
            }
            var linkType = $(this).attr('data-link-type');
            if (linkType == value) {
                $(this).slideDown();
                ftSmartLinkOptionsShow = true;
            }
            else {
                $(this).slideUp();
            }
        });
        if(ftSmartLinkOptionsShow){
            $(ftSmartLinkOptions).slideDown();
        }
        else {
            $(ftSmartLinkOptions).slideUp();
        }
    });
});