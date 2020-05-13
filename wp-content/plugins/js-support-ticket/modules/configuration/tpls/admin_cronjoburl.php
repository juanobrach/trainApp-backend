<?php
JSSTmessage::getMessage();
wp_enqueue_script('jquery-ui-tabs');
?>
<script>
jQuery(document).ready(function ($) {
    jQuery(".tabs").tabs();
});
</script>
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
                        <li><?php echo __('Cron Job URLs','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Cron Job URLs', 'js-support-ticket') ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="">
            <!-- ticket via email cron -->
            <div id="cp_wraper">
                <?php $array = array('even', 'odd');
                $k = 0; ?>
                <div id="tabs" class="tabs">
                    <ul>
                        <li><a title="<?php echo __('Web Cron Job','js-support-ticket'); ?>" class="selected" data-css="controlpanel" href="#webcrown"><?php echo __('Web Cron Job','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('Wget','js-support-ticket'); ?>"  data-css="controlpanel" href="#wget"><?php echo __('Wget','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('Curl','js-support-ticket'); ?>"  data-css="controlpanel" href="#curl"><?php echo __('Curl','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('PHP Script','js-support-ticket'); ?>"  data-css="controlpanel" href="#phpscript"><?php echo __('PHP Script','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('URL','js-support-ticket'); ?>"  data-css="controlpanel" href="#url"><?php echo __('URL','js-support-ticket'); ?></a></li>
                    </ul>
                    <div class="tabInner">
                    <div id="webcrown">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('Configuration of a backup job with webcron org','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo __('Name of cron job','js-support-ticket'); ?>
                                </span>
                                <span class="crown_text_right"><?php echo __('Ticket via email','js-support-ticket'); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo __('Timeout','js-support-ticket'); ?>
                                </span>
                                <span class="crown_text_right"><?php echo __('180 secs if the does not completely increase it most sites will work with a setting of 180 600','js-support-ticket'); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('URL you want to execute','js-support-ticket'); ?></span>
                                <span class="crown_text_right">
                                    <?php echo jssupportticket::makeUrl(array('jsstcron'=>'ticketviaemail','jsstpageid'=>jssupportticket::getPageid())); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('Login','js-support-ticket'); ?></span>
                                <span class="crown_text_right">
                                    <?php echo __('Leave this blank','js-support-ticket'); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('Password','js-support-ticket'); ?></span>
                                <span class="crown_text_right"><?php echo __('Leave this blank','js-support-ticket'); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo __('Execution time','js-support-ticket'); ?>
                                </span>
                                <span class="crown_text_right">
                                    <?php echo __('That the grid below the other options select when and how','js-support-ticket'); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('Alerts','js-support-ticket'); ?></span>
                                <span class="crown_text_right">
                                <?php echo __('If you have already set up alerts methods in webcron org interface we recommend choosing an alert','js-support-ticket'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="wget">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('Cron scheduling using wget','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo 'wget --max-redirect=10000 "' . jssupportticket::makeUrl(array('jsstcron'=>'ticketviaemail','jsstpageid'=>jssupportticket::getPageid())) .'" -O - 1>/dev/null 2>/dev/null '; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="curl">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('Cron scheduling using Curl','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo 'curl "' . jssupportticket::makeUrl(array('jsstcron'=>'ticketviaemail','jsstpageid'=>jssupportticket::getPageid())).'"<br>' . __('OR','js-support-ticket') . '<br>'; ?>
                                <?php echo 'curl -L --max-redirs 1000 -v "' . jssupportticket::makeUrl(array('jsstcron'=>'ticketviaemail','jsstpageid'=>jssupportticket::getPageid())).'" 1>/dev/null 2>/dev/null '; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="phpscript">
                        <div id="cron_job">
                            <span class="crown_text">
                                    <?php echo __('Custom PHP script to run the cron job','js-support-ticket'); ?>
                            </span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                    <?php
                                    echo '  $curl_handle=curl_init();<br>
                                                curl_setopt($curl_handle, CURLOPT_URL, \'' . jssupportticket::makeUrl(array('jsstcron'=>'ticketviaemail','jsstpageid'=>jssupportticket::getPageid())).'\');<br>
                                                curl_setopt($curl_handle,CURLOPT_FOLLOWLOCATION, TRUE);<br>
                                                curl_setopt($curl_handle,CURLOPT_MAXREDIRS, 10000);<br>
                                                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);<br>
                                                $buffer = curl_exec($curl_handle);<br>
                                                curl_close($curl_handle);<br>
                                                if (empty($buffer))<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo "' . __('Sorry the cron job didnot work','js-support-ticket') . '";<br>
                                                else<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo $buffer;<br>
                                                ';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="url">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('URL for use with your won scripts and third party','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth"><?php echo jssupportticket::makeUrl(array('jsstcron'=>'ticketviaemail','jsstpageid'=>jssupportticket::getPageid())); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="cron_job">
                        <span class="cron_job_help_txt"><?php echo __('Recommended run script hourly','js-support-ticket'); ?></span>
                    </div>
                    </div>
                </div>
            </div>
            <!-- update ticket status cron -->
            <div id="cp_wraper">
                <?php $array = array('even', 'odd');
                $k = 0; ?>
                <div id="tabs" class="tabs">
                    <ul>
                        <li><a title="<?php echo __('Web Cron Job','js-support-ticket'); ?>" class="selected" data-css="controlpanel" href="#webcrown"><?php echo __('Web Cron Job','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('Wget','js-support-ticket'); ?>"  data-css="controlpanel" href="#wget"><?php echo __('Wget','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('Curl','js-support-ticket'); ?>"  data-css="controlpanel" href="#curl"><?php echo __('Curl','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('PHP Script','js-support-ticket'); ?>"  data-css="controlpanel" href="#phpscript"><?php echo __('PHP Script','js-support-ticket'); ?></a></li>
                        <li><a title="<?php echo __('URL','js-support-ticket'); ?>"  data-css="controlpanel" href="#url"><?php echo __('URL','js-support-ticket'); ?></a></li>
                    </ul>
                    <div class="tabInner">
                    <div id="webcrown">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('Configuration of a backup job with webcron org','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo __('Name of cron job','js-support-ticket'); ?>
                                </span>
                                <span class="crown_text_right"><?php echo __('Update ticket status','js-support-ticket'); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo __('Timeout','js-support-ticket'); ?>
                                </span>
                                <span class="crown_text_right"><?php echo __('180 secs if the does not completely increase it most sites will work with a setting of 180 600','js-support-ticket'); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('URL you want to execute','js-support-ticket'); ?></span>
                                <span class="crown_text_right">
                                    <?php echo jssupportticket::makeUrl(array('jsstcron'=>'updateticketstatus','jsstpageid'=>jssupportticket::getPageid())); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('Login','js-support-ticket'); ?></span>
                                <span class="crown_text_right">
                                    <?php echo __('Leave this blank','js-support-ticket'); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('Password','js-support-ticket'); ?></span>
                                <span class="crown_text_right"><?php echo __('Leave this blank','js-support-ticket'); ?></span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left">
                                    <?php echo __('Execution time','js-support-ticket'); ?>
                                </span>
                                <span class="crown_text_right">
                                    <?php echo __('That the grid below the other options select when and how','js-support-ticket'); ?>
                                </span>
                            </div>
                            <div id="cron_job_detail_wrapper" class="<?php echo $array[$k];$k = 1 - $k; ?>">
                                <span class="crown_text_left"><?php echo __('Alerts','js-support-ticket'); ?></span>
                                <span class="crown_text_right">
                                <?php echo __('If you have already set up alerts methods in webcron org interface we recommend choosing an alert','js-support-ticket'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="wget">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('Cron scheduling using wget','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo 'wget --max-redirect=10000 "' . jssupportticket::makeUrl(array('jsstcron'=>'updateticketstatus','jsstpageid'=>jssupportticket::getPageid())) .'" -O - 1>/dev/null 2>/dev/null '; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="curl">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('Cron scheduling using Curl','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                <?php echo 'curl "' . jssupportticket::makeUrl(array('jsstcron'=>'updateticketstatus','jsstpageid'=>jssupportticket::getPageid())).'"<br>' . __('OR','js-support-ticket') . '<br>'; ?>
                                <?php echo 'curl -L --max-redirs 1000 -v "' . jssupportticket::makeUrl(array('jsstcron'=>'updateticketstatus','jsstpageid'=>jssupportticket::getPageid())).'" 1>/dev/null 2>/dev/null '; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="phpscript">
                        <div id="cron_job">
                            <span class="crown_text">
                                    <?php echo __('Custom PHP script to run the cron job','js-support-ticket'); ?>
                            </span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth">
                                    <?php
                                    echo '  $curl_handle=curl_init();<br>
                                                curl_setopt($curl_handle, CURLOPT_URL, \'' . jssupportticket::makeUrl(array('jsstcron'=>'updateticketstatus','jsstpageid'=>jssupportticket::getPageid())).'\');<br>
                                                curl_setopt($curl_handle,CURLOPT_FOLLOWLOCATION, TRUE);<br>
                                                curl_setopt($curl_handle,CURLOPT_MAXREDIRS, 10000);<br>
                                                curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);<br>
                                                $buffer = curl_exec($curl_handle);<br>
                                                curl_close($curl_handle);<br>
                                                if (empty($buffer))<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo "' . __('Sorry the cron job didnot work','js-support-ticket') . '";<br>
                                                else<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;echo $buffer;<br>
                                                ';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="url">
                        <div id="cron_job">
                            <span class="crown_text"><?php echo __('URL for use with your won scripts and third party','js-support-ticket'); ?></span>
                            <div id="cron_job_detail_wrapper" class="even">
                                <span class="crown_text_right fullwidth"><?php echo jssupportticket::makeUrl(array('jsstcron'=>'updateticketstatus','jsstpageid'=>jssupportticket::getPageid())); ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="cron_job">
                        <span class="cron_job_help_txt"><?php echo __('Recommended run script daily','js-support-ticket'); ?></span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
