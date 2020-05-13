<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTemailModel {
    /*
      $mailfor
      For which purpose you want to send mail
      1 => Ticket

      $action
      For which action of $mailfor you want to send the mail
      1 => New Ticket Create
      2 => Close Ticket
      3 => Delete Ticket
      4 => Reply Ticket (Admin/Staff Member)
      5 => Reply Ticket (Ticket member)
      6 => Lock Ticket

      $id
      id required when recever emailaddress is stored in record
     */

    function sendMail($mailfor, $action, $id = null, $tablename = null) {
        if (!is_numeric($mailfor))
            return false;
        if (!is_numeric($action))
            return false;
        if ($id != null)
            if (!is_numeric($id))
                return false;
        $pageid = jssupportticket::getPageid();
        switch ($mailfor) {
            case 1: // Mail For Tickets
                switch ($action) {
                    case 1: // New Ticket Created
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $Email = $ticketRecord->email;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Message = $ticketRecord->message;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );

                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        // New ticket mail to admin
                        if (jssupportticket::$_config['new_ticket_mail_to_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $template = $this->getTemplateForEmail('ticket-new-admin');
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###admin####" />';
                            $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###admin#### ></span>';
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        //Check to send email to department
                        $query = "SELECT dept.sendmail, email.email AS emailaddress
                                    FROM `".jssupportticket::$_db->prefix."js_ticket_tickets` AS ticket
                                    LEFT JOIN `".jssupportticket::$_db->prefix."js_ticket_departments` AS dept ON dept.id = ticket.departmentid
                                    LEFT JOIN `".jssupportticket::$_db->prefix."js_ticket_email` AS email ON email.id = dept.emailid
                                    WHERE ticket.id = ".$id;
                        $dept_result = jssupportticket::$_db->get_row($query);
                        if($dept_result){
                            if(isset($dept_result->sendmail) && $dept_result->sendmail == 1){
                                $deptemail = $dept_result->emailaddress;
                                $template = $this->getTemplateForEmail('ticket-new-admin');
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                                $matcharray['{TICKETURL}'] = $link;
                                $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###admin####" />';
                                $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###admin#### ></span>';
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($deptemail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                            }
                        }
                        // New ticket mail to User
                        $template = $this->getTemplateForEmail('ticket-new');
                        //Parsing template
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                    //token encrption
                        $tokenarray['emailaddress']=$Email;
                        $tokenarray['trackingid']=$TrackingId;
                        $token = json_encode($tokenarray);
                        include_once jssupportticket::$_path . 'includes/encoder.php';
                        $encoder = new JSSTEncoder();
                        $encryptedtext = $encoder->encrypt($token);
                    // end token encryotion
                        $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'showticketstatus','action'=>'jstask','token'=>$encryptedtext,'jsstpageid'=>jssupportticket::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###user####" />';
                        $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###user#### ></span>';
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);

                        $template = $this->getTemplateForEmail('ticket-staff');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###" />';
                        //New ticket mail to staff member
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['new_ticket_mail_to_staff_members'] == 1) {
                            // Get All Staff member of the department of Current Ticket
                            $agentmembers = JSSTincluder::getJSModel('agent')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            if(is_array($agentmembers) && !empty($agentmembers)){
                                foreach ($agentmembers AS $agent) {
                                    if($agent->canemail == 1){
                                        $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                                        $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###staff#### ></span>';
                                        $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                                    }
                                }
                            }
                        }
                        break;
                    case 2: // Close Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $Email = $ticketRecord->email;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Message = $ticketRecord->message;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')

                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('close-tk');
                        // Close ticket mail to admin
                        if (jssupportticket::$_config['ticket_close_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{FEEDBACKURL}'] = ' ';
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // Close ticket mail to staff member
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_close_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{FEEDBACKURL}'] = ' ';
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_close_user'] == 1) {
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $tokenarray['emailaddress']=$Email;
                            $tokenarray['trackingid']=$TrackingId;
                            $token = json_encode($tokenarray);
                            include_once jssupportticket::$_path . 'includes/encoder.php';
                            $encoder = new JSSTEncoder();
                            $encryptedtext = $encoder->encrypt($token);
                            if(in_array('feedback', jssupportticket::$_active_addons)){
                                $flink = "<a href=" . esc_url(jssupportticket::makeUrl(array('jstmod'=>'feedback', 'task'=>'showfeedbackform','action'=>'jstask','token'=>$encryptedtext,'jsstpageid'=>jssupportticket::getPageid()))) . ">".__('Click here to give us feedback','js-support-ticket')." </a>";
                            }else{
                                $flink = " ";
                            }

                            $matcharray['{TICKETURL}'] = $link;
                            $matcharray['{FEEDBACKURL}'] = $flink;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 3: // Delete Ticket
                        $TrackingId = jssupportticket::$_data['ticketid'];
                        $Email = jssupportticket::$_data['ticketemail'];
                        $Subject = jssupportticket::$_data['ticketsubject'];
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{TRACKINGID}' => $TrackingId,
                            '{SUBJECT}' => $Subject,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('delete-tk');
                        // Delete ticket mail to admin
                        if (jssupportticket::$_config['ticket_delete_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // Delete ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_delete_staff'] == 1) {
                            $agent_id = jssupportticket::$_data['staffid'];
                            $agentEmail = $this->getStaffEmailAddressByStaffId($agent_id);
                            if( ! empty($agentEmail)){
                                $msgSubject = $template->subject;
                                $msgBody = $template->body;
                                $this->replaceMatches($msgSubject, $matcharray);
                                $this->replaceMatches($msgBody, $matcharray);
                                $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                            }
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_delete_user'] == 1) {
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 4: // Reply Ticket (Admin/Staff Member)
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }

                        $Email = $ticketRecord->email;
                        $Message = $this->getLatestReplyByTicketId($id);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('reply-tk');
                        // Reply ticket mail to admin
                        if (jssupportticket::$_config['ticket_response_to_staff_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###admin####" />';
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // Reply ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_response_to_staff_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        $template = $this->getTemplateForEmail('responce-tk');
                        if (jssupportticket::$_config['ticket_response_to_staff_user'] == 1) {
                        //token encrption
                            $tokenarray['emailaddress']=$Email;
                            $tokenarray['trackingid']=$TrackingId;
                            $token = json_encode($tokenarray);
                            include_once jssupportticket::$_path . 'includes/encoder.php';
                            $encoder = new JSSTEncoder();
                            $encryptedtext = $encoder->encrypt($token);
                        // end token encryotion
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'showticketstatus','action'=>'jstask','token'=>$encryptedtext,'jsstpageid'=>jssupportticket::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###user####" />';
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 5: // Reply Ticket (Ticket Member)
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Message = $this->getLatestReplyByTicketId($id);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{MESSAGE}' => $Message,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('reply-tk');
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_reply_ticket_user_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###admin####" />';
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_reply_ticket_user_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_reply_ticket_user_user'] == 1) {
                        //token encrption
                            $tokenarray['emailaddress']=$Email;
                            $tokenarray['trackingid']=$TrackingId;
                            $token = json_encode($tokenarray);
                            include_once jssupportticket::$_path . 'includes/encoder.php';
                            $encoder = new JSSTEncoder();
                            $encryptedtext = $encoder->encrypt($token);
                        // end token encryotion
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket' ,'task'=>'showticketstatus','action'=>'jstask','token'=>$encryptedtext,'jsstpageid'=>jssupportticket::getPageid())));
                            $matcharray['{TICKETURL}'] = $link;
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###user####" />';
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 6: // Lock Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('lock-tk');
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_lock_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_lock_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_lock_user'] == 1) {
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 7: // Unlock Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Username = $ticketRecord->name;
                        $Subject = $ticketRecord->subject;
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => $Username,
                            '{SUBJECT}' => $Subject,
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{EMAIL}' => $Email,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('unlock-tk');
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_unlock_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_unlock_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_unlock_user'] == 1) {
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 8: // Markoverdue Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{SUBJECT}' => $Subject,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('moverdue-tk');
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_mark_overdue_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_mark_overdue_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                            // Get All Staff member of the department of Current Ticket
                            $agentmembers = JSSTincluder::getJSModel('agent')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            if(is_array($agentmembers) && !empty($agentmembers)){
                                foreach ($agentmembers AS $agent) {
                                    if($agent->canemail == 1){
                                        $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                                        $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###staff#### ></span>';
                                        $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                                    }
                                }
                            }
                            // send email to staff memebers with all ticket permissions
                            if( !is_numeric($ticketRecord->staffid) && !is_numeric($ticketRecord->departmentid)){
                                if( in_array('agent',jssupportticket::$_active_addons)){
                                    $agentmembers = JSSTincluder::getJSModel('agent')->getAllStaffMemberByAllTicketPermission();
                                    if(is_array($agentmembers) && !empty($agentmembers)){
                                        foreach ($agentmembers AS $agent) {
                                            if($agent->canemail == 1){
                                                $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                                                $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###staff#### ></span>';
                                                $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_mark_overdue_user'] == 1) {
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 9: // Mark in progress Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{TRACKINGID}' => $TrackingId,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{SUBJECT}' => $Subject,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('minprogress-tk');
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_mark_progress_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_mark_progress_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_mark_progress_user'] == 1) {
                            $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $matcharray['{TICKETURL}'] = $link;
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 10: // Ban email and close Ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{EMAIL_ADDRESS}' => $Email,
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('banemailcloseticket-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticker_ban_eamil_and_close_ticktet_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticker_ban_eamil_and_close_ticktet_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticker_ban_eamil_and_close_ticktet_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 11: // Priority change ticket
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $Subject = $ticketRecord->subject;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Priority = JSSTincluder::getJSModel('priority')->getPriorityById($ticketRecord->priorityid);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{PRIORITY_TITLE}' => __($Priority,'js-support-ticket'),
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('prtrans-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_priority_admin'] == 1) {
                            $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                            $matcharray['{TICKETURL}'] = $link;
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_priority_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_priority_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 12: // DEPARTMENT TRANSFER
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $Subject = $ticketRecord->subject;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Department = JSSTincluder::getJSModel('department')->getDepartmentById($ticketRecord->departmentid);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT_TITLE}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('deptrans-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_department_transfer_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_department_transfer_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);

                            // send email to all staff memebers of current ticket department
                            // Get All Staff member of the department of Current Ticket
                            $agentmembers = JSSTincluder::getJSModel('agent')->getAllStaffMemberByDepId($ticketRecord->departmentid);
                            if(is_array($agentmembers) && !empty($agentmembers)){
                                foreach ($agentmembers AS $agent) {
                                    if($agent->canemail == 1){
                                        $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                                        $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###staff#### ></span>';
                                        $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                                    }
                                }
                            }
                            // send email to staff memebers with all ticket permissions
                            if( !is_numeric($ticketRecord->staffid) && !is_numeric($ticketRecord->departmentid)){
                                if( in_array('agent',jssupportticket::$_active_addons) ){
                                    $agentmembers = JSSTincluder::getJSModel('agent')->getAllStaffMemberByAllTicketPermission();
                                    if(is_array($agentmembers) && !empty($agentmembers)){
                                        foreach ($agentmembers AS $agent) {
                                            if($agent->canemail == 1){
                                                $msgBody .= '<input type="hidden" name="ticketid:' . $TrackingId . '###staff####" />';
                                                $msgBody .= '<span style="display:none;" ticketid:' . $TrackingId . '###staff#### ></span>';
                                                $this->sendEmail($agent->email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_department_transfer_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 13: // REASSIGN TICKET TO STAFF
                        if(! in_array('agent',jssupportticket::$_active_addons) ){
                            return;
                        }
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $TrackingId = $ticketRecord->ticketid;
                        $DepName = $ticketRecord->departmentname;
                        if(in_array('helptopic', jssupportticket::$_active_addons)){
                            $HelptopicName = $ticketRecord->topic;
                        }else{
                            $HelptopicName = '';
                        }
                        $Email = $ticketRecord->email;
                        $Subject = $ticketRecord->subject;
                        $Staff = JSSTincluder::getJSModel('agent')->getMyName($ticketRecord->staffid);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{AGENT_NAME}' => $Staff,
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('reassign-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        $link = admin_url("admin.php?page=ticket&jstlay=ticketdetail&jssupportticketid=" . $id);
                        $matcharray['{TICKETURL}'] = $link;
                        if (jssupportticket::$_config['ticket_reassign_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{AGENT_NAME}' => $Staff,
                            '{SUBJECT}' => $Subject,
                            '{HELP_TOPIC}' => $HelptopicName,
                            '{TRACKINGID}' => $TrackingId,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $link = esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketdetail','jssupportticketid'=>$id,'jsstpageid'=>jssupportticket::getPageid())));
                        $matcharray['{TICKETURL}'] = $link;
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_reassign_staff'] == 1) {
                            $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_reassign_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 14: // Reply to closed ticket for Email Piping
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Subject = $ticketRecord->subject;
                        $Email = $ticketRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{SUBJECT}' => $Subject,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('mail-rpy-closed');
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_reply_closed_ticket_user'] == 1) {
                            $msgBody = $template->body;
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 15: // Send feedback email to user
                        if(!in_array('feedback', jssupportticket::$_active_addons)){
                            break;
                        }
                        $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Subject = $ticketRecord->subject;
                        $Email = $ticketRecord->email;
                        $TrackingId = $ticketRecord->ticketid;
                        $close_date = date_i18n(jssupportticket::$_config['date_format'], strtotime($ticketRecord->closed));
                        $username = $ticketRecord->name;
                        $tokenarray['emailaddress']=$Email;
                        $tokenarray['trackingid']=$TrackingId;
                        $token = json_encode($tokenarray);
                        include_once jssupportticket::$_path . 'includes/encoder.php';
                        $encoder = new JSSTEncoder();
                        $encryptedtext = $encoder->encrypt($token);
                        $link = "<a href=" . esc_url(jssupportticket::makeUrl(array('jstmod'=>'feedback', 'task'=>'showfeedbackform','action'=>'jstask','token'=>$encryptedtext,'jsstpageid'=>jssupportticket::getPageid()))) . ">";
                        $linkclosing = "</a>";
                        $tracking_url = "<a href=" . esc_url(jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'showticketstatus','action'=>'jstask','token'=>$encryptedtext,'jsstpageid'=>jssupportticket::getPageid()))) . ">" . $TrackingId . "</a>";
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USER_NAME}' => $username,
                            '{TICKET_SUBJECT}' => $Subject,
                            '{TRACKING_ID}' => $tracking_url,
                            '{CLOSE_DATE}' => $close_date,
                            '{LINK}' => $link,
                            '{/LINK}' => $linkclosing,
                            '{DEPARTMENT}' => __($ticketRecord->departmentname,'js-support-ticket'),
                            '{PRIORITY}' => __($ticketRecord->priority,'js-support-ticket'),
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        // code for handling custom fields start
                        $fvalue = '';
                        if(!empty($ticketRecord->params)){
                            $data = json_decode($ticketRecord->params,true);
                        }
                        $fields = JSSTincluder::getJSModel('fieldordering')->getUserfieldsfor(1);
                        if( isset($data) && is_array($data)){
                            foreach ($fields as $field) {
                                if($field->userfieldtype != 'file'){
                                    $fvalue = '';
                                    if(array_key_exists($field->field, $data)){
                                        $fvalue = $data[$field->field];
                                    }
                                    $matcharray['{'.$field->field.'}'] = $fvalue;// match array new index for custom field
                                }
                            }
                        }
                        // code for handling custom fields end
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('mail-feedback');
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_feedback_user'] == 1) {
                            $msgSubject = $template->subject;
                            $msgBody = $template->body;
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                }
                break;
            case 2: // Ban Email
                switch ($action) {
                    case 1: // Ban Email
                        if ($tablename != null)
                            $banemailRecord = $this->getRecordByTablenameAndId($tablename, $id);
                        else
                            $banemailRecord = $this->getRecordByTablenameAndId('js_ticket_email_banlist', $id);
                        $Email = $banemailRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{EMAIL_ADDRESS}' => $Email,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getDefaultSenderEmailAndName();
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('banemail-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if (jssupportticket::$_config['ticket_ban_email_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['ticket_ban_email_staff'] == 1) {
                            if ($tablename != null)
                                $agentEmail = $this->getStaffEmailAddressByStaffId($banemailRecord->staffid);
                            else
                                $agentEmail = $this->getStaffEmailAddressByStaffId($banemailRecord->submitter);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['ticket_ban_email_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 2: // Unban Email
                        if ($tablename != null)
                            $ticketRecord = $this->getRecordByTablenameAndId($tablename, $id);
                        else
                            $ticketRecord = $this->getRecordByTablenameAndId('js_ticket_tickets', $id);
                        $Email = $ticketRecord->email;
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{EMAIL_ADDRESS}' => $Email,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName($id);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('unbanemail-tk');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        // New ticket mail to admin
                        if (jssupportticket::$_config['unban_email_admin'] == 1) {
                            $adminEmailid = jssupportticket::$_config['default_admin_email'];
                            $adminEmail = $this->getEmailById($adminEmailid);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to staff
                        if ( in_array('agent',jssupportticket::$_active_addons) && jssupportticket::$_config['unban_email_staff'] == 1) {
                            if ($tablename != null)
                                $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->staffid);
                            else
                                $agentEmail = $this->getStaffEmailAddressByStaffId($ticketRecord->submitter);
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($agentEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        // New ticket mail to User
                        if (jssupportticket::$_config['unban_email_user'] == 1) {
                            $this->replaceMatches($msgSubject, $matcharray);
                            $this->replaceMatches($msgBody, $matcharray);
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                }
                break;
            case 3: // Sending email alerts on mail system
                if(!in_array('mail', jssupportticket::$_active_addons)){ // if mail addon is not installed
                    break;
                }
                switch ($action) {
                    case 1: // Store message
                        $mailRecord = $this->getMailRecordById($id);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{AGENT_NAME}' => $mailRecord->sendername,
                            '{SUBJECT}' => $mailRecord->subject,
                            '{MESSAGE}' => $mailRecord->message,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('mail-new');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $Email = $mailRecord->receveremail;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        break;
                    case 2: // Store reply
                        $mailRecord = $this->getMailRecordById($id, 1);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{AGENT_NAME}' => $mailRecord->sendername,
                            '{SUBJECT}' => $mailRecord->subject,
                            '{MESSAGE}' => $mailRecord->message,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('mail-rpy');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $Email = $mailRecord->receveremail;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        break;
                }
                break;
            case 4: // gdpr data erase or delte.
                switch ($action) {
                    case 1: // erase data email
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{USERNAME}' => jssupportticket::$_data['mail_data']['name'],
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('delete-user-data');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $Email = jssupportticket::$_data['mail_data']['email'];
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        break;
                }
                break;
            case 5: // agent emails
                switch ($action) {
                    case 1: // new agent
                        $staffname = JSSTincluder::getJSModel('agent')->getMyName($id);
                        $matcharray = array(
                            '{SITETITLE}' => jssupportticket::$_config['title'],
                            '{AGENT_NAME}' => $staffname,
                            '{CURRENT_YEAR}' => date('Y')
                        );
                        $object = $this->getSenderEmailAndName(null);
                        $senderEmail = $object->email;
                        $senderName = $object->name;
                        $template = $this->getTemplateForEmail('staff-new');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $adminEmailid = jssupportticket::$_config['default_admin_email'];
                        $adminEmail = $this->getEmailById($adminEmailid);
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $this->sendEmail($adminEmail, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        break;
                }
                break;
        }
    }

    function getMailRecordById($id, $replyto = null) { // this function will not be called if the mail addon is not installed
        if (!is_numeric($id))
            return false;
        if ($replyto == null) {
            $query = "SELECT mail.subject,mail.message,CONCAT(staff.firstname,' ',staff.lastname) AS sendername
                        FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff_mail` AS mail
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff ON staff.id = mail.fromid
                        WHERE mail.id = " . $id;
        } else {
            $query = "SELECT mail.subject,reply.message,CONCAT(staff.firstname,' ',staff.lastname) AS sendername
                        FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff_mail` AS reply
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_staff_mail` AS mail ON mail.id = reply.replytoid
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff ON staff.id = reply.fromid
                        WHERE reply.id = " . $id;
        }
        $result = jssupportticket::$_db->get_row($query);
            $query = "SELECT staff.email
                        FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff_mail` AS mail
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff ON staff.id = mail.toid
                        WHERE mail.id = " . $id;
        $email = jssupportticket::$_db->get_var($query);
        $result->receveremail = $email;
        return $result;
    }

    private function getStaffEmailAddressByStaffId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT staff.email
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_staff` AS staff
                    WHERE staff.id = $id";
        $emailaddress = jssupportticket::$_db->get_var($query);
        return $emailaddress;
    }

    private function getLatestReplyByTicketId($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT reply.message FROM `" . jssupportticket::$_db->prefix . "js_ticket_replies` AS reply WHERE reply.ticketid = " . $id . " ORDER BY reply.created DESC LIMIT 1";
        $message = jssupportticket::$_db->get_var($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $message;
    }

    private function replaceMatches(&$string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = str_replace($find, $replace, $string);
        }
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '', $action) {

        if( (is_array($recevierEmail) && empty($recevierEmail)) || (!is_array($recevierEmail) && trim($recevierEmail) == '') ){ // avoid the case of trying to send email to empty email.
            return;
        }

        $enablesmtp = $this->checkSMTPEnableOrDisable($senderEmail);
        if ($enablesmtp) {
            $this->sendSMTPmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action);
        }else{
            $this->sendEmailDefault($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action);
        }

    }

    private function sendEmailDefault($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '', $action) {
        /*
          $attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
          $headers = 'From: My Name <myname@example.com>' . "\r\n";
          wp_mail('test@example.org', 'subject', 'message', $headers, $attachments );

          $action
          For which action of $mailfor you want to send the mail
          1 => New Ticket Create
          2 => Close Ticket
          3 => Delete Ticket
          4 => Reply Ticket (Admin/Staff Member)
          5 => Reply Ticket (Ticket member)
         */
        switch ($action) {
            case 1:
                do_action('jsst-beforeemailticketcreate', $recevierEmail, $subject, $body, $senderEmail);
                break;
            case 2:
                do_action('jsst-beforeemailticketreply', $recevierEmail, $subject, $body, $senderEmail);
                break;
            case 3:
                do_action('jsst-beforeemailticketclose', $recevierEmail, $subject, $body, $senderEmail);
                break;
            case 4:
                do_action('jsst-beforeemailticketdelete', $recevierEmail, $subject, $body, $senderEmail);
                break;
        }
        if (!$senderName)
            $senderName = jssupportticket::$_config['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', array($this,'jsst_set_html_content_type'));
        // $body = preg_replace('/\r?\n|\r/', '<br/>', $body);
        // $body = str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        // $body = nl2br($body);
		if($recevierEmail){
			if(!wp_mail($recevierEmail, $subject, $body, $headers, $attachments)){
				if($GLOBALS['phpmailer']->ErrorInfo)
					JSSTincluder::getJSModel('systemerror')->addSystemError($GLOBALS['phpmailer']->ErrorInfo);
			}
		}else{
			JSSTincluder::getJSModel('systemerror')->addSystemError("No recipient email for ".$subject);
		}
    }

    function jsst_set_html_content_type() {
        return 'text/html';
    }

    private function sendSMTPmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action){
        do_action('jsst_aadon_send_smtp_mail',$recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments, $action);
    }

    private function getSenderEmailAndName($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT email.email,email.name
                        FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON department.id = ticket.departmentid
                        JOIN `" . jssupportticket::$_db->prefix . "js_ticket_email` AS email ON email.id = department.emailid
                        WHERE ticket.id = " . $id;
            $email = jssupportticket::$_db->get_row($query);
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
            }
        } else {
            $email = '';
        }
        if (empty($email)) {
            $email = $this->getDefaultSenderEmailAndName();
        }
        return $email;
    }

    private function getDefaultSenderEmailAndName() {
        $emailid = jssupportticket::$_config['default_alert_email'];
        $query = "SELECT email,name FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` WHERE id = " . $emailid;
        $email = jssupportticket::$_db->get_row($query);
        return $email;
    }

    private function getTemplateForEmail($templatefor) {
        $query = "SELECT * FROM `" . jssupportticket::$_db->prefix . "js_ticket_emailtemplates` WHERE templatefor = '" . $templatefor . "'";
        $template = jssupportticket::$_db->get_row($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $template;
    }

    private function getRecordByTablenameAndId($tablename, $id) {
        if (!is_numeric($id))
            return false;
        switch($tablename){
            case 'js_ticket_tickets':
                do_action('get_mail_table_record_query');// to prepare any addon based query
                $query = "SELECT ticket.*,department.departmentname,priority.priority ".jssupportticket::$_addon_query['select']
                    . " FROM `" . jssupportticket::$_db->prefix . $tablename . "` AS ticket "
                    . " LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_departments` AS department ON department.id = ticket.departmentid "
                    . jssupportticket::$_addon_query['join']
                    . " LEFT JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON priority.id = ticket.priorityid "
                    . " WHERE ticket.id = " . $id;
                do_action('reset_jsst_aadon_query');
            break;
            default:
                $query = "SELECT * FROM `" . jssupportticket::$_db->prefix . $tablename . "` WHERE id = " . $id;
            break;
        }
        $record = jssupportticket::$_db->get_row($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $record;
    }

    function getEmails() {
        // Filter
        $email = JSSTrequest::getVar('email');
        $inquery = '';
        if ($email != null)
            $inquery .= " WHERE email.email LIKE '%$email%'";

        $formsearch = JSSTrequest::getVar('JSST_form_search', 'post');
        if ($formsearch == 'JSST_SEARCH') {
            $_SESSION['JSST_SEARCH']['email'] = $email;
        }

        if (JSSTrequest::getVar('pagenum', 'get', null) != null) {
            $email = (isset($_SESSION['JSST_SEARCH']['email']) && $_SESSION['JSST_SEARCH']['email'] != '') ? $_SESSION['JSST_SEARCH']['email'] : null;
        }

        jssupportticket::$_data['filter']['email'] = $email;

        // Pagination
        $query = "SELECT COUNT(email.id)
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` AS email ";
        $query .= $inquery;
        $total = jssupportticket::$_db->get_var($query);
        jssupportticket::$_data[1] = JSSTpagination::getPagination($total);

        // Data
        $query = " SELECT email.id, email.email, email.autoresponse, email.created, email.updated,email.status
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` AS email ";
        $query .= $inquery;
        $query .= " ORDER BY email.email DESC LIMIT " . JSSTpagination::getOffset() . ", " . JSSTpagination::getLimit();
        jssupportticket::$_data[0] = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['email'] = $email;
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return;
    }

    function getAllEmailsForCombobox() {
        $query = "SELECT id AS id, email AS text FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` WHERE status = 1 AND autoresponse = 1";
        $emails = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $emails;
    }

    function getEmailForForm($id) {
        if ($id) {
            if (!is_numeric($id))
                return false;
            $query = "SELECT email.id, email.email, email.autoresponse, email.created, email.updated,email.status,email.smtpemailauth,email.smtphosttype,email.smtphost,email.smtpauthencation,email.name,email.password,email.smtpsecure,email.mailport
                        FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` AS email
                        WHERE email.id = " . $id;
            jssupportticket::$_data[0] = jssupportticket::$_db->get_row($query);
            if(isset(jssupportticket::$_data[0]->password) && jssupportticket::$_data[0]->password != ''){
                jssupportticket::$_data[0]->password = base64_decode(jssupportticket::$_data[0]->password);
            }
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            }
        }
        return;
    }

    function storeEmail($data) {
        if(!$data['id'])
        if($this->checkAlreadyExist($data['email'])){
            JSSTmessage::setMessage(__('Email Already Exist', 'js-support-ticket'), 'error');
            return;
        }
        if ($data['id'])
            $data['updated'] = date_i18n('Y-m-d H:i:s');
        else{
            $data['updated'] = date_i18n('Y-m-d H:i:s');
            $data['created'] = date_i18n('Y-m-d H:i:s');
        }
        if(isset($data['password']) && $data['password'] != ''){
            $data['password'] = base64_encode($data['password']);
        }

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        $row = JSSTincluder::getJSTable('email');

        $data = JSSTincluder::getJSmodel('jssupportticket')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 0) {
            JSSTmessage::setMessage(__('The email has been stored', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            JSSTmessage::setMessage(__('The email has not been stored', 'js-support-ticket'), 'error');
        }
        return;
    }

    function checkAlreadyExist($email){
        $query = "SELECT COUNT(id) FROM`" . jssupportticket::$_db->prefix . "js_ticket_email`  WHERE email = '".$email."'";
        $result = jssupportticket::$_db->get_var($query);
        if($result > 0)
            return true;
        else
            return false;
    }

    function removeEmail($id) {
        if (!is_numeric($id))
            return false;
        if ($this->canRemoveEmail($id)) {
            $row = JSSTincluder::getJSTable('email');
            if ($row->delete($id)) {
                JSSTmessage::setMessage(__('The email has been deleted', 'js-support-ticket'), 'updated');
            } else {
                JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
                JSSTmessage::setMessage(__('The email has not been deleted', 'js-support-ticket'), 'error');
            }
        } else {
            JSSTmessage::setMessage(__('Email','js-support-ticket').' '.__('in use cannot deleted', 'js-support-ticket'), 'error');
        }
        return;
    }

    private function canRemoveEmail($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT (
                        (SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_departments` WHERE emailid = " . $id . ")
                        + (SELECT COUNT(*) FROM `" . jssupportticket::$_db->prefix . "js_ticket_config` WHERE configname = 'default_alert_email' AND configvalue = " . $id . ")
                        + (SELECT COUNT(*) FROM `" . jssupportticket::$_db->prefix . "js_ticket_config` WHERE configname = 'default_admin_email' AND configvalue = " . $id . ")
                        ) AS total";
        $result = jssupportticket::$_db->get_var($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        if ($result == 0)
            return true;
        else
            return false;
    }

    function getEmailForDepartment() {
        $query = "SELECT id, email AS text FROM `" . jssupportticket::$_db->prefix . "js_ticket_email`";
        $emails = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $emails;
    }

    function getEmailById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT email  FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` WHERE id = " . $id;
        $email = jssupportticket::$_db->get_var($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $email;
    }

    function checkSMTPEnableOrDisable($senderemail){
        if(!is_string($senderemail))
            return fasle;
        $query = "SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_email` WHERE email = '".$senderemail. "' AND smtpemailauth = 1"; // 1 For smtp 0 for default
        $total = jssupportticket::$_db->get_var($query);
        if($total > 0){
            return true;
        }else{
            return false;
        }
    }

    function getSMTPEmailConfig($senderemail){
        $query = "SELECT * FROM  `" . jssupportticket::$_db->prefix . "js_ticket_email` WHERE email = '".$senderemail."'";
        $emailconfig = jssupportticket::$_db->get_row($query);
        return $emailconfig;
    }

    function sendTestEmail(){
        $hosttype = JSSTrequest::getVar('hosttype');
        $hostname = JSSTrequest::getVar('hostname');
        $ssl = JSSTrequest::getVar('ssl');
        $hostportnumber = JSSTrequest::getVar('hostportnumber');
        $emailaddress = JSSTrequest::getVar('emailaddress');
        $password = JSSTrequest::getVar('password');
        $smtpauthencation = JSSTrequest::getVar('smtpauthencation');

        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $mail = new PHPMailer(true);
        try {

            $mail->isSMTP();
            $mail->Host = $hostname;
            //$mail->Host = 'smtp1.example.com;
            $mail->SMTPAuth = $smtpauthencation;
            $mail->Username = $emailaddress;
            $mail->Password = $password;
            if($ssl == 0){
                $mail->SMTPSecure = 'ssl';
            }else{
                $mail->SMTPSecure = 'tls';
            }
            $mail->Port = $hostportnumber;
            //Recipients
            $mail->setFrom($emailaddress, jssupportticket::$_config['title']);
            $adminEmailid = jssupportticket::$_config['default_admin_email'];
            $adminEmail = $this->getEmailById($adminEmailid);

            $mail->addAddress($adminEmail,'Administrator');

            $mail->isHTML(true);
            $mail->Subject = 'SMTP Test email From :'.site_url();
            $mail->Body    = 'This is body text for SMTP test email from :'.site_url();
            $mail->send();
            $error['text'] = 'Test email has been sent on : '. $adminEmail;
            $error['type'] = 0;
        } catch (Exception $e) {
            $error['text'] = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
            $error['type'] = 1;
        }
        return json_encode($error);;

    }
}

?>
