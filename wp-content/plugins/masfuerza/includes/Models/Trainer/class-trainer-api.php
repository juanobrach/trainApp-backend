<?php

class TrainerAPI {

    public function __construct(){
        $this->init_routes();
        $this->trainer = new Trainer();
    }

    function init_routes(){

        register_rest_route( 'masfuerza/v1', '/trainer/get_trainer_data', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_get_trainer_data'),
        ));

        register_rest_route( 'masfuerza/v1', '/trainer/upload_image_profile', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_upload_image_profile'),
        ));
        
    }

    public function handle_upload_image_profile($request){
       $user_id = $_POST['userId'];
       require_once(ABSPATH . "wp-admin" . '/includes/image.php');
       require_once(ABSPATH . "wp-admin" . '/includes/file.php');
       require_once(ABSPATH . "wp-admin" . '/includes/media.php');

       $imagetype = array(
           'bmp'  => 'image/bmp',
           'gif'  => 'image/gif',
           'jpe'  => 'image/jpeg',
           'jpeg' => 'image/jpeg',
           'jpg'  => 'image/jpeg',
           'png'  => 'image/png',
           'tif'  => 'image/tiff',
           'tiff' => 'image/tiff'
       );

       $override = array(
           'mimes'     => $imagetype,
           'test_form' => false
       );
       
      foreach( $_FILES as $file ){
            $upload_file = wp_handle_upload( $file, $override );
            remove_filter( 'upload_dir', array($this, 'change_upload_dir') );
            if ( isset( $upload['error'] ) ){
                // TODO: handle errors
                return $upload['error'];
            } else {
                $uploaded_file_url = $upload_file['url'];
                update_user_meta( $user_id, "user_profile_avatar", $uploaded_file_url );
            }
        }
        return array('file_url'=> $uploaded_file_url );
    }

    public function handle_get_trainer_data($request){
        $data = json_decode ( $request->get_body() );
        $trainer_id = $data->trainer_id;
        return $this->trainer->get_trainer_data($trainer_id);
     }


    
}

