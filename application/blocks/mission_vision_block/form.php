<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php echo $form->label($view->field('mission'), t("Mission")); ?>
    <?php echo isset($btFieldsRequired) && in_array('mission', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('mission'), $mission); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('vision'), t("Vision")); ?>
    <?php echo isset($btFieldsRequired) && in_array('vision', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('vision'), $vision); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('objective'), t("Objective")); ?>
    <?php echo isset($btFieldsRequired) && in_array('objective', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('objective'), $objective); ?>
</div>