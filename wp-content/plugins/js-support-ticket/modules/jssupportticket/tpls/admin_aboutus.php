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
    	                <li><?php echo __('About Us','js-support-ticket'); ?></li>
    	            </ul>
    	        </div>
    	    </div>
    	    <div id="jsstadmin-wrapper-top-right">
    	        <div id="jsstadmin-config-btn">
    	            <a href="<?php echo admin_url("admin.php?page=configuration"); ?>" title="<?php echo __('Configuration','js-support-ticket'); ?>">
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
            <h1 class="jsstadmin-head-text"><?php echo __('About Us','js-support-ticket'); ?></h1>
        </div>
    	<div id="jsstadmin-data-wrp" class="p0">
    		<div class="jssst-admin-about-us">
				<div class="js-admin-heading">
					<span class="js-admin-head-txt">
						<?php echo __('Plugin Detail','js-support-ticket'); ?>
					</span>
				</div>
				<div class="jssst-admin-about-us-cnt">
					<div class="jssst-admin-about-author">
						<div class="jssst-author-tit">
							<?php echo __('Plugin for online JS Help Desk System','js-support-ticket'); ?>
						</div>
						<div class="jssst-author-cnt">
							<div class="jssst-author-info">
								<span class="jssst-auth-info-title"><?php echo __('Created By','js-support-ticket'); ?></span>
								<span class="jssst-auth-info-value">Ahmad Bilal</span>
							</div>
							<div class="jssst-author-info">
								<span class="jssst-auth-info-title"><?php echo __('Company','js-support-ticket'); ?></span>
								<span class="jssst-auth-info-value">JoomSky</span>
							</div>
							<div class="jssst-author-info">
								<span class="jssst-auth-info-title"><?php echo __('Plugin Name','js-support-ticket'); ?></span>
								<span class="jssst-auth-info-value"><?php echo __('JS Help Desk','js-support-ticket'); ?></span>
							</div>
						</div>
					</div>
					<div class="jssst-admin-author-prdct">
						<a href="https://www.joomsky.com/products/js-jobs-pro-wp.html" target="_blank" class="jssst-admin-author-prdct-item" title="<?php echo __('job plugin','js-support-ticket'); ?>">
							<img alt="<?php echo __('job plugin','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/aboutus_page/job-plugin.jpg" />
						</a>
					</div>
					<div class="jssst-admin-author-prdct">
						<a href="https://www.joomsky.com/products/js-vehicle-manager-pro-wp.html" class="jssst-admin-author-prdct-item" title="<?php echo __('vehicle manager','js-support-ticket'); ?>">
							<img alt="<?php echo __('vehicle manager','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/aboutus_page/vehicle-manager.jpg" />
						</a>
					</div>
					<div class="jssst-admin-author-prdct">
						<a href="https://www.joomsky.com/products/js-learn-manager-pro-wp.html" class="jssst-admin-author-prdct-item" title="<?php echo __('lms plugin','js-support-ticket'); ?>">
							<img alt="<?php echo __('lms plugin','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/aboutus_page/lms.jpg" />
						</a>
					</div>
					<div class="jssst-admin-author-prdct">
						<a href="https://themeforest.net/item/car-manager-car-dealership-business-wordpress-theme/19350332" class="jssst-admin-author-prdct-item" title="<?php echo __('car manager','js-support-ticket'); ?>">
							<img alt="<?php echo __('car manager','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/aboutus_page/car-manager.jpg" />
						</a>
					</div>
					<div class="jssst-admin-author-prdct">
						<a href="https://www.joomsky.com/products/js-jobs/job-manager-theme.html" class="jssst-admin-author-prdct-item" title="<?php echo __('job manager','js-support-ticket'); ?>">
							<img alt="<?php echo __('job manager','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/aboutus_page/job-manager.jpg" />
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
