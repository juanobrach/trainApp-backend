<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
        jQuery('#termsandconditions_linktype').on('change', function() {
            if(this.value == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else{
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }
        });
        <?php if(isset(jssupportticket::$_data[0]['userfield']->id)){ ?>
            var intial_val = jQuery('#termsandconditions_linktype').val();
            if(intial_val == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else{
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }
        <?php } ?>
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
                        <li><?php echo __('Add GDPR Field','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text">
                <?php echo __('Add GDPR Field', 'js-support-ticket'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form class="jsstadmin-form" method="post" action="<?php echo admin_url("admin.php?page=gdpr&task=savegdprfield"); ?>">
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Field Title', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('fieldtitle', isset(jssupportticket::$_data[0]['userfield']->fieldtitle) ? jssupportticket::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox js-form-input-field', 'data-validation' => 'required')) ?></div>
                </div>
                <?php
                $termsandconditions_text = '';
                $termsandconditions_linktype = '';
                $termsandconditions_link = '';
                $termsandconditions_page = '';
                if( isset(jssupportticket::$_data[0]['userfieldparams']) && jssupportticket::$_data[0]['userfieldparams'] != '' && is_array(jssupportticket::$_data[0]['userfieldparams']) && !empty(jssupportticket::$_data[0]['userfieldparams'])){
                    $termsandconditions_text = isset(jssupportticket::$_data[0]['userfieldparams']['termsandconditions_text']) ? jssupportticket::$_data[0]['userfieldparams']['termsandconditions_text'] :'' ;
                    $termsandconditions_linktype = isset(jssupportticket::$_data[0]['userfieldparams']['termsandconditions_linktype']) ? jssupportticket::$_data[0]['userfieldparams']['termsandconditions_linktype'] :'' ;
                    $termsandconditions_link = isset(jssupportticket::$_data[0]['userfieldparams']['termsandconditions_link']) ? jssupportticket::$_data[0]['userfieldparams']['termsandconditions_link'] :'' ;
                    $termsandconditions_page = isset(jssupportticket::$_data[0]['userfieldparams']['termsandconditions_page']) ? jssupportticket::$_data[0]['userfieldparams']['termsandconditions_page'] :'' ;
                } ?>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Field Text', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('termsandconditions_text', $termsandconditions_text, array('class' => 'inputbox js-form-input-field', 'data-validation' => 'required')) ?></div>
                    <div class="js-form-desc">
                        e.g "  I have read and agree to the [link] Terms and Conditions[/link].  " The text between [link] and [/link] will be linked to provided url or wordpress page.
                    </div>
                </div>
                <?php
                $yesno = array(
                    (object) array('id' => 1, 'text' => __('Yes', 'js-support-ticket')),
                    (object) array('id' => 0, 'text' => __('No', 'js-support-ticket')));
                /*
                ?>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Required', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span> </div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('required', $yesno, isset(jssupportticket::$_data[0]['userfield']->required) ? jssupportticket::$_data[0]['userfield']->required : '', __('Select Required', 'js-support-ticket'), array('class' => 'inputbox js-form-select-field')); ?></div>
                </div>
                <?php
                */
                $linktype = array(
                    (object) array('id' => 1, 'text' => __('Direct Link', 'js-support-ticket')),
                    (object) array('id' => 2, 'text' => __('Wordpress Page', 'js-support-ticket')));
                ?>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Link Type', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span> </div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('termsandconditions_linktype', $linktype, $termsandconditions_linktype, __('Select Link Type', 'js-support-ticket'), array('class' => 'inputbox js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-linktype2" style="display: none;">
                    <div class="js-form-title"><?php echo __('Link Page', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span> </div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('termsandconditions_page', JSSTincluder::getJSModel('configuration')->getPageList(), $termsandconditions_page, __('Select Page', 'js-support-ticket'), array('class' => 'inputbox js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-linktype1" style="display: none;">
                    <div class="js-form-title"><?php echo __('URL', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('termsandconditions_link', $termsandconditions_link, array('class' => 'inputbox js-form-input-field')) ?></div>
                </div>
                <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]['userfield']->id) ? jssupportticket::$_data[0]['userfield']->id : ''); ?>
                <?php echo JSSTformfield::hidden('created', isset(jssupportticket::$_data[0]['userfield']->created) ? jssupportticket::$_data[0]['userfield']->created : ''); ?>
                <?php echo JSSTformfield::hidden('ordering', isset(jssupportticket::$_data[0]['userfield']->ordering) ? jssupportticket::$_data[0]['userfield']->ordering : ''); ?>
                <?php echo JSSTformfield::hidden('userfieldtype', 'termsandconditions'); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <?php echo JSSTformfield::hidden('isuserfield', 1); ?>
                <?php echo JSSTformfield::hidden('fieldfor', 3); ?>
                <?php echo JSSTformfield::hidden('published', 1); ?>
                <?php echo JSSTformfield::hidden('required', 1); ?>
                <?php echo JSSTformfield::hidden('isvisitorpublished', 1); ?>
                <div class="js-form-button">
                    <?php echo JSSTformfield::submitbutton('save', __('Save', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                </div>
            </form>
        </div>
    </div>
</div>
