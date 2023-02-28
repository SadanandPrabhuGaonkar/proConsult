<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php $task = $this->controller->getTask();	?>
	<div class="ccm-ui">

		<?php if (is_array($errors)) { ?>
			<div class="alert alert-danger">
				<?php echo $errors['message']; ?>
			</div>
		<?php } else { ?>

			<?php if ($task == 'view') { ?>
				<div class="ccm-ui">
					<?php if ($template) { ?>
						<div class="alert alert-warning">
							<b><?php echo t('Note:'); ?></b> <?php echo t('The {%formidable_mailing%}-tag will be replaced with the message from the mail.'); ?>
						</div>
						<?php echo $template->getContent(); ?>
					<?php } else { ?>
						<div class="alert alert-danger">
							<?php echo t('Access denied') ?>
						</div>
					<?php } ?>
				</div>
				<div class="dialog-buttons">
					<button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
				</div>
			<?php 
			}
		} 
		?>
	</div>
