<?php

class Membership extends Controller{

    public function __construct(){
        $site_url = get_site_url();
        include(   ABSPATH . '/wp-load.php'); //Guessing this path based on your code sample... should be wp root

        $this->Planification  = new Planification();

    }

    public function wc_assign_custom_role($args){
        $args['role'] = 'trainer';
        return $args;
    }
    
    public function get_subscription_by_trainer_id($trainer_id){
        $subscription = array(
            'active'=> false
        );
        $subscription_product;


        $query_user_subscription = wcs_get_users_subscriptions($trainer_id);
        
        if( !empty($query_user_subscription) ){
            foreach($query_user_subscription as $subscription ){

                if ( sizeof( $subscription_items = $subscription->get_items() ) > 0 ) {
                    foreach ( $subscription_items as $item_id => $item ) {
                        $product = $item->get_product();

                        
                        $attributes = array();
                        foreach ($product->get_attributes() as $_attributes) {
                            $attributes[$_attributes->get_name()] = array( 
                                'name' => $_attributes->get_name(),
                                'value'=> $_attributes->get_options()[0]
                            );
                            
                            
                        }

                        $subscription_product = array(
                            'id'=> $product->get_id(),
                            'sku' => $product->get_sku(),
                            'name' => $product->get_name(),
                            'price'=> $product->get_price(),
                            'attributes'=> $attributes
                        );


                        
                        //Examples of use
                        $product->get_image('post-thumbnail', ['class' => 'alignleft'], false); // main image
                
                        $product_id = wcs_get_canonical_product_id( $item ); // get product id directly from item
                    }
                }



                $subscription_data = json_decode( json_encode( $subscription->get_data(), true ) );
                
                $isRecurrent = (  $subscription_data->requires_manual_renewal === true ? false : true );


                $planifications_plan_amount = (int)$subscription_product['attributes']['planificaciones']['value'];
                $planifications_actives = $this->Planification->get_active_planifications_by_trainer_id($trainer_id);
                $asigned = $planifications_actives;
            
                $is_active = ($subscription_data->status === 'active' ? true : false);

                
                // Return days left in negative, positive numbers represent exceded membership.
                $to   = strtotime( $subscription_data->schedule_next_payment->date );
                $NewDate = date('M j, Y', $to);                
                $diff = date_diff( date_create($NewDate),date_create(date("M j, Y")));
                $days_left = $diff->format('%r%a');


                // Format  'from' date
                $subscription_from = $subscription_data->date_created->date; 
                
                $subscription = array(
                    'active'=> $is_active,
                    'from' => date( 'j-m-Y',strtotime($subscription_data->date_created->date) ),
                    'to' =>  date( 'j-m-Y', strtotime($subscription_data->schedule_next_payment->date)),
                    'daysLeft' => (int) $days_left,
                    'woocommerceId'=> $subscription_data->id,
                    'paymentInformation'=> array(
                        'method'=> $subscription_data->payment_method,
                        'isRecurrent'=> $isRecurrent
                    ),
                    'product'=> $subscription_product,
                    'services'=> array(
                        'planifications'=> array(
                            'asigned' => $asigned,
                            'availables'=> $planifications_plan_amount - $asigned,
                            'total'=> $planifications_plan_amount
                        ),
                        'messages' => array(
                            'total'=> (int)$subscription_product['attributes']['consultas']['value']
                        )   
                    )

                );  
            }            
        }



        return $subscription;        

    }

    public function get_all_subscriptions(){
        $subscription;
        $subscription_product;


        $query_user_subscription = wcs_get_users_subscriptions();
        if( !empty($query_user_subscription) ){
            
            
            
            foreach($query_user_subscription as $subscription ){

                
                $product;
                if ( sizeof( $subscription_items = $subscription->get_items() ) > 0 ) {
                    foreach ( $subscription_items as $item_id => $item ) {
                        $product = $item->get_product();

                        
                        $attributes = array();
                        foreach ($product->get_attributes() as $_attributes) {
                            $attributes[] = array( 
                                'name' => $_attributes->get_name(),
                                'value'=> $_attributes->get_options()[0]
                            );
                            
                            
                        }

                        $subscription_product = array(
                            'id'=> $product->get_id(),
                            'sku' => $product->get_sku(),
                            'name' => $product->get_name(),
                            'price'=> $product->get_price(),
                            'attributes'=> $attributes
                        );


                        
                        //Examples of use
                        $product->get_image('post-thumbnail', ['class' => 'alignleft'], false); // main image
                
                        $product_id = wcs_get_canonical_product_id( $item ); // get product id directly from item
                    }
                }

                            
                $subscription_data = json_decode( json_encode( $subscription->get_data(), true ) );
                
                

                $isRecurrent = (  $subscription_data->requires_manual_renewal === true ? false : true );
                $isActive =  ( $subscription_data->status === 'active' ? true : false );
                $planifications_plan_amount = $subscription_product['attributes'][0]['value'][0];
                $planifications_actives = $this->Planification->get_active_planifications_by_trainer_id($trainer_id);
                $asigned = $planifications_actives;
                            
                $subscription = array(
                    'active'=> $isActive,
                    'from' => $subscription_data->date_created->date,
                    'to' =>  $subscription_data->schedule_next_payment->date,
                    'woocommerceId'=> $subscription_data->id,
                    'paymentInformation'=> array(
                        'method'=> $subscription_data->payment_method,
                        'isRecurrent'=> $isRecurrent
                    ),
                    'product'=> $subscription_product,
                    'services'=> array(
                        'planifications'=> array(
                            'asigned' => $asigned,
                            'availables'=> $planifications_plan_amount - $asigned,
                            'total'=> (int)$planifications_plan_amount
                        )   
                    )

                );  
            }            
        }



        return $subscription;        
    }

    public function create_subscription($data, $user_id){

        $sku = 'BASIC30';
        $address = array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => '',
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'postcode'   => '',
        );
    
        $order = wc_create_order( array( 'customer_id' => $user_id ) ); // Create a WC_Order object and save it.
        update_post_meta($order->id, '_customer_user', $user_id);

    
        $order->set_address( $address, 'billing' ); // Set customer billing adress
        
        $product = wc_get_product( wc_get_product_id_by_sku( $sku ) );
        $order->add_product( $product, 1 ); // Add an order line item
        
        // Set payment gateway
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $order->set_payment_method( $payment_gateways['cod'] );
        
        $order->calculate_totals(); // Update order taxes and totals
        $order->update_status( 'completed', 'In Store ', true ); // Set order status and save
        $period = WC_Subscriptions_Product::get_period( $product );
        $interval = WC_Subscriptions_Product::get_interval( $product );
        
        $start_date = gmdate( 'Y-m-d H:i:s' );
        $sub = wcs_create_subscription(array(
            'order_id' => $order->get_id(), 
            'status' => 'pending', // Status should be initially set to pending to match how normal checkout process goes
            'billing_period' => $period, 
            'billing_interval' => $interval, 
            'start_date' => $start_date
        ));

        if( is_wp_error( $sub ) ){
            return false;
        }
        
        
        
        $sub->add_product( $product, 1 );
        $sub->update_status( 'active', $note, true );
        return $sub;
    }
    
}