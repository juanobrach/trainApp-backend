<script type="text/javascript">
    function resetFrom() {
        document.getElementById('title').value = '';
        document.getElementById('jssupportticketform').submit();
    }
    jQuery(document).ready(function () {
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
                        <li><?php echo __('Priorities','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Priorities', 'js-support-ticket'); ?></h1>
            <a title="<?php echo __('Add','js-support-ticket'); ?>" class="jsstadmin-add-link button" href="?page=priority&jstlay=addpriority"><img alt="<?php echo __('Add','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/plus-icon.png" /><?php echo __('Add Priority', 'js-support-ticket'); ?></a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form class="js-filter-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=priority&jstlay=priorities"); ?>">
                <?php echo JSSTformfield::text('title', jssupportticket::$_data['filter']['title'], array('placeholder' => __('Title', 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH'); ?>
                <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
                <?php echo JSSTformfield::button(__('Reset', 'js-support-ticket'), __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
                <?php echo JSSTformfield::select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), jssupportticket::$_data['filter']['pagesize'],__("Records per page",'js-support-ticket'), array('class' => 'js-form-input-field js-right','onchange'=>'document.jssupportticketform.submit();')); ?>
            </form>
            <?php if (!empty(jssupportticket::$_data[0])) { ?>
                <form class="jsstadmin-form" method="post" action="<?php echo admin_url("admin.php?page=jssupportticket&task=saveordering"); ?>">
                    <table id="js-support-ticket-table">
                        <thead>
                        <tr class="js-support-ticket-table-heading">
                            <th><?php echo __('Ordering', 'js-support-ticket'); ?></th>
                            <th class="left"><?php echo __('Title', 'js-support-ticket'); ?></th>
                            <?php if(in_array('overdue', jssupportticket::$_active_addons)){ ?>
                                <th><?php echo __('Date Interval', 'js-support-ticket'); ?>&nbsp;<?php echo __('(Days/Hours)', 'js-support-ticket'); ?></th>
                                <th><?php echo __('Ticket Overdue', 'js-support-ticket'); ?></th>
                            <?php } ?>
                            <th><?php echo __('Public', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Default', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Order', 'js-support-ticket'); ?></th>
                            <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $number = 0;
                        $count = COUNT(jssupportticket::$_data[0]) - 1; //For zero base indexing
                        $pagenum = JSSTrequest::getVar('pagenum', 'get', 1);
                        $islastordershow = JSSTpagination::isLastOrdering(jssupportticket::$_data['total'], $pagenum);
                        foreach (jssupportticket::$_data[0] AS $priority) {
                            $isdefault = ($priority->isdefault == 1) ? 'good.png' : 'close.png';
                            $ispublic = ($priority->ispublic == 1) ? 'good.png' : 'close.png';
                            $ticketoverduetype = ($priority->overduetypeid == 1) ? 'Days' : 'Hours';
                            ?>

                            <tr id="id_<?php echo $priority->id; ?>">
                                <td class="js-textaligncenter jsst-order-grab-column">
                                    <span class="js-support-ticket-table-responsive-heading">
                                        <?php echo __('Ordering', 'js-support-ticket'); echo " : "; ?>
                                    </span>
                                    <img alt="<?php echo __('grab','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/list-full.png'?>"/>
                                </td>

                                <td class="left"><span class="js-support-ticket-table-responsive-heading"><?php
                                        echo __('Title', 'js-support-ticket');
                                        echo " : ";
                                        ?></span><a title="<?php echo __('Priority','js-support-ticket'); ?>" href="?page=priority&jstlay=addpriority&jssupportticketid=<?php echo $priority->id; ?>"><?php echo __($priority->priority, 'js-support-ticket'); ?></a></td>
                                <?php if(in_array('overdue', jssupportticket::$_active_addons)){ ?>
                                    <td><span class="js-support-ticket-table-responsive-heading"><?php
                                        echo __('Date Interval', 'js-support-ticket');
                                        echo " : ";
                                        ?></span><?php echo __($priority->overdueinterval , 'js-support-ticket'); ?></td>
                                    <td><span class="js-support-ticket-table-responsive-heading"><?php
                                        echo __('Ticket Overdue', 'js-support-ticket');
                                        echo " : ";
                                        ?></span><?php echo __($ticketoverduetype, 'js-support-ticket'); ?></td>
                                <?php } ?>
                                <td><span class="js-support-ticket-table-responsive-heading"><?php
                                        echo __('Public', 'js-support-ticket');
                                        echo " : ";
                                        ?></span> <img alt="<?php echo __('Public','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/<?php echo $ispublic; ?>" /></td>
                                <td><span class="js-support-ticket-table-responsive-heading"><?php
                                    echo __('Default', 'js-support-ticket');
                                    echo " : ";
                                    ?></span>
                                    <?php $url = '?page=priority&task=makedefault&action=jstask&priorityid='.$priority->id;
                                    if($pagenum > 1){
                                        $url .= '&pagenum=' . $pagenum;
                                    }?><a title="<?php echo __('Default','js-support-ticket'); ?>" href="<?php echo wp_nonce_url($url, 'make-default'); ?>" ><img alt="<?php echo __('Default','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/<?php echo $isdefault; ?>" /></a></td>
                                <td><span class="js-support-ticket-table-responsive-heading"><?php
                            echo __('Color', 'js-support-ticket');
                            echo " : ";
                            ?></span> <span class="js-ticket-admin-prirrity-color" style="background:<?php echo $priority->prioritycolour; ?>;color:#ffffff;"> <?php echo $priority->prioritycolour; ?></span></td>
                                <td>
                                    <a title="<?php echo __('Edit','js-support-ticket'); ?>" class="action-btn" href="?page=priority&jstlay=addpriority&jssupportticketid=<?php echo $priority->id; ?>"><img alt="<?php echo __('Edit','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                    <a title="<?php echo __('Delete','js-support-ticket'); ?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=priority&task=deletepriority&action=jstask&priorityid='.$priority->id,'delete-priority');?>"><img alt="<?php echo __('Delete','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /></a>
                                </td>
                            </tr>
                        <?php
                        $number++;
                    }
                    ?>
                    </tbody>
                    </table>
                        <?php echo JSSTformfield::hidden('fields_ordering_new', '123'); ?>
                       <?php echo JSSTformfield::hidden('form_request', 'jssupportticket'); ?>
                       <?php echo JSSTformfield::hidden('ordering_for', 'priority'); ?>
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
