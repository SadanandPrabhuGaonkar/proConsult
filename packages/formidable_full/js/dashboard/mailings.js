// JavaScript Document
var ccmFormidableLoadMailings = function() {
	var query_string = "formID="+formID+'&ccm_token='+formidable_security_token_mailing;
	$.ajax({ 
		type: "POST",
		url: list_url,
		data: query_string,
		dataType: 'html',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
			$('#ccm-formidable-mailings').hide();
		},
		success: function(ret) {
			$('div.placeholder, div.loader').fadeOut();
			$('#ccm-formidable-mailings').html(ret).fadeIn();
			ccmFormidableCreateMenu();			
			ccmFormidableLoadLoader(false);
		}
	});		
};

var ccmFormidableOpenMailingDialog = function(mailingID) {
	var query_string = "?formID="+formID+'&ccm_token='+formidable_security_token_mailing;
	if (parseInt(mailingID) != 0) query_string += "&mailingID="+mailingID;
	jQuery.fn.dialog.open({ 
		width: '90%',
		height: '75%',
		modal: true,
		href: dialog_url+query_string,
		title: (parseInt(mailingID) != 0)?title_message_edit:title_message_add
	});
};

var ccmFormidableAddMailingToForm = function() {
	var params = $('#mailingForm').serialize();
	ccmFormidableSaveMailing(params);
	jQuery.fn.dialog.closeTop();
};

var ccmFormidableSaveMailing = function(query_string) {
	query_string += '&formID='+formID+'&ccm_token='+formidable_security_token_mailing;
	$.ajax({ 
		type: "POST",
		url: tools_url+'/save',
		data: query_string,
		dataType: 'json',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(ret) {
			ccmFormidableSetMessage(ret.type, ret.message);
			ccmFormidableLoadMailings();
		}
	});	
};

var ccmFormidableOpenDeleteMailingDialog = function(mailingID) {
	var query_string = "?mailingID="+mailingID+"&formID="+formID+'&ccm_token='+formidable_security_token_mailing;
	jQuery.fn.dialog.open({ 
		width: 520,
		height: 100,
		modal: true,
		href: dialog_url+'/delete'+query_string,
		title: title_message_delete
	});
};

var ccmFormidableDeleteMailing = function(mailingID) {
	query_string = 'mailingID='+mailingID+'&formID='+formID+'&ccm_token='+formidable_security_token_mailing;
	$.ajax({ 
		type: "POST",
		url: tools_url+'/delete',
		data: query_string,
		dataType: 'json',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(ret) {
			ccmFormidableSetMessage(ret.type, ret.message);
			ccmFormidableLoadMailings();
		}
	});	
};

var ccmFormidableDuplicateMailing = function(mailingID) {
	query_string = 'mailingID='+mailingID+'&formID='+formID+'&ccm_token='+formidable_security_token_mailing;
	$.ajax({ 
		type: "POST",
		url: tools_url+'/duplicate',
		data: query_string,
		dataType: 'json',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(ret) {
			ccmFormidableSetMessage(ret.type, ret.message);
			ccmFormidableLoadMailings();
		}
	});	
};

var ccmFormidableCheckFormMailingSubmit = function() {
	var query_string = $('#mailingForm').serialize();	
	query_string += '&ccm_token='+formidable_security_token_mailing;
	$.ajax({ 
		type: "POST",
		url: tools_url+'/validate',
		data: query_string,
		dataType: 'json',
		success: function(ret) {
			if (ret.type == 'error') {
				var message = $('div.dialog_message').empty();
				$.each(ret.message, function(i, row) {
					message.append(row+'<br />').show();
				});
				$("div.element-body").scrollTop(0);
			} else ccmFormidableAddMailingToForm();
		}
	});	
};

