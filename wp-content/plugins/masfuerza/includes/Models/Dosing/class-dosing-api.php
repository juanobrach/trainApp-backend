<?php

class DosingAPI {

    public function __construct(){
        $this->dosing = new Dosing();
        $this->init_routes();
    }

    function init_routes(){
        
        register_rest_route( 'masfuerza/v1', '/dosings', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_dosings'),
        ));   
    }

    public function get_all_dosings(){
        return $this->dosing->get_all_dosing();
    }
}

