<?php 
	defined('C5_EXECUTE') or die("Access Denied."); 
	$task = $this->controller->getTask();

	$form = Core::make('helper/form');

	$request = \Concrete\Core\Http\Request::getInstance()->request(); 
?>

<div class="ccm-ui">

	<?php if (is_array($errors)) { ?>
		<div class="alert alert-danger">
			<?php echo $errors['message']; ?>
		</div>
	<?php } else { ?>
		<?php if ($task == 'view') { ?> 
			<?php if (!is_object($layout) && !is_array($layout)) { ?>
				<div class="alert alert-danger">
					<?php 
						if (!is_array($errors)) echo t('Can\'t find layout'); 
						else echo $errors['message'];
					?>
				</div>
			<?php } else { ?>
					<form id="layoutForm" method="post" action="">
						<div class="alert alert-danger dialog_message" style="display:none"></div>
							<?php 
				                echo $form->hidden('formID', $request['formID']);
								echo $form->hidden('layoutID', $request['layoutID']);
								echo $form->hidden('rowID', $request['rowID']);  
								echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_layout'));              
			                ?>     
					        <fieldset class="form-horizontal">

					        	<?php if ($layout->layoutID > 0) { ?>
									
									<h5><?php echo t('Set the properties of the column'); ?></h5>

									<div class="form-group">
										<?php echo $form->label('label', t('Label / Name'), array('class' => 'col-sm-3')) ?>
										<div class="col-sm-9">
											<?php echo $form->text('label', $layout->label)?>
										</div>
									</div>
									
					                <div class="form-group">
					                    <?php echo $form->label('appearance', t('Appearance'), array('class' => 'col-sm-3')) ?>
					                    <div class="col-sm-9">
					                        <?php echo $form->select('appearance', $appearances, $layout->appearance)?>
					                    </div>
					                </div>
					                
									<div class="form-group">
										<?php echo $form->label('css', t('CSS Classes'), array('class' => 'col-sm-3'))?>
										<div class="col-sm-9">
											<div class="input-group">
												<div class="input-group-addon"><?php echo $form->checkbox('css', 1, intval($layout->css) != 0)?></div>
												<?php echo $form->text('css_value', $layout->css_value); ?>
											</div>
											<div id="css_content_note" class="note help-block">
												<?php echo t('Add classname(s) to customize your form field. Example: myformelement'); ?>
											</div>
										</div>
									</div>

								<?php } else { ?>

									<h5><?php echo t('Set the properties of the row'); ?></h5>
									
									<div class="form-group">
										<?php echo $form->label('cols', t('Number of columns'), array('class' => 'col-sm-3')) ?>
										<div class="col-sm-9">
											<?php
                                            $layout_count = is_array($layout) ? count($layout) : 0;
                                            echo $form->select('cols', array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5), $layout_count )?>
											<div class="note help-block">
												<?php echo t('If you want to have less columns, empty them first.'); ?>
											</div>
										</div>
									</div>
							    <?php } ?>
				
					        </fieldset>

					    </div>

					    <div class="dialog-buttons">
							<button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
							<button type="button" onclick="ccmFormidableCheckFormLayoutSubmit();return false;" class="btn btn-primary pull-right"><?php echo t('Save')?></button>
						</div>
					</form>
					
					<script>
						$(function() {
							ccmFormidableFormElementCheckSelectors();
							$("input[name=css]").click(function() {
								ccmFormidableFormElementCheckSelectors($(this));
							});
						});
					</script> 
			<?php } ?>

		<?php } ?>


		<?php if ($task == 'select') { ?>

			<?php if (is_array($errors)) { ?>
				<div class="alert alert-danger">
					<?php echo $errors['message']; ?>
				</div>
			<?php } else { ?>
				<?php 
		            echo $form->hidden('formID', $request['formID']);
					echo $form->hidden('layoutID', $request['layoutID']);              
		        ?> 

				<div id="ccm-pane-body-left">
					<div class="well-sm well form-inline">
				    	<?php echo $form->label('quick_search_element', t('Search:'), array('style' => 'margin-left:20px; margin-right:5px;')); ?>
				        <?php echo $form->text('quick_search_element', '', array('placeholder' => 'Quick search')); ?>
					</div>
					<?php 
						$elements = $f->getAvailableElements();
						$elements_count =  is_array($elements) ? count($elements) : 0;
						if($elements_count) {
							ksort($elements);
							foreach ($elements as $group => $types) {
								ksort($types);	
								?>  
								<div class="col-sm-4">
									<p class="text-muted"><?php echo t($group); ?></p>
									<ul class="searchable_elements list-group">
										<?php foreach ($types as $element) { 
											$disabled = ''; 
											if ($element->getElementType() == 'captcha' && $f->hasCaptcha()) $disabled = 'disabled';
                                            if ($element->getElementType() == 'invisiblecaptcha' && $f->hasInvisibleCaptcha()) $disabled = 'disabled';
											?>
											<li label="<?php echo $element->getElementType(); ?>" class="list-group-item <?php echo $disabled; ?>">
												<?php echo t($element->getElementText())?>
												<?php echo (!empty($disabled))?'<span>'.t('disabled').'</span>':''; ?>
											</li>
										<?php } ?>
									</ul>
								</div>
								<?php 
							} 
						} else { ?>
							<div class="message alert alert-danger alert-message error">
								<?php echo t('No available elements found!'); ?>
							</div>
							<?php 
						} 
					?>
				</div>

				<script>
					$(function() {
						$('#quick_search').val('');
						$(".searchable_elements li").show();

						$('input[id="quick_search_element"]').on('keydown, keyup', function() {
							var s = $(this).val();
							$(".searchable_elements li").show();
							if (s.length > 0)
								$(".searchable_elements li:not(:contains('"+s+"'))").hide();	
						});
						$("#ccm-pane-body-left li:not('.disabled')").click(function(){
							ccmFormidableOpenElementDialog($(this).attr('label'), $(this).text(), $('#layoutID').val());
						});

						$('#ccm-pane-body-left li[label]').each(function() {
							$(this).css('background-image', 'url(' + CCM_REL + '/packages/formidable_full/images/icons/' + $(this).attr('label') + '.png)');	
						});
					});
				</script> 
			<?php } ?>
		<?php } ?>

		<?php if ($task == 'delete') { ?>
			<?php if (!is_object($layout) && !is_array($layout)) { ?>
				<div class="alert alert-danger">
					<?php 
						if (!is_array($errors)) echo t('Can\'t find layout'); 
						else echo $errors['message'];
					?>
				</div>
			<?php } else { ?>
				<div class="alert alert-warning">
		            <?php echo t('Are you sure you want to delete this layout?'); ?>
		        </div>

		        <form data-dialog-form="delete-result" method="post" action="">
		            <?php echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_layout')); ?>
		            <?php echo $form->hidden('layoutID', $layoutID); ?>
		            <?php echo $form->hidden('rowID', $rowID); ?>
		        </form>

		        <div class="dialog-buttons">
		            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
		            <button type="button" class="btn btn-danger pull-right" name="submit"><?php echo t('Delete')?></button>
		        </div>

		        <script>
					$(function() {	                
		                $('button[name=submit]').click(function() {
		                    ccmFormidableDeleteLayout(<?php echo $layoutID ?>, <?php echo $rowID ?>);
		                    jQuery.fn.dialog.closeTop();
		                });
		            });
				</script>
			<?php } ?>
		<?php } ?>

	<?php } ?>
</div>