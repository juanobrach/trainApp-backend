<div class="jsst-main-up-wrapper">
<?php
if (jssupportticket::$_config['offline'] == 2) {
        JSSTmessage::getMessage();
        /*JSSTbreadcrumbs::getBreadcrumbs();*/
        include_once(jssupportticket::$_path . 'includes/header.php'); ?>
        <div class="jsst-visitor-message-wrapper" >
            <img alt="image" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/jsst-support-icon.png'; ?>" />
            <span class="jsst-visitor-message" >
                <?php echo jssupportticket::$_config['visitor_message']?>
            </span>
        </div>
<?php
} else { // System is offline
    JSSTlayout::getSystemOffline();
}
?>
</div>
