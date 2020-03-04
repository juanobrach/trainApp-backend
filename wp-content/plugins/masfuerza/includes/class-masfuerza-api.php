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
 * Define the API functionality.
 *
 * @since      1.0.0
 * @package    Masfuerza
 * @subpackage Masfuerza/includes
 * @author     JuanObrach <juanobrach@gmail.com>
 */
class Masfuerza_Api {


	/**
	 * Init all api routes available
	 *
	 * @since    1.0.0
	 */
	public function init_api( ) {
		$Auth_api = new AuthAPI();
        $Planification_api = new PlanificationAPI();
        $Dosing_api = new DosingAPI();
        $Heating_api = new HeatingAPI();
		$Exercise_api = new ExerciseAPI();
		$Subscription_api = new SubscriptionAPI();

      
	}

	/**
	 *  Add roles into the login response
	 */
	public function filter_jwt_auth($data){
		$user = get_user_by('email', $data['user_email']); 
		$data['roles'] =  $user->roles;
		return $data;
	}

}
