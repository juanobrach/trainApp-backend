<?php

class Athlete extends Controller{

    public function __construct(){}


    public function create_athlete($data){

        $user_credentials = array(
            "username" => $data->athlete->email,
            "password" => $data->athlete->mobile,
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
        
        
                $u = new WP_User($user_id);
                $u->add_role( 'athlete' );    
                $u->remove_role( 'subscriber' );
                $u->remove_role( 'director' );
        
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
                )
            ) 
        );

        $results = get_users($query);
        if( !empty($results) ){

            foreach( $results as $athlete_data ){
                $user_meta = get_user_meta($athlete_data->data->ID);

                $athlete = array(
                    'id' => $athlete_data->data->ID,
                    'firstName' => $user_meta['first_name'][0],
                    'lastName' => $user_meta['last_name'][0],
                    'phone' => $user_meta['phone'][0],                    
                    'email' => $athlete_data->data->user_email,
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





}