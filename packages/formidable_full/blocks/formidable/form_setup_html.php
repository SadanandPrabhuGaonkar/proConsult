<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="ccm-ui">    
    <?php if(is_array($forms)) {
        if (count($forms)) { ?>
    <fieldset>
    <legend><?php echo t('Select a Formidable Form')?></legend>
    <div class="clearfix">
        <?php echo $form->label('form', t('Form Name').' <span class="ccm-required">*</span>')?>
        <div class="input">
        	<?php echo $form->select('formID', $forms, $controller->formID);?>
        </div>
    </div>	
    </fieldset>        
    <?php } else { ?>    
    <div class="alert alert-warning">
        <p><strong><?php echo t('There are no Formidable Forms!') ?></strong></p>
        <p class="ccm-note"><?php echo t('Go to dashboard and create a Formidable Form') ?></p>
        <p><a href="<?php echo URL::to('/dashboard/formidable/forms/'); ?>" class="btn btn-default"><?php echo t('Create a new Formidable Form') ?></a></p>    
    </div>
    <?php } }?>
</div>