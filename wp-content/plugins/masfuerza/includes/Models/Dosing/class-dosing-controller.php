<?php


class Dosing extends Controller{

    public function __construct(){ }

    public function get_all_dosing(){
        $data = array();
        $search_results = get_posts(array( 
            'posts_per_page'   => -1,
            'post_type'=> 'dosing'));

        foreach( $search_results as $dosing ){
            $dosage_id = $dosing->ID;
            
            $meta_data = get_post_meta($dosage_id);
            
            $trainee_per_week = $meta_data['trainee_per_week'][0];
            $charge_type      = $meta_data['charge_type'][0];




            
            $total_weeks = 4;
            $weeks = array();

            for( $week = 1; $week <= $total_weeks; $week++ ){
                // $data[$dosage_id]["weeks"][$week] = array();
                $dosing_days = array();
                for( $day = 1; $day <= $trainee_per_week ; $day++ ){
                    $field_name = "programa_".$trainee_per_week."_weekdays_semana_". $week .'_day_'.$day;
                    $daily_dosing_value = $meta_data[$field_name][0]; 
                    // $data[$dosage_id]["weeks"][$week]['days'][] = $daily_dosing_value;                     
                    $dosing_days[] = $daily_dosing_value; 
                }
                $weeks[] = $dosing_days;
            }

            $data[] = array(
                'id' => $dosage_id,
                'name'=> $dosing->post_title,
                'traineePerWeek'=> (int)$trainee_per_week,
                'chargeType' => $charge_type,
                'type' => 'standard',
                'weeks' => $weeks
            );                   
        }
        return $data;
    }

    public function get_dosing($dosage_id){
        $data = array();
        $search_results = get_posts(array( 'post_type'=> 'dosing', 'post__in'=> array($dosage_id)));

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
                'weeks' => $weeks
            );
                   
        }
        return $data;
    }
}