<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>
	
	<table class="entry ccm-search-results-table">
		<thead>
			<tr>
				<th class="form_label"><span><?php echo t('Template Title'); ?></span></th>
				<th class="form_used"><span><?php echo t('Used'); ?></span></th>
			</tr>
		</thead>

		<tbody class="ccm-form-list" id="ccm-form-list">
			<?php if(is_array($templates)) {
				if (count($templates)) { 

					$token = Core::make('token')->generate('formidable_preview');
					
					foreach($templates as $template) { 
						$duplicate_url = 'javascript:ccmFormidableDuplicateTemplate('.$template->getTemplateID().');';
						$edit_url = View::url('/dashboard/formidable/templates/', 'edit', $template->getTemplateID());						
						$delete_url = 'javascript:ccmFormidableOpenDeleteTemplateDialog('.$template->getTemplateID().');';
						$preview_url = URL::to('/formidable/dialog/dashboard/templates/preview');
				?>
					<tr data-launch-search-menu="<?php echo $template->getTemplateID() ?>">
					    <td class="form_label"><?php echo $template->getLabel(); ?></td>
					    <td class="form_used"><?php echo $template->getUsedCount(); ?></td>     
					</tr>

					<div class="ccm-popover-menu popover fade" data-search-menu="<?php echo $template->getTemplateID() ?>">
					    <div class="arrow"></div>
					    <div class="popover-inner">
					        <ul class="dropdown-menu">					            
					            <li><a href="<?php echo $preview_url; ?>?templateID=<?php echo $template->getTemplateID(); ?>&amp;ccm_token=<?php echo $token; ?>" class="dialog-launch" dialog-title="<?php echo t('Preview Template'); ?>" dialog-width="900" dialog-height="600" dialog-modal="true"><?php echo t('Preview') ?></a></li>
					            <li class="divider"></li>
					            <li><a href="<?php echo $edit_url; ?>"><?php echo t('Edit') ?></a></li>
					            <li><a href="<?php echo $duplicate_url; ?>"><?php echo t('Duplicate') ?></a></li>
					            <li class="divider"></li>
					            <li><a href="<?php echo $delete_url; ?>"><?php echo t('Delete'); ?></a></li>
					        </ul>
					    </div>
					</div>
				<?php 
					}
				} else {
			?>
				<tr>
					<td colspan="3" align="center">
						<?php echo t('No templates created.'); ?>
					</td>
				</tr>
			<?php } }?>
		</tbody>
	</table>
<?php }