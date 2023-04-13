<?php $title = $searchResult->getCollectionName();
$url = $searchResult->getCollectionPath();
$desc = $searchResult->getCollectionDescription();
use Application\Concrete\Helpers\ImageHelper;
$ih = new ImageHelper();
$thumbnail = $searchResult->getAttribute('thumbnail_image');

?>
<div class="service-card fadeup" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1; visibility: inherit;">
  <div class="image-card">
    <a class="absul" href="<?php echo $url; ?>">Continue Reading</a> <img src="<?php echo $ih->getThumbnail($thumbnail, 1980, 1980) ?>" alt="<?php echo $title; ?>">
    <div class="content">
      <p><?php echo $desc; ?></p>
    </div>
  </div>
  <h4><?php echo $title; ?></h4>
  <a href="<?php echo $url; ?>">Continue Reading</a>
</div>