<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php echo $form->label($view->field('title'), t("Title")); ?>
    <?php echo isset($btFieldsRequired) && in_array('title', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('title'), $title, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('desc_1'), t("Description")); ?>
    <?php echo isset($btFieldsRequired) && in_array('desc_1', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('desc_1'), $desc_1, array (
  'maxlength' => 255,
)); ?>
</div>

<?php $btn_ContainerID = 'btMoreInfoBlock-btn-container-' . $identifier_getString; ?>
<div class="ft-smart-link" id="<?php echo $btn_ContainerID; ?>">
	<div class="form-group">
		<?php echo $form->label($view->field('btn'), t("Button")); ?>
	    <?php echo isset($btFieldsRequired) && in_array('btn', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
	    <?php echo $form->select($view->field('btn'), $btn_Options, $btn, array (
  'class' => 'form-control ft-smart-link-type',
)); ?>
	</div>
	
	<div class="form-group">
		<div class="ft-smart-link-options hidden" style="padding-left: 10px;">
			<div class="form-group">
				<?php echo $form->label($view->field('btn_Title'), t("Title")); ?>
			    <?php echo $form->text($view->field('btn_Title'), $btn_Title, []); ?>		
			</div>
			
			<div class="form-group hidden" data-link-type="page">
			<?php echo $form->label($view->field('btn_Page'), t("Page")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('btn_Page'), $btn_Page); ?>
		</div>

		<div class="form-group hidden" data-link-type="url">
			<?php echo $form->label($view->field('btn_URL'), t("URL")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo $form->text($view->field('btn_URL'), $btn_URL, []); ?>
		</div>

		<div class="form-group hidden" data-link-type="relative_url">
			<?php echo $form->label($view->field('btn_Relative_URL'), t("URL")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo $form->text($view->field('btn_Relative_URL'), $btn_Relative_URL, []); ?>
		</div>

		<div class="form-group hidden" data-link-type="file">
			<?php
			if ($btn_File > 0) {
				$btn_File_o = File::getByID($btn_File);
				if (!is_object($btn_File_o)) {
					unset($btn_File_o);
				}
			} ?>
		    <?php echo $form->label($view->field('btn_File'), t("File")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/concrete/asset_library")->file('ccm-b-more_info_block-btn_File-' . $identifier_getString, $view->field('btn_File'), t("Choose File"), $btn_File_o); ?>	
		</div>

		<div class="form-group hidden" data-link-type="image">
			<?php
			if ($btn_Image > 0) {
				$btn_Image_o = File::getByID($btn_Image);
				if (!is_object($btn_Image_o)) {
					unset($btn_Image_o);
				}
			} ?>
			<?php echo $form->label($view->field('btn_Image'), t("Image")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-more_info_block-btn_Image-' . $identifier_getString, $view->field('btn_Image'), t("Choose Image"), $btn_Image_o); ?>
		</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	Concrete.event.publish('btMoreInfoBlock.btn.open', {id: '<?php echo $btn_ContainerID; ?>'});
	$('#<?php echo $btn_ContainerID; ?> .ft-smart-link-type').trigger('change');
</script>