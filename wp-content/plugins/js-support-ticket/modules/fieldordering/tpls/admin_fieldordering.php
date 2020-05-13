<script type="text/javascript">
    function resetFrom() {
        document.getElementById('title').value = '';
        document.getElementById('categoryid').value = '';
        document.getElementById('type').value = '';
        document.getElementById('jssupportticketform').submit();
    }
    jQuery(document).ready(function () {
        jQuery("a#userpopup").click(function (e) {
            e.preventDefault();
            jQuery("div#userpopupblack").show();
            var f = jQuery(this).attr('data-id');
            jQuery.post(ajaxurl, {action: 'jsticket_ajax', jstmod: 'fieldordering', task: 'getOptionsForFieldEdit',field:f}, function (data) {
                if(data){
                    var abc = jQuery.parseJSON(data)
                    jQuery("div#userpopup").html("");
                    jQuery("div#userpopup").html(abc);
                }
            });
            jQuery("div#userpopup").slideDown('slow');
        });
        jQuery("span.close, div#userpopupblack").click(function (e) {
            jQuery("div#userpopup").slideUp('slow', function () {
                jQuery("div#userpopupblack").hide();
            });

        });
        jQuery('table#js-support-ticket-table tbody').sortable({
            handle : ".jsst-order-grab-column",
            update  : function () {
                jQuery('.js-form-button').slideDown('slow');
                var abc =  jQuery('table#js-support-ticket-table tbody').sortable('serialize');
                jQuery('input#fields_ordering_new').val(abc);
            }
        });
    });
    function close_popup(){
        jQuery("div#userpopup").slideUp('slow', function () {
            jQuery("div#userpopupblack").hide();
        });
    }


</script>
<?php
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

