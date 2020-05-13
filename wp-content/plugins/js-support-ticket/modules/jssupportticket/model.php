<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTjssupportticketModel {

    function getControlPanelData() {

        //determine user
        $user_is = 'unknown';
        if(!is_user_logged_in()){
            $user_is = 'visitor';
        }else{
            if(in_array('agent', jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff()){
                $user_is = 'agent';
            }else{
                $user_is = 'user';
            }
        }
        //check if any addon is installed
        $addon_are_installed = !empty(jssupportticket::$_active_addons) ? true : false;

        if( $user_is == 'agent' ){

            $uid = get_current_user_id();
            $staffid = JSSTincluder::getJSModel('agent')->getStaffId($uid);

            $tickets = $this->getAgentLatestTicketsForCp($staffid);
            if($tickets){
                jssupportticket::$_data[0]['agent-tickets'] = $tickets;
            }

            $ticketStats = $this->getAgentTicketStats($staffid);
            if($ticketStats){
                jssupportticket::$_data[0]['count'] = $ticketStats;
            }

            //data for graph
            $this->getAgentCpChartData($staffid);

        }

        if( $user_is == 'user' ){
            $uid = get_current_user_id();

            $tickets = $this->getUserLatestTicketsForCp($uid);
            if($tickets){
                jssupportticket::$_data[0]['user-tickets'] = $tickets;
            }

            $ticketStats = $this->getUserTicketStats($uid);

            if($ticketStats){
                jssupportticket::$_data[0]['count'] = $ticketStats;
            }
        }

        if( ( $user_is == 'user' || $user_is == 'visitor' ) && $addon_are_installed ){

            $downloads = $this->getLatestDownloadsForCp();
            if($downloads){
                jssupportticket::$_data[0]['latest-downloads'] = $downloads;
            }

            $announcements = $this->getLatestAnnouncementsForCp();
            if($announcements){
                jssupportticket::$_data[0]['latest-announcements'] = $announcements;
            }

            $articles = $this->getLatestArticlesForCp();
            if($articles){
                jssupportticket::$_data[0]['latest-articles'] = $articles;
            }

            $faqs = $this->getLatestFaqsForCp();
            if($faqs){
                jssupportticket::$_data[0]['latest-faqs'] = $faqs;
            }
        }
    }

    function getControlPanelDataAdmin(){
        $curdate = date_i18n('Y-m-d');
        $fromdate = date_i18n('Y-m-d', strtotime("now -1 month"));

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply = '') AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."' ) AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $openticket_pr = jssupportticket::$_db->get_results($query);

        $query = "SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets`";
        $allticket_pr = jssupportticket::$_db->get_var($query);

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $answeredticket_pr = jssupportticket::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND isoverdue = 1 AND status != 4 AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $overdueticket_pr = jssupportticket::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id  AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '') AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $pendingticket_pr = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['stack_chart_horizontal']['title'] = "['".__('Priority','js-support-ticket')."','".__('Overdue','js-support-ticket')."','".__('Pending','js-support-ticket')."','".__('Answered','js-support-ticket')."','".__('New','js-support-ticket')."']";
        jssupportticket::$_data['stack_chart_horizontal']['data'] = "";

        foreach($overdueticket_pr AS $index => $pr){
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= "[";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= "'".__($pr->priority,'js-support-ticket')."',";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $overdueticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $pendingticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $answeredticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $openticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= "],";
        }

        //To show priority colors on chart
        $query = "SELECT prioritycolour FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` ORDER BY priority ";
        $jsonColorList = "[";
        foreach(jssupportticket::$_db->get_results($query) as $priority){
            $jsonColorList.= "'".$priority->prioritycolour."',";
        }
        $jsonColorList .= "]";
        jssupportticket::$_data['stack_chart_horizontal']['colors'] = $jsonColorList;
        //end priority colors

        jssupportticket::$_data['ticket_total']['allticket'] = $allticket_pr;
        jssupportticket::$_data['ticket_total']['openticket'] = 0;
        jssupportticket::$_data['ticket_total']['overdueticket'] = 0;
        jssupportticket::$_data['ticket_total']['pendingticket'] = 0;
        jssupportticket::$_data['ticket_total']['answeredticket'] = 0;

        $count = count($openticket_pr);
        for($i = 0;$i < $count; $i++){
            jssupportticket::$_data['ticket_total']['openticket'] += $openticket_pr[$i]->totalticket;
            jssupportticket::$_data['ticket_total']['overdueticket'] += $overdueticket_pr[$i]->totalticket;
            jssupportticket::$_data['ticket_total']['pendingticket'] += $pendingticket_pr[$i]->totalticket;
            jssupportticket::$_data['ticket_total']['answeredticket'] += $answeredticket_pr[$i]->totalticket;
        }

        do_action('jsst_staff_admin_cp_query');

        $query = "SELECT ticket.id,ticket.ticketid,ticket.subject,ticket.name,ticket.created,priority.priority,priority.prioritycolour,ticket.status,department.departmentname,ticket.uid".jssupportticket::$_addon_query['select']."
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
                    JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON priority.id = ticket.priorityid
                    LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
                    ".jssupportticket::$_addon_query['join']."
                    ORDER BY ticket.status ASC, ticket.created DESC LIMIT 0, 10";
        jssupportticket::$_data['tickets'] = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['version'] = jssupportticket::$_config['versioncode'];

        //today tickets for chart
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND date(created) = '".$curdate."')  AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $priorities = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['today_ticket_chart']['title'] = "['".__('Priority','js-support-ticket')."',";
        jssupportticket::$_data['today_ticket_chart']['data'] = "['',";
        foreach($priorities AS $pr){
            jssupportticket::$_data['today_ticket_chart']['title'] .= "'".__($pr->priority,'js-support-ticket')."',";
            jssupportticket::$_data['today_ticket_chart']['data'] .= $pr->totalticket.",";
        }
        jssupportticket::$_data['today_ticket_chart']['title'] .= "]";
        jssupportticket::$_data['today_ticket_chart']['data'] .= "]";

        //Ticket Hisotry
        if(in_array('tickethistory', jssupportticket::$_active_addons)){
            $query = "SELECT al.id,al.message,al.datetime,al.uid,al.eventtype,pr.priority,pr.prioritycolour,dp.departmentname
            FROM `" . jssupportticket::$_db->prefix . "js_ticket_activity_log`  AS al
            JOIN `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS tic ON al.referenceid=tic.id
            JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS pr ON pr.id = tic.priorityid
            LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS dp ON dp.id = tic.departmentid
            WHERE al.eventfor=1 ORDER BY al.datetime DESC LIMIT 10 ";
            jssupportticket::$_data['tickethistory'] = jssupportticket::$_db->get_results($query);
        }
    }

    function getAgentLatestTicketsForCp($staffid){
        if(!is_numeric($staffid)){
            return false;
        }

        $allowed = JSSTincluder::getJSModel('userpermissions')->checkPermissionGrantedForTask('All Tickets');
        if($allowed == true){
            $agent_conditions = "1 = 1";
        }else{
            $agent_conditions = "ticket.staffid = $staffid OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . jssupportticket::$_db->prefix . "js_ticket_acl_user_access_departments` AS dept WHERE dept.staffid = $staffid)";
        }

        //latest tickets
        $query = "SELECT DISTINCT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,
        priority.prioritycolour AS prioritycolour,staff.photo AS staffphoto,staff.id AS staffid,
        assignstaff.firstname AS staffname
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff ON staff.uid = ticket.uid
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS assignstaff ON ticket.staffid = assignstaff.id
        WHERE (".$agent_conditions.") ORDER BY ticket.created DESC LIMIT 3 ";
        $tickets = jssupportticket::$_db->get_results($query);
        return $tickets;
    }

    function getAgentTicketStats($staffid){
        if(!is_numeric($staffid) || jssupportticket::$_config['count_on_myticket'] != 1){
            return false;
        }

        $result = array();

        $allowed = JSSTincluder::getJSModel('userpermissions')->checkPermissionGrantedForTask('All Tickets');
        if($allowed == true){
            $agent_conditions = "1 = 1";
        }else{
            $agent_conditions = "ticket.staffid = $staffid OR ticket.departmentid IN (SELECT dept.departmentid FROM `" . jssupportticket::$_db->prefix . "js_ticket_acl_user_access_departments` AS dept WHERE dept.staffid = $staffid)";
        }

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND (ticket.status != 4 AND ticket.status !=5) ";
        $result['openticket'] = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND ticket.status = 3 ";
        $result['answeredticket'] = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND (ticket.status = 4 OR ticket.status = 5) ";
        $result['closedticket'] = jssupportticket::$_db->get_var($query);


        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.") AND ticket.isoverdue = 1 ";
        $result['overdue'] = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        WHERE (".$agent_conditions.")  ";
        $result['allticket'] = jssupportticket::$_db->get_var($query);

        return $result;
    }

    function getAgentCpChartData($staffid){
        if(!is_numeric($staffid) || jssupportticket::$_config['cplink_ticketstats_staff'] != 1){
            return false;
        }

        $curdate = date_i18n('Y-m-d');
        $fromdate = date_i18n('Y-m-d', strtotime("now -1 month"));

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply = '') AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."' ) AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $openticket_pr = jssupportticket::$_db->get_results($query);

        $query = "SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets`";
        $allticket_pr = jssupportticket::$_db->get_var($query);

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $answeredticket_pr = jssupportticket::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id AND isoverdue = 1 AND status != 4 AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $overdueticket_pr = jssupportticket::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE priorityid = priority.id  AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '') AND date(created) >= '".$fromdate."' AND date(created) <= '".$curdate."') AS totalticket
                    FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` AS priority ORDER BY priority.priority";
        $pendingticket_pr = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['stack_chart_horizontal']['title'] = "['".__('Priority','js-support-ticket')."','".__('Overdue','js-support-ticket')."','".__('Pending','js-support-ticket')."','".__('Answered','js-support-ticket')."','".__('New','js-support-ticket')."']";
        jssupportticket::$_data['stack_chart_horizontal']['data'] = "";

        foreach($overdueticket_pr AS $index => $pr){
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= "[";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= "'".__($pr->priority,'js-support-ticket')."',";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $overdueticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $pendingticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $answeredticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $openticket_pr[$index]->totalticket.",";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= "],";
        }
    }

    function getUserLatestTicketsForCp($uid){
        if(!is_numeric($uid)){
            return false;
        }
        do_action('jsst_addon_user_cp_tickets');
        $query = "SELECT ticket.*,department.departmentname AS departmentname ,priority.priority AS priority,priority.prioritycolour AS prioritycolour ".jssupportticket::$_addon_query['select']."
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON     ticket.departmentid = department.id
        ".jssupportticket::$_addon_query['join'];
        $query .= " WHERE ticket.uid = " . $uid;
        $query .= " ORDER BY ticket.created DESC LIMIT 3";
        $tickets = jssupportticket::$_db->get_results($query);
        do_action('reset_jsst_aadon_query');
        return $tickets;
    }

    function getUserTicketStats($uid){
        if(!is_numeric($uid) || jssupportticket::$_config['count_on_myticket'] != 1){
            return false;
        }

        $result = array();

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        WHERE ticket.uid = $uid AND (ticket.status != 4 AND ticket.status != 5)";
        $result['openticket'] = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        WHERE ticket.uid = $uid AND ticket.status = 3 ";
        $result['answeredticket'] = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        WHERE ticket.uid = $uid AND (ticket.status = 4 OR ticket.status = 5)";
        $result['closedticket'] = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(ticket.id)
        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
        LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON ticket.departmentid = department.id
        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON ticket.priorityid = priority.id
        WHERE ticket.uid = $uid";
        $result['allticket'] = jssupportticket::$_db->get_var($query);

        return $result;
    }

    function getLatestDownloadsForCp(){
        if( in_array('download', jssupportticket::$_active_addons) ){
            $query = "SELECT download.title, download.id AS downloadid
            FROM `" . jssupportticket::$_db->prefix . "js_ticket_downloads` AS download
            WHERE download.status = 1 ORDER BY download.created DESC LIMIT 4";
            return jssupportticket::$_db->get_results($query);
        }
        return false;
    }

    function getLatestAnnouncementsForCp(){
        if( in_array('announcement', jssupportticket::$_active_addons) ){
            $query = "SELECT announcement.id, announcement.title
            FROM `" . jssupportticket::$_db->prefix . "js_ticket_announcements` AS announcement
            WHERE announcement.status = 1 ORDER BY announcement.created DESC LIMIT 4";
            return jssupportticket::$_db->get_results($query);
        }
        return false;
    }


    function getLatestArticlesForCp(){
        if( in_array('knowledgebase', jssupportticket::$_active_addons) ){
            $query = "SELECT article.subject,article.content, article.id AS articleid
            FROM `" . jssupportticket::$_db->prefix . "js_ticket_articles` AS article
            WHERE article.status = 1 ORDER BY article.created DESC LIMIT 4";
            return jssupportticket::$_db->get_results($query);
        }
        return false;
    }

    function getLatestFaqsForCp(){
        if( in_array('faq', jssupportticket::$_active_addons) ){
            $query = "SELECT faq.id, faq.subject, faq.content
            FROM `" . jssupportticket::$_db->prefix . "js_ticket_faqs` AS faq
            WHERE faq.status = 1 ORDER BY faq.created DESC LIMIT 4";
            return jssupportticket::$_db->get_results($query);
        }
        return false;
    }


    function getStaffControlPanelData() {

        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` ";
        $allticket = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply = '')";
        $openticket = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE status = 4";
        $closeticket = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE isanswered = 1 AND status != 4 AND status != 0";
        $answeredticket = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE isoverdue = 1 AND status != 4";
        $overdueticket = jssupportticket::$_db->get_var($query);

        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '')";
        $pendingticket = jssupportticket::$_db->get_var($query);

        jssupportticket::$_data['ticket_total']['allticket'] = $allticket;
        jssupportticket::$_data['ticket_total']['openticket'] = $openticket;
        jssupportticket::$_data['ticket_total']['closeticket'] = $closeticket;
        jssupportticket::$_data['ticket_total']['answeredticket'] = $answeredticket;
        jssupportticket::$_data['ticket_total']['overdueticket'] = $overdueticket;
        jssupportticket::$_data['ticket_total']['pendingticket'] = $pendingticket;

        $query = "SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_tickets`";
        jssupportticket::$_data['total_tickets']['total_ticket'] = jssupportticket::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_departments`";
        jssupportticket::$_data['total_tickets']['total_department'] = jssupportticket::$_db->get_var($query);

        if(in_array('agent', jssupportticket::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_staff`";
            jssupportticket::$_data['total_tickets']['total_staff'] = jssupportticket::$_db->get_var($query);
        }else{
            jssupportticket::$_data['total_tickets']['total_staff'] = 0;
        }
        if(in_array('feedback', jssupportticket::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `".jssupportticket::$_db->prefix."js_ticket_feedbacks`";
            jssupportticket::$_data['total_tickets']['total_feedback'] = jssupportticket::$_db->get_var($query);
        }else{
            jssupportticket::$_data['total_tickets']['total_feedback'] = 0;
        }
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die(__('Cannot open file', 'js-support-ticket'));
            fclose($ourFileHandle);
        }
    }

    function checkExtension($filename) {
        $i = strrpos($filename, ".");
        if (!$i)
            return 'N';
        $l = strlen($filename) - $i;
        $ext = substr($filename, $i + 1, $l);
        $extensions = explode(",", jssupportticket::$_config['file_extension']);
        $match = 'N';
        foreach ($extensions as $extension) {
            if (strtolower($extension) == strtolower($ext)) {
                $match = 'Y';
                break;
            }
        }
        return $match;
    }

    function storeTheme($data) {
        $filepath = jssupportticket::$_path . 'includes/css/style.php';
        $filestring = file_get_contents($filepath);
        $this->replaceString($filestring, 1, $data);
        $this->replaceString($filestring, 2, $data);
        $this->replaceString($filestring, 3, $data);
        $this->replaceString($filestring, 4, $data);
        $this->replaceString($filestring, 5, $data);
        $this->replaceString($filestring, 6, $data);
        $this->replaceString($filestring, 7, $data);
        if (file_put_contents($filepath, $filestring)) {
            JSSTmessage::setMessage(__('The new theme has been applied', 'js-support-ticket'), 'updated');
        } else {
            JSSTmessage::setMessage(__('Error applying the new theme', 'js-support-ticket'), 'error');
        }
        return;
    }

    function replaceString(&$filestring, $colorNo, $data) {
        if (strstr($filestring, '$color' . $colorNo)) {
            $path1 = strpos($filestring, '$color' . $colorNo);
            $path2 = strpos($filestring, ';', $path1);
            $filestring = substr_replace($filestring, '$color' . $colorNo . ' = "' . $data['color' . $colorNo] . '";', $path1, $path2 - $path1 + 1);
        }
    }

    function getColorCode($filestring, $colorNo) {
        if (strstr($filestring, '$color' . $colorNo)) {
            $path1 = strpos($filestring, '$color' . $colorNo);
            $path1 = strpos($filestring, '#', $path1);
            $path2 = strpos($filestring, ';', $path1);
            $colorcode = substr($filestring, $path1, $path2 - $path1 - 1);
            return $colorcode;
        }
    }

    function getCurrentTheme() {
        $filepath = jssupportticket::$_path . 'includes/css/style.php';
        $filestring = file_get_contents($filepath);
        $theme['color1'] = $this->getColorCode($filestring, 1);
        $theme['color2'] = $this->getColorCode($filestring, 2);
        $theme['color3'] = $this->getColorCode($filestring, 3);
        $theme['color4'] = $this->getColorCode($filestring, 4);
        $theme['color5'] = $this->getColorCode($filestring, 5);
        $theme['color6'] = $this->getColorCode($filestring, 6);
        $theme['color7'] = $this->getColorCode($filestring, 7);
        $theme = apply_filters('cm_theme_colors', $theme, 'js-support-ticket');
        jssupportticket::$_data[0] = $theme;
        return;
    }
    //translation code
        function getListTranslations() {

        $result = array();
        $result['error'] = false;

        $path = jssupportticket::$_path.'languages';

        if( ! is_writeable($path)){
            $result['error'] = __('Dir is not writable','js-support-ticket').' '.$path;

        }else{

            if($this->isConnected()){

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $post_data['product'] ='js-support-ticket-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = jssupportticket::$_config['producttype'];
                $post_data['productcode'] = 'jsticket';
                $post_data['productversion'] = jssupportticket::$_config['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['method'] = 'getTranslations';

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $call_result = $response['body'];
                }else{
                    $call_result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                    }else{
                        $error = $response->get_error_message();
                    }
                }

                $result['data'] = $call_result;
                if(!$call_result){
                    $result['error'] = $error;
                }

            }else{
                $result['error'] = __('Unable to connect to the server','js-support-ticket');
            }
        }

        $result = json_encode($result);

        return $result;
    }

    function makeLanguageCode($lang_name){
        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];
        $match = false;
        if(array_key_exists($lang_name, $langarray)){
            $lang_name = $lang_name;
            $match = true;
        }else{
            $m_lang = '';
            foreach($langarray AS $k => $v){
                if($lang_name{0}.$lang_name{1} == $k{0}.$k{1}){
                    $m_lang .= $k.', ';
                }
            }

            if($m_lang != ''){
                $m_lang = substr($m_lang, 0,strlen($m_lang) - 2);
                $lang_name = $m_lang;
                $match = 2;
            }else{
                $lang_name = $lang_name;
                $match = false;
            }
        }

        return array('match' => $match , 'lang_name' => $lang_name);
    }

    function validateAndShowDownloadFileName( ){
        $lang_name = JSSTrequest::getVar('langname');
        if($lang_name == '') return '';
        $result = array();
        $f_result = $this->makeLanguageCode($lang_name);
        $path = jssupportticket::$_path.'languages';
        $result['error'] = false;
        if($f_result['match'] === false){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','js-support-ticket');
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writable','js-support-ticket').': '.$path;
        }else{
            $result['input'] = '<input id="languagecode" class="text_area" type="text" value="'.$lang_name.'" name="languagecode">';
            if($f_result['match'] === 2){
                $result['input'] .= '<div id="js-emessage-wrapper-other" style="display:block;margin:20px 0px 20px;">';
                $result['input'] .= __('Required language is not installed but similar language like','js-support-ticket').': "<b>'.$f_result['lang_name'].'</b>" '.__('is found in your system','js-support-ticket');
                $result['input'] .= '</div>';

            }
            $result['path'] = __('Language code','js-support-ticket');
        }
        $result = json_encode($result);
        return $result;
    }

    function getLanguageTranslation(){

        $lang_name = JSSTrequest::getVar('langname');
        $language_code = JSSTrequest::getVar('filename');

        $result = array();
        $result['error'] = false;
        $path = jssupportticket::$_path.'languages';

        if($lang_name == '' || $language_code == ''){
            $result['error'] = __('Empty values','js-support-ticket');
            return json_encode($result);
        }

        $final_path = $path.'/js-support-ticket-'.$language_code.'.po';


        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];

        if(!array_key_exists($language_code, $langarray)){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','js-support-ticket');
            return json_encode($result);
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writable','js-support-ticket').': '.$path;
            return json_encode($result);
        }

        if( ! file_exists($final_path)){
            touch($final_path);
        }

        if( ! is_writeable($final_path)){
            $result['error'] = __('File is not writable','js-support-ticket').': '.$final_path;
        }else{

            if($this->isConnected()){

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $post_data['product'] ='js-support-ticket-wp';
                $post_data['domain'] = get_site_url();
                $post_data['producttype'] = jssupportticket::$_config['producttype'];
                $post_data['productcode'] = 'jsticket';
                $post_data['productversion'] = jssupportticket::$_config['productversion'];
                $post_data['JVERSION'] = get_bloginfo('version');
                $post_data['translationcode'] = $lang_name;
                $post_data['method'] = 'getTranslationFile';

                $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                    $result = $response['body'];
                }else{
                    $result = false;
                    if(!is_wp_error($response)){
                       $error = $response['response']['message'];
                   }else{
                        $error = $response->get_error_message();
                   }
                }
                if($result){
                    $array = json_decode($result, true);
                }else{
                    $array = array();
                }

                $ret = $this->writeLanguageFile( $final_path , $array['file']);

                if($ret != false){
                    $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                    $post_data['product'] ='js-support-ticket-wp';
                    $post_data['domain'] = get_site_url();
                    $post_data['producttype'] = jssupportticket::$_config['producttype'];
                    $post_data['productcode'] = 'jsticket';
                    $post_data['productversion'] = jssupportticket::$_config['productversion'];
                    $post_data['JVERSION'] = get_bloginfo('version');
                    $post_data['folder'] = $array['foldername'];

                    $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
                    if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                        $result_call = $response['body'];
                    }else{
                        $result_call = false;
                        if(!is_wp_error($response)){
                           $error = $response['response']['message'];
                        }else{
                            $error = $response->get_error_message();
                        }
                    }
                    if($result_call){
                        $response = $result_call;
                    }else{
                        $response = $result_call;
                    }

                }
                $result['data'] = __('File successfully downloaded','js-support-ticket');
            }else{
                $result['error'] = __('Unable to connect to the server','js-support-ticket');
            }
        }

        $result = json_encode($result);

        return $result;

    }

    function writeLanguageFile( $path , $url ){
        $result = true;
        include(ABSPATH . "wp-admin/includes/admin.php");
        $tmpfile = download_url( $url);
        copy( $tmpfile, $path );
        @unlink( $tmpfile ); // must unlink afterwards
        //make mo for po file
        $this->phpmo_convert($path);
        return $result;
    }

    function isConnected(){

        $connected = @fsockopen("www.google.com", 80);
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    function phpmo_convert($input, $output = false) {
        if ( !$output )
            $output = str_replace( '.po', '.mo', $input );
        $hash = $this->phpmo_parse_po_file( $input );
        if ( $hash === false ) {
            return false;
        } else {
            $this->phpmo_write_mo_file( $hash, $output );
            return true;
        }
    }

    function phpmo_clean_helper($x) {
        if (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->phpmo_clean_helper($v);
            }
        } else {
            if ($x[0] == '"')
                $x = substr($x, 1, -1);
            $x = str_replace("\"\n\"", '', $x);
            $x = str_replace('$', '\\$', $x);
        }
        return $x;
    }
    /* Parse gettext .po files. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
    function phpmo_parse_po_file($in) {
    if (!file_exists($in)){ return false; }
    $ids = array();
    $strings = array();
    $language = array();
    $lines = file($in);
    foreach ($lines as $line_num => $line) {
        if (strstr($line, 'msgid')){
            $endpos = strrchr($line, '"');
            $id = substr($line, 7, $endpos-2);
            $ids[] = $id;
        }elseif(strstr($line, 'msgstr')){
            $endpos = strrchr($line, '"');
            $string = substr($line, 8, $endpos-2);
            $strings[] = array($string);
        }else{}
    }
    for ($i=0; $i<count($ids); $i++){
        //Shoaib
        if(isset($ids[$i]) && isset($strings[$i])){
            if($entry['msgstr'][0] == '""'){
                continue;
            }
            $language[$ids[$i]] = array('msgid' => $ids[$i], 'msgstr' =>$strings[$i]);
        }
    }
    return $language;
    }
    /* Write a GNU gettext style machine object. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
    function phpmo_write_mo_file($hash, $out) {
        // sort by msgid
        ksort($hash, SORT_STRING);
        // our mo file data
        $mo = '';
        // header data
        $offsets = array ();
        $ids = '';
        $strings = '';
        foreach ($hash as $entry) {
            $id = $entry['msgid'];
            $str = implode("\x00", $entry['msgstr']);
            // keep track of offsets
            $offsets[] = array (
                            strlen($ids), strlen($id), strlen($strings), strlen($str)
                            );
            // plural msgids are not stored (?)
            $ids .= $id . "\x00";
            $strings .= $str . "\x00";
        }
        // keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start = 7 * 4 + sizeof($hash) * 4 * 4;
        // values start right after the keys
        $value_start = $key_start +strlen($ids);
        // first all key offsets, then all value offsets
        $key_offsets = array ();
        $value_offsets = array ();
        // calculate
        foreach ($offsets as $v) {
            list ($o1, $l1, $o2, $l2) = $v;
            $key_offsets[] = $l1;
            $key_offsets[] = $o1 + $key_start;
            $value_offsets[] = $l2;
            $value_offsets[] = $o2 + $value_start;
        }
        $offsets = array_merge($key_offsets, $value_offsets);
        // write header
        $mo .= pack('Iiiiiii', 0x950412de, // magic number
        0, // version
        sizeof($hash), // number of entries in the catalog
        7 * 4, // key index offset
        7 * 4 + sizeof($hash) * 8, // value index offset,
        0, // hashtable size (unused, thus 0)
        $key_start // hashtable offset
        );
        // offsets
        foreach ($offsets as $offset)
            $mo .= pack('i', $offset);
        // ids
        $mo .= $ids;
        // strings
        $mo .= $strings;
        file_put_contents($out, $mo);
    }

    function stripslashesFull($input){// testing this function/.
        if (is_array($input)) {
            $input = array_map(array($this,'stripslashesFull'), $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = stripslashes($input);
        }
        return $input;
    }

    function getUserNameById($id){
        if (!is_numeric($id))
            return false;
        $query = "SELECT user_nicename AS name FROM `" . jssupportticket::$_wpprefixforuser . "users` WHERE id = $id";
        $username = jssupportticket::$_db->get_var($query);
        return $username;
    }

    function getusersearchajax() {
        $username = JSSTrequest::getVar('username');
        $name = JSSTrequest::getVar('name');
        $emailaddress = JSSTrequest::getVar('emailaddress');
        $canloadresult = false;
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login AS username, user.user_email AS useremail, user.display_name AS userdisplayname
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user ";
                    if(in_array('agent',jssupportticket::$_active_addons)){
                        $query .= " WHERE NOT EXISTS( SELECT staff.id FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff WHERE user.ID = staff.uid) ";
                    }else{
                        $query .= " WHERE 1 = 1 "; // to handle filter cases
                    }
        if (strlen($name) > 0) {
            $query .= " AND user.display_name LIKE '%$name%'";
            $canloadresult = true;
        }
        if (strlen($emailaddress) > 0) {
            $query .= " AND user.user_email LIKE '%$emailaddress%'";
            $canloadresult = true;
        }
        if (strlen($username) > 0) {
            $query .= " AND user.user_login LIKE '%$username%'";
            $canloadresult = true;
        }
        if($canloadresult){
            $users = jssupportticket::$_db->get_results($query);
            if(!empty($users)){
                $result ='
                <div class="js-ticket-table-wrp">
                    <div class="js-ticket-table-header">
                        <div class="js-ticket-table-header-col js-tkt-tbl-uid">'.__('User ID', 'js-support-ticket').'</div>
                        <div class="js-ticket-table-header-col js-tkt-tbl-unm">'.__('User Name', 'js-support-ticket').'</div>
                        <div class="js-ticket-table-header-col js-tkt-tbl-eml">'.__('Email Address', 'js-support-ticket').'</div>
                        <div class="js-ticket-table-header-col js-tkt-tbl-nam">'.__('Name', 'js-support-ticket').'</div>
                    </div>
                    <div class="js-ticket-table-body">';
                        foreach($users AS $user){
                            $result .='
                            <div class="js-ticket-data-row">
                                <div class="js-ticket-table-body-col js-tkt-tbl-uid">
                                    <span class="js-ticket-display-block">'.__('User ID','js-support-ticket').'</span>'.$user->userid.'
                                </div>
                                <div class="js-ticket-table-body-col js-tkt-tbl-unm">
                                    <span class="js-ticket-display-block">'.__('User Name','js-support-ticket').':</span>
                                    <span class="js-ticket-title"><a href="#" class="js-userpopup-link" data-id="'.$user->userid.'" data-email="'.$user->useremail.'" data-name="'.$user->userdisplayname.'">'.$user->username.'</a></span>
                                </div>
                                <div class="js-ticket-table-body-col js-tkt-tbl-eml">
                                    <span class="js-ticket-display-block">'.__('Email','js-support-ticket').':</span>
                                    '.$user->useremail.'
                                </div>
                                <div class="js-ticket-table-body-col js-tkt-tbl-nam">
                                    <span class="js-ticket-display-block">'.__('Name','js-support-ticket').':</span>
                                    '.$user->userdisplayname.'
                                </div>
                            </div>';
                        }
                $result .='</div>';
            }else{
                $result= JSSTlayout::getNoRecordFound();
            }
        }else{ // reset button
            //$result ='<div class="js-staff-searc-desc">'.__('Use search feature to select the user','js-support-ticket').'</div>';
            $result = $this->getuserlistajax();
        }

        return $result;
    }



    function getuserlistajax(){
        $userlimit = JSSTrequest::getVar('userlimit',null,0);
        $maxrecorded = 4;
        $query = "SELECT DISTINCT COUNT(user.id)
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user ";
                    if(in_array('agent',jssupportticket::$_active_addons)){
                        $query .= " WHERE NOT EXISTS( SELECT staff.id FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff WHERE user.ID = staff.uid) ";
                    }

        $total = jssupportticket::$_db->get_var($query);
        $limit = $userlimit * $maxrecorded;
        if($limit >= $total){
            $limit = 0;
        }
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login AS username, user.user_email AS useremail,
                    IF(user.display_name='' or user.display_name IS NULL,user.user_nicename,user.display_name) AS userdisplayname
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user ";
                    if(in_array('agent',jssupportticket::$_active_addons)){
                        $query .= " WHERE NOT EXISTS( SELECT staff.id FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff WHERE user.ID = staff.uid) ";
                    }
                    $query .= " LIMIT $limit, $maxrecorded";
        $users = jssupportticket::$_db->get_results($query);
        $html = $this->makeUserList($users,$total,$maxrecorded,$userlimit);
        return $html;

    }


     function makeUserList($users,$total,$maxrecorded,$userlimit){
        $html = '';
        if(!empty($users)){
            if(is_array($users)){
                $html ='
                <div class="js-ticket-table-wrp">
                    <div class="js-ticket-table-header">
                        <div class="js-ticket-table-header-col js-tkt-tbl-uid">'.__('User ID', 'js-support-ticket').'</div>
                        <div class="js-ticket-table-header-col js-tkt-tbl-unm">'.__('User Name', 'js-support-ticket').'</div>
                        <div class="js-ticket-table-header-col js-tkt-tbl-eml">'.__('Email Address', 'js-support-ticket').'</div>
                        <div class="js-ticket-table-header-col js-tkt-tbl-nam">'.__('Name', 'js-support-ticket').'</div>
                    </div>
                    <div class="js-ticket-table-body">';
                        foreach($users AS $user){
                            $html .='
                            <div class="js-ticket-data-row">
                                <div class="js-ticket-table-body-col js-tkt-tbl-uid">
                                    <span class="js-ticket-display-block">'.__('User ID','js-support-ticket').'</span>'.$user->userid.'
                                </div>
                                <div class="js-ticket-table-body-col js-tkt-tbl-unm">
                                    <span class="js-ticket-display-block">'.__('User Name','js-support-ticket').':</span>
                                    <span class="js-ticket-title"><a href="#" class="js-userpopup-link" data-id="'.$user->userid.'" data-email="'.$user->useremail.'" data-name="'.$user->userdisplayname.'">'.$user->username.'</a></span>
                                </div>
                                <div class="js-ticket-table-body-col js-tkt-tbl-eml">
                                    <span class="js-ticket-display-block">'.__('Email','js-support-ticket').':</span>
                                    '.$user->useremail.'
                                </div>
                                <div class="js-ticket-table-body-col js-tkt-tbl-nam">
                                    <span class="js-ticket-display-block">'.__('Name','js-support-ticket').':</span>
                                    '.$user->userdisplayname.'
                                </div>
                            </div>';
                        }
                $html .='</div>';
            }
            $num_of_pages = ceil($total / $maxrecorded);
            $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
            if($num_of_pages > 0){
                $page_html = '';
                $prev = $userlimit;
                if($prev > 0){
                    $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist('.($prev - 1).');">'.__('Previous','js-support-ticket').'</a>';
                }
                for($i = 0; $i < $num_of_pages; $i++){
                    if($i == $userlimit)
                        $page_html .= '<span class="jsst_userlink selected" >'.($i + 1).'</span>';
                    else
                        $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist('.$i.');">'.($i + 1).'</a>';

                }
                $next = $userlimit + 1;
                if($next < $num_of_pages){
                    $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist('.$next.');">'.__('Next','js-support-ticket').'</a>';
                }
                if($page_html != ''){
                    $html .= '<div class="jsst_userpages">'.$page_html.'</div>';
                }
            }

        }else{
            $html = JSSTlayout::getNoRecordFound();
        }
        echo $html;
        die();
        return $html;
    }

    function storeOrderingFromPage($data) {//
        if (empty($data)) {
            return false;
        }
        $sorted_array = array();
        parse_str($data['fields_ordering_new'],$sorted_array);
        $sorted_array = reset($sorted_array);
        if(!empty($sorted_array)){

            if($data['ordering_for'] == 'department'){
                $row = JSSTincluder::getJSTable('departments');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'priority'){
                $row = JSSTincluder::getJSTable('priorities');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'fieldsordering'){
                $row = JSSTincluder::getJSTable('fieldsordering');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'announcement'){
                $row = JSSTincluder::getJSTable('announcement');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'faq'){
                $row = JSSTincluder::getJSTable('faq');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'helptopic'){
                $row = JSSTincluder::getJSTable('helptopic');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'article'){
                $row = JSSTincluder::getJSTable('articles');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'download'){
                $row = JSSTincluder::getJSTable('download');
                $ordering_coloumn = 'ordering';
            }elseif($data['ordering_for'] == 'fieldordering'){
                $row = JSSTincluder::getJSTable('fieldsordering');
                $ordering_coloumn = 'ordering';
            }

            $page_multiplier = 1;
            if($data['pagenum_for_ordering'] > 1){
                $page_multiplier = ($data['pagenum_for_ordering'] - 1) * jssupportticket::$_config['pagination_default_page_size'] + 1;
            }
            for ($i=0; $i < count($sorted_array) ; $i++) {
                $row->update(array('id' => $sorted_array[$i], $ordering_coloumn => $page_multiplier + $i));
            }
        }
        JSSTmessage::setMessage(__('Ordering updated', 'js-support-ticket'), 'updated');
        return ;
    }

    function updateDate($addon_name,$plugin_version){
        return JSSTincluder::getJSModel('premiumplugin')->verfifyAddonActivation($addon_name);
    }

    function getAddonSqlForActivation($addon_name,$addon_version){
        return JSSTincluder::getJSModel('premiumplugin')->verifyAddonSqlFile($addon_name,$addon_version);
    }

    function installPluginFromAjax(){
        $pluginslug = JSSTrequest::getVar('pluginslug');
        if(file_exists( ABSPATH . 'wp-content/plugins/' . $pluginslug.'/'.$pluginslug.'.php')){
            return false;
        }
        if($pluginslug != ""){
            require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
            require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );

            // Get Plugin Info
            $api = plugins_api( 'plugin_information',
                array(
                    'slug' => $pluginslug,
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                )
            );
            $skin     = new WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );
            $upgrader->install( $api->download_link );
            if(file_exists(ABSPATH . 'wp-content/plugins/' . $pluginslug.'/'.$pluginslug.'.php')){
                return true;
            }
        }
        return false;
    }

    function activatePluginFromAjax(){
        $pluginslug = JSSTrequest::getVar('pluginslug');
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(file_exists(ABSPATH . 'wp-content/plugins/' . $pluginslug.'/'.$pluginslug.'.php')){
            $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
            if($isactivate){
                return false;
            }
            if($pluginslug != ""){
                if(!defined( 'WP_ADMIN')){
                    define( 'WP_ADMIN', TRUE );
                }
                // define( 'WP_NETWORK_ADMIN', TRUE ); // Need for Multisite
                if(!defined( 'WP_USER_ADMIN')){
                    define( 'WP_USER_ADMIN', TRUE );
                }

                ob_get_clean();
                require_once('../wp-load.php');
                require_once( ABSPATH . 'wp-admin/includes/admin.php' );
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                // $isactivate = $this->run_activate_plugin( $pluginslug.'/'.$pluginslug.'.php' );
                $isactivate = is_plugin_active($pluginslug.'/'.$pluginslug.'.php');
                if($isactivate){
                    return true;
                }
            }
        }
        return false;
    }

    function getJSSTDateFormat(){
        $dateformat = jssupportticket::$_config['date_format'];
        if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
            $dash = '/';
        } else {
            $dash = '-';
        }
        $firstdash = strpos($dateformat, $dash, 0);
        $firstvalue = substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = strpos($dateformat, $dash, $firstdash);
        $secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
        $js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
        $js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
        $js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);
        $js_scriptdateformat = str_replace('m', 'mm', $js_scriptdateformat);
        $js_scriptdateformat = str_replace('d', 'dd', $js_scriptdateformat);
        return $js_scriptdateformat;
    }

}

?>
