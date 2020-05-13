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
                        <li><?php echo __('GDPR Fields','js-support-ticket'); ?></li>
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
            <h1 class="jsstadmin-head-text"><?php echo __('GDPR Fields', 'js-support-ticket') ?></h1>
            <a title="<?php echo __('Add','js-support-ticket'); ?>" class="jsstadmin-add-link button" href="?page=gdpr&jstlay=addgdprfield"><img alt="<?php echo __('Add','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/plus-icon.png" /><?php echo __('Add GDPR Field', 'js-support-ticket') ?></a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0">
            <?php if (!empty(jssupportticket::$_data[0])) { ?>
                <table id="js-support-ticket-table">
                    <tr class="js-support-ticket-table-heading">
                        <th class="left"><?php echo __('Field Title', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Field Text', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Required', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Ordering', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Link Type', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Link', 'js-support-ticket'); ?></th>
                        <th><?php echo __('Action', 'js-support-ticket'); ?></th>
                    </tr>
                    <?php
                    foreach (jssupportticket::$_data[0] AS $field) {
                        $termsandconditions_text = '';
                        $termsandconditions_linktype = '';
                        $termsandconditions_link = '';
                        $termsandconditions_page = '';
                        if(isset($field->userfieldparams) && $field->userfieldparams != '' ){
                            $userfieldparams = json_decode($field->userfieldparams,true);
                            $termsandconditions_text = isset($userfieldparams['termsandconditions_text']) ? $userfieldparams['termsandconditions_text'] :'' ;
                            $termsandconditions_linktype = isset($userfieldparams['termsandconditions_linktype']) ? $userfieldparams['termsandconditions_linktype'] :'' ;
                            $termsandconditions_link = isset($userfieldparams['termsandconditions_link']) ? $userfieldparams['termsandconditions_link'] :'' ;
                            $termsandconditions_page = isset($userfieldparams['termsandconditions_page']) ? $userfieldparams['termsandconditions_page'] :'' ;
                            if($termsandconditions_linktype == 2){
                                $page_title_link = get_the_title($termsandconditions_page);
                            }else{
                                $page_title_link = $termsandconditions_link;
                            }
                        }?>
                        <tr class="js-filter-form-data">
                            <td class="left">
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Field Title', 'js-support-ticket');echo " : "; ?>
                                </span>
                                <a href="?page=gdpr&jstlay=addgdprfield&jssupportticketid=<?php echo $field->id; ?>" title="<?php echo __('Field Title','js-support-ticket'); ?>">
                                    <?php echo __($field->fieldtitle, 'js-support-ticket'); ?>
                                </a>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Field Text', 'js-support-ticket');echo " : "; ?>
                                </span>
                                <?php echo $termsandconditions_text; ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Required', 'js-support-ticket');echo " : "; ?>
                                </span>
                                <?php if ($field->required == 1) { ?>
                                    <img alt="<?php echo __('good','js-support-ticket'); ?>" height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/good.png'; ?>" />
                                <?php }else{ ?>
                                    <img alt="<?php echo __('Close','js-support-ticket'); ?>" height="15" width="15" src="<?php echo jssupportticket::$_pluginpath . 'includes/images/close.png'; ?>" />
                                <?php } ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Ordering', 'js-support-ticket'); echo " : "; ?>
                                </span>
                                <?php  echo $field->ordering; ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Link Type', 'js-support-ticket'); echo " : "; ?>
                                </span>
                                <?php if($termsandconditions_linktype == 2){
                                    echo __('Wordpress Page','js-support-ticket');
                                }else{
                                    echo __('Direct URL','js-support-ticket');
                                } ?>
                            </td>
                            <td>
                                <span class="js-support-ticket-table-responsive-heading">
                                    <?php echo __('Page Title or URL', 'js-support-ticket'); echo " : "; ?>
                                </span>
                                <?php echo __($page_title_link, 'js-support-ticket'); ?>
                            </td>
                            <td>
                                <a title="<?php echo __('Edit','js-support-ticket'); ?>" class="action-btn" href="?page=gdpr&jstlay=addgdprfield&jssupportticketid=<?php echo $field->id; ?>"><img alt="<?php echo __('Edit','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/edit.png" /></a>&nbsp;&nbsp;
                                <a title="<?php echo __('Delete','js-support-ticket'); ?>" class="action-btn" onclick="return confirm('<?php echo __('Are you sure you want to delete it?', 'js-support-ticket'); ?>');" href="<?php echo wp_nonce_url('?page=gdpr&task=deletegdpr&action=jstask&gdprid='.$field->id,'delete-gdpr');?>"><img alt="<?php echo __('Delete','js-support-ticket'); ?>" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/delete.png" /></a>
                            </td>
                        </tr>
                    <?php
            }
                ?>
                </table>
        </div>
            <?php
            // if (jssupportticket::$_data[1]) {
            //     echo '<div class="tablenav"><div class="tablenav-pages">' . jssupportticket::$_data[1] . '</div></div>';
            // }
        } else {
            JSSTlayout::getNoRecordFound();
        }
        ?>
    </div>
</div>
