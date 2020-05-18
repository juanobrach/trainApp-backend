<?php

class TrainerAPI {

    public function __construct(){
        $this->init_routes();
        $this->trainer = new Trainer();
    }

    function init_routes(){

        register_rest_route( 'masfuerza/v1', '/trainer/get_trainer_data', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_get_trainer_data'),
        ));
        
    }

    public function handle_get_trainer_data($request){
       $data = json_decode ( $request->get_body() );
       $trainer_id = $data->trainer_id;
       $username = $data->username;
       $password = $data->password;       
       return $this->trainer->get_trainer_data($trainer_id, $username, $password);
    }
    
}

