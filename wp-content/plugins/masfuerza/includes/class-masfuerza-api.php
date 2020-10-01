<?php

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
		header("Access-Control-Allow-Origin: *"); 
		$Auth_api = new AuthAPI();
		$Planification_api = new PlanificationAPI();
		$Program_api = new ProgramAPI();
		$Dosing_api = new DosingAPI();
		$Heating_api = new HeatingAPI();
		$Exercise_api = new ExerciseAPI();
		$Subscription_api = new SubscriptionAPI();
		$Athlete_api = new AthleteAPI();
		$Membership_api = new MembershipAPI();
		$Trainer_api = new TrainerAPI();
		$Ticket_api = new TicketAPI();
	}


	/**
	 * Filter JWT login to return user data based on the role. 
	 * Add roles into the login response
	 */
	public function filter_jwt_auth($data){
		$user = get_user_by('email', $data['user_email']);  		
		$data['roles'] =  $user->roles;
		$data['ID'] = $user->ID;

		foreach( $user->roles as $user_rol  ){
			if($user_rol === "trainer"){
				// get data for trainer
				$athletes = array();
				$planifications = array();
				$messages = array();
				/**
				 *  System data should return
				 * 	Exercises, Heatings and Dosings
				 */
				$system_data = array();
			}

			if($user_rol === "athlete"){
				// get data for athlete
				$planifications = array();
			}

		}
		return $data;
	}

}
