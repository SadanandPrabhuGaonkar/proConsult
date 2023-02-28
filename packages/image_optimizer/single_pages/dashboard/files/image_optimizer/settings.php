<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;

/** @var \Concrete\Core\Form\Service\Form $form */
/** @var int $maxImageSize */
?>

<div class="ccm-dashboard-header-buttons">
    <a class="btn btn-success launch-tooltip"
       data-placement="bottom"
        title="<?php echo t('%s runs as an automated job', t('Image Optimizer')); ?>"
        href="<?php echo Url::to('/dashboard/system/optimization/jobs'); ?>">
        <?php echo t("Run Image Optimizer")?>
    </a>
</div>

<div class="ccm-dashboard-content-inner">
    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        /** @var $token \Concrete\Core\Validation\CSRF\Token */
        echo $token->output('a3020.image_optimizer.settings');
        ?>

        <fieldset>
            <legend><?php echo t('General'); ?></legend>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                        title="<?php echo t('These are the files the user once uploaded to the File Manager.'); ?>"
                >
                    <?php
                    /** @var bool $includeFilemanagerImages*/
                    echo $form->checkbox('includeFilemanagerImages', 1, $includeFilemanagerImages);
                    ?>
                    <?php echo t('Optimize original files'); ?>
                </label>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                   title="<?php echo t("Thumbnail Types are often used in galleries. The images are scaled versions of the original images. You probably want this setting to be enabled. In case no thumbnails are optimized, make sure you re-run the '%s' Automated Job.", t("Fill thumbnail database table")); ?>"
                >
                    <?php
                    /** @var bool $includeThumbnailImages */
                    echo $form->checkbox('includeThumbnailImages', 1, $includeThumbnailImages);
                    ?>
                    <?php echo t('Optimize thumbnail images')?><br>
                </label><br>
                <small class="text-muted">
                    <?php
                    /** @var string $thumbnailImageDirectory **/
                    echo t(/*i18n: %s is a directory */'E.g. from %s', $thumbnailImageDirectory);
                    ?>
                </small>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("This will optimize all images found in the cache directory. This also includes cache images that are created via the getThumbnail function. It's recommend to enable this setting."); ?>"
                >
                    <?php
                    /** @var bool $includeCachedImages */
                    echo $form->checkbox('includeCachedImages', 1, $includeCachedImages);
                    ?>
                    <?php echo t('Optimize images from cache directory')?><br>
                </label><br>
                <small class="text-muted">
                    <?php
                    /** @var string $cacheDirectory **/
                    echo t(/*i18n: %s is a directory */'E.g. from %s', $cacheDirectory);
                    ?>
                </small>
            </div>
        </fieldset>

        <fieldset>
            <legend><?php echo t('Advanced settings'); ?></legend>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("Enable verbose logging. Only use this for debugging purposes."); ?>"
                >
                    <?php
                    /** @var bool $enableLog */
                    echo $form->checkbox('enableLog', 1, $enableLog);
                    ?>
                    <?php
                    echo t('Write output to concrete5 log');
                    ?>
                </label>
            </div>

            <div class="form-group">
                <label class="control-label launch-tooltip"
                   title="<?php echo t("If you run into time-out issues, you may want to decrease the batch size. (how many images are processed in a single request)") ?>"
                   for="batchSize"
                >
                    <?php
                    echo t('Batch size for automated job');
                    ?>
                </label>

                <?php
                /** @var int $batchSize */
                echo $form->number('batchSize', $batchSize, [
                    'placeholder' => t('Default: %s', 5),
                    'min' => 1,
                    'style' => 'max-width: 350px',
                ]);
                ?>
            </div>

            <div class="form-group" style="margin-bottom: 0">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("Set a maximum here if your server can't handle the big images you are trying to optimize") ?>"
                       for="maxImageSize"
                >
                    <?php
                    echo t("Don't optimize images bigger than ... KB");
                    ?>
                </label>

                <?php
                /** @var int|null $maxImageSize */
                echo $form->number('maxImageSize', $maxImageSize, [
                    'placeholder' => t('Leave empty to not set a maximum'),
                    'min' => 1,
                    'style'=> 'max-width: 350px',
                ]);
                ?>
            </div>
        </fieldset>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <?php
                echo $form->submit('submit', t('Save'), [
                    'class' => 'btn-primary pull-right',
                ]);
                ?>
            </div>
        </div>
    </form>
</div>
