<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Core::make('helper/validation/token')->generate('editor')?>";
</script>
<?php $repeatable_container_id = 'btLinks-links-container-' . $identifier_getString; ?>
    <div id="<?php echo $repeatable_container_id; ?>">
        <div class="sortable-items-wrapper">
            <a href="#" class="btn btn-primary add-entry">
                <?php echo t('Add Entry'); ?>
            </a>

            <div class="sortable-items" data-attr-content="<?php echo htmlspecialchars(
                json_encode(
                    [
                        'items' => $links_items,
                        'order' => array_keys($links_items),
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
                        <?php echo t('Links') . ' ' . t("row") . ' <span>#{{id}}</span>'; ?>
                    </span>
                    <span class="sortable-item-title-generated"></span>
                </div>

                <div class="sortable-item-inner">            <?php $linkdetail_ContainerID = 'btLinks-linkdetail-container-' . $identifier_getString; ?>
<div class="ft-smart-link" id="<?php echo $linkdetail_ContainerID; ?>">
	<div class="form-group">
		<label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail]" class="control-label"><?php echo t("Link Detail"); ?></label>
	    <?php echo isset($btFieldsRequired['links']) && in_array('linkdetail', $btFieldsRequired['links']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
	    <?php $linksLinkdetail_options = $linkdetail_Options; ?>
                    <select name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail]" id="<?php echo $view->field('links'); ?>[{{id}}][linkdetail]" class="form-control ft-smart-link-type">{{#select linkdetail}}<?php foreach ($linksLinkdetail_options as $k => $v) {
                        echo "<option value='" . $k . "'>" . $v . "</option>";
                     } ?>{{/select}}</select>
	</div>
	
	<div class="form-group">
		<div class="ft-smart-link-options hidden" style="padding-left: 10px;">
			<div class="form-group">
				<label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Title]" class="control-label"><?php echo t("Title"); ?></label>
			    <input name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Title]" id="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Title]" class="form-control" type="text" value="{{ linkdetail_Title }}" />		
			</div>
			
			<div class="form-group hidden" data-link-type="page">
			<label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Page]" class="control-label"><?php echo t("Page"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <div data-page-selector="{{token}}" data-input-name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Page]" data-cID="{{linkdetail_Page}}"></div>
		</div>

		<div class="form-group hidden" data-link-type="url">
			<label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_URL]" class="control-label"><?php echo t("URL"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <input name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_URL]" id="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_URL]" class="form-control" type="text" value="{{ linkdetail_URL }}" />
		</div>

		<div class="form-group hidden" data-link-type="relative_url">
			<label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Relative_URL]" class="control-label"><?php echo t("URL"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <input name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Relative_URL]" id="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Relative_URL]" class="form-control" type="text" value="{{ linkdetail_Relative_URL }}" />
		</div>

		<div class="form-group hidden" data-link-type="file">
		    <label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_File]" class="control-label"><?php echo t("File"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <div data-file-selector-input-name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_File]" class="ccm-file-selector" data-file-selector-f-id="{{ linkdetail_File }}"></div>	
		</div>

		<div class="form-group hidden" data-link-type="image">
			<label for="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Image]" class="control-label"><?php echo t("Image"); ?></label>
            <small class="required"><?php echo t('Required'); ?></small>
            <div data-file-selector-input-name="<?php echo $view->field('links'); ?>[{{id}}][linkdetail_Image]" class="ccm-file-selector" data-file-selector-f-id="{{ linkdetail_Image }}"></div>
		</div>
		</div>
	</div>
</div>
</div>

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
    Concrete.event.publish('btLinks.links.edit.open', {id: '<?php echo $repeatable_container_id; ?>'});
    $.each($('#<?php echo $repeatable_container_id; ?> input[type="text"].title-me'), function () {
        $(this).trigger('keyup');
    });
</script>