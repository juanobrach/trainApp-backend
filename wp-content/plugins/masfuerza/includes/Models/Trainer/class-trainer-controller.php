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
    public function get_trainer_data($trainer_id){

        $Planification = new Planification(); 
        $athlete;
        $programs;
        $membership;

        $athletes   = $this->Athlete->get_athletes_by_trainer($trainer_id);
        $programs   = $this->Program->get_programs($trainer_id);
        $membership = $this->Membership->get_subscription_by_trainer_id($trainer_id);
        $planifications = $Planification->get_planifications_by_trainer_id($trainer_id);        
        // TODO: tickets use a lot of space on localStorage. Use indexdb instead or capacitor LocalForage.
        // $tickets = $this->Ticket->get_tickets($trainer_id);

    
        return array(
            'athletes'=> $athletes,
            'programs'=> $programs,
            'membership'=> $membership,
            'planifications'=> $planifications,
            // 'support'=> array(
            //     'loading'=> false,
            //     'tickets'=> $tickets
            // )
        );
    }

}