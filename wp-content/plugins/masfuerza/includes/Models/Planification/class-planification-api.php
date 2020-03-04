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

        register_rest_route( 'masfuerza/v1', '/planification/get_form_fields', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_form_fields'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/create_planification', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_planification'),
        ));

        register_rest_route( 'masfuerza/v1', '/planification/update_planification', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_planification'),
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

    public function handle_update_workout($data){
        return $this->planification->handle_update_workout($data);
    }

    public function get_planification_by_id($data){
       return $this->planification->get_planification_by_id($data['id']);
    }

    public function update_planification($data){
        $this->planification->update_planification($data['id']);
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

    public function create_planification($planification){
        $this->planification->create_planification($planification);
    }

    public function get_form_fields(){
        $this->planification->get_form_fields();
    }
    


    public function get_all_planifications(){
       return $this->planification->get_planifications();

    }
    
    public function get_planifications_by_author($data){
        $author_id = $data['id'];
        return $this->planification->get_planifications($author_id);
    }

    
}

