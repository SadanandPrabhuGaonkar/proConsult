<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$pkgHandle = 'formidable_full';  
?>

<?php if ($errors) { ?>
	<div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<?php  foreach((array)$errors as $e) { ?>
			<?php  echo $e?><br/>
		<?php  } ?>
	</div>
<?php } ?>

<?php if ($create_template) { 
	
	$form = Core::make('helper/form');
	$editor = Core::make('editor');
?>	

<form method="post" action="<?php echo View::getInstance()->action('save')?>" id="ccm-form-record" name="formidable_form_edit">
    <?php echo is_object($t)?$form->hidden('templateID', intval($t->getTemplateID())):''; ?>
    <p>&nbsp;</p>

    <fieldset class="form-horizontal">        
        <div class="form-group">
            <?php echo $form->label('label', t('Name'), array('class' => 'col-sm-3')) ?>
            <div class="col-sm-9">
                <?php echo $form->text('label', is_object($t)?$t->getLabel():'', array('placeholder' => t('My Formidable Template')))?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->label('content', t('Content'), array('class' => 'col-sm-3'))?>
            <div class="col-sm-9">
                <div class="form-control-editor">
                    <?php print $editor->outputStandardEditor('content', is_object($t)?$t->getContent():''); ?>
                </div>
            </div>
        </div>                       
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?php echo URL::to($c)?>" class="btn pull-left btn-default"><?php echo t('Back')?></a>
            <?php echo $form->submit('submit', t('Save'), '', 'btn-primary pull-right'); ?>
        </div>
    </div>
</form> 

	<script>
		$(function() {	
			ccmFormidableCreateMenu();	
		});
	</script>

<?php } else { ?>


<div class="ccm-pane-body"> 

	<div data-search-element="results">
		<div class="ccm-dashboard-header-buttons btn-group">
			<a href="<?php echo URL::to('/dashboard/formidable/templates/add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> <?php  echo t('Add template')?></a>
		</div>
		
		<div class="ccm-dashboard-content-full" style="margin-top:-30px;">
			<div class="table-responsive" id="ccm-formidable-forms">
				
			</div>
		</div>
	
		<div id="ccm-form-record">
			<div class="loader"></div>		
		</div>
	</div>
</div>

<?php } 