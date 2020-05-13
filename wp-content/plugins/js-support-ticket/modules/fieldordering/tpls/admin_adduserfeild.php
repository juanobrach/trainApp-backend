<script type="text/javascript">
     ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
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
                        <li><?php echo __('Add Field','js-support-ticket'); ?></li>
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
                <?php
                    $heading = isset(jssupportticket::$_data[0]['fieldvalues']) ? __('Edit', 'js-support-ticket') : __('Add', 'js-support-ticket');
                    echo $heading . '&nbsp' . __('Field', 'js-support-ticket');
                ?>
            </h1>
        </div>
        <?php
        $yesno = array(
            (object) array('id' => 1, 'text' => __('Yes', 'js-support-ticket')),
            (object) array('id' => 0, 'text' => __('No', 'js-support-ticket')));
        if(isset(jssupportticket::$_data[0]['userfield']->userfieldtype) && jssupportticket::$_data[0]['userfield']->userfieldtype != 'depandant_field'){
            $fieldtypes = array(
                (object) array('id' => 'text', 'text' => __('Text Field', 'js-support-ticket')),
                (object) array('id' => 'checkbox', 'text' => __('Check Box', 'js-support-ticket')),
                (object) array('id' => 'date', 'text' => __('Date', 'js-support-ticket')),
                (object) array('id' => 'combo', 'text' => __('Drop Down', 'js-support-ticket')),
                (object) array('id' => 'email', 'text' => __('Email Address', 'js-support-ticket')),
                (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-support-ticket')),
                (object) array('id' => 'radio', 'text' => __('Radio Button', 'js-support-ticket')),
                (object) array('id' => 'file', 'text' => __('Upload File', 'js-support-ticket')),
                (object) array('id' => 'multiple', 'text' => __('Multi Select', 'js-support-ticket')),
                (object) array('id' => 'termsandconditions', 'text' => __('Terms and Conditions', 'js-support-ticket')));
        }else{
            $fieldtypes = array(
                (object) array('id' => 'text', 'text' => __('Text Field', 'js-support-ticket')),
                (object) array('id' => 'checkbox', 'text' => __('Check Box', 'js-support-ticket')),
                (object) array('id' => 'date', 'text' => __('Date', 'js-support-ticket')),
                (object) array('id' => 'combo', 'text' => __('Drop Down', 'js-support-ticket')),
                (object) array('id' => 'email', 'text' => __('Email Address', 'js-support-ticket')),
                (object) array('id' => 'textarea', 'text' => __('Text Area', 'js-support-ticket')),
                (object) array('id' => 'radio', 'text' => __('Radio Button', 'js-support-ticket')),
                (object) array('id' => 'depandant_field', 'text' => __('Dependent Field', 'js-support-ticket')),
                (object) array('id' => 'file', 'text' => __('Upload File', 'js-support-ticket')),
                (object) array('id' => 'multiple', 'text' => __('Multi Select', 'js-support-ticket')),
                (object) array('id' => 'termsandconditions', 'text' => __('Terms and Conditions', 'js-support-ticket')));
        }
        $fieldsize = array(
             (object) array('id' => 50, 'text' => __('50%', 'js-support-ticket')),
            (object) array('id' => 100, 'text' => __('100%', 'js-support-ticket')));
        ?>
        <div id="jsstadmin-data-wrp">
            <form class="jsstadmin-form" id="adminForm" method="post" action="<?php echo admin_url("admin.php?page=fieldordering&task=saveuserfeild"); ?>">
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Field Type', 'js-support-ticket'); ?><font class="required-notifier">*</font></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('userfieldtype', $fieldtypes, isset(jssupportticket::$_data[0]['userfield']->userfieldtype) ? jssupportticket::$_data[0]['userfield']->userfieldtype : 'text', '', array('class' => 'inputbox one js-form-select-field', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);')); ?></div>
                </div>
                <?php /*
                        <div class="js-form-wrapper">
                            <div class="js-form-title"><?php // echo __('Field Name', 'js-support-ticket'); ?><font class="required-notifier">*</font></div>
                            <div class="js-form-value"><?php // echo JSSTformfield::text('field', isset(jssupportticket::$_data[0]['userfield']->field) ? jssupportticket::$_data[0]['userfield']->field : '', array('class' => 'inputbox one js-form-input-field', 'data-validation' => 'required', 'onchange' => 'prep4SQL(this);')); ?></div>
                        </div>
                */ ?>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Field Title', 'js-support-ticket'); ?><font class="required-notifier">*</font></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('fieldtitle', isset(jssupportticket::$_data[0]['userfield']->fieldtitle) ? jssupportticket::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox one js-form-input-field', 'data-validation' => 'required')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide" id="for-combo-wrapper" style="display:none;">
                    <div class="js-form-title"><?php echo __('Select','js-support-ticket') .'&nbsp;'. __('Parent Field', 'js-support-ticket'); ?><font class="required-notifier">*</font></div>
                    <div class="js-form-value" id="for-combo"></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('Show On Listing', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('showonlisting', $yesno, isset(jssupportticket::$_data[0]['userfield']->showonlisting) ? jssupportticket::$_data[0]['userfield']->showonlisting : 0, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('User Published', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('published', $yesno, isset(jssupportticket::$_data[0]['userfield']->published) ? jssupportticket::$_data[0]['userfield']->published : 1, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('Visitor Published', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('isvisitorpublished', $yesno, isset(jssupportticket::$_data[0]['userfield']->isvisitorpublished) ? jssupportticket::$_data[0]['userfield']->isvisitorpublished : 1, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('User Search', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('search_user', $yesno, isset(jssupportticket::$_data[0]['userfield']->search_user) ? jssupportticket::$_data[0]['userfield']->search_user : 1, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('Visitor Search', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('search_visitor', $yesno, isset(jssupportticket::$_data[0]['userfield']->search_visitor) ? jssupportticket::$_data[0]['userfield']->search_visitor : 1, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('Required', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('required', $yesno, isset(jssupportticket::$_data[0]['userfield']->required) ? jssupportticket::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <div class="js-form-wrapper for-terms-condtions-hide">
                    <div class="js-form-title"><?php echo __('Field Size', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::select('size', $fieldsize, isset(jssupportticket::$_data[0]['userfield']->size) ? jssupportticket::$_data[0]['userfield']->size : 0, '', array('class' => 'inputbox one js-form-select-field')); ?></div>
                </div>
                <?php /*
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Java Script', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::textarea('j_script', isset(jssupportticket::$_data[0]['userfield']->j_script) ? jssupportticket::$_data[0]['userfield']->j_script : '', array('class' => 'inputbox one jsstadmin-form-textarea-field')); ?></div>
                </div>
                */ ?>
                <div id="for-combo-options" >
                    <?php
                    $arraynames = '';
                    $comma = '';
                    if (isset(jssupportticket::$_data[0]['userfieldparams']) && jssupportticket::$_data[0]['userfield']->userfieldtype == 'depandant_field') {
                        foreach (jssupportticket::$_data[0]['userfieldparams'] as $key => $val) {
                            $textvar = $key;
                            $textvar = str_replace(' ','__',$textvar);
                            $textvar = str_replace('.','___',$textvar);
                            $divid = $textvar;
                            $textvar .='[]';
                            $arraynames .= $comma . "$key";
                            $comma = ',';
                            ?>
                            <div class="jsst-user-dd-field-wrap">
                                <div class="jsst-user-dd-field-title">
                                    <?php echo $key; ?>
                                </div>
                                <div class="jsst-user-dd-field-value combo-options-fields" id="<?php echo $divid; ?>">
                                    <?php
                                    if (!empty($val)) {
                                        foreach ($val as $each) {
                                            ?>
                                            <span class="input-field-wrapper">
                                                <input name="<?php echo $textvar; ?>" id="<?php echo $textvar; ?>" value="<?php echo $each; ?>" class="inputbox one user-field" type="text">
                                                <img alt="<?php echo __('Delete', 'js-support-ticket') ?>" class="input-field-remove-img" src="<?php echo jssupportticket::$_pluginpath ?>includes/images/delete.png">
                                            </span><?php
                                        }
                                    }
                                    ?>
                                    <input id="depandant-field-button" class="jsst-button-link button user-field-val-button" onclick="getNextField( &quot;<?php echo $divid; ?>&quot;, this );" value="Add More" type="button">
                                </div>
                            </div><?php
                        }
                    }
                    ?>
                </div>

                <div id="divText" class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Max Length', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('maxlength', isset(jssupportticket::$_data[0]['userfield']->maxlength) ? jssupportticket::$_data[0]['userfield']->maxlength : '', array('class' => 'inputbox one js-form-input-field')); ?></div>
                </div>
                <div class="js-form-wrapper divColsRows">
                    <div class="js-form-title"><?php echo __('Columns', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('cols', isset(jssupportticket::$_data[0]['userfield']->cols) ? jssupportticket::$_data[0]['userfield']->cols : '', array('class' => 'inputbox one js-form-input-field')); ?></div>
                </div>
                <div class="js-form-wrapper divColsRows">
                    <div class="js-form-title"><?php echo __('Rows', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('rows', isset(jssupportticket::$_data[0]['userfield']->rows) ? jssupportticket::$_data[0]['userfield']->rows : '', array('class' => 'inputbox one js-form-input-field')); ?></div>
                </div>
                <div id="divValues" class="jsstadmin-add-user-fields-wrp divColsRowsno-margin">
                    <h3 class="jsstadmin-add-user-fields-title"><?php echo __('Use the table below to add new values', 'js-support-ticket'); ?></h3>
                    <div class="page-actions no-margin">
                        <div id="user-field-values" class="white-background" class="no-padding">
                            <?php
                            if (isset(jssupportticket::$_data[0]['userfield']) && jssupportticket::$_data[0]['userfield']->userfieldtype != 'depandant_field') {
                                if (isset(jssupportticket::$_data[0]['userfieldparams']) && !empty(jssupportticket::$_data[0]['userfieldparams'])) {
                                    foreach (jssupportticket::$_data[0]['userfieldparams'] as $key => $val) {
                                        ?>
                                        <span class="input-field-wrapper">
                                            <?php echo JSSTformfield::text('values[]', isset($val) ? $val : '', array('class' => 'inputbox one user-field')); ?>
                                            <img alt="<?php echo __('Delete', 'js-support-ticket') ?>" class="input-field-remove-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" />
                                        </span>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <span class="input-field-wrapper">
                                    <?php echo JSSTformfield::text('values[]', isset($val) ? $val : '', array('class' => 'inputbox one user-field')); ?>
                                        <img alt="<?php echo __('Delete', 'js-support-ticket') ?>" class="input-field-remove-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" />
                                    </span>
                                <?php
                                }
                            }
                            ?>
                            <a title="<?php echo __('Add Value', 'js-support-ticket') ?>" class="jsst-button-link button user-field-val-button" id="user-field-val-button" onclick="insertNewRow();"><?php echo __('Add Value', 'js-support-ticket') ?></a>
                        </div>
                    </div>
                </div>
                <div class="for-terms-condtions-show" >
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
                    <div class="js-form-wrapper ">
                        <div class="js-form-title"><?php echo __('Terms and Conditions Text', 'js-support-ticket'); ?></div>
                        <div class="js-form-value"><?php echo JSSTformfield::text('termsandconditions_text', $termsandconditions_text , array('class' => 'inputbox one js-form-input-field')); ?></div>
                        <div class="js-form-desc">
                            e.g "  I have read and agree to the [link] Terms and Conditions[/link].  " The text between [link] and [/link] will be linked to provided url or wordpress page.
                        </div>
                    </div>
                    <div class="js-form-wrapper ">
                        <div class="js-form-title"><?php echo __('Terms and Conditions Link Type', 'js-support-ticket'); ?></div>
                        <?php
                        $linktype = array(
                            (object) array('id' => 1, 'text' => __('Direct Link', 'js-support-ticket')),
                            (object) array('id' => 2, 'text' => __('Wordpress Page', 'js-support-ticket')));
                        ?>
                        <div class="js-form-value"><?php echo JSSTformfield::select('termsandconditions_linktype', $linktype, $termsandconditions_linktype, __('Select Link Type', 'js-support-ticket'), array('class' => 'inputbox one js-form-select-field')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-linktype1" style="display: none;">
                        <div class="js-form-title"><?php echo __('Terms and Conditions Link', 'js-support-ticket'); ?></div>
                        <div class="js-form-value"><?php echo JSSTformfield::text('termsandconditions_link', $termsandconditions_link , array('class' => 'inputbox one js-form-input-field')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-linktype2" style="display: none;">
                        <div class="js-form-title"><?php echo __('Terms and Conditions Page', 'js-support-ticket'); ?></div>
                        <div class="js-form-value"><?php echo JSSTformfield::select('termsandconditions_page', JSSTincluder::getJSModel('configuration')->getPageList(), $termsandconditions_page, __('Select Wordpress page','js-support-ticket'), array('class' => 'inputbox one js-form-select-field')); ?></div>
                    </div>
                </div>
                <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]['userfield']->id) ? jssupportticket::$_data[0]['userfield']->id : ''); ?>
                <?php echo JSSTformfield::hidden('fieldfor', jssupportticket::$_data['fieldfor']); ?>
                <?php echo JSSTformfield::hidden('ordering', isset(jssupportticket::$_data[0]['userfield']->ordering) ? jssupportticket::$_data[0]['userfield']->ordering : '' ); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <?php echo JSSTformfield::hidden('isuserfield', 1); ?>
                <?php echo JSSTformfield::hidden('fieldname', isset(jssupportticket::$_data[0]['userfield']->field) ? jssupportticket::$_data[0]['userfield']->field : '' ); ?>
                <?php echo JSSTformfield::hidden('depandant_field', isset(jssupportticket::$_data[0]['userfield']->depandant_field) ? jssupportticket::$_data[0]['userfield']->depandant_field : '' ); ?>
                <?php echo JSSTformfield::hidden('field', isset(jssupportticket::$_data[0]['userfield']->field) ? jssupportticket::$_data[0]['userfield']->field : '' ); ?>
                <?php echo JSSTformfield::hidden('arraynames2', $arraynames); ?>
                <div class="js-form-button">
                    <?php echo JSSTformfield::submitbutton('save', __('Save Field', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                toggleType(jQuery('select#userfieldtype').val());
                jQuery('#termsandconditions_linktype').on('change', function() {
                    if(this.value == 1){
                        jQuery('.for-terms-condtions-linktype1').slideDown();
                        jQuery('.for-terms-condtions-linktype2').hide();
                    }else{
                        jQuery('.for-terms-condtions-linktype1').hide();
                        jQuery('.for-terms-condtions-linktype2').slideDown();
                    }
                });

                var intial_val = jQuery('#termsandconditions_linktype').val();
                if(intial_val == 1){
                    jQuery('.for-terms-condtions-linktype1').slideDown();
                    jQuery('.for-terms-condtions-linktype2').hide();
                }else{
                    jQuery('.for-terms-condtions-linktype1').hide();
                    jQuery('.for-terms-condtions-linktype2').slideDown();
                }
            });
            function disableAll() {
                jQuery("#divValues").slideUp();
                jQuery(".divColsRows").slideUp();
                jQuery("#divText").slideUp();
            }
            function toggleType(type) {
                disableAll();
                //prep4SQL(document.forms['adminForm'].elements['field']);
                selType(type);
            }
            function prep4SQL(field) {
                if (field.value != '') {
                    field.value = field.value.replace('js_', '');
                    field.value = 'js_' + field.value.replace(/[^a-zA-Z]+/g, '');
                }
            }
            function selType(sType) {
                var elem;
                /*
                 text
                 checkbox
                 date
                 combo
                 email
                 textarea
                 radio
                 editor
                 depandant_field
                 multiple*/

                switch (sType) {
                    case 'editor':
                        jQuery("div.for-terms-condtions-hide").show();
                        jQuery("#divText").slideUp();
                        jQuery("#divValues").slideUp();
                        jQuery(".divColsRows").slideUp();
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div.for-terms-condtions-show").slideUp();
                        break;
                    case 'textarea':
                        jQuery("div.for-terms-condtions-hide").show();
                        jQuery("#divText").slideUp();
                        jQuery(".divColsRows").slideDown();
                        jQuery("#divValues").slideUp();
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div.for-terms-condtions-show").slideUp();
                        break;
                    case 'email':
                    case 'password':
                    case 'text':
                    case 'file':
                        jQuery("div.for-terms-condtions-hide").show();
                        jQuery("#divText").slideDown();
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div.for-terms-condtions-show").slideUp();
                        break;
                    case 'termsandconditions':
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("#divText").slideUp();
                        jQuery(".divColsRows").slideUp();
                        jQuery("#divValues").slideUp();
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div.for-terms-condtions-hide").hide();
                        jQuery("div.for-terms-condtions-show").slideDown();
                        break;
                    case 'combo':
                    case 'multiple':
                        jQuery("div.for-terms-condtions-hide").show();
                        jQuery("#divValues").slideDown();
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div.for-terms-condtions-show").slideUp();
                        break;
                    case 'depandant_field':
                        jQuery("div.for-terms-condtions-hide").show();
                        comboOfFields();
                        jQuery("div.for-terms-condtions-show").slideUp();
                        break;
                    case 'radio':
                    case 'checkbox':
                        jQuery("div.for-terms-condtions-hide").show();
                        //jQuery(".divColsRows").slideDown();
                        jQuery("#divValues").slideDown();
                        jQuery("div#for-combo-wrapper").hide();
                        jQuery("div#for-combo-options").hide();
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div.for-terms-condtions-show").slideUp();
                        /*
                         if (elem=getObject('jsNames[0]')) {
                         elem.setAttribute('mosReq',1);
                         }
                         */
                        break;
                    case 'delimiter':
                    default:
                }
                return;
            }
            function comboOfFields() {
                ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                var ff = jQuery("input#fieldfor").val();
                var pf = jQuery("input#fieldname").val();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'fieldordering', task: 'getFieldsForComboByFieldFor', fieldfor: ff,parentfield:pf}, function (data) {
                    if (data) {
                        console.log(data);
                        var d = jQuery.parseJSON(data);
                        jQuery("div#for-combo").html(d);
                        jQuery("div#for-combo-wrapper").show();
                    }
                });
            }
            function getDataOfSelectedField() {
                ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                var field = jQuery("select#parentfield").val();
                jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'fieldordering', task: 'getSectionToFillValues', pfield: field}, function (data) {
                    if (data) {
                        var d = jQuery.parseJSON(data);
                        jQuery("div#for-combo-options-head").show();
                        jQuery("div#for-combo-options").html(d);
                        jQuery("div#for-combo-options").show();
                    }else{
                        jQuery("div#for-combo-options-head").hide();
                        jQuery("div#for-combo-options").html();
                        jQuery("div#for-combo-options").hide();
                    }
                });
            }
            function getNextField(divid, object) {
                var textvar = divid + '[]';
                var fieldhtml = "<span class='input-field-wrapper' ><input type='text' name='" + textvar + "' class='inputbox one user-field'  /><img alt='<?php echo __('Delete', 'js-support-ticket') ?>' class='input-field-remove-img' src='<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png' /></span>";
                jQuery(object).before(fieldhtml);
            }
            function getObject(obj) {
                var strObj;
                if (document.all) {
                    strObj = document.all.item(obj);
                } else if (document.getElementById) {
                    strObj = document.getElementById(obj);
                }
                return strObj;
            }
            function insertNewRow() {
                var fieldhtml = '<span class="input-field-wrapper" ><input name="values[]" id="values[]" value="" class="inputbox one user-field" type="text" /><img alt="<?php echo __('Delete', 'js-support-ticket') ?>" class="input-field-remove-img" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /></span>';
                jQuery("#user-field-val-button").before(fieldhtml);
            }
            jQuery(document).ready(function () {
                jQuery("body").delegate("img.input-field-remove-img", "click", function () {
                    jQuery(this).parent().remove();
                });
            });
        </script>
    </div>
</div>
