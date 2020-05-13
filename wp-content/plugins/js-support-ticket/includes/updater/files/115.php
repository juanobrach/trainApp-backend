<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

$query = "SHOW TABLES LIKE '".jssupportticket::$_db->prefix."js_ticket_userfields'";
$table = jssupportticket::$_db->get_var($query);
if($table){    
    $query = "SELECT * FROM `".jssupportticket::$_db->prefix."js_ticket_userfields`";
    $uf = jssupportticket::$_db->get_results($query);
    $userfields = array();
    foreach($uf AS $f){
        $query = "SELECT * FROM `".jssupportticket::$_db->prefix."js_ticket_userfieldvalues` WHERE field = ".$f->id;
        $fv = jssupportticket::$_db->get_results($query);
        $field = array('field' => $f, 'fieldvalues' => $fv);
        $userfields[] = $field;
    }
    if($config['last_step_updater'] < '1151'){
        // Insert into the fieldordering
        $query = "SELECT MAX(ordering) FROM `".jssupportticket::$_db->prefix."js_ticket_fieldsordering`";
        $ordering = jssupportticket::$_db->get_var($query);
        $ordering = $ordering + 1;
        foreach($userfields AS $field){
            $f = $field['field'];
            $fv = $field['fieldvalues'];
            $params = '';
            if($f->type == 'select'){
                $p = array();
                foreach($fv AS $pv){
                    if(!empty($pv->fieldtitle)){
                        $p[] = $pv->fieldtitle;
                    }
                }
                if(!empty($p)){
                    $params = json_encode($p);                        
                }
            }
            if($f->type == 'select'){
                $f->type = 'combo';
            }
            $query = "INSERT INTO `".jssupportticket::$_db->prefix."js_ticket_fieldsordering`
                        (field,fieldtitle,fieldfor,section,ordering,published,isvisitorpublished,search_visitor,isuserfield,userfieldtype,userfieldparams,sys,cannotunpublish) VALUES
                        ('js_".str_replace(' ','',$f->name)."','".$f->title."',1,10,".$ordering.",".$f->published.",".$f->published.",0,1,'".$f->type."','".$params."',0,0)";
            jssupportticket::$_db->query($query);
            $ordering = $ordering + 1;
        }
        $query = "UPDATE `".jssupportticket::$_db->prefix."js_ticket_config` SET configvalue = '1151' WHERE configname = 'last_step_updater'";
        jssupportticket::$_db->query($query);
    }
    $query = "SELECT referenceid AS id FROM `".jssupportticket::$_db->prefix."js_ticket_userfield_data` GROUP BY referenceid";
    $tickets = jssupportticket::$_db->get_results($query);
    if($tickets){
        foreach($tickets AS $t){
            $p = array();
            $query = "SELECT * FROM `".jssupportticket::$_db->prefix."js_ticket_userfield_data` WHERE referenceid = ".$t->id;
            $ufv = jssupportticket::$_db->get_results($query);
            if($ufv){
                foreach($ufv AS $tufv){
                    foreach($userfields AS $uf){
                        $f = $uf['field'];
                        if($tufv->field == $f->id){
                            $v = $uf['fieldvalues'];
                            $ft = "js_".str_replace(' ', '', $f->name);
                            $fv = '';
                            if($f->type == 'combo' OR $f->type == 'select'){
                                foreach($v AS $vfv){
                                    if($vfv->id == $tufv->data){
                                        $fv = $vfv->fieldtitle;
                                        break;
                                    }
                                }
                            }else{
                                $fv = $tufv->data;
                            }
                            $p[$ft] = $fv;
                        }
                    }
                }
                if(!empty($p)){
                    $params = json_encode($p);
                    $params = mysql_real_escape_string($params);
                    $query = "UPDATE `".jssupportticket::$_db->prefix."js_ticket_tickets` SET params = '".$params."' WHERE id = ".$t->id;
                    jssupportticket::$_db->query($query);
                }
                $query = "DELETE FROM `".jssupportticket::$_db->prefix."js_ticket_userfield_data` WHERE referenceid = ".$t->id;
                jssupportticket::$_db->query($query);
            }
        }
        
    }
    $query = "DROP TABLE IF EXISTS `".jssupportticket::$_db->prefix."js_ticket_userfields`";
    jssupportticket::$_db->query($query);
    $query = "DROP TABLE IF EXISTS `".jssupportticket::$_db->prefix."js_ticket_userfieldvalues`";
    jssupportticket::$_db->query($query);
    $query = "DROP TABLE IF EXISTS `".jssupportticket::$_db->prefix."js_ticket_userfield_data`";
    jssupportticket::$_db->query($query);
    $query = "UPDATE `".jssupportticket::$_db->prefix."js_ticket_config` SET configvalue = '115' WHERE configname = 'last_version'";
    jssupportticket::$_db->query($query);
    $query = "UPDATE `".jssupportticket::$_db->prefix."js_ticket_config` SET configvalue = '1152' WHERE configname = 'last_step_updater'";
    jssupportticket::$_db->query($query);
}        
?>
