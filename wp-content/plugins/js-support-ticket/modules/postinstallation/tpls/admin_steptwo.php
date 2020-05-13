<?php
if (!defined('ABSPATH')) die('Restricted Access');
$yesno = array(
    (object) array('id' => '0', 'text' => __('Yes', 'js-support-ticket')),
    (object) array('id' => '1', 'text' => __('No', 'js-support-ticket'))
    );

$ticketidsequence = array(
    (object) array('id' => '0', 'text' => __('Random', 'js-support-ticket')),
    (object) array('id' => '1', 'text' => __('Sequential', 'js-support-ticket'))
    );
$owncaptchaoparend = array(
    (object) array('id' => '2', 'text' => __('2', 'js-support-ticket')),
    (object) array('id' => '3', 'text' => __('3', 'js-support-ticket'))
    );

?>
<div id="js-tk-admin-wrapper">
    <div id="js-tk-cparea">
        <div id="jsst-main-wrapper" class="post-installation">
            <div class="js-admin-title-installtion">
                <span class="jsst_heading"><?php echo __('JS Support Ticket Settings','js-support-ticket'); ?></span>
                <div class="close-button-bottom">
                    <a href="?page=jssupportticket" class="close-button">
                        <img alt="image" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/close-icon.png';?>" />
                    </a>
                </div>
            </div>
            <div class="post-installtion-content-wrapper">
                <div class="post-installtion-content-header">
                    <ul class="update-header-img step-1">
                        <li class="header-parts first-part ">
                            <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=stepone"); ?>" title="link" class="tab_icon">
                                <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/general-settings.png';?>" />
                                <span class="text"><?php echo __('General','js-support-ticket'); ?></span>
                            </a>
                        </li>
                        <li class="header-parts second-part active">
                            <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=steptwo"); ?>" title="link" class="tab_icon">
                                <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/ticket.png';?>" />
                                <span class="text"><?php echo __('Ticket Settings','js-support-ticket'); ?></span>
                            </a>
                        </li>
                        <?php if(in_array('feedback', jssupportticket::$_active_addons)){ ?>
                            <li class="header-parts third-part">
                               <a href="<?php echo admin_url("admin.php?page=postinstallation&jstlay=stepthree"); ?>" title="link" class="tab_icon">
                                   <img class="start" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/feedback.png';?>" />
                                    <span class="text"><?php echo __('Feedback Settings','js-support-ticket'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
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
                        <span class="heading-post-ins jsst-configurations-heading"><?php echo __('Ticket Configurations','js-support-ticket');?></span>
                        <?php
                            if(in_array('feedback', jssupportticket::$_active_addons)){
                                $steps = __('Step 2 of 4','js-support-ticket');
                            }else{
                                $steps = __('Step 2 of 3','js-support-ticket');
                            }
                        ?>
                        <span class="heading-post-ins jsst-config-steps"><?php echo $steps; ?></span>
                    </div>
                    <div class="post-installtion-content">
                        <form id="jssupportticket-form-ins" method="post" action="<?php echo admin_url("admin.php?page=postinstallation&task=save&action=jstask"); ?>">
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Visitor can create ticket','js-support-ticket'); ?><?php echo __(':', 'js-support-ticket');?>
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::select('visitor_can_create_ticket', $yesno , isset(jssupportticket::$_data[0]['cplink_openticket_staffvisitor_can_create_ticket']) ? jssupportticket::$_data[0]['visitor_can_create_ticket'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                    <?php echo __("Enable/Disable Open Ticket"); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Ticket ID sequence','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::select('ticketid_sequence', $ticketidsequence , isset(jssupportticket::$_data[0]['ticketid_sequence']) ? jssupportticket::$_data[0]['ticketid_sequence'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                    <?php echo __("Set ticket id sequential or random",'js-support-ticket'); ?>&nbsp;
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Maximum Tickets','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::text('maximum_tickets', isset(jssupportticket::$_data[0]['maximum_tickets']) ? jssupportticket::$_data[0]['maximum_tickets'] : '', array('class' => 'inputbox jsst-postsetting', 'data-validation' => 'required')) ?>
                                </div>
                                <div class="desc">
                                    <?php echo __("Set Maximum Ticket Per user"); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Maximum Open Ticket','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                   <?php echo JSSTformfield::text('maximum_open_tickets', isset(jssupportticket::$_data[0]['maximum_open_tickets']) ? jssupportticket::$_data[0]['maximum_open_tickets'] : '', array('class' => 'inputbox jsst-postsetting', 'data-validation' => 'required')) ?>
                                </div>
                                <div class="desc">
                                    <?php echo __("Maximum Open Ticket",'js-support-ticket'); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Reopen ticket within Days','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::text('reopen_ticket_within_days', isset(jssupportticket::$_data[0]['reopen_ticket_within_days']) ? jssupportticket::$_data[0]['reopen_ticket_within_days'] : '', array('class' => 'inputbox jsst-postsetting', 'data-validation' => 'required')) ?>
                                </div>
                                <div class="desc">
                                    <?php echo __("The ticket can be reopened within a given number of days",'js-support-ticket'); ?>&nbsp;
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Show Captcha to visitor on ticket form','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::select('show_captcha_on_visitor_from_ticket', $yesno , isset(jssupportticket::$_data[0]['show_captcha_on_visitor_from_ticket']) ? jssupportticket::$_data[0]['show_captcha_on_visitor_from_ticket'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                    <?php echo __("Enable/Disable Captcha on Ticket Form",'js-support-ticket'); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Own Captcha operands','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::select('owncaptcha_totaloperand', $owncaptchaoparend , isset(jssupportticket::$_data[0]['owncaptcha_totaloperand']) ? jssupportticket::$_data[0]['owncaptcha_totaloperand'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                   <?php echo __("Select the total operands to be given",'js-support-ticket'); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Own captcha subtraction answer positive','js-support-ticket'); ?><?php echo __(' :', 'js-support-ticket');?>
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::select('owncaptcha_subtractionans', $yesno , isset(jssupportticket::$_data[0]['owncaptcha_subtractionans']) ? jssupportticket::$_data[0]['owncaptcha_subtractionans'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                   <?php echo __("Enable/Disable Own Captcha subtraction"); ?>
                                </div>
                            </div>
                            <div class="pic-config">
                                <div class="title">
                                    <?php echo __('Enable Print Ticket','js-support-ticket'); ?>:
                                </div>
                                <div class="field">
                                    <?php echo JSSTformfield::select('print_ticket_user', $yesno , isset(jssupportticket::$_data[0]['print_ticket_user']) ? jssupportticket::$_data[0]['print_ticket_user'] : '', __('Select Type', 'js-support-ticket') , array('class' => 'inputbox jsst-postsetting js-select jsst-postsetting '));?>
                                </div>
                                <div class="desc">
                                   <?php echo __("Enable/Disable Print Ticket"); ?>
                                </div>
                            </div>
                            <div class="pic-button-part">
                                <a class="next-step" href="#" onclick="document.getElementById('jssupportticket-form-ins').submit();" >
                                    <?php echo __('Next','js-learn-manager'); ?>
                                     <img alt="image" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/next-arrow.png';?>">
                                </a>
                                <a class="back" href="<?php echo admin_url('admin.php?page=postinstallation&jstlay=stepone'); ?>">
                                     <img alt="image" src="<?php echo jssupportticket::$_pluginpath.'includes/images/postinstallation/back-arrow.png';?>">
                                    <?php echo __('Back','js-support-ticket'); ?>
                                </a>
                            </div>
                            <?php echo JSSTformfield::hidden('action', 'postinstallation_save'); ?>
                            <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                            <?php echo JSSTformfield::hidden('step', 2); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
