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

        register_rest_route( 'masfuerza/v1', '/change_password', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_change_password'),
        ));

    }
    // TODO: if use again JWT change get_json_params to get body instead.
    public function Login($credentials){        
        return $this->auth->Login($credentials->get_json_params());
    }

    public function handle_change_password($request){
        $data = $request->get_json_params();
        return $this->auth->Change_password($data);
    }
}

