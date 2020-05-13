<div class="jsst-main-up-wrapper">
<?php
if (jssupportticket::$_config['offline'] == 2) {
    if (get_current_user_id() != 0 || jssupportticket::$_config['visitor_can_create_ticket'] == 1) {
        JSSTmessage::getMessage();
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('file_validate.js', jssupportticket::$_pluginpath . 'includes/js/file_validate.js');

		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.custom_date').datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                jQuery("#tk_attachment_add").click(function () {
                    var obj = this;
                    var current_files = jQuery('input[name="filename[]"]').length;
                    var total_allow =<?php echo jssupportticket::$_config['no_of_attachement']; ?>;
                    var append_text = "<span class='tk_attachment_value_text'><input name='filename[]' type='file' onchange=\"uploadfile(this,'<?php echo jssupportticket::$_config['file_maximum_size']; ?>','<?php echo jssupportticket::$_config['file_extension']; ?>');\" size='20' maxlenght='30'  /><span  class='tk_attachment_remove'></span></span>";
                    if (current_files < total_allow) {
                        jQuery(".tk_attachment_value_wrapperform").append(append_text);
                    } else if ((current_files === total_allow) || (current_files > total_allow)) {
                        alert('<?php echo __('File upload limit exceeds', 'js-support-ticket'); ?>');
                        jQuery(obj).hide();
                    }
                });
                jQuery(document).delegate(".tk_attachment_remove", "click", function (e) {
                    jQuery(this).parent().remove();
                    var current_files = jQuery('input[name="filename[]"]').length;
                    var total_allow =<?php echo jssupportticket::$_config['no_of_attachement']; ?>;
                    if (current_files < total_allow) {
                        jQuery("#tk_attachment_add").show();
                    }
                });
                $.validate();
            });
            // to get premade and append to isssue summery
            function getHelpTopicByDepartment(val) {
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', val: val, jstmod: 'department', task: 'getHelpTopicByDepartment'}, function (data) {
                    if (data != false) {
                        jQuery("div#helptopic").html(data);
                    }else{
                        jQuery("div#helptopic").html( "<?php echo __('No help topic found','js-support-ticket'); ?>");
                    }
                });//jquery closed
            }

            // woocommerce
            function jsst_wc_order_products(){
                var orderid = jQuery("#wcorderid").val();
                jQuery.post(
                    ajaxurl,
                    {action: 'jsticket_ajax', jstmod: 'woocommerce', task: 'getWcOrderProductsAjax',orderid:orderid},
                    function (data) {
                        jQuery("#wcproductid-wrap").html(data);
                    }
                );
            }

            function jsst_edd_order_products(){
                var orderid = jQuery("select#eddorderid").val();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'easydigitaldownloads', task: 'getEDDOrderProductsAjax', eddorderid:orderid}, function (data) {
                        jQuery("#eddproductid-wrap").html(data);
                    }
                );
            }

            function jsst_eed_product_licenses(){
                var eddproductid = jQuery("select#eddproductid").val();
                var orderid = jQuery("select#eddorderid").val();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'easydigitaldownloads', task: 'getEDDProductlicensesAjax', eddproductid:eddproductid, eddorderid:orderid}, function (data) {
                        jQuery("#eddlicensekey-wrap").html(data);
                    }
                );
            }

            jQuery(document).ready(function(){
                jQuery("select#eddorderid").change(function(){
                    jsst_edd_order_products();
                });
                <?php if(!isset(jssupportticket::$_data[0]->id)){ ?>
                    if(jQuery("select#eddorderid").val()){
                        jsst_edd_order_products();
                    }
                <?php } ?>
                jQuery(document).on('change', 'select#eddproductid', function() {
                    jsst_eed_product_licenses();
                });
                if(jQuery("select#eddproductid").val()){
                    jsst_eed_product_licenses();
                }

                jQuery("#wcorderid").change(function(){
                    jsst_wc_order_products();
                });
                if(jQuery("#wcorderid").val()){
                    jsst_wc_order_products();
                }
            });
        </script>
        <span style="display:none" id="filesize"><?php echo __('Error file size too large', 'js-support-ticket'); ?></span>
        <span style="display:none" id="fileext"><?php echo __('The uploaded file extension not valid', 'js-support-ticket'); ?></span>
        <?php
        $loginuser_name = '';
        $loginuser_email = '';
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            /*$loginuser_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
            if(str_replace(' ', '', $loginuser_name) == ''){
                $loginuser_name = $current_user->user_nicename;
            }*/
            if(empty($current_user->display_name) == true){
                $loginuser_name = $current_user->user_nicename;
            }else{
                $loginuser_name = $current_user->display_name;
            }
            $loginuser_email = $current_user->user_email;
        }
        ?>
        <?php JSSTmessage::getMessage(); ?>
        <?php $formdata = JSSTformfield::getFormData(); ?>
        <?php /* JSSTbreadcrumbs::getBreadcrumbs(); */ ?>
        <?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>
        <?php if (jssupportticket::$_config['new_ticket_message']) { ?>
            <div class="js-col-xs-12 js-col-md-12 js-ticket-form-instruction-message">
                <?php echo jssupportticket::$_config['new_ticket_message']; ?>
            </div>
            <?php }
        ?>
        <div class="js-ticket-add-form-wrapper">
            <?php
            $showform = true;
            if(in_array('paidsupport', jssupportticket::$_active_addons) && class_exists('WooCommerce')){
                if(isset(jssupportticket::$_data['paidsupport'])){
                    $row = jssupportticket::$_data['paidsupport'];
                    $paidsupportid = $row->itemid;
                    ?>
                    <h3><?php echo __("Paid support info",'js-support-ticket'); ?></h3>
                    <table border="1">
                        <tr>
                            <th><?php echo __("Order ID",'js-support-ticket'); ?></th>
                            <th><?php echo __("Product Name",'js-support-ticket'); ?></th>
                            <th><?php echo __("Total Tickets",'js-support-ticket'); ?></th>
                            <th><?php echo __("Remaining Tickets",'js-support-ticket'); ?></th>
                        </tr>
                        <tr>
                            <td>#<?php echo $row->orderid; ?></td>
                            <td><?php
                            echo $row->itemname;
                            if($row->qty > 1){
                                echo '<b> x '.$row->qty."</b>";
                            }
                            ?></td>
                            <td><?php echo $row->total == -1 ? __("Unlimited",'js-support-ticket') : $row->total; ?></td>
                            <td><?php echo $row->total == -1 ? __("Unlimited",'js-support-ticket') : $row->remaining; ?></td>
                        </tr>
                    </table>
                    <?php
                }elseif(isset(jssupportticket::$_data['paidsupportitems'])){
                    $showform = false;
                    $paidsupportitems = jssupportticket::$_data['paidsupportitems'];
                    if(empty($paidsupportitems)){
                        ?>
                        <div class="js-ticket-error-message-wrapper">
                            <div class="js-ticket-message-image-wrapper">
                                <img class="js-ticket-message-image" alt="message image" src="<?php echo jssupportticket::$_pluginpath.'/includes/images/error/not-permission-icon.png'; ?>">
                            </div>
                            <div class="js-ticket-messages-data-wrapper">
                                <span class="js-ticket-messages-main-text">
                                    <?php echo __("You have not purchased any supported item",'js-support-ticket'); ?>
                                </span>
                                <span class="js-ticket-user-login-btn-wrp">
                                    <a class="js-ticket-login-btn" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo __("Go to shop",'js-support-ticket'); ?></a>
                                </span>
                            </div>
                        </div>
                        <?php
                    }else{
                        ?>
                        <h3><?php echo __("Please select paid support item",'js-support-ticket'); ?> <span style="color:red">*</span></h3>
                        <table border="1">
                            <tr>
                                <th><?php echo __("Order ID",'js-support-ticket'); ?></th>
                                <th><?php echo __("Product Name",'js-support-ticket'); ?></th>
                                <th><?php echo __("Total Tickets",'js-support-ticket'); ?></th>
                                <th><?php echo __("Remaining Tickets",'js-support-ticket'); ?></th>
                                <th></th>
                            </tr>
                            <?php
                            foreach($paidsupportitems as $row){
                                ?>
                                <tr>
                                    <td>#<?php echo $row->orderid; ?></td>
                                    <td><?php
                                    echo $row->itemname;
                                    if($row->qty > 1){
                                        echo '<b> x '.$row->qty."</b>";
                                    }
                                    ?></td>
                                    <td><?php echo $row->total == -1 ? __("Unlimited",'js-support-ticket') : $row->total; ?></td>
                                    <td><?php echo $row->total == -1 ? __("Unlimited",'js-support-ticket') : $row->remaining; ?></td>
                                    <td><a href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'ticket','jstlay'=>'addticket','paidsupportid'=>$row->itemid)); ?>"><?php echo __("Select",'js-support-ticket') ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                    }
                }
            }
            ?>

            <?php if($showform): ?>
            <form class="js-ticket-form" method="post" action="<?php echo jssupportticket::makeUrl(array('jstmod'=>'ticket', 'task'=>'saveticket')); ?>" id="adminTicketform" enctype="multipart/form-data">
                <?php
                $i = '';
                $fieldcounter = 0;
                $eddorderid = '';
                foreach (jssupportticket::$_data['fieldordering'] AS $field):
                    switch ($field->field) {
                        case 'email':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<span style="color:red">*</span></div>
                                <div class="js-ticket-from-field">
                                    <?php
                                        if(isset($formdata['email'])) $email = $formdata['email'];
                                        elseif(isset(jssupportticket::$_data[0]->email)) $email = jssupportticket::$_data[0]->email;
                                        else $email = $loginuser_email;
                                        echo JSSTformfield::text('email', $email, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => ($field->required) ? 'required email' : 'email'));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'fullname':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<span style="color:red">*</span></div>
                                <div class="js-ticket-from-field">
                                    <?php
                                        if(isset($formdata['name'])) $name = $formdata['name'];
                                        elseif(isset(jssupportticket::$_data[0]->name)) $name = jssupportticket::$_data[0]->name;
                                        else $name = $loginuser_name;
                                        echo JSSTformfield::text('name', $name, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => 'required'));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'phone':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                <div class="js-ticket-from-field">
                                    <?php
                                        if(isset($formdata['phone'])) $phone = $formdata['phone'];
                                        elseif(isset(jssupportticket::$_data[0]->phone)) $phone = jssupportticket::$_data[0]->phone;
                                        else $phone = '';
                                        echo JSSTformfield::text('phone', $phone, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => ($field->required) ? 'required' : ''));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'phoneext':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                <div class="js-ticket-from-field">
                                    <?php
                                        if(isset($formdata['phoneext'])) $phoneext = $formdata['phoneext'];
                                        elseif(isset(jssupportticket::$_data[0]->phoneext)) $phoneext = jssupportticket::$_data[0]->phoneext;
                                        else $phoneext = '';
                                        echo JSSTformfield::text('phoneext', $phoneext, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => ($field->required) ? 'required' : ''));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'department':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                <div class="js-ticket-from-field js-ticket-form-field-select">
                                    <?php
                                        if(isset($formdata['departmentid'])) $departmentid = $formdata['departmentid'];
                                        elseif(isset(jssupportticket::$_data[0]->departmentid)) $departmentid = jssupportticket::$_data[0]->departmentid;
                                        elseif(JSSTrequest::getVar('departmentid','get',0) > 0) $departmentid = JSSTrequest::getVar('departmentid','get');
                                        else $departmentid = JSSTincluder::getJSModel('department')->getDefaultDepartmentID();
                                        echo JSSTformfield::select('departmentid', JSSTincluder::getJSModel('department')->getDepartmentForCombobox(), $departmentid, __('Select Department', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'onchange' => 'getHelpTopicByDepartment(this.value);', 'data-validation' => ($field->required) ? 'required' : ''));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'helptopic':
                            if(!in_array('helptopic', jssupportticket::$_active_addons)){
                                break;
                            }
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                <div class="js-ticket-from-field js-ticket-form-field-select" id="helptopic">
                                    <?php
                                        if(isset($formdata['helptopicid'])) $helptopicid = $formdata['helptopicid'];
                                        elseif(isset(jssupportticket::$_data[0]->helptopicid)) $helptopicid = jssupportticket::$_data[0]->helptopicid;
                                        elseif(JSSTrequest::getVar('helptopicid','get',0) > 0) $helptopicid = JSSTrequest::getVar('helptopicid','get');
                                        else $helptopicid = '';
                                        echo JSSTformfield::select('helptopicid', JSSTincluder::getJSModel('helptopic')->getHelpTopicsForCombobox($departmentid), $helptopicid, __('Select Help Topic', 'js-support-ticket'), array('class ' => 'js-ticket-select-field', 'data-validation' => ($field->required) ? 'required' : ''));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'priority':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                <div class="js-ticket-from-field js-ticket-form-field-select">
                                    <?php
                                        if(isset($formdata['priorityid'])) $priorityid = $formdata['priorityid'];
                                        elseif(isset(jssupportticket::$_data[0]->priorityid)) $priorityid = jssupportticket::$_data[0]->priorityid;
                                        else $priorityid = JSSTincluder::getJSModel('priority')->getDefaultPriorityID();
                                        echo JSSTformfield::select('priorityid', JSSTincluder::getJSModel('priority')->getPriorityForCombobox(), $priorityid, __('Select Priority', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'data-validation' => ($field->required) ? 'required' : ''));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'subject':
                            if($fieldcounter % 2 == 0){
                                if($fieldcounter != 0){
                                    echo '</div>';
                                }
                                echo '<div class="js-ticket-add-form-wrapper">';
                            }
                            $fieldcounter++;
                            ?>
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<span style="color:red">*</span></div>
                                <div class="js-ticket-from-field">
                                    <?php
                                        if(isset($formdata['subject'])) $subject = $formdata['subject'];
                                        elseif(isset(jssupportticket::$_data[0]->subject)) $subject = jssupportticket::$_data[0]->subject;
                                        else $subject = '';
                                        echo JSSTformfield::text('subject', $subject, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => 'required'));
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'issuesummary':
                            if($fieldcounter != 0){
                                echo '</div>';
                                $fieldcounter = 0;
                            }
                            ?>
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<span style="color:red">*</span></div>
                                <div class="js-ticket-from-field">
                                    <?php
                                        if(isset($formdata['message'])) $message = wpautop(wptexturize(stripslashes($formdata['message'])));
                                        elseif(isset(jssupportticket::$_data[0]->message)) $message = jssupportticket::$_data[0]->message;
                                        else $message = '';
                                        echo wp_editor($message, 'jsticket_message', array('media_buttons' => false));
                                        /*
                                        * Use following settings for minimal editor as all are offering
                                        */
                                        // $settings = array(
                                        //     'media_buttons' => false,
                                        //     'textarea_rows' => 10,
                                        //     'quicktags'     => false,
                                        //     'tinymce'       => array(
                                        //         'toolbar1' => 'bold,italic,underline,strikethrough,hr,|,bullist,numlist,|,link,unlink',
                                        //         'toolbar2' => ''
                                        //     ),
                                        // );
                                        // echo wp_editor($message, 'jsticket_message', $settings);
                                    ?>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'attachments':
                            if($fieldcounter != 0){
                                echo '</div>';
                                $fieldcounter = 0;
                            }
                            ?>
                            <div class="js-ticket-reply-attachments"><!-- Attachments -->
                                <div class="js-attachment-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?><?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                <?php 
                                if(isset(jssupportticket::$_data[5]) && count(jssupportticket::$_data[5]) > 0){
                                    $attachmentreq = '';
                                }else{
                                    $attachmentreq = $field->required == 1 ? 'required' : '';
                                }
                                ?>
                                <div class="js-attachment-field">
                                    <div class="tk_attachment_value_wrapperform tk_attachment_user_reply_wrapper">
                                        <span class="tk_attachment_value_text">
                                            <input type="file" class="inputbox js-attachment-inputbox" name="filename[]" onchange="uploadfile(this, '<?php echo jssupportticket::$_config['file_maximum_size']; ?>', '<?php echo jssupportticket::$_config['file_extension']; ?>');" size="20" data-validation="<?php echo $attachmentreq; ?>" />
                                            <span class='tk_attachment_remove'></span>
                                        </span>
                                    </div>
                                    <span class="tk_attachments_configform">
                                        <?php echo __('Maximum File Size', 'js-support-ticket');
                                            echo ' (' . jssupportticket::$_config['file_maximum_size']; ?>KB)<br><?php echo __('File Extension Type', 'js-support-ticket');
                                            echo ' (' . jssupportticket::$_config['file_extension'] . ')'; ?>
                                    </span>
                                    <span id="tk_attachment_add" data-ident="tk_attachment_user_reply_wrapper" class="tk_attachments_addform"><?php echo __('Add more', 'js-support-ticket'); ?></span>
                                </div>
                                <?php if (!empty(jssupportticket::$_data[5])) {
                                        foreach (jssupportticket::$_data[5] AS $attachment) {
                                            echo '
                                                <div class="js-ticket-attached-files-wrp">
                                                     <div class="js_ticketattachment">
                                                             ' . $attachment->filename . ' ( ' . $attachment->filesize . ' ) ' . '
                                                    </div>
                                                    <a class="js-ticket-delete-attachment" href="'.wp_nonce_url(jssupportticket::makeUrl(array('jstmod'=>'attachment', 'task'=>'deleteattachment', 'action'=>'jstask', 'id'=>$attachment->id, 'tikcetid'=>jssupportticket::$_data[0]->id, 'jsstpageid'=>jssupportticket::getPageid())),'delete-attachement') . '">' . __('Remove','js-support-ticket') . '</a>
                                                </div>';
                                         }
                                 } ?>
                            </div>
                            <?php
                            break;
                            case 'wcorderid':
                                if(!in_array('woocommerce', jssupportticket::$_active_addons)){
                                    break;
                                }
                                if(!class_exists('WooCommerce')){
                                    break;
                                }
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo '</div>';
                                    }
                                    echo '<div class="js-ticket-add-form-wrapper">';
                                }
                                $fieldcounter++;

                                $orderlist = array();
								foreach(wc_get_orders(array('customer_id'=>get_current_user_id(),'post_status' => 'wc-completed')) as $order){
                                    $orderlist[] = (object) array('id' => $order->get_id(),'text'=>'#'.$order->get_id().' - '.$order->get_date_created()->date_i18n(wc_date_format()));
                                }
                                if(isset($formdata['wcorderid'])) $wcorderid = $formdata['wcorderid'];
                                elseif(isset(jssupportticket::$_data[0]->wcorderid)) $wcorderid = jssupportticket::$_data[0]->wcorderid;
                                else $wcorderid = '';  ?>
                                <div class="js-ticket-from-field-wrp">
                                    <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                    <div class="js-ticket-from-field js-ticket-form-field-select">
                                        <?php echo JSSTformfield::select('wcorderid', $orderlist, $wcorderid, __('Select Order', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'wcproductid':
                                if(!in_array('woocommerce', jssupportticket::$_active_addons)){
                                    break;
                                }
                                if(!class_exists('WooCommerce')){
                                    break;
                                }
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo '</div>';
                                    }
                                    echo '<div class="js-ticket-add-form-wrapper">';
                                }
                                $fieldcounter++;

                                $itemlist = array();
                                if(isset($formdata['wcproductid'])) $wcproductid = $formdata['wcproductid'];
                                elseif(isset(jssupportticket::$_data[0]->wcproductid)) $wcproductid = jssupportticket::$_data[0]->wcproductid;
                                else $wcproductid = '';  ?>
                                <div class="js-ticket-from-field-wrp">
                                    <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                    <div class="js-ticket-from-field js-ticket-form-field-select" id="wcproductid-wrap">
                                        <?php echo JSSTformfield::select('wcproductid', $itemlist, $wcproductid, __('Select Product', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'eddorderid':
                                if(!in_array('easydigitaldownloads', jssupportticket::$_active_addons)){
                                    break;
                                }
                                if(!class_exists('Easy_Digital_Downloads')){
                                    break;
                                }
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo '</div>';
                                    }
                                    echo '<div class="js-ticket-add-form-wrapper">';
                                }
                                $fieldcounter++;

                                $itemlist = array();

                                if(isset($formdata['eddorderid'])) $eddorderid = $formdata['eddorderid'];
                                elseif(isset(jssupportticket::$_data[0]->eddorderid)) $eddorderid = jssupportticket::$_data[0]->eddorderid;
                                elseif(isset(jssupportticket::$_data['edd_order_id'])) $eddorderid = jssupportticket::$_data['edd_order_id'];
                                $user_id = get_current_user_id();
                                if(is_numeric($user_id) && $user_id > 0){
                                    $user_purchases = edd_get_users_purchases($user_id);
                                    $user_purchase_array = array();
                                    if (is_array($user_purchases) || $user_purchases instanceof Countable) {
                                        foreach ($user_purchases AS $user_purchase) {
                                            $user_purchase_array[] = (object) array('id' => $user_purchase->ID, 'text' => '#'.$user_purchase->ID.'&nbsp;('. __('Dated','js-support-ticket').':&nbsp;' .date_i18n(jssupportticket::$_config['date_format'], strtotime($user_purchase->post_date)).')');
                                        }
                                    }
                                     ?>
                                    <div class="js-ticket-from-field-wrp">
                                        <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                        <div class="js-ticket-from-field js-ticket-form-field-select" id="eddorderid-wrap">
                                            <?php echo JSSTformfield::select('eddorderid', $user_purchase_array, $eddorderid, __('Select Order ID', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                        </div>
                                    </div>
                                <?php
                                }else{ ?>
                                    <div class="js-ticket-from-field-wrp">
                                        <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                        <div class="js-ticket-from-field js-ticket-form-field-select" id="eddorderid-wrap">
                                            <?php  echo JSSTformfield::text('eddorderid', $eddorderid, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                break;
                            case 'eddproductid':
                                if(!in_array('easydigitaldownloads', jssupportticket::$_active_addons)){
                                    break;
                                }
                                if(!class_exists('Easy_Digital_Downloads')){
                                    break;
                                }
                                if(is_numeric($user_id) && $user_id > 0){
                                    if($fieldcounter % 2 == 0){
                                        if($fieldcounter != 0){
                                            echo '</div>';
                                        }
                                        echo '<div class="js-ticket-add-form-wrapper">';
                                    }
                                    $fieldcounter++;

                                    $order_products_array = array();
                                    if($eddorderid != '' && is_numeric($eddorderid)){
                                        $order_products = edd_get_payment_meta_cart_details($eddorderid);
                                        foreach ($order_products as $order_product) {
                                            $order_products_array[] = (object) array('id'=>$order_product['id'], 'text'=>$order_product['name']);
                                        }
                                    }

                                    if(isset($formdata['eddproductid'])) $eddproductid = $formdata['eddproductid'];
                                    elseif(isset(jssupportticket::$_data[0]->eddproductid)) $eddproductid = jssupportticket::$_data[0]->eddproductid;
                                    else $eddproductid = '';  ?>
                                    <div class="js-ticket-from-field-wrp">
                                        <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                        <div class="js-ticket-from-field" id="eddproductid-wrap">
                                            <?php echo JSSTformfield::select('eddproductid', $order_products_array, $eddproductid, __('Select Product', 'js-support-ticket'), array('class' => 'inputbox js-form-select-field', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                        </div>
                                    </div>
                                <?php
                            }
                                break;
                            case 'eddlicensekey':
                                if(!in_array('easydigitaldownloads', jssupportticket::$_active_addons)){
                                    break;
                                }
                                if(!class_exists('Easy_Digital_Downloads')){
                                    break;
                                }
                                if(!class_exists('EDD_Software_Licensing')){
                                    break;
                                }
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo '</div>';
                                    }
                                    echo '<div class="js-ticket-add-form-wrapper">';
                                }
                                $fieldcounter++;
                                $license_key_array = array();
                                if($eddorderid != '' && is_numeric($eddorderid)){
                                    $license = EDD_Software_Licensing::instance();
                                    $result = $license->get_licenses_of_purchase($eddorderid);
                                    foreach ($result AS $license_record) {
                                        $license_record_licensekey = $license->get_license_key($license_record->ID);
                                        if($license_record_licensekey != ''){
                                            $license_key_array[] = (object) array('id' => $license_record_licensekey,'text' => $license_record_licensekey);
                                        }
                                    }
                                }

                                $itemlist = array();
                                if(isset($formdata['eddlicensekey'])) $eddlicensekey = $formdata['eddlicensekey'];
                                elseif(isset(jssupportticket::$_data[0]->eddlicensekey)) $eddlicensekey = jssupportticket::$_data[0]->eddlicensekey;
                                else $eddlicensekey = '';
                                $user_id = get_current_user_id();
                                if(is_numeric($user_id) && $user_id > 0){
                                ?>
                                    <div class="js-ticket-from-field-wrp">
                                        <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                        <div class="js-ticket-from-field js-ticket-form-field-select" id="eddlicensekey-wrap">
                                            <?php echo JSSTformfield::select('eddlicensekey', $license_key_array, $eddlicensekey, __('Select license key', 'js-support-ticket'), array('class' => 'inputbox js-ticket-select-field', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                        </div>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="js-ticket-from-field-wrp">
                                        <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                        <div class="js-ticket-from-field js-ticket-form-field-select" id="eddlicensekey-wrap">
                                            <?php  echo JSSTformfield::text('eddlicensekey', $eddlicensekey, array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => ($field->required) ? 'required' : '')); ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                break;
                            case 'envatopurchasecode':
                                if(!in_array('envatovalidation', jssupportticket::$_active_addons)){
                                    break;
                                }
                                if(!empty(jssupportticket::$_data[0]->envatodata)){
                                    $envlicense = json_decode(jssupportticket::$_data[0]->envatodata, true);
                                }else{
                                    $envlicense = array();
                                }
                                if($fieldcounter % 2 == 0){
                                    if($fieldcounter != 0){
                                        echo '</div>';
                                    }
                                    echo '<div class="js-ticket-add-form-wrapper">';
                                }
                                $fieldcounter++;

                                if(isset($formdata['envatopurchasecode'])) $envatopurchasecode = $formdata['envatopurchasecode'];
                                elseif(isset($envlicense['license'])) $envatopurchasecode = $envlicense['license'];
                                else $envatopurchasecode = '';  ?>
                                <div class="js-ticket-from-field-wrp">
                                    <div class="js-ticket-from-field-title"><?php echo __($field->fieldtitle, 'js-support-ticket'); ?>&nbsp;<?php if($field->required == 1) echo '&nbsp;<span style="color:red">*</span>'; ?></div>
                                    <div class="js-ticket-from-field js-ticket-form-field-select" id="envatopurchasecode-wrap">
                                        <?php echo JSSTformfield::text('envatopurchasecode', $envatopurchasecode, array('class' => 'inputbox js-ticket-form-field-input','data-validation'=>($field->required ? 'required' : ''))); ?>
                                    </div>
                                </div>
                                <?php
                                break;
                        default:
                            echo JSSTincluder::getObjectClass('customfields')->formCustomFields($field);
                            break;
                    }

                    //do_action_ref_array('jsst_ticket_form_field_loop', array($field, &$fieldcounter));

                endforeach;
                if($fieldcounter != 0){
                    echo '</div>'; // close extra div open in user field
                }
                echo '<input type="hidden" id="userfeilds_total" name="userfeilds_total"  value="' . $i . '"  />';
                ?>
                <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]->id) ? jssupportticket::$_data[0]->id : ''); ?>
                <?php echo JSSTformfield::hidden('attachmentdir', isset(jssupportticket::$_data[0]->attachmentdir) ? jssupportticket::$_data[0]->attachmentdir : ''); ?>
                <?php echo JSSTformfield::hidden('ticketid', isset(jssupportticket::$_data[0]->ticketid) ? jssupportticket::$_data[0]->ticketid : ''); ?>
                <?php echo JSSTformfield::hidden('created', isset(jssupportticket::$_data[0]->created) ? jssupportticket::$_data[0]->created : ''); ?>
                <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                <?php echo JSSTformfield::hidden('updated', isset(jssupportticket::$_data[0]->updated) ? jssupportticket::$_data[0]->updated : ''); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <?php echo JSSTformfield::hidden('jsstpageid', get_the_ID()); ?>
                <?php
                if(isset($paidsupportid)){
                    echo JSSTformfield::hidden('paidsupportid', $paidsupportid);
                }
                ?>
                <?php
                // captcha
                if (!is_user_logged_in()) {
                    if (jssupportticket::$_config['show_captcha_on_visitor_from_ticket'] == 1) {  ?>
                        <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                            <div class="js-ticket-from-field-title">
                                <?php echo __('Captcha', 'js-support-ticket'); ?>
                            </div>
                            <div class="js-ticket-from-field">
                               <?php
                                if (jssupportticket::$_config['captcha_selection'] == 1) { // Google recaptcha
                                    $error = null;
                                    echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
                                    echo '<div class="g-recaptcha" data-sitekey="'.jssupportticket::$_config['recaptcha_publickey'].'"></div>';
                                } else { // own captcha
                                    $captcha = new JSSTcaptcha;
                                    echo $captcha->getCaptchaForForm();
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>
                <div class="js-ticket-form-btn-wrp">
                    <?php echo JSSTformfield::submitbutton('save', __('Submit Ticket', 'js-support-ticket'), array('class' => 'js-ticket-save-button')); ?>
                    <a href="<?php echo esc_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel')));?>" class="js-ticket-cancel-button"><?php echo __('Cancel','js-support-ticket');?></a>
                </div>
            </form>
            <?php endif; ?>
        </div>
        <?php
    } else {// User is guest
        $redirect_url = jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'addticket'));
        $redirect_url = base64_encode($redirect_url);
        JSSTlayout::getUserGuest($redirect_url);
    }
} else { // System is offline
    JSSTlayout::getSystemOffline();
}
?>

</div>
