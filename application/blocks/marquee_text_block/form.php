<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php echo $form->label($view->field('text'), t("Text")); ?>
    <?php echo isset($btFieldsRequired) && in_array('text', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('text'), $text, array (
  'maxlength' => 255,
)); ?>
</div>