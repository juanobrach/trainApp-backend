<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTcustomfields {
    function formCustomFields($field) {
        if($field->isuserfield != 1){
            return false;
        }
        $cssclass = "";
        $html = '';
        $div1 =  ($field->size == 100) ? ' js-ticket-from-field-wrp-full-width js-ticket-from-field-wrp' : 'js-ticket-from-field-wrp' ;
        $div2 = 'js-ticket-from-field-title';
        $div3 = 'js-ticket-from-field';


        if(is_admin()){
            $div1 = 'js-form-wrapper js-form-custm-flds-wrp';
            $div2 = 'js-form-title';
            $div3 = 'js-form-value';
       }


        //echo $div1;exit();
        $required = $field->required;
        if($field->userfieldtype == 'termsandconditions'){
            if (isset(jssupportticket::$_data[0]->id)) {
                return false;
            }
            $required = 1;
        }

        $html = '<div class="' . $div1 .  '">
               <div class="' . $div2 . '">';
        if ($required == 1) {
            $html .= __($field->fieldtitle,'js-support-ticket') . '<span style="color: red;" >*</span>';
            if ($field->userfieldtype == 'email')
                $cssclass = "email";
            else
                $cssclass = "required";
        }else {
            $html .= __($field->fieldtitle,'js-support-ticket');
            if ($field->userfieldtype == 'email')
                $cssclass = "email";
            else
                $cssclass = "";
        }
        $html .= ' </div><div class="' . $div3 . '">';
        //$readonly = $field->readonly ? "'readonly => 'readonly'" : "";
        $readonly = "";
        $maxlength = $field->maxlength ? "$field->maxlength" : "";
        $fvalue = "";
        $value = "";
        $userdataid = "";
        if (isset(jssupportticket::$_data[0]->id)) {
            $userfielddataarray = json_decode(jssupportticket::$_data[0]->params);
            $uffield = $field->field;
            if (isset($userfielddataarray->$uffield) || !empty($userfielddataarray->$uffield)) {
                $value = $userfielddataarray->$uffield;
            } else {
                $value = '';
            }
        }

        switch ($field->userfieldtype) {
            case 'text':
            case 'email':
                $html .= JSSTformfield::text($field->field, $value, array('class' => 'inputbox js-form-input-field js-ticket-form-field-input one', 'data-validation' => $cssclass, 'maxlength' => $maxlength, $readonly));
                break;
            case 'date':
                $html .= JSSTformfield::text($field->field, $value, array('class' => 'custom_date js-form-date-field  js-ticket-input-field  one', 'data-validation' => $cssclass));
                break;
            case 'textarea':
                $html .= JSSTformfield::textarea($field->field, $value, array('class' => 'inputbox js-form-textarea-field js-ticket-custom-textarea one', 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    $total_options= count($obj_option);
                    if($total_options % 2 == 0)
                    {
                        $field_width = 'style = " width:calc(100% / 2 - 10px); margin:0px 5px;"';
                    }else
                    {
                        $field_width = 'style = " width:calc(100% / 3 - 10px); margin:0px 5px;"';
                    }
                    $i = 0;
                    $valuearray = explode(', ',$value);
                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<div class="jsst-formfield-radio-button-wrap js-ticket-custom-radio-box" '.$field_width.'>';
                        $html .= '<input type="checkbox" ' . $check . ' class="radiobutton js-ticket-append-radio-btn" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]">';
                        $html .= '<label for="' . $field->field . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => __($field->fieldtitle,'js-support-ticket'));
                    $html .= JSSTformfield::checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    $total_options= count($obj_option);
                    if($total_options % 2 == 0)
                    {
                        $field_width = 'style = " width:calc(100% / 2 - 10px); margin:0px 5px;"';
                    }else{
                        $field_width = 'style = " width:calc(100% / 3 - 10px); margin:0px 5px;"';
                    }
                    $i = 0;
                    $jsFunction = '';
                    if ($field->depandant_field != null) {
                        $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2);";
                    }
                    $valuearray = explode(', ',$value);
                    foreach ($obj_option AS $option) {
                        $check = '';
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<div class="jsst-formfield-radio-button-wrap js-ticket-radio-box" '.$field_width.'>';
                            $html .= '<input type="radio" ' . $check . ' class="radiobutton js-ticket-radio-btn '.$cssclass.'" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '" data-validation ="'.$cssclass.'" onclick = "'.$jsFunction.'"> ';
                            $html .= '<label for="' . $field->field . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                }
                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1);";
                }
                //end
                $html .= JSSTformfield::select($field->field, $comboOptions, $value, __('Select', 'js-support-ticket') . ' ' . __($field->fieldtitle,'js-support-ticket') , array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox js-form-select-field js-ticket-custom-select one'));
                break;
            case 'depandant_field':
                $comboOptions = array();
                if ($value != null) {
                    if (!empty($field->userfieldparams)) {
                        $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "');";
                }
                //end
                $html .= JSSTformfield::select($field->field, $comboOptions, $value, __('Select', 'js-support-ticket') . ' ' . __($field->fieldtitle,'js-support-ticket') , array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox js-form-select-field js-ticket-custom-select one'));
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $valuearray = explode(', ', $value);
                $html .= JSSTformfield::select($array, $comboOptions, $valuearray, __('Select', 'js-support-ticket') . ' ' . __($field->fieldtitle,'js-support-ticket') , array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => 'inputbox js-form-input-field one'));
                break;
            case 'file':
                $html .= '<span class="js-attachment-file-box">';
                    $html .= '<input type="file" name="'.$field->field.'" id="'.$field->field.'"/>';
                $html .= '</span>';
                if($value != null){
                    $html .= JSSTformfield::hidden($field->field.'_1', 0);
                    $html .= JSSTformfield::hidden($field->field.'_2',$value);
                    $jsFunction = "deleteCutomUploadedFile('".$field->field."_1')";
                    $html .='<span class='.$field->field.'_1>'.$value.'( ';
                    $html .= "<a href='#' onClick=".$jsFunction." >". __('Delete', 'js-support-ticket')."</a>";
                    $html .= ' )</span>';
                }
                break;
                case 'termsandconditions':
                    if (isset(jssupportticket::$_data[0]->id)) {
                        break;
                    }
                    if (!empty($field->userfieldparams)) {
                        $obj_option = json_decode($field->userfieldparams,true);

                        $url = $obj_option['termsandconditions_link'];
                        if( isset($obj_option['termsandconditions_linktype']) && $obj_option['termsandconditions_linktype'] == 2){
                             $url  = get_permalink($obj_option['termsandconditions_page']);
                        }

                        $link_start = '<a href="' . $url . '" class="termsandconditions_link_anchor" target="_blank" >';
                        $link_end = '</a>';

                        if(strstr($obj_option['termsandconditions_text'], '[link]') && strstr($obj_option['termsandconditions_text'], '[/link]')){
                            $label_string = str_replace('[link]', $link_start, $obj_option['termsandconditions_text']);
                            $label_string = str_replace('[/link]', $link_end, $label_string);
                        }else{
                            $label_string = $obj_option['termsandconditions_text'].'&nbsp;'.$link_start.$field->fieldtitle.$link_end;
                        }
                        $c_field_required = '';
                        if($field->required == 1){
                            $c_field_required = 'required';
                        }
                        // ticket terms and conditonions are required.
                        if($field->fieldfor == 1){
                            $c_field_required = 'required';
                        }

                        $html .= '<div class="js-ticket-custom-terms-and-condition-box jsst-formfield-radio-button-wrap">';
                        $html .= '<input type="checkbox" class="radiobutton js-ticket-append-radio-btn" value="1" id="' . $field->field . '" name="' . $field->field . '" required="'.$c_field_required.'" data-validation="'.$c_field_required.'">';
                        $html .= '<label for="' . $field->field . '" id="foruf_checkbox1">' . $label_string . '</label>';
                        $html .= '</div>';
                    }
                    break;
        }
        $html .= '</div></div>';
        echo $html;

    }

    function formCustomFieldsForSearch($field, &$i, $isadmin = 0) {
        if ($field->isuserfield != 1)
            return false;
        $cssclass = "";
        $html = '';
        $i++;
        $required = $field->required;
        $div1 = 'js-col-md-12 js-filter-field-wrp';
        $div3 = 'js-filter-value';

        $html = '<div class="' . $div1 . '"> ';
        $html .= ' <div class="' . $div3 . '">';
        if($isadmin == 1){
            $html = ''; // only field send
        }
        $readonly = ''; //$field->readonly ? "'readonly => 'readonly'" : "";
        $maxlength = ''; //$field->maxlength ? "'maxlength' => '".$field->maxlength : "";
        $fvalue = "";
        $value = null;
        $userdataid = "";
        $userfielddataarray = array();
        if (isset(jssupportticket::$_data['filter']['params'])) {
            $userfielddataarray = jssupportticket::$_data['filter']['params'];
            $uffield = $field->field;
            //had to user || oprator bcz of radio buttons

            if (isset($userfielddataarray[$uffield]) || !empty($userfielddataarray[$uffield])) {
                $value = $userfielddataarray[$uffield];
            } else {
                $value = '';
            }
        }
        switch ($field->userfieldtype) {
            case 'text':
            case 'file':
            case 'email':
                $html .= JSSTformfield::text($field->field, $value, array('class' => 'inputbox js-form-input-field one', 'data-validation' => $cssclass,'placeholder' => __($field->fieldtitle,'js-support-ticket') , $maxlength, $readonly));
                break;
            case 'date':
                $html .= JSSTformfield::text($field->field, $value, array('class' => 'custom_date js-form-date-field one', 'data-validation' => $cssclass,'placeholder' => __($field->fieldtitle,'js-support-ticket')));
                break;
            case 'editor':
                $html .= wp_editor(isset($value) ? $value : '', $field->field, array('media_buttons' => false, 'data-validation' => $cssclass));
                break;
            case 'textarea':
                $html .= JSSTformfield::textarea($field->field, $value, array('class' => 'inputbox js-form-input-field one', 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly));
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    if(empty($value))
                        $value = array();
                    $html .= '<div class="js-form-cust-rad-fld-wrp">';
                    foreach ($obj_option AS $option) {
                        if( in_array($option, $value)){
                            $check = 'checked="true"';
                        }else{
                            $check = '';
                        }
                        $html .= '<input type="checkbox" ' . $check . ' class="radiobutton" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '[]">';
                        $html .= '<label for="' . $field->field . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                        $i++;
                    }
                    $html .= '</div>';
                } else {
                    $comboOptions = array('1' => __($field->fieldtitle,'js-support-ticket') );
                    $html .= JSSTformfield::checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton'));
                }
                break;
            case 'radio':
                if($isadmin == 1){
                    $comboOptions = array();
                    if (!empty($field->userfieldparams)) {
                        $obj_option = json_decode($field->userfieldparams);
                        for ($i = 0; $i < count($obj_option); $i++) {
                            $comboOptions[$obj_option[$i]] = "$obj_option[$i]";
                        }
                    }
                    $jsFunction = '';
                    if ($field->depandant_field != null) {
                        $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2);";
                    }
                    $html .= '<div class="js-form-cust-rad-fld-wrp">';
                    $html .= JSSTformfield::radiobutton($field->field, $comboOptions, $value, array('data-validation' => $cssclass, "autocomplete" => "off", 'onclick' => $jsFunction));
                    $html .= '</div>';
                }else{ 
                    $comboOptions = array();
                    if (!empty($field->userfieldparams)) {
                        $obj_option = json_decode($field->userfieldparams);
                        $total_options= count($obj_option);
                        if($total_options % 2 == 0)
                        {
                            $field_width = 'style = " width:calc(100% / 2 - 10px); margin:0px 5px;"';
                        }else
                        {
                            $field_width = 'style = " width:calc(100% / 3 - 10px); margin:0px 5px;"';
                        }
                        $i = 0;
                        $jsFunction = '';
                        if ($field->depandant_field != null) {
                            $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',2);";
                        }
                        $valuearray = explode(', ',$value);
                        $html .= '<div class="js-form-cust-rad-fld-wrp">';
                        foreach ($obj_option AS $option) {
                            $check = '';
                            if(in_array($option, $valuearray)){
                                $check = 'checked';
                            }
                            $html .= '<div class="js-ticket-radio-box" '.$field_width.'>';
                                $html .= '<input type="radio" ' . $check . ' class="radiobutton js-ticket-radio-btn '.$cssclass.'" value="' . $option . '" id="' . $field->field . '_' . $i . '" name="' . $field->field . '" data-validation ="'.$cssclass.'" onclick = "'.$jsFunction.'"> ';
                                $html .= '<label for="' . $field->field . '_' . $i . '" id="foruf_checkbox1">' . $option . '</label>';
                            $html .= '</div>';
                            $i++;
                        }
                        $html .= '</div>';
                    }
                }

                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "',1);";
                }
                //end
                $html .= JSSTformfield::select($field->field, $comboOptions, $value, __('Select', 'js-support-ticket') . ' ' . __($field->fieldtitle,'js-support-ticket') , array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox js-form-select-field one'));
                break;
            case 'depandant_field':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = $this->getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                    if (!empty($obj_option)) {
                        foreach ($obj_option as $opt) {
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $jsFunction = '';
                if ($field->depandant_field != null) {
                    $jsFunction = "getDataForDepandantField('" . $field->field . "','" . $field->depandant_field . "');";
                }
                //end
                $html .= JSSTformfield::select($field->field, $comboOptions, $value, __('Select', 'js-support-ticket') . ' ' . __($field->fieldtitle,'js-support-ticket') , array('data-validation' => $cssclass, 'onchange' => $jsFunction, 'class' => 'inputbox js-form-select-field one'));
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $html .= JSSTformfield::select($array, $comboOptions, $value, __('Select', 'js-support-ticket') . ' ' . __($field->fieldtitle,'js-support-ticket') , array('data-validation' => $cssclass, 'multiple' => 'multiple','class' => 'inputbox js-form-multi-select-field'));
                break;
        }
        if($isadmin == 1){
            echo $html;
            return;
        }
        $html .= '</div></div>';
        echo $html;

    }

    function showCustomFields($field, $fieldfor, $params) {

        $fvalue = '';

        if(!empty($params)){
            $data = json_decode($params,true);
            if(is_array($data) && $data != ''){
                if(array_key_exists($field->field, $data)){
                    $fvalue = $data[$field->field];
                }
            }
        }
        if($field->userfieldtype=='file'){ 
        
           if($fvalue !=null){
                $path = admin_url("?page=ticket&action=jstask&task=downloadbyname&id=".jssupportticket::$_data['custom']['ticketid']."&name=".$fvalue);
                $html = '
                    <div class="js_ticketattachment">
                        ' .  $fvalue . '
                        <a class="button" target="_blank" href="' . esc_url($path) . '">' . __('Download', 'js-support-ticket') . '</a>
                    </div>';
                $fvalue = $html;
            }
        }elseif($field->userfieldtype=='date' && !empty($fvalue)){
            $fvalue = date_i18n(jssupportticket::$_config['date_format'],strtotime($fvalue));
        }
        $return_array['title'] = $field->fieldtitle;
        $return_array['value'] = $fvalue;
        return $return_array;
    }

    function userFieldsData($fieldfor, $listing = null) {
        if (!is_user_logged_in()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $inquery = '';
        if ($listing == 1) {
            $inquery = ' AND showonlisting = 1 ';
        }
        $query = "SELECT field,fieldtitle,isuserfield,userfieldtype,userfieldparams  FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND fieldfor =" . $fieldfor . $inquery." ORDER BY ordering";
        $data = jssupportticket::$_db->get_results($query);
        return $data;
    }

    function userFieldsForSearch($fieldfor) {
        if (!is_user_logged_in()) {
            $inquery = ' isvisitorpublished = 1 AND search_user =1';
        } else {
            $inquery = ' published = 1 AND search_visitor =1';
        }

        $query = "SELECT `rows`,`cols`,required,field,fieldtitle,isuserfield,userfieldtype,userfieldparams,depandant_field  FROM " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE isuserfield = 1 AND " . $inquery . " AND fieldfor =" . $fieldfor ." ORDER BY ordering ";
        $data = jssupportticket::$_db->get_results($query);
        return $data;
    }

    function getDataForDepandantFieldByParentField($fieldfor, $data) {
        if (!is_user_logged_in()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $value = '';
        $returnarray = array();
        $query = "SELECT field from " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND depandant_field ='" . $fieldfor . "'";
        $field = jssupportticket::$_db->get_var($query);
        if ($data != null) {
            foreach ($data as $key => $val) {
                if ($key == $field) {
                    $value = $val;
                }
            }
        }
        $query = "SELECT userfieldparams from " . jssupportticket::$_db->prefix . "js_ticket_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND field ='" . $fieldfor . "'";
        $field = jssupportticket::$_db->get_var($query);
        $fieldarray = json_decode($field);
        foreach ($fieldarray as $key => $val) {
            if ($value == $key)
                $returnarray = $val;
        }
        return $returnarray;
    }

}

?>
