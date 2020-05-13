<?php
if (jssupportticket::$_config['offline'] == 2) {
    ?>
    <?php JSSTmessage::getMessage(); ?>
    <div class="jsst-main-up-wrapper">
        <?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>
        <h1 class="jsst-missing-addon-message" >
            Page Not Found !!
        </h1>
    <?php
} else {
    JSSTlayout::getSystemOffline();
} ?>