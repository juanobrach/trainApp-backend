<?php
wp_enqueue_script( 'ticket-notify-app', 'https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js' );
wp_enqueue_script( 'ticket-notify-message', 'https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js' );
do_action('ticket-notify-generate-token');
wp_enqueue_style('status-graph', jssupportticket::$_pluginpath . 'includes/css/status_graph.css');
?>
<?php if(isset(jssupportticket::$_data['stack_chart_horizontal'])){ ?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script>
    google.setOnLoadCallback(drawStackChartHorizontal);
    function drawStackChartHorizontal() {
      var data = google.visualization.arrayToDataTable([
        <?php
            echo jssupportticket::$_data['stack_chart_horizontal']['title'].',';
            echo jssupportticket::$_data['stack_chart_horizontal']['data'];
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        height:571,
        chartArea: { width: '80%'},
        legend: { position: 'top',  },
        curveType: 'function',
        colors: ['#ff652f','#5ab9ea','#d89922','#14a76c'],
      };
      var chart = new google.visualization.AreaChart(document.getElementById("stack_chart_horizontal"));
      chart.draw(view, options);
    }
</script>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        jQuery("div#js-ticket-main-black-background,span#js-ticket-popup-close-button").click(function () {
            jQuery("div#js-ticket-main-popup").slideUp();
            setTimeout(function () {
                jQuery("div#js-ticket-main-black-background").hide();
            }, 600);

        });
    });
    function getDownloadById(value) {
        ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        jQuery.post(ajaxurl, {action: 'jsticket_ajax', downloadid: value, jstmod: 'download', task: 'getDownloadById',jsstpageid:<?php echo get_the_ID(); ?>}, function (data) {
            if (data) {
                var obj = jQuery.parseJSON(data);
                jQuery("div#js-ticket-main-content").html(obj.data);
                jQuery("span#js-ticket-popup-title").html(obj.title);
                jQuery("div#js-ticket-main-downloadallbtn").html(obj.downloadallbtn);
                jQuery("div#js-ticket-main-black-background").show();
                jQuery("div#js-ticket-main-popup").slideDown("slow");
            }
        });
    }
</script>
<?php

