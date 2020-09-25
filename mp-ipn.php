<?php
    require __DIR__ . '/vendor/autoload.php';
    require_once("./wp-load.php");
    
    $host = $_SERVER['HTTP_HOST'];
    $is_production = false;
    // Set token test by default
    
    $token = get_option('_mp_public_key_test');
    if( strpos($host, 'api.masfuerza')  ){
      $is_production = true;
    }
    

    if( strpos($host, 'stage.api.masfuerza')  ){
      $is_production = false;
    }

    if($is_production){
      $token =  get_option('_mp_access_token_prod');
    }
    
    
    MercadoPago\SDK::setAccessToken($token);

    
    $merchant_order = null;
    switch($_GET["topic"]) {
        case "payment":
            $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
            // Get the payment and the corresponding merchant_order reported by the IPN.
            $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
            break;
        case "merchant_order":
            $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
            break;
    }

    $paid_amount = 0;
    foreach ($merchant_order->payments as $payment) {
        if ($payment['status'] == 'approved'){
            error_log("Aprobado \n", 3, "./ipn.log");
            $paid_amount += $payment['transaction_amount'];
        }
    }

    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
    if($paid_amount >= $merchant_order->total_amount){
        if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
            if($merchant_order->shipments[0]->status == "ready_to_ship") {
                print_r("Totally paid. Print the label and release your item.");
            }
        } else { // The merchant_order don't has any shipments
            print_r("Totally paid. Release your item.");
        }
    } else {
        print_r("Not paid yet. Do not release your item.");
    }

?>