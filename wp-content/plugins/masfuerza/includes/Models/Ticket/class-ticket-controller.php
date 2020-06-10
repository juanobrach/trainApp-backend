<?php

class Ticket extends Controller{

    public function __construct(){

    }

    public function create_ticket($data){    
        
        $fields = array(
            'ticket_subject' => $data->ticket->subject,
            'ticket_description' => $data->ticket->message,
            'ticket_category'=> "1",
            'ticket_priority' => "43"
        );

        
        $url = get_site_url() . '/wp-json/supportcandy/v1/tickets/addRegisteredUserTicket';

        $headers = array( 
            'Content-type' => 'application/json',
        );

        $support_auth_user =  get_user_meta($data->userId,'support_auth_user', true);
        $support_auth_token =  get_user_meta($data->userId,'support_auth_token', true);

        // TODO: set env to secret key PROD/STAGE
        $body = array(
            'auth_user'=> $support_auth_user,
            'auth_token' => $support_auth_token,
            'fields_data'=> json_encode($fields)
        );
        $query_params = http_build_query($body);
            
        $response = wp_remote_post( $url . '/?'.$query_params, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );

        $response = json_decode( $response['body'] );
        $ticket_id = $response->ticket_id;
        
        $ticket = $this->get_ticket($data->userId, $ticket_id);
        return $ticket;
    }