if (jssupportticket::$_config['offline'] == 2) {
    JSSTmessage::getMessage();
    include_once(jssupportticket::$_path . 'includes/header.php');
    $agent_flag = 0;
    if(in_array('agent',jssupportticket::$_active_addons)){
        if (JSSTincluder::getJSModel('agent')->isUserStaff()) {
            $agent_flag = 1;
        }
    }

    $data = isset(jssupportticket::$_data[0]) ? jssupportticket::$_data[0] : array();
    ?>


    <div class="js-cp-main-wrp">
        <div class="js-cp-left">
            <!-- cp links for user -->
            <?php
                if ($agent_flag == 0) {
                    $count = JSSTincluder::getJSModel('configuration')->getCountByConfigFor('cplink');
                    if($count != 0){ ?>
                        <div id="js-dash-menu-link-wrp"><!-- Dashboard Links -->
                            <div class="js-section-heading"><?php echo __('Dashboard Links','js-support-ticket'); ?></div>
                            <div class="js-menu-links-wrp">
                                <?php
                                $count = 0;
                                /*<div class="js-ticket-menu-links-row">*/
                                if (jssupportticket::$_config['cplink_openticket_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'addticket')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/add-ticket.png';
                                    $menu_title =  __('Submit Ticket', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (jssupportticket::$_config['cplink_myticket_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'myticket')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/tickets.png';
                                    $menu_title =  __('My Tickets', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (jssupportticket::$_config['cplink_checkticketstatus_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketstatus')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/ticket-status.png';
                                    $menu_title =  __('Ticket Status', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (in_array('announcement', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_announcements_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'announcement', 'jstlay'=>'announcements')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/announcements.png';
                                    $menu_title =  __('Announcements', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (in_array('download', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_downloads_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'download', 'jstlay'=>'downloads')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/download.png';
                                    $menu_title =  __('Downloads', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (in_array('faq', jssupportticket::$_active_addons) &&  jssupportticket::$_config['cplink_faqs_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'faq', 'jstlay'=>'faqs')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/faq.png';
                                    $menu_title =  __("FAQ's", 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (in_array('knowledgebase', jssupportticket::$_active_addons) &&  jssupportticket::$_config['cplink_knowledgebase_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'knowledgebase', 'jstlay'=>'userknowledgebase')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/kb.png';
                                    $menu_title =  __('Knowledge Base', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (jssupportticket::$_config['cplink_erasedata_user'] == 1):
                                    $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'gdpr', 'jstlay'=>'adderasedatarequest')));
                                    $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/user-data.png';
                                    $menu_title =  __('User Data', 'js-support-ticket');
                                    printMenuLink($menu_title, $menu_url, $image_path,$count);
                                endif;
                                if (jssupportticket::$_config['cplink_login_logout_user'] == 1){
                                    $loginval = JSSTincluder::getJSModel('configuration')->getConfigValue('set_login_link');
                                    $loginlink = JSSTincluder::getJSModel('configuration')->getConfigValue('login_link');
                                     if ($loginval == 2 && $loginlink != ""){
                                            $hreflink = $loginlink;
                                        }else{
                                            $hreflink= jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'login'));
                                        }
                                        if (!is_user_logged_in()):
                                            $menu_url = $hreflink;
                                            $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/login.png';
                                            $menu_title =  __('Log In', 'js-support-ticket');
                                            printMenuLink($menu_title, $menu_url, $image_path,$count);
                                        endif;
                                    if (is_user_logged_in()):
                                        $menu_url = wp_logout_url( home_url() );
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/logout.png';
                                        $menu_title =  __('Log Out', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                }
                                if (jssupportticket::$_config['cplink_register_user'] == 1){
                                    if (!is_user_logged_in()):
                                        $is_enable = get_option('users_can_register'); /*check to make sure user registration is enabled*/
                                        if ($is_enable) {// only show the registration form if allowed
                                            $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'userregister')));
                                            $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/register.png';
                                            $menu_title =  __('Register', 'js-support-ticket');
                                            printMenuLink($menu_title, $menu_url, $image_path,$count);
                                        }
                                    endif;
                                }

                                if($count != 0){
                                    echo '</div>';// to close the last div of print menu link fuctinon
                                }
                                ?>

                            </div>
                        </div>
                    <?php
                    }
                }
            ?>

            <!-- cp links for agent -->
            <?php
                if ( in_array('agent',jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff()) {
                    $count = JSSTincluder::getJSModel('configuration')->getCountByConfigFor('cplink');
                    if($count != 0){ ?>
                        <div id="js-dash-menu-link-wrp">
                            <div class="js-section-heading"><?php echo __('Dashboard Links','js-support-ticket'); ?></div>
                            <div class="js-menu-links-wrp">  <!-- Dashboard Links -->

                                    <?php
                                    $count = 0;
                                    if (jssupportticket::$_config['cplink_openticket_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'agent', 'jstlay'=>'staffaddticket')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/add-ticket.png';
                                        $menu_title =  __('Submit Ticket', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_myticket_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'agent', 'jstlay'=>'staffmyticket')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/tickets.png';
                                        $menu_title =  __('My Tickets', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_roles_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'role', 'jstlay'=>'roles')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/role.png';
                                        $menu_title =  __('Roles', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_staff_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'agent', 'jstlay'=>'staffs')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/staff.png';
                                        $menu_title =  __('Agents', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_department_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'department', 'jstlay'=>'departments')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/department.png';
                                        $menu_title =  __('Departments', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (in_array('knowledgebase', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_category_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'knowledgebase', 'jstlay'=>'stafflistcategories')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/category.png';
                                        $menu_title =  __('Categories', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (in_array('knowledgebase', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_kbarticle_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'knowledgebase', 'jstlay'=>'stafflistarticles')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/kb.png';
                                        $menu_title =  __('Knowledge Base', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (in_array('download', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_download_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'download', 'jstlay'=>'staffdownloads')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/download.png';
                                        $menu_title =  __('Downloads', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (in_array('announcement', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_announcement_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'announcement', 'jstlay'=>'staffannouncements')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/announcements.png';
                                        $menu_title =  __('Announcements', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (in_array('faq', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_faq_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'faq', 'jstlay'=>'stafffaqs')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/faq.png';
                                        $menu_title =  __("FAQ's", 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                     if (in_array('helptopic', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_helptopic_agent'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'helptopic', 'jstlay'=>'agenthelptopics')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/help-topic.png';
                                        $menu_title =  __("Help Topics", 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;

                                    if (in_array('cannedresponses', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_cannedresponses_agent'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'cannedresponses', 'jstlay'=>'agentcannedresponses')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/canned-response.png';
                                        $menu_title =  __("Canned Responses", 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;

                                    if (in_array('mail', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_mail_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'mail', 'jstlay'=>'inbox')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/mails.png';
                                        $menu_title =  __('Mail', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_staff_report_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'reports', 'jstlay'=>'staffreports')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/staff-report.png';
                                        $menu_title =  __('Agent Reports', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_department_report_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'reports', 'jstlay'=>'departmentreports')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/department-report.png';
                                        $menu_title =  __('Department Reports', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (in_array('feedback', jssupportticket::$_active_addons) && jssupportticket::$_config['cplink_feedback_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'feedback', 'jstlay'=>'feedbacks')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/feedback.png';
                                        $menu_title =  __('Agent Feedbacks', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_myprofile_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'agent', 'jstlay'=>'myprofile')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/profile.png';
                                        $menu_title =  __('My Profile', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_erasedata_staff'] == 1):
                                        $menu_url = esc_url(jssupportticket::makeUrl(array('jstmod'=>'gdpr', 'jstlay'=>'adderasedatarequest')));
                                        $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/user-data.png';
                                        $menu_title =  __('User Data', 'js-support-ticket');
                                        printMenuLink($menu_title, $menu_url, $image_path,$count);
                                    endif;
                                    if (jssupportticket::$_config['cplink_login_logout_staff'] == 1){
                                        if (!is_user_logged_in()):
                                            $menu_url = $hreflink;
                                            $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/profile.png';
                                            $menu_title =  __('Log In', 'js-support-ticket');
                                            printMenuLink($menu_title, $menu_url, $image_path,$count);
                                        endif;
                                        if (is_user_logged_in()):
                                            $menu_url = wp_logout_url( home_url() );
                                            $image_path = jssupportticket::$_pluginpath . 'includes/images/left-icons/menu/logout.png';
                                            $menu_title =  __('Log Out', 'js-support-ticket');
                                            printMenuLink($menu_title, $menu_url, $image_path,$count);
                                        endif;
                                    }
                                    if($count != 0){
                                        echo '</div>';// to close the last div of print menu link fuctinon
                                    }
                                    ?>
                            </div>
                        </div>
                    <?php
                    }
                }
            ?>
        </div>
        <div class="js-cp-right">
            <?php if(!is_user_logged_in()){ ?>
            <div class="js-support-ticket-cont">
                <div class="js-support-ticket-box">
                    <img src="<?php echo jssupportticket::$_pluginpath . "includes/images/dashboard/add-ticket.png"; ?>" alt="<?php echo __('Create Ticket', 'js-support-ticket'); ?>" />
                    <div class="js-support-ticket-title">
                        <?php echo __('Submit Ticket','js-support-ticket'); ?>
                    </div>
                    <div class="js-support-ticket-desc">
                        <?php echo __('Submit ticket','js-support-ticket'); ?>
                    </div>
                    <a href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'addticket'))); ?>" class="js-support-ticket-btn">
                        <?php echo __('Submit Ticket','js-support-ticket'); ?>
                    </a>
                </div>
                <div class="js-support-ticket-box">
                    <img src="<?php echo jssupportticket::$_pluginpath . "includes/images/dashboard/my-tickets.png"; ?>" alt="<?php echo __('my ticket', 'js-support-ticket'); ?>" />
                    <div class="js-support-ticket-title">
                        <?php echo __('My Tickets','js-support-ticket'); ?>
                    </div>
                    <div class="js-support-ticket-desc">
                        <?php echo __('View all the created tickets','js-support-ticket'); ?>
                    </div>
                    <a href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'myticket')));?>" class="js-support-ticket-btn">
                        <?php echo __('My Tickets','js-support-ticket'); ?>
                    </a>
                </div>
                <div class="js-support-ticket-box">
                    <img src="<?php echo jssupportticket::$_pluginpath . "includes/images/dashboard/ticket-status.png"; ?>" alt="<?php echo __('Ticket Status', 'js-support-ticket'); ?>" />
                    <div class="js-support-ticket-title">
                        <?php echo __('Ticket Status','js-support-ticket'); ?>
                    </div>
                    <div class="js-support-ticket-desc">
                        <?php echo __('your ticket status','js-support-ticket'); ?>
                    </div>
                    <a href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketstatus')));?>" class="js-support-ticket-btn">
                        <?php echo __('Ticket Status','js-support-ticket'); ?>
                    </a>
                </div>
            </div>
            <?php } ?>
            <!-- count boxes -->
            <?php
            if(isset($data['count'])){
                $open_percentage = 0;
                $close_percentage = 0;
                $answered_percentage = 0;
                $overdue_percentage = 0;
                $allticket_percentage = 0;
                if($data['count']['allticket'] > 0){ //to avoid division by zero error
                    $open_percentage = round(($data['count']['openticket'] / $data['count']['allticket']) * 100);
                    $close_percentage = round(($data['count']['closedticket'] / $data['count']['allticket']) * 100);
                    $answered_percentage = round(($data['count']['answeredticket'] / $data['count']['allticket']) * 100);
                    if(isset($data['count']['overdue'])){
                        $overdue_percentage = round(($data['count']['overdue'] / $data['count']['allticket']) * 100);
                    }
                    $allticket_percentage = 100;
                }
                ?>
                <div class="js-ticket-count">
                    <div class="js-ticket-link">
                        <a class="js-ticket-link js-ticket-green" href="#" data-tab-number="1" title="<?php echo __('Open Ticket','js-support-ticket'); ?>">
                            <div class="js-ticket-cricle-wrp" data-per="<?php echo $open_percentage; ?>" >
                                <div class="js-mr-rp" data-progress="<?php echo $open_percentage; ?>">
                                    <div class="circle">
                                        <div class="mask full">
                                             <div class="fill js-ticket-open"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill js-ticket-open"></div>
                                            <div class="fill fix"></div>
                                        </div>
                                        <div class="shadow"></div>
                                    </div>
                                    <div class="inset">
                                    </div>
                                </div>
                            </div>
                            <div class="js-ticket-link-text js-ticket-green">
                                <?php
                                    echo __('Open', 'js-support-ticket');
                                    echo ' ( '.$data['count']['openticket'].' )';
                                ?>
                            </div>
                        </a>
                    </div>
                    <div class="js-ticket-link">
                        <a class="js-ticket-link js-ticket-red" href="#" data-tab-number="2" title="<?php echo __('closed ticket','js-support-ticket'); ?>">
                            <div class="js-ticket-cricle-wrp" data-per="<?php echo $close_percentage; ?>" >
                                <div class="js-mr-rp" data-progress="<?php echo $close_percentage; ?>">
                                    <div class="circle">
                                        <div class="mask full">
                                             <div class="fill js-ticket-close"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill js-ticket-close"></div>
                                            <div class="fill fix"></div>
                                        </div>
                                        <div class="shadow"></div>
                                    </div>
                                    <div class="inset">
                                    </div>
                                </div>
                            </div>
                            <div class="js-ticket-link-text js-ticket-red">
                                <?php
                                    echo __('Closed', 'js-support-ticket');
                                    echo ' ( '.$data['count']['closedticket'].' )';
                                ?>
                            </div>
                        </a>
                    </div>
                    <div class="js-ticket-link">
                        <a class="js-ticket-link js-ticket-brown" href="#" data-tab-number="3" title="<?php echo __('answered ticket','js-support-ticket'); ?>">
                            <div class="js-ticket-cricle-wrp" data-per="<?php echo $answered_percentage; ?>" >
                                <div class="js-mr-rp" data-progress="<?php echo $answered_percentage; ?>">
                                    <div class="circle">
                                        <div class="mask full">
                                             <div class="fill js-ticket-answer"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill js-ticket-answer"></div>
                                            <div class="fill fix"></div>
                                        </div>
                                        <div class="shadow"></div>
                                    </div>
                                    <div class="inset">
                                    </div>
                                </div>
                            </div>
                            <div class="js-ticket-link-text js-ticket-brown">
                                <?php
                                    echo __('Answered', 'js-support-ticket');
                                    echo ' ( '.$data['count']['answeredticket'].' )';
                                ?>
                            </div>
                        </a>
                    </div>
                    <?php if(isset($data['count']['overdue'])){ ?>
                    <div class="js-ticket-link">
                        <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="4" title="<?php echo __('overdue ticket','js-support-ticket'); ?>">
                            <div class="js-ticket-cricle-wrp" data-per="<?php echo $overdue_percentage; ?>" >
                                <div class="js-mr-rp" data-progress="<?php echo $overdue_percentage; ?>">
                                    <div class="circle">
                                        <div class="mask full">
                                             <div class="fill js-ticket-overdue"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill js-ticket-overdue"></div>
                                            <div class="fill fix"></div>
                                        </div>
                                        <div class="shadow"></div>
                                    </div>
                                    <div class="inset">
                                    </div>
                                </div>
                            </div>
                            <div class="js-ticket-link-text js-ticket-orange">
                                <?php
                                    echo __('Overdue', 'js-support-ticket');
                                    echo ' ( '.$data['count']['overdue'].' )';
                                ?>
                            </div>
                        </a>
                    </div>
                    <?php }else{ ?>
                    <div class="js-ticket-link">
                        <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="4" title="<?php echo __('overdue ticket','js-support-ticket'); ?>">
                            <div class="js-ticket-cricle-wrp" data-per="<?php echo $allticket_percentage; ?>" >
                                <div class="js-mr-rp" data-progress="<?php echo $allticket_percentage; ?>">
                                    <div class="circle">
                                        <div class="mask full">
                                             <div class="fill js-ticket-allticket"></div>
                                        </div>
                                        <div class="mask half">
                                            <div class="fill js-ticket-allticket"></div>
                                            <div class="fill fix"></div>
                                        </div>
                                        <div class="shadow"></div>
                                    </div>
                                    <div class="inset">
                                    </div>
                                </div>
                            </div>
                            <div class="js-ticket-link-text js-ticket-blue">
                                <?php
                                    echo __('All Tickets', 'js-support-ticket');
                                    echo ' ( '.$data['count']['allticket'].' )';
                                ?>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <?php
            }
            ?>
            <!-- latest user tickets -->
            <?php
            if(isset($data['user-tickets'])){
                $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
                $show_field = JSSTincluder::getJSModel('fieldordering')->getFieldsForListing(1);
                ?>
                <div class="js-ticket-latest-ticket-wrapper">
                    <div class="js-ticket-haeder">
                        <div class="js-ticket-header-txt">
                            <?php echo __("Latest Tickets",'js-support-ticket'); ?>
                        </div>
                        <a class="js-ticket-header-link" href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'ticket','jstlay'=>'myticket')); ?>">
                            <?php echo __("View All Tickets",'js-support-ticket'); ?>
                        </a>
                    </div>
                    <div class="js-ticket-latest-tickets-wrp">
                        <?php
                        foreach($data['user-tickets'] as $ticket){
                            if ($ticket->status == 0) {
                                $style = "#5bb12f;";
                                $status = __('New', 'js-support-ticket');
                            } elseif ($ticket->status == 1) {
                                $style = "#28abe3;";
                                $status = __('Waiting Reply', 'js-support-ticket');
                            } elseif ($ticket->status == 2) {
                                $style = "#69d2e7;";
                                $status = __('In Progress', 'js-support-ticket');
                            } elseif ($ticket->status == 3) {
                                $style = "#FFB613;";
                                $status = __('Replied', 'js-support-ticket');
                            } elseif ($ticket->status == 4) {
                                $style = "#ed1c24;";
                                $status = __('Closed', 'js-support-ticket');
                            } elseif ($ticket->status == 5) {
                                $style = "#dc2742;";
                                $status = __('Close and merge', 'js-support-ticket');
                            }
                            $ticketviamail = '';
                            if ($ticket->ticketviaemail == 1)
                                $ticketviamail = __('Created via Email', 'js-support-ticket');
                            ?>
                            <div class="js-ticket-row">
                                <div class="js-ticket-first-left">
                                    <div class="js-ticket-user-img-wrp">
                                        <?php echo jsst_get_avatar($ticket->uid); ?>
                                    </div>
                                    <div class="js-ticket-ticket-subject">
                                        <div class="js-ticket-data-row">
                                            <?php echo $ticket->name; ?>
                                        </div>
                                        <div class="js-ticket-data-row name">
                                            <a class="js-ticket-data-link" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket','jstlay'=>'ticketdetail','jssupportticketid'=> $ticket->id))); ?>"><?php echo $ticket->subject; ?></a>
                                        </div>
                                        <div class="js-ticket-data-row">
                                            <span class="js-ticket-title"><?php echo __($field_array['department'], 'js-support-ticket'). ' : '; ?></span>
                                            <?php echo __($ticket->departmentname, 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-ticket-second-left">
                                    <?php if ($ticket->ticketviaemail == 1){  ?>
                                        <span class="js-ticket-creade-via-email-spn"><?php echo $ticketviamail; ?></span>
                                    <?php } ?>
                                    <?php
                                    $counter = 'one';
                                    if ($ticket->lock == 1) {
                                        ?>
                                        <img class="ticketstatusimage <?php echo $counter;
                                        $counter = 'two'; ?>" src="<?php echo jssupportticket::$_pluginpath . "includes/images/lock.png"; ?>" title="<?php echo __('The ticket is locked', 'js-support-ticket'); ?>" />
                                    <?php } ?>
                                    <?php if ($ticket->isoverdue == 1) { ?>
                                            <img class="ticketstatusimage <?php echo $counter; ?>" src="<?php echo jssupportticket::$_pluginpath . "includes/images/over-due.png"; ?>" title="<?php echo __('The ticket marks as overdue', 'js-support-ticket'); ?>" />
                                    <?php } ?>
                                    <span class="js-ticket-status" style="color:<?php echo $style; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </div>
                                <div class="js-ticket-third-left">
                                    <?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->created)); ?>
                                </div>
                                <div class="js-ticket-fourth-left">
                                    <span class="js-ticket-priorty" style="background:<?php echo $ticket->prioritycolour; ?>;"><?php echo __($ticket->priority, 'js-support-ticket'); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- agent data chart -->
            <?php
            if(isset(jssupportticket::$_data['stack_chart_horizontal'])){
                ?>
                <div class="js-pm-graphtitle-wrp">
                    <div class="js-pm-graphtitle">
                        <?php echo __('Ticket Statistics', 'js-support-ticket'); ?>
                    </div>
                    <div id="js-pm-grapharea">
                        <div id="stack_chart_horizontal" style="width:100%;"></div>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- latest downloads -->
            <?php
            if(isset($data['latest-downloads'])){
                ?>
                <div class="js-ticket-data-list-wrp latst-dnlds">
                    <div class="js-ticket-haeder">
                        <div class="js-ticket-header-txt">
                            <?php echo __("Latest Downloads",'js-support-ticket'); ?>
                        </div>
                        <a class="js-ticket-header-link" href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'download','jstlay'=>'downloads')); ?>"><?php echo __("View All Downloads",'js-support-ticket'); ?></a></span>
                    </div>
                    <div class="js-ticket-data-list">
                        <?php
                        $imgindex = 1;
                        foreach($data['latest-downloads'] as $download){
                            ?>
                            <div class="js-ticket-data">
                                <div class="js-ticket-data-image">
                                    <img alt="image" class="js-ticket-data-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/downloadicon/download-<?php echo $imgindex; ?>.png" />
                                </div>
                                <div class="js-ticket-data-tit">
                                    <?php echo __($download->title,"js-support-ticket"); ?>
                                </div>
                                <button type="button" class="js-ticket-data-btn" onclick="getDownloadById(<?php echo $download->downloadid ?>)">
                                    <?php echo __('Download','js-support-ticket'); ?>
                                </button>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- latest announcements -->
            <?php
            if(isset($data['latest-announcements'])){
                ?>
                <div class="js-ticket-data-list-wrp latst-ancmts">
                    <div class="js-ticket-haeder">
                        <div class="js-ticket-header-txt">
                            <?php echo __("Latest Announcements",'js-support-ticket'); ?>
                        </div>
                        <a class="js-ticket-header-link" href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'announcement','jstlay'=>'announcements')); ?>">
                            <?php echo __("View All Announcements",'js-support-ticket'); ?>
                        </a>
                    </div>
                    <div class="js-ticket-data-list">
                        <?php
                        $imgindex = 1;
                        foreach($data['latest-announcements'] as $announcement){
                            ?>
                            <div class="js-ticket-data">
                                <div class="js-ticket-data-image">
                                    <img alt="img" class="js-ticket-data-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/announcement/announcement-<?php echo $imgindex; ?>.png" />
                                </div>
                                <a class="js-ticket-data-tit" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'announcement', 'jstlay'=>'announcementdetails', 'jssupportticketid'=>$announcement->id))); ?>">
                                    <?php echo $announcement->title; ?>
                                </a>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- latest articles -->
            <?php
            if(isset($data['latest-articles'])){
                ?>
                <div class="js-ticket-data-list-wrp latst-kb">
                    <div class="js-ticket-haeder">
                        <div class="js-ticket-header-txt">
                            <?php echo __("Latest Knowledge Base",'js-support-ticket'); ?>
                        </div>
                        <a class="js-ticket-header-link" href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'knowledgebase','jstlay'=>'userknowledgebase')); ?>">
                            <?php echo __("View All Knowledge Base",'js-support-ticket'); ?>

                        </a>
                    </div>
                    <div class="js-ticket-data-list">
                        <?php
                        $imgindex = 1;
                        foreach($data['latest-articles'] as $article){
                            ?>
                            <div class="js-ticket-data">
                                <div class="js-ticket-data-image">
                                    <img alt="image" class="js-ticket-data-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/kb/kb-<?php echo $imgindex; ?>.png" />
                                </div>
                                <a class="js-ticket-data-tit" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'knowledgebase', 'jstlay'=>'articledetails', 'jssupportticketid'=>$article->articleid))); ?>">
                                    <?php echo $article->subject; ?>
                                </a>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <!-- latest faqs -->
            <?php
            if(isset($data['latest-faqs'])){
                ?>
                <div class="js-ticket-data-list-wrp latst-faqs">
                    <div class="js-ticket-haeder">
                        <div class="js-ticket-header-txt">
                            <?php echo __("Latest FAQs",'js-support-ticket'); ?>
                        </div>
                        <a class="js-ticket-header-link" href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'faq','jstlay'=>'faqs')); ?>">
                            <?php echo __("View All FAQs",'js-support-ticket'); ?>
                        </a>
                    </div>
                    <div class="js-ticket-data-list">
                        <?php
                        $imgindex = 1;
                        foreach($data['latest-faqs'] as $faq){
                            ?>
                            <div class="js-ticket-data">
                                <div class="js-ticket-data-image">
                                    <img alt="image" class="js-ticket-data-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/faq/faq-<?php echo $imgindex; ?>.png" />
                                </div>
                                <a class="js-ticket-data-tit" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'faq', 'jstlay'=>'faqdetails', 'jssupportticketid'=>$faq->id))); ?>">
                                    <?php echo $faq->subject; ?>
                                </a>
                            </div>
                            <?php
                            $imgindex = $imgindex==6 ? 1 : $imgindex+1;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <!-- latest agent tickets -->
        <?php
        if(isset($data['agent-tickets'])){
            $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
            $show_field = JSSTincluder::getJSModel('fieldordering')->getFieldsForListing(1);
            ?>
            <div class="js-ticket-latest-ticket-wrapper">
                <div class="js-ticket-haeder">
                    <div class="js-ticket-header-txt">
                        <?php echo __("Latest Tickets",'js-support-ticket'); ?>
                    </div>
                    <a class="js-ticket-header-link" href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'agent','jstlay'=>'staffmyticket')); ?>"><?php echo __("View All Tickets",'js-support-ticket'); ?></a></span>
                </div>
                <div class="js-ticket-latest-tickets-wrp">
                    <?php
                    foreach($data['agent-tickets'] as $ticket){
                        if ($ticket->status == 0) {
                            $style = "#5bb12f;";
                            $status = __('New', 'js-support-ticket');
                        } elseif ($ticket->status == 1) {
                            $style = "#28abe3;";
                            $status = __('Waiting Reply', 'js-support-ticket');
                        } elseif ($ticket->status == 2) {
                            $style = "#69d2e7;";
                            $status = __('In Progress', 'js-support-ticket');
                        } elseif ($ticket->status == 3) {
                            $style = "#FFB613;";
                            $status = __('Replied', 'js-support-ticket');
                        } elseif ($ticket->status == 4) {
                            $style = "#ed1c24;";
                            $status = __('Closed', 'js-support-ticket');
                        } elseif ($ticket->status == 5) {
                            $style = "#dc2742;";
                            $status = __('Close and merge', 'js-support-ticket');
                        }
                        $ticketviamail = '';
                        if ($ticket->ticketviaemail == 1)
                            $ticketviamail = __('Created via Email', 'js-support-ticket');
                        ?>
                        <div class="js-ticket-row">
                            <div class="js-col-xs-12 js-col-md-12 js-ticket-toparea">
                                <div class="js-ticket-first-left">
                                    <div class="js-ticket-user-img-wrp">
                                        <?php if (in_array('agent',jssupportticket::$_active_addons) && $ticket->staffphoto) { ?>
                                            <img class="js-ticket-staff-img" src="<?php echo jssupportticket::makeUrl(array('jstmod'=>'agent','task'=>'getStaffPhoto','action'=>'jstask','jssupportticketid'=> $ticket->staffid ,'jsstpageid'=>get_the_ID()));?> ">
                                        <?php } else {
                                            echo jsst_get_avatar($ticket->uid);
                                        } ?>
                                    </div>
                                    <div class="js-ticket-ticket-subject">
                                        <div class="js-ticket-data-row">
                                            <?php echo __($ticket->name,"js-support-ticket"); ?>
                                        </div>
                                        <div class="js-ticket-data-row name">
                                            <a class="js-ticket-data-link" href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket','jstlay'=>'ticketdetail','jssupportticketid'=> $ticket->id))); ?>">
                                                <?php echo __($ticket->subject,"js-support-ticket"); ?>
                                            </a>
                                        </div>
                                        <div class="js-ticket-data-row">
                                            <span class="js-ticket-title"><?php echo __($field_array['department'], 'js-support-ticket'). ' : '; ?></span>
                                            <?php echo __($ticket->departmentname,"js-support-ticket"); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-ticket-second-left">
                                    <?php
                                    if ($ticket->ticketviaemail == 1){  ?>
                                        <span class="js-ticket-creade-via-email-spn"><?php echo $ticketviamail; ?></span>
                                    <?php } ?>
                                    <?php
                                    $counter = 'one';
                                    if ($ticket->lock == 1) { ?>
                                        <img class="ticketstatusimage <?php echo $counter;
                                            $counter = 'two'; ?>" src="<?php echo jssupportticket::$_pluginpath . "includes/images/lock.png"; ?>" title="<?php echo __('The ticket is locked', 'js-support-ticket'); ?>" />
                                    <?php } ?>
                                    <?php if ($ticket->isoverdue == 1) { ?>
                                            <img class="ticketstatusimage <?php echo $counter; ?>" src="<?php echo jssupportticket::$_pluginpath . "includes/images/over-due.png"; ?>" title="<?php echo __('The ticket marks as overdue', 'js-support-ticket'); ?>" />
                                    <?php } ?>
                                    <span class="js-ticket-status" style="color:<?php echo $style; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </div>
                                <div class="js-ticket-third-left">
                                    <?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->created)); ?>
                                </div>
                                <div class="js-ticket-fourth-left">
                                    <span class="js-ticket-priorty" style="background:<?php echo $ticket->prioritycolour; ?>;"><?php echo __($ticket->priority, 'js-support-ticket'); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <div>
            <?php
        }
        ?>
    </div>


    <div id="js-ticket-main-black-background" style="display:none;"></div>
    <div id="js-ticket-main-popup" style="display:none;">
        <span id="js-ticket-popup-title"></span>
        <span id="js-ticket-popup-close-button"><img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/close-icon-white.png" /></span>
        <div id="js-ticket-main-content"></div>
        <div id="js-ticket-main-downloadallbtn"></div>
    </div>

    <?php
    // Permission setting for notification
    } else {
        JSSTlayout::getSystemoffline();
    }

    function printMenuLink($title,$url,$image_path){
        $html = '
        <a class="js-col-xs-12 js-col-sm-6 js-col-md-4 js-ticket-dash-menu" href="'.$url.'">
            <span class="js-ticket-dash-menu-icon">
                <img class="js-ticket-dash-menu-img" alt="menu-link-image" src="'.$image_path.'" />
            </span>
            <span class="js-ticket-dash-menu-text">'.$title.'</span>
        </a>';
        echo  $html;
        return;
    }

 ?>

























