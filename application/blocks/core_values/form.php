<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php $tabs = [
    ['form-basics-' . $identifier_getString, t('Basics'), true],
    ['form-slides_items-' . $identifier_getString, t('Slides')]
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
    <?php echo $form->label($view->field('desc_1'), t("description")); ?>
    <?php echo isset($btFieldsRequired) && in_array('desc_1', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('desc_1'), $desc_1, array (
  'maxlength' => 255,
)); ?>
</div>
</div>

<div class="ccm-tab-content" id="ccm-tab-content-form-slides_items-<?php echo $identifier_getString; ?>">
    <script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php echo Core::make('helper/validation/token')->generate('editor')?>";
</script>
<?php $repeatable_container_id = 'btCoreValues-slides-container-' . $identifier_getString; ?>
    <div id="<?php echo $repeatable_container_id; ?>">
        <div class="sortable-items-wrapper">
            <a href="#" class="btn btn-primary add-entry">
                <?php echo t('Add Entry'); ?>
            </a>

            <div class="sortable-items" data-attr-content="<?php echo htmlspecialchars(
                json_encode(
                    [
                        'items' => $slides_items,
                        'order' => array_keys($slides_items),
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
                        <?php echo t('Slides') . ' ' . t("row") . ' <span>#{{id}}</span>'; ?>
                    </span>
                    <span class="sortable-item-title-generated"></span>
                </div>

                <div class="sortable-item-inner">            <div class="form-group">
    <label for="<?php echo $view->field('slides'); ?>[{{id}}][logo]" class="control-label"><?php echo t("Logo"); ?></label>
    <?php echo isset($btFieldsRequired['slides']) && in_array('logo', $btFieldsRequired['slides']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <div data-file-selector-input-name="<?php echo $view->field('slides'); ?>[{{id}}][logo]" class="ccm-file-selector ft-image-logo-file-selector" data-file-selector-f-id="{{ logo }}"></div>
</div>            <div class="form-group">
    <label for="<?php echo $view->field('slides'); ?>[{{id}}][title]" class="control-label"><?php echo t("Title"); ?></label>
    <?php echo isset($btFieldsRequired['slides']) && in_array('title', $btFieldsRequired['slides']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('slides'); ?>[{{id}}][title]" id="<?php echo $view->field('slides'); ?>[{{id}}][title]" class="form-control" type="text" value="{{ title }}" maxlength="255" />
</div>            <div class="form-group">
    <label for="<?php echo $view->field('slides'); ?>[{{id}}][desc_1]" class="control-label"><?php echo t("Description"); ?></label>
    <?php echo isset($btFieldsRequired['slides']) && in_array('desc_1', $btFieldsRequired['slides']) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <input name="<?php echo $view->field('slides'); ?>[{{id}}][desc_1]" id="<?php echo $view->field('slides'); ?>[{{id}}][desc_1]" class="form-control" type="text" value="{{ desc_1 }}" maxlength="255" />
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
    Concrete.event.publish('btCoreValues.slides.edit.open', {id: '<?php echo $repeatable_container_id; ?>'});
    $.each($('#<?php echo $repeatable_container_id; ?> input[type="text"].title-me'), function () {
        $(this).trigger('keyup');
    });
</script>
</div>