<?php
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('status-graph', jssupportticket::$_pluginpath . 'includes/css/status_graph.css');
?>
<script type="text/javascript">
    function resetFrom() {
        var form = jQuery('form#jssupportticketform');
        form.find("input[type=text], input[type=email], input[type=password], textarea").val("");
        form.find('input:checkbox').removeAttr('checked');
        form.find('select').prop('selectedIndex', 0);
        form.find('input[type="radio"]').prop('checked', false);
        document.getElementById('jssupportticketform').submit();
    }
    jQuery(document).ready(function(){
        jQuery('.date,.custom_date').datepicker({dateFormat: 'yy-mm-dd'});
        jQuery('select.js-admin-sort-select').on('change',function(e){
            e.preventDefault();
            var sortby = jQuery(".js-admin-sort-select option:selected").val();
            //alert(sortby);
            jQuery('input#sortby').val(sortby);
            jQuery('form#jssupportticketform').submit();
        });
        jQuery('a.js-admin-sort-btn').on('click',function(e){
            e.preventDefault();
            var sortby = jQuery(".js-admin-sort-select option:selected").val();
            //alert(sortby);
            jQuery('input#sortby').val(sortby);
            jQuery('form#jssupportticketform').submit();
        });
        jQuery('a.js-ticket-link').click(function(e){
            e.preventDefault();
            var list = jQuery(this).attr('data-tab-number');
            jQuery('input#list').val(list);
            jQuery('form#jssupportticketform').submit();
        });
    });

    function setDepartmentFilter( depid ){
        jQuery('#departmentid').val( depid );
        jQuery('form#jssupportticketform').submit();
    }

    function setFromNameFilter( email ){
        jQuery('#email').val( email );
        jQuery('form#jssupportticketform').submit();
    }