var ccmFormidableFormMailingCheckSelectors = function(s) {	
	if (s != undefined && s.length > 0 && typeof(s) !== 'object') { return false; }	
	var element = $("textarea[name=send_custom_value]");
	if ($('input[name=send_custom]').is(':checked')) {
		element.attr('disabled', false); $('.send_custom_note').slideDown();
	} else {
		element.val("").attr('disabled', true); $('.note').slideUp();
	}
	if ($('select[name=from_type]').val() == 'other') {
		$("input[name=from_name], input[name=from_email]").attr('disabled', false).parents('.custom').slideDown();
		$('div.reply_to').slideDown().find('input').attr('disabled', false);
		if (s && s.attr('name') == 'from_type') {
			$("input[name=from_name]").focus();
			$('div.reply_to').find('select[name=reply_type]').val('from');
		}
	} else {
		$("input[name=from_name], input[name=from_email]").attr('disabled', true).val('').parents('.custom').slideUp();
		$('div.reply_to').slideUp().find('input').attr('disabled', true).va;('');
	}	
	if ($('select[name=reply_type]').val() == 'other') {
		$("input[name=reply_name], input[name=reply_email]").attr('disabled', false).parents('.custom').slideDown();
		if (s && s.attr('name') == 'reply_type') $("input[name=reply_name]").focus();
	} else {
		$("input[name=reply_name], input[name=reply_email]").attr('disabled', true).val('').parents('.custom').slideUp();
	}	
	var element = $("select[name=templateID]");
	if ($('input[name=template]').is(':checked')) {
		element.attr('disabled', false).focus();
	} else {
		element.val("").attr('disabled', true);
	}
};

var ccmFormidableSubjectOverlay = function(formID) {
    jQuery.fn.dialog.open({
        width: 750,
		height: '75%',
		modal: true,
		href: element_dialog_url+'?formID='+formID+'&btn=1&ccm_token='+formidable_security_token_element,
		title: title_element_overlay,
		open: function() {
			$('.element_options .btn').off('click').on('click', function() {
				var code = $(this).addClass('icon-selected').data('insert');
				$('#subject').val($('#subject').val() + code);
		        jQuery.fn.dialog.closeTop();
			});
		}       
    }); 
};


ccmFormidableFormMailingAddAttachment = function(s) {
	if (s instanceof jQuery) {} else { s = $(s); }	
	var attachments_options = s.parents('.attachments_options');
	attachment_counter++;	
	var attachment_default = $('#new_attachment').html();
	var attachment = "<div class=\"file_selector\">"+attachment_default+"</div>";
	attachment = attachment.replace(/counter_tmp/g, attachment_counter);
	attachment += '<div class="input-group-buttons">';
	attachment += '<a href="javascript:;" onclick="ccmFormidableFormMailingAddAttachment($(this));" class="btn btn-success option_button">+</a> ';
	attachment += '<a href="javascript:;" onclick="ccmFormidableFormMailingRemoveAttachment($(this));" class="btn btn-danger option_button">-</a>';
	attachment += '</div>';
	var new_attachment = $('<div>').addClass('input attachment_row').html(attachment);
	s.parents('.input').after(new_attachment);

	$('.ccm-file-selector').each(function() { $('.ccm-file-selector-choose-new', $(this)).eq(1).remove(); }); 
	
	attachments_options.find('.error').attr('disabled', false);	
}

ccmFormidableFormMailingRemoveAttachment = function(s) {
	if (s instanceof jQuery) {} else { s = $(s); }
	var attachments_options = s.closest('.input-group');
	if (attachments_options.find('.input').length >= 2) s.closest('.input').remove();
	if (attachments_options.find('.input').length == 1) attachments_options.find('.btn-danger').attr('disabled', true);	
}

/* Dependencies */
var ccmFormidableAddDependency = function(mailingID, rule) {	
	var objDep = $('#dependencies_rules');
	var addAction = false;
	if (rule === undefined) {
		addAction = true;
		rule = parseInt(objDep.attr('data-next_rule'));
	}
	$.ajax({ 
		type: "POST",
		url: dependency_url+'/add',
		data: 'mailingID='+mailingID+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency,
		dataType: 'html',
		success: function(ret) {
			objDep.append(ret).attr('data-next_rule', rule+1);
			ccmFormidableInitDependency(mailingID, rule);
			if (addAction) {
				ccmFormidableAddDependencyAction(mailingID, rule);
			}
		}
	});		
};

var ccmFormidableInitDependency = function(mailingID, rule) {
	var objRule = $('#dependency_rule_'+rule);		
	objRule.find('.dependency_elements, .operator').hide();	
	if ($('#dependencies_rules').children('fieldset').length > 1 && rule > 0) {
		objRule.find('.operator').show();
	}
	$('#dependencies_rules').find('.dependency').each(function(i, row) {
		$(row).find('.rule').text(i + 1);
	});
};

var ccmFormidableDeleteDependencyDialog = function(mailingID, rule) {
	var query_string = '?mailingID='+mailingID+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency;
	jQuery.fn.dialog.open({ 
		width: 520,
		height: 100,
		modal: true,
		href: dependency_url+'/delete'+query_string,
		title: dependency_message_delete
	});
};

