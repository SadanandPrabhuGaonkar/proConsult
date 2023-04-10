<?php $title = $searchResult->getCollectionName();
$url = $searchResult->getCollectionPath();
$desc = $searchResult->getCollectionDescription();

?>
<div class="service-card fadeup" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1; visibility: inherit;">
  <div class="image-card">
    <a class="absul" href="<?php echo $url; ?>">Continue Reading</a> <img src="http://localhost/proConsult/application/files/3116/7999/9954/Sourcing-and-Procurement-in-SAP_1.jpg" alt="Sourcing-and-Procurement-in-SAP (1).jpg">
    <div class="content">
      <p><?php echo $desc; ?></p>
    </div>
  </div>
  <h4><?php echo $title; ?></h4>
  <a href="<?php echo $url; ?>">Continue Reading</a>
</div>