JSSTmessage::getMessage(); ?>
<?php
$type = array(
    (object) array('id' => '1', 'text' => __('Public', 'js-support-ticket')),
    (object) array('id' => '2', 'text' => __('Private', 'js-support-ticket'))
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
                        <li><?php echo __('Fields','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Fields', 'js-support-ticket') ?></h1>
            <a title="<?php echo __('Add','js-support-ticket'); ?>" class="jsstadmin-add-link button" href="?page=fieldordering&jstlay=adduserfeild&&fieldfor=<?php echo jssupportticket::$_data['fieldfor']; ?>"><img alt="<?php echo __('Add','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/plus-icon.png" /><?php echo __('Add Field', 'js-support-ticket'); ?></a>
        </div>
        <div id="userpopupblack" style="display:none;"></div>
        <div id="userpopup" style="display:none;">
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <?php if (!empty(jssupportticket::$_data[0])) { ?>
                <form class="jsstadmin-form" method="post" action="<?php echo admin_url("admin.php?page=jssupportticket&task=saveordering"); ?>">
                <table id="js-support-ticket-table">
                    <thead>
                    <tr class="js-support-ticket-table-heading">
                        <th><?php echo __('Ordering', 'js-support-ticket'); ?></th>
                        <th><?php echo __('S.No', 'js-support-ticket'); ?></th>
                        <th class="left"><?php echo __('Field Title', 'js-support-ticket'); ?></th>
                        <th><?php echo __('User Publish', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Visitor Publish', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Required', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $count = count(jssupportticket::$_data[0]) - 1;
                    foreach (jssupportticket::$_data[0] AS $field) {
                        if($field->field == 'wcorderid' || $field->field == 'wcproductid' || $field->field == 'wcitemid'){
                            if(!in_array('woocommerce', jssupportticket::$_active_addons)){
                                continue;
                            }
                            if(!class_exists('WooCommerce')){
                                continue;
                            }
                        }

                        if($field->field == 'eddorderid' || $field->field == 'eddproductid'){
                            if(!in_array('easydigitaldownloads', jssupportticket::$_active_addons)){
                                continue;
                            }
                            if(!class_exists('Easy_Digital_Downloads')){
                                continue;
                            }
                        }

                        if($field->field == 'eddlicensekey'){
                            if(!in_array('easydigitaldownloads', jssupportticket::$_active_addons)){
                                continue;
                            }
                            if(!class_exists('Easy_Digital_Downloads')){
                                continue;
                            }
                            if(!class_exists('EDD_Software_Licensing')){
                                continue;
                            }
                        }
                        if($field->field == 'wcitemid'){
                            continue;
                        }

                        if($field->field == 'envatopurchasecode'){
                            if(!in_array('envatovalidation', jssupportticket::$_active_addons)){
                                continue;
                            }
                        }

                        $alt = $field->published ? __('Published','js-support-ticket') : __('Unpublished','js-support-ticket');
                        $reqalt = $field->required ? __('Required','js-support-ticket') : __('Not required','js-support-ticket');
                        ?>
                        <tr id="id_<?php echo $field->id; ?>">
                            <td class="js-textaligncenter jsst-order-grab-column">
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Ordering', 'js-support-ticket'); echo " : "; ?>
                                </span>
                                <img alt="<?php echo __('grab','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/list-full.png'?>"/>
                            </td>

                            <td>
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('S.No','js-support-ticket'); ?>:</span>
                            <?php echo $field->id; ?></td>
                            <td class="left">
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('Field Title','js-support-ticket'); ?>:</span>
                                <?php
                                    if ($field->fieldtitle)
                                        echo '<a title="'.__('users popup','js-support-ticket').'" href="#" id="userpopup" data-id='.$field->id.'>'.__($field->fieldtitle,'js-support-ticket').'</a>';
                                    else echo __($field->userfieldtitle,'js-support-ticket');
                                    if($field->cannotunpublish == 1){
                                        echo '<font style="color:#1C6288;font-size:20px;margin:0px 5px;">*</font>';
                                    }
                                ?>
                            </td>
                            <td>
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('User Publish','js-support-ticket'); ?>:</span>
                                <?php if ($field->cannotunpublish == 1) { ?>
                                    <img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" title="<?php echo __('Can Not Unpublished','js-support-ticket'); ?>" alt="<?php echo __('good','js-support-ticket'); ?>" />
                                <?php }elseif ($field->published == 1) {
                                    $url  = "?page=fieldordering&task=changepublishstatus&action=jstask&status=unpublish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                         ?>
                                        <a title="<?php echo __('good','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'change-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" alt="<?php echo __('good','js-support-ticket'); ?>" /></a>
                                <?php }else{
                                    $url  = "?page=fieldordering&task=changepublishstatus&action=jstask&status=publish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                         ?>
                                        <a title="<?php echo __('cross','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'change-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/close.png'; ?>" alt="<?php echo __('cross','js-support-ticket'); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('Visitor Publish','js-support-ticket'); ?>:</span>
                                <?php if ($field->cannotunpublish == 1) { ?>
                                    <img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" title="<?php echo __('Can Not Unpublished','js-support-ticket'); ?>" />
                                <?php }elseif ($field->isvisitorpublished == 1) {
                                    $url  = "?page=fieldordering&task=changevisitorpublishstatus&action=jstask&status=unpublish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                         ?>
                                        <a title="<?php echo __('good','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'change-visitor-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" alt="<?php echo __('good','js-support-ticket'); ?>" /></a>
                                <?php }else{
                                    $url  = "?page=fieldordering&task=changevisitorpublishstatus&action=jstask&status=publish&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                         ?>
                                        <a title="<?php echo __('cross','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'change-visitor-publish-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/close.png'; ?>" alt="<?php echo __('cross','js-support-ticket'); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('Required','js-support-ticket'); ?>:</span>
                                <?php if ($field->cannotunpublish == 1 || $field->userfieldtype == 'termsandconditions' ) { ?>
                                    <img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" alt="<?php echo __('good','js-support-ticket'); ?>" title="<?php echo __('can not mark as not required','js-support-ticket'); ?>" />
                                <?php }elseif ($field->required == 1) {
                                    $url  = "?page=fieldordering&task=changerequiredstatus&action=jstask&status=unrequired&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                         ?>
                                        <a title="<?php echo __('good','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'change-required-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" alt="<?php echo __('good','js-support-ticket'); ?>" /></a>
                                <?php }else{
                                    $url  = "?page=fieldordering&task=changerequiredstatus&action=jstask&status=required&fieldorderingid=".$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'];
                                         ?>
                                        <a title="<?php echo __('Close','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'change-required-status'); ?>" ><img height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/close.png'; ?>" title="<?php echo __('Close','js-support-ticket'); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>
                            <span class="js-support-ticket-table-responsive-heading"><?php echo __('Action','js-support-ticket'); ?>:</span>
                                <?php
                                    if($field->isuserfield==1){
                                        echo '<a title="'.__('Edit','js-support-ticket').'" class="action-btn" href="?page=fieldordering&jstlay=adduserfeild&jssupportticketid='.$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'].'"><img alt="'.__('Edit','js-support-ticket').'" src="'.jssupportticket::$_pluginpath.'includes/images/edit.png" /></a>&nbsp;';
                                        echo '<a title="'.__('Delete','js-support-ticket').'" class="action-btn" onclick="return confirm(\''.__('Are you sure you want to delete it?','js-support-ticket').'\');" href="'.wp_nonce_url('?page=fieldordering&task=removeuserfeild&action=jstask&jssupportticketid='.$field->id.'&fieldfor='.jssupportticket::$_data['fieldfor'],'remove-userfeild').'"><img alt="'.__('Delete','js-support-ticket').'" src="'.jssupportticket::$_pluginpath.'includes/images/delete.png" /></a>';
                                    }else{
                                        echo '---';
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                 </tbody>
                 </table>
                 <?php echo JSSTformfield::hidden('fields_ordering_new', '123'); ?>
                    <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                    <?php echo JSSTformfield::hidden('ordering_for', 'fieldordering'); ?>
                    <?php echo JSSTformfield::hidden('fieldfor', jssupportticket::$_data['fieldfor']); ?>
                    <?php echo JSSTformfield::hidden('pagenum_for_ordering', JSSTrequest::getVar('pagenum', 'get', 1)); ?>
                    <div class="js-form-button" style="display: none;">
                        <?php echo JSSTformfield::submitbutton('save', __('Save Ordering', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                    </div>
                </form>
                <div class="jsstadmin-help-msg">
                    <?php echo '<font style="color:#1C6288;font-size:20px;margin:0px 5px;vertical-align: middle;">*</font>'.__('Cannot unpublished field','js-support-ticket'); ?>
                </div>
                <?php
                /*
                  if ( jssupportticket::$_data[1] ) {
                  echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
                  }
                 */
            } else {
                JSSTlayout::getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
