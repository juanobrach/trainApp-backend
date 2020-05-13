<?php JSSTmessage::getMessage(); ?>
<div id="jsstadmin-wrapper" class="jsstadmin-add-on-page-wrapper">
    <div id="jsstadmin-leftmenu">
        <?php  JSSTincluder::getClassesInclude('jsstadminsidemenu'); ?>
    </div>
    <div id="jsstadmin-data">
        <div id="jsstadmin-wrapper-top">
            <div id="jsstadmin-wrapper-top-left">
                <div id="jsstadmin-breadcrunbs">
                    <ul>
                        <li><a href="?page=jssupportticket" title="<?php echo __('Dashboard','js-support-ticket'); ?>"><?php echo __('Dashboard','js-support-ticket'); ?></a></li>
                        <li><?php echo __('Addons List','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __("Addons List", 'js-support-ticket') ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <div class="jsstadmin-add-on-page-wrp">
                <div class="add-on-banner">
                    <img class="add-on-banner-left-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/left-image.png" alt="<?php echo __('left image','js-support-ticket'); ?>"/>
                    <img class="add-on-banner-center-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/logo.png" alt="<?php echo __('Logo','js-support-ticket'); ?> />
                    <img class="add-on-banner-right-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/right-image.png" alt="<?php echo __('right image','js-support-ticket'); ?>" />
                </div>
                <div class="add-on-page-cnt">
                    <div class="add-on-sec-header">
                        <h1 class="add-on-header-tit">Add-On’s For Help Desk</h1>
                        <div class="add-on-header-text">Get trusted WordPress add on’s. Guaranteed to work fast, safe to use, beautifully coded, packed with features and easy to use.</div>
                    </div>
                    <div class="add-on-msg">
                        <h3 class="add-on-msg-txt">Save big with an exclusive membership plan today!</h3>
                        <a title="<?php echo __('Show','js-support-ticket'); ?>" href="https://jshelpdesk.com/pricing/" class="add-on-msg-btn"><i class="fa fa-cart"></i> show bundle pack</a>
                    </div>
                    <div class="add-on-list">
                        <div class="add-on-item agent">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/agent.png" alt="<?php echo __('Agent','js-support-ticket'); ?>" />
                            <div class="add-on-name">Agents</div>

                            <div class="add-on-txt">Add agents and assign roles and permissions to provide assistance and support to customer support tickets.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/agents/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item close-tkt">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/ticket-auto-close.png" alt="<?php echo __('Ticket auto close','js-support-ticket'); ?>" />
                            <div class="add-on-name">Ticket Auto Close</div>

                            <div class="add-on-txt">Define rules for ticket to auto close. Ticket will be auto close after specific interval of time which can be set by admin.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/close-ticket/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item feedback">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/feedback.png" alt="<?php echo __('Feedback','js-support-ticket'); ?>" />
                            <div class="add-on-name">Feedback</div>

                            <div class="add-on-txt">Get the survey from your customers on ticket closing to improve your quality of services and assistance.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/feedback/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item help-topic">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/helptopic.png" alt="<?php echo __('helptopic','js-support-ticket'); ?>" />
                            <div class="add-on-name">Helptopic</div>

                            <div class="add-on-txt">Help topics help users to find and select the area with which they need assistance.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/helptopic/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item private-note">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/private-note.png" alt="<?php echo __('private note','js-support-ticket'); ?>" />
                            <div class="add-on-name">Private Note</div>

                            <div class="add-on-txt">The private note is used as reminders or to give other agents insights into the ticket issue. User Won't see the private notes.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/internal-note/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item kb">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/kb.png" alt="<?php echo __('Knowledgebase','js-support-ticket'); ?>" />
                            <div class="add-on-name">Knowledge Base</div>

                            <div class="add-on-txt">Stop losing productivity on repetitive queries,Build your knowledge base, group solutions by topics to facilitate users.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/knowledge-base/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item max-tkt">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/max-ticket.png" alt="<?php echo __('max ticket','js-support-ticket'); ?>" />
                            <div class="add-on-name">Max Tickets</div>

                            <div class="add-on-txt">Enables admin to set N numbers of tickets for users to create and set N numbers of Ticket to open for agents separately.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/max-ticket/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item merge-tkt">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/merge-tickets.png" alt="<?php echo __('merge tickets','js-support-ticket'); ?>"/>
                            <div class="add-on-name">Merge Tickets</div>

                            <div class="add-on-txt">Enables agents to merge two tickets of the same user into one instead of dealing with the same issue on many tickets.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/merge-ticket/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item overdue-tkt">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/ticket-overdue.png" alt="<?php echo __('Ticket Overdue','js-support-ticket'); ?>" />
                            <div class="add-on-name">Ticket Overdue</div>

                            <div class="add-on-txt">Defines rules or set specific intervals of time to make ticket auto overdue.The ticket can overdue by type or overdue by Cronjob.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/overdue/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item smtp">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/smtp.png" alt="<?php echo __('SMTP','js-support-ticket'); ?>" />
                            <div class="add-on-name">SMTP</div>

                            <div class="add-on-txt">SMTP enables you to add custom mail protocol to send and receive emails within the js help desk.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/smtp/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item tkt-histry">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/ticket-history.png" alt="<?php echo __('Ticket History','js-support-ticket'); ?>" />
                            <div class="add-on-name">Ticket History</div>

                            <div class="add-on-txt">Displays complete ticket history along with the ticket status, currently assigned user and other actions performed on each ticket.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/ticket-history/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item canned-resp">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/canned-responses.png" alt="<?php echo __('Canned Responses','js-support-ticket'); ?>" />
                            <div class="add-on-name">Canned Responses</div>

                            <div class="add-on-txt">Canned Responses are pre-populated messages that allows support agents to respond quickly to customer issues.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/canned-responses/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item email-piping">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/email-piping.png" alt="<?php echo __('Email Piping','js-support-ticket'); ?>" />
                            <div class="add-on-name">Email Piping</div>

                            <div class="add-on-txt">Enables users to reply to the tickets via email without the need to login to the support system first.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/email-piping/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item time-tracking">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/time-tracking.png" alt="<?php echo __('time tracking','js-support-ticket'); ?>" />
                            <div class="add-on-name">Time Tracking</div>

                            <div class="add-on-txt">Track the time spent on each ticket by each agent and each reply. Report the admin on how much time is spent on each ticket.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/time-tracking/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item user-opt">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/user-options.png" alt="<?php echo __('user options','js-support-ticket'); ?>" />
                            <div class="add-on-name">User Options</div>

                            <div class="add-on-txt">User options enable you to add Google Re-captcha or JS Help Desk Re-captcha for a registration form.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/user-options/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item tkt-actions">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/ticket-actions.png" alt="<?php echo __('ticket actions','js-support-ticket'); ?>" />
                            <div class="add-on-name">Ticket Actions</div>

                            <div class="add-on-txt">Get multiple action options on each ticket like Print Ticket, Lock Ticket, Transfer ticket, etc.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/actions/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item announcements">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/announcments.png" alt="<?php echo __('Announcements','js-support-ticket'); ?>" />
                            <div class="add-on-name">Announcements</div>

                            <div class="add-on-txt">Make unlimited announcements associated with support system to get customer interaction.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/announcements/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item ban-email">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/ban-email.png" alt="<?php echo __('Ban Email','js-support-ticket'); ?>" />
                            <div class="add-on-name">Ban Email</div>

                            <div class="add-on-txt">Ban Email allows you to block email of any user to restrict him to create new tickets.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/ban-email/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item desk-notif">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/desktop-notifications.png" alt="<?php echo __('desktop notifications','js-support-ticket'); ?>" />
                            <div class="add-on-name">Descktop Notifications</div>

                            <div class="add-on-txt">The Desktop notifications will keep you up to date about anything happens on your support system.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/desktop-notification/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item export">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/export.png" alt="<?php echo __('Export','js-support-ticket'); ?>" />
                            <div class="add-on-name">Export</div>

                            <div class="add-on-txt">Save the ticket as a PDF in your system or the admin will also be able to export all the data inside of Ticket.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/export/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item downloads">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/downloads.png" alt="<?php echo __('Downloads','js-support-ticket'); ?>"/>
                            <div class="add-on-name">Downloads</div>

                            <div class="add-on-txt">Create downloads to ensure the user to get downloads from downloads.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/downloads/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item faq">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/faq.png" alt="<?php echo __('FAQ','js-support-ticket'); ?>" />
                            <div class="add-on-name">FAQ</div>

                            <div class="add-on-txt">Tired of getting tickets about the same problems? Add FAQs to drastically reduce the number of common questions from users.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/faq/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item themes">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/themes.png" alt="<?php echo __('Themes','js-support-ticket'); ?>" />
                            <div class="add-on-name">Themes</div>

                            <div class="add-on-txt">Get multiple themes with beautiful colors scheme to make your site more beautiful and eye catchy.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/themes/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item admin-widg">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/admin-widgets.png" alt="<?php echo __('admin widgets','js-support-ticket'); ?>" />
                            <div class="add-on-name">Admin Widgets</div>

                            <div class="add-on-txt">Get immediate data of your support operations as soon as you log into your WordPress administration area.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/admin-widget/" class="add-on-btn">buy now</a>
                        </div>
                        <div class="add-on-item internal-mail">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/internal-mail.png" alt="<?php echo __('internal mail','js-support-ticket'); ?>" />
                            <div class="add-on-name">Internal Mail</div>

                            <div class="add-on-txt">Use internal email to send emails to one agent to another agent with in support ticket.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/internal-mail/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item fe-widget">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/frontend-widget.png" alt="<?php echo __('frontend widget','js-support-ticket'); ?>" />
                            <div class="add-on-name">Front-End Widget</div>

                            <div class="add-on-txt">Widgets in WordPress allow you to add content and features in the widgetized areas of your theme.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/widget/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item email-piping">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/privatecredentials.png" alt="<?php echo __('Private Credentials','js-support-ticket'); ?>" />
                            <div class="add-on-name">Private Credentials</div>

                            <div class="add-on-txt">Widgets in WordPress allow you to add content and features in the widgetized areas of your theme.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/widget/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item help-topic">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/woocommerce.png" alt="<?php echo __('woocommerce support','js-support-ticket'); ?>" />
                            <div class="add-on-name">WooCommerce Support </div>

                            <div class="add-on-txt">Widgets in WordPress allow you to add content and features in the widgetized areas of your theme.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/widget/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item paid-support">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/paid-support.png" alt="<?php echo __('Paid Support','js-support-ticket'); ?>" />
                            <div class="add-on-name">Paid Support </div>

                            <div class="add-on-txt">Paid Support is the easiest way to integrate and manage payments for your support tickets.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/paid-support/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item envato">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/envato.png" alt="<?php echo __('envato','js-support-ticket'); ?>" />
                            <div class="add-on-name">Envato </div>

                            <div class="add-on-txt">Without valid Envato, license clients won't be able to open a new ticket.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/envato/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item mail-chimp">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/mail-chimp.png" alt="<?php echo __('mail chimp','js-support-ticket'); ?>" />
                            <div class="add-on-name">Mail Chimp </div>

                            <div class="add-on-txt">The Mail Chimp add-on adds a new checkbox to the registration form for prompting new users to subscribe your email-list.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/mail-chimp/" class="add-on-btn">buy now</a>
                        </div>

                        <div class="add-on-item easy-digi-dwnlds">
                            <img class="add-on-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/add-on-list/easy-digital-downloads.png" alt="<?php echo __('easy digital downloads','js-support-ticket'); ?>" />
                            <div class="add-on-name">Easy Digital Downloads </div>

                            <div class="add-on-txt">EDD offers customers to open new tickets just one click from their EDD account with optionally validating the license keys.</div>
                            <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/product/easy-digital-download/" class="add-on-btn">buy now</a>
                        </div>

                    </div>
                    <div class="add-on-sec-header">
                        <h1 class="add-on-header-tit">JS Help Desk Add-Ons Bundle Pack</h1>
                        <div class="add-on-header-text">Save big with an exclusive membership plan today!</div>
                    </div>
                    <div class="add-on-bundle-pack-list">
                        <div class="add-on-bundle-pack-item basic">
                            <div class="add-on-bundle-pack-name">Basic</div>
                            <div class="add-on-bundle-pack-price">$69<span>/ year</span></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li>Ticket Actions</li>
                                <li>Agents</li>
                                <li>Ticket Auto Close</li>
                                <li>FAQ</li>
                                <li>Helptopic</li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/pricing/">buy now</a>
                            </div>
                        </div>
                        <div class="add-on-bundle-pack-item standard">
                            <div class="add-on-bundle-pack-name">Standard</div>
                            <div class="add-on-bundle-pack-price">$99<span>/ year</span></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li>Export</li>
                                <li>Announcements</li>
                                <li>Internal Mail</li>
                                <li>Private Note</li>
                                <li>Canned Response</li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/pricing/">buy now</a>
                            </div>
                        </div>
                        <div class="add-on-bundle-pack-item professional">
                            <div class="add-on-bundle-pack-name">Professional</div>
                            <div class="add-on-bundle-pack-price">$149<span>/ year</span></div>
                            <ul class="add-on-bundle-pack-feat">
                                <li>Feedback</li>
                                <li>Knowledge Base</li>
                                <li>Merge Tickets</li>
                                <li>Email Piping</li>
                                <li>Time Tracking</li>
                            </ul>
                            <div class="add-on-bundle-pack-btn">
                                <a title="<?php echo __('buy now','js-support-ticket'); ?>" href="https://jshelpdesk.com/pricing/">buy now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
