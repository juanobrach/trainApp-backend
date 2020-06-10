<?php
use \Firebase\JWT\JWT;


class Auth extends Controller{

    public function __construct(){ 
        $this->Ticket = new Ticket();
    }

    public function Login($data){
        // TODO: get back JWT login          
        // $url = get_site_url() . '/wp-json/jwt-auth/v1/token';

        // $headers = array( 
        //     'Content-type' => 'application/json',
        // );
        // $response = wp_remote_post( $url, array(
        //     'method'      => 'POST',
        //     'timeout'     => 45,
        //     'body'        => $data,
        //     'cookies'     => array(),
        //     'headers'     =>  $headers
        //     )
        // );
         
        // if ( is_wp_error( $response ) ) {
        //     $error_message = $response->get_error_message();
            
        // } else {


            // $response = json_decode( $response['body'] );            
            // if($response->message){
            //     return new WP_Error($response->message);
            //     // return $response->message;
            // }
 
            // $user_data = $response;
            $credentials = array(
                'user_login'=> $data['username'],
                'user_password' => $data['password']
            );
        
            $user = wp_signon($credentials);

            if( is_wp_error( $user )){
                
                $error = array(
                    'error'=> true,
                    'message'=> ''
                );

                if ( isset( $user->errors['incorrect_password'] )){
                    $error['message'] = $user->errors['incorrect_password'][0];
                }

                if ( isset( $user->errors['invalid_username'] )){
                    $error['message'] = $user->errors['invalid_username'][0];
                }
                return $error;
            }else{

                $user_meta = get_user_meta($user->data->ID);
                $user_data = get_userdata( $user->data->ID );
                

                $support_auth_user =  get_user_meta($data['user_id'],'support_auth_user', true);
                $support_auth_token =  get_user_meta($data['user_id'],'support_auth_token', true);
        
                
                if($support_credentials === false || $support_auth_token === false  ){
                    
                    $credentials  = array(
                        'username'=> $data['username'],
                        'password'=> $data['password'],
                        'secret_key'=> '5ebc434032a155ebc434032a17'
                    );
                    
                    
                    $support_token = $this->Ticket->create_support_credentials($credentials, $user->data->ID);
                    $support_auth_user = $support_token['authUser'];
                    $support_auth_token = $support_token['authToken'];
                    
                }


                //$token = $user->data->token;
                //$valid_token = $this->validate_token($token);
                //if( $valid_token === false  ) return false;
    
                $user = array(
                    'ID' => $user->data->ID,
                  //  'token' => $token,
                    'support' => array(
                        'auth_user'=> $support_auth_user,
                        'auth_token'=> $support_auth_token
                    ),
                    'first_name' => $user_meta['first_name'][0],
                    'last_name' => $user_meta['last_name'][0],
                    'username' => $user->data->user_nicename,
                    'email' => $user->data->user_email,
                    'roles' => $user_data->roles
                );                
                return $user;
            }
            

            
        // }
     
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


    function Change_password($data){
        $user_id = $data['userId'];
        $password = $data['password'];
        wp_set_password( $password, $user_id );
        return true;
    }

}