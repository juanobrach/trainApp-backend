<?php

class Athlete extends Controller{

    public function __construct(){}

    public function athlete_stats($athlete_id){
        $Planification = new Planification();
        $planifications   = $Planification->get_planification_by_athlete_id($athlete_id);

        $exercises_completed = 0;
        $trainer_days_completed = 0;
        $total_exercises = array();
        $max_charge = 0;

        foreach($planifications as $planification){
            $routines = $planification['routines'];
            $max_charge_planification = 0;

            $max_charge_routine = 0;
            foreach($routines as $routine){
                $progress = $routine['progress'];
                
                $days_completed = $progress['daysCompleted'];
                $completed_weeks = $progress['completedWeeks'];
                
                if($days_completed > 0 ){
                    $days = explode(',',$days_completed);
                    $trainer_days_completed += count($days);
                }else{
                    $days = array();
                }
                



                $exercises = $routine['exercises'];
                
                foreach( $exercises as $exercise ){
                    // Count unique exercises
                    

                    $dosage = $exercise['dosings'];
                
                    $max_charge_exercise = 0;
                    // Only iterate over completed weeks and days

                    if( $completed_weeks ){
                        $weeks = explode(',',$completed_weeks);
                    }else{
                        $weeks = array();
                    }
                    
                    if($days_completed > 0 ){
                        $days = explode(',',$days_completed);
                    }else{
                        $days = array();
                    }
                    
                    if( count($days) > 0 ){
                        if(!in_array($exercise['id'], $total_exercises)){
                            $total_exercises[] = $exercise['id'];
                        }
                        if( count( $weeks ) >0 ){
                            foreach ($weeks as $week) {
                                foreach ($days as $day ) {
                                    $charge = $dosage['weeks'][$week-1]['days'][$day-1]['charge'];
                                    if( $max_charge_exercise < $charge ){
                                        $max_charge_exercise = $charge;
                                    }

                                    if( $max_charge_routine < $charge ){
                                        $max_charge_routine = $charge;
                                    }
                                }
                            }
                        }else{   
                        
                            foreach ($days as $day ) {
                                $charge = $dosage['weeks'][0]['days'][$day-1]['charge'];                                
                                if( $max_charge_exercise < $charge ){
                                    $max_charge_exercise = $charge;
                                }
                                
                                if( $max_charge_routine < $charge ){
                                    $max_charge_routine = $charge;
                                }
                            }
                        }
                    }
                    
                }
                
            }
            if(  $max_charge < $max_charge_routine ){
                $max_charge = $max_charge_routine;
            }
        }

        return
            array(
                'max_charge' => (int)$max_charge,
                'days_completed' => (int)$trainer_days_completed,
                'exercises_total' => (int) count( $total_exercises)
            );
    }

    public function desactivate_athlete($athlete_id){
        $Planification = new Planification();
        // Change athlete status to inactive
        update_user_meta( $athlete_id, 'user_is_active', false );        
        $planifications   = $Planification->get_planification_by_athlete_id($athlete_id);        
        foreach( $planifications as $planification ){            
            $Planification->desactivate_planification_by_id($planification['id']);
        }

        return true;
    }

    public function activate_athlete($athlete_id){
        $Planification = new Planification();
        // Change athlete status to inactive
        update_user_meta( $athlete_id, 'user_is_active', true );     
        
        // TODO: ask the owner how to handle reactivation of the account and old planifications
        // $planifications   = $Planification->get_planification_by_athlete_id($athlete_id);        
        // foreach( $planifications as $planification ){            
        //     $Planification->desactivate_planification_by_id($planification['id']);
        // }

        return true;
    }

    public function get_athlete_data($athlete_id){        
        $Planification = new Planification();
        $planifications;

        $planifications   = $Planification->get_planification_by_athlete_id($athlete_id);
        $trainer =  $this->get_athlete_trainer_data($athlete_id);
        
        return array(
            'planifications'=> $planifications,
            'trainer' => $trainer,
            'stats'=> $this->athlete_stats($athlete_id)
        );
    }

