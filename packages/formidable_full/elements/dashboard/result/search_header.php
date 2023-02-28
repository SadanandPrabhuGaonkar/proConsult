<?php
defined('C5_EXECUTE') or die("Access Denied.");

$session = Core::make('app')->make('session');
$formID  = $session->get('formidableFormID');

$form = Core::make('helper/form');

$forms = \Concrete\Package\FormidableFull\Src\Formidable::getAllForms();

$preview_token = Core::make('token')->generate('formidable_preview');
$result_token  = Core::make('token')->generate('formidable_result');
?>

<div class="ccm-header-search-form ccm-ui" data-header="file-manager">

    <form method="get" action="<?php echo URL::to('/formidable/dialog/dashboard/results/search/basic') ?>">

        <div class="input-group">
            <div class="ccm-header-search-form-select">
                <?php echo $form->select('formID', $forms, $formID); ?>
            </div>
            <div class="ccm-header-search-form-input">
                <a class="ccm-header-reset-search" href="#"
                   data-button-action-url="<?php echo URL::to('/formidable/dialog/dashboard/results/search/clear') ?>"
                   data-button-action="clear-search"><?php echo t('Reset Search') ?></a>
                <a class="ccm-header-launch-advanced-search"
                   href="<?php echo URL::to('/formidable/dialog/dashboard/results/search/advanced_search') ?>"
                   data-launch-dialog="advanced-search"><?php echo t('Advanced') ?></a>
                <input type="text" class="form-control" autocomplete="off" name="fKeywords"
                       placeholder="<?php echo t('Search') ?>">
            </div>

            <span class="input-group-btn">
                <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
            </span>
        </div>
        <ul class="ccm-header-search-navigation">
            <?php if ($formID) { ?>
                <li>
                    <a href="<?php echo URL::to('/formidable/dialog/dashboard/forms/preview'); ?>?formID=<?php echo $formID; ?>&amp;ccm_token=<?php echo $preview_token; ?>"
                       class="link-primary dialog-launch" dialog-title="<?php echo t('Preview Form'); ?>"
                       dialog-width="900" dialog-height="600" dialog-modal="true"><i
                                class="fa fa-eye"></i> <?php echo t('Preview') ?></a></li>
                <li><a href="<?php echo URL::to('/dashboard/formidable/forms/edit/', $formID); ?>" class=""><i
                                class="fa fa-pencil"></i> <?php echo t('Edit form') ?></a></li>
                <li>
                    <a href="<?php echo URL::to('/formidable/dialog/dashboard/results/csv'); ?>?ccm_token=<?php echo $result_token; ?>"
                       id="export_to_csv" dialog-width="520" dialog-height="100" dialog-modal="true"
                       class="link-success"><i class="fa fa-download"></i> <?php echo t('Export to CSV') ?></a>
                </li>
            <?php } else { ?>
                <li><a href="<?php echo URL::to('/dashboard/formidable/forms/add'); ?>" class="link-primary"><i
                                class="fa fa-plus"></i> <?php echo t('Add form') ?></a></li>
            <?php } ?>
        </ul>

        <div id="ExportCSVDialog" style="display: none;">
            <span>Export Options</span><br>
            <input type="radio" value="latest" name="method"/> Latest x records - <input type="number" name="x"/><br>
            <input type="radio" value="all" name="method"/> All Records<br>
        </div>
    </form>
</div>
<div class="clearfix"></div>

<script>

    var EXPORT_CSV_MODULE = {
        METHOD_LATEST: 'latest',
        METHOD_ALL: 'all',
        EXPORT_DIALOG_ELEM: $('div#ExportCSVDialog'),
        EXPORT_CSV_BUTTON: $('#export_to_csv'),

        init: function () {
            var parent_obj = this;

            parent_obj.EXPORT_CSV_BUTTON.click(function (evt) {

                evt.preventDefault();
                var href = this.href;

                parent_obj.EXPORT_DIALOG_ELEM.dialog({
                    title: 'Export To CSV',
                    buttons: [
                        {
                            class: 'btn cancel',
                            click: function () {
                                $(this).dialog('close');
                            },
                            text: 'Cancel'
                        },
                        {
                            class: 'btn btn-primary save',
                            click: function () {

                                //get value
                                var method = $('input[name=method]:checked').val();
                                if (!method) {
                                    alert('Please select a choice');
                                    return;

                                }
                                if (method == parent_obj.METHOD_LATEST) {
                                    var val = $('input[name="x"]').val();
                                    if (!val) {
                                        alert('Please enter a number.');
                                        return;
                                    }
                                    var url = href += "&method=" + parent_obj.METHOD_LATEST + '&x=' + val;
                                } else if (method == parent_obj.METHOD_ALL) {
                                    var url = href;
                                }

                                $(this).dialog('close');
                                location.href = url;

                            },
                            text: 'Export'
                        }
                    ],
                    dialogClass: 'ccm-ui',
                    height: 400,
                    modal: true,
                    width: 400
                });

            });


        }

    };

    $(document).ready(function () {

        EXPORT_CSV_MODULE.init();

    });

</script>

