<?php

class Membership extends Controller{

    public function __construct(){
        $site_url = get_site_url();
        include('../wp-load.php'); //Guessing this path based on your code sample... should be wp root

        $this->Subscriptions_Manager = WC_Subscriptions_Manager;


    }


    public function get_subscription_by_trainer_id($trainer_id){

        $query_user_subscription = wcs_get_users_subscriptions($trainer_id);
        if( !empty($query_user_subscription) ){
            foreach($query_user_subscription as $user_subscription_object ){
                $subscription = $user_subscription_object->get_data();
            }            
        }
        return $subscription;        

    }

    public function get_all_subscriptions(){
        
        try{

            $query_all_subscriptions = $this->Subscriptions_Manager::get_all_users_subscriptions();
            $subscriptions = array();

            
            if( !empty($query_all_subscriptions) ){
                foreach( $query_all_subscriptions as $_subscription ){
                    $subscription = array();
                    
                    $product_id = $_subscription['product_id'];
                    $product_object = wc_get_product( $product_id );

                    $product = array(
                        'id' => $product_id, 
                        'name' => $product_object->get_name(),
                        'status' => $product_object->get_status(),
                        'description' => $product_object->get_description(),
                        'sku' => $product_object->get_sku(),
                        'price' => $product_object->get_price()
                    );

                    $order_object  = wc_get_order($_subscription['order_id']);
                    $order = $order_object->get_data();



                    $subscription = array(
                        'subscription_id' => $_subscription['order_id'],
                        'status'=> $_subscription['status'],
                        'period'=>$_subscription['period'],
                        'interval'=> $_subscription['interval'],
                        'start_date'=> $_subscription['start_date'],
                        'completed_payments'=> $_subscription['completed_payments'],
                        'last_payment_date'=> $_subscription['last_payment_date'],
                        'product'=> $product,
                        'order'=>$order
                    );

                    $subscriptions[] = $subscription;

                }
            }
            return $subscriptions;
                 
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    
}