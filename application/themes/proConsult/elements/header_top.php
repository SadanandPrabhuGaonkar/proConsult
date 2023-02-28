<?php defined('C5_EXECUTE') or die("Access Denied.");

/** @var HtmlHelper $htmlHelper */
$htmlHelper = Loader::helper('html');

global $u;

$page = $c;

$pageType = (string) $page->getAttribute('page_type');
if (!$pageType) {
    $pageType = 'default';
}

if (!$bodyClass) {
    $bodyClass = '';
}
$bodyClass .= ' ' . $pageType . '-page';
if (User::isLoggedIn()) {
    $bodyClass .= ' logged-in';
}
if ($page->isEditMode()) {
    $bodyClass .= ' edit-mode';
}
$site = Config::get('concrete.site');
?>
<!DOCTYPE html>
<!--[if lte IE 8]> <html lang="<?php echo Localization::activeLanguage() ?>" class="ie10 ie9 ie8"> <![endif]-->
<!--[if IE 9]> <html lang="<?php echo Localization::activeLanguage() ?>" class="ie10 ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="<?php echo Localization::activeLanguage() ?>"> <!--<![endif]-->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Author -->
    <meta name="author" content="TenTwenty | Webdesign, Webshops & E-marketing | Dubai">

    <!-- Below tag will be used for android mobile browser colors, change it to main logo color of the project -->
    <meta name="theme-color" content="#393939">

    <!-- Meta Tags for Social Media -->
    <meta property="og:site_name" content="<?php echo $site; ?>">
    <meta property="og:image" content="<?php echo BASE_URL . $this->getThemePath(); ?>/images/logo.jpg">
    <meta property="og:title" content="<?php echo $site; ?> | <?php echo $page->getCollectionName(); ?>">
    <meta property="og:description" content="<?php echo $page->getCollectionDescription(); ?>">
    <meta name="twitter:title" content="<?php echo $site; ?> | <?php echo $page->getCollectionName(); ?>">
    <meta name="twitter:image" content="<?php echo BASE_URL . $this->getThemePath(); ?>/images/logo.jpg">
    <meta name="twitter:description" content="<?php echo $page->getCollectionDescription(); ?>">
    <meta name="twitter:card" content="summary_large_image"/>

    <?php
    //print core cms files
    $metaTitle = $c->getAttribute('meta_title');
    $template = $c->getPageTemplateObject();
    $title = $metaTitle ? $metaTitle : ($pageType == 'home' || is_object($template) && $template->getPageTemplateHandle() === 'homepage' ?  $site :  $page->getCollectionName() . ' | '. $site);
    View::element('header_required', [
        'pageTitle' => isset($title) ? $title : '',
        'pageDescription' => isset($pageDescription) ? $pageDescription : $page->getCollectionDescription(),
        'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
    ]);

    //custom css files
    // $this->addHeaderItem($htmlHelper->css('css/all.css'));
    // $this->addHeaderItem($htmlHelper->css('css/style.css'));
    // $this->addHeaderItem($htmlHelper->css('css/print.css'));
    ?>
    <link rel="stylesheet" href="<?php echo $this->getThemePath() . '/dist/css/app.min.css'; ?>">    
    <!-- <link rel="stylesheet" href="<?php echo $this->getThemePath() . '/dist/css/vendors.min.css'; ?>">     -->
    <script>
        if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
            var msViewportStyle = document.createElement('style');
            msViewportStyle.appendChild(
                document.createTextNode(
                    '@-ms-viewport{width:auto!important}'
                )
            );
            document.querySelector('head').appendChild(msViewportStyle);
        }
   
        //set cookie for site
        function setCookie(cname, cvalue) {
            var d = new Date();
            d.setTime(d.getTime() + 2160000000);
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

</head>
<body class="<?php echo $bodyClass; ?>">
    <!-- Site Loader -->
    <div class="init-overlay"></div>
    <div class="site-loader">
        <div class="logo-middle">
            <img src="<?php echo $this->getThemePath(); ?>/dist/images/logo.svg" alt="<?php echo $site; ?>"/>
        </div>
    </div>
    <script>
        if (document.cookie.indexOf("visited=") == -1) {
            setCookie("visited", "1");
            $('.site-loader').show();
        }
        else{
            $('.init-overlay').show();
        }
    </script>
    <!-- Site Loader End -->
    
    <div class="wrapper <?php echo $c->getPageWrapperClass()?>"><!-- opening of wrapper div ends in footer_bottom.php -->

