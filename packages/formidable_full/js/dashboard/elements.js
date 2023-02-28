// JavaScript Document
var txt_min_height = 34;
var txt_max_height = 102;
var toggle_delay = 350;

var ccmFormidableFormElementCheckSelectors = function(s) {
	if (s != undefined && s.length > 0 && typeof(s) !== 'object') { return false; }

	$('input.option_default').mousedown(function() {
		$(this).data('wasChecked', this.checked);
	});
	$('input.option_default').click(function() {
		if ($(this).data('wasChecked')) this.checked = false;
	});	
	
	if ($('input[name="required"]').is(':checked')) $('div.input-subgroup.required').slideDown();
	else $('div.input-subgroup.required').slideUp();

	if ($('input[name="confirmation"]').is(':checked')) $('div.input-subgroup.confirmation').slideDown();
	else $('div.input-subgroup.confirmation').slideUp();

	if ($('input[name="default_value"]').is(':checked')) {
		$('select[name="default_value_type"]').attr('disabled', false);			
		if ($('select[name="default_value_type"]').val() == 'value') {			
			$('div[id="default_value_type_value"]').slideDown().find('input[name="default_value_value"], textarea[name="default_value_value"]').attr('disabled', false).next('.note, .note_attribute').slideDown();
			if ($('input[name="default_value_value"]').attr('data-mask'))$('input[id="default_value_value"]').mask($('input[name="default_value_value"]').attr('data-mask'));
			
			if ((s && s.attr('name') == 'default_value_type') || (s && s.attr('name') == 'default_value')) {
				$('input[name="default_value_value"], textarea[name="default_value_value"]').focus();
				$('select[name="default_value_attribute"], input[name="default_value_request"]').val('').attr('disabled', true).next('.note, .note_attribute').slideUp();
			}			
			$('div[id="default_value_type_request"], div.default_value_type_attribute').slideUp();
					
		} else if ($('select[name="default_value_type"]').val() == 'request') {
			
			$('div[id="default_value_type_request"]').slideDown().find('input[name="default_value_request"]').attr('disabled', false).next('.note, .note_attribute').slideDown();

			if ($('input[name="default_value_request"]').attr('data-mask'))
				$('input[id="default_value_request"]').mask($('input[name="default_value_request"]').attr('data-mask'));

			if ((s && s.attr('name') == 'default_value_type') || (s && s.attr('name') == 'default_value')) {
				$('input[name="default_value_request"]').focus();
				$('select[name="default_value_attribute"], input[name="default_value_value"], textarea[name="default_value_value"]').val('').attr('disabled', true).next('.note, .note_attribute').slideUp();
			}			
			$('div[id="default_value_type_value"], div.default_value_type_attribute').slideUp();
					
		} else {
				
			var selected = $('select[name="default_value_type"]').val();
				
			$('div[id="default_value_type_'+selected+'"]').slideDown().find('select[name="default_value_attribute"]').attr('disabled', false).next('.note, .note_attribute').slideDown();

			if ((s && s.attr('name') == 'default_value_type') || (s && s.attr('name') == 'default_value')) {
				$('select[name="default_value_attribute"]').focus();
				$('input[name="default_value_value"], textarea[name="default_value_value"]').val('').attr('disabled', true).next('.note, .note_attribute').slideUp();
			}		
			$('div[id="default_value_type_value"], div[id="default_value_type_request"], div.default_value_type_attribute:not([id="default_value_type_'+selected+'"])').slideUp();
		}
		
	} else {
		$('select[name="default_value_type"]').attr('disabled', true);			
		$('div[id="default_value_type_value"], div[id="default_value_type_request"], div.default_value_type_attribute').slideUp();
		$('input[name="default_value_value"], div[id="default_value_type_request"], textarea[name="default_value_value"], select[name="default_value_attribute"]').val('').attr('disabled', true).next('.note, .note_attribute').slideUp();
	}
	
	var element = $('input[name="placeholder_value"]');
	if ($('input[name="placeholder"]').is(':checked')) {
		element.attr('disabled', false);
		$('.placeholder_note').slideDown();
		if (s && s.attr('name') == 'placeholder') element.focus();
	} else {
		element.val('').attr('disabled', true);
		$('.placeholder_note').slideUp();
	}
		
	if ($('input[name="min_max"]').is(':checked')) {
		$('input[name="min_value"], input[name="max_value"], select[name="min_max_type"]').attr('disabled', false);
		if (s && s.attr('name') == 'min_max') $('input[name="min_value"]').focus();
		$('div.input-subgroup.min_max:not(.'+$("select[name=min_max_type]").val()+')').slideUp();
		$('div.input-subgroup.min_max.'+$("select[name=min_max_type]").val()).slideDown();
	} else {
		$('input[name="min_value"], input[name="max_value"]').val('').attr('disabled', true);
		$('select[name="min_max_type"]').attr('disabled', true);
		$('div.input-subgroup.min_max').slideUp();
	}

	if ($.find('select[name="mask_format"]').length == 1) {
		var element = $('select[name="mask_format"]');
		if ($('input[name="mask"]').is(':checked')) {
			element.attr('disabled', false); $('.mask_note').slideDown();
			if (s && s.attr('name') == 'mask') element.focus().find('option:first').attr('selected', true);
		} else { 
			element.attr('disabled', true).val(''); $('.mask_note').slideUp();
			element.find('option:selected').removeAttr('selected');
		}
	} else if ($.find('input[name="mask_format"]').length == 1) {
		var element = $('input[name="mask_format"]');
		if ($('input[name="mask"]').is(':checked')) {
			element.attr('disabled', false); $('.mask_note').slideDown();
			if (s && s.attr('name') == 'mask') element.focus().val(element.attr('placeholder'));
		} else {
			element.attr('disabled', true).val(''); $('.mask_note').slideUp();
		}
	}
	
	var element = $('select[id="chars_allowed_value"]');
	if ($('input[name="chars_allowed"]').is(':checked')) {
		element.attr('disabled', false).animate({height:txt_max_height+'px'}, toggle_delay); $('.chars_allowed_note').slideDown();
		if (s && s.attr('name') == 'chars_allowed') element.focus().find('option:first').attr('selected', true);
	} else {
		element.attr('disabled', true).animate({height:txt_min_height+'px'}, toggle_delay).find('option:selected').removeAttr('selected'); $('.chars_allowed_note').slideUp();
	}
	
	var element = $('textarea[name="tooltip_value"]');
	if ($('input[name="tooltip"]').is(':checked')) {
		element.attr('disabled', false);
		if (s && s.attr('name') == 'tooltip') element.focus().animate({height: txt_max_height}, toggle_delay);
	} else {
		element.val('').attr('disabled', true);
		if (s && s.attr('name') == 'tooltip') element.animate({height: txt_min_height}, toggle_delay);
		else element.height(txt_min_height);
	}
	
	if ($('input[name="option_other"]').is(':checked')) {
		$('input[name="option_other_value"]').attr('disabled', false);
		$('select[name="option_other_type"]').attr('disabled', false); $('.option_other_note').slideDown();
		if (s && s.attr('name') == 'option_other') {
			$('input[name="option_other_value"]').focus();
			$('select[name="option_other_type"]').find('option:first').attr('selected', true);
		}
	} else {
		$('input[name="option_other_value"]').attr('disabled', true);
		$('select[name="option_other_type"]').attr('disabled', true).val(''); $('.option_other_note').slideUp();
	}
	
	
	if ($.find('input[name="multiple"]').length > 0) {
		if ($('input[name="multiple"]').is(':checked')) {
			$('.element_options').find('input[type=radio]').each(function() {
				var label = $(this).parent('.input-group-addon');
				$(this).detach().attr('type', 'checkbox').appendTo(label);
			});
			if (s && s.attr('name') == 'multiple') {
				if ($.find('input[name="min_max"]').length > 0) {
					$('input[name="min_max"]').parents('.form-group').slideDown();	
				}
			}
		} else {
			$('.element_options').find('input[type=checkbox]').each(function(i, element) {
				var label = $(this).parent('.input-group-addon');
				$(this).detach().attr('type', 'radio').appendTo(label);
			});
			if ($.find('input[name="min_max"]').length > 0) {
				$('input[name="min_max"]').attr('checked', false).parents('.form-group').slideUp();
				$('input[name="min_value"], input[name="max_value"]').val('').attr('disabled', true);
				$('select[name="min_max_type"]').attr('disabled', true);		
			}
		}
	}
	
	var element = $('textarea[name="allowed_extensions_value"]');
	if ($('input[name="allowed_extensions"]').is(':checked')) {
		element.attr('disabled', false); $('.allowed_extensions_note').slideDown();
		if (s && s.attr('name') == 'allowed_extensions') element.focus().val(element.attr('placeholder'));
	} else {
		element.val('').attr('disabled', true); $('.allowed_extensions_note').slideUp();
	}
		
	var element = $('select[name="fileset_value"]');
	if ($('input[name="fileset"]').is(':checked')) {
		element.attr('disabled', false); $('.fileset_note').slideDown();
		if (s && s.attr('name') == 'fileset') element.focus().find('option:first').attr('selected', true);
	} else {
		element.attr('disabled', true).val(''); $('.fileset_note').slideUp();
	}
	
	var element = $('textarea[name="advanced_value"]');
	if ($('input[name="advanced"]').is(':checked')) {
		element.attr('disabled', false);$('.advanced_note').slideDown();
		if (s && s.attr('name') == 'advanced') element.focus().animate({height: txt_max_height}, toggle_delay);
	} else {
		element.val('').attr('disabled', true);$('.advanced_note').slideUp();		
		if (s && s.attr('name') == 'advanced') element.animate({height: txt_min_height}, toggle_delay);
		else element.height(txt_min_height);
	}

	if ($('select[name="format"]').val() == 'other') {
		$('input[name="format_other"]').attr('disabled', false); $('.format_note').slideDown();
		if (s && s.attr('name') == 'format') $('input[name="format"]').focus();
	} else {
		$('input[name="format_other"]').attr('disabled', true).val(''); $('.format_note').slideUp();	
	}
	

	if ( $('select[name="appearance"]').val() != 'picker' && $('select[name="appearance"]').val() != 'slider' && $('[id="element_type"]').val() != 'slider' && $('[id="element_type"]').val() != 'rating') {
		if (s && s.attr('name') == 'appearance') {
			if ($.find('input[name="advanced"]').length > 0) {
				$('input[name="advanced"]').attr('checked', false).parents('.form-group').slideUp();
				$('textarea[name="advanced_value"]').val('').attr('disabled', true);
			}
		} else {
			$('textarea[name="advanced_value"]').attr('disabled', true).val(''); $('.advanced_note').slideUp();
			$('input[name="advanced"]').attr('checked', false).parents('.form-group').slideUp();				
		}
	} else {
		if ($.find('input[name="advanced"]').length > 0) {
			$('input[name="advanced"]').parents('.form-group').slideDown();	
		}
	}
	
	var element = $('input[name="css_value"]');
	if ($('input[name="css"]').is(':checked')) {
		element.attr('disabled', false); $('.css_note').slideDown();
		if (s && s.attr('name') == 'default') element.focus();
	} else {
		element.attr('disabled', true).val(''); $('.css_note').slideUp();
	}
		
	if ($('input[name="submission_update"]').is(':checked')) {		
		$('select[name="submission_update_type"], input[name="submission_update_empty"]').attr('disabled', false);			
		$('div.submission_update').slideDown();
		
		var selected = $('select[name="submission_update_type"]').val();			
		$('div[id="submission_update_type_'+selected+'"]').slideDown().find('select[name="submission_update_attribute"]').attr('disabled', false);
		
		if ((s && s.attr('name') == 'submission_update_type') || (s && s.attr('name') == 'submission_update')) {
			$('select[name="submission_update_attribute"]').focus();							
		}
		$('div.submission_update_type_attribute:not([id="submission_update_type_'+selected+'"])').slideUp( function() { $(this).css('display','none') } );
	
	} else {			
		$('div.submission_update_type_attribute, div.submission_update').slideUp( function() { $(this).css('display','none') } );
		$('select[name="submission_update_type"], select[name="submission_update_attribute"], input[name="submission_update_empty"]').val('').attr('disabled', true);
	}

	if (parseInt($('select[name="errors"]').val()) == 1) $('div[id="errors_div"]').slideDown();
	else $('div[id="errors_div"]').slideUp();
};

