<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
</script>

<div id="jsstadmin-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">
        <div id="jsstadmin-wrapper-top">
            <div id="jsstadmin-wrapper-top-left">
                <div id="jsstadmin-breadcrunbs">
                    <ul>
                        <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
                        <li><?php echo __('Add Department','js-support-ticket'); ?></li>
                    </ul>
                </div>
            </div>
            <div id="jsstadmin-wrapper-top-right">
                <div id="jsstadmin-config-btn">
                    <a title="<?php echo __('Configuration','js-support-ticket'); ?>" href="<?php echo admin_url("admin.php?page=configuration"); ?>">
                        <img alt="<?php echo __('Configuration','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/config.png" />
                    </a>
                </div>
                <div id="jsstadmin-vers-txt">
                    <?php echo __("Version",'js-support-ticket'); ?>:
                    <span class="jsstadmin-ver"><?php echo JSSTincluder::getJSModel('configuration')->getConfigValue('versioncode'); ?></span>
                </div>
            </div>
        </div>
        <div id="jsstadmin-head">
            <h1 class="jsstadmin-head-text"><?php echo __('Add Department', 'js-support-ticket'); ?></h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form class="jsstadmin-form" method="post" action="<?php echo admin_url("admin.php?page=department&task=savedepartment"); ?>">
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Title', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('departmentname', isset(jssupportticket::$_data[0]->departmentname) ? jssupportticket::$_data[0]->departmentname : '', array('class' => 'inputbox js-form-input-field', 'data-validation' => 'required')) ?></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title">
                        <?php echo __('Outgoing Email', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                        <a title="<?php echo __('Add New Email','js-support-ticket'); ?>" class="js-form-link" href="?page=email&jstlay=addemail"><?php echo __('Add New Email','js-support-ticket'); ?></a>
                    </div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('emailid', JSSTincluder::getJSModel('email')->getEmailForDepartment(), isset(jssupportticket::$_data[0]->emailid) ? jssupportticket::$_data[0]->emailid : '', __('Select Email', 'js-support-ticket'), array('class' => 'inputbox js-form-select-field', 'data-validation' => 'required')); ?>
                    </div>
                    <div class="js-form-desc">(<?php echo __('The user of this department will receive email on the new ticket','js-support-ticket'); ?>)</div>
                </div>
                <div class="js-form-wrapper" style="display:none;">
                    <div class="js-form-title"><?php echo __('Public', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('ispublic', array('1' => __('Public', 'js-support-ticket'), '0' => __('Private', 'js-support-ticket')), isset(jssupportticket::$_data[0]->ispublic) ? jssupportticket::$_data[0]->ispublic : '1', array('class' => 'radiobutton')); ?></div>
                </div>
                <div class="js-form-wrapper" >
                    <div class="js-form-title"><?php echo __('Receive Email', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('sendmail', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->sendmail) ? jssupportticket::$_data[0]->sendmail : '0', array('class' => 'radiobutton')); ?></div>
                </div>
                <div class="js-form-wrapper fullwidth">
                    <div class="js-form-title"><?php echo __('Signature', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo wp_editor(isset(jssupportticket::$_data[0]->departmentsignature) ? jssupportticket::$_data[0]->departmentsignature : '', 'departmentsignature', array('media_buttons' => false)); ?></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Append Signature', 'js-support-ticket'); ?></div>
                    <div class="js-form-value">
                        <div class="js-form-chkbox-field">
                            <?php echo JSSTformfield::checkbox('canappendsignature', array('1' => __('Append signature with a reply', 'js-support-ticket')), isset(jssupportticket::$_data[0]->canappendsignature) ? jssupportticket::$_data[0]->canappendsignature : '1', array('class' => 'radiobutton')); ?>
                        </div>
                    </div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Status', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('status', array('1' => __('Enabled', 'js-support-ticket'), '0' => __('Disabled', 'js-support-ticket')), isset(jssupportticket::$_data[0]->status) ? jssupportticket::$_data[0]->status : '1', array('class' => 'radiobutton')); ?></div>
                </div>
                <div class="js-form-wrapper" >
                    <div class="js-form-title"><?php echo __('Default', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('isdefault', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->isdefault) ? jssupportticket::$_data[0]->isdefault : '0', array('class' => 'radiobutton')); ?></div>
                </div>
                <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]->id) ? jssupportticket::$_data[0]->id : ''); ?>
                <?php echo JSSTformfield::hidden('created', isset(jssupportticket::$_data[0]->created) ? jssupportticket::$_data[0]->created : ''); ?>
                <?php echo JSSTformfield::hidden('updated', isset(jssupportticket::$_data[0]->updated) ? jssupportticket::$_data[0]->updated : ''); ?>
                <?php echo JSSTformfield::hidden('ordering', isset(jssupportticket::$_data[0]->ordering) ? jssupportticket::$_data[0]->ordering : ''); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <div class="js-form-button">
                    <?php echo JSSTformfield::submitbutton('save', __('Save Department', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                </div>
            </form>
        </div>
    </div>
</div>
