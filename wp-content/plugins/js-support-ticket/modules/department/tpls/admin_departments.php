<script type="text/javascript">
    function resetFrom() {
        document.getElementById('departmentname').value = '';
        document.getElementById('jssupportticketform').submit();
    }

    jQuery(document).ready(function () {
        jQuery("div#jsvm_full_background").click(function () {
            searchclosePopup();
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
</script>

<?php
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
JSSTmessage::getMessage(); ?>
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
                        <li><?php echo __('Departments','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Departments', 'js-support-ticket') ?></h1>
            <a title="<?php echo __('Add','js-support-ticket'); ?>" class="jsstadmin-add-link button" href="?page=department&jstlay=adddepartment"><img alt="<?php echo __('Add','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/plus-icon.png" /><?php echo __('Add Department', 'js-support-ticket'); ?></a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form class="js-filter-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=department&jstlay=departments"); ?>">
                <?php echo JSSTformfield::text('departmentname', jssupportticket::$_data['filter']['departmentname'], array('placeholder' => __('Department Name', 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH'); ?>
                <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
                <?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
                <?php echo JSSTformfield::select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), jssupportticket::$_data['filter']['pagesize'],__("Records per page",'js-support-ticket'), array('class' => 'js-form-input-field js-right','onchange'=>'document.jssupportticketform.submit();')); ?>
            </form>
            <?php if (!empty(jssupportticket::$_data[0])) { ?>
                <form class="jsstadmin-form" method="post" action="<?php echo admin_url("admin.php?page=jssupportticket&task=saveordering"); ?>">
                    <table id="js-support-ticket-table">
                        <tr class="js-support-ticket-table-heading">
                            <th><?php echo __('Ordering', 'js-support-ticket'); ?></th>
                            <th class="left"><?php echo __('Department Name', 'js-support-ticket'); ?></th>
                            <th class="left"><?php echo __('Outgoing Email', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Default', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Status', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Created', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                        </tr>
                        <?php
                        $number = 0;
                        $count = COUNT(jssupportticket::$_data[0]) - 1; //For zero base indexing
                        $pagenum = JSSTrequest::getVar('pagenum', 'get', 1);
                        $islastordershow = JSSTpagination::isLastOrdering(jssupportticket::$_data['total'], $pagenum);
                        foreach (jssupportticket::$_data[0] AS $department) {
                            $default = ($department->isdefault == 1) ? 'good.png' : 'close.png';
                            $status = ($department->status == 1) ? 'good.png' : 'close.png';
                            ?>
                            <tr id="id_<?php echo $department->id; ?>" style="width: 100%;" >
                                <td class="js-textaligncenter jsst-order-grab-column">
                                    <span class="js-support-ticket-table-responsive-heading">
                                        <?php echo __('Ordering', 'js-support-ticket'); echo " : "; ?>
                                    </span>
                                    <img alt="<?php echo __('grab','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/list-full.png'?>"/>
                                </td>
                                <td class="left jsst-left-row"><span class="js-support-ticket-table-responsive-heading"><?php echo __('Department', 'js-support-ticket');
                        echo " : "; ?></span><a title="<?php echo __('Department','js-support-ticket'); ?>" href="?page=department&jstlay=adddepartment&jssupportticketid=<?php echo $department->id; ?>"><?php echo __($department->departmentname, 'js-support-ticket'); ?></a></td>
                                <td class="left"><span class="js-support-ticket-table-responsive-heading"><?php echo __('Outgoing Email', 'js-support-ticket');
                        echo " : "; ?></span><?php echo $department->outgoingemail; ?></td>
                                <td><span class="js-support-ticket-table-responsive-heading"><?php echo __('Status', 'js-support-ticket');
                                echo " : "; ?></span>
                                <a title="<?php echo __('Default','js-support-ticket'); ?>" href="<?php echo wp_nonce_url('?page=department&task=changedefault&action=jstask&departmentid='. $department->id.'&default='.$department->isdefault, 'change-default');?>"> <img alt="<?php echo __('Default','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath  .'includes/images/' . $default; ?>"/> </a></td>
                                <td><span class="js-support-ticket-table-responsive-heading"><?php echo __('Status', 'js-support-ticket');
                                echo " : "; ?></span><a title="<?php echo __('Status','js-support-ticket'); ?>" href="<?php echo wp_nonce_url('?page=department&task=changestatus&action=jstask&departmentid='.$department->id,'change-status');?>"> <img alt="<?php echo __('Status','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/' . $status; ?>"/> </a></td>
                                <td><span class="js-support-ticket-table-responsive-heading"><?php echo __('Created', 'js-support-ticket');
                        echo " : "; ?></span><?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($department->created)); ?></td>
                                <td>
                                    <a title="<?php echo __('Edit','js-support-ticket'); ?>" class="action-btn" href="?page=department&jstlay=adddepartment&jssupportticketid=<?php echo $department->id; ?>"><img alt="<?php echo __('Edit','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                    <a title="<?php echo __('Delete','js-support-ticket'); ?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=department&task=deletedepartment&action=jstask&departmentid='.$department->id,'delete-department');?>"><img alt="<?php echo __('Delete','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /></a></td>
                            </tr>
                        <?php
                        $number++;
                }
                    ?>
                    </table>
                    <?php echo JSSTformfield::hidden('fields_ordering_new', '123'); ?>
                    <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                    <?php echo JSSTformfield::hidden('ordering_for', 'department'); ?>
                    <?php echo JSSTformfield::hidden('pagenum_for_ordering', JSSTrequest::getVar('pagenum', 'get', 1)); ?>
                    <div class="js-form-button" style="display: none;">
                        <?php echo JSSTformfield::submitbutton('save', __('Save Ordering', 'js-support-ticket'), array('class' => 'button js-form-save')); ?>
                    </div>
                </form>
                <?php
                if (jssupportticket::$_data[1]) {
                    echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
                }
            } else {
                JSSTlayout::getNoRecordFound();
            }
            ?>
        </div>
    </div>
</div>
