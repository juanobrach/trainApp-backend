<?php
if(in_array('notification', jssupportticket::$_active_addons)){
    wp_enqueue_script( 'ticket-notify-app', 'https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js' );
    wp_enqueue_script( 'ticket-notify-message', 'https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js' );
}
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
JSSTmessage::getMessage();
if(in_array('notification', jssupportticket::$_active_addons)){
    if(jssupportticket::$_data[0]['apiKey_firebase'] != "" && jssupportticket::$_data[0]['databaseURL_firebase'] != "" && jssupportticket::$_data[0]['authDomain_firebase'] != "" && jssupportticket::$_data[0]['projectId_firebase'] != "" && jssupportticket::$_data[0]['storageBucket_firebase'] != "" && jssupportticket::$_data[0]['messagingSenderId_firebase'] != "" && jssupportticket::$_data[0]['server_key_firebase'] != ""){
        do_action('ticket-notify-generate-token');
    }
}

?>
<script>
    jQuery(document).ready(function () {

        jQuery('ul.jsst_tabs li').click(function(){
            var tab_id = jQuery(this).attr('data-jsst-tab');

            jQuery('ul.jsst_tabs li').removeClass('jsst_current_tab');
            jQuery('.jsst_tab_content').removeClass('jsst_current_tab');

            jQuery(this).addClass('jsst_current_tab');
            jQuery("#"+tab_id).addClass('jsst_current_tab');
        });

        jQuery('select#ticket_overdue_type').change(function(){
            var isselect = jQuery('select#ticket_overdue_type').val();
            if(isselect == 1){
                jQuery('span.ticket_overdue_type_text').html("<?php echo __('Days', 'js-support-ticket');?>");
            }else{
                jQuery('span.ticket_overdue_type_text').html("<?php echo __('Hours', 'js-support-ticket');?>");
            }
        });
    });
    function showhidehostname(value){
        if(value == 4){
            jQuery("div#tve_hostname").show();
        }else{
            jQuery("div#tve_hostname").hide();
        }
    }

    jQuery(document).ready(function () {
        jQuery('select#set_login_link').change(function(){
           var value = jQuery(this).val();
           if (value == 2)
            {
               jQuery('.loginlink_field').attr('style','display: block');
            }else
            {
                jQuery('.loginlink_field').attr('style','display: none');
            }

            })

           var value = jQuery('select#set_login_link').val();
           if (value == 2)
            {
               jQuery('.loginlink_field').attr('style','display: block');
            }else
            {
                jQuery('.loginlink_field').attr('style','display: none');
            }

            });

</script>

