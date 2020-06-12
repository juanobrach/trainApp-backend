<?php


class Planification extends Controller{

    function __construct(){
        $this->dosing = new Dosing();
        $this->program = new Program();
    }

    /**
     * Get all planifications
     * ROUTINES [ days_per_week, heating, workouts ]
     * WORKOUTS [ workout, note?, super_serie?, dosing? ]
     *  */
    public function get_planifications($author_id=null, $athlete_id=null){
        $args = array(
            'post_type'        => 'planification',
            'posts_per_page'   => -1,
        );

        if( $author_id != null ) $args['author'] = $author_id;
        if( $athlete_id != null ) {
            $args['meta_key'] = "athlete";
            $args['meta_value'] = (int)$athlete_id;
            
        }

        $search_results = get_posts($args);
        
        $planifications = array();
        foreach( $search_results as $planification ){
            $planifications[] = $this->get_planification_by_id($planification->ID);
        }

        return $planifications;
    }


    public function get_planification_by_id($id){
        $planification = array();
        $routines = array();
        $data = get_post_meta($id);
        
        // print_r(get_field('field_5e8fb36555068', 3089));die;
        
        
        $athlete_id = $data['athlete'][0];

        $routines_amount = $data['routines_planification'][0];
        $author_id = $planification->post_author;
        $author_data = get_userdata($author_id)->data;
        $name = get_the_title($id);
        
        
        

        
        $routines_weekdays_acumulator = array();
        $routines_weekdays = array();
        
        for( $routine = 0; $routine < $routines_amount; $routine++ )
        {

            $workouts_amount = $data['routines_planification_'.$routine.'_workouts'][0];
            $routine_days_per_week = $data['routines_planification_'.$routine.'_days_per_week'][0];
            if( is_serialized( $data['routines_planification_'.$routine.'_heating'][0] )){
                $heating_id = (int)maybe_unserialize( $data['routines_planification_'.$routine.'_heating'][0] )[0];
            }else{
                $heating_id = (int)$data['routines_planification_'.$routine.'_heating'][0];
            }
            
            $heating_data = $this->get_data('heating',$heating_id);

            
            $completed_days = $data['routines_planification_'.$routine.'_progress_0_completed_days'][0];
            $actual_day = (int)$data['routines_planification_'.$routine.'_progress_0_actual_day'][0];
            $next_day = (int)$data['routines_planification_'.$routine.'_progress_0_next_day'][0];
            $actual_week =(int) $data['routines_planification_'.$routine.'_progress_0_actual_week'][0];
            $completed_weeks = $data['routines_planification_'.$routine.'_progress_0_completed_weeks'][0];
            

              if( is_serialized( $data['routines_planification_'.$routine.'_active'][0] )){
                $routine_active = (int)maybe_unserialize( $data['routines_planification_'.$routine.'_active'][0] )[0];
            }else{
                $routine_active = (int)$data['routines_planification_'.$routine.'_active'][0];
            }

            

            

            $progress = array(
                'daysCompleted'=> $completed_days,
                'actualDay' => $actual_day,
                'nextDay' => $next_day,
                'actualWeek'=> $actual_week,
                'completedWeeks'=> $completed_weeks
            );
            $routinesName = array('A', 'B','C','D','E','F','G');
            $routines[$routine] = array(
                'id'=> $routine,
                'name'=>$routinesName[$routine],
                'daysPerWeek'=> (int)$routine_days_per_week,
                'warmUpId' => $heating_id,
                'warmUpName'=> $heating_data['title'],
                'totalExercises'=> $workouts_amount,
                'progress'=>  $progress,
                'active'=>  ($routine_active === 1 ? true : false)
            );


            // Workouts
            for( $workout = 0; $workout < $workouts_amount; $workout++  ){
                if( is_serialized($data['routines_planification_'.$routine.'_workouts_'.$workout.'_super_serie']) ){

                }
                $super_workout_id = maybe_unserialize( [0] )[0];
                

                $super_workout_id = 0;
                if( is_serialized( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_super_serie'][0] ) ){
                    $super_workout_id = maybe_unserialize( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_super_serie'][0])[0];                    
                }else{
                    $super_workout_id = $data['routines_planification_'.$routine.'_workouts_'.$workout.'_super_serie'][0];
                }


                $super_workout_data = array();

                if( $super_workout_id > 0 ){
                    $super_workout_data = $this->get_data( 'exercise', $super_workout_id );
                }

                


                
                $workout_id = 0;
                if( is_serialized( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_workout'][0] ) ){
                    $workout_id = maybe_unserialize( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_workout'][0])[0];
                }else{
                    $workout_id = $data['routines_planification_'.$routine.'_workouts_'.$workout.'_workout'][0];

                }

                $workout_data = array();
                if((int) $workout_id > 1 ){
                    $workout_data = $this->get_data( 'exercise', $workout_id );
                }

                
                

                $note = maybe_unserialize( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_note'][0]);
                if(is_serialized( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_dosage_0_id'][0] )){
                    $dosage_id = (int) maybe_unserialize( $data['routines_planification_'.$routine.'_workouts_'.$workout.'_dosage_0_id'][0]);
                }else{
                    $dosage_id = (int)$data['routines_planification_'.$routine.'_workouts_'.$workout.'_dosage_0_id'][0];
                }

                $min_weeks = 4;
                $dosings = array();
                $weeks = array();
                for( $week=1; $week <= $min_weeks; $week++ ){
                    $week_data = array();
                    $dosage_week_field = 'routines_planification_'.$routine.'_workouts_'.$workout.'_dosage_'. 0 .'_weeks_'. ($week - 1) ;
                    
                    for( $day=1; $day <= $routine_days_per_week; $day++ ){

                        $day_field = $dosage_week_field . '_days_' . (string)($day - 1);
                                         
                        $week_data['days'][] = array(
                            'dosage'=> $data[  $day_field . '_charge'][0],
                            'series'=> $data[ $day_field . '_series'][0],
                            'charge'=>  $data[ $day_field . '_max'][0]
                        );
                    }
                    $weeks[] = $week_data;
                }
                $exercise_name = $workout_data['title'];
                $super_workout_name = $super_workout_data['title'];
                
                $dosage_data = $this->get_data( 'dosing', $dosage_id );
                
                $dosings['id'] = $dosage_id;
                $dosings['name'] = $dosage_data['title'];
                $dosings['weeks'] = $weeks;
                $routines[$routine]['exercises'][] = array(
                    'id'=>  (int)$workout_id,
                    'name'=> $exercise_name,
                    'superExerciseId' => $super_workout_id,
                    'superExerciseName'=> $super_workout_name,
                    'note'=>$note,
                    'dosings'=> $dosings,
                );

            }
        }

        
        $planification = array(
            'id'=> $id,
            'athleteId' => (int)$athlete_id,
            'programId'=> $data['program'][0],
            'programSku'=> $data['program_sku'][0],
            'name'=> $name,
            'trainerId' => $author_id,
            // 'routines_amount'=> $routines_amount,
            'routines'=> $routines,
            'active'=> (bool)$data['planification_active'][0],
            'finished'=> (bool)$data['planification_finished'][0],
            'sku' => $data['sku'][0]
        );
        return $planification;
    }

    /**
     *  Return an object with all fields with choices or relationships to the front-end.
     *  Some fields will be inside a 'sub_fields' objects and could have or not values. If the fields is a relationship we need to
     *  search for options.
     *
     */
    public function get_planification_fields(){


        global $wpdb;
        global $post;


        $planification_group_fields_id = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%plani%' AND post_type='acf-field-group' AND post_status='publish'");

        $planifications_fields = array();
        $fields = acf_get_fields( $planification_group_fields_id[0] );
        echo  json_encode( $fields );
        return;
    }


    // /**
    //  *  Update planification
    //  */
    // public function update_planification($new_data = null, $planification_id ){
        

    // }

    public function update_progress($routine_id, $planification_id, $days_per_week){
//print_r($planification_id);die;
        $data = array(
            'finished'=> 0,
            'data'=> array()
        );

        $next_progress = array(); 
        $actual_progress = get_field('routines_planification_' . $routine_id . '_progress', $planification_id );
        
        // Completed day
        $next_progress['actual_day'] = $actual_progress[0]['actual_day'] + 1;
        $next_progress['next_day'] = $next_progress['actual_day'] + 1;

        // Convert string to array and then add new day
        $completed_days = $actual_progress[0]['completed_days'];

        $completed_days_arr = preg_split("/[\s,]+/", $completed_days);
        if( $completed_days_arr[0] != "" ){
            $completed_days_arr[] = $actual_progress[0]['actual_day'];
            $completed_days = implode(', ', $completed_days_arr);
        }else{
            $completed_days = $actual_progress[0]['actual_day'];
        }        
        $next_progress['completed_days']  = $completed_days;        
        $completed_weeks = $actual_progress[0]['completed_weeks'];
        
        
        // Completed week
        if(  (int)$actual_progress[0]['actual_day'] === (int)$days_per_week ){
            $next_progress['actual_week'] = $actual_progress[0]['actual_week'] + 1;
           
            // WEEKS STRING
            $completed_weeks_arr = preg_split("/[\s,]+/", $completed_weeks);
            if( $completed_weeks_arr[0] != "" ){
                $completed_weeks_arr[] = $actual_progress[0]['actual_week'];
                $completed_weeks = implode(', ', $completed_weeks_arr);
            }else{
                $completed_weeks = $actual_progress[0]['actual_week'];
            }   

            $next_progress['completed_days']  = $completed_days;        
            $next_progress['completed_weeks'] = $completed_weeks;            
            $next_progress['actual_day'] = 1;
            $next_progress['next_day'] = 2;

        }
        
        if(  (  count ( explode ( ',', $next_progress['completed_weeks'] ) ) ) ===  4 ){
            $data['finished'] = true;
        }

        $data['data'] = $next_progress;
        return $data;
    }

    public function map_planification_fields($planification_data){        
        $routines = array();

        foreach($planification_data['routines'] as $routines_data ){
                                
            $workouts = array();

            // For each workout
            foreach( $routines_data['exercises'] as $exercise_data ){
                
                $dosage = array();
                $dosings = $exercise_data['dosings'];
                $dosage['id'] = $dosings['id'];
                foreach($dosings['weeks'] as $key => $week){
                    
                    foreach ($week['days'] as $key_week => $day) {
                                                                        
                        $dosage['weeks'][$key]['days'][] = array(
                            'charge'=> $day['dosage'],
                            'series'=> $day['series'],
                            'max'=> $day['charge'],

                        );  
                    }
                    
                }


                $workouts[] = array(
                    'workout'=>(int) $exercise_data['id'],
                    'dosage' => array( $dosage ),
                    'super_serie' => $exercise_data['superExerciseId'],
                    'note' => $exercise_data['note']
                );

            }

            $progress = array(
                'completed_days' => '',
                'next_day'=>2,
                'actual_day'=>1,
                'actual_week'=>1,
                'completed_weeks'=> ''
            );

            $routines[] = array(
                'id'=> $routines_data['id'],
                'days_per_week' => (int)$routines_data['daysPerWeek'],
                'heating' => $routines_data['warmUpId'],
                'workouts' => $workouts,
                'progress'=> array( $progress ),
                'active' => ($routines_data['active'] === true ? 1 : 0)
            );
            
        }

        

        $athletes = array();
        
        foreach ($program_data['athletes'] as $athlete ) {
            $athletes[] = $athlete['id'];
        }
            

        $program = array(
            'id'=> $program_data['id'],
            'routines'=> $routines

        );

        return $program;

    }

     /**
     *  Update planification
     */
    public function update_planification($new_planification, $planification_id ){
        // Update Routine

        
        $planification = $this->map_planification_fields($new_planification);

        // print_r($planification);die;
        

        
        $routines = $planification['routines'];


        $planification_post = array(
            'ID'           => $planification_id,
            'post_title'   => $new_planification['name']
        );
        wp_update_post($planification_post);
        
        
        

        delete_field('routines_planification', $planification_id);


        if( have_rows('routines_planification', $planification_id) ){

            while(   have_rows('routines_planification', $planification_id) ) : the_row();

            $row_index = get_row_index();

            foreach( $routines as $routine_row_number => $routine ){

                
                if( $row_index === $routine['id'] ){ 
                    $this->update_routine_days_per_week( $routine, $routines, $planification_id  );
                    $this->bulk_update_workout( $routine['workouts'], $routine['id'], $planification_id );
                }else{

                    
                    $this->create_routine( $routine, $routines ,$planification_id);
                }

                //        $this->update_workout( $exercise,  $routine['id'], $planification_id , $rowNumber );

            }
            endwhile;
        }else{

            foreach( $routines as $routine_row_number => $routine ){
                $this->create_routine( $routine, $routines, $planification_id );
            }

        }

        echo json_encode ( $this->get_planification_by_id(  $planification_id  ) );
        return ;


    }

    /**
     *  Fn to click on create routine inside an existing planification
     *
     */
    public function handle_create_routine( $request ){
        $data =  $request->get_json_params();
        $routine = $data['routine'];
        $routines = $data['routines'];

        $planification_id = $data['planification_id'];        
        $this->create_routine( $routine, $routines, $planification_id );

    }


    /**
     *  Fn to update a routine inside an existing planification
     *
     */
    public function handle_update_routine( $request ){
        $data =  $request->get_json_params();
        $routine = $data['routine'];
        $routines = $data['routines'];

        $planification_id = $data['planification_id'];
        $this->update_routine_days_per_week( $routine, $routines, $planification_id );
    }


    public function update_routine_days_per_week($routine,$routines, $planification_id){
        $max_day_for_week_exceeded = $this->max_day_for_week_exceeded($routine, $routines );

        if( $max_day_for_week_exceeded ){
            $error = new WP_Error( '001', 'Excediste la cantidad maxima de dias por semana para una planificacion', 'Some information' );
            return wp_send_json_error($error);
        }
        update_row( "routines_".($routine['id'] - 1)."_days_per_week", $routine['id'], $routine['days_per_week'], $planification_id);
    }


    // Add a new routine into a planification
    public function create_routine($routine_data, $planification_routines, $planification_id, $max=true ){
        
        unset($routine_data['id']);
        // print_r($routine_data);die;
        // print_r($routine_data);die;
        
        
        if ( $planification_id  <= 0  ){
            $error = new WP_Error( '001', 'No es posbile crear una rutina sin un ID de planificaicon', 'Some information' );
            return wp_send_json_error($error);
        }

        $max_day_for_week_exceeded = $this->max_day_for_week_exceeded($routine_data, $planification_routines );

        


        if( $max_day_for_week_exceeded && $max ){
            $error = new WP_Error( '001', 'Excediste la cantidad maxima de dias por semana para una planificacion', 'Some information' );
            return wp_send_json_error($error);
        }else{                
                add_row('routines_planification', $routine_data, $planification_id);
                //code...
        }

    }

    public function handle_update_workout($request){
        $data = $request->get_json_params();

        $workout = $data['workout'];
        $planification_id = $data['planification_id'];
        $routine_id  =  $data['routine_id'];
        $this->update_workout($workout, $routine_id ,$planification_id);
    }


    /**
     *  Update Workout
     * @param [ workout_id, dosing, super_serie, note ]
     */
    public function update_workout($workout, $routine_id, $planification_id){
        $workout_id = $workout['id'];
        $exercise = $workout['workout'];
        $dosing = $workout['dosings']['id'];
        $note = $workout['note'];
        $super_serie = $workout['super_serie'];

        

        update_field( "routines_".$routine_id."_workouts_".$workout_id."_workout", $exercise,  $planification_id);
        update_field( "routines_".$routine_id."_workouts_".$workout_id."_note", $note,  $planification_id);
        update_field( "routines_".$routine_id."_workouts_".$workout_id."_dosage", $dosing,  $planification_id);
        update_field( "routines_".$routine_id."_workouts_".$workout_id."_super_serie", $super_serie,  $planification_id);

    }


    public function handle_delete_workout($request){
        $data = $request->get_json_params();
        $workout_row_number = $data['workout_row_number'];
        $routine_row_number = $data['routine_row_number'];
        $planification_id = $data['planification_id'];

        $this->delete_workout($workout_row_number, $routine_row_number, $planification_id );
    }

    /**
     *  Delete workout from a specific routine inside a planification
     *  TODO: return the new ID's for each workout if exists.
     */
    public function delete_workout($workout_row_number, $routine_row_number, $planification_id){
        // delete_row( 'routines_0_workouts_0_workout', 1 ,$planification_id);
        $deleted = delete_row( 'routines_'.($routine_row_number - 1 ).'_workouts', $workout_row_number ,$planification_id  );

        print_r($deleted);die;
    }


    public function handle_add_workout($request){
        $data = $request->get_json_params();

        $routine_id = $data['routine_id'];
        $workout = $data['workout'];
        $planification_id = $data['planification_id'];

        $this->add_workout($routine_id, $planification_id, $workout);
    }

    public function add_workout($routine_id, $planification_id, $workout){

        if( $routine_id === "" || $planification_id <= 0 ){
            $error = new WP_Error( '001', 'No se encuentra ni el ID de la planificacion ni de la rutina', 'Some information' );
            return wp_send_json_error($error);
        }

        $row = array(
            'workout' => $workout['exercise'],
            'super_serie' => $workout['super_serie'],
            'note' => $workout['note']
        );

        add_row("routines_".$routine_id."_workouts", $row, $planification_id);

    }

    /**
     *  Check for days available to create the routine
     */
    public function max_day_for_week_exceeded($new_routine, $planification_routines){
        $max_day_for_week = 6;
        $days_counted = 0;
        
        foreach ($planification_routines as $routine ){
            if( $routine['id'] == $new_routine['id']  ) continue;
            $days_counted += $routine['days_per_week'];
        }
        $days_counted += $new_routine['days_per_week'];
        
        if($days_counted > 6 ){
            return true;
        }else{
            return false;
        }

    }

    public function prepare_routines_fields($program_routines){
        $routines = array();



        foreach ($program_routines as $routine ) {

            
            $workouts = array();
            foreach ($routine['exercises'] as $workout ) {


                $dosage = array();
                foreach ($workout['dosings']['weeks'] as $key => $dosing_weeks ) {
                   foreach ($dosing_weeks as $day ) {
                       $dosage['weeks'][$key]['days'][] = array(
                                'charge'=> $day,
                                'series'=> '',
                                'max'=>''
                       );

                    } 
                }                

                $workouts[] = array(
                    'workout'=> $workout['id'],
                    'super_serie'=> $workout['superWorkOutId'],
                    'note'=> $workout['name'],
                    'dosage'=> array( $dosage )
                );
            }

            $routines[] = array(
                'days_per_week'=> (int)$routine['daysPerWeek'],
                'heating'=> $routine['warmUpId'],
                'workouts' => $workouts,
                'progress'=>array(
                    'completed_days'=>'',
                    'actual_day'=> '1',
                    'next_day'=> '2',
                    'actual_week'=> '1',
                    'completed_weeks'=> '',
                    'active'=> false,
                ),
                'active'=> true,
                'finished'=>false
            );
        }

        return $routines;
    }

    /**
    *  Create a new planification
    *  This function could have all the data at once or perhaps only some data information about the planification.
    *  On th app a trainer could update routines, assignaments, and more.
    *  @name  []
    *  @athletes []
    *  @routines []
    *  @return planification_id
    *
    */
    public function create_planification($planification){
        $Program = new Program();
        
        $name = $planification['sku'];        
        $trainerId = $planification['trainerId'];
        $athlete_id = $planification['athleteId'];

        $program_sku = $planification['programSku'];
        $sku = $planification['sku'];
    
        $program_id = $Program->get_program_id($program_sku);
        
        
        // discount 1 plan available from trainer data
        
            
        
        $planification_data =  $this->map_planification_fields($planification);
        $routines = $planification_data['routines'];

        
        if ( empty( $program_sku ) ) {
            $error = new WP_Error( '001', 'No es posible crear una planificacion sin el SKU de un programa', 'Some information' );
            return wp_send_json_error($error);
        }

        if ( empty( $name ) ) {
            $error = new WP_Error( '002', 'La planificacion al menos debe tener un nombre', 'Some information' );
            return wp_send_json_error($error);
        }




        // Create the planification and return the id
        $planification_id = wp_insert_post(array(
            'post_type'=>'planification',
            'post_status' => 'publish',
            'post_title'=> $name,
            'post_author'=> (int)$trainerId
        ));

        


        // Add athletes
        update_field('athlete', $athlete_id, $planification_id);
        update_field('program',$program_id, $planification_id);
        update_field('planification_active', true, $planification_id);
        update_field('sku', $sku, $planification_id);
        update_field('program_sku', $program_sku, $planification_id);

        // Add athlete to program
        $assigned_athletes =  get_field('athletes', $program_id);
        $assigned_athletes[] = (int)$athlete_id;
        
        update_field('athletes', $assigned_athletes, $program_id); 

        // Add routines
        if( !empty($routines) ){
            foreach( $routines as $routine  ){
                $this->create_routine($routine, $routines, $planification_id, false);
            }
        }

        $planification = $this->get_planification_by_id($planification_id);
        echo  json_encode( $planification );      
        return;

    }


    public function get_planification_by_athlete_id( $athlete_id ){

        $planifications = $this->get_planifications( null, $athlete_id);               
        return $planifications;
    }

    

    public function get_active_planifications_by_trainer_id($trainer_id){
        $args = array(
            'post_author'        =>  $trainer_id,
            'numberposts' =>  -1,
            'post_type'		=> 'planification',
            'meta_key'		=> 'planification_active',
            'meta_value'	=> 1
        );

        $planifications = get_posts( $args );        
        return count( $planifications );
        
    }

    public function get_planifications_by_trainer_id($trainer_id){
        $args = array(
            'post_author'        =>  $trainer_id,
            'numberposts' =>  -1,
            'post_type'		=> 'planification',
            'meta_key'		=> 'planification_active',
            'meta_value'	=> 1
        );

        $wp_planifications = get_posts( $args );   
        $planifications = array();

        foreach( $wp_planifications as $planification ){
            $planifications[]  = $this->get_planification_by_id($planification->ID);
        }
        
        return  $planifications;
        
    }

    public function get_planification_by_sku($sku){
        $args = array(
            'numberposts' =>  1,
            'post_type'		=> 'planification',
            'meta_key'		=> 'sku',
            'meta_value'	=> $sku
        );

        $wp_planification = get_posts( $args );   
        
        return $this->get_planification_by_id($wp_planification[0]->ID);        
        
    }

    public function asign_routine($data){
        $planification_id = $data['planification_id'];
        $routine_id = $data['routine_id'];
        print_r($data);die;
    }

}