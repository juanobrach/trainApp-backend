<?php

class AuthAPI {

    public function __construct(){
        $this->auth = new Auth();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/login', array(
            'methods' => 'GET',
            'callback' => array($this, 'do_login'),
        ));
    }

    public function do_login(){
        return $this->auth->Login();
    }
}