var ccmFormidableDeleteDependency = function(rule) {
	var objRule = $('#dependency_rule_'+rule);	
	objRule.remove();			 
	$('#dependencies_rules').find('.dependency').each(function(i, row) {
		$(row).find('span.rule').text(i + 1);
		if (i == 0) {
			$(row).find('.operator').hide();
		}
	});		
};

var ccmFormidableAddDependencyAction = function(mailingID, dependency_rule, rule) {	
	var objDep = $('#dependency_rule_'+dependency_rule+' .dependency_actions');
	if (rule === undefined) rule = parseInt(objDep.attr('data-next_rule'));
	$.ajax({ 
		type: "POST",
		url: dependency_url+'/action',
		data: 'mailingID='+mailingID+'&dependency_rule='+dependency_rule+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency,
		dataType: 'html',
		success: function(ret) {
			objDep.append(ret).attr('data-next_rule', rule+1);
			ccmFormidableInitDependencyAction(mailingID, dependency_rule, rule);
		}
	});		
};

var ccmFormidableDeleteDependencyAction = function(dependency_rule, rule) {
	if ($('#dependency_rule_'+dependency_rule+' .dependency_actions').children().length == 1) return false;		
	var objRule = $('#dependency_rule_'+dependency_rule+' #action_'+rule);
	objRule.remove();	
	if ($('#dependency_rule_'+dependency_rule+' .dependency_actions').children().length == 1) {
		$('#dependency_rule_'+dependency_rule+' .dependency_actions a.error').attr('disabled', true);
	}	
	objNext = $('#dependency_rule_'+dependency_rule+' .dependency_actions').children(':first');
	objNext.find('span.action_label').hide();	
};	

var ccmFormidableInitDependencyAction = function(mailingID, dependency_rule, rule) {
	var objRule = $('#dependency_rule_'+dependency_rule+' #action_'+rule);	
	objRule.find('span.action_label').hide();	
	var action = objRule.find('select.action');
	var action_value = objRule.find('input.action_value');
	var action_value_select = objRule.find('select.action_select');
	if (objRule.parents('.dependency_actions').children().length > 1 && rule > 0) {
		objRule.find('span.action_label').show();
	}			
	
	action_value.hide();
	action_value_select.hide();
	
	if (action.val() != '') {
		$('#dependency_rule_'+dependency_rule+' .dependency_elements').show();
		if (action.val() == 'class' || action.val() == 'placeholder') action_value.show();			
		if (action.val() == 'value') {
			if (action_value_select.find('option').length > 0) action_value_select.show();			
			else action_value.show();
		}
	}
	
	action.on('change', function() {
		action.val(action.val());
		action_value.hide();
		action_value_select.hide();
		if (action.val() != '') {
			objRule.parents('.dependency').find('.dependency_elements').show();
			if (objRule.parents('.dependency').find('.dependency_elements').children().length < 1)
				ccmFormidableAddDependencyElement(mailingID, dependency_rule);
		}
		if (action.val() == 'class') {
			action_value.val('').attr('placeholder', dependency_action_placeholder_class).show();
		}
		if (action.val() == 'placeholder') {
			action_value.val('').attr('placeholder', dependency_action_placeholder_placeholder).show();
		}
		if (action.val() == 'value') {
			if (action.val() == 'value') {
				if (action_value_select.find('option').length > 1) {
					action_value_select.show();			
				}
				else {
					action_value.val('').attr('placeholder', dependency_action_placeholder_value).show();		
				}
			}
		}
		if (action.val() == '') {
			objRule.parents('.dependency').find('.dependency_elements').hide();
		}
	});
	
	$('#dependency_rule_'+dependency_rule+' .dependency_actions a.error').attr('disabled', true);
	if ($('#dependency_rule_'+dependency_rule+' .dependency_actions').children().length > 1) {
		$('#dependency_rule_'+dependency_rule+' .dependency_actions a.error').attr('disabled', false);
	}
};


var ccmFormidableAddDependencyElement = function(mailingID, dependency_rule, rule) {	
	var query = '';
	var objDep = $('#dependency_rule_'+dependency_rule+' .dependency_elements');
	if (rule === undefined) rule = parseInt(objDep.attr('data-next_rule'));
	$.ajax({ 
		type: "POST",
		url: dependency_url+'/element',
		data: 'mailingID='+mailingID+'&dependency_rule='+dependency_rule+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency,
		dataType: 'html',
		success: function(ret) {
			objDep.append(ret).attr('data-next_rule', rule+1);
			ccmFormidableInitDependencyElement(mailingID, dependency_rule, rule);
		}
	});		
};