var ccmFormidableFormElementAddOptions = function(s, value, name) {
	if (s instanceof jQuery) {} else { s = $(s); }
	
	var isParent = false;
	if (s.hasClass('element_options')) isParent = true;

	if (value == undefined) value = '';
	if (name == undefined) name = '';

	option_counter++;
	
	var element_type = 'radio';
	var second_text = false;
	switch ($('input[name="element_type"]').val()) {
		case 'checkbox': 
			element_type = 'checkbox';
		break;
		case 'select': 
			if ($('input[name="multiple"]').is(':checked')) element_type = 'checkbox';
		break;
		case 'recipientselector': 
			if($('input[name="multiple"]').is(':checked')) element_type = 'checkbox';
			second_text = true;	
		break;
	}
	
	var option = '';
	option += '<div class="input-group-addon">';
	option += '<i class="mover fa fa-arrows"></i>';
	option += '</div>';
	option += '<div class="input-group-addon">';
	option += '<input type="'+element_type+'" name="options_selected[]" value="'+option_counter+'" class="option_default ccm-input-radio">';
	option += '</div>';
	if (second_text) {
		option += '<input type="text" name="options_name['+option_counter+']" value="'+name+'" style="width: 44%; margin-right: 1%;" placeholder="'+placeholder_name+'" class="form-control ccm-input-text">';
		option += '<input type="text" name="options_value['+option_counter+']" value="'+value+'" style="width: 55%;" placeholder="'+placeholder_email+'" class="form-control ccm-input-text">';
	} else {
		option += '<input type="text" name="options_name['+option_counter+']" value="'+value+'" placeholder="'+placeholder_option+'" class="form-control ccm-input-text">';
	}
	option += '<div class="input-group-buttons">';
	option += '<a href="javascript:;" onclick="ccmFormidableFormElementAddOptions(this);" class="btn btn-success option_button">+</a> ';
	option += '<a href="javascript:;" onclick="ccmFormidableFormElementRemoveOptions(this);" class="btn btn-danger option_button">-</a>';
	option += '</div>';
	
	var new_option = $('<div>').addClass('input-group option_row').append($(option));
	if (!isParent) s.closest('.input-group').after(new_option);
	else s.append(new_option);
	
	$('input.option_default').mousedown(function() {
		$(this).data('wasChecked', this.checked);
	});
	$('input.option_default').click(function() {
		if ($(this).data('wasChecked')) this.checked = false;
	});

	if (!isParent) {
		var element_options = s.closest('.element_options')
		element_options.find('.error').attr('disabled', false);	
	}
};

