<?php
use \Firebase\JWT\JWT;


class Auth extends Controller{

    public function __construct(){ 
        $this->Ticket = new Ticket();
    }

    public function Login($credentials){
                
        $url = get_site_url() . '/wp-json/jwt-auth/v1/token';

        $headers = array( 
            'Content-type' => 'application/json',
        );
        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'body'        => $credentials,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );
         
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            
        } else {


            $response = json_decode( $response['body'] );            
            if($response->message){
                return new WP_Error($response->message);
                // return $response->message;
            }

            $user_data = $response;
            $user_meta = get_user_meta($user_data->ID);
            
            $token = $user_data->token;
            $valid_token = $this->validate_token($token);
            if( $valid_token === false  ) return false;

            $user = array(
                'ID' => $user_data->ID,
                'token' => $token,
                'first_name' => $user_meta['first_name'][0],
                'last_name' => $user_meta['last_name'][0],
                'username' => $user_data->user_nicename,
                'email' => $user_data->user_email,
                'roles' => $user_data->roles
            );

            return $user;
            
        }
     
    }

    function validate_token($token){
                       
        $url = get_site_url() . '/wp-json/jwt-auth/v1/token/validate';

        $headers = array( 
            'Content-type' => 'application/json',
            'Authorization'=> "Bearer " . $token
        );

        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );
        
        $response = json_decode ( $response['body'] );

        if( $response->code === 'jwt_auth_invalid_token' ) return false;
        
        return true;         
    }

}