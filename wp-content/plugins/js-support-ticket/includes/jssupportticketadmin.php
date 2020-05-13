<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class jssupportticketadmin {

    function __construct() {
        add_action('admin_menu', array($this, 'mainmenu'));
    }

    function mainmenu() {
        if (current_user_can('jsst_support_ticket')) {
            add_menu_page(__('JS Help Desk Control Panel', 'js-support-ticket'), // Page title
                    __('Help Desk', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'jssupportticket', //menu slug
                    array($this, 'showAdminPage'), // function name
    			  plugins_url('js-support-ticket/includes/images/admin_ticket.png'),26
            );
            add_submenu_page('jssupportticket', // parent slug
                    __('Tickets', 'js-support-ticket'), // Page title
                    __('Tickets', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'ticket', //menu slug
                    array($this, 'showAdminPage') // function name
            );
            if(in_array('agent', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket', // parent slug
                        __('Agents', 'js-support-ticket'), // Page title
                        __('Agents', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'agent', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('agent');
            }

            if(in_array('knowledgebase', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket', // parent slug
                        __('Knowledge Base', 'js-support-ticket'), // Page title
                        __('Knowledge Base', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'knowledgebase', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('knowledgebase');
            }
            if(in_array('announcement', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket', // parent slug
                        __('Announcements', 'js-support-ticket'), // Page title
                        __('Announcements', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'announcement', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('announcement');
            }
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Priorities', 'js-support-ticket'), // Page title
                    __('Priority', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'priority', //menu slug
                    array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Department', 'js-support-ticket'), // Page title
                    __('Departments', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'department', //menu slug
                    array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Emails', 'js-support-ticket'), // Page title
                    __('System Emails', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'email', //menu slug
                    array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('System Error', 'js-support-ticket'), // Page title
                    __('System Errors', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'systemerror', //menu slug
                    array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Email Templates', 'js-support-ticket'), // Page title
                    __('Email Templates', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'emailtemplate', //menu slug
                    array($this, 'showAdminPage') // function name
            );
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('User Fields', 'js-support-ticket'), // Page title
                    __('User Fields', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'userfeild', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            if(in_array('cannedresponses', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Canned Responses', 'js-support-ticket'), // Page title
                        __('Canned Responses', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'cannedresponses', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('cannedresponses');
            }

            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Roles', 'js-support-ticket'), // Page title
                    __('Roles', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'role', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            if(in_array('mail', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Mail', 'js-support-ticket'), // Page title
                        __('Mail', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'mail', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('mail');
            }

            if(in_array('banemail', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Ban Email', 'js-support-ticket'), // Page title
                        __('Ban Emails', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'banemail', //menu slug
                        array($this, 'showAdminPage') // function name
                );
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Ban list log', 'js-support-ticket'), // Page title
                        __('Ban list log', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'banemaillog', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('banemail');
                $this->addMissingAddonPage('banemaillog');
            }
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Field Ordering', 'js-support-ticket'), // Page title
                    __('Field Ordering', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'fieldordering', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            if(in_array('emailpiping', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('JS Help Desk', 'js-support-ticket'), // Page title
                        __('Email Piping', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'emailpiping', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('emailpiping');
            }

            add_submenu_page('jssupportticket_hide', // parent slug
                    __('JS Help Desk', 'js-support-ticket'), // Page title
                    __('Reports', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'reports', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            if(in_array('export', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Export', 'js-support-ticket'), // Page title
                        __('Export', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'export', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('export');
            }

            if(in_array('feedback', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Feedbacks', 'js-support-ticket'), // Page title
                        __('Feedbacks', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'feedback', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('feedback');
            }
            add_submenu_page('jssupportticket_hide', // parent slug
                    __('Post Installation', 'js-support-ticket'), // Page title
                    __('Post Installation', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'postinstallation', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            if(in_array('faq', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket', // parent slug
                        __("FAQs", 'js-support-ticket'), // Page title
                        __("FAQs", 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'faq', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('faq');
            }

            if(in_array('download', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket', // parent slug
                    __('Downloads', 'js-support-ticket'), // Page title
                    __('Downloads', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'download', //menu slug
                    array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('download');
            }

            add_submenu_page('jssupportticket', // parent slug
                    __('Configurations', 'js-support-ticket'), // Page title
                    __('Configurations', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'configuration', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            add_submenu_page('jssupportticket', // parent slug
                    __('Premium Addons', 'js-support-ticket'), // Page title
                    __('Premium Addons', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'premiumplugin', //menu slug
                    array($this, 'showAdminPage') // function name
            );

            // adddons mpage code.



            // if(in_array('knowledgebase', jssupportticket::$_active_addons)){
            //     add_submenu_page('jssupportticket', // parent slug
            //         __('Knowledge Base', 'js-support-ticket'), // Page title
            //         __('Knowledge Base', 'js-support-ticket'), // menu title
            //         'jsst_support_ticket', // capability
            //         'knowledgebase', //menu slug
            //         array($this, 'showAdminPage') // function name
            //     );
            // }

            if(in_array('helptopic', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Help Topics', 'js-support-ticket'), // Page title
                        __('Help Topics', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'helptopic', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('helptopic');
            }

            if(in_array('themes', jssupportticket::$_active_addons)){
                add_submenu_page('jssupportticket_hide', // parent slug
                        __('Themes', 'js-support-ticket'), // Page title
                        __('Themes', 'js-support-ticket'), // menu title
                        'jsst_support_ticket', // capability
                        'themes', //menu slug
                        array($this, 'showAdminPage') // function name
                );
            }else{
                $this->addMissingAddonPage('themes');
            }

            add_submenu_page('jssupportticket_hide', // parent slug
                    __('GDPR', 'js-support-ticket'), // Page title
                    __('GDPR', 'js-support-ticket'), // menu title
                    'jsst_support_ticket', // capability
                    'gdpr', //menu slug
                    array($this, 'showAdminPage') // function name
            );

        }else{
            add_menu_page(__('JS Help Desk Control Panel', 'js-support-ticket'), // Page title
                    __('JS Help Desk', 'js-support-ticket'), // menu title
                    'jsst_support_ticket_tickets', // capability
                    'ticket', //menu slug
                    array($this, 'showAdminPage'), // function name
                  plugins_url('js-support-ticket/includes/images/admin_ticket.png'), 26
            );
        }
    }

    function addMissingAddonPage($module_name){
        add_submenu_page('jssupportticket_hide', // parent slug
                __('Premium Addon', 'js-support-ticket'), // Page title
                __('Premium Addon', 'js-support-ticket'), // menu title
                'jsst_support_ticket', // capability
                $module_name, //menu slug
                array($this, 'showMissingAddonPage') // function name
        );
    }

    function showAdminPage() {
        $page = JSSTrequest::getVar('page');
        JSSTincluder::include_file($page);
    }

    function showMissingAddonPage() {
        JSSTincluder::include_file('admin_missingaddon','premiumplugin');
    }

}

$jssupportticketAdmin = new jssupportticketadmin();
?>
