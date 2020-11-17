<?php
use \Firebase\JWT\JWT;


class Auth extends Controller{

    public function __construct(){ 
        $this->Ticket = new Ticket();
        $this->Membership = new Membership();
        
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

                // User exists, verify if it's an athlete and if it's status is active.

                
                $user_meta = get_user_meta( $user->data->ID);
                $user_data = get_userdata( $user->data->ID );
                
                if( in_array( 'athlete', (array) $user_data->caps ) ){
                        
                    if( isset($user_meta['user_is_active']) && boolval($user_meta['user_is_active'][0])  !== true ){                        
                        return   array(
                            'error'=> true,
                            'message'=> 'El usuario se encuentra inactivo'
                        );
                    }                
                }

                $user_address = get_usermeta(  $user->data->ID, 'address' );

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
                    'isLoggedIn'=>true,
                    'first_name' => $user_meta['first_name'][0],
                    'last_name' => $user_meta['last_name'][0],
                    'username' => $user->data->user_nicename,
                    'email' => $user->data->user_email,
                    'roles' => $user_data->roles,
                    'phone' => $user_meta['phone'][0],
                    'profile_picture' => $user_meta['user_profile_avatar'][0],
                    'address'=> array(
                        'country'=> '',
                        'state' => '',
                        'city' => ''
                    )
                );         
                
                if( $user_address ){
                    $user['address'] = $user_address;
                }
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


    public function update_user_data($user_id, $data){

        $user_address = $data['address'];
            
       $user_phone = $data['phone'];

        if( $user_phone != '' ){
            update_user_meta( $user_id, 'phone', $user_phone );
        }

        if( $user_address ){
            update_user_meta( $user_id, 'address', $user_address );
        }


        return $this->get_user_data( $user_id );

    }

    public function get_user_data( $user_id ){
        $user_meta = get_user_meta( $user_id);
        $user_data = get_userdata( $user_id );
        $user_address = get_usermeta(  $user_id, 'address' );

        $support_auth_user =  get_user_meta( $user_id,'support_auth_user', true);
        $support_auth_token =  get_user_meta( $user_id,'support_auth_token', true);
        

        //$token = $user->data->token;
        //$valid_token = $this->validate_token($token);
        //if( $valid_token === false  ) return false;

        $user = array(
            'ID' => $user_id,
          //  'token' => $token,
            'support' => array(
                'auth_user'=> $support_auth_user,
                'auth_token'=> $support_auth_token
            ),
            'isLoggedIn'=>true,
            'first_name' => $user_meta['first_name'][0],
            'last_name' => $user_meta['last_name'][0],
            'username' => $user->data->user_nicename,
            'email' => $user->data->user_email,
            'roles' => $user_data->roles,
            'phone' => $user_meta['phone'][0],
            'profile_picture' => $user_meta['user_profile_avatar'][0],
            'address'=> array(
                'country'=> '',
                'state' => '',
                'city' => ''
            )
        );         
        
        if( $user_address ){
            $user['address'] = $user_address;
        }
        return $user;
    }


    function Change_password($data){
        $user_id = $data['userId'];
        $password = $data['password'];
        wp_set_password( $password, $user_id );
        return true;
    }

    public function create_free_account($data){
        
        $user_credentials = array(
            "username" => $data['email'],
            "password" => $data['password'],
            "email"    => $data['email']
        );             

     
        

        $user_id = wp_create_user($user_credentials["username"],$user_credentials["password"],$user_credentials["email"]);
        if ( is_wp_error( $user_id ) ) {
            return array(
                'error'=> 'Ya existe un usuario con el correo seleccionado'
            );
        }else{
            $user = get_user_by('email', $data["email"] );
            update_user_meta( $user->ID, 'first_name',  $data["first_name"] );        
            update_user_meta( $user->ID, 'last_name',  $data["last_name"] ); 
            

            $u = new WP_User($user->ID);
            $roles = $u->roles;
            foreach($roles as $rol){
                $u->remove_role($rol);
            }
            $u->add_role( 'trainer');    
            $this->Membership->create_subscription($data, $user_id);
            
        }
        
    }

}