function FormidableResultLoader() { 
    
    var global = $(window);

    'use strict';    

    function FormidableResult($element, options) {
        'use strict';
        var my = this;
        options = $.extend({
            'bulkParameterName': 'item',
            'searchMethod': 'get',
            'appendToOuterDialog': true,
            'selectMode': 'multiple' // Enables multiple advanced item selection, range click, etc
        }, options);

        my.interactionIsDragging = false;
      
        ConcreteAjaxSearch.call(my, $element, options);
        
        my.setupEvents();
    }

    FormidableResult.prototype = Object.create(ConcreteAjaxSearch.prototype);

    FormidableResult.prototype.refreshResults = function(files) {
        var my = this;
        my.loadResults(false, true);
    }

    FormidableResult.prototype.setupEvents = function() {
        var my = this;
        ConcreteEvent.unsubscribe('SavedSearchCreated');
        ConcreteEvent.subscribe('SavedSearchCreated', function(e, data) {
            my.ajaxUpdate(data.search.baseUrl, {});
        });

        ConcreteEvent.subscribe('SearchSelectItems', function(e, data) {
            var $menu = my.getResultMenu(data.results);
            if ($menu) {
                my.$element.find('button.btn-menu-launcher').prop('disabled', false);
            } else {
                my.$element.find('button.btn-menu-launcher').prop('disabled', true);
            }
        }, my.$element);
    }

    FormidableResult.prototype.showMenu = function($element, $menu, event) {
        var my = this;
        var concreteMenu = new FormidableResultMenu($element, {
            menu: $menu,
            handle: 'none',
            container: my
        });
        concreteMenu.show(event);
    }

    FormidableResult.prototype.activateMenu = function($menu) {
        var my = this;
        if (my.getSelectedResults().length > 1) {
            // bulk menu
            $menu.find('a').on('click.concreteFileManagerBulkAction', function(e) {
                var value = $(this).attr('data-bulk-action'),
                    type = $(this).attr('data-bulk-action-type'),
                    ids = [];
                $.each(my.getSelectedResults(), function(i, result) {
                    ids.push(result.answerSetID);
                });
                my.handleSelectedBulkAction(value, type, $(this), ids);
            });
        }
    }

    FormidableResult.prototype.setupBulkActions = function() {
        var my = this;

        // Or, maybe we're using a button launcher
        my.$element.on('click', 'button.btn-menu-launcher', function(event) {
            var $menu = my.getResultMenu(my.getSelectedResults());
            if ($menu) {
                $menu.find('.dialog-launch').dialog();
                var $list = $menu.find('ul');
                $list.attr('data-search-menu', $menu.attr('data-search-menu'));
                $(this).parent().find('ul').remove();
                $(this).parent().append($list);

                var fileMenu = new FormidableResultMenu();
                fileMenu.setupMenuOptions($(this).next('ul'));

                ConcreteEvent.publish('ConcreteMenuShow', {menu: my, menuElement: $(this).parent()});
            }
        });
    }

    FormidableResult.prototype.handleSelectedBulkAction = function(value, type, $option, ids) {
        var my = this, itemIDs = [];
        
        if (ids instanceof jQuery) {
            $.each($items, function(i, checkbox) {
                itemIDs.push({'name': my.options.bulkParameterName + '[]', 'value': $(checkbox).val()});
            });
        } else {
            $.each(ids, function(i, id) {
                itemIDs.push({'name': my.options.bulkParameterName + '[]', 'value': id});
            });
        }

        var url = $option.attr('data-bulk-action-url');
        if (url.indexOf('?')!==false) url = url + '&';
        else url = url + '?';

        if (type == 'dialog') {
            jQuery.fn.dialog.open({
                width: $option.attr('data-bulk-action-dialog-width'),
                height: $option.attr('data-bulk-action-dialog-height'),
                modal: true,
                href: url + jQuery.param(itemIDs),
                title: $option.attr('data-bulk-action-title')
            });
        }

        if (type == 'ajax') {
            $.concreteAjax({
                url: $option.attr('data-bulk-action-url'),
                data: itemIDs,
                success: function(r) {
                    if (r.message) {
                        ConcreteAlert.notify({
                            'message': r.message,
                            'title': r.title
                        });
                    }
                }
            });
        }

        if (type == 'progressive') {
            ccm_triggerProgressiveOperation(url, itemIDs,  $option.attr('data-bulk-action-title'), function() {
                my.refreshResults();
            });
        }

        if (type == 'link') {
            window.location.href = url + jQuery.param(itemIDs);
        }

        my.publish('SearchBulkActionSelect', {value: value, option: $option, items: ids});
    };

    FormidableResult.prototype.reloadResults = function() {
        this.loadResults();
    }

    //FormidableResult.prototype.hoverIsEnabled = function($element) {
    //    var my = this;
    //    return !my.interactionIsDragging;
    //}

    FormidableResult.prototype.updateResults = function(result) {
        var my = this;
        ConcreteAjaxSearch.prototype.updateResults.call(my, result);      
    }

    FormidableResult.prototype.loadResults = function(url, showRecentFirst) {
        var my = this;
        var data = my.getSearchData();
        if (!url) var url = my.options.result.baseUrl;
        else {
            // dynamically update baseUrl because we're coming to this folder via
            // something like the breadcrumb
            my.options.result.baseUrl = url; // probably a nicer way to do this
        }

        if (my.options.result.filters) {
            // We are loading a folder with a filter. So we loop through the fields
            // and add them to data.
            $.each(my.options.result.filters, function(i, field) {
                var fieldData = field.data;
                data.push({'name': 'field[]', 'value': field.key});
                for(var key in fieldData) {
                    data.push({'name': key, 'value': fieldData[key]});
                }
            });
        }

        if (showRecentFirst) {
            data.push({'name': 'ccm_order_by', 'value': 'a_submitted'});
            data.push({'name': 'ccm_order_by_direction', 'value': 'desc'});
        }

        my.ajaxUpdate(url, data);
    }

    FormidableResult.prototype.getResultMenu = function(results) {
        var my = this;
        var $menu = ConcreteAjaxSearch.prototype.getResultMenu.call(this, results);
        if ($menu)  my.activateMenu($menu);
        return $menu;
    }
   

    $.fn.concreteFormidableResult = function(options) {
        return $.each($(this), function(i, obj) {
            new FormidableResult($(this), options);
        });
    };

    global.FormidableResult = FormidableResult;

    function FormidableResultMenu($element, options) {
        var my = this, 
            options = options || {};

        options = $.extend({
            'container': false,
        }, options);

        my.options = options;

        if ($element) {
            ConcreteMenu.call(my, $element, options);
        }           
    }

    FormidableResultMenu.prototype = Object.create(ConcreteMenu.prototype);

    FormidableResultMenu.prototype.setupMenuOptions = function($menu) {
        //var my = this,
        var parent = ConcreteMenu.prototype;
            //answerSetID = $menu.attr('data-search-menu'),
            //container = my.options.container;
            
        parent.setupMenuOptions($menu);            
    }

    // jQuery Plugin
    $.fn.concreteFileMenu = function(options) {
        return $.each($(this), function(i, obj) {
            new FormidableResultMenu($(this), options);
        });
    }

    global.FormidableResultMenu = FormidableResultMenu;

}
