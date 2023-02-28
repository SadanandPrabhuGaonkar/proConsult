<?php 
	defined('C5_EXECUTE') or die("Access Denied.");
	$form = Core::make('helper/form');
	$task = $this->controller->getTask();
?>

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>

	<?php if ($task == 'add') { ?>
		<fieldset class="dependency form-horizontal" id="dependency_rule_<?php echo $rule; ?>" data-rule="<?php echo $rule; ?>" data-mailingID="<?php echo $current_mailing->getMailingID(); ?>">
			<div class="input operator">
				<?php echo t('OR'); ?>
			</div>
			<div class="form-group">
				<label class="col-sm-3">
					<a href="javascript:ccmFormidableDeleteDependencyDialog(<?php echo $current_mailing->getMailingID(); ?>, <?php echo $rule; ?>);" class="btn btn-danger option_button" title="<?php echo t('Delete this rule'); ?>">-</a>
					<i class="mover fa fa-arrows" title="<?php echo t('Move this rule'); ?>"></i> 
					<?php echo t('Rule'); ?> #<span class="rule"><?php echo ($rule + 1); ?></span>
				</label>
				<div class="col-sm-9">
					<div class="dependency_actions input" data-next_rule="100"></div>
					<div class="dependency_elements input" data-next_rule="100"></div>
				</div>
			</div>
		</fieldset>

		<script> 
			<?php 
				if (!empty($dependency['actions'])) {
					foreach($dependency['actions'] as $action_rule => $action) {
						echo 'ccmFormidableAddDependencyAction('.$current_mailing->getMailingID().', '.$rule.', '.$action_rule.');';		
					}
				}
			?> 
			<?php 
				if (!empty($dependency['elements'])) {
					foreach($dependency['elements'] as $element_rule => $element) {
						echo 'ccmFormidableAddDependencyElement('.$current_mailing->getMailingID().', '.$rule.', '.$element_rule.');';								
					}
				} 
			?>
		</script>

	<?php } elseif ($task == 'action') { ?>
		
		<?php 
			// Declare if it isn't
			if (!is_array($action['dependency_action'])) {
				$action['dependency_action'] = array(
					'action' => '',
					'action_value' => '',
					'action_select' => '',
				);
			}
		?>

		<div class="dependency_action" id="action_<?php echo $action_rule; ?>" style="margin-top: 5px;">
			<div class="action_buttons" style="width:16%;float:right;">
				<a href="javascript:ccmFormidableAddDependencyAction(<?php echo $current_mailing->getMailingID(); ?>, <?php echo $action_rule; ?>);" class="btn btn-success option_button" title="<?php echo t('Add an action to this rule'); ?>">+</a>
				<a href="javascript:ccmFormidableDeleteDependencyAction(<?php echo $rule; ?>, <?php echo $action_rule; ?>);" class="btn btn-danger option_button" title="<?php echo t('Delete this action'); ?>">-</a>
			</div>
			<div class="action input-group" style="width: 82%;">
				<span class="action_label input-group-addon"><?php echo t('and'); ?></span> 
				<?php 
					echo $form->select('dependency['.$rule.'][action]['.$action_rule.'][action]', (array)$action['actions'], $action['dependency_action']['action'], array('style' => 'width: 45%', 'class' => 'action'));

					echo $form->text('dependency['.$rule.'][action]['.$action_rule.'][action_value]', $action['dependency_action']['action_value'], array('style' => 'width: 55%', 'class' => 'action_value'));
				
					echo $form->select('dependency['.$rule.'][action]['.$action_rule.'][action_select]', (array)$action['values'], $action['dependency_action']['action_select'], array('style' => 'width: 55%', 'class' => 'action_select'));
				?>
			</div>
		</div>

	<?php } elseif ($task == 'element') { ?>
			
		<?php 
			// Declare if it isn't
			if (!is_array($element['dependency_element'])) {
				$element['dependency_element'] = array(
					'element' => '',
					'element_value' => '',
					'condition' => '',
					'condition_value' => '',
				);
			}
		?>

		<div class="dependency_element" id="element_<?php echo $element_rule; ?>" style="margin-top: 5px;">
			<?php 
				$form = Core::make('helper/form');
				echo $form->hidden('element_select_'.$rule.'_'.$element_rule, $element['dependency_element']['element_value']);
				echo $form->hidden('condition_select_'.$rule.'_'.$element_rule, $element['dependency_element']['condition']);
				echo $form->hidden('condition_value_'.$rule.'_'.$element_rule, $element['dependency_element']['condition_value']);
			?>
			<div class="action_buttons" style="width:16%;float:right;">
				<a href="javascript:ccmFormidableAddDependencyElement(<?php echo $current_mailing->getMailingID(); ?>, <?php echo $element_rule; ?>);" class="btn btn-success option_button" title="<?php echo t('Add an element to this rule'); ?>">+</a>
				<a href="javascript:ccmFormidableDeleteDependencyElement(<?php echo $rule; ?>, <?php echo $element_rule; ?>);" class="btn btn-danger option_button" title="<?php echo t('Delete this element'); ?>">-</a>
			</div>
			<div class="element input-group" style="width: 82%;">
				<div class="input-group-addon">
					<span class="element_label"><?php echo t('and'); ?></span> 
					<?php echo t('if'); ?>
				</div>
				<?php echo $form->select('dependency['.$rule.'][element]['.$element_rule.'][element]', (array)$element['elements'], $element['dependency_element']['element'], array('class' => 'element')); ?>
			</div>
			<div class="element_value input-group" style="width: 82%;margin-top: 5px;">
				<div class="input-group-addon">
					<?php echo t('has'); ?>
				</div>
				<?php echo $form->select('dependency['.$rule.'][element]['.$element_rule.'][element_value]', (array)$element['values'], $element['dependency_element']['element_value'], array('class' => 'element_value')); ?>
				<div class="input-group-addon">
					<?php echo t('selected/checked'); ?>
				</div>
			</div>
			<div class="condition input-group" style="margin-top: 5px;width: 82%;">
				<?php 
					echo $form->select('dependency['.$rule.'][element]['.$element_rule.'][condition]', (array)$element['conditions'], $element['dependency_element']['condition'], array('style' => 'width: 50%', 'class' => 'condition'));
					echo $form->text('dependency['.$rule.'][element]['.$element_rule.'][condition_value]', $element['dependency_element']['condition_value'], array('style' => 'width: 50%', 'class' => 'condition_value'));
				?>
			</div>
		</div>

	<?php } elseif ($task == 'delete') { ?>

		<div class="ccm-ui">
			<?php if ($dependency) { ?>
				<div class="alert alert-danger">
					<?php echo t('Can\'t find dependency'); ?>	
				</div>
			<?php } else { ?>
				<div class="alert alert-warning">
		        	<?php  echo t('Are you sure you want to delete this dependency?'); ?>
		        </div>

		        <form data-dialog-form="delete-result" method="post" action="">
		            <?php  echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_element')); ?>
		            <?php  echo $form->hidden('rule', $rule); ?>
		        </form>

		        <div class="dialog-buttons">
		            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php  echo t('Cancel')?></button>
		            <button type="button" class="btn btn-danger pull-right" name="submit"><?php  echo t('Delete')?></button>
		        </div>

		        <script>
					$(function() {	                
		                $('button[name=submit]').click(function() {
		                    ccmFormidableDeleteDependency(<?php echo $rule ?>);
		                    jQuery.fn.dialog.closeTop();
		                });
		            });
				</script>
			<?php  } ?>
		</div>		
	<?php } ?>
<?php }
