<?php

/**
 * Define the API functionality.
 *
 * @since      1.0.0
 * @package    Masfuerza
 * @subpackage Masfuerza/includes
 * @author     JuanObrach <juanobrach@gmail.com>
 */
class Masfuerza_Cron {


	/**
	 * Init all api routes available
	 *
	 * @since    1.0.0
	 */
	public function init_api( ) {}


	public function routines_progress(){
    $Planification = new Planification();
    $log = "running cron";
    
    $args = array(
      'numberposts' =>  -1,
      'post_type'		=> 'planification',
      'meta_key'		=> 'planification_active',
      'meta_value'	=>  1
    );
    
    $wp_planifications = get_posts( $args );   
    
    foreach( $wp_planifications as $planification_wp ){
      
      $planification =  $Planification->get_planification_by_id($planification_wp->ID);
      foreach( $planification['routines'] as $routine  ){        

        // if( $routine['active'] === 1 ){          

        if( $routine['active'] === 1 ){          
          
          // Desactivate routine
          update_field( "routines_planification_".$routine['id']."_active", 0 ,  (int)$planification['id'] );
          // TODO : update progress
          $progress = $Planification->update_progress($routine['id'], $planification_wp->ID, $routine['daysPerWeek']);
          
          update_field( "routines_planification_".$routine['id']."_progress", array( $progress['data'] ) ,  (int)$planification['id'] );          
          if( $progress['finished'] === true ){
            update_field( "planification_active", 0 ,  (int)$planification['id'] );
            update_field( "planification_finished", 1 ,  (int)$planification['id'] );
          }
          

          error_log( "Planificaciones modificaada: " . $planification['id'] ." \n" , 3,  dirname(__FILE__)."/my-errors.log");
        }
      }
    }
        
  }

}
