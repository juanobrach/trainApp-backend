<?php JSSTmessage::getMessage(); ?>
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
                        <li><?php echo __('Reports','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __("Reports", 'js-support-ticket') ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <a class="js-admin-report-wrapper" href="<?php echo admin_url('admin.php?page=reports&jstlay=overallreport'); ?>" >
                <div class="js-admin-overall-report-type-wrapper">
                    <img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/report/overall_icon.png" />
                    <span class="js-admin-staff-report-type-label"><?php echo __('Overall Statistics','js-support-ticket'); ?></span>
                </div>
            </a>
            <a class="js-admin-report-wrapper" href="<?php echo admin_url('admin.php?page=reports&jstlay=staffreport'); ?>" >
                <div class="js-admin-staff-report-type-wrapper">
                    <img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/report/staff.png" />
                    <span class="js-admin-staff-report-type-label"><?php echo __('Staff Reports','js-support-ticket'); ?></span>
                </div>
            </a>
            <a class="js-admin-report-wrapper" href="<?php echo admin_url('admin.php?page=reports&jstlay=departmentreport'); ?>" >
                <div class="js-admin-department-report-type-wrapper">
                    <img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/report/department.png" />
                    <span class="js-admin-staff-report-type-label"><?php echo __('Department Reports','js-support-ticket'); ?></span>
                </div>
            </a>
            <a class="js-admin-report-wrapper" href="<?php echo admin_url('admin.php?page=reports&jstlay=userreport'); ?>" >
                <div class="js-admin-user-report-type-wrapper">
                    <img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/report/user.png" />
                    <span class="js-admin-user-report-type-label"><?php echo __('User Reports','js-support-ticket'); ?></span>
                </div>
            </a>
        </div>
    </div>
</div>
