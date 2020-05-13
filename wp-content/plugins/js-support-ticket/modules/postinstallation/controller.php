<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTpostinstallationController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSSTrequest::getLayout('jstlay', null, 'stepone');
        if($this->canaddfile()){
            switch ($layout) {
                case 'admin_quickconfig':
                    JSSTincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepone':
                    JSSTincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_steptwo':
                    JSSTincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepthree':
                    if(!in_array('feedback', jssupportticket::$_active_addons)){// to hanle show hide of feed back settings.
                        $layout = 'admin_settingcomplete';
                    }
                    JSSTincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_themedemodata':
                    jssupportticket::$_data['flag'] = JSSTrequest::getVar('flag');
                break;
            }
            $module = (is_admin()) ? 'page' : 'jstmod';
            $module = JSSTrequest::getVar($module, null, 'postinstallation');
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

    function save(){
        $data = JSSTrequest::get('post');
        $result = JSSTincluder::getJSModel('postinstallation')->storeConfigurations($data);
        $url = admin_url("admin.php?page=postinstallation&jstlay=steptwo");
        if($data['step'] == 2){
            $url = admin_url("admin.php?page=postinstallation&jstlay=stepthree");
        }
        if($data['step'] == 3){
            $url = admin_url("admin.php?page=postinstallation&jstlay=stepfour");
        }
        wp_redirect($url);
        exit();
    }

    function savesampledata(){
        $data = JSSTrequest::get('post');
        $sampledata = $data['sampledata'];
        $jsmenu = $data['jsmenu'];
        $empmenu = $data['empmenu'];
        $url = admin_url("admin.php?page=jslearnmanager");
        $result = JSSTincluder::getJSModel('postinstallation')->installSampleData($sampledata);
        wp_redirect($url);
        exit();
    }
}
$JSSTpostinstallationController = new JSSTpostinstallationController();
?>