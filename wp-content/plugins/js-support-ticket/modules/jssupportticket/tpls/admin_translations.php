<?php
   if(!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jssupportticketadmin-wrapper">
    <?php JSSTmessage::getMessage(); ?>

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
                        <li><?php echo __('Translations','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Translations', 'js-support-ticket'); ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <div id="black_wrapper_translation"></div>
            <div id="jstran_loading">
                <img alt="<?php echo __('spinning wheel','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/spinning-wheel.gif" />
            </div>

            <div id="js-language-wrapper">
                <div class="jstopheading"><?php echo __('Get JS Help Desk Translations','js-support-ticket'); ?></div>
                <div id="gettranslation" class="gettranslation"><img alt="<?php echo __('Download','js-support-ticket'); ?>" style="width:18px; height:auto;" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/download-icon.png" /><?php echo __('Get Translations','js-support-ticket'); ?></div>
                <div id="js_ddl">
                    <span class="title"><?php echo __('Select Translation','js-support-ticket'); ?>:</span>
                    <span class="combo" id="js_combo"></span>
                    <span class="button" id="jsdownloadbutton"><img alt="<?php echo __('Download','js-support-ticket'); ?>" style="width:14px; height:auto;" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/download-icon.png" /><?php echo __('Download','js-support-ticket'); ?></span>
                    <div id="jscodeinputbox" class="js-some-disc"></div>
                    <div class="js-some-disc"><img alt="<?php echo __('info','js-support-ticket'); ?>" style="width:18px; height:auto;" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/info-icon.png" /><?php echo __('When WordPress language change to ro, JS Help Desk language will auto change to ro'); ?></div>
                </div>
                <div id="js-emessage-wrapper">
                    <img alt="<?php echo __('c error','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/c_error.png" />
                    <div id="jslang_em_text"></div>
                </div>
                <div id="js-emessage-wrapper_ok">
                    <img alt="<?php echo __('saved','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/saved.png" />
                    <div id="jslang_em_text_ok"></div>
                </div>
            </div>
            <div id="js-lang-toserver">
                <div class="col"><a class="anc one" href="https://www.transifex.com/joom-sky/js-support-ticket" target="_blank" title="<?php echo __('Contribute In Translation','js-support-ticket'); ?>"><img alt="<?php echo __('translate','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/translation-icon.png" /><?php echo __('Contribute In Translation','js-support-ticket'); ?></a></div>
                <div class="col"><a class="anc two" href="http://www.joomsky.com/translations.html" target="_blank" title="<?php echo __('Manual Download','js-support-ticket'); ?>"><img alt="<?php echo __('Manual Download','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/manual-download.png" /><?php echo __('Manual Download','js-support-ticket'); ?></a></div>
            </div>
        </div>
</div>

<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
    jQuery(document).ready(function(){
        jQuery('#gettranslation').click(function(){
            jsShowLoading();
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'jssupportticket', task: 'getListTranslations'}, function (data) {
                if (data) {
                    console.log(data);
                    jsHideLoading();
                    data = JSON.parse(data);
                    if(data['error']){
                        jQuery('#js-emessage-wrapper div').html(data['error']);
                        jQuery('#js-emessage-wrapper').show();
                    }else{
                        jQuery('#js-emessage-wrapper').hide();
                        jQuery('#gettranslation').hide();
                        jQuery('div#js_ddl').show();
                        jQuery('span#js_combo').html(data['data']);
                    }
                }
            });
        });

        jQuery(document).on('change', 'select#translations' ,function() {
            var lang_name = jQuery( this ).val();
            if(lang_name != ''){
                jQuery('#js-emessage-wrapper_ok').hide();
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'jssupportticket', task: 'validateandshowdownloadfilename',langname:lang_name}, function (data) {
                    console.log(data);
                    if (data) {
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#js-emessage-wrapper div').html(data['error']);
                            jQuery('#js-emessage-wrapper').show();
                            jQuery('#jscodeinputbox').slideUp('400' , 'swing' , function(){
                                jQuery('input#languagecode').val("");
                            });
                        }else{
                            jQuery('#js-emessage-wrapper').hide();
                            jQuery('#jscodeinputbox').html(data['path']+': '+data['input']);
                            jQuery('#jscodeinputbox').slideDown();
                        }
                    }
                });
            }
        });

        jQuery('#jsdownloadbutton').click(function(){
            jQuery('#js-emessage-wrapper_ok').hide();
            var lang_name = jQuery('#translations').val();
            var file_name = jQuery('#languagecode').val();
            if(lang_name != '' && file_name != ''){
                jsShowLoading();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'jssupportticket', task: 'getlanguagetranslation',langname:lang_name , filename: file_name}, function (data) {
                    if (data) {
                        console.log(data);
                        jsHideLoading();
                        data = JSON.parse(data);
                        if(data['error']){
                            jQuery('#js-emessage-wrapper div').html(data['error']);
                            jQuery('#js-emessage-wrapper').show();
                        }else{
                            jQuery('#js-emessage-wrapper').hide();
                            jQuery('#js-emessage-wrapper_ok div').html(data['data']);
                            jQuery('#js-emessage-wrapper_ok').slideDown();
                        }
                    }
                });
            }
        });
    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }

    function jsHideLoading(){
        jQuery('div#black_wrapper_translation').hide();
        jQuery('div#jstran_loading').hide();
    }
</script>
</div>
</div>
