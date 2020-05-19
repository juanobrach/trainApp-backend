<?php
use \Firebase\JWT\JWT;


class Subscription extends Controller{

    public function __construct(){ 
    }

    public function get_user_subscription(){
        
    //    // Get all customers subscriptions
    //     $customer_subscriptions = get_posts( array(
    //         'numberposts' => -1,
    //         // 'meta_key'    => '_customer_user',
    //         // 'meta_value'  => get_current_user_id(), // Or $user_id
    //         'post_type'   => 'shop_subscription', // WC orders post type
    //         'post_status' => 'wc-active' // Only orders with status "completed"
    //     ));

    //     foreach( $customer_subscriptions as $customer_subscription ){
    //         $subscription_id = $customer_subscription->ID;
    //         $subscription = new WC_Subscription( $subscription_id );
    //         // Or also you can use
    //         // wc_get_order( $subscription_id );    
    //         $subscription_products = $subscription->get_items();
    //         $order_products = $subscription_products[0];
    //         $order_data = $subscription_products[1]->get_data();
    //         return $order_data;
    //     }
    }

    public function cancel_suscription(){}
}