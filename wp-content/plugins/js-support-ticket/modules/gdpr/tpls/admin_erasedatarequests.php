<script type="text/javascript">
    function resetFrom() {
        document.getElementById('email').value = '';
        document.getElementById('jssupportticketform').submit();
    }
</script>
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
                        <li><?php echo __('Erase Data Requests','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('Erase Data Requests', 'js-support-ticket') ?></h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form class="js-filter-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=gdpr&jstlay=erasedatarequests"); ?>">
                <?php echo JSSTformfield::text('email', jssupportticket::$_data['filter']['email'], array('placeholder' => __('User Email', 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH'); ?>
                <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
                <?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
            </form>
            <?php if (!empty(jssupportticket::$_data[0])) { ?>
                <table id="js-support-ticket-table">
                    <tr class="js-support-ticket-table-heading">
                        <th class="left"><?php echo __('Subject', 'js-support-ticket'); ?></th>
                        <th class="left"><?php echo __('Message', 'js-support-ticket'); ?></th>
                        <th ><?php echo __('Email', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Request Status', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Created', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                    </tr>
                    <?php
                    foreach (jssupportticket::$_data[0] AS $request) {
                        ?>
                        <tr>
                            <td class="left">
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Subject', 'js-support-ticket');echo " : "; ?>
                                </span>
                                <?php echo __($request->subject, 'js-support-ticket'); ?>
                            </td>
                            <td class="left">
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Message', 'js-support-ticket');echo " : "; ?>
                                </span>
                                <?php echo __($request->message, 'js-support-ticket'); ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Email', 'js-support-ticket'); echo " : "; ?>
                                </span>
                                <?php echo __($request->user_email, 'js-support-ticket'); ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Request Status', 'js-support-ticket'); echo " : "; ?>
                                </span>
                                <?php
                                    if($request->status == 1){
                                        echo __('Awaiting response','js-support-ticket');
                                    }elseif($request->status == 2){
                                        echo __('Erased identifying data','js-support-ticket');
                                    }else{
                                        echo  __('Deleted','js-support-ticket');
                                    }
                                ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Created', 'js-support-ticket');echo " : "; ?>
                                </span>
                                <?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($request->created)); ?>
                            </td>
                            <td>
                                <a title="<?php echo __('Erase identifying data', 'js-support-ticket');?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure to erase identifying data', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=gdpr&task=eraseidentifyinguserdata&action=jstask&jssupportticketid='.$request->uid,'erase-userdata');?>">
                                    <?php echo __('Erase identifying data', 'js-support-ticket');?>
                                </a>
                                <a title="<?php echo __('Delete data', 'js-support-ticket');?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=gdpr&task=deleteuserdata&action=jstask&jssupportticketid='.$request->uid,'delete-userdata');?>">
                                    <?php echo __('Delete data', 'js-support-ticket');?>
                                </a>
                            </td>
                        </tr>
                    <?php
                }
                ?>
                </table>
                <?php
                if (jssupportticket::$_data[1]) {
                    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . jssupportticket::$_data[1] . '</div></div>';
                }
            } else {
                JSSTlayout::getNoRecordFound();
            }
            ?>
    </div>
</div>