var ccmFormidableFormElementRemoveOptions = function(s) {
	if (s instanceof jQuery) {} else { s = $(s); }
	var element_options = s.parents('.element_options');
	if (element_options.children('.input-group').length > 1) s.parents('.input-group').remove();
	
	if (element_options.find('.input-group').length == 1) element_options.find('.error').attr('disabled', true);	
};

var ccmFormidableLoadElements = function() {
	$.ajax({ 
		type: "POST",
		url: list_url,
		data: 'formID='+formID+'&ccm_token='+formidable_security_token_element,
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(html){
			$('#ccm-element-list div').remove();
			if (html) {
				$('#ccm-element-list').append(html);
				$('#ccm-element-list div.element_row_wrapper:not(.hide)').fadeIn();
				$('div.placeholder, div.loader').fadeOut();
				ccmFormidableCreateMenu();
			}
			else 
				ccmFormidableLoadPlaceholder(true);
		}
	});	
};

var ccmFormidableOpenElementDialog = function(element_type, element_text, layout_id, elementID) {
	jQuery.fn.dialog.closeTop();
	var query_string = "element_type="+element_type+"&element_text="+element_text+"&layoutID="+layout_id+"&formID="+formID+'&ccm_token='+formidable_security_token_element;
	if (element_type == 'line' || element_type == 'hr') {
		ccmFormidableSaveElement(query_string+"&label="+element_text+'&ccm_token='+formidable_security_token_element);
	} else {
		if (parseInt(elementID)!=0 && elementID!=undefined) query_string += "&elementID="+elementID;
		jQuery.fn.dialog.open({ 
			width: '90%',
			height: '75%',
			modal: true,
			href: dialog_url+"?"+query_string,
			title: (parseInt(elementID)!=0 && elementID!=undefined)?element_message_edit:element_message_add		
		});
	}
};

