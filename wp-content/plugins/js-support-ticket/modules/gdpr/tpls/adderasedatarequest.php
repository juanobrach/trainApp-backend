<div class="jsst-main-up-wrapper">
    <?php
    if (jssupportticket::$_config['offline'] == 2) {
        if (get_current_user_id() != 0) {
            $yesno = array((object) array('id' => '1', 'text' => __('Yes', 'js-support-ticket')),
                (object) array('id' => '0', 'text' => __('No', 'js-support-ticket'))
            );
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $.validate();
                });
            </script>
            <?php JSSTmessage::getMessage(); ?>
            <?php /* JSSTbreadcrumbs::getBreadcrumbs(); */ ?>
            <?php include_once(jssupportticket::$_path . 'includes/header.php'); ?>
            <div class="js-ticket-add-form-wrapper">
                <div class="js-ticket-top-search-wrp">
                    <div class="js-ticket-search-heading-wrp">
                        <div class="js-ticket-heading-left">
                            <?php echo __('Export your data', 'js-support-ticket') ?>
                        </div>
                        <div class="js-ticket-heading-right">
                            <a class="js-ticket-add-download-btn" href="<?php echo esc_url(wp_nonce_url(jssupportticket::makeUrl(array('jstmod'=>'gdpr','task'=>'exportusereraserequest','action'=>'jstask','jssupportticketid'=> get_current_user_id() ,'jsstpageid'=>get_the_ID())),'export-usereraserequest')); ?>"><span class="js-ticket-add-img-wrp"></span><?php echo __('Export', 'js-support-ticket') ?></a>
                        </div>
                    </div>
                </div>
            <?php if(isset(jssupportticket::$_data[0]) && !empty(jssupportticket::$_data[0])) { ?>
                <div class="js-ticket-top-search-wrp second-style">
                    <div class="js-ticket-search-heading-wrp second-style">
                        <div class="js-ticket-heading-left">
                            <?php echo __('You have filed a request to remove your data.', 'js-support-ticket') ?>
                        </div>
                        <div class="js-ticket-heading-right">
                            <a class="js-ticket-add-download-btn" href="<?php echo esc_url(wp_nonce_url(jssupportticket::makeUrl(array('jstmod'=>'gdpr','task'=>'removeusereraserequest','action'=>'jstask','jssupportticketid'=> jssupportticket::$_data[0]->id ,'jsstpageid'=>get_the_ID())),'delete-usereraserequest')); ?>"><span class="js-ticket-add-img-wrp"></span><?php echo __('To withdraw erases data request', 'js-support-ticket') ?></a>
                        </div>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="js-ticket-top-search-wrp second-style">
                    <div class="js-ticket-search-heading-wrp second-style">
                        <div class="js-ticket-heading-left">
                            <?php echo __('Request data removal from the system.', 'js-support-ticket') ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
                <form class="js-ticket-form" method="post" action="<?php echo jssupportticket::makeUrl(array('jstmod'=>'gdpr', 'task'=>'saveusereraserequest')); ?>">
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Subject', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                        </div>
                        <div class="js-ticket-from-field">
                            <?php echo JSSTformfield::text('subject', isset(jssupportticket::$_data[0]->subject) ? jssupportticket::$_data[0]->subject : '', array('class' => 'inputbox js-ticket-form-field-input', 'data-validation' => 'required')) ?>
                        </div>
                    </div>
                    <div class="js-ticket-from-field-wrp js-ticket-from-field-wrp-full-width">
                        <div class="js-ticket-from-field-title">
                            <?php echo __('Message', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span>
                        </div>
                        <div class="js-ticket-from-field">
                            <?php echo wp_editor(isset(jssupportticket::$_data[0]->message) ? jssupportticket::$_data[0]->message : '', 'message', array('media_buttons' => false)); ?>
                        </div>
                    </div>
                    <?php echo JSSTformfield::hidden('jsstpageid', get_the_ID()); ?>
                    <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]->id) ?jssupportticket::$_data[0]->id :'' ); ?>
                    <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                    <div class="js-ticket-form-btn-wrp">
                        <?php echo JSSTformfield::submitbutton('save', __('Save', 'js-support-ticket'), array('class' => 'js-ticket-save-button')); ?>
                        <a href="<?php echo jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel'));?>" class="js-ticket-cancel-button"><?php echo __('Cancel','js-support-ticket'); ?></a>
                    </div>
                </form>
            </div>
            <?php
        } else {
            JSSTlayout::getUserGuest();
        }
    } else {
        JSSTlayout::getSystemOffline();
} ?>
</div>
