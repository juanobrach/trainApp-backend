    <div id="jsstadmin-wrapper">
        <div id="jsstadmin-leftmenu">
            <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
        </div>
    <div id="jsstadmin-data">
        <span class="js-adminhead-title"><a class="jsanchor-backlink" href="<?php echo admin_url('admin.php?page=jssupportticket');?>"><img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/back-icon.png" /></a><span class="jsheadtext"><?php echo __('Premium Addons', 'js-support-ticket'); ?></span></span>

        <div id="jssupportticket-content">
            <h1 class="jsst-missing-addon-message" >
                <?php
                $addon_name = JSSTrequest::getVar('page');
                echo ucfirst($addon_name).'&nbsp;';
                echo __('addon in no longer active','js-support-ticket').'!';
                ?>

            </h1>
        </div>
    </div>
</div>
