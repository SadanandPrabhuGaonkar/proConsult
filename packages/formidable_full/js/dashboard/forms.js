// JavaScript Document
$(function() { 
	changed = false;
	$('input, textarea').on('keyup keydown', function() { changed = true; });
	$('select').on('change', function() { changed = true; });
	$('input[type="radio"], input[type="checkbox"]').on('click', function() { changed = true; });
	$('input[type="submit"]').on('click', function() { changed = false; });
	$(window).bind('beforeunload', function(e) {
		if (changed) { return changed_values; }
	});

	$('.form-tabs li>a[href="javascript:;"]').closest('li').addClass('disabled');
});

var ccmFormidableFormCheckSelectors = function(s) {
	if (s != undefined && s.length > 0 && typeof(s) !== 'object') { return false; }

	var div = $('div[id="limits_div"]');
	if (parseInt($('select[name="limits"]').val()) == 1) div.slideDown();
	else div.slideUp();
	
	if (parseInt($('select[name="limits_redirect"]').val()) == 0) {
		$('div[id="limits_redirect_content"]').slideDown();
		$('div[id="limits_redirect_page"]').slideUp();
	} else {
		$('div[id="limits_redirect_content"]').slideUp();
		$('div[id="limits_redirect_page"]').slideDown();
	}
	
	var div = $('div[id="schedule_div"]');
	if (parseInt($('select[name="schedule"]').val()) == 1) div.slideDown();
	else div.slideUp();
	
	if (parseInt($('select[name="schedule_redirect"]').val()) == 0) {
		$('div[id="schedule_redirect_content"]').slideDown();
		$('div[id="schedule_redirect_page"]').slideUp();
	} else {
		$('div[id="schedule_redirect_content"]').slideUp();
		$('div[id="schedule_redirect_page"]').slideDown();
	}

	if (parseInt($('select[name=submission_redirect]').val()) == 0) {
		$('div[id=submission_redirect_content]').slideDown();
		$('div[id=submission_redirect_page]').slideUp();
	} else {
		$('div[id=submission_redirect_content]').slideUp();
		$('div[id=submission_redirect_page]').slideDown();
	}
			
	var element = $('input[name=css_value]');
	if ($('input[name=css]').is(':checked')) {
		element.attr('disabled', false); $('#css_content_note').slideDown();
		if (s && s.attr('name') == 'default') element.focus();
	} else {
		element.attr('disabled', true).val(''); $('#css_content_note').slideUp();
	}
};

var ccmFormidableLoadForms = function() {
	var query_string = '&ccm_token='+formidable_security_token_form;
	$.ajax({ 
		type: 'POST',
		url: list_url,
		data: query_string,
		dataType: 'html',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
			$('#ccm-formidable-forms').hide();
		},
		success: function(ret) {
			$('div.placeholder, div.loader').fadeOut();
			$('#ccm-formidable-forms').html(ret).fadeIn();
			ccmFormidableCreateMenu();			
			ccmFormidableLoadLoader(false);
		}
	});		
};

var ccmFormidableOpenDeleteFormDialog = function(formID) {
	var query_string = '?formID='+formID+'&ccm_token='+formidable_security_token_form;
	jQuery.fn.dialog.open({ 
		width: 520,
		height: 100,
		modal: true,
		href: dialog_url+'/delete'+query_string,
		title: title_message_delete
	});
};

var ccmFormidableDeleteForm = function(formID) {
	query_string = 'formID='+formID+'&ccm_token='+formidable_security_token_form;
	$.ajax({ 
		type: 'POST',
		url: tools_url+'/delete',
		data: query_string,
		dataType: 'json',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(ret) {
			ccmFormidableSetMessage(ret.type, ret.message);
			ccmFormidableLoadForms();
		}
	});	
};

var ccmFormidableDuplicateForm = function(formID) {
	query_string = 'formID='+formID+'&ccm_token='+formidable_security_token_form;
	$.ajax({ 
		type: 'POST',
		url: tools_url+'/duplicate',
		data: query_string,
		dataType: 'json',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(ret) {
			ccmFormidableSetMessage(ret.type, ret.message);
			ccmFormidableLoadForms();
		}
	});	
};