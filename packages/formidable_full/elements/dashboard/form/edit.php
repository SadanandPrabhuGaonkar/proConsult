<?php 
defined('C5_EXECUTE') or die("Access Denied.");
 
$form = Core::make('helper/form');
$editor = Core::make('editor');
$date_time = new Concrete\Core\Form\Service\Widget\DateTime();
$form_page_selector = new Concrete\Core\Form\Service\Widget\PageSelector();

?>
<form method="post" action="<?php echo View::getInstance()->action('save')?>" id="ccm-form-record" name="formidable_form_edit">
    <?php echo is_object($f)?$form->hidden('formID', intval($f->getFormID())):''; ?>
    <p>&nbsp;</p>

    <fieldset class="form-horizontal">        
        <div class="form-group">
            <?php echo $form->label('label', t('Form name'), array('class' => 'col-sm-3')) ?>
            <div class="col-sm-9">
                <?php echo $form->text('label', is_object($f)? t($f->getLabel()) : '', array('placeholder' => t('My Formidable Form')))?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->label('submission_redirect', t('After submission'), array('class' => 'col-sm-3'))?>
            <div class="col-sm-9">
                <div class="message-or-page">
                    <?php echo $form->select('submission_redirect', array(t('Show message'), t('Redirect to page')), is_object($f)?intval($f->getAttribute('submission_redirect')):''); ?>

                    <div id="submission_redirect_content">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo $form->label('submission_redirect_content', t('Message'))?></div>
                            <div class="form-control-editor">
                                <?php print $editor->outputStandardEditor('submission_redirect_content', is_object($f)?$f->getAttribute('submission_redirect_content'):''); ?>
                            </div>
                        </div>
                    </div>
                    <div id="submission_redirect_page">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo $form->label('submission_redirect_page', t('Select page'))?></div>
                            <?php echo $form_page_selector->selectPage('submission_redirect_page', is_object($f)?(intval($f->getAttribute('submission_redirect_page'))!=0?intval($f->getAttribute('submission_redirect_page')):''):''); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->label('limits', t('Enable limits'), array('class' => 'col-sm-3'))?>
            <div class="col-sm-9">
                
                <?php echo $form->select('limits', array(0 => t('No limits'), 1 => t('Enable limits')), is_object($f)?intval($f->getAttribute('limits')):''); ?>

                <div id="limits_div">

                    <?php echo $form->label('limits_value', t('Set limits')) ?>
                    <div class="input-group">
                        <?php echo $form->text('limits_value', is_object($f)?$f->getAttribute('limits_value'):'', array('style' => 'width:40%', 'placeholder' => t('Value')))?>
                        <?php echo $form->select('limits_type', array('total' => t('Total submissions'), 'ip' => t('Per IP-address'), 'user' => t('Per user (guest-visitors excluded)')), is_object($f)?$f->getAttribute('limits_type'):'', array('style' => 'width:60%')); ?>
                    </div>

                    <div class="message-or-page">
                        <?php echo $form->select('limits_redirect', array(t('Show message'), t('Redirect to page')), is_object($f)?intval($f->getAttribute('limits_redirect')):''); ?>

                        <div id="limits_redirect_content">
                            <div class="input-group">
                                <div class="input-group-addon"><?php echo $form->label('limits_redirect_content', t('Message'))?></div>
                                <div class="form-control-editor">
                                    <?php print $editor->outputStandardEditor('limits_redirect_content', is_object($f)?$f->getAttribute('limits_redirect_content'):''); ?>
                                </div>
                            </div>
                        </div>
                        <div id="limits_redirect_page">
                            <div class="input-group">
                                <div class="input-group-addon"><?php echo $form->label('limits_redirect_page', t('Select page'))?></div>
                                <?php echo $form_page_selector->selectPage('limits_redirect_page', is_object($f)?(intval($f->getAttribute('limits_redirect_page'))!=0?intval($f->getAttribute('limits_redirect_page')):''):''); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->label('schedule', t('Enable scheduling'), array('class' => 'col-sm-3'))?>
            <div class="col-sm-9">
                
                <?php echo $form->select('schedule', array(0 => t('No scheduling'), 1 => t('Enable scheduling')), is_object($f)?intval($f->getAttribute('schedule')):''); ?>

                <div id="schedule_div">
                    <div>
                        <?php echo $form->label('schedule_start', t('From')) ?>
                        <?php echo $date_time->datetime('schedule_start', is_object($f)?$f->getAttribute('schedule_start'):date("Y-m-d"), true, true); ?>
                        <?php echo $form->label('schedule_end', t('To')) ?>
                        <?php echo $date_time->datetime('schedule_end', is_object($f)?$f->getAttribute('schedule_end'):date("Y-m-d"), true, true); ?>
                    </div>
                    
                    <?php echo $form->label('schedule_label', t('When outside schedule')) ?>
                    
                    <div class="message-or-page">
                        <?php echo $form->select('schedule_redirect', array(t('Show message'), t('Redirect to page')), is_object($f)?intval($f->getAttribute('schedule_redirect')):''); ?>

                        <div id="schedule_redirect_content">
                            <div class="input-group">
                                <div class="input-group-addon"><?php echo $form->label('schedule_redirect_content', t('Message'))?></div>
                                <div class="form-control-editor">
                                    <?php print $editor->outputStandardEditor('schedule_redirect_content', is_object($f)?$f->getAttribute('schedule_redirect_content'):''); ?>
                                </div>
                            </div>
                        </div>
                        <div id="schedule_redirect_page">
                            <div class="input-group">
                                <div class="input-group-addon"><?php echo $form->label('schedule_redirect_page', t('Select page'))?></div>
                                <?php echo $form_page_selector->selectPage('schedule_redirect_page', is_object($f)?(intval($f->getAttribute('schedule_redirect_page'))!=0?intval($f->getAttribute('schedule_redirect_page')):''):''); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->label('css', t('CSS Classes'), array('class' => 'col-sm-3'))?>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-addon"><?php echo $form->checkbox('css', 1, is_object($f)?intval($f->getAttribute('css')) != 0:'')?></div>
                    <?php echo $form->text('css_value', is_object($f)?$f->getAttribute('css_value'):''); ?>
                </div>
                <div id="css_content_note" class="note help-block"><?php echo t('Add classname(s) to customize your form. Example: myform'); ?></div>
            </div>
        </div>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?php echo URL::to(Page::getByPath('/dashboard/formidable/forms'))?>" class="btn pull-left btn-default"><?php echo t('Back')?></a>
            <?php echo $form->submit('submit', t('Save').' '.t('Form Properties'), '', 'btn-primary pull-right'); ?>
        </div>
    </div>
</form> 