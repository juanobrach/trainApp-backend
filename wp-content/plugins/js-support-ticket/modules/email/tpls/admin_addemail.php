<?php
    $smtphost = array(
        (object) array('id' => '1', 'text' => __('Gmail', 'js-support-ticket')),
        (object) array('id' => '2', 'text' => __('Yahoo', 'js-support-ticket')),
        (object) array('id' => '3', 'text' => __('Hotmail', 'js-support-ticket')),
        (object) array('id' => '4', 'text' => __('Aol', 'js-support-ticket')),
        (object) array('id' => '5', 'text' => __('Other', 'js-support-ticket'))
    );
    $emailtype = array(
        (object) array('id' => '0', 'text' => __('Default', 'js-support-ticket')),
        (object) array('id' => '1', 'text' => __('SMTP', 'js-support-ticket'))
    );
    $truefalse = array(
        (object) array('id' => '0', 'text' => __('False', 'js-support-ticket')),
        (object) array('id' => '1', 'text' => __('True', 'js-support-ticket'))
    );
    $securesmtp = array(
        (object) array('id' => '1', 'text' => __('TLS', 'js-support-ticket')),
        (object) array('id' => '0', 'text' => __('SSL', 'js-support-ticket'))
    );
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
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
                        <li><?php echo __('Add Email','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Add Email', 'js-support-ticket'); ?></h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form class="jsstadmin-form" method="post" action="<?php echo admin_url("?page=email&task=saveemail"); ?>">
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Email', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-field"><?php echo JSSTformfield::text('email', isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->email : '', array('class' => 'inputbox js-form-input-field', 'data-validation' => 'required email')) ?></div>
                </div>
                <?php if(in_array('smtp', jssupportticket::$_active_addons)){ ?>
                    <div class="js-form-wrapper">
                        <div class="js-form-title"><?php echo __('Send Email By', 'js-support-ticket'); ?></div>
                        <div class="js-form-field"><?php echo JSSTformfield::select('smtpemailauth', $emailtype , isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->smtpemailauth : '' , __('Select Type', 'js-support-ticket') , array('class' => 'js-smtp-select js-form-select-field'))?></div>
                    </div>
                    <div id="smtpauthselect" style="display: none;">
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('SMTP host type', 'js-support-ticket'); ?></div>
                            <div class="js-form-field"><?php echo JSSTformfield::select('smtphosttype', $smtphost , isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->smtphosttype : '', __('Select Type', 'js-support-ticket') , array('class' => 'js-smtp-select js-form-select-field'))?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('SMTP host', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="js-form-field"><?php echo JSSTformfield::text('smtphost', isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->smtphost : '', array('class' => 'inputbox js-form-select-field')) ?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('SMTP Authentication', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="js-form-field"><?php echo JSSTformfield::select('smtpauthencation', $truefalse , isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->smtpauthencation : '' , __('Select Type', 'js-support-ticket') , array('class' => 'js-smtp-select js-form-select-field'))?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('Username', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="js-form-field"><?php echo JSSTformfield::text('name', isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->name : '', array('class' => 'inputbox js-form-input-field')) ?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('Password', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="js-form-field"><?php echo JSSTformfield::password('password', isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->password : '', array('class' => 'inputbox js-form-input-field')) ?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('SMTP Secure', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="js-form-field"><?php echo JSSTformfield::select('smtpsecure', $securesmtp , isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->smtpsecure : '' , __('Select Type', 'js-support-ticket') , array('class' => 'js-smtp-select js-form-select-field'))?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php echo __('SMTP Port', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                            <div class="js-form-field"><?php echo JSSTformfield::text('mailport', isset(jssupportticket::$_data[0]->email) ? jssupportticket::$_data[0]->mailport : '', array('class' => 'inputbox js-form-input-field')) ?></div>
                        </div>
                        <div class="js-col-md-12 js-col-md-offset-2 js-admin-ticketviaemail-wrapper-checksetting">
                            <a title="<?php echo __('Check Settings','js-support-ticket'); ?>" href="#" id="js-admin-ticketviaemail"><img alt="<?php echo __('check','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/tick_ticketviaemail.png" /><?php echo __('Check Settings','js-support-ticket'); ?></a>
                            <div id="js-admin-ticketviaemail-bar"></div>
                            <div class="js-col-md-12" id="js-admin-ticketviaemail-text"><?php echo __('If the system doesnot respond in 30 seconds','js-support-ticket').', '.__('it means system unable to connect email server','js-support-ticket'); ?></div>
                            <div class="js-col-md-12">
                               <div id="js-admin-ticketviaemail-msg"></div>
                           </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Auto Response', 'js-support-ticket'); ?></div>
                    <div class="js-form-field"><?php echo JSSTformfield::radiobutton('autoresponse', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->autoresponse) ? jssupportticket::$_data[0]->autoresponse : '1', array('class' => 'radiobutton js-form-radio-field')); ?></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Status', 'js-support-ticket'); ?></div>
                    <div class="js-form-field"><?php echo JSSTformfield::radiobutton('status', array('1' => __('Active', 'js-support-ticket'), '0' => __('Disabled', 'js-support-ticket')), isset(jssupportticket::$_data[0]->status) ? jssupportticket::$_data[0]->status : '1', array('class' => 'radiobutton js-form-radio-field')); ?></div>
                </div>
                <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]->id) ? jssupportticket::$_data[0]->id : '' ); ?>
                <?php echo JSSTformfield::hidden('created', isset(jssupportticket::$_data[0]->created) ? jssupportticket::$_data[0]->created : '' ); ?>
                <?php echo JSSTformfield::hidden('updated', isset(jssupportticket::$_data[0]->updated) ? jssupportticket::$_data[0]->updated : '' ); ?>
                <?php echo JSSTformfield::hidden('action', 'email_saveemail'); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <div class="js-form-button">
                    <?php echo JSSTformfield::submitbutton('save', __('Save Email', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        smtpAuthSelect();
        if(jQuery("#host").val() == "")
            smtphosttype(1);
        $("select#smtpemailauth").change(function(){
            smtpAuthSelect();
        });
        $("#smtphosttype").change(function(){
            smtphosttype(1);
        });

        function smtpAuthSelect(){
            if(jQuery("select#smtpemailauth").val() == 1){
                jQuery("div#smtpauthselect").show();
            }else{
                jQuery("div#smtpauthselect").hide();
            }
        }

        function smtphosttype(n){
            if(n==1 || jQuery("#host").val() == ""){
                if(jQuery("#smtphosttype").val() == 1){
                    jQuery("#host").val("smtp.gmail.com");
                }else if(jQuery("#smtphosttype").val() == 2){
                    jQuery("#host").val("smtp.mail.yahoo.com");
                }else if(jQuery("#smtphosttype").val() == 3){
                    jQuery("#host").val("smtp.live.com");
                }else if(jQuery("#smtphosttype").val() == 4){
                    jQuery("#host").val("smtp.aol.com");
                }else{
                    jQuery("#host").val("");
                }
            }
        }

        $("form").submit(function(e){
            if(jQuery("select#smtpemailauth").val() == 1){
                if($("#host").val() == "" || $("#name").val() == "" || $("#password").val() == "" || $("#smtpsecure").val() == "" || $("#port").val() == "" || $("#smtpauthencation").val() == ""){
                    e.preventDefault();
                    alert("Some values are not acceptable please retry");
                }
            }
            if(jQuery("select#smtpemailauth").val() == 0){
                $("#host").val("");
                $("#name").val("");
                $("#password").val("");
                $("#smtpsecure").val("");
                $("#port").val("");
                $("#smtpauthencation").val("");
            }
        });
        jQuery("a#js-admin-ticketviaemail").click(function(e){
            e.preventDefault();

                var hosttype = jQuery('select#smtphosttype').val();
                var hostname = jQuery('input#smtphost').val();
                if(hosttype == 4){
                    var hostname = jQuery('input#hostname').val();
                    if(hostname != ''){
                        var hostname = jQuery('input#hostname').val();
                    }else{
                        alert("<?php echo __('Please enter the hostname first','js-support-ticket'); ?>");
                        return;
                    }
                }
                var emailaddress = jQuery('input#name').val();
                var password = jQuery('input#password').val();
                var ssl = jQuery('select#smtpsecure').val();
                var hostportnumber = jQuery('input#mailport').val();
                var smtpauthencation_val = jQuery('select#smtpauthencation').val();
                jQuery("div#js-admin-ticketviaemail-bar").show();
                jQuery("div#js-admin-ticketviaemail-text").show();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', hosttype: hosttype,hostname:hostname, emailaddress: emailaddress,password:password,ssl:ssl,hostportnumber:hostportnumber, smtpauthencation:smtpauthencation_val , jstmod: 'email', task: 'sendTestEmail'}, function (data) {
                    if (data) {
                        jQuery("div#js-admin-ticketviaemail-bar").hide();
                        jQuery("div#js-admin-ticketviaemail-text").hide();
                        var obj = jQuery.parseJSON(data);
                        if(obj.type == 0){
                            jQuery("div#js-admin-ticketviaemail-msg").html(obj.text).addClass('no-error');
                        }else{
                            jQuery("div#js-admin-ticketviaemail-msg").html(obj.text).addClass('imap-error');
                        }
                        jQuery("div#js-admin-ticketviaemail-msg").show();
                    }
                });//jquery closed

        });
    });
</script>
