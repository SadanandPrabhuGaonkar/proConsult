<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php echo $form->label($view->field('title'), t("Title")); ?>
    <?php echo isset($btFieldsRequired) && in_array('title', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('title'), $title, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('subtitle'), t("Subtitle")); ?>
    <?php echo isset($btFieldsRequired) && in_array('subtitle', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('subtitle'), $subtitle, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('number'), t("Phone Number")); ?>
    <?php echo isset($btFieldsRequired) && in_array('number', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('number'), $number, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('email'), t("Email")); ?>
    <?php echo isset($btFieldsRequired) && in_array('email', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('email'), $email, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('linkedin'), t("Linkedin URL")); ?>
    <?php echo isset($btFieldsRequired) && in_array('linkedin', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('linkedin'), $linkedin, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('stack'), t("Stack")); ?>
    <?php echo isset($btFieldsRequired) && in_array('stack', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->selectMultiple($view->field('stack'), $stack_options, isset($stack_selected) ? $stack_selected : [], []); ?>
</div>

<script type="text/javascript">
    Concrete.event.publish('contact_block.stack.stacks');
</script>

