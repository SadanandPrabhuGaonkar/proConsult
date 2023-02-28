<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>

	<table class="entry ccm-search-results-table">
		<thead>
			<tr>
				<th class="mailing_subject"><span><?php echo t('Subject'); ?></span></th>
				<th class="mailing_from"><span><?php echo t('Mail from'); ?></span></th>
			</tr>
		</thead>
		<tbody class="ccm-form-list" id="ccm-mailing-list">
			<?php 
				if(is_array($mailings)) {
				    if(count($mailings)) {
					foreach($mailings as $m) {
			?>
				<tr class="mailing_row_wrapper" data-launch-search-menu="<?php echo $m->getMailingID() ?>">
				    <td class="mailing_subject"><?php echo $m->getSubject() ?></td>
				    <td class="mailing_from"><?php echo $m->getFromDisplay() ?></td>
				</tr>

				<div class="ccm-popover-menu popover fade" data-search-menu="<?php echo $m->getMailingID() ?>">
				    <div class="arrow"></div>
				    <div class="popover-inner">
				        <ul class="dropdown-menu">
				            <li><a href="javascript:ccmFormidableOpenMailingDialog(<?php echo $m->getMailingID() ?>);"><?php echo t('Edit') ?></a></li>
				            <li class="divider"></li>
				            <li><a href="javascript:ccmFormidableDuplicateMailing(<?php echo $m->getMailingID() ?>);"><?php echo t('Duplicate') ?></a></li>
				            <li class="divider"></li>
				            <li><a href="javascript:ccmFormidableOpenDeleteMailingDialog(<?php echo $m->getMailingID() ?>);"><?php echo t('Delete'); ?></a></li>
				        </ul>
				    </div>
				</div>
			<?php } } else { ?>
				<tr>
					<td colspan="2" align="center">
						<?php echo t('No mailings created for this form'); ?>
					</td>
				</tr>
			<?php } }?>
		</tbody>
	</table>
<?php }