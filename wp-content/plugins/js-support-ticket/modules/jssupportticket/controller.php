<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTjssupportticketController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSSTrequest::getLayout('jstlay', null, 'controlpanel');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_controlpanel':
			        include_once jssupportticket::$_path . 'includes/updates/updates.php';
			        JSSTupdates::checkUpdates();
                    JSSTincluder::getJSModel('jssupportticket')->getControlPanelDataAdmin();
                    break;
                case 'controlpanel':
                    JSSTincluder::getJSModel('jssupportticket')->getControlPanelData();
                    //JSSTincluder::getJSModel('jssupportticket')->getStaffControlPanelData();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jstmod';
            $module = JSSTrequest::getVar($module, null, 'jssupportticket');
            JSSTincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jssupportticket')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jstask')
            return false;
        else
            return true;
    }

    function saveordering(){
        $post = JSSTrequest::get('post');

        JSSTincluder::getJSModel('jssupportticket')->storeOrderingFromPage($post);
        if($post['ordering_for'] == 'department'){
            if (is_admin()) {
                $url = admin_url("admin.php?page=department&jstlay=departments");
            } else {
                $url = jssupportticket::makeUrl(array('jstmod'=>'department', 'jstlay'=>'departments'));
            }
        }elseif($post['ordering_for'] == 'priority'){
            if (is_admin()) {
                $url = admin_url("admin.php?page=priority&jstlay=priorities");
            } else {
                $url = jssupportticket::makeUrl(array('jstmod'=>'priority', 'jstlay'=>'priorities'));
            }
        }elseif($post['ordering_for'] == 'fieldordering'){
            $fieldfor = JSSTrequest::getVar('fieldfor');
            if($fieldfor == ''){
                $fieldfor = jssupportticket::$_data['fieldfor'];
            }
            $url = admin_url("admin.php?page=fieldordering&jstlay=fieldordering&fieldfor=".$fieldfor);
        }elseif($post['ordering_for'] == 'announcement'){
            if (is_admin()) {
            $url = admin_url("admin.php?page=announcement&jstlay=announcements");
        } else {
            $url = jssupportticket::makeUrl(array('jstmod'=>'announcement', 'jstlay'=>'staffannouncements'));
        }
        }elseif($post['ordering_for'] == 'article'){
            if (is_admin()) {
                $url = admin_url("admin.php?page=knowledgebase&jstlay=listarticles");
            } else {
                $url = jssupportticket::makeUrl(array('jstmod'=>'knowledgebase', 'jstlay'=>'stafflistarticles'));
            }
        }elseif($post['ordering_for'] == 'download'){
            if (is_admin()) {
                $url = admin_url("admin.php?page=download&jstlay=downloads");
            } else {
                $url = jssupportticket::makeUrl(array('jstmod'=>'download', 'jstlay'=>'staffdownloads'));
            }
        }elseif($post['ordering_for'] == 'faq'){
            if (is_admin()) {
                $url = admin_url("admin.php?page=faq&jstlay=faqs");
            } else {
                $url = jssupportticket::makeUrl(array('jstmod'=>'faq', 'jstlay'=>'stafffaqs'));
            }
        }elseif($post['ordering_for'] == 'helptopic'){
            if (is_admin()) {
                $url = admin_url("admin.php?page=helptopic&jstlay=helptopics");
            } else {
                $url = jssupportticket::makeUrl(array('jstmod'=>'helptopic', 'jstlay'=>'agenthelptopics'));
            }
        }

        wp_redirect($url);
        exit;
    }
}

$controlpanelController = new JSSTjssupportticketController();
?>
