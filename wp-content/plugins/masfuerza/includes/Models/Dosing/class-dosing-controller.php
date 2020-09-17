<?php


class Dosing extends Controller{

    public function __construct(){ }

    public function get_all_dosing($user_id = null){
        $data = array();

        global $wpdb;
        $standards = $wpdb->get_results( "SELECT p.* FROM {$wpdb->posts} p, {$wpdb->usermeta} u"
        ." WHERE    p.post_type     = 'dosing'"
        ." AND      p.post_status   = 'publish'"
        ." AND      u.user_id       = p.`post_author`"
        ." AND      u.meta_key      = 'wp_capabilities'"
        ." AND      u.meta_value    NOT LIKE '%trainer%'"
        );

        
        

        if( $user_id ){
            $personalized = $wpdb->get_results( "SELECT p.* FROM {$wpdb->posts} p"
            ." WHERE    p.post_type     = 'dosing'"
            ." AND      p.post_status   = 'publish'"
            ." AND      p.post_author       = $user_id"
            );
            foreach( $personalized as $dosing ){
                $dosage_id = $dosing->ID;
                $dosage = $this->get_dosing($dosage_id, 'personalized');
                $data[] = $dosage;
                
            }
        }

      

        foreach( $standards as $dosing ){
            $dosage_id = $dosing->ID;
            $dosage = $this->get_dosing($dosage_id, 'standard');
            $data[] = $dosage;
            
        }

        

        // Get personalized

        
        return $data;
    }

    public function get_dosing($dosage_id, $type=null){
        $data = array();
        $search_results = get_posts(array(  'posts_per_page'   => -1, 'post_type'=> 'dosing', 'post__in'=> array($dosage_id)));

        foreach( $search_results as $dosing ){
            $dosage_id = $dosing->ID;
            $data[] = array();
            
            $meta_data = get_post_meta($dosage_id);
            $trainee_per_week = $meta_data['trainee_per_week'][0];
            $charge_type      = $meta_data['charge_type'][0];
            $total_weeks = 4;

            $weeksDosings  = array(); 
            $weeks = array();
            for( $week = 1; $week <= $total_weeks; $week++ ){
                $weekData = array();
                for( $day = 1; $day <= $trainee_per_week ; $day++ ){
                    $field_name = "programa_".$trainee_per_week."_weekdays_semana_". $week .'_day_'.$day;
                    $daily_dosing_value = $meta_data[$field_name][0]; 
                    $weekData[] = $daily_dosing_value; 
                }
                $weeks[] = $weekData;
            }
            

            $data = array(
                "id"=> $dosage_id,
                "traineePerWeek" => (int)$trainee_per_week,
                "chargeType" => $charge_type,
                'name' =>  $dosing->post_title,
                'weeks' => $weeks,
                'type'=> $type
            );
                   
        }
        return $data;
    }

    public function create_dosing($data){
        $trainer_id = $data['trainer_id'];
        $dosage = $data['dosage'];
        $trainee_per_week = $dosage['trainee_per_week'];
        $dosage_name = $dosage['name'];
        $weeks = $dosage['weeks'];

        // Create the planification and return the id
        $dosage_id = wp_insert_post(array(
            'post_type'=>'dosing',
            'post_title'=> $dosage['name'],
            'post_status' => 'publish',
            'post_author'=> (int)$trainer_id
         ));
         update_field('trainee_per_week', $trainee_per_week, $dosage_id);



         if( $trainee_per_week === 3 ){
            foreach( $weeks as $week_index => $week ){
                foreach( $week as $day_index => $day ){
                    update_field('programa_3_weekdays_semana_'.$week_index+1 .'_day_' . $day_index+1, $day, $dosage_id);
                }
            }
         }

         if( $trainee_per_week === 2 ){
            foreach( $weeks as $week_index => $week ){
                $week_index =  $week_index+1;
                foreach( $week as $day_index => $day ){
                    $day_index = $day_index + 1;
                    update_field('programa_2_weekdays_semana_'.$week_index .'_day_' . $day_index, $day, $dosage_id);
                }
            }
        }

        return $this->get_dosing( $dosage_id, 'personalized' );
    }
}