    public function get_ticket($user_id, $ticket_id, $with_thread=true){
        
        $url = get_site_url() . '/wp-json/supportcandy/v1/tickets/' . $ticket_id;

        $support_auth_user =  get_user_meta( (int)$user_id,'support_auth_user', true);
        $support_auth_token =  get_user_meta( (int)$user_id,'support_auth_token', true);
        
        


        $headers = array( 
            'Content-type' => 'application/json',
        );


        // TODO: set env to secret key PROD/STAGE
        $body = array(
            'auth_user'=> $support_auth_user,
            'auth_token' => $support_auth_token
        );
        $query_params = http_build_query($body);
        $response = wp_remote_post( $url . '/?'.$query_params, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );
        $response = json_decode( $response['body'] );
        
        
       

        $ticket =  json_decode( json_encode($response), true);
        
        
        // Statues Query
        $statues_url = get_site_url() .'/wp-json/supportcandy/v1/statuses';
        $response_statues = wp_remote_post( $statues_url . '/'.$ticket['ticket_status'], array(
            'method'      => 'GET',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );

        $status = json_decode(  $response_statues['body'] );    
        

        $ticket = array(
            'id' => (int)$ticket['ticket_id'],
            'authCode' => $ticket['ticket_auth_code'],
            'created' => $ticket['date_created'],
            'updated' => $ticket['date_updated'],
            'subject' => $ticket['ticket_subject'],
            'message' => $ticket['ticket_description'],
            'status' => array(
                'id'=> (int)$ticket['ticket_status'],
                'message'=> $status->name,
            ),
            // 'thread' => $thread
        );
        // TODO : thread will be load when open message page
        if( $with_thread ){
            $thread = $this->get_thread($user_id, $ticket_id);
            $ticket['thread'] = $thread;
        }
        
        return $ticket;
        
    }

    public function get_tickets($user_id){

        $support_auth_user =  get_user_meta((int) $user_id,'support_auth_user', true);
        $support_auth_token =  get_user_meta( (int)$user_id,'support_auth_token', true);
      
        $url = get_site_url() . '/wp-json/supportcandy/v1/tickets';

        $headers = array( 
            'Content-type' => 'application/json',
        );
        
        $credentials = array(
            'auth_user'=> $support_auth_user,
            'auth_token'=> $support_auth_token
        );

        $query_params = http_build_query($credentials);

        $response = wp_remote_post( $url . '/?'.$query_params, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );

        $response = json_decode(  $response['body'] );
        
        $tickets = array();
        foreach( $response->tickets as $ticket ){            
            $ticket =  json_decode( json_encode($ticket), true);
           
            // Statues Query
            $statues_url = get_site_url() .'/wp-json/supportcandy/v1/statuses';
            $response_statues = wp_remote_post( $statues_url . '/'.$ticket['ticket_status'], array(
                'method'      => 'GET',
                'timeout'     => 45,
                'cookies'     => array(),
                'headers'     =>  $headers
                )
            );
    
            $status = json_decode( $response_statues['body'] );    


            $ticket = array(
                'id' => (int)$ticket['ticket_id'],
                'subject' => $ticket['ticket_subject'],
                'authCode' => $ticket['ticket_auth_code'],
                'created' => $ticket['date_created'],
                'updated' => $ticket['date_updated'],
                'message' => $ticket['ticket_description'],
                'status' => array(
                    'id'=> (int)$ticket['ticket_status'],
                    'message'=> $status->name,
                ),
                // 'thread' => $thread
            );
            $tickets[] = $ticket;
        }        
        
        return $tickets;
    }


    /**
     *  Get thread for ticket
     */

    public function get_thread($user_id, $ticket_id) {

        $support_auth_user =  get_user_meta( $user_id,'support_auth_user', true);
        $support_auth_token =  get_user_meta($user_id,'support_auth_token', true);
        
        $url = get_site_url() . '/wp-json/supportcandy/v1/tickets/'.$ticket_id.'/threads';
        
        $headers = array( 
            'Content-type' => 'application/json',
        );

        $credentials = array(
            'auth_user' => $support_auth_user,
            'auth_token' => $support_auth_token
        );

        $query_params = http_build_query($credentials);

        $response = wp_remote_post( $url . '/?'.$query_params, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );

        $response = json_decode ( $response['body'] );

        $threads = array();
        foreach( $response->threads as $thread ){         
            $_thread = json_decode( json_encode($thread), true );
            $_thread['thread_body'] = wp_filter_nohtml_kses( $_thread['thread_body'] );
            $threads[] = $_thread;
        }
        return array_reverse( $threads );
    }

	/**
	 *  Create credentials to manage support tickets
	 * @params [ user_id, user_password, support_plugin_token ]
	 * @return (string|string)[ auth_user, auth_token ]
	 */
	public function create_support_credentials( $credentials, $user_id = null ){
		
		$support_credentials = array(
			'auth_user' => '',
			'auth_token' => ''
		);

        
		$credentials = $this->get_support_token($credentials, $user_id);
		return $credentials;

	}


    public function reply_ticket($data){     
        $user_id = $data->userId;
        $ticket_id = $data->ticket;
        $message = $data->message;

        $url = get_site_url() . '/wp-json/supportcandy/v1/tickets/'.$ticket_id.'/addReply';

        $headers = array( 
            'Content-type' => 'application/json',
        );
        
        $auth_user = get_user_meta($user_id, 'support_auth_user', true);
        $auth_token = get_user_meta($user_id, 'support_auth_token', true);
        
        $body = array(
            'auth_user'=> $auth_user,
            'auth_token'=> $auth_token,
            'reply_body' => $message
        );

        $query_params = http_build_query($body);

        $response = wp_remote_post( $url . '/?'.$query_params, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );

        $response = json_decode( $response['body'] ); 
        if($response){
            $ticket = $this->get_ticket($user_id, $ticket_id);            
            return $ticket;
        }else{
            return $response;
        }
    }

    public function get_support_token($credentials, $user_id=null){
        
        $url = get_site_url() . '/wp-json/supportcandy/v1/login';

        $headers = array( 
            'Content-type' => 'application/json',
        );

        // TODO: set env to secret key PROD/STAGE
        $body = array(
            'username'=> $credentials['username'],
            'password'=> $credentials['password'],
            'secret_key'=> $credentials['secret_key']
        );

        

        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'body'        => json_encode($body),
            'cookies'     => array(),
            'headers'     =>  $headers
            )
        );

        $response = json_decode( $response['body'] );     
        update_user_meta( (int)$user_id, 'support_auth_user', $response->auth_user  );
        update_user_meta( (int)$user_id, 'support_auth_token', $response->auth_token);
        
        $credentials = array(
            'authUser'=> $response->auth_user,
            'authToken'=> $response->auth_token
        );

        

        
        return $credentials;
    }

}