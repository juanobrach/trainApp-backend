<?php
use \Firebase\JWT\JWT;


class Auth extends Controller{

    public function __construct(){ 
        $this->key = JWT_AUTH_SECRET_KEY;
    }

    public function Login(){
        
        
        $token = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );
        
        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($token, $this->key);
        $data = array(

        );
        return $data;
    }

}