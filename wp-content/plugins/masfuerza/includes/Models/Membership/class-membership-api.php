<?php

class MembershipAPI {

    public function __construct(){
        $this->membership = new Membership();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/memberships/subscriptions/all', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_subscriptions'),
        ));

        register_rest_route( 'masfuerza/v1', '/memberships/subscriptions/trainer/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_subscription_by_trainer_id'),
        ));


    }
        
    public function get_all_subscriptions( $request ){
        return $this->membership->get_all_subscriptions();
    }

    public function get_subscription_by_trainer_id( $request ){
        $trainer_id = $request['id'];        
        return $this->membership->get_subscription_by_trainer_id($trainer_id);
    }

}

