<?php 
	defined('C5_EXECUTE') or die("Access Denied."); 
	$task = $this->controller->getTask();
	$form = Core::make('helper/form');	
	$editor = Core::make('editor');
	$ui = new \Concrete\Core\Application\Service\UserInterface();

	use Concrete\Package\FormidableFull\Src\Formidable\Validator\Result as ValidatorResult;

	$err = new ValidatorResult();
?>

<div class="ccm-ui">

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>

	<?php if ($task == 'view') { ?>

		<?php if (!is_object($element)) { ?>
			<div class="alert alert-danger">
				<?php echo t('Can\'t find element'); ?>	
			</div>
		<?php }
			else {			
				$disabled = true;
				if ($element->getElementID()) $disabled = false;
				?>
				<form id="elementForm" method="post" action="">
					<div class="alert alert-danger dialog_message" style="display:none"></div>
					<?php 
		                $tabs = array(
		                	array('properties', t('Properties'), true),
		                	array('dependency', t('Dependency'))
		                );
		                if ($element->getProperty('handling') !== false) $tabs[] = array('handling', t('On submit'));
		                echo $ui::tabs($tabs);

		                echo $element->getFormID()!=0?$form->hidden('formID', $element->getFormID()):'';
		                echo $element->getLayoutID()!=0?$form->hidden('layoutID', $element->getLayoutID()):'';
		                echo $element->getElementID()!=0?$form->hidden('elementID', $element->getElementID()):'';
		                echo $form->hidden('element_text', $element->getElementText());
		                echo $form->hidden('element_type', $element->getElementType());
		                echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_element'));
		                ?>
				        <div id="ccm-tab-content-properties" class="ccm-tab-content container-fluid">        
					        <fieldset class="form-horizontal">
					        	
								<h5><?php echo t('Please set the properties of this element. Each element has his own behaviour and/or values.'); ?></h5>

								<div class="form-group">
									<?php echo $form->label('element_type_disabled', t('Type'), array('class' => 'col-sm-3')) ?>
									<div class="col-sm-9">
										<?php echo $form->text('element_type_disabled', $element->getElementText(), array('disabled' => true))?>
									</div>
								</div>
								<?php if ($element->getProperty('label')) { ?>
									<div class="form-group">
										<?php echo $form->label('label', t('Label / Name'), array('class' => 'col-sm-3')) ?>
										<div class="col-sm-9">
											<?php echo $form->text('label', $element->getLabel())?>
										</div>
									</div>
								<?php } ?>
							
								<?php if ($element->getProperty('label_hide')) { ?> 
									<div class="form-group">
										<?php echo $form->label('label_hide', t('Hide label / name'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="checkbox">
												<label>
													<?php echo $form->checkbox('label_hide', 1, intval($element->getLabelHide()) != 0)?>
													<?php echo t('When enabled, the label or name of the form element will not be displayed'); ?>
												</label>
											</div>
										</div>
									</div>
								<?php } ?>
							
								<?php if ($element->getProperty('confirmation')) { ?> 
									<div class="form-group">
										<?php echo $form->label('confirmation', t('Confirmation'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="checkbox">
												<label>
													<?php echo $form->checkbox('confirmation', 1, intval($element->getPropertyValue('confirmation')) != 0)?>
													<?php echo t('When enabled, duplicates field and compare both values'); ?>
												</label>
											</div>
										</div>
									</div>
								<?php } ?>    
							
								<?php if ($element->getProperty('required')) { ?> 
									<div class="form-group">
										<?php echo $form->label('required', t('Required'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="checkbox">
												<label>
													<?php echo $form->checkbox('required', 1, intval($element->getPropertyValue('required')) != 0)?>
													<?php echo t('When enabled, the submitted value will be required and checked'); ?>
												</label>
											</div>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('placeholder')) { ?>
									<div class="form-group">
										<?php echo $form->label('placeholder', t('Placeholder'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('placeholder', 1, intval($element->getPropertyValue('placeholder')) != 0)?></div>
												<?php echo $form->text('placeholder_value', $element->getPropertyValue('placeholder_value')); ?>
											</div>
											<?php if ($element->getProperty('placeholder', 'note')) { ?>
												<div class="note placeholder_note">
													<?php echo @implode('<br />', $element->getProperty('placeholder', 'note')); ?>
												</div>	   
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							
								<?php if ($element->getProperty('default')) { ?>
									<div class="form-group">
										<?php echo $form->label('default_value', t('Default value'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('default_value', 1, intval($element->getPropertyValue('default_value')) != 0)?></div>
												<?php echo $form->select('default_value_type', array('value' => t('Value'), 'request' => t('Request Data ($_REQUEST)'), 'user_attribute' => t('User Data'), 'collection_attribute' => t('Collection Data')), $element->getPropertyValue('default_value_type')); ?>

												<div id="default_value_type_value">
													<?php 
													if ($element->getProperty('default', 'type') == 'textarea') {
														echo $form->textarea('default_value_value', $element->getPropertyValue('default_value_value'), array('style' => 'height: 100px;'));
													} else {
														echo $form->text('default_value_value', $element->getPropertyValue('default_value_value'), array('data-mask' => $element->getProperty('default', 'mask')));
													}
													?>
													<?php if ($element->getProperty('default', 'note')) { ?>
														<div class="note addon">
															<?php echo @implode('<br />', $element->getProperty('default', 'note')); ?>
														</div>	   
													<?php } ?>
												</div>

												<div id="default_value_type_request">				
													<?php 
													echo $form->text('default_value_request', $element->getPropertyValue('default_value_value'), array('data-mask' => '*?*************************************************'));
													?>
													<?php if ($element->getProperty('default', 'note')) { ?>
														<div class="note addon">
															<?php echo @implode('<br />', $element->getProperty('default', 'note')); ?>
														</div>	   
													<?php } ?>												
												</div>

												<div id="default_value_type_user_attribute" class="default_value_type_attribute">
													<select name="default_value_user_attribute" id="default_value_user_attribute" class="form-control">
							                        	<optgroup label="<?php echo t('Properties'); ?>">
							                            	<?php 
																$_select = array(
																	'user_id' => t('User ID'),
																	'user_name' => t('Username'),
																	'user_email' => t('Email Address'),
																	'user_date_added' => t('Date added')
																);
																foreach ($_select as $v => $n) {
																	$_sel = '';
																	if ($v == $element->getPropertyValue('default_value_value')) $_sel = 'selected';
																	echo '<option value="'.$v.'" '.$_sel.'>'.$n.'</option>';	
																}	
															?>							
							                            </optgroup>
							                            <option></option>
							                            <optgroup label="<?php echo t('Attributes'); ?>">
							                            <?php 
															$attribs = UserAttributeKey::getList();
															if (is_array($attribs) && count($attribs)) {
																foreach($attribs as $at) {										
																	$_sel = '';
																	if ('ak_'.$at->getAttributeKeyHandle() == $element->getPropertyValue('default_value_value')) $_sel = 'selected';
																	echo '<option value="ak_'.$at->getAttributeKeyHandle().'" '.$_sel.'>'.$at->getAttributeKeyName().'</option>';
																}
															}
														?>
							                            </optgroup>
							                        </select>
							                        <?php if ($element->getProperty('default', 'note_attribute')) { ?>
														<div class="note_attribute addon">
															<?php echo @implode('<br />', $element->getProperty('default', 'note_attribute')); ?>
														</div>
													<?php } ?>
												</div>

												<div id="default_value_type_collection_attribute" class="default_value_type_attribute">
													<select name="default_value_collection_attribute" id="default_value_collection_attribute" class="form-control">
							                        	<optgroup label="<?php echo t('Properties'); ?>">
							                            	<?php 
																$_select = array(
																	'collection_id' => t('Collection ID'),
																	'collection_name' => t('Name'),
																	'collection_handle' => t('Handle'),
																	'collection_type_id' => t('Page Type (ID)'),
																	'collection_date_added' => t('Date added')
																);
																foreach($_select as $v => $n) {
																	$_sel = '';
																	if ($v == $element->getPropertyValue('default_value_value')) $_sel = 'selected';
																	echo '<option value="'.$v.'" '.$_sel.'>'.$n.'</option>';	
																}	
															?>							
							                            </optgroup>
							                            <option></option>
							                            <optgroup label="<?php echo t('Attributes'); ?>">
							                            <?php 
															$attribs = CollectionAttributeKey::getList();
															if (is_array($attribs) && count($attribs)) {
																foreach ($attribs as $at) {
																	$_sel = '';
																	if ('ak_'.$at->getAttributeKeyHandle() == $element->getPropertyValue('default_value_value')) $_sel = 'selected';
																	echo '<option value="ak_'.$at->getAttributeKeyHandle().'" '.$_sel.'>'.$at->getAttributeKeyName().'</option>';
																}
															}
														?>
							                            </optgroup>
							                        </select>
							                        <?php if ($element->getProperty('default', 'note_attribute')) { ?>
														<div class="note_attribute addon">
															<?php echo @implode('<br />', $element->getProperty('default', 'note_attribute')); ?>
														</div>	   
													<?php } ?>
												</div>

						       				</div>
						       			</div>
									</div>
								<?php } ?>
								
						        <?php if ($element->getProperty('content')) { ?>
									<div class="form-group">
										<?php echo $form->label('content', t('Content'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<?php echo $form->textarea('content', $element->getPropertyValue('content')); ?>
										</div>
									</div>
								<?php } ?>
						        
								<?php if ($element->getProperty('code')) { ?>
									<div class="form-group">
										<?php echo $form->label('code_value', t('Code'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="alert alert-warning"><strong><?php echo t('Warning:'); ?></strong> <?php echo t('adding code to the form could break the form. Use this only if you know what you are doing!'); ?></div>
											<div id="code_value" style="width: 100%; border: 1px solid #eee; height: 490px;"><?php echo $element->getPropertyValue('code_value') ?></div>
											<textarea style="display: none" id="code_value-textarea" name="code_value"></textarea>
										</div>
									</div>
								<?php }  ?>

								<?php if ($element->getProperty('html')) { ?>
									<div class="form-group">
										<?php echo $form->label('html_value', t('Content'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<?php print $editor->outputStandardEditor('html_value', Core::make('helper/text')->decodeEntities($element->getPropertyValue('html_value'))); ?>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('options')) { ?>
									<div class="form-group">
										<?php echo $form->label('element_option', t('Options'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="element_options">
												<?php 
													$i = 1; 
													$options = $element->getPropertyValue('options');
													if (!$options) $options = array(array('selected' => 0, 'name' => ''));
													foreach ($options as $opt) { ?>
													<div class="input-group option_row">
														<div class="input-group-addon">
															<i class="mover fa fa-arrows"></i>
														</div>	
														<div class="input-group-addon border-right">
															<?php 
															if ($element->getElementType() == 'checkbox' || ($element->getElementType() == 'select' && intval($element->getPropertyValue('multiple')) != 0) || ($element->getElementType() == 'recipientselector' && intval($element->getPropertyValue('multiple')) != 0)) {
																echo $form->checkbox('options_selected[]', $i, $opt['selected'], array('class' => 'option_default'));
															} else {
																echo $form->radio('options_selected[]', $i, $opt['selected']?$i:'', array('class' => 'option_default'));
															} 
															?>
														</div>	
														<?php 															
															//echo $form->text('options_name['.$i.']', $opt['name'], array('style' => 'width: 44%; margin-right: 1%;', 'placeholder' => t('Name')));
															//echo $form->text('options_value['.$i.']', $opt['value'], array('style' => 'width: 55%;', 'placeholder' => t('E-mailaddress')));
															echo $form->text('options_name['.$i.']', $opt['name'], array('placeholder' => t('Option')));															
														?>

														<div class="input-group-buttons">
															<a href="javascript:;" onclick="ccmFormidableFormElementAddOptions(this);" class="btn btn-success option_button">+</a>
															<a href="javascript:;" onclick="ccmFormidableFormElementRemoveOptions(this);" class="btn btn-danger option_button" <?php if (is_array($element->getPropertyValue('options')) && count($element->getPropertyValue('options')) <= 1) {?>disabled="disabled"<?php } ?>>-</a>
														</div>
													</div>
												<?php $i++; ?> 
												<?php } ?>
											</div>
											<?php if ($element->getProperty('option_other')) { ?>
												<div class="input-group">
													<div class="input-group-addon"><?php echo $form->checkbox('option_other', 1, intval($element->getPropertyValue('option_other')) != 0)?></div>
													<?php echo $form->text('option_other_value', $element->getPropertyValue('option_other_value'), array('style'=>'width: 55%; margin-right: 1%;', 'placeholder' => t('Other text'))); ?>
													<?php echo $form->select('option_other_type', $element->getProperty('option_other'), $element->getPropertyValue('option_other_type'), array('style'=>'width: 44%;'))?>
												</div>
												<div class="note option_other_note"><?php echo t('When enabled, user can add a new option.') ?></div>
											<?php } ?>

											<div class="extrabuttons">												
												<a class="btn btn-danger btn-sm pull-right" onclick="ccmFormidableClearOptions();"><?php echo t('Clear all'); ?></a>												
												<a class="btn btn-info btn-sm pull-right" onclick="ccmFormidableAddBulkOptions();"><?php echo t('Add multiple'); ?></a>
											</div>

										</div>
									</div>

								<?php } ?>
								
								<?php if ($element->getProperty('multiple')) { ?> 
									<div class="form-group">
										<?php echo $form->label('multiple', t('Allow multiple'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="checkbox">
												<label>
													<?php echo $form->checkbox('multiple', 1, intval($element->getPropertyValue('multiple')) != 0)?>
													<?php echo t('When enabled, multiple options can be selected'); ?>
												</label>
											</div>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('min_max')) { ?>
									<div class="form-group">
										<?php echo $form->label('min_max', t('Limits'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('min_max', 1, intval($element->getPropertyValue('min_max')) != 0)?></div>
												<?php echo $form->text('min_value', $element->getPropertyValue('min_value'), array('style' => 'width: 20%;', 'placeholder' => t('Minimum')))?>
												<?php echo $form->text('max_value', (intval($element->getPropertyValue('max_value'))==0)?'':intval($element->getPropertyValue('max_value')), array('style' => 'width: 20%;', 'placeholder' => t('Maximum')))?>
												<?php echo $form->select('min_max_type', $element->getProperty('min_max'), $element->getPropertyValue('min_max_type'), array('style' => 'width: 60%;'))?>
											</div>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('chars_allowed')) { ?>
									<div class="form-group">
										<?php echo $form->label('chars_allowed', t('Allowed chars'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('chars_allowed', 1, intval($element->getPropertyValue('chars_allowed')) != 0)?></div>
												<select name="chars_allowed_value[]" id="chars_allowed_value" multiple="1" class="form-control ccm-input-select">
													<?php foreach ($element->getProperty('chars_allowed') as $key => $option) { ?>
													<option value="<?php echo $key ?>" <?php echo (@in_array($key, $element->getPropertyValue('chars_allowed_value')))?'selected="selected"':''; ?>><?php echo $option ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="note chars_allowed_note"><?php echo t('Use CTRL (or option) to deselect or select multiple') ?></div>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('allowed_extensions')) { ?>
									<div class="form-group">
										<?php echo $form->label('allowed_extensions', t('Allowed extensions'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('allowed_extensions', 1, intval($element->getPropertyValue('allowed_extensions')) != 0)?></div>
												<?php echo $form->textarea('allowed_extensions_value', $element->getPropertyValue('allowed_extensions_value'), array('style'=>'height: 35px;', 'placeholder' => $element->getProperty('allowed_extensions')))?>
											</div>
											<div class="note allowed_extensions_note"><?php echo t('Comma seperate each extension')?></div>
										</div>
									</div>
								<?php } ?>
								
								<?php 
								if ($element->getProperty('fileset')) {
									$s1 = FileSet::getMySets();
									$sets = array();
									foreach ($s1 as $s) $sets[$s->fsID] = $s->fsName; 
									?>
									<div class="form-group">
										<?php echo $form->label('fileset', t('Assign to fileset'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('fileset', 1, intval($element->getPropertyValue('fileset')) != 0, (sizeof($sets)>0)?'':array('disabled' => true))?></div>
												<?php 
												if (sizeof($sets) > 0) {
													echo $form->select('fileset_value', $sets, $element->getPropertyValue('fileset_value'));
												} else {
													echo $form->text('fileset_value', t('No filesets available'), array('disabled' => true));
												} 
												?>
											</div>
											<div class="note fileset_note"><?php echo t('Assign uploaded file to selected fileset') ?></div>
										</div>
									</div>
								<?php } ?>
							
								<?php if ($element->getProperty('mask')) { ?>
									<div class="form-group">
										<?php echo $form->label('mask', t('Enable masking'), array('class' => 'col-sm-3')) ?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('mask', 1, intval($element->getPropertyValue('mask')) != 0)?></div>
												<?php 
												if(is_array($element->getProperty('mask', 'formats'))) {
													echo $form->select('mask_format', $element->getProperty('mask', 'formats'), $element->getPropertyValue('mask_format'));
												} else {
													echo $form->text('mask_format', $element->getPropertyValue('mask_format'), array('placeholder' => $element->getProperty('mask', 'placeholder')));
												} ?>
											</div>
											<?php if ($element->getProperty('mask', 'note')) { ?>
												<div class="note mask_note">
													<?php echo @implode('<br />', $element->getProperty('mask', 'note')); ?>
												</div>	   
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('format')) { ?>
									<div class="form-group">
										<?php echo $form->label('format', t('Format'), array('class' => 'col-sm-3')) ?>
										<div class="col-sm-9">
											<div class="input-group" style="width:100%;">
												<?php echo $form->select('format', $element->getProperty('format', 'formats'), $element->getPropertyValue('format'), array('style' => 'width: 40%;'))?>
												<?php echo $form->text('format_other', $element->getPropertyValue('format_other'), array('style' => 'width: 60%;'))?>
											</div>
											<?php if ($element->getProperty('format', 'note')) { ?>
												<div class="note format_note">
													<?php echo @implode('<br />', $element->getProperty('format', 'note')); ?>
												</div>	   
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('appearance')) { ?>
									<div class="form-group">
										<?php echo $form->label('appearance', t('Appearance'), array('class' => 'col-sm-3')) ?>
										<div class="col-sm-9">
											<?php echo $form->select('appearance', $element->getProperty('appearance'), $element->getPropertyValue('appearance'))?>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('advanced')) { ?>
									<div class="form-group">
										<?php echo $form->label('advanced', t('Advanced options'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('advanced', 1, intval($element->getPropertyValue('advanced')) != 0)?></div>
												<?php 
													$value = $element->getPropertyValue('advanced_value');
													if (intval($element->getElementID()) == 0) $value = $element->getProperty('advanced', 'value');
													echo $form->textarea('advanced_value', $element->getPropertyValue('advanced_value'), array('style' => 'height: 70px;'));
												?>
											</div>
											<?php if ($element->getProperty('advanced', 'note')) { ?>
												<div class="note advanced_note">
													<?php echo @implode('<br />', $element->getProperty('advanced', 'note')); ?>
												</div>	   
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								
								<?php if ($element->getProperty('tooltip')) { ?>
									<div class="form-group">
										<?php echo $form->label('tooltip', t('Tooltip / Description'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon">
												<?php echo $form->checkbox('tooltip', 1, intval($element->getPropertyValue('tooltip')) != 0)?></div>
												<?php echo $form->textarea('tooltip_value', $element->getPropertyValue('tooltip_value'))?>
											</div>
										</div>
									</div>
								<?php } ?>
								   
								<?php if ($element->getProperty('css') !== false) { ?>
									<div class="form-group">
										<?php echo $form->label('css', t('CSS Classes'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('css', 1, intval($element->getPropertyValue('css')) != 0)?></div>
												<?php echo $form->text('css_value', $element->getPropertyValue('css_value')); ?>
											</div>
											<div class="note css_note">
												<?php echo t('Add classname(s) to customize your form field. Example: myformelement'); ?>
											</div>	   
										</div>
									</div>
								<?php } ?>

								<?php if ($element->getProperty('errors') !== false && ($element->getPropertyValue('errors_values'))) { ?>
									<div class="form-group">
							            <?php echo $form->label('errors', t('Custom errors'), array('class' => 'col-sm-3'))?>
							            <div class="col-sm-9">
							                <?php echo $form->select('errors', array(0 => t('No custom errors'), 1 => t('Enable custom errors')), intval($element->getPropertyValue('errors'))); ?>
							                <div id="errors_div">                        
							                    <?php $errors = $element->getPropertyValue('errors_values'); ?>
							                    <?php if ($element->getProperty('errors', 'empty')) { ?>
								                    <div class="input-subgroup required">
								                        <?php echo $form->label('error[empty]', t('Error when field is empty')) ?>
								                        <?php echo $form->text('error[empty]]', $errors['empty']?$errors['empty']:$err->getDefaultErrorText('ERROR_EMPTY'), array('placeholder' => $err->getDefaultErrorText('ERROR_EMPTY'))); ?>
								                    </div>
								                <?php } ?>
								                <?php if ($element->getProperty('errors', 'invalid_numeric')) { ?>
								                    <div class="input-subgroup email">
								                        <?php echo $form->label('error[invalid_numeric]', t('Error when field is invalid (numeric)')) ?>
								                        <?php echo $form->text('error[invalid_numeric]', $errors['invalid_numeric']?$errors['invalid_numeric']:$err->getDefaultErrorText('ERROR_INVALID_NUMERIC'), array('placeholder' => $err->getDefaultErrorText('ERROR_INVALID_NUMERIC'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('errors', 'invalid_email')) { ?>
								                    <div class="input-subgroup email">
								                        <?php echo $form->label('error[invalid_email]', t('Error when field is invalid (email address)')) ?>
								                        <?php echo $form->text('error[invalid_email]', $errors['invalid_email']?$errors['invalid_email']:$err->getDefaultErrorText('ERROR_INVALID_EMAIL'), array('placeholder' => $err->getDefaultErrorText('ERROR_INVALID_EMAIL'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('errors', 'invalid_url')) { ?>
								                    <div class="input-subgroup url">
								                        <?php echo $form->label('error[invalid_url]', t('Error when field is invalid (url)')) ?>
								                        <?php echo $form->text('error[invalid_url]', $errors['invalid_url']?$errors['invalid_url']:$err->getDefaultErrorText('ERROR_INVALID_URL'), array('placeholder' => $err->getDefaultErrorText('ERROR_INVALID_URL'))); ?>
								                    </div>
							                    <?php } ?>
							                    <?php if ($element->getProperty('errors', 'invalid_date')) { ?>
								                    <div class="input-subgroup url">
								                        <?php echo $form->label('error[invalid_date]', t('Error when field is invalid (date)')) ?>
								                        <?php echo $form->text('error[invalid_date]', $errors['invalid_date']?$errors['invalid_date']:$err->getDefaultErrorText('ERROR_INVALID_DATE'), array('placeholder' => $err->getDefaultErrorText('ERROR_INVALID_DATE'))); ?>
								                    </div>
							                    <?php } ?>
							                    <?php if ($element->getProperty('errors', 'invalid_time')) { ?>
								                    <div class="input-subgroup url">
								                        <?php echo $form->label('error[invalid_time]', t('Error when field is invalid (time)')) ?>
								                        <?php echo $form->text('error[invalid_time]', $errors['invalid_time']?$errors['invalid_time']:$err->getDefaultErrorText('ERROR_INVALID_TIME'), array('placeholder' => $err->getDefaultErrorText('ERROR_INVALID_TIME'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('errors', 'confirmation')) { ?>
								                    <div class="input-subgroup confirmation">
								                        <?php echo $form->label('error[confirmation]', t('Error when confirmation field doesn\'t match')) ?>
								                        <?php echo $form->text('error[confirmation]', $errors['confirmation']?$errors['confirmation']:$err->getDefaultErrorText('ERROR_CONFIRMATION'), array('placeholder' => $err->getDefaultErrorText('ERROR_CONFIRMATION'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('errors', 'allowed')) { ?>
								                    <div class="input-subgroup allowed">
								                        <?php echo $form->label('error[allowed]', t('Error when character(s) aren\'t allowed')) ?>
								                        <?php echo $form->text('error[allowed]', $errors['allowed']?$errors['allowed']:$err->getDefaultErrorText('ERROR_ALLOWED'), array('placeholder' => $err->getDefaultErrorText('ERROR_ALLOWED'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('min_max', 'words')) { ?>
								                    <div class="input-subgroup min_max words">
								                        <?php echo $form->label('error[words]', t('Error when wordcount doesn\'t match')) ?>
								                        <?php echo $form->text('error[words_between]', $errors['words_between']?$errors['words_between']:$err->getDefaultErrorText('ERROR_WORDS_BETWEEN'), array('placeholder' => $err->getDefaultErrorText('ERROR_WORDS_BETWEEN'))); ?>
								                        <?php echo $form->text('error[words_minimal]', $errors['words_minimal']?$errors['words_minimal']:$err->getDefaultErrorText('ERROR_WORDS_MINIMAL'), array('placeholder' => $err->getDefaultErrorText('ERROR_WORDS_MINIMAL'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('min_max', 'chars')) { ?>
								                    <div class="input-subgroup min_max chars">
								                        <?php echo $form->label('error[chars]', t('Error when charactercount doesn\'t match')) ?>
								                        <?php echo $form->text('error[chars_between]', $errors['chars_between']?$errors['chars_between']:$err->getDefaultErrorText('ERROR_CHARS_BETWEEN'), array('placeholder' => $err->getDefaultErrorText('ERROR_CHARS_BETWEEN'))); ?>
								                        <?php echo $form->text('error[chars_minimal]', $errors['chars_minimal']?$errors['chars_minimal']:$err->getDefaultErrorText('ERROR_CHARS_MINIMAL'), array('placeholder' => $err->getDefaultErrorText('ERROR_CHARS_MINIMAL'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('min_max', 'value')) { ?>
								                    <div class="input-subgroup min_max value">
								                        <?php echo $form->label('error[value]', t('Error when value doesn\'t match')) ?>
								                        <?php echo $form->text('error[value_between]', $errors['value_between']?$errors['value_between']:$err->getDefaultErrorText('ERROR_VALUE_BETWEEN'), array('placeholder' => $err->getDefaultErrorText('ERROR_VALUE_BETWEEN'))); ?>
								                        <?php echo $form->text('error[value_minimal]', $errors['value_minimal']?$errors['value_minimal']:$err->getDefaultErrorText('ERROR_VALUE_MINIMAL'), array('placeholder' => $err->getDefaultErrorText('ERROR_VALUE_MINIMAL'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('min_max', 'options')) { ?>
								                    <div class="input-subgroup min_max options">
								                        <?php echo $form->label('error[option]', t('Error when selected options doesn\'t match')) ?>
								                        <?php echo $form->text('error[option_count]', $errors['option_count']?$errors['option_count']:$err->getDefaultErrorText('ERROR_OPTION_COUNT'), array('placeholder' => $err->getDefaultErrorText('ERROR_OPTION_COUNT'))); ?>
								                        <?php echo $form->text('error[option_between]', $errors['option_between']?$errors['option_between']:$err->getDefaultErrorText('ERROR_OPTION_BETWEEN'), array('placeholder' => $err->getDefaultErrorText('ERROR_OPTION_BETWEEN'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('min_max', 'files')) { ?>
								                    <div class="input-subgroup min_max files">
								                        <?php echo $form->label('error[files]', t('Error when filecount doesn\'t match')) ?>
								                        <?php echo $form->text('error[files_count]', $errors['files_count']?$errors['files_count']:$err->getDefaultErrorText('ERROR_FILES_COUNT'), array('placeholder' => $err->getDefaultErrorText('ERROR_FILES_COUNT'))); ?>
								                        <?php echo $form->text('error[files_between]', $errors['files_between']?$errors['files_between']:$err->getDefaultErrorText('ERROR_FILES_BETWEEN'), array('placeholder' => $err->getDefaultErrorText('ERROR_FILES_BETWEEN'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('other')) { ?>
								                    <div class="input-subgroup other">
								                        <?php echo $form->label('error[other]', t('Error when "Other"-field is empty')) ?>
								                        <?php echo $form->text('error[other]', $errors['other']?$errors['other']:$err->getDefaultErrorText('ERROR_OTHER'), array('placeholder' => $err->getDefaultErrorText('ERROR_OTHER'))); ?>
								                    </div>
							                    <?php } ?>
								                <?php if ($element->getProperty('errors', 'extension')) { ?>
								                    <div class="input-subgroup extension">
								                        <?php echo $form->label('error[extension]', t('Error when file-extension isn\'t allowed')) ?>
								                        <?php echo $form->text('error[extension]', $errors['extension']?$errors['extension']:$err->getDefaultErrorText('ERROR_EXTENSION'), array('placeholder' => $err->getDefaultErrorText('ERROR_EXTENSION'))); ?>
								                    </div>
							                    <?php } ?>
							                    <div id="errors_content_note" class="note help-block"> <?php echo t('If the error is empty the default error will be shown.'); ?></div>
							                </div>                                   
							            </div>
							        </div>
							    <?php } ?>

							</fieldset>
				        </div>
				        
				        <div id="ccm-tab-content-dependency" class="ccm-tab-content container-fluid"> 
				        	<fieldset>
				            	<?php if(!$disabled) { ?>
				                    <h5><?php echo t('If you want you can use dependencies for this element. This means the behaviour or the value can be influenced with the behaviour or value of another element'); ?></h5>

				                    <div id="dependencies_rules" data-next_rule="100"></div>

				                    <input type="button" class="btn btn-default pull-right" onclick="ccmFormidableAddDependency(<?php echo $element->getElementID(); ?>)" value="<?php echo t('Add dependency rule'); ?>">
				    			<?php } else { ?>
				                	 <div class="alert alert-info">
				                     	<strong><?php echo t('Note:'); ?></strong> <?php echo t('You have to save the element first before you can add dependencies to this element.'); ?>
				                     </div>      
				                <?php } ?>
				            </fieldset>
				        </div> 
				        
				        <?php if ($element->getProperty('handling') !== false) { ?>
				        <div id="ccm-tab-content-handling" class="ccm-tab-content container-fluid"> 
				        	<fieldset class="form-horizontal">            
				            
				               <div class="alert alert-warning">
				                   <strong><?php echo t('Warning:'); ?></strong> <?php echo t('This element can overwrite certain values within your Concrete5 installation. There will be no validation here, so if you overwrite the user\'s username it will try to save that value. This means it could break your site! Please be aware of this!!!'); ?>
				               </div>
				                   
				               <div class="form-group">
									<?php echo $form->label('submission_update', t('Update at submission'), array('class' => 'col-sm-3'))?>
				                    <div class="col-sm-9">
				                        <div class="input-group">
				                            <div class="input-group-addon"><?php echo $form->checkbox('submission_update', 1, intval($element->getPropertyValue('submission_update')) != 0)?></div>
				                            <?php echo $form->select('submission_update_type', array(
				                            	'user_attribute' => t('User Data'), 
				                            	'collection_attribute' => t('Collection Data')), $element->getPropertyValue('submission_update_type')!=''?$element->getPropertyValue('submission_update_type'):'user_attribute'); ?>
				                        	
				                        	<div id="submission_update_type_user_attribute" class="submission_update_type_attribute">
					                            <select name="submission_update_user_attribute" id="submission_update_user_attribute" class="form-control">
					                                <optgroup label="<?php echo t('Properties'); ?>">
					                                    <?php 
					                                        $_select = array(
					                                            'user_name' => t('Username'),
					                                            'user_email' => t('Email Address'),
																'user_password' => t('Password'),
					                                            'user_date_added' => t('Date added')
					                                        );
					                                        foreach ($_select as $v => $n) {
					                                            $_sel = '';
					                                            if ($v == $element->getPropertyValue('submission_update_value')) $_sel = 'selected';
					                                            echo '<option value="'.$v.'" '.$_sel.'>'.$n.'</option>';	
					                                        }	
					                                    ?>							
					                                </optgroup>
					                                <option></option>
					                                <optgroup label="<?php echo t('Attributes'); ?>">
					                                <?php 
					                                    $attribs = UserAttributeKey::getList();
					                                    if(is_array($attribs) && count($attribs)) {
					                                        foreach ($attribs as $at) {										
					                                            $_sel = '';
					                                            if ('ak_'.$at->getAttributeKeyHandle() == $element->getPropertyValue('submission_update_value')) $_sel = 'selected';
					                                            echo '<option value="ak_'.$at->getAttributeKeyHandle().'" '.$_sel.'>'.$at->getAttributeKeyName().'</option>';
					                                        }
					                                    }
					                                ?>
					                                </optgroup>
					                            </select>	
						                	</div>

						                	<div id="submission_update_type_collection_attribute" class="submission_update_type_attribute">
					                            <select name="submission_update_collection_attribute" id="submission_update_collection_attribute" class="form-control">
					                                <optgroup label="<?php echo t('Properties'); ?>">
					                                    <?php 
					                                        $_select = array(
					                                            'collection_name' => t('Name'),
					                                            'collection_handle' => t('Handle'),
					                                            'collection_date_added' => t('Date added')
					                                        );
					                                        foreach ($_select as $v => $n) {
					                                            $_sel = '';
					                                            if ($v == $element->getPropertyValue('submission_update_value')) $_sel = 'selected';
					                                            echo '<option value="'.$v.'" '.$_sel.'>'.$n.'</option>';	
					                                        }	
					                                    ?>							
					                                </optgroup>
					                                <option></option>
					                                <optgroup label="<?php echo t('Attributes'); ?>">
					                                <?php 
					                                    $attribs = CollectionAttributeKey::getList();
					                                    if(is_array($attribs) && count($attribs)) {
					                                        foreach ($attribs as $at) {
					                                            $_sel = '';
					                                            if ('ak_'.$at->getAttributeKeyHandle() == $element->getPropertyValue('submission_update_value')) $_sel = 'selected';
					                                            echo '<option value="ak_'.$at->getAttributeKeyHandle().'" '.$_sel.'>'.$at->getAttributeKeyName().'</option>';
					                                        }
					                                    }
					                                ?>
					                                </optgroup>
					                            </select>                    
				                 			</div>

				                        </div>
				                    </div>
				                </div>
				                
								<div class="form-group submission_update">
									<?php echo $form->label('submission_update_empty', t('Skip if empty'), array('class' => 'col-sm-3'))?>
									<div class="col-sm-9">
										<div class="checkbox">
											<label>
												<?php echo $form->checkbox('submission_update_empty', 1, intval($element->getPropertyValue('submission_update_empty')) != 0)?>
												<?php echo t('If the value of this element is empty, skip saving the data into the selected propertie or attribute');?>
											</label>
										</div>
									</div>
								</div>

				        	</fieldset>
				            
				        </div>         
				        <?php } ?>
				    </div>

				    <div class="dialog-buttons">
						<button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
						<button type="button" onclick="ccmFormidableCheckFormElementSubmit();return false;" class="btn btn-primary pull-right"><?php echo t('Save')?></button>
					</div>
				</form>
				
				<script>
					$(function() {
				
						$('input[id=label]').focus();
							   
						ccmFormidableFormElementCheckSelectors();
						
						$("input").click(function() {
							ccmFormidableFormElementCheckSelectors($(this));
						});
						$("select").change(function() {
							ccmFormidableFormElementCheckSelectors($(this));
						});
						$("input.option_default").mousedown(function() {
							$(this).data('wasChecked', this.checked);
						});				

						<?php 					
							$dependencies = $element->getDependency('initialized');
							if (!empty($dependencies)) {
							foreach((array)$dependencies as $rule => $dependency) { ?>
									setTimeout(function() { ccmFormidableAddDependency('<?php echo $element->getElementID(); ?>', '<?php echo $rule; ?>'); }, <?php echo 200*intval($rule); ?>);
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

						if ($('#code_value').length > 0) {
					        var editor = ace.edit("code_value");
					        editor.setTheme("ace/theme/eclipse");
					        editor.getSession().setMode("ace/mode/html");
					        refreshTextarea(editor.getValue());
					        editor.getSession().on('change', function() {
					            refreshTextarea(editor.getValue());
					        });
					    }

					    $(".element_options").sortable({
							items: ".option_row",
							handle: ".mover",
							sort: function(event, ui) {
								$(this).removeClass( "ui-state-default" );
							}							
						});			    					    
					});

					function refreshTextarea(contents) {
				        $('#code_value-textarea').val(contents);
				    }
				</script>
			<?php } ?>
		<?php } ?>

		<?php if ($task == 'select') { ?>
			
			<form>
				
				<?php 
					$combined_element = $combined_advanced = array();
					$tabs = array( array('tab-3', t('Predefined')), array('tab-1', t('Elements'), true), array('tab-2', t('Form data')), array('tab-4', t('Page Attributes')), array('tab-5', t('User Attributes')) );
					echo $ui->tabs($tabs); 
				?>

			    <div id="ccm-tab-content-tab-1" class="ccm-tab-content container-fluid" style="display:block;">   

			        <?php if (count($elements)) { ?>	
			            <table class="table-responsive table ccm-search-results-table">
			                <thead>
			                	<tr>
			                        <th class="element_label"><?php echo t('Label / Name'); ?></th>
			                        <th class="element_type"><?php echo t('Type'); ?></th>
			                        <th class="element_options"><?php echo t('Option'); ?></th>
			                    </tr>
			                </thead>
			                <tbody>	                    
			                    <?php foreach($elements as $element) { 
			                    	if (empty($element->getHandle()) || $element->getElementType() == 'captcha') continue; ?>
			                        <tr data-launch-search-menu="<?php echo $element->getHandle() ?>">
			                            <td class="element_label"><?php echo $element->getLabel(); ?></td>	
			                            <td class="element_type"><?php echo $element->getElementText() ?></td>
			                                    <td class="element_options">	          
			                                <?php if ($element->isLayout()) { ?>
			                                    <a href="javascript:void(0);" data-insert="{%<?php echo $element->getHandle(); ?>.label%}" class="btn btn-default"><?php echo t('Label') ?></a>
			                                <?php } else { ?>
			                                    <a href="javascript:void(0);" data-insert="{%<?php echo $element->getHandle(); ?>.label%}" class="btn btn-default"><?php echo t('Label') ?></a>
			                                    <a href="javascript:void(0);" data-insert="{%<?php echo $element->getHandle(); ?>.value%}" class="btn btn-default"><?php echo t('Value') ?></a>
			                                    <a href="javascript:void(0);" data-insert="{%<?php echo $element->getHandle(); ?>.label%}: {%<?php echo $element->getHandle(); ?>.value%}" class="btn btn-default"><?php echo t('Both') ?></a> 
			                                <?php }	?>
			                            </td>
			                        </tr>
			                        <?php 
				                    	$combined_element['label'] .= '{%'.$element->getHandle().'.label%}<br />';
				                    	if (!$element->isLayout()) $combined_element['value'] .= '{%'.$element->getHandle().'.value%}<br />';
				                    	$combined_element['both'] .= (!$element->isLayout()?'{%'.$element->getHandle().'.label%}: ':'').'{%'.$element->getHandle().'.value%}<br />';
				                    
				                    ?>
			                    <?php } ?>
			                </tbody>
			            </table>
			        <?php } else { ?>
			            <p>
			        	    <?php echo t('No elements created yet.'); ?><br />
			                <?php echo t('Go to "Layout and Elements"-tab in this form, and add some form elements.'); ?>
			            </p>
			        <?php } ?>					
			    </div>

			    <div id="ccm-tab-content-tab-2" class="ccm-tab-content container-fluid">
			    	<table class="table-responsive table ccm-search-results-table">
			            <thead>
		                	<tr>
		                        <th class="element_label"><?php echo t('Label / Name'); ?></th>
		                        <th class="element_type"><?php echo t('Type'); ?></th>
		                           <th class="element_options"><?php echo t('Option'); ?></th>
		                       </tr>
		                </thead>
		                <tbody>	                
			            <?php foreach($advanced as $key => $advance) {  ?>
			                    <tr data-launch-search-menu="<?php echo $advance['handle'] ?>">
			                        <td class="element_label"><?php echo $advance['label'] ?> <?php echo $advance['comment'] ?></td>	
			                        <td class="element_type"><?php echo $advance['type'] ?></td>
			                        <td class="element_options">	          
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $advance['handle']; ?>.label%}" class="btn btn-default"><?php echo t('Label') ?></a>
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $advance['handle']; ?>.value%}" class="btn btn-default"><?php echo t('Value') ?></a>
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $advance['handle']; ?>.label%}: {%<?php echo $advance['handle']; ?>.value%}" class="btn btn-default"><?php echo t('Both') ?></a> 
			                        </td>
			                    </tr>	                    
			                    <?php 
			                    	$combined_advanced['label'] .= '{%'.$advance['handle'].'.label%}<br />';
			                    	$combined_advanced['value'] .= '{%'.$advance['handle'].'.value%}<br />';
			                    	$combined_advanced['both'] .= '{%'.$advance['handle'].'.label%}: {%'.$advance['handle'].'.value%}<br />';
			                    ?>
			                <?php } ?>
			            </tbody>
			        </table>
			    </div>

			    <div id="ccm-tab-content-tab-3" class="ccm-tab-content container-fluid">
			    	<table class="table-responsive table ccm-search-results-table">
			            <thead>
		                	<tr>
		                        <th class="element_label"><?php echo t('Label / Name'); ?></th>
		                        <th class="element_type"><?php echo t('Type'); ?></th>
		                        <th class="element_options"><?php echo t('Option'); ?></th>
		                    </tr>
		                </thead>
		                <tbody>
			                <tr data-launch-search-menu="predefined_elements">
			                    <td class="element_label"><?php echo t('All elements combined') ?></td>	
			                    <td class="element_type"><?php echo t('All') ?></td>
			                    <td class="element_options">
			                        <a href="javascript:void(0);" data-insert="{%all_elements%}" class="btn btn-default"><?php echo t('Add element data') ?></a>        
			                    </td>
			                </tr>
			                <tr data-launch-search-menu="predefined_elements">
			                    <td class="element_label"><?php echo t('All elements as list') ?></td>	
			                    <td class="element_type"><?php echo t('All') ?></td>
			                    <td class="element_options">	          
		                            <a href="javascript:void(0);" data-insert="<?php echo $combined_element['label'] ?>" class="btn btn-default"><?php echo t('Label') ?></a>
		                            <a href="javascript:void(0);" data-insert="<?php echo $combined_element['value'] ?>" class="btn btn-default"><?php echo t('Value') ?></a>
		                            <a href="javascript:void(0);" data-insert="<?php echo $combined_element['both'] ?>" class="btn btn-default"><?php echo t('Both') ?></a> 
		                        </td>
			                </tr>
			                <tr data-launch-search-menu="predefined_advanced">
			                    <td class="element_label"><?php echo t('All form data combined') ?></td>	
			                    <td class="element_type"><?php echo t('All') ?></td>
			                    <td class="element_options">
			                        <a href="javascript:void(0);" data-insert="{%all_advanced_data%}" class="btn btn-default"><?php echo t('All form data') ?></a>        
			                    </td>
			                </tr>  
			                <tr data-launch-search-menu="predefined_advanced">
			                    <td class="element_label"><?php echo t('All form data as list') ?></td>	
			                    <td class="element_type"><?php echo t('All') ?></td>
			                    <td class="element_options">	          
		                            <a href="javascript:void(0);" data-insert="<?php echo $combined_advanced['label'] ?>" class="btn btn-default"><?php echo t('Label') ?></a>
		                            <a href="javascript:void(0);" data-insert="<?php echo $combined_advanced['value'] ?>" class="btn btn-default"><?php echo t('Value') ?></a>
		                            <a href="javascript:void(0);" data-insert="<?php echo $combined_advanced['both'] ?>" class="btn btn-default"><?php echo t('Both') ?></a> 
		                        </td>
			                </tr>                           
			            </tbody>
			        </table>
			    </div>

			    <div id="ccm-tab-content-tab-4" class="ccm-tab-content container-fluid">
			    	<table class="table-responsive table ccm-search-results-table">
			            <thead>
		                	<tr>
		                        <th class="element_label"><?php echo t('Label / Name'); ?></th>
		                        <th class="element_type"><?php echo t('Type'); ?></th>
		                           <th class="element_options"><?php echo t('Option'); ?></th>
		                       </tr>
		                </thead>
		                <tbody>	                
			            	<?php foreach($page as $key => $attrib) {  ?>
			                    <tr data-launch-search-menu="<?php echo $attrib['handle'] ?>">
			                        <td class="element_label"><?php echo $attrib['label'] ?> <?php echo $attrib['comment'] ?></td>	
			                        <td class="element_type"><?php echo $attrib['type'] ?></td>
			                        <td class="element_options">	          
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $attrib['handle']; ?>.label%}" class="btn btn-default"><?php echo t('Label') ?></a>
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $attrib['handle']; ?>.value%}" class="btn btn-default"><?php echo t('Value') ?></a>
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $attrib['handle']; ?>.label%}: {%<?php echo $attrib['handle']; ?>.value%}" class="btn btn-default"><?php echo t('Both') ?></a> 
			                        </td>
			                    </tr>	                    
			                <?php } ?>
			            </tbody>
			        </table>
			    </div>

			    <div id="ccm-tab-content-tab-5" class="ccm-tab-content container-fluid">
			    	<table class="table-responsive table ccm-search-results-table">
			            <thead>
		                	<tr>
		                        <th class="element_label"><?php echo t('Label / Name'); ?></th>
		                        <th class="element_type"><?php echo t('Type'); ?></th>
		                           <th class="element_options"><?php echo t('Option'); ?></th>
		                       </tr>
		                </thead>
		                <tbody>	                
			            	<?php foreach($user as $key => $attrib) {  ?>
			                    <tr data-launch-search-menu="<?php echo $attrib['handle'] ?>">
			                        <td class="element_label"><?php echo $attrib['label'] ?> <?php echo $attrib['comment'] ?></td>	
			                        <td class="element_type"><?php echo $attrib['type'] ?></td>
			                        <td class="element_options">	          
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $attrib['handle']; ?>.label%}" class="btn btn-default"><?php echo t('Label') ?></a>
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $attrib['handle']; ?>.value%}" class="btn btn-default"><?php echo t('Value') ?></a>
			                            <a href="javascript:void(0);" data-insert="{%<?php echo $attrib['handle']; ?>.label%}: {%<?php echo $attrib['handle']; ?>.value%}" class="btn btn-default"><?php echo t('Both') ?></a> 
			                        </td>
			                    </tr>	                    
			                <?php } ?>
			            </tbody>
			        </table>
			    </div>

			</form>

		<?php } ?>
	<?php } ?>

	<?php if ($task == 'bulk') { ?>	
	    <form id="elementBulkForm" method="post" action="">
            <?php echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_element')); ?>

            <fieldset class="form-horizontal">
				        	
				<h5><?php echo t('Add values in the textarea. New line for each new option.'); ?></h5>
						
				<div class="form-group">
					<?php echo $form->label('options', t('Options'), array('class' => 'col-sm-3')) ?>
					<div class="col-sm-9">
						<?php echo $form->textarea('options', '', array('rows' => 25))?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $form->label('clear', t('Clear'), array('class' => 'col-sm-3'))?>
					<div class="col-sm-9">
						<div class="checkbox">
							<label>
								<?php echo $form->checkbox('clear', 1, 1)?>
								<?php echo t('Remove all existing options before adding new ones'); ?>
							</label>
						</div>
					</div>
				</div>

			</fieldset>

        </form>

        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
            <button type="button" class="btn btn-primary pull-right" onclick="ccmFormidableAddOptionsToElement(); return false;"><?php echo t('Add options')?></button>
        </div>
	<?php } ?>

	<?php if ($task == 'delete') { ?>
		<?php if (!is_object($element)) { ?>
			<div class="alert alert-danger">
				<?php echo t('Can\'t find element'); ?>	
			</div>
		<?php } else { ?>
			<div class="alert alert-warning">
	            <?php echo t('Are you sure you want to delete this element?'); ?>
	        </div>

	        <form data-dialog-form="delete-result" method="post" action="">
	            <?php echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_element')); ?>
	            <?php echo $form->hidden('elementID', $element->getElementID()); ?>
	        </form>

	        <div class="dialog-buttons">
	            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
	            <button type="button" class="btn btn-danger pull-right" name="submit"><?php echo t('Delete')?></button>
	        </div>

	        <script>
				$(function() {	                
	                $('button[name=submit]').click(function() {
	                    ccmFormidableDeleteElement(<?php echo $element->getElementID() ?>);
	                    jQuery.fn.dialog.closeTop();
	                });
	            });
			</script>
		<?php } ?>
	<?php } ?>

</div>
