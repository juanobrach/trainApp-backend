<?php

class TicketAPI {

    public function __construct(){
        $this->ticket = new Ticket();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/tickets/create_ticket', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_create_ticket'),
        ));

        register_rest_route( 'masfuerza/v1', '/tickets/reply_ticket', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_reply_ticket'),
        ));

        register_rest_route( 'masfuerza/v1', '/tickets/get_support_token', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_get_support_token'),
        ));

        register_rest_route( 'masfuerza/v1', '/tickets/get_tickets', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_get_tickets'),
        ));

    }


    public function handle_get_tickets($request){
        $data = json_decode ( $request->get_body() );
        return $this->ticket->get_tickets($data);
    }

    public function handle_reply_ticket($request){        
        $data = json_decode ( $request->get_body() );
        return $this->ticket->reply_ticket($data);
    }


    public function handle_create_ticket($request){
        $data = json_decode ( $request->get_body() );        
        return $this->ticket->create_ticket($data);
    }

    public function handle_get_support_token($data){
        $credentials = $data->get_json_params();
        return $this->ticket->get_support_token($credentials);
        
    }

}

