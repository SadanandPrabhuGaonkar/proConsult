<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$pkgHandle = 'formidable_full';
?>
<div class="ccm-pane-body ccm-ui ccm-formidable">
	
    <?php  echo View::element('dashboard/form/nav', array('f' => $f), $pkgHandle)?>
    <br>    
	<form method="post" action="<?php   echo $this->action('save') ?>" id="ccm-form-record">
		<?php echo is_object($f)?$form->hidden('formID', $f->getFormID()):''; ?>
		<fieldset>
			<div id="ccm-element-list">
				<div class="placeholder"><?php  echo t('Add row'); ?></div>
			</div>
		</fieldset>
		<div class="loader"></div>
		<div style="clear:both;"></div>
	</form>
</div>
<div class="ccm-pane-footer"></div>

<script>
var formID = <?php echo is_object($f)?$f->getFormID():0 ?>;
$(function() {
	ccmFormidableInitializeSortables();
});
</script>
<?php 
