<div class="jsst-main-up-wrapper">
<?php
if (jssupportticket::$_config['offline'] == 2) {
    if (jssupportticket::$_data['permission_granted'] == 1) {
        if (get_current_user_id() != 0) {
            if ( in_array('agent',jssupportticket::$_active_addons) && JSSTincluder::getJSModel('agent')->isUserStaff()) {
                if (jssupportticket::$_data['staff_enabled']) {
                    $type = array((object) array('id' => '1', 'text' => __('Public', 'js-support-ticket')),
                        (object) array('id' => '0', 'text' => __('Private', 'js-support-ticket'))
                    );
                    $status = array((object) array('id' => '1', 'text' => __('Enabled', 'js-support-ticket')),
                        (object) array('id' => '0', 'text' => __('Disabled', 'js-support-ticket'))
                    );
                    $yesno = array((object) array('id' => '1', 'text' => __('Yes', 'js-support-ticket')),
                        (object) array('id' => '0', 'text' => __('No', 'js-support-ticket'))
                    );
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            $.validate();
                        });
                    </script>
                    <?php /* JSSTbreadcrumbs::getBreadcrumbs(); */ ?>
                    <?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>

                    <div class="js-ticket-add-form-wrapper">
                        <form class="js-ticket-form" method="post" action="<?php echo jssupportticket::makeUrl(array('jstmod'=>'department', 'task'=>'savedepartment')); ?>">
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Title', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                                </div>
                                <div class="js-ticket-from-field">
                                    <?php echo JSSTformfield::text('departmentname', isset(jssupportticket::$_data[0]->departmentname) ? jssupportticket::$_data[0]->departmentname : '', array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => 'required')) ?>
                                </div>
                            </div>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Outgoing Email', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                                </div>
                                <div class="js-ticket-from-field js-ticket-form-field-select">
                                    <?php echo JSSTformfield::select('emailid', JSSTincluder::getJSModel('email')->getEmailForDepartment(), isset(jssupportticket::$_data[0]->emailid) ? jssupportticket::$_data[0]->emailid : '', __('Select Email', 'js-support-ticket'), array('class' => 'inputbox js-ticket-form-field-select', 'data-validation' => 'required')); ?>
                                </div>
                                <span class="js-support-ticket-outgoing-email-message" >(<?php echo __('Tickets of this department will receive emails from this email','js-support-ticket');?>)</span>
                            </div>
                            <div class="js-ticket-from-field-wrp">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Receive Email', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                                </div>
                                <div class="js-ticket-from-field">
                                    <div class="js-ticket-radio-btn-wrp">
                                        <?php echo JSSTformfield::radiobutton('sendmail', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->sendmail) ? jssupportticket::$_data[0]->sendmail : '0', array('class' => 'radiobutton js-ticket-form-field-radio-btn')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="js-ticket-append-signature-wrp js-ticket-append-signature-wrp-full-width"><!-- Append Signature -->
                                <div class="js-ticket-append-field-title"><?php echo __('Append Signature', 'js-support-ticket'); ?></div>
                                <div class="js-ticket-append-field-wrp">
                                    <div class="js-ticket-signature-radio-box js-ticket-signature-radio-box-full-width ">
                                        <?php echo JSSTformfield::checkbox('canappendsignature', array('1' => __('Append signature with a reply', 'js-support-ticket')), isset(jssupportticket::$_data[0]->canappendsignature) ? jssupportticket::$_data[0]->canappendsignature : '', array('class' => 'radiobutton js-ticket-append-radio-btn')); ?>
                                    </div>

                                </div>
                            </div>
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Signature', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                                </div>
                                <div class="js-ticket-from-field">
                                    <?php echo wp_editor(isset(jssupportticket::$_data[0]->departmentsignature) ? jssupportticket::$_data[0]->departmentsignature : '', 'departmentsignature', array('media_buttons' => false)); ?>
                                </div>
                            </div>
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Status', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                                </div>
                                <div class="js-ticket-from-field js-ticket-form-field-select">
                                    <?php echo JSSTformfield::select('status', $status, isset(jssupportticket::$_data[0]->status) ? jssupportticket::$_data[0]->status : '', __('Select Status', 'js-support-ticket'), array('class' => 'inputbox js-ticket-form-field-input')); ?>
                                </div>
                            </div>
                            <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                                <div class="js-ticket-from-field-title">
                                    <?php echo __('Default', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                                </div>
                                <div class="js-ticket-from-field js-ticket-form-field-select">
                                    <?php echo JSSTformfield::radiobutton('isdefault', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->isdefault) ? jssupportticket::$_data[0]->isdefault : '0', array('class' => 'radiobutton js-ticket-form-field-radio-btn')); ?>

                                </div>
                            </div>
                            <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]->id) ? jssupportticket::$_data[0]->id : ''); ?>
                            <?php echo JSSTformfield::hidden('created', isset(jssupportticket::$_data[0]->created) ? jssupportticket::$_data[0]->created : ''); ?>
                            <?php echo JSSTformfield::hidden('updated', isset(jssupportticket::$_data[0]->updated) ? jssupportticket::$_data[0]->updated : ''); ?>
                            <?php echo JSSTformfield::hidden('ordering', isset(jssupportticket::$_data[0]->ordering) ? jssupportticket::$_data[0]->ordering : ''); ?>
                            <?php echo JSSTformfield::hidden('action', 'department_savedepartment'); ?>
                            <?php echo JSSTformfield::hidden('jsstpageid', get_the_ID()); ?>
                            <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            <div class="js-ticket-form-btn-wrp">
                                <?php echo JSSTformfield::submitbutton('save', __('Save Department', 'js-support-ticket'), array('class' => 'js-ticket-save-button')); ?>
                                <a href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'department', 'jstlay'=>'departments'));?>" class="js-ticket-cancel-button"><?php echo __('Cancel','js-support-ticket'); ?></a>
                            </div>
                        </form>
                    </div>
                    <?php
                } else {
                    JSSTlayout::getStaffMemberDisable();
                }
            } else { // user not Staff
                JSSTlayout::getNotStaffMember();
            }
        } else {
            $redirect_url = jssupportticket::makeUrl(array('jstmod'=>'department', 'jstlay'=>'adddepartment'));
            $redirect_url = base64_encode($redirect_url);
            JSSTlayout::getUserGuest($redirect_url);
        }
    } else { // User permission not granted
        JSSTlayout::getPermissionNotGranted();
    }
} else {
    JSSTlayout::getSystemOffline();
} ?>
</div>
