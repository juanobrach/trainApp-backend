 <?php
$allPlugins = get_plugins(); // associative array of all installed plugins

$addon_array = array();
foreach ($allPlugins as $key => $value) {
    $addon_index = explode('/', $key);
    $addon_array[] = $addon_index[0];
}
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
                            <li><?php echo __('Install Addons','js-support-ticket'); ?></li>
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
                <h1 class="jsstadmin-head-text"><?php echo __('Install Addons', 'js-support-ticket'); ?></h1>
            </div>
            <div id="jsstadmin-data-wrp" class="p0">
                <div id="jssupportticket-content">
                    <div id="black_wrapper_translation"></div>
                    <div id="jstran_loading">
                        <img alt="image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/spinning-wheel.gif" />
                    </div>
                    <div id="jsst-lower-wrapper">
                        <div class="jsst-addon-installer-wrapper" >
                            <form id="jsticketfrom" action="<?php echo admin_url('admin.php?page=premiumplugin&task=downloadandinstalladdons&action=jstask'); ?>" method="post">
                                <div class="jsst-addon-installer-left-section-wrap" >
                                    <div class="jsst-addon-installer-left-image-wrap" >
                                        <img class="jsst-addon-installer-left-image" src="<?php echo jssupportticket::$_pluginpath; ?>includes/images/addon-images/addon-installer-logo.png" />
                                    </div>
                                    <div class="jsst-addon-installer-left-heading" >
                                        <?php echo __("JS Help Desk","js-support-ticket"); ?>
                                    </div>
                                    <div class="jsst-addon-installer-left-title" >
                                        <?php echo __("Wordpress Plugin","js-support-ticket"); ?>
                                    </div>
                                    <div class="jsst-addon-installer-left-description" >
                                        <?php echo __("JS Help Desk is a trusted open source ticket system. JS Help Desk is a simple, easy to use, web-based customer support system. Users can create a ticket from the front-end. JS Help Desk comes packed with lot features than most of the expensive(and complex) support ticket system on the market. The best part is, It completely free.","js-support-ticket"); ?>
                                    </div>
                                </div>
                                <div class="jsst-addon-installer-right-section-wrap step2" >
                                    <div class="jsst-addon-installer-right-heading" >
                                        <?php echo __("JS Help Desk Addon Installer","js-support-ticket"); ?>
                                    </div>
                                    <?php /*
                                    <div class="jsst-addon-installer-right-description" >
                                        lorem ipsum dolor sit amet
                                    </div> */ ?>
                                    <div class="jsst-addon-installer-right-addon-wrapper" >
                                        <?php
                                        $error_message = '';
                                        if(isset($_SESSION['jsst_addon_install_data'])){
                                            $result = $_SESSION['jsst_addon_install_data'];
                                            if(isset($result['status']) && $result['status'] == 1){?>
                                                <div class="jsst-addon-installer-right-addon-title">
                                                    <?php echo __("Select Addons for download","js-support-ticket"); ?>
                                                </div>
                                                <div class="jsst-addon-installer-right-addon-section" >
                                                    <?php
                                                    if(!empty($result['data'])){
                                                        $addon_availble_count = 0;
                                                        foreach ($result['data'] as $key => $value) {
                                                            if(!in_array($key, $addon_array)){
                                                                $addon_availble_count++;
                                                                $addon_slug_array = explode('-', $key);
                                                                $addon_image_name = $addon_slug_array[count($addon_slug_array) - 1];
                                                                $addon_slug = str_replace('-', '', $key);

                                                                $addon_img_path = '';
                                                                $addon_img_path = jssupportticket::$_pluginpath.'includes/images/addon-images/addons/';
                                                                if($value['status'] == 1){ ?>
                                                                    <div class="jsst-addon-installer-right-addon-single" >
                                                                        <img class="jsst-addon-installer-right-addon-image" data-addon-name="<?php echo $key; ?>" src="<?php echo $addon_img_path.$addon_image_name.'.png';?>" />
                                                                        <div class="jsst-addon-installer-right-addon-name" >
                                                                            <?php echo $value['title'];?>
                                                                        </div>
                                                                        <input type="checkbox" class="jsst-addon-installer-right-addon-single-checkbox" id="addon-<?php echo $key; ?>" name="<?php echo $key; ?>" value="1">
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        if($addon_availble_count == 0){ // all allowed addon are already installed
                                                            $error_message = __('All allowed add ons are already installed','js-support-ticket').'.';
                                                        }
                                                    }else{ // no addon returend
                                                        $error_message = __('You are not allowed to install any add on','js-support-ticket').'.';
                                                    }
                                                    if($error_message != ''){
                                                        $url = admin_url("admin.php?page=premiumplugin&jstlay=step1");

                                                        echo '<div class="jsst-addon-go-back-messsage-wrap">';
                                                        echo '<h1>';
                                                        echo $error_message;
                                                        echo '</h1>';

                                                        echo '<a class="jsst-addon-go-back-link" href="'.$url.'">';
                                                        echo __('Back','js-support-ticket');
                                                        echo '</a>';
                                                        echo '</div>';
                                                    }
                                                     ?>
                                                </div>
                                                <?php if($error_message == ''){ ?>
                                                    <div class="jsst-addon-installer-right-addon-bottom" >
                                                        <label for="jsst-addon-installer-right-addon-checkall-checkbox"><input type="checkbox" class="jsst-addon-installer-right-addon-checkall-checkbox" id="jsst-addon-installer-right-addon-checkall-checkbox"><?php echo __("Select All Addons","js-support-ticket"); ?></label>
                                                    </div>
                                                <?php
                                                }
                                            }
                                        }else{
                                            $error_message = __('Something went wrong','js-support-ticket').'!';
                                            $url = admin_url("admin.php?page=premiumplugin&jstlay=step1");

                                            echo '<div class="jsst-addon-go-back-messsage-wrap">';
                                            echo '<h1>';
                                            echo $error_message;
                                            echo '</h1>';

                                            echo '<a class="jsst-addon-go-back-link" href="'.$url.'">';
                                            echo __('Back','js-support-ticket');
                                            echo '</a>';
                                            echo '</div>';
                                        }

                                         ?>
                                    </div>
                                    <?php if($error_message == ''){ ?>
                                        <div class="jsst-addon-installer-right-button" >
                                            <button type="submit" class="jsst_btn" role="submit" onclick="jsShowLoading();"><?php echo __("Proceed","js-support-ticket"); ?></button>
                                        </div>
                                    <?php } ?>
                                </div>
                                <input type="hidden" name="token" value="<?php echo $result['token']; ?>"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    jQuery(document).ready(function(){
        jQuery('#jsticketfrom').on('submit', function() {
            jsShowLoading();
        });

        jQuery('.jsst-addon-installer-right-addon-image').on('click', function() {
            var addon_name = jQuery(this).attr('data-addon-name')
            var prop_checked = jQuery("#addon-"+addon_name).prop("checked");
            if(prop_checked){
                jQuery("#addon-"+addon_name).prop("checked", false);
            }else{
                jQuery("#addon-"+addon_name).prop("checked", true);
            }
        });
        // to handle select all check box.
        jQuery('#jsst-addon-installer-right-addon-checkall-checkbox').change(function() {
           jQuery(".jsst-addon-installer-right-addon-single-checkbox").prop("checked", this.checked);
       });


    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }
</script>
<?php
if(isset($_SESSION['jsst_addon_install_data'])){// to avoid to show data on refresh
    unset($_SESSION['jsst_addon_install_data']);
}
?>
