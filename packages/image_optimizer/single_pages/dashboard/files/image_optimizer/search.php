<?php

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\Support\Facade\Url;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Utility\Service\Number $numberHelper */
/** @var int $totalFiles */
/** @var int $totalGained */
?>

<div class="ccm-dashboard-header-buttons btn-group">
    <form method="post" action="<?php echo $this->action('resetAll'); ?>" id="frm-reset-all-files">
        <?php
        /** @var $token \Concrete\Core\Validation\CSRF\Token */
        echo $token->output('a3020.image_optimizer.reset_all');
        ?>
        <input type="submit"
           data-placement="bottom"
           class="btn btn-danger launch-tooltip"
           title="<?php echo t('Use this if you want to re-optimize all images. Once reset, statistics of optimized images will be gone.'); ?>"
           value="<?php echo t("Reset all files")?>" />

        <a class="btn btn-success launch-tooltip"
           data-placement="bottom"
            title="<?php echo t('%s runs as an automated job', t('Image Optimizer')); ?>"
            href="<?php echo Url::to('/dashboard/system/optimization/jobs'); ?>">
            <?php echo t("Run Image Optimizer")?>
        </a>
    </form>
</div>

<div class="ccm-dashboard-content-inner page-optimized-images">
    <?php
    $this->element('/dashboard/review_notification', [], 'image_optimizer');
    ?>

    <?php
    if ($totalGained) {
        ?>
        <p class="text-muted" style="margin-bottom: 20px;">
            <?php
            echo t('Total file size gained: %s', $numberHelper->formatSize($totalGained));
            echo ' (' . t2('%s file', '%s files', $totalFiles) .')';
            ?>
        </p>
        <?php
    }
    ?>

    <table class="table table-striped table-bordered" id="tbl-files">
        <thead>
            <tr>
                <th><?php echo t('Image') ?></th>
                <th>
                    <?php echo t('When'); ?>
                    <i class="text-muted launch-tooltip fa fa-question-circle" data-placement="bottom"
                       title="<?php echo t('When this image was optimized.') ?>">
                    </i>
                </th>
                <th>
                    <?php echo t('Before'); ?>
                    <i class="text-muted launch-tooltip fa fa-question-circle" data-placement="bottom"
                       title="<?php echo t('The file size before the last optimization.') ?>">
                    </i>
                </th>
                <th>
                    <?php echo t('After'); ?>
                    <i class="text-muted launch-tooltip fa fa-question-circle" data-placement="bottom"
                       title="<?php echo t('The file size after optimization.') ?>">
                    </i>
                </th>
                <th>
                    <?php echo t('Saved file size'); ?>
                    <i class="text-muted launch-tooltip fa fa-question-circle" data-placement="bottom"
                       title="<?php echo t('How much smaller the image has become. The higher, the better.') ?>">
                    </i>
                </th>
                <th>
                    <?php echo t('OK'); ?>
                </th>
                <th>
                    <?php echo t('Reset'); ?>
                    <i class="text-muted launch-tooltip fa fa-question-circle" data-placement="right"
                       title="<?php echo t("Image Optimizer marks files it has processed in a log. By clicking the reset button, the log will be cleared for a file. By doing so, Image Optimizer will try to optimize the file again next time. Because files are overwritten, it may be that the image can't be optimized further.") ?>">
                    </i>
                </th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function() {
        var DataTableElement = $('#tbl-files');

        var DataTable = DataTableElement.DataTable({
            ajax: '<?php echo Url::to('/ccm/system/image_optimizer/files') ?>',
            lengthMenu: [[15, 40, 80, -1], [15, 40, 80, '<?php echo t('All') ?>']],
            columns: [
                {
                    width: '120px',
                    data: function(row, type, val) {
                        if (type === 'display') {
                            var html = '';
                            html += '<div class="thumb" style="background-image: url(\''+row.path+'\')">';
                            html += '<a class="launch-tooltip" title="<?php echo t('Click to open in new tab'); ?>" target="_blank" href="' + row.path + '"></a>';
                            html += '</div>';
                            html += '<div class="path"><a target="_blank" href="' + row.path + '">' + row.path + '</a></div>';

                            return html;
                        }

                        return row.path;
                    }
                },
                {
                    width: '100px',
                    data: function(row, type, val) {
                        if (type === 'display') {
                            return '<div class="text-muted">' + row.date + '</div>';
                        }

                        return row.date;
                    }
                },
                {
                    width: '100px',
                    data: function(row, type, val) {
                        if (type === 'display') {
                            return '<div class="text-muted">'+row.size_original + ' <?php echo t('KB'); ?><br>'
                                + '<small class="text-muted">' + row.size_original_human + '</small></div>';
                        }

                        return row.size_original;
                    }
                },
                {
                    width: '100px',
                    data: function(row, type, val) {
                        if (type === 'display') {
                            return '<div class="text-muted">'+row.size_optimized + ' <?php echo t('KB'); ?><br>'
                                + '<small class="text-muted">' + row.size_optimized_human + '</small></div>';
                        }

                        return row.size_optimized;
                    }
                },
                {
                    data: function(row, type, val) {
                        if (type === 'display') {
                            return '<div class="reduction">' + row.size_reduction + ' <?php echo t('KB'); ?><br>'
                                + '<small class="text-muted">' + row.size_reduction_human + '</small></div>';
                        }

                        return row.size_reduction;
                    }
                },
                {
                    width: '100px',
                    data: function(row, type, val) {
                        if (row.skip_reason) {
                            var reason = '';
                            switch (row.skip_reason) {
                                case <?php echo ProcessedFile::SKIP_REASON_PNG_8_BUG ?>:
                                    reason = '<?php echo t("A bug in concrete5 causes issues with PNG-8 images. TinyPNG might return 8-bit PNG images, therefore this file was skipped."); ?>';
                                    break;
                                case <?php echo ProcessedFile::SKIP_REASON_FILE_TOO_BIG ?>:
                                    reason = '<?php echo t("The file was too big to process."); ?>';
                                    break;
                                case <?php echo ProcessedFile::SKIP_REASON_FILE_EXCLUDED ?>:
                                    reason = '<?php echo t("The file was excluded."); ?>';
                                    break;
                                case <?php echo ProcessedFile::SKIP_REASON_EMPTY_FILE ?>:
                                    reason = '<?php echo t("The file was empty or not existing (anymore)."); ?>';
                                    break;
                            }

                            return '<i class="fa fa-info-circle launch-tooltip" title="' + reason + '"></i>';
                        }

                        if (row.size_reduction === 0) {
                            return '<i class="fa fa-info-circle launch-tooltip" ' +
                                'title="<?php echo t("0KB was optimized. This can happen if you ran the optimizers multiple times, if no optimizers have been configured, or because the image was already optimized."); ?>"></i>';
                        }

                        return '<i class="fa fa-check launch-tooltip" title="<?php echo t('All good'); ?>"></i>';
                    }
                },
                {
                    width: '100px',
                    data: function(row, type, val) {
                        return '<a title="<?php echo t('Optimize again next time'); ?>" data-id="'+row.id+'" data-is-original="'+ (row.is_original ? 1 : 0)+'" href="#" class="reset-one launch-tooltip">' +
                            '<i class="fa fa-refresh"></i>' +
                            '</a>';
                    }
                }
            ],
            order: [[ 1, 'desc' ]],
            language: {
                emptyTable: '<?php echo t('No images have been optimized yet. Please go to Automated Jobs to run the Image Optimizer.') ?>'
            },
            drawCallback: function(settings) {
                $('.launch-tooltip').tooltip();
            }
        });

        $('#frm-reset-all-files').on('submit', function() {
            return confirm("<?php
                echo t('Are you sure you want to reset the status of all files?') . ' '
                . t('If so, Image Optimizer will try to optimize the images again.') . ' '
                . t('All statistics will be lost!') . ' '
                . t("You probably only want to do this if you didn't have any optimizers configured before.")
            ?>");
        });

        DataTableElement.on('click', '.reset-one', function() {
            var data = {
                'id': $(this).data('id'),
                'is_original': $(this).data('is-original')
            };

            var row = $(this).closest('tr');
            row.css('opacity', '.5');

            $.post('<?php echo Url::to('/ccm/system/image_optimizer/reset') ?>', data)
                .done(function() {
                    DataTable.row(row).remove();
                })
                .always(function() {
                    DataTable.draw(false);
                });
        });
    });
</script>
