<?php

class DosingAPI {

    public function __construct(){
        $this->dosing = new Dosing();
        $this->init_routes();
    }

    function init_routes(){
        
        register_rest_route( 'masfuerza/v1', '/dosings', array(
            'methods' => 'POST',
            'callback' => array($this, 'get_all_dosings'),
        ));

        register_rest_route( 'masfuerza/v1', '/dosings/create', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_create_dosing'),
        ));  
    }

    /**
     *  Get all public dosings
     */
    public function get_all_dosings($request){
        $data = $request->get_json_params();
        return $this->dosing->get_all_dosing($data['user_id']);
    }


    public function handle_create_dosing($request) {
        $data = $request->get_json_params();
        return $this->dosing->create_dosing($data);
    }
}

