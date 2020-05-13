<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTfieldorderingModel {

    function getFieldOrderingForList($fieldfor) {
        if(!is_numeric($fieldfor)){
            return false;
        }
        // Pagination
        /*
          $query = "SELECT COUNT(`id`) FROM `".jssupportticket::$_db->prefix."js_ticket_fieldsordering` WHERE published = 1 AND fieldfor = 1";
          $total = jssupportticket::$_db->get_var($query);
          jssupportticket::$_data[1] = JSSTpagination::getPagination($total);
         */

        // Data
//        $query = "SELECT * FROM `".jssupportticket::$_db->prefix."js_ticket_fieldsordering` WHERE published = 1 AND fieldfor = 1 ORDER BY ordering LIMIT ".JSSTpagination::getOffset().", ".JSSTpagination::getLimit();
        $query = "SELECT * FROM `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` WHERE fieldfor = ".$fieldfor." ORDER BY ordering ";
        jssupportticket::$_data[0] = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return;
    }

    function changePublishStatus($id, $status) {
        if (!is_numeric($id))
            return false;
        if ($status == 'publish') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET published = 1 WHERE id = " . $id . " AND cannotunpublish = 0";
            jssupportticket::$_db->query($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
            JSSTmessage::setMessage(__('Field mark as published', 'js-support-ticket'),'updated');
        } elseif ($status == 'unpublish') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET published = 0 WHERE id = " . $id . " AND cannotunpublish = 0";
            jssupportticket::$_db->query($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
            JSSTmessage::setMessage(__('Field mark as unpublished', 'js-support-ticket'),'updated');
        }
        return;
    }

    function changeVisitorPublishStatus($id, $status) {
        if (!is_numeric($id))
            return false;
        if ($status == 'publish') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET isvisitorpublished = 1 WHERE id = " . $id . " AND cannotunpublish = 0";
            jssupportticket::$_db->query($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
            JSSTmessage::setMessage(__('Field mark as published', 'js-support-ticket'),'updated');
        } elseif ($status == 'unpublish') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET isvisitorpublished = 0 WHERE id = " . $id . " AND cannotunpublish = 0";
            jssupportticket::$_db->query($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
            JSSTmessage::setMessage(__('Field mark as unpublished', 'js-support-ticket'),'updated');
        }
        return;
    }

    function changeRequiredStatus($id, $status) {
        if (!is_numeric($id))
            return false;
        if ($status == 'required') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET required = 1 WHERE id = " . $id . " AND cannotunpublish = 0";
            jssupportticket::$_db->query($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
            JSSTmessage::setMessage(__('Field mark as required', 'js-support-ticket'),'updated');
        } elseif ($status == 'unrequired') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET required = 0 WHERE id = " . $id . " AND cannotunpublish = 0";
            jssupportticket::$_db->query($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
            JSSTmessage::setMessage(__('Field mark as not required', 'js-support-ticket'),'updated');
        }
        return;
    }

    function changeOrder($id, $action) {
        if (!is_numeric($id))
            return false;
        if ($action == 'down') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` AS f1, `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` AS f2
                        SET f1.ordering = f1.ordering - 1 WHERE f1.ordering = f2.ordering + 1 AND f1.fieldfor = f2.fieldfor
                        AND f2.id = " . $id;
            jssupportticket::$_db->query($query);
            $query = " UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET ordering = ordering + 1 WHERE id = " . $id;
            jssupportticket::$_db->query($query);
            JSSTmessage::setMessage(__('Field ordering down', 'js-support-ticket'),'updated');
        } elseif ($action == 'up') {
            $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` AS f1, `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` AS f2 SET f1.ordering = f1.ordering + 1
                        WHERE f1.ordering = f2.ordering - 1 AND f1.fieldfor = f2.fieldfor AND f2.id = " . $id;
            jssupportticket::$_db->query($query);
            $query = " UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET ordering = ordering - 1 WHERE id = " . $id;
            jssupportticket::$_db->query($query);
            JSSTmessage::setMessage(__('Field ordering up', 'js-support-ticket'),'updated');
        }
        return;
    }

    function getFieldsOrderingforForm($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        if (!is_user_logged_in()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $query = "SELECT  * FROM `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` WHERE ".$published." AND fieldfor =  " . $fieldfor . " ORDER BY ordering ";
        jssupportticket::$_data['fieldordering'] = jssupportticket::$_db->get_results($query);
        return;
    }

    function storeUserField($data) {
        if (empty($data)) {
            return false;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if ($data['isuserfield'] == 1) {
            // value to add as field ordering
            if ($data['id'] == '') { // only for new
                $query = "SELECT max(ordering) FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE fieldfor=".$data['fieldfor'];
                $var = jssupportticket::$_db->get_var($query);
                $data['ordering'] = $var + 1;
                if(isset($data['userfieldtype']) && ($data['userfieldtype'] == 'file' || $data['userfieldtype'] == 'termsandconditions' ) ){
                    $data['cannotsearch'] = 1;
                    $data['cannotshowonlisting'] = 1;
                }else{
                    $data['cannotshowonlisting'] = 0;
                    $data['cannotsearch'] = 0;
                }
                $query = "SELECT max(id) FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering ";
                $var = jssupportticket::$_db->get_var($query);
                $var = $var + 1;
                $fieldname = 'ufield_'.$var;
            }else{
                $fieldname = $data['field'];
            }

            $params = array();
            //code for depandetn field
            if (isset($data['userfieldtype']) && $data['userfieldtype'] == 'depandant_field') {
                if ($data['id'] != '') {
                    //to handle edit case of depandat field
                    $data['arraynames'] = $data['arraynames2'];
                }
                $flagvar = $this->updateParentField($data['parentfield'], $fieldname, $data['fieldfor']);
                if ($flagvar == false) {
                    JSSTmessage::setMessage(__('Parent field has not been stored', 'js-support-ticket'), 'error');
                }
                if (!empty($data['arraynames'])) {
                    $valarrays = explode(',', $data['arraynames']);
                    $empty_flag = 0;
                    $key_flag = '';
                    foreach ($valarrays as $key => $value) {
                        if($key != $key_flag){
                            $key_flag = $key;
                            $empty_flag = 0;
                        }
                        $keyvalue = $value;
                        $value = str_replace(' ','__',$value);
                        $value = str_replace('.','___',$value);
                        if ( isset($data[$value]) && $data[$value] != null) {
                            $params[$keyvalue] = array_filter($data[$value]);
                            $empty_flag = 1;
                        }
                    }
                    if($empty_flag == 0){
                        JSSTmessage::setMessage(__('Please Insert At least one value for every option', 'js-support-ticket'), 'error');
                        return 2 ;
                    }
                }
            }
            if (!empty($data['values'])) {
                foreach ($data['values'] as $key => $value) {
                    if ($value != null) {
                        $params[] = trim($value);
                    }
                }
            }
            //echo '<pre>';print_r($params);die();
            //if(!empty($params)){

            if (isset($data['userfieldtype']) && $data['userfieldtype'] == 'termsandconditions') { // to manage terms and condition field
                $params['termsandconditions_text'] = $data['termsandconditions_text'];
                $params['termsandconditions_linktype'] = $data['termsandconditions_linktype'];
                $params['termsandconditions_link'] = $data['termsandconditions_link'];
                $params['termsandconditions_page'] = $data['termsandconditions_page'];
            }

                $params = json_encode($params);
                $data['userfieldparams'] = $params;
            //}
        }else{
            $fieldname = $data['field'];
        }

        $data['field'] = $fieldname;
        $data['section'] = 10;

        $row = JSSTincluder::getJSTable('fieldsordering');

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
            JSSTmessage::setMessage(__('Field has not been stored', 'js-support-ticket'), 'error');
        } else {
            JSSTmessage::setMessage(__('Field has been stored', 'js-support-ticket'), 'updated');
        }
        return 1;
    }

    function updateField($data) {
        if (empty($data)) {
            return false;
        }
        $inquery = '';
        $clasue = '';
        if(isset($data['fieldtitle']) && $data['fieldtitle'] != null){
            $inquery .= $clasue." fieldtitle = '". $data['fieldtitle']."'";
            $clasue = ' , ';
        }
        if(isset($data['published']) && $data['published'] != null){
            $inquery .= $clasue." published = ". $data['published'];
            $clasue = ' , ';
        }
        if(isset($data['isvisitorpublished']) && $data['isvisitorpublished'] != null){
            $inquery .= $clasue." isvisitorpublished = ". $data['isvisitorpublished'];
            $clasue = ' , ';
        }
        if(isset($data['required']) && $data['required'] != null){
            $inquery .= $clasue." required = ". $data['required'];
            $clasue = ' , ';
        }
        if(isset($data['search_user']) && $data['search_user'] != null){
            $inquery .= $clasue." search_user = ". $data['search_user'];
            $clasue = ' , ';
        }
        if(isset($data['search_visitor']) && $data['search_visitor'] != null){
            $inquery .= $clasue." search_visitor = ". $data['search_visitor'];
            $clasue = ' , ';
        }
        if(isset($data['showonlisting']) && $data['showonlisting'] != null){
            $inquery .= $clasue." showonlisting = ". $data['showonlisting'];
            $clasue = ' , ';
        }

        $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET ".$inquery." WHERE id = " . $data['id'] ;
        jssupportticket::$_db->query($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        JSSTmessage::setMessage(__('Field has been updated', 'js-support-ticket'),'updated');

        return;
    }

    function updateParentField($parentfield, $field, $fieldfor) {
        if(!is_numeric($parentfield)) return false;

        $query = "UPDATE `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` SET depandant_field = '" . $field . "' WHERE id = " . $parentfield." AND fieldfor = ".$fieldfor;
        jssupportticket::$_db->query($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return true;
    }

    function getFieldsForComboByFieldFor() {
        $fieldfor = JSSTrequest::getVar('fieldfor');
        $parentfield = JSSTrequest::getVar('parentfield');
        $wherequery = '';
        if(isset($parentfield) && $parentfield !='' ){
            $query = "SELECT id FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE fieldfor = $fieldfor AND (userfieldtype = 'radio' OR userfieldtype = 'combo'OR userfieldtype = 'depandant_field') AND depandant_field = '" . $parentfield . "' ";
            $parent = jssupportticket::$_db->get_var($query);
            $wherequery = ' OR id = '.$parent;
        }
        $query = "SELECT fieldtitle AS text ,id FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE fieldfor = $fieldfor AND (userfieldtype = 'radio' OR userfieldtype = 'combo' OR userfieldtype = 'depandant_field') AND (depandant_field = '' ".$wherequery." ) ";
        $data = jssupportticket::$_db->get_results($query);
        if(isset($parentfield) && $parentfield !='' ){
            $query = "SELECT id FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE fieldfor = $fieldfor AND (userfieldtype = 'radio' OR userfieldtype = 'combo'OR userfieldtype = 'depandant_field') AND depandant_field = '" . $parentfield . "' ";
            $parent = jssupportticket::$_db->get_var($query);
        }
        $jsFunction = 'getDataOfSelectedField();';
        $html = JSSTformfield::select('parentfield', $data, (isset($parent) && $parent !='') ? $parent : '', __('Select', 'js-support-ticket') .'&nbsp;'. __('Parent Field', 'js-support-ticket'), array('onchange' => $jsFunction, 'class' => 'inputbox one js-form-select-field', 'data-validation' => 'required'));
        $data = json_encode($html);
        return $data;
    }

    function getSectionToFillValues() {
        $field = JSSTrequest::getVar('pfield');
        if(!is_numeric($field)){
            return false;
        }
        $query = "SELECT userfieldparams FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE id=$field";
        $data = jssupportticket::$_db->get_var($query);
        $datas = json_decode($data);
        $html = '';
        $fieldsvar = '';
        $comma = '';
        foreach ($datas as $data) {
            if(is_array($data)){
                for ($i = 0; $i < count($data); $i++) {
                    $fieldsvar .= $comma . "$data[$i]";
                    $textvar = $data[$i];
                    $textvar = str_replace(' ','__',$textvar);
                    $textvar = str_replace('.','___',$textvar);
                    $divid = $textvar;
                    $textvar .='[]';
                    $html .= "<div class='jsst-user-dd-field-wrap'>";
                    $html .= "<div class='jsst-user-dd-field-title'>" . $data[$i] . "</div>";
                    $html .= "<div class='jsst-user-dd-field-value combo-options-fields' id=" . $divid . ">
                                    <span class='input-field-wrapper'>
                                        " . JSSTformfield::text($textvar, '', array('class' => 'inputbox one user-field')) . "
                                        <img class='input-field-remove-img' src='" . jssupportticket::$_pluginpath . "includes/images/delete.png' />
                                    </span>
                                    <input type='button' class='jsst-button-link button user-field-val-button' id='depandant-field-button' onClick='getNextField(\"" . $divid . "\", this);'  value='Add More' />
                                </div>";
                    $html .= "</div>";
                    $comma = ',';
                }
            }else{
                $fieldsvar .= $comma . "$data";
                $textvar = $data;
                $textvar = str_replace(' ','__',$textvar);
                $textvar = str_replace('.','___',$textvar);
                $divid = $textvar;
                $textvar .='[]';
                $html .= "<div class='jsst-user-dd-field-wrap'>";
                $html .= "<div class='jsst-user-dd-field-title'>" . $data . "</div>";
                $html .= "<div class='jsst-user-dd-field-value combo-options-fields' id=" . $divid . ">
                                <span class='input-field-wrapper'>
                                    " . JSSTformfield::text($textvar, '', array('class' => 'inputbox one user-field')) . "
                                    <img class='input-field-remove-img' src='" . jssupportticket::$_pluginpath . "includes/images/delete.png' />
                                </span>
                                <input type='button' class='jsst-button-link button user-field-val-button' id='depandant-field-button' onClick='getNextField(\"" . $divid . "\", this);'  value='Add More' />
                            </div>";
                $html .= "</div>";
                $comma = ',';
            }

        }
        $html .= " <input type='hidden' name='arraynames' value='" . $fieldsvar . "' />";
        $html = json_encode($html);
        return $html;
    }

    function getOptionsForFieldEdit() {
        $field = JSSTrequest::getVar('field');
		if(!is_numeric($field)) return false;
        $yesno = array(
            (object) array('id' => 1, 'text' => __('Yes', 'js-support-ticket')),
            (object) array('id' => 0, 'text' => __('No', 'js-support-ticket')));

        $query = "SELECT * FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE id=$field";
        $data = jssupportticket::$_db->get_row($query);

        $html = '<div class="userpopup-top">
                    <div class="userpopup-heading" >
                    ' . __("Edit Field", 'js-support-ticket') . '
                    </div>
                    <img id="popup_cross" class="userpopup-close" onClick="close_popup();" src="' . jssupportticket::$_pluginpath . 'includes/images/close-icon-white.png" alt="'.__('Close','js-support-ticket').'">
                </div>';
        $html .= '<form id="adminForm" class="popup-field-from" method="post" action="' . admin_url("?page=fieldordering&task=savefeild") . '">';
        $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . __('Field Title', 'js-support-ticket') . '<font class="required-notifier">*</font></div>
                    <div class="popup-field-obj">' . JSSTformfield::text('fieldtitle', isset($data->fieldtitle) ? $data->fieldtitle : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                </div>';
        if ($data->cannotunpublish == 0 || $data->cannotshowonlisting == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . __('User Published', 'js-support-ticket') . '</div>
                        <div class="popup-field-obj">' . JSSTformfield::select('published', $yesno, isset($data->published) ? $data->published : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . __('Visitor Published', 'js-support-ticket') . '</div>
                        <div class="popup-field-obj">' . JSSTformfield::select('isvisitorpublished', $yesno, isset($data->isvisitorpublished) ? $data->isvisitorpublished : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';

            $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . __('Required', 'js-support-ticket') . '</div>
                    <div class="popup-field-obj">' . JSSTformfield::select('required', $yesno, isset($data->required) ? $data->required : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                </div>';
        }
        if ($data->cannotsearch == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . __('User Search', 'js-support-ticket') . '</div>
                        <div class="popup-field-obj">' . JSSTformfield::select('search_user', $yesno, isset($data->search_user) ? $data->search_user : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . __('Visitor Search', 'js-support-ticket') . '</div>
                        <div class="popup-field-obj">' . JSSTformfield::select('search_visitor', $yesno, isset($data->search_visitor) ? $data->search_visitor : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        }
        if ($data->isuserfield == 1 || $data->cannotshowonlisting == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . __('Show On Listing', 'js-support-ticket') . '</div>
                        <div class="popup-field-obj">' . JSSTformfield::select('showonlisting', $yesno, isset($data->showonlisting) ? $data->showonlisting : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')) . '</div>
                    </div>';
        }
        $html .= JSSTformfield::hidden('form_request', 'jssupportticket');
        $html .= JSSTformfield::hidden('id', $data->id);
        $html .= JSSTformfield::hidden('isuserfield', $data->isuserfield);
        $html .= JSSTformfield::hidden('fieldfor', $data->fieldfor);
        $html .='<div class="js-submit-container js-col-lg-10 js-col-md-10 js-col-md-offset-1 js-col-md-offset-1">
                    ' . JSSTformfield::submitbutton('save', __('Save', 'js-support-ticket'), array('class' => 'button'));
        if ($data->isuserfield == 1) {
            $html .= '<a class="button" style="margin-left:10px;" id="user-field-anchor" href="?page=fieldordering&jstlay=adduserfeild&jssupportticketid=' . $data->id .'&fieldfor='.$data->fieldfor.'"> ' . __('Advanced', 'js-support-ticket') . ' </a>';
        }

        $html .='</div>
            </form>';
        return json_encode($html);
    }

    function deleteUserField($id){
        if (is_numeric($id) == false)
           return false;
        $query = "SELECT field,fieldfor FROM `".jssupportticket::$_db->prefix."js_ticket_fieldsordering` WHERE id = $id";
        $result = jssupportticket::$_db->get_row($query);
        if ($this->userFieldCanDelete($result) == true) {
            $row = JSSTincluder::getJSTable('fieldsordering');
            if (!$row->delete($id)) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
                JSSTmessage::setMessage(__('Field has not been deleted', 'js-support-ticket'),'error');
            }else{
                JSSTmessage::setMessage(__('Field has been deleted', 'js-support-ticket'),'updated');
            }
        }else{
            JSSTmessage::setMessage(__('Field has not been deleted', 'js-support-ticket'),'error');
        }
        return false;
    }

    function enforceDeleteUserField($id){
        if (is_numeric($id) == false)
           return false;
        $query = "SELECT field,fieldfor FROM `".jssupportticket::$_db->prefix."js_ticket_fieldsordering` WHERE id = $id";
        $result = jssupportticket::$_db->get_row($query);
        if ($this->userFieldCanDelete($result) == true) {
            $row = JSSTincluder::getJSTable('fieldsordering');
            $row->delete($id);
        }
        return false;
    }

    function userFieldCanDelete($field) {
        $fieldname = $field->field;
        $fieldfor = $field->fieldfor;

        //if($fieldfor == 1){//for deleting a ticket field
            $table = "tickets";
        //}
        $query = ' SELECT
                    ( SELECT COUNT(id) FROM `' . jssupportticket::$_db->prefix . 'js_ticket_'.$table.'` WHERE
                        params LIKE \'%"' . $fieldname . '":%\'
                    )
                    AS total';
        $total = jssupportticket::$_db->get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getUserfieldsfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        if (!is_user_logged_in()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $query = "SELECT field,userfieldparams,userfieldtype,fieldtitle FROM `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 AND " . $published;
        $fields = jssupportticket::$_db->get_results($query);
        return $fields;
    }

    function getUserUnpublishFieldsfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        if (!is_user_logged_in()) {
            $published = ' isvisitorpublished = 0 ';
        } else {
            $published = ' published = 0 ';
        }
        $query = "SELECT field FROM `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` WHERE fieldfor = " . $fieldfor . " AND isuserfield = 1 AND " . $published;
        $fields = jssupportticket::$_db->get_results($query);
        return $fields;
    }

    function getFieldTitleByFieldfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;

        $query = "SELECT field,fieldtitle FROM `" . jssupportticket::$_db->prefix . "js_ticket_fieldsordering` WHERE fieldfor = " . $fieldfor ;
        $fields = jssupportticket::$_db->get_results($query);
        $fielddata = array();
        foreach ($fields as $value) {
            $fielddata[$value->field] = $value->fieldtitle;
        }
        return $fielddata;
    }

    function getUserFieldbyId($id,$fieldfor) {
        if ($id) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT * FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE id = " . $id;
            jssupportticket::$_data[0]['userfield'] = jssupportticket::$_db->get_row($query);
            $params = jssupportticket::$_data[0]['userfield']->userfieldparams;
            jssupportticket::$_data[0]['userfieldparams'] = !empty($params) ? json_decode($params, True) : '';
        }
        jssupportticket::$_data[0]['fieldfor'] = $fieldfor;
        return;
    }
    function getFieldsForListing($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $query = "SELECT field, showonlisting FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE  fieldfor =  " . $fieldfor ." ORDER BY ordering";
        $fields = jssupportticket::$_db->get_results($query);
        $fielddata = array();
        foreach ($fields AS $field) {
            $fielddata[$field->field] = $field->showonlisting;
        }
        return $fielddata;
    }

    function DataForDepandantField(){
        $val = JSSTrequest::getVar('fvalue');
        $childfield = JSSTrequest::getVar('child');
        $query = "SELECT userfieldparams,fieldtitle,depandant_field,field FROM `".jssupportticket::$_db->prefix."js_ticket_fieldsordering` WHERE field = '".$childfield."'";
        $data = jssupportticket::$_db->get_row($query);
        $decoded_data = json_decode($data->userfieldparams);
        $comboOptions = array();
        $flag = 0;
        foreach ($decoded_data as $key => $value) {
            if($key==$val){
               for ($i=0; $i <count($value) ; $i++) {
                   $comboOptions[] = (object)array('id' => $value[$i], 'text' => $value[$i]);
                   $flag = 1;
               }
            }
        }
        $jsFunction = '';
        if ($data->depandant_field != null) {
            $jsFunction = "getDataForDepandantField('" . $data->field . "','" . $data->depandant_field . "',1);";
        }
        $textvar =  ($flag == 1) ?  __('Select', 'js-support-ticket').' '.$data->fieldtitle : '';
        $html = JSSTformfield::select($childfield, $comboOptions, '',$textvar, array('data-validation' => '','class' => 'inputbox one js-form-select-field js-ticket-custom-select', 'onchange' => $jsFunction)); $phtml = json_encode($html);
        return $phtml;
    }

}

?>
