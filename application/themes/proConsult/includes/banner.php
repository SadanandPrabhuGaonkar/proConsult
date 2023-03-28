<?php defined('C5_EXECUTE') or die("Access Denied.");
    use Application\Concrete\Helpers\ImageHelper;
    $ih = new ImageHelper();
    $page = Page::getCurrentPage();
    $title = $page->getCollectionName();
    $thumbnail = $page->getAttribute('thumbnail_image');
    $business_type = $page->getAttribute('business_type');
    $thumbnail = $thumbnail ?  $thumbnail->getUrl() : '';
?>

<section class="PageBanner">
    <?php if($thumbnail) { ?>
    <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="banner-image">
    <?php } ?>
    <?php if($business_type) { ?>
    <div class="Typelabel">
        <h3><?php echo $business_type; ?></h3>
    </div>
    <?php } ?>
    <?php if($title) { ?>
    <h1><?php echo $title; ?></h1>
    <?php } ?>
    <?php $stack = Stack::getByName('Breadcrumb Trail'); $stack && $stack->display(); ?>
</section>