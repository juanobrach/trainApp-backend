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
    // TODO: if use again JWT change get_json_params to get body instead.
    public function Login($credentials){        
        return $this->auth->Login($credentials->get_json_params());
    }
}

