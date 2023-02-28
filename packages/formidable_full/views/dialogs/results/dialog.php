<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$task = $this->controller->getTask();
$form = Core::make('helper/form');
?>

<div class="ccm-ui">

<?php if (is_array($errors)) { ?>
    <div class="alert alert-danger">
        <?php echo $errors['message']; ?>
    </div>
<?php } else {

    if(is_array($results)) {
    ?>
    
    <?php 
        if ($task == 'delete') { 
            $action = \URL::to('/formidable/dialog/dashboard/results/delete/submit');
            $button = t('Delete');
        ?> 
        <div class="alert alert-warning">
            <?php 
                if (is_array($results) && count($results) == 1) echo t('Are you sure you want to delete this result?');
                else echo t('Are you sure you want to delete the following results?')
            ?>
        </div>
    <?php } ?>

    <?php 
        if ($task == 'resend') { 
            $action = \URL::to('/formidable/dialog/dashboard/results/resend/submit');
            $button = t('Resend');
        ?> 
        <div class="alert alert-warning">
            <?php 
                if (is_array($results) && count($results) == 1) echo t('Are you sure you want to resend this result?');
                else echo t('Are you sure you want to resend the following results?')
            ?>
        </div>
    <?php } ?>

    <form data-dialog-form="delete-result" method="post" action="<?php echo $action ?>">
        <?php echo $form->hidden('ccm_token', Core::make('token')->generate('formidable_result')); ?>
        <?php 
            if (is_array($results) && count($results)) {
                foreach($results as $r) { 
                    echo $form->hidden('answerSetIDs[]', $r->getAnswerSetID());
                }                 
                if (is_array($results) && count($results) > 1) {?>
                <table width="100%" class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo t('AnswerSetID'); ?></th>
                            <th><?php echo t('Submitted on'); ?></th>
                            <th><?php echo t('By'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $r) { ?>                
                            <tr>
                                <td><?php echo $r->getAnswerSetID() ?></td>
                                <td><?php echo $r->getSubmissionDate() ?></td>
                                <td><?php echo $r->getUserName() ?></td>                    
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
        <?php } } ?>
    </form>

    <div class="dialog-buttons">
        <button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
        <button type="button" data-dialog-action="submit" class="btn btn-danger pull-right"><?php echo $button ?></button>
    </div>

<?php } } ?>

</div>