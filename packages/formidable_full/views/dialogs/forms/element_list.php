<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if (is_array($errors)) { ?>
	<div class="alert alert-danger">
		<?php echo $errors['message']; ?>
	</div>
<?php } else { ?>

<?php
    $layout_count = is_array($layouts) ? count($layouts) : 0;
    if($layout_count) {
		foreach($layouts as $rowID => $row) { ?>
			<div class="f-row" data-id="<?php echo $rowID; ?>" id="row_<?php echo $rowID; ?>">
				<div class="overlay"></div>
				<div class="inner">
					<div class="clearfix">
						<?php 
						$width = round(100/count($row)); $i=0;
						foreach($row as $layoutID => $layout) { 
							$elements = $layout->getElements();
							?>
							<div class="f-col <?php echo ($i==(count($row)-1)?'f-col-last':''); ?>" data-id="<?php echo $layout->getLayoutID(); ?>" style="width:<?php echo $width; ?>%">
								<div class="inner">
									<div class="overlay"></div>
									<div class="element_row_wrapper element-empty <?php  $elem_count = is_array($elements) ? count($elements) : 0;   echo $elem_count ?'hide':''; ?>"><em><?php echo t('Empty column'); ?></em></div>
									<?php 
										foreach((array)$elements as $element) {

											$delete_url = 'javascript:ccmFormidableDeleteElementDialog('.$element->getElementID().');';
											$duplicate_url = 'javascript:ccmFormidableDuplicateElement('.$element->getElementID().');';

											$edit_disabled = false;
											if ($element->element_type == 'line' || $element->element_type == 'hr')
												$edit_disabled = true;

											$duplicate_disabled = false;
											if ($element->element_type == 'captcha')
												$duplicate_disabled = true;
									?>
										<div class="element_row_wrapper" style="display: none;" data-element_id="<?php echo intval($element->getElementID()) ?>" data-combined_element_id="<?php echo intval($element->combined_elementID) ?>">
										  	<table id="element_<?php echo $element->getElementID() ?>" class="element_row entry ccm-results-list">
												<tr class="ccm-list-record">
													<td class="element_mover"><i class="mover fa fa-arrows"></i></td>
													<td class="element_label" data-launch-search-menu="options_<?php echo $element->getElementID() ?>">
														<span><?php echo $element->getLabel() ?></span> <?php echo $element->getPropertyValue('required')?'<sup class="text-danger">*</sup>':''; ?>
														<br /><small><?php echo $element->getElementText() ?></small>
													</td>
												</tr>
										    </table>
										</div>

										<div class="ccm-popover-menu popover fade" data-search-menu="options_<?php echo $element->getElementID() ?>">
										    <div class="arrow"></div>
										    <div class="popover-inner">
										        <ul class="dropdown-menu">
										            <?php  if (!$edit_disabled) { ?>
										            <li><a href="javascript:ccmFormidableOpenElementDialog('<?php echo $element->getElementType() ?>','<?php echo $element->getElementText() ?>', <?php echo $layout->getLayoutID() ?>, <?php echo $element->getElementID() ?>);"><?php echo t('Edit') ?></a></li>
										            <li class="divider"></li>
										            <?php  } ?>
										            <?php  if (!$duplicate_disabled) { ?>										            
										            <li><a href="<?php echo $duplicate_url; ?>"><?php echo t('Duplicate') ?></a></li>
										            <li class="divider"></li>
										            <?php  } ?>
										            <?php  if (!$delete_disabled) { ?>										            
										            <li><a href="<?php echo $delete_url; ?>"><?php echo t('Delete') ?></a></li>
										            <?php  } ?>
										        </ul>
										    </div>
										</div>

									<?php } ?>
									<div class="tools col-tools" data-launch-search-menu="coloptions_<?php echo $layout->getLayoutID() ?>">
										<div class="tools-link"><a href="javascript:;">
											<i class="fa fa-pause"></i> <?php echo t('Column'); ?>
										</a></div>
									</div>

									<div class="ccm-popover-menu popover fade" data-search-menu="coloptions_<?php echo $layout->getLayoutID() ?>">
										<div class="arrow"></div>
										<div class="popover-inner">
											<ul class="dropdown-menu">													
												<li><a href="javascript:ccmFormidableOpenLayoutDialog(<?php echo $layout->getLayoutID() ?>,<?php echo intval($rowID) ?>);"><?php echo t('Edit') ?></a></li>
												<li><a href="javascript:ccmFormidableMoveColumns(<?php echo $rowID; ?>);"><?php echo t('Move') ?></a></li>
												<li class="divider"></li>
												<li><a href="javascript:ccmFormidableOpenNewElementDialog(<?php echo $layout->getLayoutID() ?>);"><?php echo t('Add element') ?></a></li>
												<li class="divider"></li>
												<li><a href="javascript:ccmFormidableOpenDeleteLayoutDialog(<?php echo $layout->getLayoutID() ?>,<?php echo intval($rowID) ?>);"><?php echo t('Delete') ?></a></li>
											</ul>
										</div>
									</div>

								</div>
							</div>
						<?php 
							$i++;
						}
						?>
					</div>
				</div>
				<div class="tools row-tools" data-launch-search-menu="rowoptions_<?php echo intval($rowID) ?>">
					<div class="tools-link"><a href="javascript:;">
						<i class="fa fa-bars"></i> 
						<?php echo t('Row'); ?>
					</a></div>
				</div>
				<div  class="ccm-popover-menu popover fade" data-search-menu="rowoptions_<?php echo intval($rowID) ?>">
					<div class="arrow"></div>
					<div class="popover-inner">
						<ul class="dropdown-menu">
							<li><a href="javascript:ccmFormidableOpenLayoutDialog(-1,<?php echo intval($rowID) ?>);"><?php echo t('Edit') ?></a></li>
							<li><a href="javascript:ccmFormidableMoveLayout();"><?php echo t('Move') ?></a></li>
							<li class="divider"></li>
							<li><a href="javascript:ccmFormidableOpenDeleteLayoutDialog(-1,<?php echo intval($rowID) ?>);"><?php echo t('Delete') ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		<?php  } ?>
		<div class="tools row-tools row-add">
			<a href="javascript:ccmFormidableOpenLayoutDialog(-1,-1);">
				<i class="fa fa-indent"></i>
				<?php echo t('Add row') ?>
			</a>
		</div>
	<?php  } ?>
<?php }