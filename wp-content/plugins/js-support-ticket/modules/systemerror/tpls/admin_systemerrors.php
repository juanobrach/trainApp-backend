<script type="text/javascript">
    function resetFrom() {
        document.getElementById('error').value = '';
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
                        <li><?php echo __('System Errors','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('System Errors', 'js-support-ticket'); ?></h1>
            <a class="jsstadmin-add-link button" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=systemerror&task=deletesystemerror&action=jstask&systemerrorid=all','delete-systemerror');?>"><img alt="<?php echo __('Add','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /><?php echo __('Remove All', 'js-support-ticket'); ?></a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <?php
            if (!empty(jssupportticket::$_data[0])) {
                ?>
                <table id="js-support-ticket-table">
                    <tr class="js-support-ticket-table-heading">
                        <th class="left w70"><?php echo __('Error', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Created', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                    </tr>
                    <?php
                    foreach (jssupportticket::$_data[0] AS $systemerror) {
                        $isview = ($systemerror->isview == 1) ? 'close.png' : 'good.png';
                        ?>
                        <tr>
                            <td class="left w70"><span class="js-support-ticket-table-responsive-heading"><?php
                                    echo __('Error', 'js-support-ticket');
                                    echo " : ";
                                    ?></span><?php echo $systemerror->error; ?></td>
                            <td><span class="js-support-ticket-table-responsive-heading"><?php
                            echo __('Created', 'js-support-ticket');
                            echo " : ";
                                    ?></span><?php echo date_i18n(jssupportticket::$_config['date_format'], strtotime($systemerror->created)); ?></td>
                            <td>
                                <a title="<?php echo __('Delete','js-support-ticket'); ?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=systemerror&task=deletesystemerror&action=jstask&systemerrorid='.$systemerror->id,'delete-systemerror');?>"><img alt="<?php echo __('Delete','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /></a>
                            </td>
                        </tr>
                <?php }
                ?>
                </table>
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