var ccmFormidableAddElementToForm = function() {
	var data = $('#elementForm').serialize();
	ccmFormidableSaveElement(data);
	jQuery.fn.dialog.closeTop();
};

var ccmFormidableCheckFormElementSubmit = function() {	
	var errors = [];	
	$('#dependencies_rules [disabled]').attr('disabled', false);
	var data = $('#elementForm').serialize();
	$.ajax({ 
		type: "POST",
		url: tools_url+'/validate',
		data: data,
		dataType: 'json',
		success: function(ret) {
			if (ret.type == 'error') {
				var message = $('div.dialog_message').empty();
				$.each(ret.message, function(i, row) {
					message.append(row+'<br />').show();
				});
				$('div.element-body').scrollTop(0);
			} else ccmFormidableAddElementToForm();
		}
	});	
};

var ccmFormidableSaveElement = function(data) {
	ccmFormidableActionElement('save', data);
};

var ccmFormidableDuplicateElement = function(elementID) {
	data = 'elementID='+elementID;
	ccmFormidableActionElement('duplicate', data);
};

var ccmFormidableDeleteElementDialog = function(elementID) {
	var query_string = '?elementID='+elementID+'&formID='+formID+'&ccm_token='+formidable_security_token_element;
	jQuery.fn.dialog.open({ 
		width: 520,
		height: 100,
		modal: true,
		href: dialog_url+'/delete'+query_string,
		title: element_message_delete
	});
};

