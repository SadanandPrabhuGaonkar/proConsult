<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if (!$f->getFormID()) { ?>
    <div class="alert alert-danger">
        <?php echo t('Can\'t find the Formidable Form'); ?>
    </div>
<?php } else { ?>

    <div id="formidable_container_<?php echo $f->getFormID() ?>" class="formidable <?php echo $error?'error':'' ?>">

        <div id="formidable_message_<?php echo $f->getFormID() ?>" class="formidable_message">
            <?php if ($limits) { ?><div class="alert alert-warning"><?php echo $limits; ?></div><?php } ?>
            <?php if ($schedule) { ?><div class="alert alert-info"><?php echo $schedule; ?></div><?php } ?>
        </div>

        <?php if (!$limits && !$schedule) { ?>
            <?php if ($error) { ?>
                <div id="ff_msg_<?php echo $f->getFormID() ?>" class="alert alert-danger">
                    <?php foreach ((array)$error as $er) { ?>
                        <div><?php echo $er ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <form id="ff_<?php echo $f->getFormID() ?>" name="formidable_form" method="post" class="<?php echo $f->getAttribute('class'); ?>" role="form" action="<?php echo \URL::to('/formidable/dialog/formidable'); ?>">
                <input type="hidden" name="formID" id="formID" value="<?php echo $f->getFormID(); ?>">
                <input type="hidden" name="cID" id="cID" value="<?php echo $f->getCollectionID(); ?>">
                <input type="hidden" name="bID" id="bID" value="<?php echo $f->getBlockID(); ?>">
                <input type="hidden" name="resolution" id="resolution" value="">
                <input type="hidden" name="ccm_token" id="ccm_token" value="<?php echo $f->getToken(); ?>">
                <input type="hidden" name="locale" id="locale" value="<?php echo $f->getLocale(); ?>">
                <?php
                $layout = $f->getLayout();
                if ($layout && count($layout) && is_array($layout)) {
                    foreach($layout as $row) { ?>
                        <div class="formidable_row row">
                        <?php
                            $i=0;
                            $width = round(12/count($row));
                            foreach($row as $column) { ?>
                                <div class="formidable_column col-sm-<?php echo $width; ?> <?php echo ($i==(count($row)-1)?' last':''); ?>">
                                <?php
                                    echo $column->getContainerStart();
                                    $elements = $column->getElements();
                                    if($elements && count($elements)) {
                                        foreach($elements as $element) {
                                            if (in_array($element->getElementType(), array('hidden', 'captcha', 'hr', 'heading', 'line'))) echo $element->getInput();
                                            else { ?>
                                                <div class="element form-group <?php echo $element->getHandle(); ?>">
                                                    <?php if ($column->hasElementsWithLabels()) { ?>
                                                        <?php if (!$element->getPropertyValue('label_hide')) { ?>
                                                            <label for="<?php echo $element->getHandle(); ?>">
                                                                <?php echo $element->getLabel(); ?>
                                                                <?php if ($element->getPropertyValue('required')) { ?>
                                                                    <span class="required">*</span>
                                                                <?php } ?>
                                                            </label>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <div class="input <?php echo $element->getPropertyValue('label_hide')?'no_label':'has_label'; ?>">

                                                        <?php
                                                            // Changing elements format (for checkboxes and radios)
                                                            //$element->setFormat('<div class="radio {SIZE}"><label for="{ID}">{ELEMENT} {TITLE}</label></div>');
                                                            echo $element->getInput();
                                                        ?>

                                                        <?php if ($element->getPropertyValue('min_max')) { ?>
                                                            <div class="help-block">
                                                                <div id="<?php echo $element->getHandle() ?>_counter" class="counter" type="<?php echo $element->getPropertyValue('min_max_type') ?>" min="<?php echo $element->getPropertyValue('min_value') ?>" max="<?php echo $element->getPropertyValue('max_value') ?>">
                                                                    <?php if ($element->getPropertyValue('max_value') > 0) { ?>
                                                                        <?php  echo t('You have') ?> <span id="<?php echo $element->getHandle() ?>_count"><?php echo $element->getPropertyValue('max_value') ?></span> <?php echo $element->getPropertyValue('min_max_type'); ?> <?php echo t('left')?>.
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ($element->getPropertyValue('option_other')) { ?>
                                                        <div class="input option_other <?php echo $element->getPropertyValue('label_hide')?'no_label':'has_label'; ?>">
                                                            <?php echo $element->getOther(); ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($element->getPropertyValue('confirmation')) { ?>
                                                        <div class="clearfix"></div>
                                                        <?php if ($column->hasElementsWithLabels()) { ?>
                                                            <?php if (!$element->getPropertyValue('label_hide')) { ?>
                                                                <label for="<?php echo $element->getHandle(); ?>">
                                                                    <?php echo t('Confirm %s', $element->getLabel()) ?>
                                                                    <?php if ($element->getPropertyValue('required')) { ?>
                                                                        <span class="required">*</span>
                                                                    <?php } ?>
                                                                </label>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <div class="input <?php echo $element->getPropertyValue('label_hide')?'no_label':'has_label'; ?>">
                                                            <?php echo $element->getConfirm(); ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($element->getPropertyValue('tooltip') && !$review) { ?>
                                                        <div class="tooltip" id="<?php echo "tooltip_".$element->getElementID(); ?>">
                                                            <?php echo $element->getPropertyValue('tooltip_value'); ?>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                            <?php
                                                }
                                            }
                                        }
                                        echo $column->getContainerEnd();
                                        $i++;
                                    ?>
                                </div>
                            <?php } ?>

                        </div>
                        <?php
                    }
                } ?>

                <?php if (!$f->hasButtons()) { ?>
                    <div class="formidable_row row">
                        <div class="formidable_column col-sm-12">
                            <div class="element form-group form-actions">
                                <div class="col-sm-3"></div>
                                <div id="ff_buttons" class="buttons col-sm-9">
                                    <?php echo Core::make('helper/form')->submit('submit', t('Submit'), array(), 'submit btn btn-success'); ?>
                                    <div class="please_wait_loader"><img src="<?php echo BASE_URL ?>/packages/formidable_full/images/loader.gif" alt="<?php echo t('Please wait...'); ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </form>
        </div>

        <script>
            <?php echo $f->getJavascript(); ?>
            $(function() {
                $('form[id="ff_<?php echo $f->getFormID(); ?>"]').formidable({
                    'error_messages_on_top': false,
                    'error_messages_on_top_class': 'alert alert-danger',
                    'warning_messages_class': 'alert alert-warning',
                    'error_messages_beneath_field': true,
                    'error_messages_beneath_field_class': 'text-danger error',
                    'success_messages_class': 'alert alert-success',
                    'remove_form_on_success': true,
                    errorCallback: function() { },
                    successCallback: function() { }
                });
                <?php echo $f->getJquery(); ?>
            });
        </script>
    <?php } ?>
<?php }
