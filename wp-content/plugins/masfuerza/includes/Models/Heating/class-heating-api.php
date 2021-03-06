<?php

class HeatingAPI {

    public function __construct(){
        $this->heating = new Heating();
        $this->init_routes();
    }

    function init_routes(){
        
        register_rest_route( 'masfuerza/v1', '/heatings', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_heatings'),
        ));
        
        register_rest_route( 'masfuerza/v1', '/heatings/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_heating')
        ));
    }

    public function get_all_heatings(){
        return $this->heating->get_all_heatings();
    }

    public function get_heating( $request ){
        $heatingId = $request['id'];
        if( !empty($heatingId)){
            return $this->heating->get_heating($heatingId);
        }else{
            return new WP_Error( 'not_exercise_id', 'Invalid Exercise ID', array( 'status' => 404 ) );
        }
    }
}