var ccmFormidableDeleteElement = function(elementID) {
	data = 'elementID='+elementID;
	ccmFormidableActionElement('delete', data);
};

var ccmFormidableAddBulkOptions = function(elementID) {
	var query_string = '?formID='+formID+'&ccm_token='+formidable_security_token_element;
	jQuery.fn.dialog.open({ 
		width: 520,
		height: '75%',
		modal: true,
		href: dialog_url+'/bulk'+query_string,
		title: element_message_bulk
	});
};

var ccmFormidableAddOptionsToElement = function() {	
	var errors = [];	
	var data = $('#elementBulkForm').serialize();
	$.ajax({ 
		type: "POST",
		url: tools_url+'/bulk',
		data: data,
		dataType: 'json',
		success: function(ret) {
			if (ret.type == 'error') {
				var message = $('div.dialog_message').empty();
				$.each(ret.message, function(i, row) {
					message.append(row+'<br />').show();
				});
				$('div.element-body').scrollTop(0);
			} else {
				var holder = $('div.element_options');
				if (ret.clear == 1) $('div.element_options div.option_row').remove();
				$.each(ret.options, function(i, row) {
					ccmFormidableFormElementAddOptions(holder, row, row);
				});
				jQuery.fn.dialog.closeTop();
			}
		}
	});	
};

var ccmFormidableClearOptions = function() {
	$('div.element_options div.option_row').remove();
	ccmFormidableFormElementAddOptions($('div.element_options'));
}	

var ccmFormidableActionElement = function(action, data) {
	data += '&ccm_token='+formidable_security_token_element;
	$.ajax({ 
		type: "POST",
		url: tools_url+'/'+action,
		data: data,
		dataType: 'json',
		beforeSend: function () {
			ccmFormidableLoadLoader(true);
		},
		success: function(ret) {
			ccmFormidableLoadElements();
			ccmFormidableSetMessage(ret.type, ret.message)
		}
	});		
};

