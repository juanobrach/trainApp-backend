<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTfieldorderingController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSSTrequest::getLayout('jstlay', null, 'fieldordering');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_fieldordering':
                    $fieldfor = JSSTrequest::getVar('fieldfor');
                    jssupportticket::$_data['fieldfor'] = $fieldfor;
                    JSSTincluder::getJSModel('fieldordering')->getFieldOrderingForList($fieldfor);
                    break;
                case 'admin_adduserfeild':
                    $id = JSSTrequest::getVar('jssupportticketid');
                    $fieldfor = JSSTrequest::getVar('fieldfor');
                    if($fieldfor == ''){
                        $fieldfor = jssupportticket::$_data['fieldfor'];
                    }else{
                        jssupportticket::$_data['fieldfor'] = $fieldfor;
                    }
                    JSSTincluder::getJSModel('fieldordering')->getUserFieldbyId($id,1);
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jstmod';
            $module = JSSTrequest::getVar($module, null, 'fieldordering');
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

    static function changeorder() {
        $nonce = JSSTrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-order') ) {
            die( 'Security check Failed' );
        }
        $id = JSSTrequest::getVar('fieldorderingid');
        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        $action = JSSTrequest::getVar('order');
        JSSTincluder::getJSModel('fieldordering')->changeOrder($id, $action);
        $url = admin_url("admin.php?page=fieldordering&jstlay=fieldordering&fieldfor=".$fieldfor);
        wp_redirect($url);
        exit;
    }

    static function changepublishstatus() {
        $nonce = JSSTrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-publish-status') ) {
            die( 'Security check Failed' );
        }
        $id = JSSTrequest::getVar('fieldorderingid');
        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        $status = JSSTrequest::getVar('status');
        JSSTincluder::getJSModel('fieldordering')->changePublishStatus($id, $status);
        $url = admin_url("admin.php?page=fieldordering&jstlay=fieldordering&fieldfor=".$fieldfor);
        wp_redirect($url);
        exit;
    }

    static function changevisitorpublishstatus() {
        $nonce = JSSTrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-visitor-publish-status') ) {
            die( 'Security check Failed' );
        }
        $id = JSSTrequest::getVar('fieldorderingid');
        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        $status = JSSTrequest::getVar('status');
        JSSTincluder::getJSModel('fieldordering')->changeVisitorPublishStatus($id, $status);
        $url = admin_url("admin.php?page=fieldordering&jstlay=fieldordering&fieldfor=".$fieldfor);
        wp_redirect($url);
        exit;
    }

    static function changerequiredstatus() {
        $nonce = JSSTrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'change-required-status') ) {
            die( 'Security check Failed' );
        }
        $id = JSSTrequest::getVar('fieldorderingid');
        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        $status = JSSTrequest::getVar('status');
        JSSTincluder::getJSModel('fieldordering')->changeRequiredStatus($id, $status);
        $url = admin_url("admin.php?page=fieldordering&jstlay=fieldordering&fieldfor=".$fieldfor);
        wp_redirect($url);
        exit;
    }

    static function saveuserfeild() {
        $data = JSSTrequest::get('post');

        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        JSSTincluder::getJSModel('fieldordering')->storeUserField($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=fieldordering&fieldfor=".$fieldfor);
        } else {
            $url = jssupportticket::makeUrl(array('jstmod'=>'fieldordering', 'jstlay'=>'userfeilds'));
        }
        wp_redirect($url);
        exit;
    }

    static function savefeild() {
        $data = JSSTrequest::get('post');
        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        JSSTincluder::getJSModel('fieldordering')->updateField($data);
        if (is_admin()) {
            $url = admin_url("admin.php?page=fieldordering&fieldfor=".$fieldfor);
        } else {
            $url = jssupportticket::makeUrl(array('jstmod'=>'fieldordering', 'jstlay'=>'userfeilds'));
        }
        wp_redirect($url);
        exit;
    }

    static function removeuserfeild() {
        $nonce = JSSTrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'remove-userfeild') ) {
            die( 'Security check Failed' );
        }
        $id = JSSTrequest::getVar('jssupportticketid');
        $fieldfor = JSSTrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = jssupportticket::$_data['fieldfor'];
        }
        JSSTincluder::getJSModel('fieldordering')->deleteUserField($id);
        if (is_admin()) {
            $url = admin_url("admin.php?page=fieldordering&fieldfor=".$fieldfor);
        } else {
            $url = jssupportticket::makeUrl(array('jstmod'=>'fieldordering', 'jstlay'=>'userfeilds'));
        }
        wp_redirect($url);
        exit;
    }

}

$fieldorderingController = new JSSTfieldorderingController();
?>
