<?php
wp_enqueue_script('colorpicker.js', jssupportticket::$_pluginpath . 'includes/js/colorpicker.js');
wp_enqueue_style('colorpicker', jssupportticket::$_pluginpath . 'includes/css/colorpicker.css');
?>
<script type="text/javascript">

jQuery(document).ready(function () {
        jQuery('select#overduetypeid').change(function(){
            changevalue();
        });
        changevalue();
        function changevalue()
        {
            var isselect = jQuery('select#overduetypeid').val();
            if(isselect == 1){
                jQuery('span.ticket_overdue_type_text').html("<?php echo __('Days', 'js-support-ticket');?>");
            }else{
                jQuery('span.ticket_overdue_type_text').html("<?php echo __('Hours', 'js-support-ticket');?>");
            }
        }

        jQuery.validate();
    });
</script>
<?php
    $dayshours = array(
    (object) array('id' => '1', 'text' => __('Days', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Hours', 'js-support-ticket'))
    );
?>
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
                        <li><?php echo __('Add Priority','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Add Priority', 'js-support-ticket'); ?></h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form class="jsstadmin-form" method="post" action="<?php echo admin_url("?page=priority&task=savepriority"); ?>">
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Priority', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('priority', isset(jssupportticket::$_data[0]->priority) ? jssupportticket::$_data[0]->priority : '', array('class' => 'inputbox js-form-input-field', 'data-validation' => 'required')) ?></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Color', 'js-support-ticket'); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="js-form-value"><?php echo JSSTformfield::text('prioritycolor', isset(jssupportticket::$_data[0]->prioritycolour) ? jssupportticket::$_data[0]->prioritycolour : '', array('class' => 'inputbox js-form-input-field', 'data-validation' => 'required')); ?></div>
                </div>
                <?php if(in_array('overdue', jssupportticket::$_active_addons)){ ?>
                    <div class="js-form-wrapper">
                        <div class="js-form-title"><?php echo __('Ticket Overdue Interval Type', 'js-support-ticket') ?></div>
                        <div class="js-form-value"><?php echo JSSTformfield::select('overduetypeid', $dayshours , (isset(jssupportticket::$_data[0]->overduetypeid) ? jssupportticket::$_data[0]->overduetypeid : '' ), '',array('class' => 'inputbox js-form-select-field'))?></div>
                    </div>
                    <div class="js-form-wrapper">
                        <div class="js-form-title"><?php echo __('Ticket Overdue', 'js-support-ticket') ?></div>
                        <div class="js-form-value"><?php echo JSSTformfield::text('overdueinterval', isset(jssupportticket::$_data[0]->overdueinterval) ? jssupportticket::$_data[0]->overdueinterval : '', array('class' => 'inputbox js-form-input-field')) ?><span class="ticket_overdue_type_text" ><?php echo isset(jssupportticket::$_data[0]->overduetypeid) ? jssupportticket::$_data[0]->overduetypeid : '' ?></span></div>
                    </div>
                <?php } ?>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Public', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('ispublic', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->ispublic) ? jssupportticket::$_data[0]->ispublic : '1', array('class' => 'radiobutton')); ?></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Default', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('isdefault', array('1' => __('Yes', 'js-support-ticket'), '0' => __('No', 'js-support-ticket')), isset(jssupportticket::$_data[0]->isdefault) &&  jssupportticket::$_data[0]->isdefault == 1 ? 1 : 0, array('class' => 'radiobutton')); ?></div>
                </div>
                <div class="js-form-wrapper">
                    <div class="js-form-title"><?php echo __('Status', 'js-support-ticket'); ?></div>
                    <div class="js-form-value"><?php echo JSSTformfield::radiobutton('status', array('1' => __('Enabled', 'js-support-ticket'), '0' => __('Disabled', 'js-support-ticket')), isset(jssupportticket::$_data[0]->status) ? jssupportticket::$_data[0]->status : '1', array('class' => 'radiobutton')); ?></div>
                </div>
                <?php echo JSSTformfield::hidden('id', isset(jssupportticket::$_data[0]->id) ? jssupportticket::$_data[0]->id : '' ); ?>
                <?php echo JSSTformfield::hidden('ordering', isset(jssupportticket::$_data[0]->ordering) ? jssupportticket::$_data[0]->ordering : '' ); ?>
                <?php echo JSSTformfield::hidden('action', 'priority_savepriority'); ?>
                <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                <?php echo JSSTformfield::hidden('uid', get_current_user_id()); ?>
                <div class="js-form-button">
                    <?php echo JSSTformfield::submitbutton('save', __('Save Priority', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                /*jQuery('input#prioritycolor').on('click', function(){
                    alert('hi');
                });*/
                jQuery('input#prioritycolor').ColorPicker({
                    color: jQuery('input#prioritycolor').val(),
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#prioritycolor').css('backgroundColor', '#' + hex).val('#' + hex);
                    }
                });
            });

        </script>
    </div>
</div>
