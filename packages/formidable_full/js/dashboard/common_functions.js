var ccmFormidableLoadPlaceholder = function(show) {	
	if (show) { 
		$('div.placeholder').show(); 
		$('div.loader').hide(); 
	} 
	else $('div.placeholder').hide();
};

var ccmFormidableLoadLoader = function(show) {	
	if (show) $('div.loader').show(); 
	else $('div.loader').hide();
};

var ccmFormidableCloseDialog = function() {
	jQuery.fn.dialog.closeTop();
};

ccmFormidableSetMessage = function(type, message) {
	if (type == 'error') ConcreteAlert.error({message: message});
	else ConcreteAlert.notify({type: type, message: message});
}

//  Callback when all layouts and elements are loaded.
var ccmFormidableCreateMenu = function () {
	$("[data-launch-search-menu]").each(function() {
		$(this).concreteMenu({container:false,menu:$('[data-search-menu='+$(this).data('launch-search-menu')+']')});
	});
};