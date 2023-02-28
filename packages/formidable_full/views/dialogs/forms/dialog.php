<?php 
	defined('C5_EXECUTE') or die("Access Denied."); 
	$task = $this->controller->getTask();

	$form = Core::make('helper/form');
	?>

<div class="ccm-ui">

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>

	<?php if (!is_object($f)) { ?>
		<div class="alert alert-danger">
			<?php echo t('Can\'t find form'); ?>
		</div>
	<?php } else { ?>

		<?php if ($task == 'delete') { ?>
			<div class="alert alert-warning">
	            <?php echo t('Are you sure you want to delete this form?'); ?>
	        </div>

	        <form data-dialog-form="delete-result" method="post" action="">
	            <?php echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_form')); ?>
	            <?php echo $form->hidden('formID', $f->getFormID()); ?>
	        </form>

	        <div class="dialog-buttons">
	            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
	            <button type="button" class="btn btn-danger pull-right" name="submit"><?php echo t('Delete')?></button>
	        </div>

	        <script>
				$(function() {	                
	                $('button[name=submit]').click(function() {
	                    ccmFormidableDeleteForm(<?php echo $f->getFormID(); ?>);
	                    jQuery.fn.dialog.closeTop();
	                });
	            });
			</script>

		<?php } ?>
	<?php } ?>
<?php } ?>	
</div>