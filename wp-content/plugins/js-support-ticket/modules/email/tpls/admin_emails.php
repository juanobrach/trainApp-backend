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
                        <li><?php echo __('System Emails','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('System Emails', 'js-support-ticket'); ?></h1>
            <a title="<?php echo __('Add','js-support-ticket'); ?>" class="jsstadmin-add-link button" href="?page=email&jstlay=addemail"><img alt="<?php echo __('Add','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/plus-icon.png" /><?php echo __('Add Email', 'js-support-ticket'); ?></a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form class="js-filter-form" name="jssupportticketform" id="jssupportticketform" method="post" action="<?php echo admin_url("admin.php?page=email&jstlay=emails"); ?>">
                <?php echo JSSTformfield::text('email', jssupportticket::$_data['filter']['email'], array('placeholder' => __('Email', 'js-support-ticket'),'class' => 'js-form-input-field')); ?>
                <?php echo JSSTformfield::hidden('JSST_form_search', 'JSST_SEARCH'); ?>
                <?php echo JSSTformfield::submitbutton('go', __('Search', 'js-support-ticket'), array('class' => 'button js-form-search')); ?>
                <?php echo JSSTformfield::button('reset', __('Reset', 'js-support-ticket'), array('class' => 'button js-form-reset', 'onclick' => 'resetFrom();')); ?>
            </form>
            <span id="js-systemail" class="js-admin-infotitle"><img alt="<?php echo __('info','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/infoicon.png" /><?php echo __('System email used for sending email', 'js-support-ticket'); ?></span>
            <?php if (!empty(jssupportticket::$_data[0])) { ?>
            <table id="js-support-ticket-table">
                <tr class="js-support-ticket-table-heading">
                    <th class="left w60"><?php echo __('Email Address', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Auto Response', 'js-support-ticket'); ?></th>
                    <!-- <th><?php /* echo __('Priority','js-support-ticket'); */ ?></th> -->
                    <th><?php echo __('Created', 'js-support-ticket'); ?></th>
                    <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                </tr>
                <?php
                foreach (jssupportticket::$_data[0] AS $email) {
                    $autoresponse = ($email->autoresponse == 1) ? 'good.png' : 'close.png';
                    ?>
                    <tr>
                        <td class="left w60"><span class="js-support-ticket-table-responsive-heading"><?php echo __('Email Address', 'js-support-ticket');
                echo " : "; ?></span><a title="<?php echo __('Email','js-support-ticket'); ?>" href="?page=email&jstlay=addemail&jssupportticketid=<?php echo $email->id; ?>"><?php echo $email->email; ?></a></td>
                        <td><span class="js-support-ticket-table-responsive-heading"><?php echo __('Auto Response', 'js-support-ticket');
                echo " : "; ?></span><img alt="<?php echo __('Auto Response','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/<?php echo $autoresponse; ?>" /></td>
                        <!-- <td><span class="js-support-ticket-table-responsive-heading"><?php /* echo __('Priority','js-support-ticket');echo " : "; ?></span><?php echo $email->priority; */ ?></td> -->
                        <td><span class="js-support-ticket-table-responsive-heading"><?php echo __('Created', 'js-support-ticket');
                echo " : "; ?></span><?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($email->created)); ?></td>
                        <td >
                            <a title="<?php echo __('Edit','js-support-ticket'); ?>" class="action-btn" href="?page=email&jstlay=addemail&jssupportticketid=<?php echo $email->id; ?>"><img alt="<?php echo __('Edit','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/edit.png" /></a>
                            <a title="<?php echo __('Delete','js-support-ticket'); ?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=email&task=deleteemail&action=jstask&emailid=' .$email->id,'delete-email'); ?>"><img alt="<?php echo __('Delete','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /></a>
                        </td>
                    </tr>
                <?php }
            ?>
            </table>
            <?php
            if (jssupportticket::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
            }
        } else {// User is guest
            JSSTlayout::getNoRecordFound();
        }
        ?>
        </div>
    </div>
</div>
