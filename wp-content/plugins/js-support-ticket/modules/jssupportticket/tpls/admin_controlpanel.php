<?php
wp_enqueue_script( 'ticket-notify-app', 'https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js' );
wp_enqueue_script( 'ticket-notify-message', 'https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js' );
wp_enqueue_style('status-graph', jssupportticket::$_pluginpath . 'includes/css/status_graph.css');
do_action('ticket-notify-generate-token');
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script>
    google.setOnLoadCallback(drawStackChartHorizontal);
    google.setOnLoadCallback(drawTodayTicketsChart);
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

    function drawTodayTicketsChart() {
      var data = google.visualization.arrayToDataTable([
        <?php
            echo jssupportticket::$_data['today_ticket_chart']['title'].',';
            echo jssupportticket::$_data['today_ticket_chart']['data'];
        ?>
      ]);

      var view = new google.visualization.DataView(data);

      var options = {
        height:130,
        chartArea: { width: '70%', left: 30 },
        legend: { position: "right" },
        hAxis: { textPosition: 'none' },
        colors:<?php echo jssupportticket::$_data['stack_chart_horizontal']['colors']; ?>,
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("today_ticket_chart"));
      chart.draw(view, options);
    }
</script>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">
        <div id="js-main-cp-wrapper">
            <div id="jsstadmin-wrapper-top">
                <div id="jsstadmin-wrapper-top-left">
                    <div id="jsstadmin-breadcrunbs">
                        <ul>
                            <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
                        </ul>
                    </div>
                </div>
                <div id="jsstadmin-wrapper-top-right">
                    <div id="jsstadmin-config-btn">
                        <a href="<?php echo admin_url("admin.php?page=configuration"); ?>" title="<?php echo __('Configuration','js-support-ticket'); ?>">
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
                <h1 class="jsstadmin-head-text">
                    <?php echo __('Dashboard', 'js-support-ticket'); ?>
                </h1>
                <?php if(in_array('agent', jssupportticket::$_active_addons)){ ?>
                    <a href="?page=agent" class="jsstadmin-add-link orange-bg button" title="<?php echo __('Agents', 'js-support-ticket'); ?>">
                        <img alt="<?php echo __('Staff', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/staff-1.png"/>
                        <?php echo __('Agents', 'js-support-ticket'); ?>
                    </a>
                <?php } ?>
                <a href="?page=ticket" class="jsstadmin-add-link button" title="<?php echo __('All Tickets', 'js-support-ticket'); ?>">
                    <img alt="<?php echo __('All Tickets', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/all-tickets.png"/>
                    <?php echo __('All Tickets', 'js-support-ticket'); ?>
                </a>
            </div>

            <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
                <div class="js-cp-cnt-sec">
                    <div class="js-cp-cnt-left">
                        <?php
                            $open_percentage = 0;
                            $close_percentage = 0;
                            $answered_percentage = 0;
                            $pending_percentage = 0;
                            $overdue_percentage = 0;
                            if(isset(jssupportticket::$_data['ticket_total']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
                                $open_percentage = round((jssupportticket::$_data['ticket_total']['openticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                                //$close_percentage = round((jssupportticket::$_data['ticket_total']['closeticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                                $overdue_percentage = round((jssupportticket::$_data['ticket_total']['overdueticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                                $answered_percentage = round((jssupportticket::$_data['ticket_total']['answeredticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                                $pending_percentage = round((jssupportticket::$_data['ticket_total']['pendingticket'] / jssupportticket::$_data['ticket_total']['allticket']) * 100);
                            }
                            if(isset(jssupportticket::$_data['ticket_total']['allticket']) && isset(jssupportticket::$_data['ticket_total']['allticket']) && jssupportticket::$_data['ticket_total']['allticket'] != 0){
                                $allticket_percentage = 100;
                            }
                        ?>
                        <div class="js-ticket-count">
                            <div class="js-ticket-link">
                                <a class="js-ticket-link js-ticket-green" href="#" data-tab-number="1">
                                    <div class="js-ticket-cricle-wrp" data-per="<?php echo $open_percentage; ?>" data-tab-number="1">
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
                                            echo __('New', 'js-support-ticket');
                                            echo ' ( '.jssupportticket::$_data['ticket_total']['openticket'].' )';
                                        ?>
                                    </div>
                                </a>
                            </div>
                            <div class="js-ticket-link">
                                <a class="js-ticket-link js-ticket-brown" href="#" data-tab-number="2">
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
                                            echo ' ( '. jssupportticket::$_data['ticket_total']['answeredticket'].' )';
                                        ?>
                                    </div>
                                </a>
                            </div>
                            <div class="js-ticket-link">
                                <a class="js-ticket-link js-ticket-blue" href="#" data-tab-number="3">
                                    <div class="js-ticket-cricle-wrp" data-per="<?php echo $pending_percentage; ?>">
                                        <div class="js-mr-rp" data-progress="<?php echo $pending_percentage; ?>">
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
                                            echo __('Pending', 'js-support-ticket');
                                            echo ' ( '. jssupportticket::$_data['ticket_total']['pendingticket'].' )';
                                        ?>
                                    </div>
                                </a>
                            </div>
                            <div class="js-ticket-link">
                                <a class="js-ticket-link js-ticket-orange" href="#" data-tab-number="4">
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
                                            echo ' ( '. jssupportticket::$_data['ticket_total']['overdueticket'].' )';
                                        ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="js-cp-cnt">
                            <div class="js-cp-cnt-title">
                                <span class="js-cp-cnt-title-txt">
                                    <?php echo __('Statistics', 'js-support-ticket'); ?>
                                    <?php $curdate = date_i18n('Y-m-d'); $fromdate = date_i18n('Y-m-d', strtotime("now -1 month")); echo " ($fromdate - $curdate)"; ?>
                                </span>
                            </div>
                            <div id="js-pm-grapharea">
                                <div id="stack_chart_horizontal" style="width:100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="js-cp-cnt-right">
                        <div class="js-cp-cnt">
                            <div class="js-cp-cnt-title">
                                <span class="js-cp-cnt-title-txt">
                                    <?php echo __('Today Tickets', 'js-support-ticket'); ?>
                                </span>
                            </div>
                            <div id="js-pm-grapharea">
                                <div id="today_ticket_chart" style="width:100%;"></div>
                            </div>
                        </div>
                        <div class="js-cp-cnt">
                            <div class="js-cp-cnt-title">
                                <span class="js-cp-cnt-title-txt">
                                    <?php echo __('Short Links', 'js-support-ticket'); ?>
                                </span>
                            </div>
                            <div id="js-wrapper-menus">
                                <a title="<?php echo __('Tickets', 'js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=ticket"> <img alt="<?php echo __('Tickets', 'js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/tickets.png"/><div class="jsmenu-text"><?php echo __('Tickets', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Department','js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=department"><img alt="<?php echo __('Department','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/department.png"/><div class="jsmenu-text"><?php echo __('Departments', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Priority','js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=priority"><img alt="<?php echo __('Priority','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/priorities.png"/><div class="jsmenu-text"><?php echo __('Priorities', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Field Ordering','js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=fieldordering&fieldfor=1"><img alt="<?php echo __('Field Ordering','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/fields.png"/><div class="jsmenu-text"><?php echo __('Fields', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Configuration','js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=configuration"><img alt="<?php echo __('Configuration','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/config.png"/><div class="jsmenu-text"><?php echo __('Configurations', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Overall Report','js-support-ticket'); ?>" class="js-admin-menu-link" href="<?php echo admin_url('admin.php?page=reports&jstlay=overallreport'); ?>"><img alt="<?php echo __('Overall Report','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/report.png"/><div class="jsmenu-text"><?php echo __('Overall Statistics','js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Department Reports','js-support-ticket'); ?>" class="js-admin-menu-link" href="<?php echo admin_url('admin.php?page=reports&jstlay=departmentreport'); ?>"><img alt="<?php echo __('Department Reports','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/department-report.png"/><div class="jsmenu-text"><?php echo __('Department Reports','js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('User report','js-support-ticket'); ?>" class="js-admin-menu-link" href="<?php echo admin_url('admin.php?page=reports&jstlay=userreport'); ?>"><img alt="<?php echo __('User report','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/user-reports.png"/><div class="jsmenu-text"><?php echo __('User Reports', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('Email','js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=email"><img alt="<?php echo __('Email','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/system-email.png"/><div class="jsmenu-text"><?php echo __('System Emails', 'js-support-ticket'); ?></div></a>
                                <a title="<?php echo __('email template','js-support-ticket'); ?>" class="js-admin-menu-link" href="?page=emailtemplate"><img alt="<?php echo __('email template','js-support-ticket'); ?>" class="jsmenu-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/email-template.png"/><div class="jsmenu-text"><?php echo __('Email Templates', 'js-support-ticket'); ?></div></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="js-cp-cnt-sec js-cp-baner">
                    <div class="js-cp-baner-cnt">
                        <div class="js-cp-banner-tit-bold">
                            <?php echo __('Install Now','js-support-ticket'); ?>
                        </div>
                        <div class="js-cp-banner-tit">
                            <?php $data = __('Premium Addons List & Features','js-support-ticket');
                            echo $data; ?>
                        </div>
                        <div class="js-cp-banner-desc">
                            <?php echo __('The best support system plugin for WordPress has everything you need.','js-support-ticket'); ?>
                        </div>
                        <div class="js-cp-banner-btn-wrp">
                            <a href="?page=premiumplugin&jstlay=addonfeatures" class="js-cp-banner-btn orange-bg">
                                <?php  $data = __('Add-Ons List','js-support-ticket');
                                echo $data; ?>
                            </a>
                            <a href="?page=premiumplugin&jstlay=step1" class="js-cp-banner-btn">
                                <?php echo __('Add New Addons','js-support-ticket'); ?>
                            </a>
                        </div>
                    </div>
                    <img class="js-cp-baner-img" alt="<?php echo __('addon','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/banner/addon-image.png"/>
                </div>

                <?php
                $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
                ?>
                <div class="js-cp-cnt-sec js-cp-tkt">
                    <div class="js-cp-cnt-title">
                        <span class="js-cp-cnt-title-txt">
                            <?php echo __('Latest Tickets', 'js-support-ticket'); ?>
                        </span>
                        <?php if(count(jssupportticket::$_data['tickets']) > 0){ ?>
                            <a href="?page=ticket" class="js-cp-cnt-title-btn" title="<?php echo __('View All Tickets', 'js-support-ticket'); ?>">
                                <?php echo __('View All Tickets', 'js-support-ticket'); ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="js-ticket-admin-cp-tickets">
                        <?php if(count(jssupportticket::$_data['tickets']) > 0){
                            foreach (jssupportticket::$_data['tickets'] AS $ticket): ?>
                                <div class="js-cp-tkt-list">
                                    <div class="js-cp-tkt-list-left">
                                        <div class="js-cp-tkt-image">
                                            <?php echo jsst_get_avatar($ticket->uid); ?>
                                        </div>
                                        <div class="js-cp-tkt-cnt">
                                            <div class="js-cp-tkt-info name"><?php echo $ticket->name; ?></div>
                                            <div class="js-cp-tkt-info subject" >
                                                <a title="<?php echo __('Subject','js-support-ticket'); ?>" href="?page=ticket&jstlay=ticketdetail&jssupportticketid=<?php echo $ticket->id; ?>"><?php echo $ticket->subject; ?></a>
                                            </div>
                                            <div class="js-cp-tkt-info dept">
                                                <span class="js-cp-tkt-info-label" >
                                                    <?php echo __('Department', 'js-support-ticket'). " : "; ?>
                                                </span>
                                                <?php echo __($ticket->departmentname,'js-support-ticket'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="js-cp-tkt-status">
                                        <?php
                                        if ($ticket->status == 0) {
                                            $style = "#1572e8;";
                                            $status = __('New', 'js-support-ticket');
                                        } elseif ($ticket->status == 1) {
                                            $style = "#ad6002;";
                                            $status = __('Waiting Agent Reply', 'js-support-ticket');
                                        } elseif ($ticket->status == 2) {
                                            $style = "#FF7F50;";
                                            $status = __('In Progress', 'js-support-ticket');
                                        } elseif ($ticket->status == 3) {
                                            $style = "green;";
                                            $status = __('Replied', 'js-support-ticket');
                                        } elseif ($ticket->status == 4) {
                                            $style = "blue;";
                                            $status = __('Closed', 'js-support-ticket');
                                        }
                                        echo '<span style="color:' . $style . '">' . $status . '</span>';
                                        ?>
                                    </div>
                                    <div class="js-cp-tkt-crted"><?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->created)); ?></div>
                                    <div class="js-cp-tkt-prorty">
                                        <span style="background-color:<?php echo $ticket->prioritycolour; ?>;">
                                            <?php echo __($ticket->priority, 'js-support-ticket'); ?>
                                        </span>
                                    </div>
                                </div>
                        <?php
                            endforeach;
                        }else{ ?>
                            <div class="jsst_no_record">
                                <?php echo __("No Record Found","js-support-ticket"); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="js-cp-fed-ad-wrp">
                    <?php if(in_array('tickethistory', jssupportticket::$_active_addons)){ ?>
                    <div class="js-cp-feedback-wrp">
                        <div class="js-cp-cnt-title">
                            <span class="js-cp-cnt-title-txt"><?php echo __('Ticket History', 'js-support-ticket'); ?></span>
                        </div>
                        <div class="js-cp-feedback-list">
                            <?php
                            if(count(jssupportticket::$_data['tickethistory']) > 0){
                                foreach(jssupportticket::$_data['tickethistory'] as $history){
                                    ?>
                                    <div class="js-cp-feedback">
                                        <div class="js-cp-feedback-image">
                                            <?php echo jsst_get_avatar($history->uid, 'js-cp-feedback-img'); ?>
                                        </div>
                                        <div class="js-cp-feedback-cnt">
                                            <div class="js-cp-feedback-row">
                                                <span class="js-cp-feedback-type">
                                                    <?php echo $history->eventtype; ?>
                                                </span>
                                                <span class="js-cp-feedback-crt-date"><?php echo ' - ' .$history->datetime; ?></span>
                                            </div>
                                            <div class="js-cp-feedback-row">
                                                <?php echo $history->message; ?>
                                            </div>
                                            <div class="js-cp-feedback-row">
                                                <span class="js-cp-feedback-tit">
                                                    <?php echo __('Department','js-support-ticket'). ' : ' ; ?>
                                                </span>
                                                <span class="js-cp-feedback-val">
                                                    <?php echo __($history->departmentname,'js-support-ticket'); ?>
                                                </span>
                                            </div>
                                            <div class="js-cp-feedback-row">
                                                <span class="js-cp-feedback-prty" style="background:<?php echo $history->prioritycolour; ?>;">
                                                    <?php echo __($history->priority,'js-support-ticket'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }else{ ?>
                                <div class="jsst_no_record">
                                    <?php echo __("No Record Found","js-support-ticket"); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php /*
                        <div class="js-cp-feedback-btn-wrp">
                            <a href="#" class="js-cp-feedback-btn" title="<?php echo __('view all tickets history', 'js-support-ticket'); ?>">
                                <?php echo __('View All Tickets History','js-support-ticket'); ?>
                            </a>
                        </div> */ ?>
                    </div>
                    <?php } ?>
                    <div class="js-cp-addon-wrp">
                        <div class="js-cp-cnt-title">
                            <span class="js-cp-cnt-title-txt"><?php echo __('Addons', 'js-support-ticket'); ?></span>
                        </div>
                        <div class="js-cp-addon-list">
                            <?php if ( !in_array('agent',jssupportticket::$_active_addons)) { ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Agent','js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/staff.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Agents', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Add agents and assign roles and permissions to provide assistance.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-agent/js-support-ticket-agent.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-agent&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/agents/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if ( !in_array('autoclose',jssupportticket::$_active_addons)) { ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Ticket Auto Close','js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/close-ticket.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Ticket Auto Close', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Define rules for the ticket to auto-close after a specific interval of time.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-autoclose/js-support-ticket-autoclose.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-autoclose&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/close-ticket/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('feedback', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Feedbacks', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/feedback.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Feedbacks', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Get a survey from customers on ticket closing to improve quality.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-feedback/js-support-ticket-feedback.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-feedback&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/feedback/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('helptopic', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Help Topics', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/help-topic.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Help Topics', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Help users to find and select the area with which they need assistance.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-helptopic/js-support-ticket-helptopic.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-helptopic&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/helptopic/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('note', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Private Note', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/note.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Private Note', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('The private note is used as reminders or to give other agents insights into the ticket issue.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-note/js-support-ticket-note.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-note&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/internal-note/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('knowledgebase', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Knowledge Base', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/kb.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Knowledge Base', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Stop losing productivity on repetitive queries, build your knowledge base.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-knowledgebase/js-support-ticket-knowledgebase.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-knowledgebase&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/knowledge-base/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('maxticket', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Max Ticket', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/tickets.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Max Tickets', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Enables admin to set N numbers of tickets for users and agents separately.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-maxticket/js-support-ticket-maxticket.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-maxticket&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/max-ticket/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('mergeticket', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Merge Ticket', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/merge.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Merge Tickets', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Enables agents to merge two tickets of the same user into one instead of dealing with the same issue on many tickets.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-mergeticket/js-support-ticket-mergeticket.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-mergeticket&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/merge-ticket/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('overdue', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Overdue', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/overdue.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Overdue', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Defines rules or set specific intervals of time to make ticket auto overdue.The ticket can overdue by type or overdue by Cronjob.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-overdue/js-support-ticket-overdue.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-overdue&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/overdue/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('smtp', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('SMTP', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/smtp.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('SMTP', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('SMTP enables you to add custom mail protocol to send and receive emails within the js help desk.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-smtp/js-support-ticket-smtp.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-smtp&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/smtp/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('tickethistory', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Ticket History', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/history.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Ticket History', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Displays complete ticket history along with the ticket status, currently assigned user and other actions performed on each ticket.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-tickethistory/js-support-ticket-tickethistory.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-tickethistory&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/ticket-history/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('cannedresponses', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Canned Responses', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/canned-response.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Canned Responses', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Pre-populated messages allow support agents to respond quickly.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-cannedresponses/js-support-ticket-cannedresponses.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-cannedresponses&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/canned-responses/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('emailpiping', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Email Piping', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/email-piping.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Email Piping', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Enables users to reply to the tickets via email without login.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-emailpiping/js-support-ticket-emailpiping.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-emailpiping&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/email-piping/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('timetracking', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Time Tracking', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/time-tracking.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Time Tracking', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Track the time spent on each ticket by each agent and each reply. Report the admin on how much time is spent on each ticket.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-timetracking/js-support-ticket-timetracking.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-timetracking&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/time-tracking/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('useroptions', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('User Options', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/user-options.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('User Options', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('User options enable you to add Google Re-captcha or JS Help Desk Re-captcha for a registration form.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-useroptions/js-support-ticket-useroptions.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-useroptions&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/user-options/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('actions', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Actions', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/actions.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Ticket Actions', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Get multiple action options on each ticket like Print Ticket, Lock Ticket, Transfer ticket, etc.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-actions/js-support-ticket-actions.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-actions&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/actions/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('announcement', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Announcements', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/announcements.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Announcements', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Make unlimited announcements associated with the support system.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-announcement/js-support-ticket-announcement.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-announcement&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/announcements/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('banemail', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Ban Emails', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/ban.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Ban Emails', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('It allows you to block the email of any user to restrict him to create new tickets.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-banemail/js-support-ticket-banemail.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-banemail&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/ban-email/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('notification', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Desktop Notification', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/notification.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Desktop Notification', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('The Desktop notifications will keep you up to date about anything happens on your support system.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-notification/js-support-ticket-notification.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-notification&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/desktop-notification/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('export', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Export', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/export.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Export', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Save the ticket as a PDF in your system and able to export all data.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-export/js-support-ticket-export.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-export&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/export/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('download', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Downloads', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/download.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Downloads', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Create downloads to ensure the user to get downloads from downloads.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-download/js-support-ticket-download.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-download&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/downloads/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('faq', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __("FAQ's", 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/faq.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __("FAQ's", 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Add FAQs to drastically reduce the number of common questions.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-faq/js-support-ticket-faq.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-faq&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/faq/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('themes', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Themes', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/themes.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Themes', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Get multiple themes with a beautiful color scheme to make the site beautiful.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-themes/js-support-ticket-themes.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-themes&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/themes/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('dashboardwidgets', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Dashboard Widgets', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/admin-widget.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Dashboard Widgets', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Get immediate data of your support operations as soon as you log into your WordPress administration area.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-dashboardwidgets/js-support-ticket-dashboardwidgets.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-dashboardwidgets&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/admin-widget/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('mail', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Internal Mail', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/mail.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Internal Mail', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Use an internal email to send emails to one agent to another agent.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-mail/js-support-ticket-mail.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-mail&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/internal-mail/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('widgets', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Front-End Widgets', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/widget.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Front-End Widgets', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Widgets in WordPress allow you to add content and features in the widgetized areas of your theme.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-widgets/js-support-ticket-widgets.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-widgets&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/widget/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('woocommerce', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('WooCommerce', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/woo.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('WooCommerce', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('JS Help Desk WooCommerce provides the much-needed bridge between your WooCommerce store and the JS Help Desk.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-woocommerce/js-support-ticket-woocommerce.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-woocommerce&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/woocommerce/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('privatecredentials', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('Private Credentials', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/private-credentials.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Private Credentials', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Collect your customer\'s private data, sensitive information from credit card to health information and store them encrypted.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-privatecredentials/js-support-ticket-privatecredentials.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-privatecredentials&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/private-credentials/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('envatovalidation', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('envato', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/envatovalidation.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Envato Validation', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Without valid Envato, license clients won\'t be able to open a new ticket.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-envatovalidation/js-support-ticket-envatovalidation.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-envatovalidation&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/envato/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('mailchimp', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('mailchimp', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/mail-chimp.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Mailchimp', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Adds the option to the registration form for prompting new users to subscribe to your email list.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-mailchimp/js-support-ticket-mailchimp.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-mailchimp&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/mail-chimp/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('paidsupport', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('paidsupport', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/paid-support.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Paid Support', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('Paid Support is the easiest way to integrate and manage payments for your tickets.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-paidsupport/js-support-ticket-paidsupport.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-paidsupport&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/paid-support/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if(!in_array('easydigitaldownloads', jssupportticket::$_active_addons)){ ?>
                                <div class="js-cp-addon">
                                    <div class="js-cp-addon-image">
                                        <img alt="<?php echo __('easy digital downloads', 'js-support-ticket'); ?>" class="js-cp-addon-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/addon/easy-digital-download.png"/>
                                    </div>
                                    <div class="js-cp-addon-cnt">
                                        <div class="js-cp-addon-tit">
                                            <?php echo __('Easy Digital Downloads', 'js-support-ticket'); ?>
                                        </div>
                                        <div class="js-cp-addon-desc">
                                            <?php echo __('EDD offers customers to open new tickets just one click from their EDD account.', 'js-support-ticket'); ?>
                                        </div>
                                    </div>
                                    <?php $plugininfo = checkJSSTPluginInfo('js-support-ticket-easydigitaldownloads/js-support-ticket-easydigitaldownloads.php');
                                    if($plugininfo['availability'] == "1"){
                                        $text = $plugininfo['text'];
                                        $url = "plugins.php?s=js-support-ticket-easydigitaldownloads&plugin_status=inactive";
                                    }elseif($plugininfo['availability'] == "0"){
                                        $text = $plugininfo['text'];
                                        $url = "https://jshelpdesk.com/product/easy-digital-download/";
                                    } ?>
                                    <a href="<?php echo $url; ?>" class="js-cp-addon-btn" title="<?php $text; ?>">
                                        <?php echo $text; ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div id="jsreview-banner">
                    <div class="review">
                        <div class="upper">
                            <span class="simple-text">
                                <?php echo __('We\'d love to hear from You.', 'js-support-ticket'); ?>
                                <br>
                                <?php echo __('Please write appreciated review at', 'js-support-ticket'); ?>
                            </span>
                            <a class="review-link" href="https://wordpress.org/support/plugin/js-support-ticket/reviews" target="_blank" title="<?php echo __('WP Extension Directory', 'js-support-ticket'); ?>">
                                <img alt="<?php echo __('star','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/star.png">
                                <?php echo __('WP Extension Directory', 'js-support-ticket'); ?>
                            </a>
                        </div>
                        <div class="lower">
                            <span class="simple-text"><?php echo __('Spread the word', 'js-support-ticket'). ' : ' ; ?></span>
                            <a class="rev-soc-link" href="https://www.facebook.com/joomsky">
                                <img alt="<?php echo __('fb','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/fb.png">
                            </a>
                            <a class="rev-soc-link" href="https://twitter.com/joomsky">
                                <img alt="<?php echo __('twitter','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/review/twitter.png">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="js-other-products-wrp">
                    <div class="js-other-product-title">
                        <?php echo __("Other Products","js-support-ticket"); ?>
                    </div>
                    <div class="js-other-products-detail">
                        <div class="js-other-products-image">
                            <img title="<?php echo __("WP Vehicle Manager","js-support-ticket"); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/otherproducts/vehicle-manager.png">
                            <div class="js-other-products-bottom">
                                <div class="js-product-title"><?php echo __("WP Vehicle Manager","js-support-ticket"); ?></div>
                                <div class="js-product-bottom-btn">
                                    <span class="js-product-view-btn">
                                        <a href="https://wpvehiclemanager.com"  target="_blank" title="<?php echo __("Visit site","js-support-ticket"); ?>"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/otherproducts/new-tab.png"></a>
                                    </span>
                                    <span class="js-product-install-btn">
                                        <?php $plugininfo = checkJSSTPluginInfo('js-vehicle-manager/js-vehicle-manager.php'); ?>
                                        <a title="<?php echo __("Install WP Vehicle Manager Plugin","js-support-ticket"); ?>" class="wp-vehicle-manager-btn-color <?php echo $plugininfo['class']; ?>" data-slug="js-vehicle-manager" <?php echo $plugininfo['disabled']; ?>>
                                            <?php echo __($plugininfo['text'],"js-support-ticket") ?>
                                            <?php ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="js-other-products-image">
                            <img title="<?php echo __("JS Job Manager","js-support-ticket"); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/otherproducts/job.png">
                            <div class="js-other-products-bottom">
                                <div class="js-product-title"><?php echo __("JS Job Manager","js-support-ticket"); ?></div>
                                <div class="js-product-bottom-btn">
                                    <span class="js-product-view-btn">
                                        <a href="https://joomsky.com/products/js-jobs-pro-wp.html"  target="_blank" title="<?php echo __("Visit site","js-support-ticket"); ?>"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/otherproducts/new-tab.png"></a>
                                    </span>
                                    <span class="js-product-install-btn">
                                        <?php $plugininfo = checkJSSTPluginInfo('js-jobs/js-jobs.php'); ?>
                                        <a title="<?php echo __("Install JS Job Manager Plugin","js-support-ticket"); ?>" class="js-jobs-manager-btn-color <?php echo $plugininfo['class']; ?>" data-slug="js-jobs" <?php echo $plugininfo['disabled']; ?>>
                                            <?php echo __($plugininfo['text'],"js-support-ticket") ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="js-other-products-image">
                            <img title="<?php echo __("WP Learn Manager","js-support-ticket"); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/otherproducts/lms.png">
                            <div class="js-other-products-bottom">
                                <div class="js-product-title"><?php echo __("WP Learn Manager","js-support-ticket"); ?></div>
                                <div class="js-product-bottom-btn">
                                    <span class="js-product-view-btn">
                                        <a title="<?php echo __("Visit site","js-support-ticket"); ?>" href="https://wplearnmanager.com" target="_blank"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/admincp/otherproducts/new-tab.png"></a>
                                    </span>
                                    <span class="js-product-install-btn">
                                        <?php $plugininfo = checkJSSTPluginInfo('learn-manager/learn-manager.php'); ?>
                                        <a title="<?php echo __("Install WP Learn Manager Plugin","js-support-ticket"); ?>" class="wp-learn-manager-btn-color <?php echo $plugininfo['class']; ?>" data-slug="learn-manager" <?php echo $plugininfo['disabled']; ?>><?php echo __($plugininfo['text'],"js-support-ticket") ?></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("span.dashboard-icon").find('span.download').hover(function(){
                    jQuery(this).find('span').toggle("slide");
                    }, function(){
                    jQuery(this).find('span').toggle("slide");
                });
            });
        </script>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(document).on('click','a.js-btn-install-now',function(){
            jQuery(this).attr('disabled',true);
            jQuery(this).html('Installing.....!');
            jQuery(this).removeClass('js-btn-install-now');
            var pluginslug = jQuery(this).attr("data-slug");
            var buttonclass = jQuery(this).attr("class");
            jQuery(this).addClass('js-installing-effect');
            if(pluginslug != ''){
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'jssupportticket', task: 'installPluginFromAjax', pluginslug:pluginslug}, function (data) {
                    if(data == 1){
                        jQuery("span.js-product-install-btn a."+buttonclass).attr('disabled',false);
                        jQuery("span.js-product-install-btn a."+buttonclass).html("Active Now");
                        jQuery("span.js-product-install-btn a."+buttonclass).addClass("js-btn-active-now js-btn-green");
                        jQuery("span.js-product-install-btn a."+buttonclass).removeClass("js-installing-effect");
                    }else{
                        jQuery("span.js-product-install-btn a."+buttonclass).attr('disabled',false);
                        jQuery("span.js-product-install-btn a."+buttonclass).html("Please try again");
                        jQuery("span.js-product-install-btn a."+buttonclass).addClass("js-btn-install-now");
                        jQuery("span.js-product-install-btn a."+buttonclass).removeClass("js-installing-effect");
                    }
                });
            }
        });

        jQuery(document).on('click','a.js-btn-active-now',function(){
            jQuery(this).attr('disabled',true);
            jQuery(this).html('Activating.....!');
            jQuery(this).removeClass('js-btn-active-now');
            var pluginslug = jQuery(this).attr("data-slug");
            var buttonclass = jQuery(this).attr("class");
            if(pluginslug != ''){
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'jssupportticket', task: 'activatePluginFromAjax', pluginslug:pluginslug}, function (data) {
                    if(data == 1){
                        jQuery("a[data-slug="+pluginslug+"]").html("Activated");
                        jQuery("a[data-slug="+pluginslug+"]").addClass("js-btn-activated");
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
