<?php

class PlanificationAPI {

    public function __construct(){
        $this->init_routes();

       $this->planification = new Planification();
       //$this->planification->get_data();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/planifications', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_planifications'),
        ));

        register_rest_route( 'masfuerza/v1', '/planifications/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_planification_by_id'),
        ));
        
        register_rest_route( 'masfuerza/v1', '/planifications/author/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_planifications_by_author'),
          ) );

        register_rest_route( 'masfuerza/v1', '/planification/fields', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_planification_fields'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/create_planification', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_create_planification'),
        ));

        
        // Updates

        register_rest_route( 'masfuerza/v1', '/planification/update_planification', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_planification'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/update_dosage_charge', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_update_dosage_charge'),
        ));




        register_rest_route( 'masfuerza/v1', '/planification/get_planification_by_athlete_id', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_get_planification_by_athlete_id'),
        ));

        


        register_rest_route( 'masfuerza/v1', '/planification/asign_routine', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_asign_routine'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/create_routine', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_create_routine'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/handle_add_workout', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_add_workout'),
        ));


        register_rest_route( 'masfuerza/v1', '/planification/handle_update_routine', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_routine'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/handle_update_workout', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_workout'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/handle_delete_workout', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_delete_workout'),
        ));
        
        
    }

    public function handle_delete_workout($data){
        return $this->planification->handle_delete_workout($data);
    }

    public function handle_asign_routine($data){
        $data = $data->get_body();
        return $this->planification->asign_routine($data);
    }


    public function handle_update_workout($data){
        return $this->planification->handle_update_workout($data);
    }

    public function handle_update_update_dosage_charge($request){
        return $this->planification->update_dosage_charge($planification, $routine, $exercise, $week, $day);
    }


    public function get_planification_by_id($data){
       return $this->planification->get_planification_by_id($data['id']);
    }

    public function handle_update_planification($request){
        $data =  $request->get_json_params();        
        $this->planification->update_planification($data["data"], (int)$data["id"]);
    }

    public function handle_create_routine($data){
        $this->planification->handle_create_routine($data);
    }

    public function handle_add_workout($data){
        $this->planification->handle_add_workout($data);
    }

    public function handle_update_routine($data){
        $this->planification->handle_update_routine($data);
    }

    public function handle_create_planification($request){
        $planification =  $request->get_json_params();        
        $this->planification->create_planification( $planification);
    }


    public function handle_get_planification_by_athlete_id($request){
        $data =  $request->get_json_params();        
        return $this->planification->get_planification_by_athlete_id($data['athlete_id']);
    }

    public function get_planification_fields(){
        $this->planification->get_planification_fields();
    }
    


    public function get_all_planifications(){
       return $this->planification->get_planifications();

    }
    
    public function get_planifications_by_author($data){
        $author_id = $data['id'];
        return $this->planification->get_planifications($author_id);
    }

    
}

