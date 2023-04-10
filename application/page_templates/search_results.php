<?php defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Core\View\View;
$th           = Loader::helper('text');

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
$this->inc('includes/banner.php'); 
$page = Page::getCurrentPage();
$keywords     = $th->entities($th->sanitize($_GET['keywords']));

?>

<?php $a = new Area("Search results area 1"); $a->display($c); ?>

<section class="results">
    <h3>Search</h3>
<div class="search-btn-input">
    <form>
    <input id="keywords" type="text" name="query" value="<?php echo $keywords;?>" placeholder="<?php echo t('What are you looking for?'); ?> "  autocomplete="off" >
            <button> Search
            </button>
    </form>
</div>

<div class="results-inner">
    <h3>Results</h3>
    <section class="services common-padding">
   
    <div class="service-details search--list">
    <img src="/proConsult/application/themes/proConsult/dist/images/Union.png" alt="union" class="uni1">
    <img src="/proConsult/application/themes/proConsult/dist/images/Union.png" alt="union" class="uni2">
      
      <?php foreach ($searchResults as $searchResult) {
              View::element('search', ['searchResult' => $searchResult, 'ih' => $ih, 'themePath' => $themePath]);
        } ?>
        <?php if (!$searchResults) { ?>
          <h4 class="" style="margin-top:32px"><?php echo t('There were no results found. Please try another keyword or phrase.')?></h4>
        <?php } ?>
    
   </div>
</section>
</div>
</section>

<?php $a = new Area("Search results area 2"); $a->display($c); ?>