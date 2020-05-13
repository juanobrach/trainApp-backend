<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTattachmentModel {

    function getAttachmentForForm($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT filename,filesize,id
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_attachments`
                    WHERE ticketid = " . $id . " and replyattachmentid = 0";
        jssupportticket::$_data[5] = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return;
    }

    function getAttachmentForReply($id, $replyattachmentid) {
        if (!is_numeric($id))
            return false;
        if (!is_numeric($replyattachmentid))
            return false;
        $query = "SELECT filename,filesize,id
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_attachments`
                    WHERE ticketid = " . $id . " AND replyattachmentid = " . $replyattachmentid;
        $result = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    function storeAttachments($data) {
        JSSTincluder::getObjectClass('uploads')->storeTicketAttachment($data, $this);
        return;
    }

    function storeTicketAttachment($ticketid, $replyattachmentid, $filesize, $filename) {
        if (!is_numeric($ticketid))
            return false;
        $created = date_i18n('Y-m-d H:i:s');
        $data = array('ticketid' => $ticketid,
            'replyattachmentid' => $replyattachmentid,
            'filesize' => $filesize,
            'filename' => $filename,
            'status' => 1,
            'created' => $created
        );

        $row = JSSTincluder::getJSTable('attachments');

        $data = JSSTincluder::getJSmodel('jssupportticket')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 1) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
            return false;
        }
        return true;
    }

    function removeAttachment($id) {
        if (!is_numeric($id))
            return false;
        $query = $query = "SELECT ticket.attachmentdir AS foldername,ticket.id AS ticketid,attach.filename  "
                . " FROM `".jssupportticket::$_db->prefix."js_ticket_attachments` AS attach "
                . " JOIN `".jssupportticket::$_db->prefix."js_ticket_tickets` AS ticket ON ticket.id = attach.ticketid "
                . " WHERE attach.id = $id";
        $obj = jssupportticket::$_db->get_row($query);
        $filename = $obj->filename;
        $foldername = $obj->foldername;

        $row = JSSTincluder::getJSTable('attachments');
        if ($row->delete($id)) {
            $datadirectory = jssupportticket::$_config['data_directory'];

            $maindir = wp_upload_dir();
            $path = $maindir['basedir'];
            $path = $path .'/'.$datadirectory;
            $path = $path . '/attachmentdata';

            $path = $path . '/ticket/'.$foldername.'/' . $filename;
            unlink($path);
            //$files = glob($path.'/*.*');
            //array_map('unlink', $files); // delete all file in the direcoty
            JSSTmessage::setMessage(__('The attachment has been removed', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
            JSSTmessage::setMessage(__('The attachment has not been removed', 'js-support-ticket'), 'error');
        }
    }

    function getDownloadAttachmentById($id){
        if(!is_numeric($id)) return false;
        $query = "SELECT ticket.attachmentdir AS foldername,ticket.id AS ticketid,attach.filename  "
                . " FROM `".jssupportticket::$_db->prefix."js_ticket_attachments` AS attach "
                . " JOIN `".jssupportticket::$_db->prefix."js_ticket_tickets` AS ticket ON ticket.id = attach.ticketid "
                . " WHERE attach.id = $id";
        $object = jssupportticket::$_db->get_row($query);
        $foldername = $object->foldername;
        $ticketid = $object->ticketid;
        $filename = $object->filename;
        $download = false;
        if(is_user_logged_in()){
            if(current_user_can('manage_options') || current_user_can('jsst_support_ticket_tickets') ){
                $download = true;
            }else{
                if( in_array('agent',jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff()){
                    $download = true;
                }else{
                    if(JSSTincluder::getJSModel('ticket')->validateTicketDetailForUser($ticketid)){
                        $download = true;
                    }
                }
            }
        }else{ // user is visitor
            $download = JSSTincluder::getJSModel('ticket')->validateTicketDetailForVisitor($ticketid);
        }
        if($download == true){
            $datadirectory = jssupportticket::$_config['data_directory'];
            $maindir = wp_upload_dir();
            $path = $maindir['basedir'];
            $path = $path .'/'.$datadirectory;
            $path = $path . '/attachmentdata';
            $path = $path . '/ticket/' . $foldername;
            $file = $path . '/'.$filename;

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            //ob_clean();
            flush();
            readfile($file);
            exit();
        }else{
            include( get_query_template( '404' ) );
            exit;
        }
    }

    function getDownloadAttachmentByName($file_name,$id){
        if(empty($file_name)) return false;
        if(!is_numeric($id)) return false;
        $filename = str_replace(' ', '_',$file_name);
        $query = "SELECT attachmentdir FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` WHERE id = ".$id;
        $foldername = jssupportticket::$_db->get_var($query);

        $datadirectory = jssupportticket::$_config['data_directory'];
        $maindir = wp_upload_dir();
        $path = $maindir['basedir'];
        $path = $path .'/'.$datadirectory;

        $path = $path . '/attachmentdata';
        $path = $path . '/ticket/' . $foldername;
        $file = $path . '/'.$filename;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //ob_clean();
        flush();
        readfile($file);
        exit();
        exit;

    }

    function getAllDownloads() {
        $downloadid = JSSTrequest::getVar('downloadid');
        $ticketattachment = JSSTincluder::getJSModel('ticket')->getAttachmentByTicketId($downloadid);
        if(!class_exists('PclZip')){
            require_once(jssupportticket::$_path . 'includes/lib/pclzip.lib.php');
        }
        $path = jssupportticket::$_path;
        $path .= 'zipdownloads';
        JSSTincluder::getJSModel('jssupportticket')->makeDir($path);
        $randomfolder = $this->getRandomFolderName($path);
        $path .= '/' . $randomfolder;

        JSSTincluder::getJSModel('jssupportticket')->makeDir($path);
        $archive = new PclZip($path . '/alldownloads.zip');
        $datadirectory = jssupportticket::$_config['data_directory'];
        $maindir = wp_upload_dir();
        $jpath = $maindir['basedir'];
        $jpath = $jpath .'/'.$datadirectory;
        $scanned_directory = [];
        foreach ($ticketattachment AS $ticketattachments) {
            $directory = $jpath . '/attachmentdata/ticket/' . $ticketattachments->attachmentdir . '/';
            // $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        array_push($scanned_directory,$ticketattachments->filename);
        }
        // if(!is_dir($directory))
        //         return false;

        $filelist = '';
        foreach ($scanned_directory AS $file) {
            $filelist .= $directory . '/' . $file . ',';
        }
        $filelist = substr($filelist, 0, strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . $archive->errorInfo() . "'");
        }
        $file = $path . '/alldownloads.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //ob_clean();
        flush();
        readfile($file);
        @unlink($file);
        $path = jssupportticket::$_path;
        $path .= 'zipdownloads';
        $path .= '/' . $randomfolder;
        @unlink($path . '/index.html');
        rmdir($path);
        exit();
    }

    function getAllReplyDownloads() {
        $downloadid = JSSTrequest::getVar('downloadid');
        $replyattachment = JSSTincluder::getJSModel('reply')->getAttachmentByReplyId($downloadid);
        if(!class_exists('PclZip')){
            require_once(jssupportticket::$_path . 'includes/lib/pclzip.lib.php');
        }
        $path = jssupportticket::$_path;
        $path .= 'zipdownloads';
        JSSTincluder::getJSModel('jssupportticket')->makeDir($path);
        $randomfolder = $this->getRandomFolderName($path);
        $path .= '/' . $randomfolder;

        JSSTincluder::getJSModel('jssupportticket')->makeDir($path);
        $archive = new PclZip($path . '/alldownloads.zip');
        $datadirectory = jssupportticket::$_config['data_directory'];
        $maindir = wp_upload_dir();
        $jpath = $maindir['basedir'];
        $jpath = $jpath .'/'.$datadirectory;
        $scanned_directory = [];
        foreach ($replyattachment AS $replyattachments) {
            $directory = $jpath . '/attachmentdata/ticket/' . $replyattachments->attachmentdir . '/';
            // $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        array_push($scanned_directory,$replyattachments->filename);
        }
        // if(!is_dir($directory))
        //         return false;

        $filelist = '';
        foreach ($scanned_directory AS $file) {
            $filelist .= $directory . '/' . $file . ',';
        }
        $filelist = substr($filelist, 0, strlen($filelist) - 1);
        $v_list = $archive->create($filelist, PCLZIP_OPT_REMOVE_PATH, $directory);
        if ($v_list == 0) {
            die("Error : '" . $archive->errorInfo() . "'");
        }
        $file = $path . '/alldownloads.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //ob_clean();
        flush();
        readfile($file);
        @unlink($file);
        $path = jssupportticket::$_path;
        $path .= 'zipdownloads';
        $path .= '/' . $randomfolder;
        @unlink($path . '/index.html');
        @rmdir($path);
        exit();
    }

    function getRandomFolderName($path) {
        $match = '';
        do {
            $rndfoldername = "";
            $length = 5;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            $maxlength = strlen($possible);
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            $i = 0;
            while ($i < $length) {
                $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
                if (!strstr($rndfoldername, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $rndfoldername .= $char;
                            $i++;
                        }
                    } else {
                        $rndfoldername .= $char;
                        $i++;
                    }
                }
            }
            $folderexist = $path . '/' . $rndfoldername;
            if (file_exists($folderexist))
                $match = 'Y';
            else
                $match = 'N';
        }while ($match == 'Y');

        return $rndfoldername;
    }
}

?>
