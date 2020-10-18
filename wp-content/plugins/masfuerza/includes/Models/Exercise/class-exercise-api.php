<?php

class ExerciseAPI {

    public function __construct(){
        $this->exercise = new Exercise();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/exercises', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_exercises')
        ));

        register_rest_route( 'masfuerza/v1', '/exercises/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_exercise')
        ));

        register_rest_route( 'masfuerza/v1', '/exercises/categories', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_exercise_categories')
        ));

        register_rest_route( 'masfuerza/v1', '/exercises/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_create_exercise')
        ));
    }


    public function handle_create_exercise($request){
        $exercise_data = $request->get_json_params();
        return $this->exercise->create_exercise($exercise_data);
        
    }

    public function get_exercise_categories(){
        return $this->exercise->get_exercise_categories();
    }

    public function get_all_exercises($request){
        $data = $request->get_params('user_id');        
        return $this->exercise->get_all_exercises($data['user_id']);
    }

    public function get_exercise( $request ){
        $exerciseId = $request['id'];
        if( !empty($exerciseId)){
            return $this->exercise->get_exercise($exerciseId);
        }else{
            return new WP_Error( 'not_exercise_id', 'Invalid Exercise ID', array( 'status' => 404 ) );
        }
    }
}

