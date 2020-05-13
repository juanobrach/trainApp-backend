<div class="jsst-main-up-wrapper">
<?php
if (jssupportticket::$_config['offline'] == 2) {
    if (get_current_user_id() != 0 || jssupportticket::$_config['visitor_can_create_ticket'] == 1) {
        JSSTmessage::getMessage();
        ?>
        <?php /* JSSTbreadcrumbs::getBreadcrumbs(); */ ?>
        <?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>
        <div class="js-ticket-checkstatus-wrp">
            <form class="js-ticket-form form-validate" action="<?php echo jssupportticket::makeUrl(array('jstmod'=>'ticket','task'=>'showticketstatus')); ?>" method="post" id="adminForm" enctype="multipart/form-data">
                <div class="js-ticket-checkstatus-field-wrp">
                    <div class="js-ticket-field-title">
                        <?php echo __('Email','js-support-ticket'); ?>&nbsp;<span style="color: red">*</span>
                    </div>
                    <div class="js-ticket-field-wrp">
                        <input class="inputbox js-ticket-form-input-field required validate-email" data-validation="email" type="text" name="email" id="email" size="40" maxlength="255" value="<?php if (isset(jssupportticket::$_data['0']->email)) echo jssupportticket::$_data['0']->email; ?>" />
                    </div>
                </div>
                <div class="js-ticket-checkstatus-field-wrp">
                    <div class="js-ticket-field-title">
                        <?php echo __('Ticket ID','js-support-ticket'); ?>&nbsp;<span style="color: red">*</span>
                    </div>
                    <div class="js-ticket-field-wrp">
                        <input class="inputbox js-ticket-form-input-field required" type="text" name="ticketid" id="ticketid" size="40" maxlength="255" value="" />
                    </div>
                </div>
                <div class="js-ticket-form-btn-wrp">
                    <input class="tk_dft_btn js-ticket-save-button" type="submit" name="submit_app" value="<?php echo __('Check Status', 'js-support-ticket'); ?>" />
                </div>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <?php echo JSSTformfield::hidden('checkstatus', 1); ?>
                <?php echo JSSTformfield::hidden('jsstpageid',get_the_ID()); ?>
            </form>
        </div>
    <?php
    }else {// User is guest
        $redirect_url = jssupportticket::makeUrl(array('jstmod'=>'ticket','jstlay'=>'ticketstatus'));
        $redirect_url = base64_encode($redirect_url);
        JSSTlayout::getUserGuest($redirect_url);
    }
} else { // System is offline
    JSSTlayout::getSystemOffline();
}
?>
</div>
