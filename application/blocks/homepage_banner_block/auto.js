Concrete.event.bind('btHomepageBannerBlock.btnone.open', function (options, settings) {
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
});Concrete.event.bind('btHomepageBannerBlock.btntwo.open', function (options, settings) {
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
});Concrete.event.bind('btHomepageBannerBlock.slideimages.edit.open', function (options, settings) {
    var container = $('#' + settings.id);
    var hbTemplate = Handlebars.compile($(container).find('.repeatableTemplate').html());
    var sortableItems = $(container).find('.sortable-items');
    var slideSpeed = 200;
    var sortableConfig = {
        items: '.sortable-item',
        handle: '.sortable-item-handle',
        forcePlaceholderSize: true,
        placeholder: 'sortable-item sortable-item-placeholder',
        start: function (e, ui) {
            ui.placeholder.height(ui.item.height());
        }
    };

    var token = function () {
        return Math.random().toString(36).substr(2) + Math.random().toString(36).substr(2);
    };

    var updateItem = function (newField) {
        $(newField).find(".launch-tooltip").tooltip({container: "#ccm-tooltip-holder"});
    var ftImageImg = $(newField).find('.ft-image-img-file-selector');
            if ($(ftImageImg).length > 0) {
                $(ftImageImg).concreteFileSelector({'inputName': $(ftImageImg).attr('data-file-selector-input-name'), 'filters': [], 'fID' : $(ftImageImg).attr('data-file-selector-f-id') });
            }
            var pageSelectorImg = $(newField).find('.ft-image-img-page-selector');
            if ($(pageSelectorImg).length > 0) {
                $(pageSelectorImg).concretePageSelector({'inputName': $(pageSelectorImg).attr('data-input-name'), 'cID' : $(pageSelectorImg).attr('data-cID')});
            }
    };

     var loadComplete = function () {
        var sortableItemsLength = $(sortableItems).find('.sortable-item').length;
        if (sortableItemsLength <= 0) {
            $(container).find('.add-entry-last').addClass('hidden');
        }
        else {
            $(container).find('.add-entry-last').removeClass('hidden');
        }
    };

    $(document).ready(function () {
        var jsonString = $(sortableItems).attr('data-attr-content');
        var data = $.parseJSON(jsonString);
        if ($.isPlainObject(data)) {
            var items = [];
            var newField = false;
            $.each(data.order, function (i, value) {
                var item = data.items[value];
                if (item != undefined) {
                    item.token = token();
                    item.id = value + 1;
                    items.push(item);
                }
            });
            $.each(items, function (i, v) {
                $(sortableItems).append(hbTemplate(v));
                newField = $(sortableItems).find('.sortable-item[data-id="' + v.id + '"]');
                updateItem(newField);
            });
            loadComplete();
            $(sortableItems).sortable(sortableConfig);
        }
    });

    $(container).on('click', '.sortable-item .sortable-item-collapse-toggle', function (e) {
        e.preventDefault();
        var sortableItem = $(this).parent();
        if ($(sortableItem).hasClass('collapsed')) {
            $(sortableItem).removeClass('collapsed');
        }
        else {
            $(sortableItem).addClass('collapsed');
        }
    });

    $(container).on('click', '.sortable-item .sortable-item-delete', function (e) {
        e.preventDefault();
        var deleteIt = confirm($(this).attr('data-attr-confirm-text'));
        if (deleteIt === true) {
            var sortableItem = $(this).parent();
            $(sortableItem).slideUp(slideSpeed, function () {
                $(sortableItem).remove();
                loadComplete();
            });
        }
    });

    $(container).on('keyup', '.sortable-item input[type="text"].title-me', function (e) {
        var me = this;
        var value = $(me).val();
        var sortableItem = $(me).parents('.sortable-item');
        var newFieldTitle = $(sortableItem).find('.sortable-item-title');
        var newFieldTitleDefault = $(newFieldTitle).find('.sortable-item-title-default');
        var newFieldTitleGenerated = $(newFieldTitle).find('.sortable-item-title-generated');
        if ($.trim(value) != '') {
            $(newFieldTitleDefault).hide();
            $(newFieldTitleGenerated).html(value).show();
        }
        else {
            $(newFieldTitleDefault).show();
            $(newFieldTitleGenerated).html(' ').hide();
        }
    });

    $(container).on('click', '.add-entry', function (e) {
        e.preventDefault();
        var ids = new Array();
        $(sortableItems).find('.sortable-item').each(function () {
            ids.push(parseInt($(this).attr('data-id')));
        });
        if (ids.length == 0) {
            ids.push(0);
        }
        var id = Math.max.apply(Math, ids) + 1;
        var data = {
            "token": token(),
            "id": id,
            "sortOrder": id
        };
        $(sortableItems).append(hbTemplate(data)).sortable(sortableConfig);
        var newField = $(sortableItems).find('.sortable-item[data-id="' + id + '"]');
        $.each($(newField).find('input[data-attr-default-value], select[data-attr-default-value], textarea[data-attr-default-value]'), function(){
           $(this).val($(this).attr('data-attr-default-value'));
        });
        $(newField).hide().slideDown(slideSpeed);
        loadComplete();
        $(newField).find('input, textarea, select').filter(':visible:first').focus();
        updateItem(newField);
        var uiDialogContent = $(container).parents('.ui-dialog-content');
        $(uiDialogContent).animate({scrollTop: $(newField).position().top + $(uiDialogContent).scrollTop()});
    });
});