<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php $tabs = [
    ['form-basics-' . $identifier_getString, t('Basics'), true],
    ['form-slideimages_items-' . $identifier_getString, t('Slider Images')]
];
echo Core::make('helper/concrete/ui')->tabs($tabs); ?>

<div class="ccm-tab-content" id="ccm-tab-content-form-basics-<?php echo $identifier_getString; ?>">
    <div class="form-group">
    <?php echo $form->label($view->field('title'), t("Title")); ?>
    <?php echo isset($btFieldsRequired) && in_array('title', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('title'), $title, array (
  'maxlength' => 255,
)); ?>
</div><div class="form-group">
    <?php echo $form->label($view->field('tagline'), t("Tag line")); ?>
    <?php echo isset($btFieldsRequired) && in_array('tagline', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('tagline'), $tagline, array (
  'maxlength' => 255,
)); ?>
</div><div class="form-group">
    <?php echo $form->label($view->field('desc_1'), t("Description")); ?>
    <?php echo isset($btFieldsRequired) && in_array('desc_1', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('desc_1'), $desc_1, array (
  'maxlength' => 255,
)); ?>
</div><?php $btnone_ContainerID = 'btHomepageBannerBlock-btnone-container-' . $identifier_getString; ?>
<div class="ft-smart-link" id="<?php echo $btnone_ContainerID; ?>">
	<div class="form-group">
		<?php echo $form->label($view->field('btnone'), t("Button one")); ?>
	    <?php echo isset($btFieldsRequired) && in_array('btnone', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
	    <?php echo $form->select($view->field('btnone'), $btnone_Options, $btnone, array (
  'class' => 'form-control ft-smart-link-type',
)); ?>
	</div>
	
	<div class="form-group">
		<div class="ft-smart-link-options hidden" style="padding-left: 10px;">
			<div class="form-group">
				<?php echo $form->label($view->field('btnone_Title'), t("Title")); ?>
			    <?php echo $form->text($view->field('btnone_Title'), $btnone_Title, []); ?>		
			</div>
			
			<div class="form-group hidden" data-link-type="page">
			<?php echo $form->label($view->field('btnone_Page'), t("Page")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('btnone_Page'), $btnone_Page); ?>
		</div>

		<div class="form-group hidden" data-link-type="url">
			<?php echo $form->label($view->field('btnone_URL'), t("URL")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo $form->text($view->field('btnone_URL'), $btnone_URL, []); ?>
		</div>

		<div class="form-group hidden" data-link-type="relative_url">
			<?php echo $form->label($view->field('btnone_Relative_URL'), t("URL")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo $form->text($view->field('btnone_Relative_URL'), $btnone_Relative_URL, []); ?>
		</div>

		<div class="form-group hidden" data-link-type="file">
			<?php
			if ($btnone_File > 0) {
				$btnone_File_o = File::getByID($btnone_File);
				if (!is_object($btnone_File_o)) {
					unset($btnone_File_o);
				}
			} ?>
		    <?php echo $form->label($view->field('btnone_File'), t("File")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/concrete/asset_library")->file('ccm-b-homepage_banner_block-btnone_File-' . $identifier_getString, $view->field('btnone_File'), t("Choose File"), $btnone_File_o); ?>	
		</div>

		<div class="form-group hidden" data-link-type="image">
			<?php
			if ($btnone_Image > 0) {
				$btnone_Image_o = File::getByID($btnone_Image);
				if (!is_object($btnone_Image_o)) {
					unset($btnone_Image_o);
				}
			} ?>
			<?php echo $form->label($view->field('btnone_Image'), t("Image")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-homepage_banner_block-btnone_Image-' . $identifier_getString, $view->field('btnone_Image'), t("Choose Image"), $btnone_Image_o); ?>
		</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	Concrete.event.publish('btHomepageBannerBlock.btnone.open', {id: '<?php echo $btnone_ContainerID; ?>'});
	$('#<?php echo $btnone_ContainerID; ?> .ft-smart-link-type').trigger('change');
</script><?php $btntwo_ContainerID = 'btHomepageBannerBlock-btntwo-container-' . $identifier_getString; ?>
<div class="ft-smart-link" id="<?php echo $btntwo_ContainerID; ?>">
	<div class="form-group">
		<?php echo $form->label($view->field('btntwo'), t("Button two")); ?>
	    <?php echo isset($btFieldsRequired) && in_array('btntwo', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
	    <?php echo $form->select($view->field('btntwo'), $btntwo_Options, $btntwo, array (
  'class' => 'form-control ft-smart-link-type',
)); ?>
	</div>
	
	<div class="form-group">
		<div class="ft-smart-link-options hidden" style="padding-left: 10px;">
			<div class="form-group">
				<?php echo $form->label($view->field('btntwo_Title'), t("Title")); ?>
			    <?php echo $form->text($view->field('btntwo_Title'), $btntwo_Title, []); ?>		
			</div>
			
			<div class="form-group hidden" data-link-type="page">
			<?php echo $form->label($view->field('btntwo_Page'), t("Page")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('btntwo_Page'), $btntwo_Page); ?>
		</div>

		<div class="form-group hidden" data-link-type="url">
			<?php echo $form->label($view->field('btntwo_URL'), t("URL")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo $form->text($view->field('btntwo_URL'), $btntwo_URL, []); ?>
		</div>

		<div class="form-group hidden" data-link-type="relative_url">
			<?php echo $form->label($view->field('btntwo_Relative_URL'), t("URL")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo $form->text($view->field('btntwo_Relative_URL'), $btntwo_Relative_URL, []); ?>
		</div>

		<div class="form-group hidden" data-link-type="file">
			<?php
			if ($btntwo_File > 0) {
				$btntwo_File_o = File::getByID($btntwo_File);
				if (!is_object($btntwo_File_o)) {
					unset($btntwo_File_o);
				}
			} ?>
		    <?php echo $form->label($view->field('btntwo_File'), t("File")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/concrete/asset_library")->file('ccm-b-homepage_banner_block-btntwo_File-' . $identifier_getString, $view->field('btntwo_File'), t("Choose File"), $btntwo_File_o); ?>	
		</div>

		<div class="form-group hidden" data-link-type="image">
			<?php
			if ($btntwo_Image > 0) {
				$btntwo_Image_o = File::getByID($btntwo_Image);
				if (!is_object($btntwo_Image_o)) {
					unset($btntwo_Image_o);
				}
			} ?>
			<?php echo $form->label($view->field('btntwo_Image'), t("Image")); ?>
            <small class="required"><?php echo t('Required'); ?></small>
            <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-homepage_banner_block-btntwo_Image-' . $identifier_getString, $view->field('btntwo_Image'), t("Choose Image"), $btntwo_Image_o); ?>
		</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	Concrete.event.publish('btHomepageBannerBlock.btntwo.open', {id: '<?php echo $btntwo_ContainerID; ?>'});
	$('#<?php echo $btntwo_ContainerID; ?> .ft-smart-link-type').trigger('change');
</script>
</div>

<div class="ccm-tab-content" id="ccm-tab-content-form-slideimages_items-<?php echo $identifier_getString; ?>">
    <script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Core::make('helper/validation/token')->generate('editor')?>";
</script>
<?php $repeatable_container_id = 'btHomepageBannerBlock-slideimages-container-' . $identifier_getString; ?>
    <div id="<?php echo $repeatable_container_id; ?>">
        <div class="sortable-items-wrapper">
            <a href="#" class="btn btn-primary add-entry">
                <?php echo t('Add Entry'); ?>
            </a>

            <div class="sortable-items" data-attr-content="<?php echo htmlspecialchars(
                json_encode(
                    [
                        'items' => $slideimages_items,
                        'order' => array_keys($slideimages_items),
                    ]
                )
            ); ?>">
            </div>

            <a href="#" class="btn btn-primary add-entry add-entry-last">
                <?php echo t('Add Entry'); ?>
            </a>
        </div>

        <script class="repeatableTemplate" type="text/x-handlebars-template">
            <div class="sortable-item" data-id="{{id}}">
                <div class="sortable-item-title">
                    <span class="sortable-item-title-default">
                        <?php echo t('Slider Images') . ' ' . t("row") . ' <span>#{{id}}</span>'; ?>
                    </span>
                    <span class="sortable-item-title-generated"></span>
                </div>

                <div class="sortable-item-inner">            <div class="form-group">
    <label for="<?php echo $view->field('slideimages'); ?>[{{id}}][img]" class="control-label"><?php echo t("Image"); ?></label>
    <?php echo isset($btFieldsRequired['slideimages']) && in_array('img', $btFieldsRequired['slideimages']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <div data-file-selector-input-name="<?php echo $view->field('slideimages'); ?>[{{id}}][img]" class="ccm-file-selector ft-image-img-file-selector" data-file-selector-f-id="{{ img }}"></div>
</div></div>

                <span class="sortable-item-collapse-toggle"></span>

                <a href="#" class="sortable-item-delete" data-attr-confirm-text="<?php echo t('Are you sure'); ?>">
                    <i class="fa fa-times"></i>
                </a>

                <div class="sortable-item-handle">
                    <i class="fa fa-sort"></i>
                </div>
            </div>
        </script>
    </div>

<script type="text/javascript">
    Concrete.event.publish('btHomepageBannerBlock.slideimages.edit.open', {id: '<?php echo $repeatable_container_id; ?>'});
    $.each($('#<?php echo $repeatable_container_id; ?> input[type="text"].title-me'), function () {
        $(this).trigger('keyup');
    });
</script>
</div>