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


        register_rest_route( 'masfuerza/v1', '/athlete/get_athlete_data', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_get_athlete_data'),
        ));

        register_rest_route( 'masfuerza/v1', '/athlete/desactivate_athlete', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_desactivate_athlete'),
        ));

        register_rest_route( 'masfuerza/v1', '/athlete/activate_athlete', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_activate_athlete'),
        ));

        register_rest_route( 'masfuerza/v1', '/athlete/stats', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_athlete_stats'),
        ));



        

    }

    public function handle_athlete_stats($request){
        $data = json_decode ( $request->get_body() );        
        $athlete_id = $data->athlete_id;       
        return $this->athlete->athlete_stats($athlete_id);

    }


    public function handle_desactivate_athlete($request){
        $data = json_decode ( $request->get_body() );        
        $athlete_id = $data->athlete_id;       
        return $this->athlete->desactivate_athlete($athlete_id);
     }

     public function handle_activate_athlete($request){
        $data = json_decode ( $request->get_body() );        
        $athlete_id = $data->athlete_id;       
        return $this->athlete->activate_athlete($athlete_id);
     }

    public function handle_get_athlete_data($request){
        $data = json_decode ( $request->get_body() );
        $athlete_id = $data->athlete_id;       
        return $this->athlete->get_athlete_data($athlete_id);
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

