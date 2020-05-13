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
                        <li><?php echo __('Install Addons','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Install Addons', 'js-support-ticket'); ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <div id="jssupportticket-content">
                <div id="black_wrapper_translation"></div>
                <div id="jstran_loading">
                    <img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/spinning-wheel.gif" />
                </div>
                <div id="jsst-lower-wrapper">
                    <div class="jsst-addon-installer-wrapper step3" >
                        <div class="jsst-addon-installer-left-image-wrap" >
                            <img class="jsst-addon-installer-left-image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/addon-images/addon-installer-logo.png" />
                        </div>
                        <div class="jsst-addon-installer-left-heading" >
                            <?php echo __("Add ons installed and activated successfully","js-support-ticket"); ?>
                        </div>
                        <div class="jsst-addon-installer-left-description" >
                            <?php echo __("Add ons for JS Help Desk have been installed and activated successfully. ","js-support-ticket"); ?>
                        </div>
                        <div class="jsst-addon-installer-right-button" >
                            <a class="jsst_btn" href="?page=jssupportticket" ><?php echo __("Control Panel","js-support-ticket"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
