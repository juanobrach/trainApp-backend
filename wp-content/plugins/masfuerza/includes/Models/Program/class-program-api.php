<?php

require dirname(__FILE__) . '/Exercise/routes.php';


class ProgramAPI {

    public function __construct(){
        $this->init_routes();
        $this->exerciseRoutes = new ProgramExerciseRoute();
        $this->program = new Program();
    }

    function init_routes(){

        // $this->exerciseRoutes->init_routes();


        register_rest_route( 'masfuerza/v1', '/programs', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_programs'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_program_by_id'),
        ));
        
                
        // CRUD programs
        
        register_rest_route( 'masfuerza/v1', '/programs/author/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_programs_by_author'),
            ) );
            
            register_rest_route( 'masfuerza/v1', '/programs/get_form_fields', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_form_fields'),
        ));
        
        register_rest_route( 'masfuerza/v1', '/programs/create_program', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_program'),
        ));
        
        register_rest_route( 'masfuerza/v1', '/programs/update_program', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_program'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/delete_program', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_delete_program'),
        ));





        // Routine
        register_rest_route( 'masfuerza/v1', '/programs/create_routine', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_create_routine'),
        ));
      
        register_rest_route( 'masfuerza/v1', '/programs/handle_update_routine', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_routine'),
        ));
        
        register_rest_route( 'masfuerza/v1', '/programs/delete_routine', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_delete_routine'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/assing_athlete', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_assing_athlete'),
        ));

        
        


        register_rest_route( 'masfuerza/v1', '/programs/handle_add_workout', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_add_workout'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/handle_update_workout', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_workout'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/handle_delete_workout', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_delete_workout'),
        ));
        
    }



    public function get_programs(){
       return $this->program->get_programs();
    }



    public function handle_delete_workout($data){
        return $this->program->handle_delete_workout($data);
    }

    public function handle_update_workout($data){
        return $this->program->handle_update_workout($data);
    }

    public function handle_delete_program($data){
        $data =  json_decode ( $data->get_body() );
        return $this->program->handle_delete_program($data->programSku);
    }

    public function handle_assing_athlete($request){
        $data =  $request->get_json_params();
        return $this->program->assing_athlete($data['athlete_id'], $data['program_id'] );
    }


    // Routine

    public function handle_delete_routine($data){
        $data =  json_decode ( $data->get_body() );        
        return $this->program->handle_delete_routine($data);
     }


    public function get_program_by_id($data){
       return $this->program->get_program_by_id($data['id']);
    }

    public function update_program($request){                
        $data =  json_decode( $request->get_body() );                        
        $this->program->handle_update_program($data->program, $data->id );
    }

    public function handle_create_routine($data){
        $this->program->handle_create_routine($data);
    }

    public function handle_add_workout($data){
        $this->program->handle_add_workout($data);
    }

    public function handle_update_routine($data){
        $this->program->handle_update_routine($data);
    }

    public function create_program($planification){
        $this->program->create_program($planification);
    }

    public function get_form_fields(){
        $this->program->get_form_fields();
    }
    
    
    public function get_programs_by_author($data){
        $author_id = $data['id'];
        return $this->program->get_programs($author_id);
    }

    
}

