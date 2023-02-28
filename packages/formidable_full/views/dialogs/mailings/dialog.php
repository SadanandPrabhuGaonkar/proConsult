<?php 
	defined('C5_EXECUTE') or die("Access Denied."); 
	$task = $this->controller->getTask();

	$form = Core::make('helper/form');
	
	$ui = new \Concrete\Core\Application\Service\UserInterface();
	?>

<div class="ccm-ui">

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>

	<?php if (!is_object($mailing)) { ?>
		<div class="alert alert-danger">
			<?php echo t('Can\'t find mailing'); ?>
		</div>
	<?php } else { ?>

		<?php if ($task == 'view') { 
			?>
			<form id="mailingForm" method="post" action="">
				<div class="alert alert-danger dialog_message" style="display:none"></div>
					<?php 
		                $tabs = array(
		                    array('from', t('Send from'), true),
		                    array('to', t('Send to')),
		                    array('message', t('Message')),
		                    array('attachments', t('Attachments')),
		                    array('dependency', t('Dependency')),		                    
		                );
		                echo $ui::tabs($tabs);

		                $disabled = true;
						if ($mailing->getMailingID()) $disabled = false;

		                echo (intval($mailing->getFormID())!=0?$form->hidden('formID', $mailing->getFormID()):'');
	                	echo (intval($mailing->getMailingID())!=0?$form->hidden('mailingID', $mailing->getMailingID()):'');
	                	echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_mailing'));
		            ?>
				    <div id="ccm-tab-content-from" class="ccm-tab-content">
	                    <fieldset class="form-horizontal">
	                        <div class="form-group">
	                            <div class="col-sm-12">
	                                <p><?php echo t('Set the "From"-header of the mail. You can either select a form element or add a custom one.'); ?></p>
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <?php echo $form->label('from_name', t('From'), array('class' => 'col-sm-3')) ?>
	                            <div class="col-sm-9">
	                                <?php echo $form->select('from_type', $mailing->getFrom(), $mailing->getFromType())?>
	                                <div class="custom row form-row-pd">
	                                    <div class="col-sm-6">
	                                        <?php echo $form->text('from_name', $mailing->getFromName(), array('placeholder' => t('Name')))?>
	                                    </div>
	                                    <div class="col-sm-6">
	                                        <?php echo $form->text('from_email', $mailing->getFromEmail(), array('placeholder' => t('Email Address')))?>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="form-group reply_to">
	                            <?php echo $form->label('reply_name', t('Reply To'), array('class' => 'col-sm-3')) ?>
	                            <div class="col-sm-9">
	                                <?php echo $form->select('reply_type', $mailing->getReply(), $mailing->getReplyType())?>
	                                <div class="custom row form-row-pd">
	                                    <div class="col-sm-6">
	                                        <?php echo $form->text('reply_name', $mailing->getReplyName(), array('placeholder' => t('Name')))?>
	                                    </div>
	                                    <div class="col-sm-6">
	                                        <?php echo $form->text('reply_email', $mailing->getReplyEmail(), array('placeholder' => t('Email Address')))?>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>

	                    </fieldset>
	                </div>

	                <div id="ccm-tab-content-to" class="ccm-tab-content">
	                    <fieldset class="form-horizontal">
	                        <div class="form-group">
	                            <div class="col-sm-12">
	                                <p><?php echo t('You can send a mail to multiple addresses. You can select "Send to"-elements if you have them in your form. You can also add custom Email Address(es) in the field below.'); ?></p>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <?php echo $form->label('send', t('Send to'), array('class' => 'col-sm-3')) ?>
	                            <div class="col-sm-9">
	                                <?php if (is_array($mailing->getSendTo()) && count($mailing->getSendTo())) { ?>
	                                <div class="input">
	                                    <?php foreach ($mailing->getSendTo() as $key => $option) { ?>
	                                        <div class="checkbox">
												<label>
													 <?php echo $form->checkbox('send[]', $key, @in_array($key, (array)$mailing->send)); ?> 
													 <?php echo $option; ?>
												</label>
											</div>
	                                    <?php } ?>
	                                </div>
	                                <div style="padding: 10px 0px 5px 0px;"><strong><?php echo t('AND/OR'); ?></strong></div>       
	                                <?php } else { ?>
	                                <div class="no_send_to_select">
	                                    <?php echo t('Add an "Email Address" or a "Recipient Selector" element(s) to enable selection of recipient.') ?><br />
	                                    <?php echo t('You must add custom e-mailaddress now:') ?>
	                                </div>
	                                <?php } ?>
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <?php echo $form->label('send', t('E-mail Address(es)'), array('class' => 'col-sm-3')) ?>
	                            <div class="col-sm-9">
	                                <div class="input-group">
	                                    <div class="input-group-addon">
	                                        <?php echo $form->checkbox('send_custom', 1, intval($mailing->getSendCustom()) != 0)?>
	                                    </div>
	                                    <?php echo $form->textarea('send_custom_value', $mailing->getSendCustomValue(), array('style' => 'height: 35px;')); ?>
	                                </div>
	                                <div class="send_custom_note note"><?php echo t('Comma seperate each e-mailaddress') ?></div>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <?php echo $form->label('send_cc', t('Send as CC'), array('class' => 'col-sm-3'))?>
	                            <div class="col-sm-9">
	                                <div class="input-group">
	                                    <div class="checkbox">
											<label>
												 <?php echo $form->checkbox('send_cc', 1, $mailing->getSendType())?>
												 <?php echo t('Use CC instead of BCC (default) for sending mail') ?>
											</label>
										</div>
	                                </div>
	                            </div>
	                        </div>
	                    
	                    </fieldset>
	                </div>

	                <div id="ccm-tab-content-message" class="ccm-tab-content">
	                    <fieldset class="form-horizontal">
	                        <div class="form-group">
	                            <div class="col-sm-12">
	                                <p><?php echo t('Define your mail here. Add a subject and a HTML-message. In your message and subject you can use element values and labels of the form by clicking on "Insert Formidable Element" in the top bar of the editor.'); ?></p>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
								<?php echo $form->label('template', t('Template'), array('class' => 'col-sm-3'))?>
								<div class="col-sm-9">
									<?php if (is_array($mailing->getTemplates()) && count($mailing->getTemplates())) { ?>
										<div class="input-group">
		                                    <div class="input-group-addon">
		                                        <?php echo $form->checkbox('template', 1, intval($mailing->getTemplateID()) != 0)?>
		                                    </div>
		                                    <?php echo $form->select('templateID', $mailing->getTemplates(), $mailing->getTemplateID())?>	
										</div>
									<?php } else { ?>
	                                <div class="no_send_to_select">
	                                    <?php echo t('No templates created yet. Click <a href="%s">here</a> to create one.', URL::to(Page::getByPath('/dashboard/formidable/templates'))) ?>
	                                </div>
	                                <?php } ?>									
								</div>
							</div>

	                        <div class="form-group">
	                            <?php echo $form->label('subject', t('Subject'), array('class' => 'col-sm-3')) ?>
	                            <div class="col-sm-9">
	                                <div class="input-group">
	                                    <?php echo $form->text('subject', $mailing->getSubject()); ?>
	                                    <div class="input-group-addon">
	                                        <a class="launch-tooltip control-label subject_element" data-placement="right" title="" data-original-title="<?php echo t('Insert Formidable Element')?>">
	                                            <i class="fa fa-plus-circle"></i>
	                                        </a>
	                                    </div> 
	                                </div>
	                            </div>
	                        </div>

	                        <div class="form-group">
	                            <?php echo $form->label('message', t('Message'), array('class' => 'col-sm-3')) ?>
	                            <div class="col-sm-9">
	                                <div class="mail_message form-control-editor">
	                                    <?php print $editor->outputStandardEditor('message', Core::make('helper/text')->decodeEntities($mailing->getMessage())); ?>
	                                </div>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <?php echo $form->label('discard_empty', t('Discard empty'), array('class' => 'col-sm-3'))?>
	                            <div class="col-sm-9">
	                                <div class="input-group">
	                                    <div class="checkbox">
											<label>
												 <?php echo $form->checkbox('discard_empty', 1, intval($mailing->getDiscardEmpty()) != 0)?>
												 <?php echo t('If checked empty elements will not be shown in message') ?>
											</label>
										</div>
	                                </div>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <?php echo $form->label('discard_layout', t('Discard layout'), array('class' => 'col-sm-3'))?>
	                            <div class="col-sm-9">
	                                <div class="input-group">
	                                    <div class="checkbox">
											<label>
												 <?php echo $form->checkbox('discard_layout', 1, intval($mailing->getDiscardLayout()) != 0)?>
												 <?php echo t('If checked layout elements will not be shown in message') ?>
											</label>
										</div>
	                                </div>
	                            </div>
	                        </div>
	                      
	                    </fieldset>
	                </div>


	                <div id="ccm-tab-content-dependency" class="ccm-tab-content container-fluid"> 
			        	<fieldset>
			            	<?php if(!$disabled) { ?>
			                    <h5><?php echo t('If you want you can use dependencies for this mailing. This means the mail can be influenced with the behaviour or value of a form element'); ?></h5>
			                    <div id="dependencies_rules" data-next_rule="100"></div>
			                    <input type="button" class="btn btn-default pull-right" onclick="ccmFormidableAddDependency(<?php echo $mailing->getMailingID(); ?>)" value="<?php echo t('Add dependency rule'); ?>">
			    			<?php } else { ?>
			                	 <div class="alert alert-info">
			                     	<strong><?php echo t('Note:'); ?></strong> <?php echo t('You have to save the element first before you can add dependencies to this element.'); ?>
			                     </div>      
			                <?php } ?>
			            </fieldset>
			        </div> 


			        <div id="ccm-tab-content-attachments" class="ccm-tab-content container-fluid"> 
			        	<fieldset>

			        		<div class="form-group">
	                            <?php echo $form->label('attachment', t('Files after upload'), array('class' => 'col-sm-3'))?>
	                            <div class="col-sm-9">
	                                <div class="input-group">
	                                    <?php 
	                                    $elements = $mailing->getAttachmentElements();
	                                    if (is_array($uploadElements) && count($uploadElements)) {
	                                    	foreach ($uploadElements as $element) { 
	                                    ?>
	                                    <div class="checkbox">
											<label>
												 <?php echo $form->checkbox('attachment_elements[]', $element->getElementID(), @in_array($element->getElementID(), (array)$elements))?>
												 <?php echo $element->getLabel() ?>
											</label>
										</div>
										<?php } ?>
										<?php } else { ?>
											<div class="no_send_to_select">
			                                    <?php echo t('Add an "Upload" element to enable selection.') ?><br />
			                                    <?php echo t('You can add files from the filemanager:') ?>
			                                </div>
										<?php } ?>
	                                </div>
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                            <?php echo $form->label('attachment', t('Files from filemanager'), array('class' => 'col-sm-3'))?>
	                            <div class="col-sm-9">
	                                <div class="input-group" style="width:100%">	 
	                                	<?php
		                                	$i = 1; 
		                                	$attachments = $mailing->getAttachmentFiles();
		                                	if (is_array($attachments) && count($attachments)) {
		                                		foreach($attachments as $fID) { ?>
		                                			<div class="input attachment_row">
														<div class="file_selector">
        													<?php echo Core::make('helper/concrete/file_manager')->file('attachment_'.$i, 'attachment_files['.$i.']', t('Choose file'), File::getByID($fID)); ?>
        												</div>
														<div class="input-group-buttons">
															<a href="javascript:;" onclick="ccmFormidableFormMailingAddAttachment($(this));" class="btn btn-success option_button">+</a>
															<a href="javascript:;" onclick="ccmFormidableFormMailingRemoveAttachment($(this));" class="btn btn-danger option_button" <?php if (count($attachments)) {?>disabled="disabled"<?php } ?>>-</a> 
														</div>
													</div>		                                			
		                                			<?php
		                                			$i++;
		                                		}
		                                	}
		                                ?>
	                                </div>
	                                <div class="send_custom_note note"><?php echo t('Be careful with size and extensions, some spamfilters can block your mail.') ?></div>
	                                <div id="new_attachment">
	                                	<?php echo Core::make('helper/concrete/file_manager')->file('attachment_files_counter_tmp', 'attachment_files[counter_tmp]', t('Choose file')); ?> 
	                                </div>
	                            </div>
	                        </div>
			        	</fieldset>
			        </div>

	            </div>

			    <div class="dialog-buttons">
					<button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
					<button type="button" onclick="ccmFormidableCheckFormMailingSubmit();return false;" class="btn btn-primary pull-right"><?php echo t('Save')?></button>
				</div>
			</form>
			
			<script>
				$(function() {
	                ccmFormidableFormMailingCheckSelectors();
	                
	                $('.subject_element').on('click', function() {
	                    ccmFormidableSubjectOverlay(<?php echo $mailing->getFormID() ?>);
	                });                
	                $("select[name=from_type]").change(function() {
	                    ccmFormidableFormMailingCheckSelectors($(this));
	                }); 
	                $("select[name=reply_type]").change(function() {
	                    ccmFormidableFormMailingCheckSelectors($(this));
	                }); 
	                $("input[name=send_custom]").click(function() {
	                    ccmFormidableFormMailingCheckSelectors($(this));
	                });
	                $('input[name=template]').click(function() {
	                    ccmFormidableFormMailingCheckSelectors($(this));
	                });

	                <?php 					
						$dependencies = $mailing->getDependency('initialized');
						if (!empty($dependencies)) {
						foreach((array)$dependencies as $rule => $dependency) { ?>
								setTimeout(function() { ccmFormidableAddDependency('<?php echo $mailing->getMailingID(); ?>', '<?php echo $rule; ?>'); }, <?php echo 200*intval($rule); ?>);
					<?php } } ?>
					
					$("#dependencies_rules").sortable({
						items: ".dependency",
						handle: ".mover",
						sort: function(event, ui) {
							$(this).removeClass( "ui-state-default" );
						},
						stop: function(event, ui) {
							$("#dependencies_rules").find('.dependency').each(function(i, row) {
								$(row).find('span.rule').text(i + 1);
								if (i == 0) $(row).find('div.operator').hide();	
								else $(row).find('div.operator').show();
							});
						}
					});
	            });
			</script>
		<?php } ?>

		<?php if ($task == 'delete') { ?>
			<div class="alert alert-warning">
	            <?php echo t('Are you sure you want to delete this mailing?'); ?>
	        </div>

	        <form data-dialog-form="delete-result" method="post" action="">
	            <?php echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_mailing')); ?>
	            <?php echo $form->hidden('mailingID', $mailing->getMailingID()); ?>
	        </form>

	        <div class="dialog-buttons">
	            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
	            <button type="button" class="btn btn-danger pull-right" name="submit"><?php echo t('Delete')?></button>
	        </div>

	        <script>
				$(function() {	                
	                $('button[name=submit]').click(function() {
	                    ccmFormidableDeleteMailing(<?php echo $mailing->getMailingID(); ?>);
	                    jQuery.fn.dialog.closeTop();
	                });
	            });
			</script>

		<?php } ?>
	<?php } ?>
<?php } ?>	
</div>