<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="ccm-ui ccm-search-fields-advanced-dialog">

    <?php echo Core::make('helper/concrete/ui')->tabs(array(
        array('fields', t('Filters'), true),
        array('columns', t('Customize Results'))
    ));?>

    <form class="ccm-search-fields ccm-search-fields-none" data-form="advanced-search" method="post" action="<?php echo $controller->action('submit')?>">

    <div class="ccm-tab-content" id="ccm-tab-content-fields">

            <div class="form-group">
                <button class="btn btn-primary" type="button" data-button-action="add-field"><?php echo t('Add Field')?></button>
            </div>
            <!-- <hr/> -->
            <div data-container="search-fields" class="ccm-search-fields-advanced">

            </div>
    </div>

    <div class="ccm-tab-content" id="ccm-tab-content-columns">
        <?php
        print $customizeElement->render();
        ?>
    </div>
    </form>


    <div class="dialog-buttons">
        <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
        <button type="button" onclick="$('form[data-form=advanced-search]').trigger('submit')" class="btn btn-primary pull-right"><?php echo t('Search')?></button>
        <?php if ($supportsSavedSearch) { ?>
            <button type="button" data-button-action="save-search-preset" class="btn btn-success pull-right"><?php echo t('Save as Search Preset')?></button>
        <?php } ?>
    </div>


</div>

<?php if ($supportsSavedSearch) { ?>
<div style="display: none">
    <div data-dialog="save-search-preset" class="ccm-ui">
        <form data-form="save-preset" action="<?php echo $controller->action('save_preset')?>" method="post">
            <div class="form-group">
                <?php $form = Core::make('helper/form'); ?>
                <?php echo $form->label('presetName', t('Name'))?>
                <?php echo $form->text('presetName')?>
            </div>
        </form>
        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?php echo t('Cancel')?></button>
            <button class="btn btn-primary pull-right" data-button-action="save-search-preset-submit"><?php echo t('Save Preset')?></button>
        </div>
    </div>
</div>
<?php } ?>

<script type="text/template" data-template="search-field-row">
    <div class="ccm-search-fields-row">
        <select data-action="<?php echo $controller->action('add_field')?>" name="field[]" class="ccm-search-choose-field form-control">
            <option value=""><?php echo t('** Select Field')?></option>
            <?php if($manager) { foreach($manager->getGroups() as $group) { ?>
                <optgroup label="<?php echo $group->getName()?>">
                    <?php foreach($group->getFields() as $field) { ?>
                        <option value="<?php echo $field->getKey()?>" <% if (typeof(field) != 'undefined' && field.key == '<?php echo $field->getKey()?>') { %> selected <% } %>><?php echo $field->getDisplayName()?></option>
                    <?php }  ?>
                </optgroup>
            <?php } }?>
        </select>
        <div class="form-group"><% if (typeof(field) != 'undefined') { %><%=field.element%><% } %></div>
        <a data-search-remove="search-field" class="ccm-search-remove-field" href="#"><i class="fa fa-minus-circle"></i></a>
    </div>
</script>

<script type="text/json" data-template="default-query">
    <?php echo $defaultQuery?>
</script>
