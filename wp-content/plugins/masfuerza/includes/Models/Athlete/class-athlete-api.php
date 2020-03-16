<?php

class AthleteAPI {

    public function __construct(){
        $this->athlete = new Athlete();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/athletes/by_trainer', array(
            'methods' => 'POST',
            'callback' => array($this, 'get_athletes_by_trainer'),
        ));

        register_rest_route( 'masfuerza/v1', '/athlete/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_athlete'),
        ));

        register_rest_route( 'masfuerza/v1', '/athlete/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_athlete_by_id'),
        ));

    }
        
    public function get_athlete_by_id( $request ){
        $athlete_id = $request['id'];
        if( !empty($athlete_id)){
            return $this->athlete->get_athlete_by_id($athlete_id);
        }else{
            return new WP_Error( 'not_exercise_id', 'Invalid Exercise ID', array( 'status' => 404 ) );
        }
    }

    
    public function get_athletes_by_trainer($request){ 
    
        $request = json_decode ( $request->get_body() );        
        return $this->athlete->get_athletes_by_trainer($request->trainerId);
    }

    public function create_athlete($request){ 
    
        $request_body = json_decode ( $request->get_body() );        
        return $this->athlete->create_athlete($request_body);
    }


}

