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
    }

    public function get_all_exercises(){
        return $this->exercise->get_all_exercises();
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