</script>
<?php JSSTmessage::getMessage(); ?>
<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php
        if(current_user_can('jsst_support_ticket')){
            JSSTincluder::getClassesInclude('jsstadminsidemenu');
        }
        ?>
    </div>
    <div id="jsstadmin-data">
        <div id="jsstadmin-wrapper-top">
            <div id="jsstadmin-wrapper-top-left">
                <div id="jsstadmin-breadcrunbs">
                    <ul>
                        <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
                        <li><?php echo __('Tickets','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Tickets', 'js-support-ticket'); ?></h1>
            <a title="<?php echo __('Add', 'js-support-ticket'); ?>" class="jsstadmin-add-link button" href="?page=ticket&jstlay=addticket"><img alt="<?php echo __('Add', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/plus-icon.png" /><?php echo __('Create Ticket', 'js-support-ticket'); ?></a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <?php
            $list = JSSTrequest::getVar('list', null, null);
            if($list == null){
                $list = (isset($_SESSION['JSST_list']) && $_SESSION['JSST_list'] != '') ? $_SESSION['JSST_list'] : 1;
            }
            $open = ($list == 1) ? 'active' : '';
            $answered = ($list == 2) ? 'active' : '';
            $overdue = ($list == 3) ? 'active' : '';
            $closed = ($list == 4) ? 'active' : '';
            $alltickets = ($list == 5) ? 'active' : '';
            $field_array = JSSTincluder::getJSModel('fieldordering')->getFieldTitleByFieldfor(1);
            ?>
            <?php
            $open_percentage = 0;
            $close_percentage = 0;
            $overdue_percentage = 0;
            $answered_percentage = 0;
            if(isset(jssupportticket::$_data['count']) && isset(jssupportticket::$_data['count']['allticket']) && jssupportticket::$_data['count']['allticket'] != 0){
                $open_percentage = round((jssupportticket::$_data['count']['openticket'] / jssupportticket::$_data['count']['allticket']) * 100);
                $close_percentage = round((jssupportticket::$_data['count']['closedticket'] / jssupportticket::$_data['count']['allticket']) * 100);
                $overdue_percentage = round((jssupportticket::$_data['count']['overdueticket'] / jssupportticket::$_data['count']['allticket']) * 100);
                $answered_percentage = round((jssupportticket::$_data['count']['answeredticket'] / jssupportticket::$_data['count']['allticket']) * 100);
            }
            if(isset(jssupportticket::$_data['count']) && isset(jssupportticket::$_data['count']['allticket']) && jssupportticket::$_data['count']['allticket'] != 0){
                $allticket_percentage = 100;
            }
            //echo $open_percentage;die();
            ?>
            <div class="js-ticket-count">
                <div class="js-ticket-link">
                    <a class="js-ticket-link <?php echo $open; ?> js-ticket-green" href="#" data-tab-number="1" title="<?php echo __('Open Ticket','js-support-ticket'); ?>">
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
                                if(jssupportticket::$_config['count_on_myticket'] == 1)
                                    echo ' ( '.jssupportticket::$_data['count']['openticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link <?php echo $answered; ?> js-ticket-brown" href="#" data-tab-number="2" title="<?php echo __('answered ticket','js-support-ticket'); ?>">
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
                                if(jssupportticket::$_config['count_on_myticket'] == 1)
                                    echo ' ( '.jssupportticket::$_data['count']['answeredticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link <?php echo $overdue; ?> js-ticket-orange" href="#" data-tab-number="3" title="<?php echo __('overdue ticket','js-support-ticket'); ?>">
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
                                if(jssupportticket::$_config['count_on_myticket'] == 1)
                                    echo ' ( '.jssupportticket::$_data['count']['overdueticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link <?php echo $closed; ?> js-ticket-red" href="#" data-tab-number="4" title="<?php echo __('closed ticket','js-support-ticket'); ?>">
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
                                if(jssupportticket::$_config['count_on_myticket'] == 1)
                                    echo ' ( '.jssupportticket::$_data['count']['closedticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="js-ticket-link">
                    <a class="js-ticket-link <?php echo $alltickets; ?> js-ticket-blue" href="#" data-tab-number="5" title="<?php echo __('All Tickets','js-support-ticket'); ?>">
                        <div class="js-ticket-cricle-wrp" data-per="<?php echo $allticket_percentage; ?>">
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
                                if(jssupportticket::$_config['count_on_myticket'] == 1)
                                    echo ' ( '.jssupportticket::$_data['count']['allticket'].' )';
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <?php
            $uid = JSSTrequest::getVar('uid',null,0);
            if(is_numeric($uid) && $uid){
                $formaction = admin_url("admin.php?page=ticket&jstlay=tickets&uid=".$uid);
            }else{
                $formaction = admin_url("admin.php?page=ticket&jstlay=tickets");
            }
            ?>
            <form class="js-filter-form mt0" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo $formaction; ?>">
                <?php echo JSSTformfield::text('subject', jssupportticket::$_data['filter']['subject'], array('placeholder' => __($field_array['subject'], 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php echo JSSTformfield::text('name', jssupportticket::$_data['filter']['name'], array('placeholder' => __('Ticket Creator Name', 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php echo JSSTformfield::text('email', jssupportticket::$_data['filter']['email'], array('placeholder' => __($field_array['email'], 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php if ( in_array('agent',jssupportticket::$_active_addons)) { ?>
                    <?php echo JSSTformfield::select('staffid', JSSTincluder::getJSModel('agent')->getStaffForCombobox(), jssupportticket::$_data['filter']['staffid'], __('Select Agent','js-support-ticket'), array('class' => 'js-form-select-field')); ?>
                <?php } ?>
                <?php echo JSSTformfield::select('departmentid', JSSTincluder::getJSModel('department')->getDepartmentForCombobox(), jssupportticket::$_data['filter']['departmentid'], __('Select','js-support-ticket').' '.__($field_array['department'],'js-support-ticket'), array('class' => 'js-form-select-field')); ?>
                <?php echo JSSTformfield::select('priority', JSSTincluder::getJSModel('priority')->getPriorityForCombobox(), jssupportticket::$_data['filter']['priority'], __('Select','js-support-ticket').' '.__($field_array['priority'],'js-support-ticket'), array('class' => 'js-form-select-field')); ?>
                <?php echo JSSTformfield::text('datestart', jssupportticket::$_data['filter']['datestart'], array('placeholder' => __('From Date', 'js-support-ticket'), 'class' => 'date js-form-date-field')); ?>
                <?php echo JSSTformfield::text('dateend', jssupportticket::$_data['filter']['dateend'], array('placeholder' => __('To Date', 'js-support-ticket'), 'class' => 'date js-form-date-field')); ?>
                <?php echo JSSTformfield::text('ticketid', jssupportticket::$_data['filter']['ticketid'], array('placeholder' => __('Ticket ID', 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php if(class_exists('WooCommerce') && in_array('woocommerce', jssupportticket::$_active_addons)){  ?>
                    <?php echo JSSTformfield::text('orderid', jssupportticket::$_data['filter']['orderid'], array('placeholder' => __($field_array['wcorderid'], 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php } ?>
                <?php echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH'); ?>
                <?php echo JSSTformfield::hidden('sortby', jssupportticket::$_data['filter']['sortby']); ?>
                <?php echo JSSTformfield::hidden('list', $list); ?>

                <?php
                    $customfields = JSSTincluder::getObjectClass('customfields')->userFieldsForSearch(1);
                    foreach ($customfields as $field) {
                        JSSTincluder::getObjectClass('customfields')->formCustomFieldsForSearch($field, $k, 1);
                    }
                ?>
                <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
                <?php echo JSSTformfield::button(__('Reset', 'js-support-ticket'), __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
            </form>
            <?php
            $link = '?page=ticket';
            if (jssupportticket::$_sortorder == 'ASC')
                $img = "sorting-white-1.png";
            else
                $img = "sorting-white-2.png";
            ?>
            <div class="js-admin-heading">
                <div class="js-admin-head-txt"><?php echo __('All Tickets', 'js-support-ticket'); ?></div>
                <div class="js-admin-sorting">
                    <select class="js-admin-sort-select">
                        <?php echo __($field_array['subject'], 'js-support-ticket'); ?>
                        <option value="<?php echo jssupportticket::$_sortlinks['subject']; ?>" <?php if (jssupportticket::$_sorton == 'subject') echo 'selected' ?>><?php echo __("Subject",'js-support-ticket'); ?></option>
                        <option value="<?php echo jssupportticket::$_sortlinks['priority']; ?>"  <?php if (jssupportticket::$_sorton == 'priority') echo 'selected' ?>><?php echo __("Priority",'js-support-ticket'); ?></option>
                        <option value="<?php echo jssupportticket::$_sortlinks['ticketid']; ?>"  <?php if (jssupportticket::$_sorton == 'ticketid') echo 'selected' ?>><?php echo __("Ticket ID",'js-support-ticket'); ?></option>
                        <option value="<?php echo jssupportticket::$_sortlinks['isanswered']; ?>"  <?php if (jssupportticket::$_sorton == 'isanswered') echo 'selected' ?>><?php echo __("Answered",'js-support-ticket'); ?></option>
                        <option value="<?php echo jssupportticket::$_sortlinks['status']; ?>"  <?php if (jssupportticket::$_sorton == 'status') echo 'selected' ?>><?php echo __("Status",'js-support-ticket'); ?></option>
                        <option value="<?php echo jssupportticket::$_sortlinks['created']; ?>"  <?php if (jssupportticket::$_sorton == 'created') echo 'selected' ?>><?php echo __("Created",'js-support-ticket'); ?></option>
                    </select>
                    <a href="#" class="js-admin-sort-btn" title="<?php echo __('sort','js-support-ticket'); ?>">
                        <img alt="<?php echo __('sort','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/' . $img ?>">
                    </a>
                </div>
            </div>
            <?php
            if (!empty(jssupportticket::$_data[0])) {
                ?>
                <!-- Tabs Area -->
                <?php
                foreach (jssupportticket::$_data[0] AS $ticket) {
                    if ($ticket->status == 0) {
                        $style = "#1572e8;";
                        $status = __('New', 'js-support-ticket');
                    } elseif ($ticket->status == 1) {
                        $style = "#ba8a51;";
                        $status = __('Waiting Reply', 'js-support-ticket');
                    } elseif ($ticket->status == 2) {
                        $style = "#FE7C2C;";
                        $status = __('In Progress', 'js-support-ticket');
                    } elseif ($ticket->status == 3) {
                        $style = "#4a836f;";
                        $status = __('Replied', 'js-support-ticket');
                    } elseif ($ticket->status == 4) {
                        $style = "#e92d3e;";
                        $status = __('Closed', 'js-support-ticket');
                    } elseif ($ticket->status == 5) {
                        $style = "#F04646;";
                        $status = __('Close due to merge', 'js-support-ticket');
                    }
                    $ticketviamail = '';
                    if ($ticket->ticketviaemail == 1)
                        $ticketviamail = __('Created via Email', 'js-support-ticket');
                    ?>
                    <div class="js-ticket-wrapper">
                        <div class="js-ticket-toparea">
                            <div class="js-ticket-pic">
                                <?php echo jsst_get_avatar($ticket->uid); ?>
                                <?php /*if (in_array('agent',jssupportticket::$_active_addons) && $ticket->staffphoto) { ?>
                                    <img alt="<?php echo __('Staff','js-support-ticket'); ?>" src="<?php echo admin_url('?page=agent&action=jstask&task=getStaffPhoto&jssupportticketid='.$ticket->staffid); ?>">
                                <?php } else {
                                    echo jsst_get_avatar($ticket->uid);
                                }*/ ?>
                            </div>
                            <div class="js-ticket-data">
                                <div class="js-ticket-left">
                                    <div class="js-ticket-data-row">
                                        <span class="js-ticket-user" style="cursor:pointer;" onClick="setFromNameFilter('<?php echo $ticket->email; ?>');"><?php echo $ticket->name; ?></span>
                                    </div>
                                    <div class="js-ticket-data-row">
                                        <a title="<?php echo __('Subject','js-support-ticket'); ?>" class="js-ticket-det-link" href="?page=ticket&jstlay=ticketdetail&jssupportticketid=<?php echo $ticket->id; ?>"><?php echo $ticket->subject; ?></a>
                                    </div>
                                    <div class="js-ticket-data-row">
                                        <div class="js-ticket-data-row-rec">
                                            <span class="js-ticket-title"><?php echo __($field_array['department'], 'js-support-ticket'); ?>&nbsp;:&nbsp;</span>
                                            <span class="js-ticket-value" style="cursor:pointer;" onClick="setDepartmentFilter('<?php echo $ticket->departmentid; ?>');"><?php echo __($ticket->departmentname,'js-support-ticket'); ?></span>
                                        </div>
                                    </div>
                                    <?php
                                        //jssupportticket::$_data['ticketid'] = $ticket->id;
                                        jssupportticket::$_data['custom']['ticketid'] = $ticket->id;
                                        $customfields = JSSTincluder::getObjectClass('customfields')->userFieldsData(1, 1);
                                        foreach ($customfields as $field) {
                                            $ret = JSSTincluder::getObjectClass('customfields')->showCustomFields($field,1, $ticket->params);
                                            ?>
                                            <div class="js-ticket-data-row js-tkt-custm-flds-wrp">
                                                <div class="js-ticket-data-row-rec">
                                                    <span class="js-ticket-title"><?php echo $ret['title']; ?>&nbsp;:&nbsp;</span>
                                                    <span class="js-ticket-value" style="cursor:pointer;"><?php echo $ret['value']; ?></span>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="js-ticket-right">

                                    <span class="js-ticket-value js-ticket-creade-via-email-spn"><?php echo $ticketviamail; ?></span>
                                    <?php
                                    $counter = 'one';
                                    if ($ticket->lock == 1) { ?>
                                        <img class="ticketstatusimage <?php echo $counter; $counter = 'two'; ?>" src="<?php echo jssupportticket::$_pluginpath . "includes/images/lock.png"; ?>" alt="<?php echo __('The ticket is locked', 'js-support-ticket'); ?>" title="<?php echo __('The ticket is locked', 'js-support-ticket'); ?>" />
                                    <?php } ?>
                                    <?php if ($ticket->isoverdue == 1) { ?>
                                        <img class="ticketstatusimage <?php echo $counter; ?>" src="<?php echo jssupportticket::$_pluginpath . "includes/images/over-due.png"; ?>" alt="<?php echo __('The ticket marks as overdue', 'js-support-ticket'); ?>" title="<?php echo __('The ticket marks as overdue', 'js-support-ticket'); ?>" />
                                    <?php } ?>
                                    <span class="js-ticket-status" style="color:<?php echo $style; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                    <span class="js-ticket-priority js-ticket-wrapper-textcolor" style="background:<?php echo $ticket->prioritycolour; ?>;"><?php echo __($ticket->priority, 'js-support-ticket'); ?></span>
                                    <div class="js-ticket-data1">
                                        <div class="js-ticket-data1-row">
                                            <div class="js-ticket-data1-title"><?php echo __('Ticket ID', 'js-support-ticket').':'; ?></div>
                                            <div class="js-ticket-data1-value"><?php echo $ticket->ticketid; ?></div>
                                        </div>
                                        <?php if (empty($ticket->lastreply) || $ticket->lastreply == '0000-00-00 00:00:00') { ?>
                                        <div class="js-ticket-data1-row">
                                            <div class="js-ticket-data1-title"><?php echo __('Created', 'js-support-ticket').':'; ?></div>
                                            <div class="js-ticket-data1-value"><?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->created)); ?></div>
                                        </div>
                                        <?php } else { ?>
                                        <div class="js-ticket-data1-row">
                                            <div class="js-ticket-data1-title"><?php echo __('Last Reply', 'js-support-ticket').':'; ?></div>
                                            <div class="js-ticket-data1-value"><?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->lastreply)); ?></div>
                                        </div>
                                        <?php } ?>
                                        <?php /*
                                        <div class="js-ticket-data1-row">
                                            <div class="js-ticket-data1-title"><?php echo __($field_array['priority'], 'js-support-ticket'); ?></div>
                                            <div class="js-ticket-data1-value js-ticket-wrapper-textcolor" style="background:<?php echo $ticket->prioritycolour; ?>;"><?php echo __($ticket->priority, 'js-support-ticket'); ?></div>
                                        </div> */ ?>
                                        <?php if (in_array('agent',jssupportticket::$_active_addons)) { ?>
                                            <div class="js-ticket-data1-row">
                                                <div class="js-ticket-data1-title"><?php echo __($field_array['assignto'], 'js-support-ticket'); ?></div>
                                                <div class="js-ticket-data1-value"><?php echo $ticket->staffname; ?></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-ticket-bottom-data-part">
                            <?php /*<span class="js-ticket-created"><?php echo __('Created', 'js-support-ticket'); ?>&nbsp;:&nbsp;<?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($ticket->created)); ?></span>*/ ?>
                            <div class="js-ticket-datapart-buttons-action">
                                <a class="js-ticket-datapart-action-btn button" title="<?php echo __('Edit Ticket', 'js-support-ticket'); ?>" href="?page=ticket&jstlay=addticket&jssupportticketid=<?php echo $ticket->id; ?>"><img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/edit-2.png" /><?php echo __('Edit Ticket', 'js-support-ticket'); ?></a>
                                <a class="js-ticket-datapart-action-btn button" title="<?php echo __('Delete Ticket', 'js-support-ticket'); ?>"  onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=ticket&task=deleteticket&action=jstask&ticketid='.$ticket->id,'delete-ticket');?>">
                                    <img alt="<?php echo __('Delete', 'js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete-2.png" />
                                    <?php echo __('Delete Ticket', 'js-support-ticket'); ?></a>
                                <a title="<?php echo __('Enforce delete', 'js-support-ticket'); ?>" class="js-ticket-datapart-action-btn button"  onclick="return confirm('<?php echo __('Are you sure to enforce delete', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=ticket&task=enforcedeleteticket&action=jstask&ticketid='.$ticket->id,'enforce-delete-ticket')?>"><img src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/forced-delete.png" alt="<?php echo __('Enforce delete', 'js-support-ticket'); ?>" /><?php echo __('Enforce delete', 'js-support-ticket'); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if (jssupportticket::$_data[1]) {
                    echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
                }
            } else {
                JSSTlayout::getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
