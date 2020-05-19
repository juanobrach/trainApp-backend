<?php

include dirname(__FILE__) . '/controller.php';


class ProgramExerciseRoute {

    public function __construct(){
        $this->init_routes();
        $this->ProgramExerciseController = new ProgramExerciseController();
    }

    function init_routes(){
        


        register_rest_route( 'masfuerza/v1', '/programs/routine/exercise/delete', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_delete_exercise'),
        ));

        register_rest_route( 'masfuerza/v1', '/programs/routine/exercise/add', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_add_exercise'),
        ));
        
    }

    public function handle_delete_exercise($data){
        $data =  json_decode ( $data->get_body() );        
        return $this->ProgramExerciseController->handle_delete_exercise($data);
    }

    public function handle_add_exercises($data){
        $data =  json_decode ( $data->get_body() );        
     return $this->ProgramExerciseController->handle_add_exercises($data);
    }
    
}

