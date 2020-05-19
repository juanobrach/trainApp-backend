<?php


class Trainer extends Controller {

    function __construct(){
        $this->Athlete = new Athlete();
        $this->Program = new Program();
        $this->Membership = new Membership();
        $this->Ticket= new Ticket();
    }

    /**
     * Get all trainer data.
     * See (trainer.ts) model inside front/app
     * 
     *  */
    public function get_trainer_data($trainer_id, $username, $password){

        $Planification = new Planification(); 
        $athlete;
        $programs;
        $membership;

        $athletes   = $this->Athlete->get_athletes_by_trainer($trainer_id);
        $programs   = $this->Program->get_programs();
        $membership = $this->Membership->get_subscription_by_trainer_id($trainer_id);
        $planifications = $Planification->get_planifications_by_trainer_id($trainer_id);

        $user_credentials = array(
            'user_id' => $trainer_id,
            'username' => $username,
            'password'=> $password
        );

        
        $tickets = $this->Ticket->get_tickets($user_credentials);

    
        return array(
            'athletes'=> $athletes,
            'programs'=> $programs,
            'membership'=> $membership,
            'planifications'=> $planifications,
            'tickets'=> $tickets
        );
    }

}