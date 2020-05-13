<?php
if (!defined('ABSPATH')) die('Restricted Access');
$ticketidsequence = array(
    (object) array('id' => '0', 'text' => __('Random', 'js-support-ticket')),
    (object) array('id' => '1', 'text' => __('Sequential', 'js-support-ticket'))
    );
$type = array(
    (object) array('id' => '0', 'text' => __('Days', 'js-support-ticket')),
    (object) array('id' => '1', 'text' => __('Hours', 'js-support-ticket'))
    );

?>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-cparea">
        <div id="jsst-main-wrapper" class="post-installation">
            <div class="js-admin-title-installtion">
                <span class="jsst_heading"><?php echo __('JS Support Ticket Settings','js-support-ticket'); ?></span>
                <div class="close-button-bottom">
                    <a href="#" class="close-button">
                        <img alt="image" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/close-icon.png';?>" />
                    </a>
                </div>
            </div>
            <div class="post-installtion-content-wrapper">
                <div class="post-installtion-content-header">
                    <ul class="update-header-img step-1">
                        <li class="header-parts first-part">
                            <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=stepone"); ?>" title="link" class="tab_icon">
                                <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/general-settings.png';?>" />
                                <span class="text"><?php echo __('General','js-support-ticket'); ?></span>
                            </a>
                        </li>
                        <li class="header-parts second-part">
                            <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=steptwo"); ?>" title="link" class="tab_icon">
                                <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/ticket.png';?>" />
                                <span class="text"><?php echo __('Ticket Settings','js-support-ticket'); ?></span>
                            </a>
                        </li>
                        <li class="header-parts third-part active">
                           <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=stepthree"); ?>" title="link" class="tab_icon">
                               <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/feedback.png';?>" />
                                <span class="text"><?php echo __('Feedback Settings','js-support-ticket'); ?></span>
                            </a>
                        </li>
                        <li class="header-parts forth-part">
                            <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=settingcomplete"); ?>" title="link" class="tab_icon">
                               <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/complete.png';?>" />
                                <span class="text"><?php echo __('Complete','js-support-ticket'); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="post-installtion-content_wrapper_right">
                    <div class="jsst-config-topheading">
                        <span class="heading-post-ins jsst-configurations-heading"><?php echo __('Feedback Configurations','js-support-ticket');?></span>
                        <span class="heading-post-ins jsst-config-steps"><?php echo __('Step 3 of 4','js-support-ticket');?></span>
                    </div>
                    <div class="post-installtion-content">
                        <form id="jssupportticket-form-ins" method="post" action="<?php echo admin_url("admin.php?page=postinstallation&task=save&action=jstask"); ?>">
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Feedback Email Delay Type','js-support-ticket'); ?><?php echo __(':', 'js-support-ticket');?>
                                </div>
                                <div class="field">
                                     <?php echo JSSTformfield::select('feedback_email_delay_type', $type , isset(jssupportticket::$_data[0]['feedback_email_delay_type']) ? jssupportticket::$_data[0]['feedback_email_delay_type'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                    <?php echo __('Set Email Delay Time'); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Feedback Email Delay','js-support-ticket'); ?><?php echo __(' :', 'js-support-ticket');?>
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::text('feedback_email_delay', isset(jssupportticket::$_data[0]['feedback_email_delay']) ? jssupportticket::$_data[0]['feedback_email_delay'] : '', array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting', 'data-validation' => 'required')) ?>
                                </div>
                                <div class="desc">
                                    <?php echo __('Set Email Delay','js-support-ticket'); ?>
                                </div>
                            </div>
                             <div class="pic-button-part">
                                <a class="next-step" href="#" onclick="document.getElementById('jssupportticket-form-ins').submit();" >
                                    <?php echo __('Next','js-learn-manager'); ?>
                                     <img alt="image" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/next-arrow.png';?>">
                                </a>
                                <a class="back" href="<?php echo admin_url('admin.php?page=postinstallation&jstlay=steptwo'); ?>">
                                    <img alt="image" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/back-arrow.png';?>">
                                    <?php echo __('Back','js-support-ticket'); ?>
                                </a>
                            </div>
                            <?php echo JSSTformfield::hidden('action', 'postinstallation_save'); ?>
                            <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            <?php echo JSSTformfield::hidden('step', 3); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
