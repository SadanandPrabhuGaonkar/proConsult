<?php defined('C5_EXECUTE') or die("Access Denied.");

$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
$this->inc('includes/banner.php'); 

?>

<?php $a = new Area("Search results area 1"); $a->display($c); ?>

<section class="results">
    <h3>Search</h3>
<div class="search-btn-input">
    <form action="">
        <input type="text" name="keywords" placeholder="Type in your keyword">
            <button>
             Search
            </button>
    </form>
</div>

<div class="results-inner">
    <h3>Results</h3>
    <section class="services common-padding">
   <div class="service-details">
      <img src="/proConsult/application/themes/proConsult/dist/images/Union.png" alt="union" class="uni1">
      <img src="/proConsult/application/themes/proConsult/dist/images/Union.png" alt="union" class="uni2">
      <div class="service-card fadeup" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1; visibility: inherit;">
         <div class="image-card">
            <a class="absul" href="http://localhost/proConsult/index.php/services/procurement-sourcing">Continue Reading</a>                        <img src="http://localhost/proConsult/application/files/3116/7999/9954/Sourcing-and-Procurement-in-SAP_1.jpg" alt="Sourcing-and-Procurement-in-SAP (1).jpg">            
            <div class="content">
               <p>We ensure that the client’s delivery strategy, performance and business terms are properly captured in contract that is realistic, achievable and meet or Exceeds clients businesses qualitative and Quantitative Key Performance Indicators(KPIs)</p>
            </div>
         </div>
         <h4>Procurement &amp; Sourcing</h4>
         <a href="http://localhost/proConsult/index.php/services/procurement-sourcing">Continue Reading</a>        
      </div>
      <div class="service-card fadeup" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1; visibility: inherit;">
         <div class="image-card">
            <a class="absul" href="http://localhost/proConsult/index.php/services/quality-management-systems">Continue Reading</a>                        <img src="http://localhost/proConsult/application/files/4816/7999/9956/Management-Consultancy_1_1.jpg" alt="Management-Consultancy 1 (1).jpg">            
            <div class="content">
               <p>Design and build portions serve to develop the structure of a QMS, its processes, and plans for implementation.</p>
            </div>
         </div>
         <h4>Quality Management Systems</h4>
         <a href="http://localhost/proConsult/index.php/services/quality-management-systems">Continue Reading</a>        
      </div>
      <div class="service-card fadeup" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1; visibility: inherit;">
         <div class="image-card">
            <a class="absul" href="http://localhost/proConsult/index.php/services/project-procurement-management">Continue Reading</a>                        <img src="http://localhost/proConsult/application/files/1116/8000/6667/pexels-lukas-590022_1_1_1_1.jpg" alt="pexels-lukas-590022 1 1 (1) (1).jpg">            
            <div class="content">
               <p>Our methodology, adaptability shall bridge the gaps of completeness to fulfilment of all challenges thereby increasing efficiency, improving productivity, surpassing margins and getting into the best books of your customers.</p>
            </div>
         </div>
         <h4>Project Procurement Management</h4>
         <a href="http://localhost/proConsult/index.php/services/project-procurement-management">Continue Reading</a>        
      </div>
      <div class="service-card fadeup" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1; visibility: inherit;">
         <div class="image-card">
            <a class="absul" href="http://localhost/proConsult/index.php/services/e-transformation">Continue Reading</a>                        <img src="http://localhost/proConsult/application/files/4416/7999/9957/Digital_engeenearing_works_Banner_va2-04_1_1.png" alt="Digital engeenearing works Banner_va2-04 1 (1).png">            
            <div class="content">
               <p>Economic motive to be the primary driver for oganisation to  adopt e-procurements  systems and that motivation for implementation was based on expectations of lower purchase prices, reduced transaction costs, increased speed and efficiency..</p>
            </div>
         </div>
         <h4>E-Transformation</h4>
         <a href="http://localhost/proConsult/index.php/services/e-transformation">Continue Reading</a>        
      </div>
   </div>
</section>
</div>
</section>

<?php $a = new Area("Search results area 2"); $a->display($c); ?>