var ccmFormidableDeleteDependencyElement = function(dependency_rule, rule) {
	if ($('#dependency_rule_'+dependency_rule+' .dependency_elements').children().length == 1) {
		return false;
	}	
	var objRule = $('#dependency_rule_'+dependency_rule+' #element_'+rule);	
	objRule.remove();	
	if ($('#dependency_rule_'+dependency_rule+' .dependency_elements').children().length == 1) {
		$('#dependency_rule_'+dependency_rule+' .dependency_elements a.error').attr('disabled', true);
	}		
	objNext = $('#dependency_rule_'+dependency_rule+' .dependency_elements').children(':first');
	objNext.find('span.element_label').hide();
};

var ccmFormidableInitDependencyElement = function(mailingID, dependency_rule, rule) {
	
	var objRule = $('#dependency_rule_'+dependency_rule+' #element_'+rule);
		
	objRule.find('.element_value, .condition, span.element_label').hide();	
	
	var element_select = objRule.find('select.element');
	var element_value_select = objRule.find('select.element_value');
	var condition_select = objRule.find('select.condition');
	var condition_value = objRule.find('input.condition_value');
	
	if (objRule.parents('.dependency_elements').children().length > 1 && rule > 0) {
		objRule.find('span.element_label').show();
	}
			
	if (element_select.val() != '') {
		objRule.find('.element_value, .condition').hide();
		if (element_value_select.find('option').length > 0) objRule.find('.element_value').show();
		else {
			element_value_select.append($('<option>').val('').text('').attr('selected', 'selected'));
			objRule.find('.condition').show();
		}
	}
	
	element_select.on('change', function() {
		objRule.find('.element_value, .condition').hide();
		if (element_select.val() != '') {
			$.ajax({ 
				type: "POST",
				url: element_tools_url+'/options',
				data: 'elementID='+element_select.val()+'&ccm_token='+formidable_security_token_dependency,
				dataType: 'json',
				success: function(ret) {
					element_value_select.find('option').remove();
					condition_select.find('option:gt(1)').remove();	
					
					var selected_dependency_select = $('#element_select_'+dependency_rule+'_'+rule).val();
					var selected_condition_select = $('#condition_select_'+dependency_rule+'_'+rule).val();
					var selected_condition_value = $('#condition_value_'+dependency_rule+'_'+rule).val();

					if (ret.length > 0) {
						for( var i=0; i<ret.length; i++) {
							var option = $('<option>').val(ret[i]['value']).text(ret[i]['name']);
							if (selected_dependency_select == ret[i]['value']) option.attr('selected', 'selected');
							element_value_select.append(option);
						}
						objRule.find('.element_value').show();
					} else { 
						for( var i=0; i<condition_values.length; i++) {
							var option = $('<option>').val(condition_values[i][0]).text(condition_values[i][1]);
							if (selected_condition_select == condition_values[i][0]) option.attr('selected', 'selected');
							condition_select.append(option);
						}						
						objRule.find('.condition').show();
					}

					if (condition_select.val() != 'enabled' && condition_select.val() != 'disabled' && condition_select.val() != 'empty' && condition_select.val() != 'not_empty') condition_value.show().val(selected_condition_value);
					else condition_value.hide();
				}
			});			
		}
		
	});
			
	if (condition_select.val() != 'enabled' && condition_select.val() != 'disabled' && condition_select.val() != 'empty' && condition_select.val() != 'not_empty') condition_value.show();
	else condition_value.hide();
	
	condition_select.on('change', function() {
		condition_value.hide();
		if (condition_select.val() != 'enabled' && condition_select.val() != 'disabled' && condition_select.val() != 'empty' && condition_select.val() != 'not_empty') condition_value.show().val('').attr('placeholder', dependency_condition_placeholder)	
	});
	
	$('#dependency_rule_'+dependency_rule+' .dependency_elements a.error').attr('disabled', true);
	if ($('#dependency_rule_'+dependency_rule+' .dependency_elements').children().length > 1) {
		$('#dependency_rule_'+dependency_rule+' .dependency_elements a.error').attr('disabled', false);
	}
};

