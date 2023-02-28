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

<?php if ($create_form) { ?>

	<?php echo View::element('dashboard/form/nav', array('f' => $f), $pkgHandle)?>
	<?php echo View::element('dashboard/form/edit', array('f' => $f, 'limit_submissions_types' => $this->controller->limit_submissions_types), $pkgHandle)?>

	<script>
		$(function() {	
			ccmFormidableCreateMenu();	
			ccmFormidableFormCheckSelectors();
			$("select[name=limits]").change(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			$("select[name=limits_redirect]").change(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			$("select[name=schedule]").change(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			$("select[name=schedule_redirect]").change(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			$("select[name=submission_redirect]").change(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			$("input[name=css]").click(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			$("select[name=errors]").change(function() {
				ccmFormidableFormCheckSelectors($(this));
			});
			var serialized_form = $('form[name="formidable_form_edit"]').serialize();
		});
	</script>

<?php } else { ?>


<div class="ccm-pane-body"> 

	<div data-search-element="results">
		<div class="ccm-dashboard-header-buttons btn-group">
			<a href="<?php echo URL::to('/dashboard/formidable/forms/add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> <?php  echo t('Add form')?></a>
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