var ccmFormidableAddDependency = function(elementID, rule) {	
	var objDep = $('#dependencies_rules');
	var addAction = false;
	if (rule === undefined) {
		addAction = true;
		rule = parseInt(objDep.attr('data-next_rule'));
	}
	$.ajax({ 
		type: "POST",
		url: dependency_url+'/add',
		data: 'elementID='+elementID+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency,
		dataType: 'html',
		success: function(ret) {
			objDep.append(ret).attr('data-next_rule', rule+1);
			ccmFormidableInitDependency(elementID, rule);
			if (addAction) {
				ccmFormidableAddDependencyAction(elementID, rule);
			}
		}
	});		
};

var ccmFormidableInitDependency = function(elementID, rule) {
	var objRule = $('#dependency_rule_'+rule);		
	objRule.find('.dependency_elements, .operator').hide();	
	if ($('#dependencies_rules').children('fieldset').length > 1 && rule > 0) {
		objRule.find('.operator').show();
	}
	$('#dependencies_rules').find('.dependency').each(function(i, row) {
		$(row).find('.rule').text(i + 1);
	});
};

var ccmFormidableDeleteDependencyDialog = function(elementID, rule) {
	var query_string = '?elementID='+elementID+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency;
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

var ccmFormidableAddDependencyAction = function(elementID, dependency_rule, rule) {	
	var objDep = $('#dependency_rule_'+dependency_rule+' .dependency_actions');
	if (rule === undefined) rule = parseInt(objDep.attr('data-next_rule'));
	$.ajax({ 
		type: "POST",
		url: dependency_url+'/action',
		data: 'elementID='+elementID+'&dependency_rule='+dependency_rule+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency,
		dataType: 'html',
		success: function(ret) {
			objDep.append(ret).attr('data-next_rule', rule+1);
			ccmFormidableInitDependencyAction(elementID, dependency_rule, rule);
		}
	});		
};

var ccmFormidableDeleteDependencyAction = function(dependency_rule, rule) {
	if ($('#dependency_rule_'+dependency_rule+' .dependency_actions').children().length == 1) {
		return false;
	}		
	var objRule = $('#dependency_rule_'+dependency_rule+' #action_'+rule);
	objRule.remove();	
	if ($('#dependency_rule_'+dependency_rule+' .dependency_actions').children().length == 1) {
		$('#dependency_rule_'+dependency_rule+' .dependency_actions a.error').attr('disabled', true);
	}	
	objNext = $('#dependency_rule_'+dependency_rule+' .dependency_actions').children(':first');
	objNext.find('span.action_label').hide();	
};	

var ccmFormidableInitDependencyAction = function(elementID, dependency_rule, rule) {
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
		if (action.val() == 'class' || action.val() == 'placeholder') {
			action_value.show();
		}			
		if (action.val() == 'value') {
			if (action_value_select.find('option').length > 0) {
				action_value_select.show();			
			}
			else {
				action_value.show();
			}
		}
	}
	
	action.on('change', function() {
		action.val(action.val());
		action_value.hide();
		action_value_select.hide();
		if (action.val() != '') {
			objRule.parents('.dependency').find('.dependency_elements').show();
			if (objRule.parents('.dependency').find('.dependency_elements').children().length < 1)
				ccmFormidableAddDependencyElement(elementID, dependency_rule);
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


var ccmFormidableAddDependencyElement = function(elementID, dependency_rule, rule) {	
	var query = '';
	var objDep = $('#dependency_rule_'+dependency_rule+' .dependency_elements');
	if (rule === undefined) rule = parseInt(objDep.attr('data-next_rule'));
	$.ajax({ 
		type: "POST",
		url: dependency_url+'/element',
		data: 'elementID='+elementID+'&dependency_rule='+dependency_rule+'&rule='+rule+'&ccm_token='+formidable_security_token_dependency,
		dataType: 'html',
		success: function(ret) {
			objDep.append(ret).attr('data-next_rule', rule+1);
			ccmFormidableInitDependencyElement(elementID, dependency_rule, rule);
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

var ccmFormidableInitDependencyElement = function(elementID, dependency_rule, rule) {
	
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
				url: tools_url+'/options',
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

	//element_select.trigger('change');
};