<?php

include dirname(__FILE__) . '/controller.php';


class ProgramExerciseRoute {

    public function __construct(){
        $this->init_routes();
        $this->ProgramRoutineController = new ProgramRoutineController();
    }

    function init_routes(){
        


        register_rest_route( 'masfuerza/v1', '/programs/routine/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_update_routine'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/routine/update', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_add_exercise'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/routine/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_add_exercise'),
        ));
        
    }

    public function handle_create_routine($data){
        $data =  json_decode ( $data->get_body() );        
        return $this->ProgramRoutineController->handle_create_routine($data);
    }

    public function handle_update_routine($data){
        $data =  json_decode ( $data->get_body() );        
     return $this->ProgramRoutineController->handle_update_routine($data);
    }

    public function handle_update_delete($data){
        $data =  json_decode ( $data->get_body() );        
     return $this->ProgramRoutineController->handle_delete_routine($data->routineId);
    }
    
}

