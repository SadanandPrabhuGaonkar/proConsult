<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;
?>

<p><?php echo t('Congratulations, %s has been installed!', t('Image Optimizer')); ?></p>
<br>

<p class="alert alert-warning">
    <?php
    echo t('Do you want to use TinyPNG for optimization? If so, go to the %sTinyPNG settings%s page to configure the API-key.',
        '<a href="' . Url::to('/dashboard/files/image_optimizer/settings') . '">',
        '</a>'
    );
    ?>
</p>

<p class="alert alert-warning">
    <?php
    echo t("Do you prefer to run the optimizers on your own server? Make sure you read the %sinstallation instructions%s.",
        '<a href="https://www.concrete5.org/marketplace/addons/image-optimizer/installation/" target="_blank">',
        '</a>'
    );
    ?>
</p>
