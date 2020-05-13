<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTincluder {

    function __construct() {

    }

    /*
     * Includes files
     */

    public static function include_file($filename, $module_name = null) {
        if ($module_name != null) {
            $file_path = JSSTincluder::getPluginPath($module_name,'file',$filename);
            if (file_exists(jssupportticket::$_path . 'includes/css/inc-css/' . $module_name . '-' . $filename . '.css.php')) {
                require_once(jssupportticket::$_path . 'includes/css/inc-css/' . $module_name . '-' . $filename . '.css.php');
            }
            //include_once $path . 'modules/' . $module_name . '/tpls/' . $filename . '.php';
			if (locate_template('js-support-ticket/' . $module_name . '-' . $filename . '.php', 1, 1)) {
			   return;
			}

            if(file_exists($file_path)){
                include_once $file_path; //
            }else{
                $file_path = JSSTincluder::getPluginPath('premiumplugin','file','missingaddon');
                include_once $file_path; //
            }
        } else {
            $file_path = JSSTincluder::getPluginPath($filename,'file');
            if(file_exists($file_path)){
                include_once $file_path; //
            }else{
                $file_path = JSSTincluder::getPluginPath('premiumplugin','file');
                include_once $file_path; //
            }
        }
        return;
    }

    /*
     * Static function to handle the page slugs
     */

    public static function include_slug($page_slug) {
        include_once jssupportticket::$_path . 'modules/js-support-ticket-controller.php';
    }

    /*
     * Static function for the model object
     */

    public static function getJSModel($modelname) {
        $file_path = JSSTincluder::getPluginPath($modelname,'model');
        // echo var_dump(JSSTincluder::getPluginPath($modelname));
        // exit;
        include_once $file_path;
        $classname = "JSST" . $modelname . 'Model';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes objects
     */

    public static function getObjectClass($classname) {
        $file_path = JSSTincluder::getPluginPath($classname,'class');

        include_once $file_path;
        $classname = 'JSST'.$classname;
        $obj = new $classname();
        return $obj;
    }

    public static function getClassesInclude($classname) {
        $file_path = JSSTincluder::getPluginPath($classname,'class');
        include_once $file_path;
    }

    /*
     * Static function for the controller object
     */

    public static function getJSController($controllername) {
        $file_path = JSSTincluder::getPluginPath($controllername,'controller');

        include_once $file_path;
        $classname = "JSST".$controllername . "Controller";
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the Table Class Object
     */

    public static function getJSTable($tableclass) {
        $file_path = JSSTincluder::getPluginPath($tableclass,'table');
        require_once jssupportticket::$_path . 'includes/tables/table.php';
        include_once $file_path;
        $classname = "JSST" . $tableclass . 'Table';
        $obj = new $classname();
        return $obj;
    }

    /*
     *  Identify file path to include or require this fucntion helps to accommodate addon calls
     */

    public static function getPluginPath($module,$type,$file_name = '') {

        $addons_secondry = array('articles','articleattachmet','banemaillog','downloadattachment','roleaccessdepartments','rolepermissions','useraccessdepartments','userpermissions', 'role', 'acl_roles', 'acl_role_access_departments', 'acl_role_permissions', 'categories' ,'email_banlist', 'acl_user_access_departments','articles_attachments','email_banlist','acl_user_permissions');

        if(in_array($module, jssupportticket::$_active_addons)){
            $path = WP_PLUGIN_DIR.'/'.'js-support-ticket-'.$module.'/';
            switch ($type) {
                case 'file':
                    if($file_name != ''){
                        $file_path = $path . 'module/tpls/' . $file_name . '.php';
                    }else{
                        $file_path = $path . 'module/controller.php';
                    }
                    break;
                case 'model':
                    $file_path = $path . 'module/model.php';
                    break;
                case 'class':
                    $file_path = $path . 'classes/' . $module . '.php';
                    break;
                case 'controller':
                    $file_path = $path . 'module/controller.php';
                    break;
                case 'table':
                    $file_path = $path . 'includes/' . $module . '-table.php';
                    break;
            }

        }elseif(in_array($module, $addons_secondry)){ // to handle the case of modules that are submodules for some addon
            $parent_module = '';
            switch ($module) {// to identify addon for submodules.
                case 'articles':
                case 'articleattachmet':
                case 'articles_attachments':
                case 'categories':
                    $parent_module = 'knowledgebase';
                    break;
                case 'banemaillog':
                case 'email_banlist':
                case 'email_banlist':
                    $parent_module = 'banemail';
                    break;
                case 'downloadattachment':
                    $parent_module = 'download';
                    break;
                case 'roleaccessdepartments':
                case 'rolepermissions':
                case 'useraccessdepartments':
                case 'userpermissions':
                case 'role':
                case 'acl_roles':
                case 'acl_role_access_departments':
                case 'acl_user_access_departments':
                case 'acl_role_permissions':
                case 'acl_user_permissions':
                    $parent_module = 'agent';
                    break;
            }

            $path = WP_PLUGIN_DIR.'/'.'js-support-ticket-'.$parent_module.'/';
            if(in_array($parent_module, jssupportticket::$_active_addons)){
                switch ($type) {
                    case 'file':
                        if($file_name != ''){
                            $file_path = $path . $module.'/tpls/' . $file_name . '.php';
                        }else{
                            $file_path = $path . $module.'/controller.php';
                        }
                        break;
                    case 'model':
                        $file_path = $path . $module.'/model.php';
                        break;

                    case 'class':
                        $file_path = $path . 'classes/' . $module . '.php';
                        break;
                    case 'controller':
                        $file_path = $path . $module.'/controller.php';
                        break;
                    case 'table':
                        $file_path = $path . 'includes/' . $module . '-table.php';
                        break;
                }
            }else{
                $file_path = JSSTincluder::getPluginPath('premiumplugin','file');
            }
        }else{
            $path = jssupportticket::$_path;
            switch ($type) {
                case 'file':
                    if($file_name != ''){
                        $file_path = $path . 'modules/' . $module . '/tpls/' . $file_name . '.php';
                    }else{
                        $file_path = $path . 'modules/' . $module . '/controller.php';
                    }
                    break;
                case 'model':
                        $file_path = $path . 'modules/' . $module . '/model.php';
                    break;

                case 'class':
                    $file_path = $path . 'includes/classes/' . $module . '.php';
                    break;
                case 'controller':
                        $file_path = $path . 'modules/' . $module . '/controller.php';
                    break;
                case 'table':
                    $file_path = $path . 'includes/tables/' . $module . '.php';;
                    break;
            }
        }
        return $file_path;
    }

}

$includer = new JSSTincluder();
if (!defined('JCONSTINST'))
   define('JCONSTINST', base64_decode("aHR0cHM6Ly9zZXR1cC5qb29tc2t5LmNvbS9qc3RpY2tldHdwL3Byby9pbmRleC5waHA="));
if (!defined('JCONSTS'))
    define('JCONSTS', base64_decode("aHR0cDovL3d3dy5qb29tc2t5LmNvbS9pbmRleC5waHA/b3B0aW9uPWNvbV9qc3Byb2R1Y3RsaXN0aW5nJnRhc2s9YWFnamN3cA=="));

if (!defined('JCONSTV'))
    define('JCONSTV', base64_decode("aHR0cHM6Ly9zZXR1cC5qb29tc2t5LmNvbS9qc3RpY2tldHdwL3Byby92ZXJpZmllci5waHA="));
?>
