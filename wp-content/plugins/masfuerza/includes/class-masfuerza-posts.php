<?php

/**
 * Define the posts functionality
 *
 * Loads and defines the posts files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/juanobrach
 * @since      1.0.0
 *
 * @package    Masfuerza
 * @subpackage Masfuerza/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Masfuerza
 * @subpackage Masfuerza/includes
 * @author     JuanObrach <juanobrach@gmail.com>
 */
class Masfuerza_Posts {



	public function on_edit_post( $post ) {
		$screen = get_current_screen();
		
		if (! empty($_GET['post']) )
		{
			$post = get_post($_GET['post']);			
			if (! empty($post->post_content) )
			{
				update_field('name', $post->post_title, $post->ID);
			}
		}

	} 

	/**
	 * Hook when post is saved and save the field "name" as a WP title
	 *
	 * @since    1.0.0
	 */
	public function custom_type_save_post( $post_id ) {
		$post_type = get_post_type( $post_id );
		
		$data = array(
			'ID' => $post_id
		);

		switch($post_type){
			case 'athlete':
			case 'instructor':
				$title              = get_field( 'first_name', $post_id ); 
				$data['post_title'] = $title;
				$data['post_name']  = sanitize_title( $title );
				wp_update_post( $data );
			break;
			case 'heating':
				$title              = get_field( 'name', $post_id ); 
					$data['post_title'] = $title;
					$data['post_name']  = sanitize_title( $title );
					// Set a category from custom category field
					$category = get_field('category', $post_id);
					if( !empty( (array) $category) ){
						wp_set_object_terms($post_id, $category->slug,'exercise_category',true);
					}					
					wp_update_post( $data );
			break;
			case 'exercise':
				
				// Set a title from custom name field
				$title              = get_field( 'name', $post_id ); 
				$data['post_title'] = $title;
				$data['post_name']  = sanitize_title( $title );

				

				// Set a category from custom category field
				$category = get_field('category', $post_id);
				if( !empty( (array) $category) ){
					wp_set_object_terms($post_id, $category->slug,'exercise_category',true);
				}
				wp_update_post( $data );
			break;
			case 'planification':
				$user              = get_field( 'athlete', $post_id );
				$user_nickname = $user['nickname'];
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$today = date('m/d/Y h:i:s a', time());
				$title = $user_nickname ."  #". $today;
				$data['post_title'] = $title;
				$data['post_name']  = sanitize_title( $title );
				wp_update_post( $data );
			break;
			case 'program':
				$title              = get_field( 'name', $post_id ); 
				$data['post_title'] = $title;
				$data['post_name']  = sanitize_title( $title );
				wp_update_post( $data );
			break;
		}
	}

	public function get_own_trainer_athletes (  $args, $field, $options_post_id    ){
		// Show athletes owner by current trainner
		$current_user = wp_get_current_user()->data; 
		if( $field['name']  === "athlete"){
			if( !is_admin() ){
				// If user is not and admin filter only athletes on their own
				$args["meta_query"] = array(
					array(
						'key' => 'trainer',
						'value' => (string)$current_user->ID,
						'compare' => '==',
						'type' => 'STRING'
						)
					);
				}
			}
			
			
		return $args;
	}

	/**
	 *  Asign athlete creted to the current user logged in. It should a trainer createing a new user as their
	 *  athlete.
	 */
	public function registration_save( $user_id ) {
		
		$user_data=get_userdata($user_id);
		
		
		$user_roles=$user_data->roles;
		if( in_array('atleta', $user_roles) ){
			if ( isset( $_POST['first_name'] ) )
				update_user_meta($user_id, 'trainer', wp_get_current_user()->data->ID  );
		}
		
 
	}



	public function after_password_reset( $user_id ) {
		

				$url = home_url();
				$production_url = "api.masfuerza";
				$stage_url  = "stage.api.masfuerza";
				$local_url  = "local.masfuerza";

				$production_app_url = "https://trainapp.masfuerza.net";
				$stage_app_url = "https://stage.trainapp.masfuerza.net";
				$local_app_url = "http://local.trainapp.com";

				$redirect_to_url = $production_app_url;
			
				// Production
				if (strpos($url, $production_url) !== false) {
					$redirect_to_url = $production_app_url;
				}

				// Stage
				if (strpos($url, $stage_url) !== false) {
					$redirect_to_url = $stage_app_url;
				}

				// Local
				if (strpos($url, $local_url) !== false) {
					$redirect_to_url = $local_app_url;
				}
		
				wp_redirect( $redirect_to_url ); 
				exit;
		
 
	}
	


}
