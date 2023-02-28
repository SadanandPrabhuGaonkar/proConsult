<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var bool $tinyPngEnabled */
/** @var int $maxImageSize */
/** @var int|null $tinyPngMaxOptimizationsPerMonth */
/** @var int $tinyPngNumberOfCompressions */
?>

<?php
if ($tinyPngEnabled) {
    ?>
    <div class="ccm-dashboard-header-buttons">
        <a class="btn btn-success launch-tooltip"
           data-placement="bottom"
            title="<?php echo t('%s runs as an automated job', t('Image Optimizer')); ?>"
            href="<?php echo Url::to('/dashboard/system/optimization/jobs'); ?>">
            <?php echo t("Run Image Optimizer")?>
        </a>
    </div>
    <?php
}
?>

<div class="ccm-dashboard-content-inner">
    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        echo $token->output('a3020.image_optimizer.settings');
        ?>

        <p>
            <?php
            echo t('TinyPNG is a cloud service that can optimize PNG and JPEG images. You can register an account '.
                'on <a href="%s" target="_blank">https://tinypng.com</a> to obtain an API-key.', 'https://tinypng.com');
            ?>
        </p>
        <br>

        <div class="form-group">
            <label>
                <?php
                echo $form->checkbox('tinyPngEnabled', 1, $tinyPngEnabled);
                ?>
                <?php
                echo t('Enable TinyPNG');
                ?>
            </label>
        </div>

        <div class="form-group">
            <?php
            /** @var bool $tinyPngApiKey */
            echo $form->label('tinyPngApiKey', t('TinyPNG API key'));
            echo $form->text('tinyPngApiKey', $tinyPngApiKey);
            ?>
        </div>

        <div class="form-group" style="margin-bottom: 0">
            <label class="control-label launch-tooltip"
               title="<?php echo t('Per month, you can perform %d optimizations for free!', 500) ?>"
               for="tinyPngMaxOptimizationsPerMonth"
            >
                <?php
                echo $form->label('tinyPngMaxOptimizationsPerMonth', t('Maximum number of optimizations per month'));
                ?>
            </label>

            <?php
            echo $form->number('tinyPngMaxOptimizationsPerMonth', $tinyPngMaxOptimizationsPerMonth, [
                'placeholder' => t('Leave empty to not set a maximum'),
                'min' => 0,
                'style'=> 'max-width: 350px',
            ]);
            ?>

            <?php
            if ($tinyPngNumberOfCompressions !== null) {
                echo '<small class="help-block">';

                echo t('Number of compressions this month: %s.', $tinyPngNumberOfCompressions);

                echo '</small>';
            }
            ?>
        </div>

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
