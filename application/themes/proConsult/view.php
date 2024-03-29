<?php
defined('C5_EXECUTE') or die("Access Denied.");
$site = Config::get('concrete.site');
$themePath = $this->getThemePath();
$page = \Concrete\Core\Page\Page::getCurrentPage();
$pgtemplate = $page->getPageTemplateHandle();
?>

<?php $this->inc('elements/header.php'); ?>

<main>
<?php if($pgtemplate != "contact") { ?>
    <div class="popup-bg"></div>
    <div class="popup">
        <div class="popup-under">
        <a class="close-popup"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        </svg></a>
        <h3>Get in touch!</h3>
        <p>We’d love to hear from you. Do you have any questions about our serivces?<br>
        E-mail :info@proconsult.co.in or call :+917218386120</p>
        <?php $stack = Stack::getByName('Contact Form'); $stack && $stack->display(); ?>
        </div>
    </div>
    <a class="whatsapp fade-up-anim" id="whatsappBtn">
        Contact me
    </a>
    <?php } ?>
    <div class="search-main">
        <div class="inner">
            <h3>Search</h3>
            <a class="close-search"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M14 1L1.29933 13.7007" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        <path d="M1 1.29883L13.7007 13.9995" stroke="white" stroke-width="2" stroke-linecap="round"/>
        </svg></a>
            <div class="search-btn-input">
            <form method="get" action="<?= View::url('/search-result'); ?>">
                            <input type="text" name="keywords" placeholder="Type in your keyword">
                            <button>
                                Search
                            </button>
            </form>
            </div>
        </div>
    </div>
                <?php
                View::element('system_errors', [
                    'format' => 'block',
                    'error' => isset($error) ? $error : null,
                    'success' => isset($success) ? $success : null,
                    'message' => isset($message) ? $message : null,
                ]);

                echo $innerContent;
                ?>
</main>

<?php $this->inc('elements/footer.php'); ?>
<?php $this->inc('elements/scripts.php'); ?>
