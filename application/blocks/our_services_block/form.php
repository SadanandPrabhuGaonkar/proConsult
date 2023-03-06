<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php $tabs = [
    ['form-basics-' . $identifier_getString, t('Basics'), true],
    ['form-services_items-' . $identifier_getString, t('Services')]
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
    <?php echo $form->label($view->field('desc_1'), t("Desc")); ?>
    <?php echo isset($btFieldsRequired) && in_array('desc_1', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('desc_1'), $desc_1, array (
  'maxlength' => 255,
)); ?>
</div>
</div>

<div class="ccm-tab-content" id="ccm-tab-content-form-services_items-<?php echo $identifier_getString; ?>">
    <script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Core::make('helper/validation/token')->generate('editor')?>";
</script>
<?php $repeatable_container_id = 'btOurServicesBlock-services-container-' . $identifier_getString; ?>
    <div id="<?php echo $repeatable_container_id; ?>">
        <div class="sortable-items-wrapper">
            <a href="#" class="btn btn-primary add-entry">
                <?php echo t('Add Entry'); ?>
            </a>

            <div class="sortable-items" data-attr-content="<?php echo htmlspecialchars(
                json_encode(
                    [
                        'items' => $services_items,
                        'order' => array_keys($services_items),
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
                        <?php echo t('Services') . ' ' . t("row") . ' <span>#{{id}}</span>'; ?>
                    </span>
                    <span class="sortable-item-title-generated"></span>
                </div>

                <div class="sortable-item-inner">            <div class="form-group">
    <label for="<?php echo $view->field('services'); ?>[{{id}}][servicetitle]" class="control-label"><?php echo t("Title"); ?></label>
    <?php echo isset($btFieldsRequired['services']) && in_array('servicetitle', $btFieldsRequired['services']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('services'); ?>[{{id}}][servicetitle]" id="<?php echo $view->field('services'); ?>[{{id}}][servicetitle]" class="form-control" type="text" value="{{ servicetitle }}" maxlength="255" />
</div>            <div class="form-group">
    <label for="<?php echo $view->field('services'); ?>[{{id}}][descservice]" class="control-label"><?php echo t("Description"); ?></label>
    <?php echo isset($btFieldsRequired['services']) && in_array('descservice', $btFieldsRequired['services']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('services'); ?>[{{id}}][descservice]" id="<?php echo $view->field('services'); ?>[{{id}}][descservice]" class="form-control" type="text" value="{{ descservice }}" maxlength="255" />
</div>            <?php $btn_ContainerID = 'btOurServicesBlock-btn-container-' . $identifier_getString; ?>
<div class="ft-smart-link" id="<?php echo $btn_ContainerID; ?>">
	<div class="form-group">
		<label for="<?php echo $view->field('services'); ?>[{{id}}][btn]" class="control-label"><?php echo t("Button"); ?></label>
	    <?php echo isset($btFieldsRequired['services']) && in_array('btn', $btFieldsRequired['services']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
	    <?php $servicesBtn_options = $btn_Options; ?>
                    <select name="<?php echo $view->field('services'); ?>[{{id}}][btn]" id="<?php echo $view->field('services'); ?>[{{id}}][btn]" class="form-control ft-smart-link-type">{{#select btn}}<?php foreach ($servicesBtn_options as $k => $v) {
                        echo "<option value='" . $k . "'>" . $v . "</option>";
                     } ?>{{/select}}</select>
	</div>
	
	<div class="form-group">
		<div class="ft-smart-link-options hidden" style="padding-left: 10px;">
			<div class="form-group">
				<label for="<?php echo $view->field('services'); ?>[{{id}}][btn_Title]" class="control-label"><?php echo t("Title"); ?></label>
			    <input name="<?php echo $view->field('services'); ?>[{{id}}][btn_Title]" id="<?php echo $view->field('services'); ?>[{{id}}][btn_Title]" class="form-control" type="text" value="{{ btn_Title }}" />		
			</div>
			
			<div class="form-group hidden" data-link-type="page">
			<label for="<?php echo $view->field('services'); ?>[{{id}}][btn_Page]" class="control-label"><?php echo t("Page"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <div data-page-selector="{{token}}" data-input-name="<?php echo $view->field('services'); ?>[{{id}}][btn_Page]" data-cID="{{btn_Page}}"></div>
		</div>

		<div class="form-group hidden" data-link-type="url">
			<label for="<?php echo $view->field('services'); ?>[{{id}}][btn_URL]" class="control-label"><?php echo t("URL"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <input name="<?php echo $view->field('services'); ?>[{{id}}][btn_URL]" id="<?php echo $view->field('services'); ?>[{{id}}][btn_URL]" class="form-control" type="text" value="{{ btn_URL }}" />
		</div>

		<div class="form-group hidden" data-link-type="relative_url">
			<label for="<?php echo $view->field('services'); ?>[{{id}}][btn_Relative_URL]" class="control-label"><?php echo t("URL"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <input name="<?php echo $view->field('services'); ?>[{{id}}][btn_Relative_URL]" id="<?php echo $view->field('services'); ?>[{{id}}][btn_Relative_URL]" class="form-control" type="text" value="{{ btn_Relative_URL }}" />
		</div>

		<div class="form-group hidden" data-link-type="file">
		    <label for="<?php echo $view->field('services'); ?>[{{id}}][btn_File]" class="control-label"><?php echo t("File"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <div data-file-selector-input-name="<?php echo $view->field('services'); ?>[{{id}}][btn_File]" class="ccm-file-selector" data-file-selector-f-id="{{ btn_File }}"></div>	
		</div>

		<div class="form-group hidden" data-link-type="image">
			<label for="<?php echo $view->field('services'); ?>[{{id}}][btn_Image]" class="control-label"><?php echo t("Image"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <div data-file-selector-input-name="<?php echo $view->field('services'); ?>[{{id}}][btn_Image]" class="ccm-file-selector" data-file-selector-f-id="{{ btn_Image }}"></div>
		</div>
		</div>
	</div>
</div>
            <div class="form-group">
    <label for="<?php echo $view->field('services'); ?>[{{id}}][img]" class="control-label"><?php echo t("Image"); ?></label>
    <?php echo isset($btFieldsRequired['services']) && in_array('img', $btFieldsRequired['services']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <div data-file-selector-input-name="<?php echo $view->field('services'); ?>[{{id}}][img]" class="ccm-file-selector ft-image-img-file-selector" data-file-selector-f-id="{{ img }}"></div>
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
    Concrete.event.publish('btOurServicesBlock.services.edit.open', {id: '<?php echo $repeatable_container_id; ?>'});
    $.each($('#<?php echo $repeatable_container_id; ?> input[type="text"].title-me'), function () {
        $(this).trigger('keyup');
    });
</script>
</div>