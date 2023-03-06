<?php defined('C5_EXECUTE') or die("Access Denied.");
    use Application\Concrete\Helpers\ImageHelper;
    $ih = new ImageHelper();
    $page = Page::getCurrentPage();
    $title = $page->getCollectionName();
    $thumbnail = $page->getAttribute('thumbnail_image');
    $thumbnail = $thumbnail ?  $thumbnail->getUrl() : '';
?>

<section class="PageBanner">
    <?php if($thumbnail) { ?>
    <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="banner-image">
    <?php } ?>
    <div class="Typelabel fadeuplate">
        <h3>Core Business</h3>
    </div>
    <?php if($title) { ?>
    <h1 class="text-title"><?php echo $title; ?></h1>
    <?php } ?>
    <?php $stack = Stack::getByName('Breadcrumb Trail'); $stack && $stack->display(); ?>
</section>