    public function create_athlete($data){
        $log_file_dir =  ABSPATH . "wp-content/plugins/masfuerza/includes/Logs/Mails/mails.json";
        
        $log_data = array(
            'user' => $data,
            'error'=> false,
            'message'=> ''
        );

        $user_credentials = array(
            "username" => $data->athlete->username,
            "password" => str_replace(' ', '_',  strtolower($data->athlete->firstName)."_".$data->athlete->phone),
            "email"    => $data->athlete->email
        );        

        $user_id = wp_create_user($user_credentials["username"],$user_credentials["password"],$user_credentials["email"]);
        
        if ( is_wp_error( $user_id ) ) {
            $error_string = $user_id->get_error_message();
           
                    return array(
                        'error' => 'true',
                        'message'=>'ya existe un atleta con ese correo'
                    );
        }else{

            
                update_user_meta($user_id, 'trainer', $data->trainerId  );
                update_user_meta($user_id, 'first_name', $data->athlete->firstName );
                update_user_meta($user_id, 'last_name', $data->athlete->lastName );
                update_user_meta($user_id, 'phone', $data->athlete->phone );
                update_user_meta($user_id, 'user_is_active', true );
        
        
                $u = new WP_User($user_id);
                $u->add_role( 'athlete' );    
                $u->remove_role( 'subscriber' );
                $u->remove_role( 'director' );
        
                
                
                
                $file_path = ABSPATH . "wp-content/plugins/masfuerza/mails/welcome/welcome.html";

                $template = file_get_contents( $file_path );
        
                $variables = array();
                $variables['athlete_name'] = $data->athlete->firstName;
                $variables['user_name'] =  $data->athlete->username;
                $variables['password'] = $user_credentials['password'];
        
                foreach($variables as $key => $value)
                {
                    $template = str_replace('{{'.$key.'}}', $value, $template);
                }
        
                
                $to = $data->athlete->email;
                $subject = 'Bienvenido a TrainApp';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                
                $mail = wp_mail( $to, $subject, $template, $headers );
                if(!$mail){
                    $log_data['error'] = true;
                    logger($log_data , $log_file_dir);
                }
                $athlete = $this->get_athlete_by_id($user_id); 
                return $athlete;
                
        }
        
        

    }

    public function get_athlete_by_id($id){

        $results = get_user_by('ID', $id);

        $athlete_data = $results->data;

        $athlete_meta = get_user_meta($id);
        
        $athlete = array(
            'id' => $athlete_data->ID,
            'email' => $athlete_data->user_email,
            'displayName' => $athlete_data->display_name,
            'firstName' => $athlete_meta['first_name'][0],
            'lastName' =>$athlete_meta['last_name'][0],
            'phone'=>$athlete_meta['phone'][0],
            'username' => $athlete_data->user_nicename
        );         

        return $athlete;        

    }
    /**
     *  Get all athletes by trainer logged
     *  TODO: defined athlete data to return 
     */
    public function get_athletes_by_trainer($trainer_id){
        $athletes = array();
        
        $query = array(
            'relation' => 'AND',
            'role__in' =>  array('athlete'),
            'meta_query' => array(
                array(
                    'key' => 'trainer',
                    'value' => $trainer_id,
                    'compare' => '='
                ),
                array(
                    'key' => 'user_is_active',
                    'value' => false,
                    'compare' => '!='
                )
            ) 
        );

        $results = get_users($query);
        if( !empty($results) ){

            foreach( $results as $athlete_data ){
                $user_meta = get_user_meta($athlete_data->data->ID);
                $athlete = $this->get_athlete_by_id($athlete_data->data->ID);

                $athlete = array(
                    'id' => $athlete_data->data->ID,
                    'firstName' => $user_meta['first_name'][0],
                    'lastName' => $user_meta['last_name'][0],
                    'phone' => $user_meta['phone'][0],                    
                    'email' => $athlete_data->data->user_email,
                    'stats' => $this->athlete_stats($athlete_data->data->ID)
                );         
                $athletes[] = $athlete;        
            }

        }else{
            return array();
        }
        

        return $athletes;
    }

    function checkloggedinuser(){
        $currentuserid_fromjwt = get_current_user_id();
        print_r($currentuserid_fromjwt);
        exit;
    }

    public function get_athlete_trainer_data($athlete_id){    
        $trainer_id = get_user_meta( $athlete_id, 'trainer', true );
        $trainer_data = get_user_meta( $trainer_id );
        $trainer = array(
            'firstName'=> $trainer_data['first_name'][0],
            'lastName'=> $trainer_data['last_name'][0],
            'email' => $trainer_data['billing_email'][0],
            'phone' => $trainer_data['phone'][0],
            'id'=> (int)$trainer_id
        );

        return $trainer;           
    }


}