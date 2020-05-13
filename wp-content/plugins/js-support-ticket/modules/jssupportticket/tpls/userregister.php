<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

if (jssupportticket::$_config['offline'] == 2) {
    if (!is_user_logged_in()) {
        // check to make sure user registration is enabled
        $is_enable = get_option('users_can_register');
        // only show the registration form if allowed
        if ($is_enable) {
            JSSTmessage::getMessage();
            include_once(jssupportticket::$_path . 'includes/header.php'); ?>

            <div class="js-ticket-add-form-wrapper">
                <?php jsst_show_error_messages();?> <!-- show any error messages after form submission -->
                <form id="jsst_registration_form" class="jsst_form" action="" method="POST">
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Username','js-support-ticket'); ?> <span style="color:red">*</span>
                        </div>
                        <div class="js-ticket-from-field">
                            <input name="jsst_user_login" id="jsst_user_login" class="required js-ticket-form-field-input" type="text"/>
                        </div>
                    </div>
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Email','js-support-ticket'); ?> <span style="color:red">*</span>
                        </div>
                        <div class="js-ticket-from-field">
                           <input name="jsst_user_email" id="jsst_user_email" class="required js-ticket-form-field-input" type="text"/>
                        </div>
                    </div>
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('First Name','js-support-ticket'); ?>
                        </div>
                        <div class="js-ticket-from-field">
                           <input name="jsst_user_first" id="jsst_user_first" class="required js-ticket-form-field-input" type="text"/>
                        </div>
                    </div>
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Last Name','js-support-ticket'); ?>
                        </div>
                        <div class="js-ticket-from-field">
                           <input name="jsst_user_last" id="jsst_user_last" class="required js-ticket-form-field-input" type="text"/>
                        </div>
                    </div>
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Password','js-support-ticket'); ?> <span style="color:red">*</span>
                        </div>
                        <div class="js-ticket-from-field">
                            <input name="jsst_user_pass" id="password" class="required js-ticket-form-field-input" type="password"/>
                        </div>
                    </div>
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Repeat Password','js-support-ticket'); ?> <span style="color:red">*</span>
                        </div>
                        <div class="js-ticket-from-field">
                           <input name="jsst_user_pass_confirm" id="password_again" class="required js-ticket-form-field-input" type="password"/>
                        </div>
                    </div>

                    <?php
                    if(in_array('mailchimp',jssupportticket::$_active_addons)){
                        ?>
                        <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                            <div class="js-ticket-from-field">
                                <label class="js-ticket-subscribe">
                                    <input name="jsst_mailchimp_subscribe" id="jsst_mailchimp_subscribe" value="1" class="" type="checkbox"/>
                                    <?php echo __('Subscribe to the newsletter','js-support-ticket'); ?>
                                </label>
                            </div>
                        </div>
                        <?php
                    } 
                    ?>

                    <?php
                        if ( in_array('useroptions',jssupportticket::$_active_addons) && jssupportticket::$_config['captcha_on_registration'] == 1) { ?>
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Captcha', 'js-support-ticket'); ?>
                                </div>
                                <div class="js-ticket-from-field">
                                   <?php
                                    if (jssupportticket::$_config['captcha_selection'] == 1) { // Google recaptcha
                                        $error = null;
                                        echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
                                        echo '<div class="g-recaptcha" data-sitekey="'.jssupportticket::$_config['recaptcha_publickey'].'"></div>';
                                    } else { // own captcha
                                        $captcha = new JSSTcaptcha;
                                        echo $captcha->getCaptchaForForm();
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php }
                        JSSTincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3);
                           foreach (jssupportticket::$_data['fieldordering'] as $field) {
                               echo JSSTincluder::getObjectClass('customfields')->formCustomFields($field);
                           ?>
                       <?php } ?>
                        <input type="hidden" name="jsst_support_register_nonce" value="<?php echo wp_create_nonce('jsst-support-register-nonce'); ?>"/>
                        <div class="js-ticket-form-btn-wrp">
                            <?php echo JSSTformfield::submitbutton('save', __('Register', 'js-support-ticket'), array('class' => 'js-ticket-save-button')); ?>
                           <a href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel'));?>" class="js-ticket-cancel-button"><?php echo __('Cancel','js-support-ticket'); ?></a>
                        </div>
                </form>
            </div>
        <?php
        } else {
            JSSTlayout::getRegistrationDisabled();
        }
    }else{
            JSSTlayout::getYouAreLoggedIn();
    }
}
if(isset($google_recaptcha) && $google_recaptcha){ ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
}
?>
