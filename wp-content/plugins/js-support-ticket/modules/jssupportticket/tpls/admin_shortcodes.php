<?php
    $filepath = jssupportticket::$_path . 'includes/css/style.php';
    $filestring = file_get_contents($filepath);
    $color1 = JSSTincluder::getJSModel('jssupportticket')->getColorCode($filestring, 1);
    $color3 = JSSTincluder::getJSModel('jssupportticket')->getColorCode($filestring, 3);
?>
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
		                <li><?php echo __('Short Codes','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Short Codes', 'js-support-ticket'); ?></h1>
        </div>
    	<div id="jsstadmin-data-wrp" class="p0">
			<div id="jsst-shortcode-wrapper">
				<div class="jsst-shortcode-1"><?php echo __('JS Help Desk / JS Support Ticket Control Panel','js-support-ticket'); ?></div>
				<div class="jsst-shortcode-2"><?php echo "[jssupportticket]"; ?></div>
				<div class="jsst-shortcode-3"><?php echo __("JS Help Desk / JS Support Ticket main control panel",'js-support-ticket'); ?></div>
			</div>
			<div id="jsst-shortcode-wrapper">
				<div class="jsst-shortcode-1"><?php echo __('Add Ticket','js-support-ticket'); ?></div>
				<div class="jsst-shortcode-2"><?php echo "[jssupportticket_addticket]"; ?></div>
				<div class="jsst-shortcode-3"><?php echo __("Add new ticket form for both user and agent",'js-support-ticket'); ?></div>
			</div>
			<div id="jsst-shortcode-wrapper">
				<div class="jsst-shortcode-1"><?php echo __('My Tickets','js-support-ticket'); ?></div>
				<div class="jsst-shortcode-2"><?php echo "[jssupportticket_mytickets]"; ?></div>
				<div class="jsst-shortcode-3"><?php echo __("My tickets for both user and agent",'js-support-ticket'); ?></div>
			</div>
			<?php if(in_array('download', jssupportticket::$_active_addons)){ ?>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Downloads','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_downloads]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("List downloads",'js-support-ticket'); ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Latest Downloads','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_downloads_latest]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show latest downloads. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_downloads_latest text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Popular Downloads','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_downloads_popular]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show popular downloads. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_downloads_popular text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
			<?php } ?>
			<?php if(in_array('knowledgebase', jssupportticket::$_active_addons)){ ?>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Knowledge Base','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_knowledgebase]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("List knowledge base",'js-support-ticket'); ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Latest Knowledge Base','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_knowledgebase_latest]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show latest knowledge base. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_knowledgebase_latest text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Popular knowledge base','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_knowledgebase_popular]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show popular knowledge base. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_knowledgebase_popular text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
			<?php } ?>
			<?php if(in_array('faq', jssupportticket::$_active_addons)){ ?>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __("FAQ's",'js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_faqs]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("List FAQ's",'js-support-ticket'); ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __("Latest FAQ's",'js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_faqs_latest]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show latest FAQ's. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_faqs_latest text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __("Popular FAQ's",'js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_faqs_popular]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show popular FAQ's. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_faqs_popular text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
			<?php } ?>
			<?php if(in_array('announcement', jssupportticket::$_active_addons)){ ?>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Announcements','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_announcements]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("List announcements",'js-support-ticket'); ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Latest Announcements','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_announcements_latest]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show latest announcements. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_announcements_latest text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
				<div id="jsst-shortcode-wrapper">
					<div class="jsst-shortcode-1"><?php echo __('Popular Announcements','js-support-ticket'); ?></div>
					<div class="jsst-shortcode-2"><?php echo "[jssupportticket_announcements_popular]"; ?></div>
					<div class="jsst-shortcode-3"><?php echo __("Show popular announcements. Options",'js-support-ticket').': text_color="'.$color3.'" '.__("and",'js-support-ticket').' background_color="'.$color1.'" '.__("i.e.",'js-support-ticket').' [jssupportticket_announcements_popular text_color="'.$color3.'" background_color="'.$color1.'"]'; ?></div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
