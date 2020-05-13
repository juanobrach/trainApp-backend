<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTlayout {

    static function getNoRecordFound() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/no-record-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry', 'js-support-ticket') . '!
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('No record found', 'js-support-ticket') . '...!
						</span>
					</div>
				</div>
		';
        echo $html;
    }
    static function getNoRecordFoundForAjax() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/no-record-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('No record found ...!', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        return $html;
    }

    static function getPermissionNotGranted() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Access Denied', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('You have no permission to access this page', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getNotStaffMember() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Access Denied', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('User is not allowed to access this page.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getYouAreLoggedIn() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/already-loggedin.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('You are already Logged In.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getStaffMemberDisable() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Access Denied!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('Your account has been disabled, please contact the administrator.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getSystemOffline() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/offline-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Offline', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . jssupportticket::$_config['offline_message'] . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getUserGuest($redirect_url = '') {
        $loginval = JSSTincluder::getJSModel('configuration')->getConfigValue('set_login_link');
        $loginlink = JSSTincluder::getJSModel('configuration')->getConfigValue('login_link');
        $html = '
                <div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-login-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('You are not logged In', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('To access the page, Please login', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-user-login-btn-wrp">';
	                        if($loginval == 2 && $loginlink != ""){
	                            $html .= '<a class="js-ticket-login-btn" href="'.esc_url($loginlink).'" title="Login">' . __('Login', 'js-support-ticket') . '</a>';
	                        }else{
	                            $html .= '<a class="js-ticket-login-btn" href="'.esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'login', 'js_redirecturl'=>$redirect_url))).'" title="Login">' . __('Login', 'js-support-ticket') . '</a>';
	                        }
	                        $is_enable = get_option('users_can_register');/*check to make sure user registration is enabled*/
                            if ($is_enable) {
	                        	$html .= '<a class="js-ticket-register-btn" href="'.esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'userregister', 'js_redirecturl'=>$redirect_url))).'" title="Login">' . __('Register', 'js-support-ticket') . '</a>';
	                        }

                    $html .= '</span>
                    </div>

				</div>
        ';
        echo $html;
    }

    static function getYouAreNotAllowedToViewThisPage() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('User is not allowed to view this Ticket', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getRegistrationDisabled() {
        $html = '
				<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/ban.png"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . __('Sorry!', 'js-support-ticket') . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' . __('Registration has been disabled by admin, please contact the system administrator.', 'js-support-ticket') . '
						</span>
					</div>
				</div>
		';
        echo $html;
    }

    static function getFeedbackMessages($msg_type) {
    	if($msg_type == 2){
    		$img_var = '3.png';
    		$text_var_1 = __('Sorry!', 'js-support-ticket');
    		$text_var_2 = __('You have already given the feedback for this ticket.', 'js-support-ticket');
    	}elseif($msg_type == 3){
    		$img_var = 'no-record-icon.png';
    		$text_var_1 = __('Sorry!', 'js-support-ticket');
    		$text_var_2 = __('Ticket not found...!', 'js-support-ticket');
    	}else{
    		$img_var = 'not-permission-icondd.png';
    		$text_var_1 = __('Sorry!', 'js-support-ticket');
    		$text_var_2 = __('User is not allowed to view this page', 'js-support-ticket');
    	}
    	if($msg_type == 4){
			$html = '
					<div class="js-ticket-error-message-wrapper">
						<div class="js-ticket-message-image-wrapper">
							<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/success.png"/>
						</div>
						<div class="js-ticket-messages-data-wrapper">
							<span class="js-ticket-messages-main-text">
						    	'. __('Thank you so much for your feedback', 'js-support-ticket') .'
							</span>
							<span class="js-ticket-messages-block_text">
						    	'. jssupportticket::$_config['feedback_thanks_message'] .'
							</span>
						</div>
					</div>';
    	}else{
	        $html = '
					<div class="js-ticket-error-message-wrapper">
					<div class="js-ticket-message-image-wrapper">
						<img class="js-ticket-message-image" alt="message image" src="' . jssupportticket::$_pluginpath . 'includes/images/error/'.$img_var.'"/>
					</div>
					<div class="js-ticket-messages-data-wrapper">
						<span class="js-ticket-messages-main-text">
					    	' . $text_var_1 . '
						</span>
						<span class="js-ticket-messages-block_text">
					    	' .$text_var_2. '
						</span>
					</div>
				</div>
			';
		}
        echo $html;
	}

}

?>
