<?php


class Program extends Controller{

    function __construct(){
        $this->dosing = new Dosing();
    }

    /**
     * Get all planifications
     * ROUTINES [ days_per_week, heating, workouts ]
     * WORKOUTS [ workout, note?, super_serie?, dosing? ]
     *  */    
    public function get_programs($author_id=null){
        $args = array(
            'post_type'        => 'program'
        );
        
        if( $author_id != null ) $args['author'] = $author_id;

        $search_results = get_posts($args);
        
        
        $programs = array();
        foreach( $search_results as $program ){
            $routines = array();
            $data = get_post_meta($program->ID);

            
            $_athletes = maybe_unserialize( $data['athletes'][0] );
            $athletes = array();
            if( $_athletes ) {
                
                foreach( $_athletes as $athlete_id ){
                    
                    $athlete_meta    = get_user_meta($athlete_id);
                    $athletes[] = array(
                        'id'=> $athlete_id,
                        'firstName' => $athlete_meta['first_name'][0],
                        'lastName' => $athlete_meta['last_name'][0]
                    );
                }
            }

            
            $routines_amount = $data['routines'][0];
            $author_id = $program->post_author;
            $author_data = get_userdata($author_id)->data;
            

            $routines_weekdays_acumulator = array();
            $routines_weekdays = array();
            $routinesName = array('A', 'B','C','D','E','F','G');
            for( $routine = 0; $routine < $routines_amount; $routine++ ){
                $workouts_amount = $data['routines_'.$routine.'_workouts'][0];
                $routines_weekdays_selected = maybe_unserialize( $data['routines_'.$routine.'_weekdays'][0] ); 
                $routine_days_per_week_string = $data['routines_'.$routine.'_days_per_week'][0];
                $routine_days_per_week_number = strpos( $routine_days_per_week_string, '2') !== false ? 2 : 3;
                $routine_workouts_amount = $data['routines_'.$routine.'_workouts'][0];
                $heating_id = maybe_unserialize( $data['routines_'.$routine.'_heating'][0] )[0];
                $heating_data = $this->get_data('heating',$heating_id);
                $routines[$routine] = array(
                    'name' => $routinesName[$routine],
                    'warmUpId' => $heating_id,
                    'warmUpName' => $heating_data['title'],
                    'daysPerWeek'=> $routine_days_per_week_number,
                    'weekday' => $routines_weekdays_selected,
                    'totalExercises' => $workouts_amount
                );
                foreach( $routines_weekdays_selected as $day ){
                    $routines_weekdays_acumulator[$day] = $day;
                }
                
                $routines_weekdays = array_merge( $routines_weekdays, $routines_weekdays_acumulator );

                for( $workout = 0; $workout < $routine_workouts_amount; $workout++  ){
                    $super_workout_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_super_serie'][0] )[0];
                    $super_workout_data = $this->get_data( 'exercise', $super_workout_id );

                    $workout_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_workout'][0])[0];
                    $workout_data = $this->get_data( 'exercise', $workout_id );
                    $note =  maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_note'][0])[0];
                    $dosage_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0])[0];
                    
                    $dosage_data = $this->dosing->get_dosing( $dosage_id);
                    $routines[$routine]['exercises'][] = array(
                        'id'=>  $workout_id,
                        'name' => $workout_data['title'],
                        'superWorkOutId' => $super_workout_id,
                        'superWorkOutName' => $super_workout_data['title'],
                        'dosageId' => $dosage_id,
                        'dosings' => $dosage_data,
                        'note'=> $note
                    );
                }
            }
            $programs[] = array(
                'id'=> $program->ID,
                'name'=> $data['name'][0],
                'routinesAmount'=> $routines_amount,
                'athletes' => $athletes,
                'routines' => $routines
                // 'author' => array(
                //     'id'=> $author_id,
                //     'name'=> $author_data->display_name,
                //     'email'=> $author_data->user_email
                // )
            );
        }
        return $programs;
    }

     public function get_program_by_id($id){
        $planification_data = get_post($id);
        $data = get_post_meta($id);        
        print_r($data);die;
        
        $planification = array();
        $athletes = array();
        $routines = array();
        $routines_total = 0;
        $days_per_week_total = 0;        

        $athletes = maybe_unserialize ( $data['athletes'][0]);
        $routines_total = $data['routines'][0];
        
        for( $i=0; $i <= $routines_total - 1; $i++ ){
            $days_per_week = maybe_unserialize( $data["routines_".$i."_days_per_week"][0] )[0];
            $days_per_week_total += $days_per_week;
            $heating  = maybe_unserialize( $data["routines_".$i."_heating"][0] )[0];
            $total_workouts =  maybe_unserialize( $data["routines_".$i."_workouts"][0] );

            $routines[$i] = array(
                "id"=> $i,
                "days_per_week" => $days_per_week,
                "heating" => $heating,
                "total_workouts" => $total_workouts
            );

            for($w=0; $w <= $total_workouts - 1; $w++ ){
                $workout = "";
                $super_serie = "";
                $dosing = "";
                $note = "";

                if(  $data["routines_".$i."_workouts_".$w . "_workout"] ){
                    $workout =  maybe_unserialize($data["routines_".$i."_workouts_".$w . "_workout"][0])[0];
                }

                if(  $data["routines_".$i."_workouts_".$w . "_dosage_".$days_per_week] ){
                    $dosing =  maybe_unserialize($data["routines_".$i."_workouts_".$w . "_dosage_".$days_per_week][0])[0];
                }

                if(  $data["routines_".$i."_workouts_".$w. "_note"] ){
                    $note = $data["routines_".$i."_workouts_".$w. "_note"][0];
                }

                if(  $data["routines_".$i."_workouts_".$w. "_super_serie"] ){
                    $super_serie =  maybe_unserialize( $data["routines_".$i."_workouts_".$w. "_super_serie"][0])[0];
                }

                $routines[$i]['workouts'][$w] = array(
                    "id" => $w,
                    "workout" => $workout,
                    "dosing" => $dosing,
                    "note"=> $note,
                    "superserie" => $super_serie
                );

            }


        }
        $planification = array(
            "id" => $id,
            "author" => $planification_data->post_author,
            "routines_total"=> $routines_total,
            "days_per_week_total" => $days_per_week_total,
            "routines" => $routines

        );
        return $planification;
    }



    public function get_form_fields(){
        $fields = $this->program_form_fields();
        print_r($fields);die;
        
      return $this->program_form_fields();
    }

    /**
     *  Return an object with all fields with choices or relationships to the front-end.
     *  Some fields will be inside a 'sub_fields' objects and could have or not values. If the fields is a relationship we need to
     *  search for options.
     * 
     */
    public function program_form_fields(){


        global $wpdb;
        global $post;
    
        

        $planification_group_fields_id = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%program%' AND post_type='acf-field-group' AND post_status='publish'");
        
        $planifications_fields = array();
        $fields = acf_get_fields( $planification_group_fields_id[0] );
        foreach ( $fields as $field ) {
            if(  empty( $field['type'] ) ) return;
            switch($field['type']){
                //  USER FIELD
                case 'user':
                    // Get all athletes owner by the current user.

                    // TODO get current user instead of harcoded trainer ID
                    $athletes = get_users(array('meta_key' => 'trainer', 'meta_value' => '1'));
                    foreach($athletes as &$athlete ){
                        $athlete = array(
                            'ID' => $athlete->ID,
                            'user_nicename'=> $athlete->data->user_nicename,
                            'user_email'=> $athlete->data->user_email,

                        );
                    }

                    $planifications_fields['athlete'] = array(
                        'name' => $field['name'],
                        'label'=> $field['label'], 
                        'acf_key' => $field['key'],
                        'type'=> 'select',
                        'choices'=> $athletes , // Get athletes by owner
                    );
                break;
                //  REPEATER GROUP FIELDS ( Routines )
                case 'repeater':
                    $subfields = array();
                    // SUB FIELDS
                    foreach ( $field['sub_fields'] as $subfield ){
                        switch( $subfield['type'] ){

                            // Veces por semana
                            case 'select':
                                if(  $subfield['name'] === 'days_per_week' ){
                                    $subfields[$subfield['key']] =  array(
                                        'name' => $subfield['name'],
                                        'label'=> $subfield['label'],
                                        'acf_key'=> $subfield['key'],
                                        'type' => $subfield['type'],
                                        'choices' => $subfield['choices']
                                    );
                                }
                            break;
                            // Dias de la semana
                            case 'checkbox':
                                if(  $subfield['name'] === 'weekdays' ){

                                    $subfields[$subfield['key']] =  array(
                                        'name' => $subfield['name'],
                                        'label'=> $subfield['label'],
                                        'acf_key'=> $subfield['key'],
                                        'type' => $subfield['type'],
                                        'choices' => $subfield['choices']
                                    );
                                }
                            break;
                            // Calentamiento
                            case 'relationship':
                                if(  $subfield['name'] === 'heating' ){

                                    $heatings = get_posts( array('post_type' => 'heating') );

                                    foreach( $heatings as &$heating ){
                                        $heating = array(
                                            'ID' => $heating->ID,
                                            'post_title' => $heating->post_title,
                                            'post_name' => $heating->post_name
                                        );

                                        // TODO get link or meta data for heatings

                                    }
                                    $subfields[$subfield['key']] =  array(
                                        'name' => $subfield['name'],
                                        'label'=> $subfield['label'],
                                        'acf_key'=> $subfield['key'],
                                        'type' => $subfield['type'],
                                        'choices' => $heatings
                                    );
                                }
                            break;
                            // Rutinas
                            case 'repeater':
                                if( $subfield['name'] === 'workouts'){
                                    $workout_fields = array();
                                    // Ejercicio > Dosificacion > Super serie
                                    foreach( $subfield['sub_fields']  as $workout_field ){

                                        $workout_fields[$workout_field['key']][$workout_field['label']] =  array(
                                            'name' => $workout_field['name'],
                                            'label'=> $workout_field['label'],
                                            'acf_key'=> $workout_field['key'],
                                            'type' => $workout_field['type'],
                                            'choices' => array()
                                        );
                                    
                                    }
                                    // Campos de una rutina
                                    $subfields[ $subfield['key'] ] =  array(
                                        'name' => $subfield['name'],
                                        'label'=> $subfield['label'],
                                        'acf_key'=> $subfield['key'],
                                        'type' => $subfield['type'],
                                        'subfields' => $workout_fields
                                    );
                                }
                            break;
                        }
                    } 
                    
                    $planifications_fields['routines'] = array(
                        'label'=> $field['label'], 
                        'acf_key' => $field['key'],
                        'type'=> 'repeater',
                        'sub_fields'=> $subfields , // Get athletes by owner
                    );
                break;
            }
        }
        
        return $planifications_fields;
    }

    
    /**
     *  Update planification
     */
    public function update_program($program_id, $new_data = null ){
        
    }

    /**
     *  Fn to click on create routine inside an existing planification
     * 
     */
    public function handle_create_routine( $request ){
        $data =  $request->get_json_params();
        $routine = $data['routine'];
        $routines = $data['routines'];

        $program_id = $data['program_id'];
        $this->create_routine( $routine, $routines, $program_id );

    }
    

    /**
     *  Fn to update a routine inside an existing planification
     * 
     */
    public function handle_update_routine( $request ){
        $data =  $request->get_json_params();
        $routine = $data['routine'];
        $routines = $data['routines'];

        $program_id = $data['program_id'];
        $this->update_routine_days_per_week( $routine, $routines, $program_id );
    }


    public function update_routine_days_per_week($routine,$routines, $program_id){
        $max_day_for_week_exceeded = $this->max_day_for_week_exceeded($routine, $routines );

        if( $max_day_for_week_exceeded ){
            $error = new WP_Error( '001', 'Excediste la cantidad maxima de dias por semana para una planificacion', 'Some information' );
            return wp_send_json_error($error);
        }
        update_row( "routines_".($routine['id'] - 1)."_days_per_week", $routine['id'], $routine['days_per_week'], $program_id);
    }


    // Add a new routine into a planification
    public function create_routine($routine_data, $planification_routines, $program_id ){

        if ( $program_id <= 0  ){
            $error = new WP_Error( '001', 'No es posbile crear una rutina sin un ID de planificaicon', 'Some information' );
            return wp_send_json_error($error);
        }

        $max_day_for_week_exceeded = $this->max_day_for_week_exceeded($routine_data, $planification_routines );
        if( $max_day_for_week_exceeded ){
            $error = new WP_Error( '001', 'Excediste la cantidad maxima de dias por semana para una planificacion', 'Some information' );
            return wp_send_json_error($error);
        }else{
            add_row('routines', $routine_data, $program_id);
        }

    }

    public function handle_update_workout($request){
        $data = $request->get_json_params();

        $workout = $data['workout'];
        $program_id = $data['program_id'];
        $routine_id  =  $data['routine_id'];
        $this->update_workout($workout, $routine_id ,$program_id);
    }


    /**
     *  Update Workout
     * @param [ workout_id, dosing, super_serie, note ]
     */
    public function update_workout($workout, $routine_id, $program_id){
        $workout_id = $workout['id'];
        $exercise = $workout['workout'];
        $dosing = $workout['dosing'];
        $note = $workout['note'];
        $super_serie = $workout['super_serie'];
        

        update_field( "routines_".$routine_id."_workouts_".$workout_id."_workout", $exercise,  $program_id);
        update_field( "routines_".$routine_id."_workouts_".$workout_id."_note", $note,  $program_id);
        update_field( "routines_".$routine_id."_workouts_".$workout_id."_dosage", $dosing,  $program_id);
        update_field( "routines_".$routine_id."_workouts_".$workout_id."_super_serie", $super_serie,  $program_id);
        
    }


    

    public function handle_delete_program($program_id){
        $program = $this->delete_program( $program_id );
        return $program;
    }

    public function delete_program($program_id){
        // delete_row( 'routines_0_workouts_0_workout', 1 ,$program_id);
        $program = wp_delete_post($program_id);
        
        if( $program ){
            return $program_id;
        }else{
            return false;
        }
    }


    // Routine
    public function handle_delete_routine($data){
        return $this->delete_routine( $data );
    }

    public function delete_routine($data){

        $routine_row = $data->routineId;
        $planification_id = $data->programId;
        $deleted = delete_row('routines',  $routine_row, $planification_id );
        return $deleted;
    }


    


    public function handle_delete_workout($request){
        $data = $request->get_json_params();
        $workout_row_number = $data['workout_row_number'];
        $routine_row_number = $data['routine_row_number'];
        $program_id = $data['program_id'];
        $this->delete_workout($workout_row_number, $routine_row_number, $program_id );
    }

    /**
     *  Delete workout from a specific routine inside a planification
     *  TODO: return the new ID's for each workout if exists. 
     */
    public function delete_workout($workout_row_number, $routine_row_number, $program_id){
        // delete_row( 'routines_0_workouts_0_workout', 1 ,$program_id);
        $deleted = delete_row( 'routines_'.($routine_row_number - 1 ).'_workouts', $workout_row_number ,$program_id  );
        
        print_r($deleted);die;
    }


    public function handle_add_workout($request){
        $data = $request->get_json_params();
        
        $routine_id = $data['routine_id'];
        $workout = $data['workout'];
        $program_id = $data['program_id'];

        $this->add_workout($routine_id, $program_id, $workout);
    }

    public function add_workout($routine_id, $program_id, $workout){

        if( $routine_id === "" || $program_id <= 0 ){
            $error = new WP_Error( '001', 'No se encuentra ni el ID de la planificacion ni de la rutina', 'Some information' );
            return wp_send_json_error($error);
        }

        $row = array(
            'workout' => $workout['exercise'],
            'super_serie' => $workout['super_serie'],
            'note' => $workout['note']
        );
        
        add_row("routines_".$routine_id."_workouts", $row, $program_id);

    }


    


    /**
     *  Check for days available to create the routine
     */
    public function max_day_for_week_exceeded($new_routine, $planification_routines){
        $max_day_for_week = 6;
        $days_counted = 0;
        foreach ($planification_routines as $routine ){
            if( $routine['id'] === $new_routine['id']  ) continue;
            $days_counted += $routine['days_per_week'];
        }
        $days_counted += $new_routine['days_per_week'];
        
        if($days_counted > 6 ){
            return true;
        }else{
            return false;
        }

    }



    /**
    *  Create a new planification
    *  This function could have all the data at once or perhaps only some data information about the planification.
    *  On th app a trainer could update routines, assignaments, and more.
    *  @name  []
    *  @athletes []
    *  @routines []
    *
    */
    public function create_program($request){
        $planification_data =  $request->get_json_params();
        $name = $planification_data['name'];
        $athletes = $planification_data['athletes'];
        $routines = $planification_data['routines'];

        if ( empty( $name ) ) {
            $error = new WP_Error( '001', 'La planificacion al menos debe tener un nombre', 'Some information' );
            return wp_send_json_error($error);
        }



        // Create the planification and return the id
        $program_id = wp_insert_post(array(
            'post_type'=>'planification',
            'post_title'=> $name, 
            'post_status' => 'publish'
        ));


        // Add athletes
        if( !empty($athletes) ){
            update_field('athletes', $athletes, $program_id);
        }


        // Add routines
        if( !empty($routines) ){
            foreach( $routines as $routine  ){
                $this->create_routine($routine, $routines, $program_id);
            }
        }

        return array( 'id'=> $program_id );
            
    }


}