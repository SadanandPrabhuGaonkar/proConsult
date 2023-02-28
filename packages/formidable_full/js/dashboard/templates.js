// JavaScript Document
var ccmFormidableFormCheckSelectors = function(s) {
	if (s != undefined && s.length > 0 && typeof(s) !== 'object') { return false; }
};

var ccmFormidableLoadTemplates = function() {
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

var ccmFormidableOpenDeleteTemplateDialog = function(templateID) {
	var query_string = '?templateID='+templateID+'&ccm_token='+formidable_security_token_form;
	jQuery.fn.dialog.open({ 
		width: 520,
		height: 100,
		modal: true,
		href: dialog_url+'/delete'+query_string,
		title: title_message_delete
	});
};

var ccmFormidableDeleteTemplate = function(templateID) {
	query_string = 'templateID='+templateID+'&ccm_token='+formidable_security_token_form;
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
			ccmFormidableLoadTemplates();
		}
	});	
};

var ccmFormidableDuplicateTemplate = function(templateID) {
	query_string = 'templateID='+templateID+'&ccm_token='+formidable_security_token_form;
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
			ccmFormidableLoadTemplates();
		}
	});	
};