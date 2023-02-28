<?php 
	defined('C5_EXECUTE') or die("Access Denied."); 	
	
	$task = $this->controller->getTask();

	$ui = new \Concrete\Core\Application\Service\UserInterface();
	$lh = new \Concrete\Package\FormidableFull\Src\Helpers\LinkHelper();
	
	?>
	<style>
		.ccm-tab-content .tooltip-inner { max-width: initial !important; }
		.ccm-tab-content .ccm-generic-thumbnail { max-width: 30px; display: inline-block; }
		.ccm-tab-content .upload_row { border-top: 1px solid #dbdbdb; padding: 14px 0px;}
		.ccm-tab-content .upload_row:first-child { border:0; padding-top: 0; }
		.ccm-tab-content tr { vertical-align: top; }
	</style>
	<div class="ccm-ui">

		<?php if (is_array($errors)) { ?>
			<div class="alert alert-danger">
				<?php echo $errors['message']; ?>
			</div>
		<?php } else { ?>

			<?php if ($task == 'view') { ?>
				<div class="ccm-ui">
					<?php if ($block) { ?>
						<div class="alert alert-warning">
							<b><?php echo t('Note:'); ?></b> <?php echo t('The captcha element (if you have one) will not be shown and validated in the preview-mode.'); ?>
						</div>
					<?php } else { ?>
						<div class="alert alert-danger">
							<?php echo t('Access denied') ?>
						</div>
					<?php } ?>
				</div>
				<?php $block?$block->render('/templates/dashboard/view'):''; ?>
				<div class="dialog-buttons">
					<button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
				</div>
			<?php 
			} 

			if ($task == 'result') { 

				if (!is_object($result)) { ?>
					<div class="alert alert-danger">
						<?php echo t('No results found.'); ?>
					</div>
				<?php } else { ?>				
					<form>
				    	<div id="ccm-result-response"></div>				
							<?php 
				       			$tabs = array(
				                    array('data', t('Submitted data'), true),
				                    array('details', t('Submission details')),
				                );
				                echo $ui::tabs($tabs);
				            ?>
					        <div id="ccm-tab-content-data" class="ccm-tab-content">

				        		<table border="0" cellspacing="0" cellpadding="0" class="table-responsive ccm-search-results-table">
									<thead>
										<tr>
											<th><span><?php echo t('Label'); ?></span></th>
											<th><span><?php echo t('Value'); ?></span></th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$elements = $form->getElements();
											if(is_array($elements)) {
                                                if (count($elements)) {
                                                    foreach ($elements as $element) {
                                                        if ($element->isLayout() || $element->getElementType() == 'captcha') continue;
                                                        // Assign the result to the element
                                                        $element->setValue($result->getAnswerByElementID($element->getElementID()), true);
									   	?>
									   		<tr>
									   			<td><?php echo $element->getLabel(); ?></td>
									   			<td><?php echo $element->getDisplayResult(); ?></td>
									   		</tr>
									   	<?php } } }?>
									</tbody>
								</table>       		
					        </div>

					        <div id="ccm-tab-content-details" class="ccm-tab-content">

					        	<table border="0" cellspacing="0" cellpadding="0" class="table-responsive ccm-search-results-table">
									<thead>
										<tr>
											<th><span><?php echo t('Label'); ?></span></th>
											<th><span><?php echo t('Value'); ?></span></th>
										</tr>
									</thead>
									<tbody>

										<tr>
											<td><?php echo t('Submitted on') ?></td>
											<td><?php echo $result->getSubmissionDate(); ?></td>      
										</tr> 
										<tr>
											<td><?php echo t('From page') ?></td>
											<td><?php echo $result->getPageData('collection_url'); ?></td>      
										</tr>
										<tr>
											<td><?php echo t('Submitted by') ?></td>
											<td><?php echo $result->getUserData('user_url'); ?></td>      
										</tr>
										<tr>
											<td><?php echo t('Localization') ?></td>
											<td><?php echo $result->getCurrentLocale(); ?></td>
										</tr> 
										<tr>
											<td><?php echo t('Answerset ID (unique)') ?></td>
											<td><?php echo $result->getAnswerSetID(); ?></td>
										</tr>  
										<tr>
											<td><?php echo t('Submitters IP') ?></td>
											<td><?php echo $result->getIPAddress(); ?></td>      
										</tr> 	 
										<tr>
											<td><?php echo t('Used Browser') ?></td>
											<td><?php echo $result->getBrowser(); ?></td>      
										</tr> 
										<tr>
											<td><?php echo t('Platform') ?></td>
											<td><?php echo $result->getPlatform(); ?></td>      
										</tr> 
										<tr>
											<td><?php echo t('Screen resolution') ?></td>
											<td><?php echo $result->getResolution(); ?></td>      
										</tr> 

									</tbody>
								</table>
					        </div>
				    	</div>
				    	<div class="dialog-buttons">
							<button class="btn btn-default pull-left" data-dialog-action="cancel"><?php echo t('Cancel')?></button>
						</div>
				    </form>
				    <script>
					    $(function() {
					    	$('.ccm-tab-content a[data-toggle="tooltip"]').tooltip({
							    animated: 'fade',
							    placement: 'right',
							    html: true
							});
					    });
				    </script>
				<?php 
				}
			}
		}
		?>
	</div>
