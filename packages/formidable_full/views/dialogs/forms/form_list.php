<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>
	
	<table class="entry ccm-search-results-table">
		<thead>
			<tr>
				<th class="form_label"><span><?php echo t('Form Title'); ?></span></th>
				<th class="form_last_submission"><span><?php echo t('Last Submission'); ?></span></th>
				<th class="form_submissions text-right"><span><?php echo t('Submissions'); ?></span></th>
			</tr>
		</thead>

		<tbody class="ccm-form-list" id="ccm-form-list">
			<?php
                if (is_array($forms) && count($forms)) {

                    $token = Core::make('token')->generate('formidable_preview');

                    foreach ($forms as $form) {

                        $results_url = View::url('/dashboard/formidable/results/?formID=' . $form->getFormID());
                        $duplicate_url = 'javascript:ccmFormidableDuplicateForm(' . $form->getFormID() . ');';
                        $edit_url = View::url('/dashboard/formidable/forms/', 'edit', $form->getFormID());
                        $preview_url = URL::to('/formidable/dialog/dashboard/forms/preview');
                        $elements_url = View::url('/dashboard/formidable/forms/', 'elements', $form->getFormID());
                        $mailings_url = View::url('/dashboard/formidable/forms/', 'mailings', $form->getFormID());
                        $delete_url = 'javascript:ccmFormidableOpenDeleteFormDialog(' . $form->getFormID() . ');';
                        ?>
                        <tr data-launch-search-menu="<?php echo $form->getFormID() ?>">
                            <td class="form_label"><?php echo t($form->getLabel()); ?></td>
                            <td class="form_last_submission"><?php echo $form->getLastSubmissionDate(); ?></td>
                            <td class="form_submissions" align="right"><?php echo $form->getSubmissionCount(); ?></td>
                        </tr>

                        <div class="ccm-popover-menu popover fade" data-search-menu="<?php echo $form->getFormID() ?>">
                            <div class="arrow"></div>
                            <div class="popover-inner">
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $results_url; ?>"><?php echo t('Results') ?></a></li>
                                    <li>
                                        <a href="<?php echo $preview_url; ?>?formID=<?php echo $form->getFormID(); ?>&amp;ccm_token=<?php echo $token; ?>"
                                           class="dialog-launch" dialog-title="<?php echo t('Preview Form'); ?>"
                                           dialog-width="900" dialog-height="600"
                                           dialog-modal="true"><?php echo t('Preview') ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo $edit_url; ?>"><?php echo t('Form Properties') ?></a></li>
                                    <li>
                                        <a href="<?php echo $elements_url; ?>"><?php echo t('Layout and elements') ?></a>
                                    </li>
                                    <li><a href="<?php echo $mailings_url; ?>"><?php echo t('Emails') ?></a></li>
                                    <li class="divider"></li>
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
                            <?php echo t('No formidable forms created. Please create a form'); ?>
                        </td>
                    </tr>
                <?php }?>
		</tbody>
	</table>
<?php }