<?php
$captchaselection = array(
    (object) array('id' => '1', 'text' => __('Google Recaptcha', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Own Captcha', 'js-support-ticket'))
);
$owncaptchaoparend = array(
    (object) array('id' => '2', 'text' => '2'),
    (object) array('id' => '3', 'text' => '3')
);
$owncaptchatype = array(
    (object) array('id' => '0', 'text' => __('Any', 'js-support-ticket')),
    (object) array('id' => '1', 'text' => __('Addition', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Subtraction', 'js-support-ticket'))
);
$yesno = array(
    (object) array('id' => '1', 'text' => __('Yes', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('No', 'js-support-ticket'))
);
$showhide = array(
    (object) array('id' => '1', 'text' => __('Show', 'js-support-ticket')),
    (object) array('id' => '0', 'text' => __('Hide', 'js-support-ticket'))
);
$defaultcustom = array(
    (object) array('id' => '1', 'text' => __('Default', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Custom', 'js-support-ticket'))
);
$screentagposition = array(
    (object) array('id' => '1', 'text' => __('Top left', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Top right', 'js-support-ticket')),
    (object) array('id' => '3', 'text' => __('Middle left', 'js-support-ticket')),
    (object) array('id' => '4', 'text' => __('Middle right', 'js-support-ticket')),
    (object) array('id' => '5', 'text' => __('Bottom left', 'js-support-ticket')),
    (object) array('id' => '6', 'text' => __('Bottom right', 'js-support-ticket'))
);
$enableddisabled = array(
    (object) array('id' => '1', 'text' => __('Enabled', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Disabled', 'js-support-ticket'))
);
$mailreadtype = array(
    (object) array('id' => '1', 'text' => __('Only New Tickets', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Only Replies', 'js-support-ticket')),
    (object) array('id' => '3', 'text' => __('Both', 'js-support-ticket'))
);

$sequence = array(
    (object) array('id' => '1', 'text' => __('Random', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Sequence', 'js-support-ticket'))
);

$hosttype = array(
    (object) array('id' => '1', 'text' => __('Gmail', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Yahoo', 'js-support-ticket')),
    (object) array('id' => '3', 'text' => __('Aol', 'js-support-ticket')),
    (object) array('id' => '4', 'text' => __('Other', 'js-support-ticket'))
);

$ticketordering = array(
    (object) array('id' => '1', 'text' => __('Default', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Created', 'js-support-ticket'))
);
$ticketsorting = array(
    (object) array('id' => '1', 'text' => __('Ascending', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Descending', 'js-support-ticket'))
);
// wp roles combo for new user
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
?>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">
        <div id="jsstadmin-wrapper-top">
            <div id="jsstadmin-wrapper-top-left">
                <div id="jsstadmin-breadcrunbs">
                    <ul>
                        <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
                        <li><?php echo __('Configurations','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jsstadmin-wrapper-top-right">
                <div id="jsstadmin-config-btn">
                    <a title="<?php echo __('Configuration','js-support-ticket'); ?>" href="<?php echo admin_url("admin.php?page=configuration"); ?>">
                        <img alt="<?php echo __('Configuration','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/config.png" />
                    </a>
                </div>
                <div id="jsstadmin-vers-txt">
                    <?php echo __("Version",'js-support-ticket'); ?>:
                    <span class="jsstadmin-ver"><?php echo JSSTincluder::getJSModel('configuration')->getConfigValue('versioncode'); ?></span>
                </div>
            </div>
        </div>
        <div id="jsstadmin-head">
            <h1 class="jsstadmin-head-text"><?php echo __("Configurations", 'js-support-ticket') ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <form method="post" class="js-support-ticket-configurations" action="<?php echo admin_url("?page=configuration&task=saveconfiguration"); ?>" enctype="multipart/form-data">
                <div id="tabs" class="tabs">
                    <ul class="jsst_tabs" >
                        <li class="tab-link jsst_current_tab" data-jsst-tab="general" ><a><?php echo __('General Settings', 'js-support-ticket') ?></a></li>
                        <li class="tab-link" data-jsst-tab="ticketsettig" ><a><?php echo __('Ticket Settings', 'js-support-ticket') ?></a></li>
                        <li class="tab-link" data-jsst-tab="defaultemail" ><a><?php echo __('System Emails', 'js-support-ticket') ?></a></li>
                        <?php /*
                          <li class="tab-link" data-jsst-tab="php" ><ap echo __('Agent','js-support-ticket') ?></a></li>
                          <li class="tab-link" data-jsst-tab="php" ><ap echo __('Knowledge Base','js-support-ticket') ?></a></li>
                         *
                         */ ?>
                        <li class="tab-link" data-jsst-tab="mailsetting" ><a><?php echo __('Email Settings', 'js-support-ticket'); ?></a></li>

                        <?php if(in_array('agent', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="staffmenusetting" ><a><?php echo __('Agent Menu', 'js-support-ticket'); ?></a></li>
                        <?php } ?>

                        <li class="tab-link" data-jsst-tab="usermenusetting" ><a><?php echo __('User Menu', 'js-support-ticket'); ?></a></li>

                        <?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="feedback" ><a><?php echo __('Feedback', 'js-support-ticket'); ?></a></li>
                        <?php } ?>

                        <?php if(in_array('emailpiping', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="ticketviaemail" ><a><?php echo __('Email Piping', 'js-support-ticket'); ?></a></li>
                        <?php } ?>
                        <?php if(in_array('notification', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="pushnotification" ><a><?php echo __('Push Notifications', 'js-support-ticket'); ?></a></li>
                        <?php } ?>
                        <?php if(in_array('privatecredentials', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="privatecredentials" ><a><?php echo __('Private Credentials', 'js-support-ticket'); ?></a></li>
                        <?php } ?>
                        <?php if(in_array('envatovalidation', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="envatovalidation" ><a><?php echo __('Envato Validation', 'js-support-ticket'); ?></a></li>
                        <?php } ?>
                        <?php if(in_array('mailchimp', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="mailchimp" ><a><?php echo __('MailChimp', 'js-support-ticket'); ?></a></li>
                        <?php } ?>
                        <?php if(in_array('easydigitaldownloads', jssupportticket::$_active_addons)){ ?>
                            <li class="tab-link" data-jsst-tab="easydigitaldownloads" ><a><?php echo __('Easy Digital Downloads', 'js-support-ticket'); ?></a></li>
                        <?php } ?>

                    </ul>
                    <div class="tabInner">
                        <div id="general" class="jsst_tab_content jsst_current_tab">
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('General Settings', 'js-support-ticket') ?></h3>
                                <?php

                                if(isset(jssupportticket::$_data[0]['title'])){
                                    $title = __('Title', 'js-support-ticket');
                                    $field = JSSTformfield::text('title', jssupportticket::$_data[0]['title'], array('class' => 'inputbox'));
                                    $description =  __('Set the heading of your plugin', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['default_pageid'])){
                                    $title = __('Ticket Default Page', 'js-support-ticket');
                                    $field = JSSTformfield::select('default_pageid', JSSTincluder::getJSModel('configuration')->getPageList(), jssupportticket::$_data[0]['default_pageid'], __('Select Page', 'js-support-ticket'), array('class' => 'inputbox', 'data-validation' => 'required'));
                                    $description =  __('Select JS Help Desk default page, on the action system, will redirect on the selected page. If not select the default page, email links, and support icon might not work.', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['support_screentag'])){
                                    $title = __('Support Icon', 'js-support-ticket');
                                    $field = JSSTformfield::select('support_screentag', $showhide, jssupportticket::$_data[0]['support_screentag'], __('Screen Tag', 'js-support-ticket'), array('class' => 'inputbox', 'data-validation' => 'required'));
                                    $description =  __('Enable / disable your support icon', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['screentag_position'])){
                                    $title = __('Support Icon Position', 'js-support-ticket');
                                    $field = JSSTformfield::select('screentag_position', $screentagposition, jssupportticket::$_data[0]['screentag_position'], __('Screen Tag Position', 'js-support-ticket'), array('class' => 'inputbox', 'data-validation' => 'required'));
                                    $description =  __('Select position for your support icon', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['offline'])){
                                    $title = __('Offline', 'js-support-ticket');
                                    $field = JSSTformfield::select('offline', array((object) array('id' => '1', 'text' => __('Offline', 'js-support-ticket')), (object) array('id' => '2', 'text' => __('Online', 'js-support-ticket'))), jssupportticket::$_data[0]['offline']);
                                    $description =  __('Set your plugin offline for front end', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['offline_message'])){
                                    $title = __('Offline Message', 'js-support-ticket');
                                    $field = wp_editor(jssupportticket::$_data[0]['offline_message'], 'offline_message', array('media_buttons' => false));
                                    $description =  __('Set the offline message for your user', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['data_directory'])){
                                    $title = __('Data Directory', 'js-support-ticket');
                                    $field = JSSTformfield::text('data_directory', jssupportticket::$_data[0]['data_directory'], array('class' => 'inputbox'));
                                    $description =  __('Set the name for your data directory', 'js-support-ticket') .'<br>' . __('You need to rename the existing data directory in the file system before changing the data directory name', 'js-support-ticket') ; ?><?php //echo __('You need to rename the existing data directory in file system before changing the data directory name', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['date_format'])){
                                    $title = __('Date Format', 'js-support-ticket');
                                    $field = JSSTformfield::select('date_format', array((object) array('id' => 'd-m-Y', 'text' => __("DD-MM-YYYY", 'js-support-ticket')), (object) array('id' => 'm-d-Y', 'text' => __("MM-DD-YYYY", 'js-support-ticket')), (object) array('id' => 'Y-m-d', 'text' => __("YYYY-MM-DD", 'js-support-ticket'))), jssupportticket::$_data[0]['date_format']);
                                    $description =  __('Set the default date format', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                /*
                                if(isset(jssupportticket::$_data[0]['ticket_overdue_type'])){
                                    $title = __('Ticket Overdue Interval Type', 'js-support-ticket');
                                    $field = JSSTformfield::select('ticket_overdue_type', array((object) array('id' => '1', 'text' => __('Days', 'js-support-ticket')), (object) array('id' => '2', 'text' => __('Hours', 'js-support-ticket'))), jssupportticket::$_data[0]['ticket_overdue_type']);
                                    $description =  __('Interval type to mark ticket as overdue', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['ticket_overdue'])){
                                    $title = __('Ticket Overdue', 'js-support-ticket');
                                    $field = JSSTformfield::text('ticket_overdue', jssupportticket::$_data[0]['ticket_overdue'], array('class' => 'inputbox')) ?><span class="ticket_overdue_type_text" ><?php echo jssupportticket::$_data[0]['ticket_overdue_type'] == 1 ? __('Days', 'js-support-ticket'): __('Hours', 'js-support-ticket');
                                    $description =  __('Set no. of days or hours to mark ticket as overdue', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                */

                                if(isset(jssupportticket::$_data[0]['ticket_auto_close'])){
                                    $title = __('Ticket auto close', 'js-support-ticket');
                                    $field = JSSTformfield::text('ticket_auto_close', jssupportticket::$_data[0]['ticket_auto_close'], array('class' => 'inputbox'));
                                    $description = '<span class="js-ticket-configuration-sml-txt">'. __('Days','js-support-ticket').'</span>' . __('Ticket auto-close if user does not respond within given days', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['no_of_attachement'])){
                                    $title = __('No. of attachment', 'js-support-ticket');
                                    $field = JSSTformfield::text('no_of_attachement', jssupportticket::$_data[0]['no_of_attachement'], array('class' => 'inputbox'));
                                    $description =  __('No. of attachment allowed at a time', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['file_maximum_size'])){
                                    $title = __('File maximum size', 'js-support-ticket');
                                    $field = JSSTformfield::text('file_maximum_size', jssupportticket::$_data[0]['file_maximum_size'], array('class' => 'inputbox')) ?><?php //echo __('Kb', 'js-support-ticket');
                                    $description =  __('Kb', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field,$description);
                                }

                                if(isset(jssupportticket::$_data[0]['file_extension'])){
                                    $title = __('File extension', 'js-support-ticket');
                                    $field = JSSTformfield::textarea('file_extension', jssupportticket::$_data[0]['file_extension'], array('class' => 'inputbox'));
                                    $description =  __('File extension allowed to attach', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['pagination_default_page_size'])){
                                    $title = __('Pagination default page size', 'js-support-ticket');
                                    $field = JSSTformfield::text('pagination_default_page_size', jssupportticket::$_data[0]['pagination_default_page_size'], array('class' => 'inputbox'));
                                    $description =  __('Set the no. of record per page', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['show_breadcrumbs'])){
                                    $title = __('Breadcrumbs', 'js-support-ticket');
                                    $field = JSSTformfield::select('show_breadcrumbs', $showhide, jssupportticket::$_data[0]['show_breadcrumbs']);
                                    $description =  __('Show hide breadcrumbs', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['show_header'])){
                                    $title = __('Top Header', 'js-support-ticket');
                                    $field = JSSTformfield::select('show_header', $showhide, jssupportticket::$_data[0]['show_header']);
                                    $description =  __('Show hide Top Header', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['login_redirect'])){
                                    $title = __('Login redirect', 'js-support-ticket');
                                    $field = JSSTformfield::select('login_redirect', $yesno, jssupportticket::$_data[0]['login_redirect']);
                                    $description =  __('Redirect user to JS Help Desk control panel after log in', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['count_on_myticket'])){
                                    $title = __('Show count on my tickets', 'js-support-ticket');
                                    $field = JSSTformfield::select('count_on_myticket', $yesno, jssupportticket::$_data[0]['count_on_myticket']);
                                    $description =  __('Show number of the open, closed, answered ticket in my ticket', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['captcha_on_registration'])){
                                    $title = __('Show captcha on registration form', 'js-support-ticket');
                                    $field = JSSTformfield::select('captcha_on_registration', $yesno, jssupportticket::$_data[0]['captcha_on_registration']);
                                    $description =  __('Select whether you want to show captcha on the registration form or not', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['wp_default_role'])){
                                    $title = __('Default wp role for new users', 'js-support-ticket');
                                    $field = JSSTformfield::select('wp_default_role', $userroles, jssupportticket::$_data[0]['wp_default_role']);
                                    $description =  __('Select the role you want to assign to new users', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['set_login_link'])){
                                    $title = __('Set Login Link', 'js-support-ticket');
                                    $field = JSSTformfield::select('set_login_link', $defaultcustom, jssupportticket::$_data[0]['set_login_link']);
                                    $description =  __('Set Login Link Default or Custom', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['login_link'])){
                                    $title ="&nbsp";
                                    $field = JSSTformfield::text('login_link', jssupportticket::$_data[0]['login_link'], array('class' => 'inputbox loginlink_field'));
                                    printConfigFieldSingle($title, $field);
                                }
                                    ?>

                        </div>
                        <div id="ticketsettig" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('Ticket Settings', 'js-support-ticket') ?></h3>
                                <?php
                                if(isset(jssupportticket::$_data[0]['ticketid_sequence'])){
                                    $title = __('Ticketid sequence', 'js-support-ticket');
                                    $field = JSSTformfield::select('ticketid_sequence', $sequence, jssupportticket::$_data[0]['ticketid_sequence']);
                                    $description =  __('Set ticketid sequential or random', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['tickets_ordering'])){
                                    $title = __('Ticket listing ordering', 'js-support-ticket');
                                    $field = JSSTformfield::select('tickets_ordering', $ticketordering, jssupportticket::$_data[0]['tickets_ordering']);
                                    $description =  __('Set default ordering for ticket listing', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['tickets_sorting'])){
                                    $title = __('Ticket listing sorting', 'js-support-ticket');
                                    $field = JSSTformfield::select('tickets_sorting', $ticketsorting, jssupportticket::$_data[0]['tickets_sorting']);
                                    $description =  __('Set default sorting for ticket listing', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['maximum_tickets'])){
                                    $title = __('Maximum tickets', 'js-support-ticket');
                                    $field = JSSTformfield::text('maximum_tickets', jssupportticket::$_data[0]['maximum_tickets'], array('class' => 'inputbox'));
                                    $description =  __('Maximum ticket per user', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['maximum_open_tickets'])){
                                    $title = __('Maximum open tickets', 'js-support-ticket');
                                    $field = JSSTformfield::text('maximum_open_tickets', jssupportticket::$_data[0]['maximum_open_tickets'], array('class' => 'inputbox'));
                                    $description =  __('Maximum opened tickets per user', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['reopen_ticket_within_days'])){
                                    $title = __('Reopen ticket within days', 'js-support-ticket');
                                    $field = JSSTformfield::text('reopen_ticket_within_days', jssupportticket::$_data[0]['reopen_ticket_within_days'], array('class' => 'inputbox'));
                                    $description =  __('The ticket can be reopened within a given number of days', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['print_ticket_user'])){
                                    $title = __('User can print ticket', 'js-support-ticket');
                                    $field = JSSTformfield::select('print_ticket_user', $yesno, jssupportticket::$_data[0]['print_ticket_user']);
                                    $description =  __('Can user print ticket from ticket detail or not', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['show_captcha_on_visitor_from_ticket'])){
                                    $title = __('Show captcha on the visitor ticket form', 'js-support-ticket');
                                    $field = JSSTformfield::select('show_captcha_on_visitor_from_ticket', $yesno, jssupportticket::$_data[0]['show_captcha_on_visitor_from_ticket']);
                                    $description =  __('Show captcha when a visitor wants to create a ticket', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['captcha_selection'])){
                                    $title = __('Captcha selection', 'js-support-ticket');
                                    $field = JSSTformfield::select('captcha_selection', $captchaselection, jssupportticket::$_data[0]['captcha_selection']);
                                    $description =  __('Which captcha you want to add', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['recaptcha_publickey'])){
                                    $title = __('Google recaptcha public key', 'js-support-ticket');
                                    $field = JSSTformfield::text('recaptcha_publickey', jssupportticket::$_data[0]['recaptcha_publickey'], array('class' => 'inputbox'));
                                    $description =  __('Please enter the google re-captcha public key from','js-support-ticket').' https://www.google.com/recaptcha/admin ';
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['recaptcha_privatekey'])){
                                    $title = __('Google recaptcha private key', 'js-support-ticket');
                                    $field = JSSTformfield::text('recaptcha_privatekey', jssupportticket::$_data[0]['recaptcha_privatekey'], array('class' => 'inputbox'));
                                    $description =  __('Please enter the google re-captcha private key from','js-support-ticket').' https://www.google.com/recaptcha/admin ';
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['owncaptcha_calculationtype'])){
                                    $title = __('Own captcha calculation type', 'js-support-ticket');
                                    $field = JSSTformfield::select('owncaptcha_calculationtype', $owncaptchatype, jssupportticket::$_data[0]['owncaptcha_calculationtype']);
                                    $description =  __('Select calculation type addition or subtraction', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['owncaptcha_totaloperand'])){
                                    $title = __('Own captcha operands', 'js-support-ticket');
                                    $field = JSSTformfield::select('owncaptcha_totaloperand', $owncaptchaoparend, jssupportticket::$_data[0]['owncaptcha_totaloperand']);
                                    $description =  __('Select the total operands to be given', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['owncaptcha_subtractionans'])){
                                    $title = __('Own captcha subtraction answer positive', 'js-support-ticket');
                                    $field = JSSTformfield::select('owncaptcha_subtractionans', $yesno, jssupportticket::$_data[0]['owncaptcha_subtractionans']);
                                    $description =  __('Is subtraction answer should be positive', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['new_ticket_message'])){
                                    $title = __('New ticket message', 'js-support-ticket');
                                    $field = wp_editor(jssupportticket::$_data[0]['new_ticket_message'], 'new_ticket_message');
                                    $description =  __('This message will show on the new ticket', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['reply_to_closed_ticket'])){
                                    $title = __('Allow Users To Reply via Email On Closed Ticket', 'js-support-ticket');
                                    $field = JSSTformfield::select('reply_to_closed_ticket', $yesno, jssupportticket::$_data[0]['reply_to_closed_ticket']);
                                    $description =  __('Select whether users can reply to closed email piping ticket or not','js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['visitor_can_create_ticket'])){
                                    $title = __('Visitor can create ticket', 'js-support-ticket');
                                    $field = JSSTformfield::select('visitor_can_create_ticket', $yesno, jssupportticket::$_data[0]['visitor_can_create_ticket']);
                                    $description =  __('Can visitor create ticket or not', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['visitor_message'])){
                                    $title = __('Visitor ticket creation message', 'js-support-ticket');
                                    $field = wp_editor(jssupportticket::$_data[0]['visitor_message'], 'visitor_message');
                                    $description =  __('This text will appear whenever a visitor creates a ticket', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                    ?>
                        </div>
                        <div id="defaultemail" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('System Emails', 'js-support-ticket') ?></h3>
                                <?php

                                if(isset(jssupportticket::$_data[0]['default_alert_email'])){
                                    $title = __('Default alert email', 'js-support-ticket');
                                    $field = JSSTformfield::select('default_alert_email', jssupportticket::$_data[1], jssupportticket::$_data[0]['default_alert_email']);
                                    $description = __('If ticket department email is not selected then this email is used to send emails', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['default_admin_email'])){
                                    $title = __('Default admin email', 'js-support-ticket');
                                    $field = JSSTformfield::select('default_admin_email', jssupportticket::$_data[1], jssupportticket::$_data[0]['default_admin_email']);
                                    $description = __('Admin email address to receive emails', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                ?>
                                <div class="js-ticket-configuration-row">
                                    <div class="js-ticket-configuration-title">
                                        <?php echo __('Add New Email','js-support-ticket'); ?>
                                        <a href="?page=email&jstlay=addemail" class="js-ticket-configuration-link"><?php echo __('Add New Email','js-support-ticket'); ?></a>
                                    </div>
                                </div>
                        </div>
                        <div id="mailsetting" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('Ban Email New Ticket', 'js-support-ticket') ?></h3>
                                <?php

                                if(isset(jssupportticket::$_data[0]['banemail_mail_to_admin'])){
                                    $title = __('Mail to admin', 'js-support-ticket');
                                    $field = JSSTformfield::select('banemail_mail_to_admin', $enableddisabled, jssupportticket::$_data[0]['banemail_mail_to_admin']);;
                                    $description = __('Email sends to admin when banned email try to create a ticket', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                ?>
                                <h3 class="js-ticket-configuration-heading-main"><?php echo __('Ticket Operations Email Setting', 'js-support-ticket') ?></h3>
                                <div class="js-ticket-configuration-row-mail">
                                    <div class="js-ticket-conf-text-sub"><?php echo __('Admin', 'js-support-ticket') ?></div>
                                    <?php if(in_array('agent', jssupportticket::$_active_addons)){ ?>
                                        <div class="js-ticket-conf-text-sub"><?php echo __('Agent', 'js-support-ticket') ?></div>
                                    <?php }else{ ?>
                                        <div class="js-ticket-conf-text-sub">------</div>
                                    <?php } ?>

                                    <div class="js-ticket-conf-text-sub"><?php echo __('User', 'js-support-ticket') ?></div>
                                </div>
                                    <?php

                                    if(isset(jssupportticket::$_data[0]['new_ticket_mail_to_admin'])){
                                        $title = __('New ticket', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('new_ticket_mail_to_admin', $enableddisabled, jssupportticket::$_data[0]['new_ticket_mail_to_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('new_ticket_mail_to_staff_members', $enableddisabled, jssupportticket::$_data[0]['new_ticket_mail_to_staff_members']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }
                                    if(isset(jssupportticket::$_data[0]['ticket_reassign_admin'])){
                                        $title = __('Ticket reassign', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_reassign_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_reassign_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_reassign_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_reassign_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_reassign_user', $enableddisabled, jssupportticket::$_data[0]['ticket_reassign_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_close_admin'])){
                                        $title = __('Ticket close', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_close_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_close_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_close_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_close_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_close_user', $enableddisabled, jssupportticket::$_data[0]['ticket_close_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_delete_admin'])){
                                        $title = __('Ticket delete', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_delete_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_delete_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_delete_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_delete_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_delete_user', $enableddisabled, jssupportticket::$_data[0]['ticket_delete_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_mark_overdue_admin'])){
                                        $title = __('The ticket marks as overdue', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_mark_overdue_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_mark_overdue_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_mark_overdue_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_mark_overdue_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_mark_overdue_user', $enableddisabled, jssupportticket::$_data[0]['ticket_mark_overdue_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_ban_email_admin'])){
                                        $title = __('Ticket ban email', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_ban_email_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_ban_email_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_ban_email_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_ban_email_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_ban_email_user', $enableddisabled, jssupportticket::$_data[0]['ticket_ban_email_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_department_transfer_admin'])){
                                        $title = __('Ticket department transfer', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_department_transfer_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_department_transfer_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_department_transfer_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_department_transfer_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_department_transfer_user', $enableddisabled, jssupportticket::$_data[0]['ticket_department_transfer_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_reply_ticket_user_admin'])){
                                        $title = __('Ticket reply User', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_reply_ticket_user_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_reply_ticket_user_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_reply_ticket_user_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_reply_ticket_user_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_reply_ticket_user_user', $enableddisabled, jssupportticket::$_data[0]['ticket_reply_ticket_user_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_response_to_staff_admin'])){
                                        $title = __('Ticket Response Agent', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_response_to_staff_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_response_to_staff_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_response_to_staff_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_response_to_staff_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_response_to_staff_user', $enableddisabled, jssupportticket::$_data[0]['ticket_response_to_staff_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticker_ban_eamil_and_close_ticktet_admin'])){
                                        $title = __('Ticket ban email and close ticket', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticker_ban_eamil_and_close_ticktet_admin', $enableddisabled, jssupportticket::$_data[0]['ticker_ban_eamil_and_close_ticktet_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticker_ban_eamil_and_close_ticktet_staff', $enableddisabled, jssupportticket::$_data[0]['ticker_ban_eamil_and_close_ticktet_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticker_ban_eamil_and_close_ticktet_user', $enableddisabled, jssupportticket::$_data[0]['ticker_ban_eamil_and_close_ticktet_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['unban_email_admin'])){
                                        $title = __('Ticket unban email', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('unban_email_admin', $enableddisabled, jssupportticket::$_data[0]['unban_email_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('unban_email_staff', $enableddisabled, jssupportticket::$_data[0]['unban_email_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('unban_email_user', $enableddisabled, jssupportticket::$_data[0]['unban_email_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_lock_admin'])){
                                        $title = __('Ticket lock', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_lock_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_lock_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_lock_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_lock_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_lock_user', $enableddisabled, jssupportticket::$_data[0]['ticket_lock_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_unlock_admin'])){
                                        $title = __('Ticket unlock', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_unlock_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_unlock_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_unlock_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_unlock_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_unlock_user', $enableddisabled, jssupportticket::$_data[0]['ticket_unlock_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_priority_admin'])){
                                        $title = __('Ticket Change Priority', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_priority_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_priority_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_priority_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_priority_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_priority_user', $enableddisabled, jssupportticket::$_data[0]['ticket_priority_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_mark_progress_admin'])){
                                        $title = __('Mark Ticket In Progress', 'js-support-ticket');
                                        $field1 = JSSTformfield::select('ticket_mark_progress_admin', $enableddisabled, jssupportticket::$_data[0]['ticket_mark_progress_admin']);
                                        if(in_array('agent', jssupportticket::$_active_addons)){
                                            $field2 = JSSTformfield::select('ticket_mark_progress_staff', $enableddisabled, jssupportticket::$_data[0]['ticket_mark_progress_staff']);
                                        }else{
                                            $field2 = '<span class="js-ticket-configuration-no-rec">'.'------'.'</span>';
                                        }
                                        $field3 = JSSTformfield::select('ticket_mark_progress_user', $enableddisabled, jssupportticket::$_data[0]['ticket_mark_progress_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_reply_closed_ticket_user'])){
                                        $title = __('Reply To A Closed Ticket By Email', 'js-support-ticket');
                                        $field1 = '<span class="js-ticket-configuration-no-rec">'.'----'.'</span>';
                                        $field2 = '<span class="js-ticket-configuration-no-rec">'.'----'.'</span>';
                                        $field3 =  JSSTformfield::select('ticket_reply_closed_ticket_user', $enableddisabled, jssupportticket::$_data[0]['ticket_reply_closed_ticket_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    if(isset(jssupportticket::$_data[0]['ticket_feedback_user'])){
                                        $title = __('Send Feedback Email To User', 'js-support-ticket');
                                        $field1 = '<span class="js-ticket-configuration-no-rec">'.'----'.'</span>';
                                        $field2 = '<span class="js-ticket-configuration-no-rec">'.'----'.'</span>';
                                        $field3 = JSSTformfield::select('ticket_feedback_user', $enableddisabled, jssupportticket::$_data[0]['ticket_feedback_user']);
                                        printConfigFieldMulti($title, $field1, $field2, $field3);
                                    }

                                    ?>
                        </div>
                        <?php if(in_array('agent', jssupportticket::$_active_addons)){ ?>
                        <div id="staffmenusetting" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('Dashboard Links', 'js-support-ticket') ?></h3>
                                    <?php

                                    if(isset(jssupportticket::$_data[0]['cplink_openticket_staff'])){
                                        $title = __('Open Ticket', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_openticket_staff', $showhide, jssupportticket::$_data[0]['cplink_openticket_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_myticket_staff'])){
                                        $title =  __('My Tickets', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_myticket_staff', $showhide, jssupportticket::$_data[0]['cplink_myticket_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_addrole_staff'])){
                                        $title = __('Add Role', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_addrole_staff', $showhide, jssupportticket::$_data[0]['cplink_addrole_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_roles_staff'])){
                                        $title =  __('Roles', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_roles_staff', $showhide, jssupportticket::$_data[0]['cplink_roles_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_addstaff_staff'])){
                                        $title = __('Add Agent', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_addstaff_staff', $showhide, jssupportticket::$_data[0]['cplink_addstaff_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_staff_staff'])){
                                        $title =  __('Agent', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_staff_staff', $showhide, jssupportticket::$_data[0]['cplink_staff_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_adddepartment_staff'])){
                                        $title = __('Add Department', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_adddepartment_staff', $showhide, jssupportticket::$_data[0]['cplink_adddepartment_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_department_staff'])){
                                        $title =  __('Department', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_department_staff', $showhide, jssupportticket::$_data[0]['cplink_department_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_addcategory_staff'])){
                                        $title = __('Add Category', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_addcategory_staff', $showhide, jssupportticket::$_data[0]['cplink_addcategory_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_category_staff'])){
                                        $title =  __('Category', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_category_staff', $showhide, jssupportticket::$_data[0]['cplink_category_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_addkbarticle_staff'])){
                                        $title = __('Add Knowledge Base', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_addkbarticle_staff', $showhide, jssupportticket::$_data[0]['cplink_addkbarticle_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_kbarticle_staff'])){
                                        $title =  __('Knowledge Base', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_kbarticle_staff', $showhide, jssupportticket::$_data[0]['cplink_kbarticle_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_adddownload_staff'])){
                                        $title = __('Add Download', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_adddownload_staff', $showhide, jssupportticket::$_data[0]['cplink_adddownload_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_download_staff'])){
                                        $title =  __('Download', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_download_staff', $showhide, jssupportticket::$_data[0]['cplink_download_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_addannouncement_staff'])){
                                        $title = __('Add Announcement', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_addannouncement_staff', $showhide, jssupportticket::$_data[0]['cplink_addannouncement_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_announcement_staff'])){
                                        $title =  __('Announcement', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_announcement_staff', $showhide, jssupportticket::$_data[0]['cplink_announcement_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_addfaq_staff'])){
                                        $title = __('Add FAQ', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_addfaq_staff', $showhide, jssupportticket::$_data[0]['cplink_addfaq_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }


                                    if(isset(jssupportticket::$_data[0]['cplink_faq_staff'])){
                                        $title =  __("FAQ's", 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_faq_staff', $showhide, jssupportticket::$_data[0]['cplink_faq_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_mail_staff'])){
                                        $title = __('Mail', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_mail_staff', $showhide, jssupportticket::$_data[0]['cplink_mail_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_myprofile_staff'])){
                                        $title =  __('My Profile', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_myprofile_staff', $showhide, jssupportticket::$_data[0]['cplink_myprofile_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_staff_report_staff'])){
                                        $title = __('Agent Reports', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_staff_report_staff', $showhide, jssupportticket::$_data[0]['cplink_staff_report_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_department_report_staff'])){
                                        $title =  __('Department reports', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_department_report_staff', $showhide, jssupportticket::$_data[0]['cplink_department_report_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_feedback_staff'])){
                                        $title = __('Feedbacks', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_feedback_staff', $showhide, jssupportticket::$_data[0]['cplink_feedback_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_login_logout_staff'])){
                                        $title =  __('Login/Logout Button', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_login_logout_staff', $showhide, jssupportticket::$_data[0]['cplink_login_logout_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_totalcount_staff'])){
                                        $title = __('Ticket Total Count', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_totalcount_staff', $showhide, jssupportticket::$_data[0]['cplink_totalcount_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_ticketstats_staff'])){
                                        $title =  __('Ticket Statistics', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_ticketstats_staff', $showhide, jssupportticket::$_data[0]['cplink_ticketstats_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_latesttickets_staff'])){
                                        $title = __('Latest Tickets', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_latesttickets_staff', $showhide, jssupportticket::$_data[0]['cplink_latesttickets_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_helptopic_agent'])){
                                        $title = __('Help Topic', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_helptopic_agent', $showhide, jssupportticket::$_data[0]['cplink_helptopic_agent']);
                                        printConfigFieldSingle($title, $field);
                                    }
                                    if(isset(jssupportticket::$_data[0]['cplink_cannedresponses_agent'])){
                                        $title = __('Canned Response', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_cannedresponses_agent', $showhide, jssupportticket::$_data[0]['cplink_cannedresponses_agent']);
                                        printConfigFieldSingle($title, $field);
                                    }
                                    if(isset(jssupportticket::$_data[0]['cplink_erasedata_staff'])){
                                        $title = __('Erase Agent Data', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_erasedata_staff', $showhide, jssupportticket::$_data[0]['cplink_erasedata_staff']);
                                        printConfigFieldSingle($title, $field);
                                    }
                                    ?>
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('Top Menu Links', 'js-support-ticket') ?></h3>
                                <?php
                                if(isset(jssupportticket::$_data[0]['tplink_home_staff'])){
                                    $title = __('Home', 'js-support-ticket');
                                    $field = JSSTformfield::select('tplink_home_staff', $showhide, jssupportticket::$_data[0]['tplink_home_staff']);
                                    printConfigFieldSingle($title, $field);
                                }

                                if(isset(jssupportticket::$_data[0]['tplink_tickets_staff'])){
                                    $title = __('Tickets', 'js-support-ticket');
                                    $field = JSSTformfield::select('tplink_tickets_staff', $showhide, jssupportticket::$_data[0]['tplink_tickets_staff']);
                                    printConfigFieldSingle($title, $field);
                                }

                                if(isset(jssupportticket::$_data[0]['tplink_openticket_staff'])){
                                    $title = __('Open Ticket', 'js-support-ticket');
                                    $field =  JSSTformfield::select('tplink_openticket_staff', $showhide, jssupportticket::$_data[0]['tplink_openticket_staff']);
                                    printConfigFieldSingle($title, $field);
                                }
                                    ?>
                        </div>
                        <?php } ?>
                        <div id="usermenusetting" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('Dashboard Links', 'js-support-ticket') ?></h3>

                                <?php
                                    if(isset(jssupportticket::$_data[0]['cplink_openticket_user'])){
                                        $title = __('Open Ticket', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_openticket_user', $showhide, jssupportticket::$_data[0]['cplink_openticket_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_myticket_user'])){
                                        $title = __('My Tickets', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_myticket_user', $showhide, jssupportticket::$_data[0]['cplink_myticket_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_checkticketstatus_user'])){
                                        $title = __('Check Ticket Status', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_checkticketstatus_user', $showhide, jssupportticket::$_data[0]['cplink_checkticketstatus_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_downloads_user'])){
                                        $title = __('Downloads', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_downloads_user', $showhide, jssupportticket::$_data[0]['cplink_downloads_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_announcements_user'])){
                                        $title = __('Announcements', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_announcements_user', $showhide, jssupportticket::$_data[0]['cplink_announcements_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_faqs_user'])){
                                        $title = __("FAQ's", 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_faqs_user', $showhide, jssupportticket::$_data[0]['cplink_faqs_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_knowledgebase_user'])){
                                        $title = __('Knowledge Base', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_knowledgebase_user', $showhide, jssupportticket::$_data[0]['cplink_knowledgebase_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_login_logout_user'])){
                                        $title = __('Login/Logout Button', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_login_logout_user', $showhide, jssupportticket::$_data[0]['cplink_login_logout_user']);
                                        printConfigFieldSingle($title, $field);
                                    }

                                    if(isset(jssupportticket::$_data[0]['cplink_register_user'])){
                                        $title = __('Registration', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_register_user', $showhide, jssupportticket::$_data[0]['cplink_register_user']);
                                        printConfigFieldSingle($title, $field);
                                    }
                                    if(isset(jssupportticket::$_data[0]['cplink_erasedata_user'])){
                                        $title = __('Erase User Data', 'js-support-ticket');
                                        $field = JSSTformfield::select('cplink_erasedata_user', $showhide, jssupportticket::$_data[0]['cplink_erasedata_user']);
                                        printConfigFieldSingle($title, $field);
                                    }
                                    ?>
                            <h3 class="js-ticket-configuration-heading-main"><?php echo __('Top Menu Links', 'js-support-ticket') ?></h3>

                                <?php
                                if(isset(jssupportticket::$_data[0]['tplink_home_user'])){
                                    $title = __('Home', 'js-support-ticket');
                                    $field = JSSTformfield::select('tplink_home_user', $showhide, jssupportticket::$_data[0]['tplink_home_user']);
                                    printConfigFieldSingle($title, $field);
                                }

                                if(isset(jssupportticket::$_data[0]['tplink_tickets_user'])){
                                    $title = __('Tickets', 'js-support-ticket');
                                    $field = JSSTformfield::select('tplink_tickets_user', $showhide, jssupportticket::$_data[0]['tplink_tickets_user']);
                                    printConfigFieldSingle($title, $field);
                                }

                                if(isset(jssupportticket::$_data[0]['tplink_openticket_user'])){
                                    $title = __('Open Ticket', 'js-support-ticket');
                                    $field = JSSTformfield::select('tplink_openticket_user', $showhide, jssupportticket::$_data[0]['tplink_openticket_user']);
                                    printConfigFieldSingle($title, $field);
                                }

                                if(isset(jssupportticket::$_data[0]['tplink_login_logout_user'])){
                                    $title = __('Login/Logout Button', 'js-support-ticket');
                                    $field = JSSTformfield::select('tplink_login_logout_user', $showhide, jssupportticket::$_data[0]['tplink_login_logout_user']);
                                    printConfigFieldSingle($title, $field);
                                }
                                ?>

                        </div>
                        <?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
                        <div id="feedback" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('Feedback Settings', 'js-support-ticket') ?></h3>
                                <?php

                                if(isset(jssupportticket::$_data[0]['feedback_email_delay_type'])){
                                    $title = __('Feedback Email Delay Type', 'js-support-ticket');
                                    $field = JSSTformfield::select('feedback_email_delay_type',  array((object) array('id' => '1', 'text' => __('Days', 'js-support-ticket')), (object) array('id' => '2', 'text' => __('Hours', 'js-support-ticket'))), jssupportticket::$_data[0]['feedback_email_delay_type']);
                                    $description = __('Select delay type for feedback email', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['feedback_email_delay'])){
                                    $title = __('Feedback Email Delay', 'js-support-ticket');
                                    $field = JSSTformfield::text('feedback_email_delay', jssupportticket::$_data[0]['feedback_email_delay'], array('class' => 'inputbox'));
                                    $description = __('Set no. of days or hours to send feedback email after a ticket is closed', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['feedback_thanks_message'])){
                                    $title = __('Success message after submitting feedback', 'js-support-ticket');
                                    $field = wp_editor(jssupportticket::$_data[0]['feedback_thanks_message'], 'feedback_thanks_message');
                                    $description =  __('This text will appear whenever anyone submits feedback', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                ?>
                        </div>
                        <?php } ?>
                        <?php if(in_array('emailpiping', jssupportticket::$_active_addons)){ ?>
                        <div id="ticketviaemail" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('Email Piping', 'js-support-ticket') ?></h3>
                            <?php
                                if(isset(jssupportticket::$_data[0]['read_utf_ticket_via_email'])){
                                    $title = __('UTF Auto Switch', 'js-support-ticket');
                                    $field = JSSTformfield::select('read_utf_ticket_via_email',$yesno, jssupportticket::$_data[0]['read_utf_ticket_via_email']);
                                    printConfigFieldSingle($title, $field);
                                }
                                if(isset(jssupportticket::$_data[0]['create_user_via_email'])){
                                    $title = __('Create User via email', 'js-support-ticket');
                                    $field = JSSTformfield::select('create_user_via_email',$yesno, jssupportticket::$_data[0]['create_user_via_email']);
                                    printConfigFieldSingle($title, $field);
                                }
                            ?>

                        </div>
                        <?php } ?>

                        <?php if(in_array('notification', jssupportticket::$_active_addons)){ ?>
                        <div id="pushnotification" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('Firebase Notifications', 'js-support-ticket') ?></h3>
                            <?php
                            if(!file_exists(WP_PLUGIN_DIR.'/js-support-ticket-notification/js-support-ticket-notification.php')){ ?>
                                <div class="jsst_error_messages" style="color: #000; margin-bottom: 15px;">
                                    <span style="color: #000;" class="jsst_msg" id="jsst_error_message"><?php echo __("JS Help Desk Desktop Notifications plugin is not installed. Please install the plugin to enable desktop notifications","js-support-ticket");?><a title="<?php echo __("Click here to insert Install.","js-support-ticket"); ?>" href="<?php echo admin_url("admin.php?page=premiumplugin"); ?>"><?php echo __("Click here to insert Install.","js-support-ticket"); ?></a></span>
                                </div>
                                <?php
                            }elseif(!class_exists('JSSTNotification')){ ?>
                                <div class="jsst_error_messages" style="color: #000; margin-bottom: 15px;">
                                    <span style="color: #000;" class="jsst_msg" id="jsst_success_message"><?php echo __("JS Help Desk Desktop Notifications plugin is not active.","js-support-ticket");?></span>
                                </div>
                                <?php
                            } ?>
                            <div class="jsst_error_messages" style="color: #000; margin-bottom: 15px;">
                                <span style="color: #000;" class="jsst_warning_msg" id="jsst_error_message"><?php echo __("Find and add firebase api's keys","js-support-ticket");?><a title="<?php echo __("Click here to get firebae api keys.","js-support-ticket"); ?>" href="https://console.firebase.google.com" target="_blank"><?php echo __("Click here to get firebae api keys.","js-support-ticket"); ?></a></span>
                            </div>
                            <?php
                                if(isset(jssupportticket::$_data[0]['apiKey_firebase'])){
                                    $title = __('API key for user', 'js-support-ticket');
                                    $field = JSSTformfield::text('apiKey_firebase', jssupportticket::$_data[0]['apiKey_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase api key for front user', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['authDomain_firebase'])){
                                    $title = __('Auth Domain', 'js-support-ticket');
                                    $field = JSSTformfield::text('authDomain_firebase', jssupportticket::$_data[0]['authDomain_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase Auth Domain', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['databaseURL_firebase'])){
                                    $title = __('Database Url', 'js-support-ticket');
                                    $field = JSSTformfield::text('databaseURL_firebase', jssupportticket::$_data[0]['databaseURL_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase Database URL', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['projectId_firebase'])){
                                    $title = __('Project Id', 'js-support-ticket');
                                    $field = JSSTformfield::text('projectId_firebase', jssupportticket::$_data[0]['projectId_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase Project Id', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['storageBucket_firebase'])){
                                    $title = __('Bucket Storage', 'js-support-ticket');
                                    $field = JSSTformfield::text('storageBucket_firebase', jssupportticket::$_data[0]['storageBucket_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase Bucket Storage', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['messagingSenderId_firebase'])){
                                    $title = __('Message Sender Id', 'js-support-ticket');
                                    $field = JSSTformfield::text('messagingSenderId_firebase', jssupportticket::$_data[0]['messagingSenderId_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase Message Sender Id', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['server_key_firebase'])){
                                    $title = __('Private Server Key', 'js-support-ticket');
                                    $field = JSSTformfield::text('server_key_firebase', jssupportticket::$_data[0]['server_key_firebase'], array('class' => 'inputbox'));
                                    $description =  __('Firebase Server Key', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }

                                if(isset(jssupportticket::$_data[0]['logo_for_desktop_notfication_url'])){
                                    $title = __('Logo Image for Desktop Notifications', 'js-support-ticket');
                                    $value = '<input type="file" name="logo_for_desktop_notfication" id="logo_for_desktop_notfication">';
                                    $description = '';
                                    if(jssupportticket::$_config['logo_for_desktop_notfication_url'] != ''){
                                        $maindir = wp_upload_dir();
                                        $path = $maindir['baseurl'].'/'.jssupportticket::$_config['data_directory'].'/attachmentdata';
                                        $description = '<img alt="'. __('Remove Image','js-support-ticket').'" height="60px" width="60px;" src="'.$path.'/'.jssupportticket::$_config['logo_for_desktop_notfication_url'].'"/> <label><input type="checkbox" name="del_logo_for_desktop_notfication" value="1">'. __('Remove Logo','js-support-ticket').'</label>';
                                    }else{
                                        $description = __('No Firebase Notificaiton Logo', 'js-support-ticket');
                                    }
                                    printConfigFieldSingle($title, $value, $description);
                                }
                                ?>
                        </div>
                        <?php } ?>

                        <?php if(in_array('privatecredentials', jssupportticket::$_active_addons)){ ?>
                        <div id="privatecredentials" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('Private Credentials', 'js-support-ticket') ?></h3>
                                <?php
                                if(isset(jssupportticket::$_data[0]['private_credentials_secretkey'])){
                                    $title = __('Secret Key', 'js-support-ticket');
                                    $field = JSSTformfield::text('private_credentials_secretkey', jssupportticket::$_data[0]['private_credentials_secretkey'], array('class' => 'inputbox'));
                                    $description =  __('Private Credentials Encryption Key changing this value will discard all existing credentials ', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                $privatecredentialsurl = WP_PLUGIN_DIR.'/js-support-ticket-privatecredentials/classes/privatecredentials.php';
                                $title = __('Second Level Security', 'js-support-ticket');
                                $field = '';
                                $description =  sprintf(__('For enhanced security change encryption method in %s on line %s', 'js-support-ticket'),$privatecredentialsurl,10);
                                printConfigFieldSingle($title, $field, $description);
                                ?>
                        </div>
                        <?php } ?>

                        <?php if(in_array('envatovalidation', jssupportticket::$_active_addons)){ ?>
                        <div id="envatovalidation" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('Envato Validation', 'js-support-ticket') ?></h3>
                                <?php
                                if(isset(jssupportticket::$_data[0]['envato_api_key'])){
                                    $title = __('Api Key', 'js-support-ticket');
                                    $field = JSSTformfield::text('envato_api_key', jssupportticket::$_data[0]['envato_api_key'], array('class' => 'inputbox'));
                                    $description =  __('Enter Envato api key ', 'js-support-ticket');
                                    $description.= '<a title="'.__("Click here to generate an api key",'js-support-ticket').'" target="_blank" href="https://build.envato.com/create-token/">'.__("Click here to generate an api key",'js-support-ticket').'</a>';
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                if(isset(jssupportticket::$_data[0]['envato_license_required'])){
                                    $title = __('License Mandatory', 'js-support-ticket');
                                    $field = JSSTformfield::select('envato_license_required', $yesno, jssupportticket::$_data[0]['envato_license_required']);
                                    $description =  __('Prevent users from submitting a ticket without a valid license for one of your product', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                if(isset(jssupportticket::$_data[0]['envato_product_ids'])){
                                    $title = __('Product ID', 'js-support-ticket');
                                    $field = JSSTformfield::text('envato_product_ids', jssupportticket::$_data[0]['envato_product_ids'], array('class' => 'inputbox'));
                                    $description =  __('A comma-separated list of Envato product ids', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                ?>
                        </div>
                        <?php } ?>

                        <?php if(in_array('mailchimp', jssupportticket::$_active_addons)){ ?>
                        <div id="mailchimp" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('MailChimp', 'js-support-ticket') ?></h3>
                                <?php
                                if(isset(jssupportticket::$_data[0]['mailchimp_api_key'])){
                                    $title = __('Api Key', 'js-support-ticket');
                                    $field = JSSTformfield::text('mailchimp_api_key', jssupportticket::$_data[0]['mailchimp_api_key'], array('class' => 'inputbox'));
                                    $description =  __('Enter MailChimp API key ', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                if(isset(jssupportticket::$_data[0]['mailchimp_list_id'])){
                                    $title = __('Audience ID', 'js-support-ticket');
                                    $field = JSSTformfield::text('mailchimp_list_id', jssupportticket::$_data[0]['mailchimp_list_id'], array('class' => 'inputbox'));
                                    $description =  __('Find Audience ID in your MailChimp account', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                if(isset(jssupportticket::$_data[0]['mailchimp_double_optin'])){
                                    $title = __('Enable double opt-in', 'js-support-ticket');
                                    $field = JSSTformfield::select('mailchimp_double_optin', $yesno, jssupportticket::$_data[0]['mailchimp_double_optin']);
                                    $description =  __('You must also enable double opt-in in your MailChimp account', 'js-support-ticket');
                                    printConfigFieldSingle($title, $field, $description);
                                }
                                $title = __('Welcome email', 'js-support-ticket');
                                $field = __('You can enable Final Welcome Email in your MailChimp account', 'js-support-ticket');
                                $description = '';
                                printConfigFieldSingle($title, $field, $description);
                                ?>
                        </div>
                        <?php } ?>

                        <?php if(in_array('easydigitaldownloads', jssupportticket::$_active_addons)){ ?>
                        <div id="easydigitaldownloads" class="jsst_tab_content ">
                            <h3 class="js-ticket-configuration-heading-main"> <?php echo __('Easy Digital Downloads', 'js-support-ticket') ?></h3>
                                <?php
                                if(isset(jssupportticket::$_data[0]['verify_license_on_ticket_creation'])){
                                    $title = __('Verify License On Ticket Creation', 'js-support-ticket');
                                    $field = JSSTformfield::select('verify_license_on_ticket_creation', $yesno, jssupportticket::$_data[0]['verify_license_on_ticket_creation']);
                                    printConfigFieldSingle($title, $field);
                                }
                                ?>
                        </div>
                        <?php } ?>

                    </div>
                </div>
                <?php echo JSSTformfield::hidden('action', 'configuration_saveconfiguration'); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <div class="js-form-button">
                    <?php echo JSSTformfield::submitbutton('save', __('Save Configurations', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

    function printConfigFieldSingle($title, $field, $description = ''){
        $html = '';
        $html .= '
            <div class="js-ticket-configuration-row">
                <div class="js-ticket-configuration-title">'.$title.'</div>
                <div class="js-ticket-configuration-value">'.$field.'</div>';
        if($description !=''){
            $html .= '<div class="js-ticket-configuration-description">'.$description.'</div>';
        }
        $html .= '
        </div>';
        echo $html;
    }

    function printConfigFieldMulti($title, $field1, $field2, $field3){
        $html = '';

        $html = '
        <div class="js-ticket-configuration-row-mail">
            <div class="js-ticket-configuration-title">'.$title.'</div>
            <div class="js-ticket-configuration-value"><span class="js-ticket-config-xs-show-hide">'. __('Agent','js-support-ticket') .'</span>'.$field1.'</div>
            <div class="js-ticket-configuration-value"><span class="js-ticket-config-xs-show-hide">'. __('User','js-support-ticket') .'</span>'.$field2.'</div>
            <div class="js-ticket-configuration-value"><span class="js-ticket-config-xs-show-hide">'. __('Admin','js-support-ticket') .'</span>'.$field3.'</div>
        </div>
        ';
        echo $html;
    }

 ?>
