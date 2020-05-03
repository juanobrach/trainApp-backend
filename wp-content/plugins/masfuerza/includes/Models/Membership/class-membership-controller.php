<?php

class Membership extends Controller{

    public function __construct(){
        $site_url = get_site_url();
        include('../wp-load.php'); //Guessing this path based on your code sample... should be wp root

        $this->Subscriptions_Manager = WC_Subscriptions_Manager;
        $this->Planification  = new Planification();

    }


    public function get_subscription_by_trainer_id($trainer_id){
        $subscription;
        $subscription_product;


        $query_user_subscription = wcs_get_users_subscriptions($trainer_id);
        if( !empty($query_user_subscription) ){
            foreach($query_user_subscription as $subscription ){

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
                $planifications_plan_amount = (int)$subscription_product['attributes'][0]['value'];
                $planifications_actives = $this->Planification->get_active_planifications_by_trainer_id($trainer_id);
                $asigned = $planifications_actives;
            

                $subscription = array(
                    'active'=> $subscription_data->status,
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
                            'total'=> $planifications_plan_amount
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
    
}