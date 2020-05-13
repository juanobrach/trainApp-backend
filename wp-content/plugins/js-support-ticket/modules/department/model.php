<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTdepartmentModel {

    function getDepartments() {
        // Filter
        $isadmin = is_admin();
        $deptname = ($isadmin) ? 'departmentname' : 'jsst-dept';

        $departmentname = addslashes(trim(JSSTrequest::getVar($deptname)));
        $pagesize = absint(JSSTrequest::getVar('pagesize'));

        $formsearch = JSSTrequest::getVar('JSST_form_search', 'post');
        if ($formsearch == 'JSST_SEARCH') {
            $_SESSION['JSST_SEARCH']['departmentname'] = $departmentname;
            $_SESSION['JSST_SEARCH']['pagesize'] = $pagesize;
        }elseif(JSSTrequest::getVar('pagenum', 'get', null) == null){
            if(isset($_SESSION['JSST_SEARCH'])){
                unset($_SESSION['JSST_SEARCH']['departmentname']);
            }
        }

        if (JSSTrequest::getVar('pagenum', 'get', null) != null ) {
            $departmentname = (isset($_SESSION['JSST_SEARCH']['departmentname']) && $_SESSION['JSST_SEARCH']['departmentname'] != '') ? $_SESSION['JSST_SEARCH']['departmentname'] : null;
        }
        $pagesize = (isset($_SESSION['JSST_SEARCH']['pagesize']) && $_SESSION['JSST_SEARCH']['pagesize'] != '') ? $_SESSION['JSST_SEARCH']['pagesize'] : null;

        $departmentname = jssupportticket::parseSpaces($departmentname);
        $inquery = '';
        if ($departmentname != null)
            $inquery .= " WHERE department.departmentname LIKE '%".$departmentname."%'";

        jssupportticket::$_data['filter'][$deptname] = $departmentname;
        jssupportticket::$_data['filter']['pagesize'] = $pagesize;

        // Pagination
        if($pagesize){
            JSSTpagination::setLimit($pagesize);
        }
        $query = "SELECT COUNT(`id`) FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department";
        $query .= $inquery;
        $total = jssupportticket::$_db->get_var($query);
        jssupportticket::$_data['total'] = $total;
        jssupportticket::$_data[1] = JSSTpagination::getPagination($total);

        // Data
        $query = "SELECT department.*,email.email AS outgoingemail
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department
                    LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_email` AS email ON email.id = department.emailid ";
        $query .= $inquery;
        $query .= " ORDER BY department.ordering ASC,department.departmentname ASC LIMIT " . JSSTpagination::getOffset() . ", " . JSSTpagination::getLimit();
        jssupportticket::$_data[0] = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return;
    }

    function getDepartmentForForm($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT department.*,email.email AS outgoingemail
                        FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_email` AS email ON email.id = department.emailid
                        WHERE department.id = " . $id;
            jssupportticket::$_data[0] = jssupportticket::$_db->get_row($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        return;
    }

    private function getNextOrdering() {
        $query = "SELECT MAX(ordering) FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments`";
        $result = jssupportticket::$_db->get_var($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result + 1;
    }

    function storeDepartment($data) {
        if ( in_array('agent',jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff()) {
            $task_allow = ($data['id'] == '') ? 'Add Department' : 'Edit Department';
            $allowed = JSSTincluder::getJSModel('userpermissions')->checkPermissionGrantedForTask($task_allow);
            if ($allowed != true) {
                JSSTmessage::setMessage(__('You are not allowed', 'js-support-ticket') . ' ' . __($task_allow, 'js-support-ticket'), 'error');
                return;
            }
        }

        if($data['sendmail'] == 1 && is_numeric($data['emailid'])){
            if ( in_array('emailpiping',jssupportticket::$_active_addons)) {
                $query = "SELECT emailaddress FROM `" . jssupportticket::$_db->prefix . "js_ticket_ticketsemail` ";
                $emailaddresses = jssupportticket::$_db->get_results($query);
            }else{
                $emailaddresses = array();
            }
            $query = "SELECT email FROM `" . jssupportticket::$_db->prefix . "js_ticket_email`
                WHERE id = ".$data['emailid'];
            $email = jssupportticket::$_db->get_var($query);

            foreach ($emailaddresses as $edata) {
                if($email == $edata->emailaddress){
                    JSSTmessage::setMessage(__('You cannot use this email, it is used in email piping', 'js-support-ticket'), 'error');
                    return;
                }
            }
        }

        if ($data['id'])
            $data['updated'] = date_i18n('Y-m-d H:i:s');
        else
            $data['created'] = date_i18n('Y-m-d H:i:s');

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $data['departmentsignature'] = wpautop(wptexturize(stripslashes($_POST['departmentsignature'])));

        if (!$data['id']) { //new
            $data['ordering'] = $this->getNextOrdering();
        }
        if (isset($data['canappendsignature'])) { //new
            $data['canappendsignature'] = 1;
        }else{
            $data['canappendsignature'] = 0;
        }

        $row = JSSTincluder::getJSTable('departments');

        $data = JSSTincluder::getJSmodel('jssupportticket')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            if ($row->isdefault) {
                $this->changeDefault($row->id, 0);
            }
            JSSTmessage::setMessage(__('The department has been stored', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            JSSTmessage::setMessage(__('The department has not been stored', 'js-support-ticket'), 'error');
        }

        return;
    }

    function setOrdering($id) {
        if (!is_numeric($id))
            return false;
        $order = JSSTrequest::getVar('order', 'get');
        if ($order == 'down') {
            $order = ">";
            $direction = "ASC";
        } else {
            $order = "<";
            $direction = "DESC";
        }
        $query = "SELECT t.ordering,t.id,t2.ordering AS ordering2 FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS t,`" . jssupportticket::$_db->prefix . "js_ticket_departments` AS t2 WHERE t.ordering $order t2.ordering AND t2.id = $id ORDER BY t.ordering $direction LIMIT 1";
        $result = jssupportticket::$_db->get_row($query);

        $row = JSSTincluder::getJSTable('departments');
        if ($row->update(array('id' => $id, 'ordering' => $result->ordering)) && $row->update(array('id' => $result->id, 'ordering' => $result->ordering2))) {
            JSSTmessage::setMessage(__('Departments','js-support-ticket').' '.__('ordering has been changed', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            JSSTmessage::setMessage(__('Departments','js-support-ticket').' '.__('ordering has not changed', 'js-support-ticket'), 'error');
        }
        return;
    }

    function removeDepartment($id) {
        if (!is_numeric($id))
            return false;
        if ( in_array('agent',jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff()) {
            $allowed = JSSTincluder::getJSModel('userpermissions')->checkPermissionGrantedForTask('Delete Department');
            if ($allowed != true) {
                JSSTmessage::setMessage(__('You are not allowed', 'js-support-ticket'), 'error');
                return;
            }
        }
        if ($this->canRemoveDepartment($id)) {

            $row = JSSTincluder::getJSTable('departments');
            if ($row->delete($id)) {
                if(in_array('agent',jssupportticket::$_active_addons)){
                    $query = "DELETE
                                FROM `".jssupportticket::$_db->prefix . "js_ticket_acl_role_access_departments`
                                WHERE departmentid = ".$id;
                    jssupportticket::$_db->query($query);
                    JSSTmessage::setMessage(__('The department has been deleted', 'js-support-ticket'), 'updated');
                }
            } else {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
                JSSTmessage::setMessage(__('The department has not been deleted', 'js-support-ticket'), 'error');
            }
        } else {
            JSSTmessage::setMessage(__('The department in use cannot be delete', 'js-support-ticket'), 'error');
        }
        return;
    }

    private function canRemoveDepartment($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT (
                    (SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE departmentid = " . $id . ")
                    + (SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE id = " . $id . " AND isdefault = 1) ";

                    if(in_array('agent', jssupportticket::$_active_addons)){
                        $query .= " + (SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_acl_user_access_departments` WHERE departmentid = " . $id . ") ";
                    }

                    if(in_array('helptopic', jssupportticket::$_active_addons)){
                        $query .= " + (SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_help_topics` WHERE departmentid = " . $id . ") ";
                    }

                    if(in_array('cannedresponses', jssupportticket::$_active_addons)){
                        $query .= " + (SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_department_message_premade` WHERE departmentid = " . $id . ")";
                    }

                    $query .= " ) AS total";
        $result = jssupportticket::$_db->get_var($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        if ($result == 0)
            return true;
        else
            return false;
    }

    function getDepartmentForCombobox() {
        $query = "SELECT id, departmentname AS text FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE status = 1";
        /*if (!is_admin()) {
            $query .= '  AND ispublic = 1';
        }*/
        $query .= " ORDER BY ordering";
        $list = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $list;
    }

    function changeStatus($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT status  FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE id=" . $id;
           $status = jssupportticket::$_db->get_var($query);
       $status = 1 - $status;

       $row = JSSTincluder::getJSTable('departments');
       if ($row->update(array('id' => $id, 'status' => $status))) {
            JSSTmessage::setMessage(__('Department','js-support-ticket').' '.__('status has been changed', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            JSSTmessage::setMessage(__('Department','js-support-ticket').' '.__('status has not been changed', 'js-support-ticket'), 'error');
        }
        return;
    }

    function changeDefault($id,$default) {
        if (!is_numeric($id))
            return false;

        $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_departments` SET isdefault = 0 WHERE id != " . $id;
        jssupportticket::$_db->query($query);

        $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_departments` SET isdefault = 1 - $default WHERE id=" . $id;
        jssupportticket::$_db->query($query);

        if (jssupportticket::$_db->last_error == null) {
            JSSTmessage::setMessage(__('Department','js-support-ticket').' '.__('default has been changed', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            JSSTmessage::setMessage(__('Department','js-support-ticket').' '.__('default has not been changed', 'js-support-ticket'), 'error');
        }
        return;
    }

    function getHelpTopicByDepartment() {
        if(!in_array('helptopic', jssupportticket::$_active_addons)){
            return;
        }

        $departmentid = JSSTrequest::getVar('val');
        if (!is_numeric($departmentid)){
            return false;
        }

        $query = "SELECT id, topic AS text FROM `" . jssupportticket::$_db->prefix . "js_ticket_help_topics` WHERE status = 1 AND departmentid = " . $departmentid;
        $list = jssupportticket::$_db->get_results($query);

        $query = "SELECT required FROM `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` WHERE field='helptopic'";
        $isRequired = jssupportticket::$_db->get_var($query);

        $combobox = false;
        if(!empty($list)){
            $combobox = JSSTformfield::select('helptopicid', $list, '', __('Select Help Topic', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field','data-validation'=>($isRequired ? 'required' : '')));
        }
        return $combobox;
    }

    function getPremadeByDepartment() {
        if(!in_array('cannedresponses', jssupportticket::$_active_addons)){
            return false;
        }
        $departmentid = JSSTrequest::getVar('val');
        if (!is_numeric($departmentid))
            return false;
        $query = "SELECT id, title AS text FROM `" . jssupportticket::$_db->prefix . "js_ticket_department_message_premade` WHERE status = 1 AND departmentid = " . $departmentid;
        $list = jssupportticket::$_db->get_results($query);
        $combobox = false;
        $html = '';
        if(!empty($list)){
            foreach($list as $premade){
                $html = '<div class="js-form-perm-msg" onclick="getpremade('.$premade->id.');">
                    <a href="#" title="'.__('Canned response','js-support-ticket').'">'.$premade->text.'</a>
                </div>';


            }
        }else{
            $html = '<div class="js-form-perm-msg">
                <div>'. __('No Record Found','js-support-ticket') .'</div>
            </div>';
        }

        /*if(!empty($list)){
            $combobox = JSSTformfield::select('premadeid', $list, '', __('Select Premade', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'onchange' => 'getpremade(this.value)'));
        }else{
            $combobox .= '<span id = "js-ticket-no-premade">' .__('No premade found','js-support-ticket').'</span>';
        }*/
        return $html;
    }

    function getSignatureByID($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT departmentsignature FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE id = " . $id;
        $signature = jssupportticket::$_db->get_var($query);
        return $signature;
    }

    function getDepartmentById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT departmentname FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE id = " . $id;
        $departmentname = jssupportticket::$_db->get_var($query);
        return $departmentname;
    }

    function getDefaultDepartmentID() {
        $query = "SELECT id FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE isdefault = 1";
        $departmentid = jssupportticket::$_db->get_var($query);
        return $departmentid;
    }

}

?>
