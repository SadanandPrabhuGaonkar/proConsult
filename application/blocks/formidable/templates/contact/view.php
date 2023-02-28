<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if (!$f->getFormID()) { ?>
    <div class="alert alert-danger">
        <?php echo t('Can\'t find the Formidable Form'); ?>
    </div>
<?php } else { ?>

    <div id="formidable_container_<?php echo $f->getFormID() ?>" class="formidable <?php echo $error?'error':'' ?>">

        <div id="formidable_message_<?php echo $f->getFormID() ?>" class="formidable_message">
            <?php if ($limits) { ?><div class="warning"><?php echo $limits; ?></div><?php } ?>
            <?php if ($schedule) { ?><div class="info"><?php echo $schedule; ?></div><?php } ?>
            <?php if ($error) { ?>
                <div class="danger">
                    <?php foreach ((array)$error as $er) { ?>
                        <div><?php echo $er ?></div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <?php if (!$limits && !$schedule) { ?>

            <form id="ff_<?php echo $f->getFormID() ?>" name="formidable_form" method="post" class="<?php echo $f->getAttribute('class'); ?> myContactForm" action="<?php echo \URL::to('/formidable/dialog/formidable'); ?>">
                <input type="hidden" name="formID" id="formID" value="<?php echo $f->getFormID(); ?>">
                <input type="hidden" name="cID" id="cID" value="<?php echo $f->getCollectionID(); ?>">
                <input type="hidden" name="bID" id="bID" value="<?php echo $f->getBlockID(); ?>">
                <input type="hidden" name="resolution" id="resolution" value="">
                <input type="hidden" name="ccm_token" id="ccm_token" value="<?php echo $f->getToken(); ?>">
                <input type="hidden" name="locale" id="locale" value="<?php echo $f->getLocale(); ?>">
                <?php
                $layout = $f->getLayout();
                if(is_array($layout)) {
                if (count($layout) && is_array($layout)) {
                    foreach($layout as $row) { ?>
                        <div class="formidable_row">
                        <?php
                            $i=0;
                            $width = round(12/count($row));
                            foreach($row as $column) { ?>
                                <div class="formidable_column width-<?php echo $width; ?> <?php echo ($i==(count($row)-1)?' last':''); ?>">
                                <?php
                                    echo $column->getContainerStart();
                                    $elements = $column->getElements();
                                    if(is_array($elements)) {
                                    if(count($elements) && is_array($elements)) {
                                        foreach($elements as $element) {
                                            if (in_array($element->getElementType(), array('hidden', 'captcha', 'hr', 'heading', 'line', 'invisiblecaptcha'))) echo $element->getInput();
                                            else { ?>
                                                <div class="element <?php echo $element->getHandle(); ?>">
                                                    <?php if ($column->hasElementsWithLabels()) { ?>
                                                        <?php if ($element->getPropertyValue('label_hide')) { ?>
                                                            <div class="label-hidden"></div>
                                                        <?php } else { ?>
                                                            <label for="<?php echo $element->getHandle(); ?>">
                                                                <?php echo t($element->getLabel()); ?>
                                                                <?php if ($element->getPropertyValue('required')) { ?>
                                                                    <span class="required">*</span>
                                                                <?php } ?>
                                                            </label>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <div class="input">

                                                        <?php
                                                            // Changing elements format (for checkboxes and radios)
                                                            //$element->setFormat('<div class="radio {SIZE}"><label for="{ID}">{ELEMENT} {TITLE}</label></div>');
                                                            echo $element->getInput();
                                                        ?>

                                                        <?php if ($element->getPropertyValue('min_max')) { ?>
                                                            <div class="help-block">
                                                                <div id="<?php echo $element->getHandle() ?>_counter" class="counter" type="<?php echo $element->getPropertyValue('min_max_type') ?>" min="<?php echo $element->getPropertyValue('min_value') ?>" max="<?php echo $element->getPropertyValue('max_value') ?>">
                                                                    <?php if ($element->getPropertyValue('max_value') > 0) { ?>
                                                                        <?php echo t('You have') ?> <span id="<?php echo $element->getHandle() ?>_count"><?php echo $element->getPropertyValue('max_value') ?></span> <?php echo $element->getPropertyValue('min_max_type'); ?> <?php echo t('left')?>.
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ($element->getPropertyValue('option_other')) { ?>
                                                        <?php if ($column->hasElementsWithLabels()) { ?>
                                                            <div class="label-hidden"></div>
                                                        <?php } ?>
                                                        <div class="input option_other">
                                                            <?php echo $element->getOther(); ?>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($element->getPropertyValue('confirmation')) { ?>
                                                        <div class="clear"></div>
                                                        <?php if ($column->hasElementsWithLabels()) { ?>
                                                            <?php if ($element->getPropertyValue('label_hide')) { ?>
                                                                <div class="label-hidden"></div>
                                                            <?php } else { ?>
                                                                <label for="<?php echo $element->getHandle(); ?>">
                                                                    <?php echo t('Confirm %s', $element->getLabel()) ?>
                                                                    <?php if ($element->getPropertyValue('required')) { ?>
                                                                        <span class="required">*</span>
                                                                    <?php } ?>
                                                                </label>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <div class="input">
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
                                        } }
                                        echo $column->getContainerEnd();
                                        $i++;
                                    ?>
                                </div>
                            <?php } ?>

                        </div>
                        <?php
                    }
                } }?>

                <?php if (!$f->hasButtons()) { ?>
                    <div class="formidable_row">
                        <div class="formidable_column width-12">
                            <div class="element">
                                <div class="label-hidden"></div>
                                <div id="ff_buttons" class="buttons col-sm-9">
                                    <?php echo Core::make('helper/form')->submit('btnSubmit_'.$f->getFormID(), t('Send'), array(), 'submit btn btn-success button button-violet-dark captcha--buttons'); ?>
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
                    'error_messages_on_top_class': 'error',
                    'warning_messages_class': 'warning',
                    'error_messages_beneath_field': true,
                    'error_messages_beneath_field_class': 'error',
                    'success_messages_class': 'success',
                    'remove_form_on_success': true,
                    errorCallback: function() {
                        //Uncomment for invisible recaptcha
                        var c = $('.captcha--buttons').length;
                        for (var i = 0; i < c; i++){
                            grecaptcha.reset(i);
                        }
                    },
                    successCallback: function() { }
                });
                <?php echo $f->getJquery(); ?>
            });
        </script>
    <?php } ?>
<?php }
