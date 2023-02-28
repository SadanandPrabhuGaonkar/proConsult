<?php   
defined('C5_EXECUTE') or die("Access Denied.");    
$pkgHandle = 'formidable_full'; 
?>
<div class="ccm-pane-body"> 
	<?php  echo View::element('dashboard/form/nav', array('f' => $f), $pkgHandle)?>
    <br>
	<div data-search-element="results">
		<div class="table-responsive" id="ccm-formidable-mailings">			
		</div>
	</div>
	<div id="ccm-form-record">
		<div class="loader"></div>		
	</div>
</div>

<div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions">
        <a href="javascript:ccmFormidableOpenMailingDialog(0);" class="btn btn-success pull-right"><i class="fa fa-plus"></i> <?php  echo t('Add mailing')?></a>
    </div>
</div>

<script>
var formID = <?php echo is_object($f)?$f->getFormID():0 ?>;
</script> 

