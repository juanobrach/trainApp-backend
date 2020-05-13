<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

if (jssupportticket::$_config['offline'] == 2) {
    JSSTmessage::getMessage();
    // JSSTbreadcrumbs::getBreadcrumbs();
    include_once(jssupportticket::$_path . 'includes/header.php'); ?>

        <div class="js-ticket-login-wrapper">
            <div  class="js-ticket-login">
<?php /*                <div class="login-heading"><?php echo __('Login into your account', 'js-support-ticket'); ?></div> */ ?>
                <?php
                $redirecturl = JSSTrequest::getVar('js_redirecturl','GET', base64_encode(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket','jstlay'=>'controlpanel'))));
                $redirecturl = base64_decode($redirecturl);
                if (!is_user_logged_in()) { // Display WordPress login form:
                    $args = array(
                        'redirect' => $redirecturl,
                        'form_id' => 'loginform-custom',
                        'label_username' => __('Username', 'js-support-ticket'),
                        'label_password' => __('Password', 'js-support-ticket'),
                        'label_remember' => __('keep me login', 'js-support-ticket'),
                        'label_log_in' => __('Login', 'js-support-ticket'),
                        'remember' => true
                    );
                    wp_login_form($args);
                }else{ // user not Staff
                    JSSTlayout::getYouAreLoggedIn();
                }
                ?>
            </div>
        </div>
<?php
} ?>
