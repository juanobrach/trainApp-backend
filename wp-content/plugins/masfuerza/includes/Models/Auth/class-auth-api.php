<?php

class AuthAPI {

    public function __construct(){
        $this->auth = new Auth();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/login', array(
            'methods' => 'POST',
            'callback' => array($this, 'Login'),
        ));

    }

    public function Login($credentials){        
        return $this->auth->Login($credentials->get_body());
    }
}

