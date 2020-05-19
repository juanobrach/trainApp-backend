<?php

class SubscriptionAPI {

    public function __construct(){
        $this->subscription = new Subscription();
        $this->init_routes();
    }

    function init_routes(){
        register_rest_route( 'masfuerza/v1', '/subscription/get_user_subscription', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_user_subscription_'),
        ));
    }

    public function get_user_subscription_(){
        return $this->subscription->get_user_subscription();
    }
}

