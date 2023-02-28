<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$form = Core::make('helper/form');
?>

<div class="form-group">
    <label class="control-label"><?php  echo t('Remove all content'); ?></label>
    <div class="checkbox">
        <label>
        	<?php  echo $form->checkbox('removeContent', 1); ?>                    
        	<span><?php  echo t('Remove all the content (forms and submissions)'); ?></span><br>
        	<span><?php  echo t('When you upgrade to Formidable (full-version), make sure this option is disabled.'); ?></span>
        </label>
    </div>
</div>
