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
            'post_type'        => 'program',
            'posts_per_page'   => -1,

        );

        if( $author_id != null ){
            $args['post_author'] = $author_id;
        }
        
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


            $routines_days_per_week_total = 0;
            $routinesName = array('A', 'B','C','D','E','F','G');
            for( $routine = 0; $routine < $routines_amount; $routine++ ){
                $workouts_amount =    $data['routines_'.$routine.'_workouts'][0];
                $routine_days_per_week_string = $data['routines_'.$routine.'_days_per_week'][0];
                $routine_days_per_week_number = strpos( $routine_days_per_week_string, '2') !== false ? 2 : 3;
                $routine_workouts_amount = $data['routines_'.$routine.'_workouts'][0];
                
                $heating_id = 0;
                if( is_serialized( $data['routines_'.$routine.'_heating'][0] ) ){
                    $heating_id = maybe_unserialize( $data['routines_'.$routine.'_heating'][0] )[0];
                }else{
                    $heating_id = $data['routines_'.$routine.'_heating'][0];
                }


                $heating_data = $this->get_data('heating',$heating_id);
                $routines[$routine] = array(
                    "id" => $routine + 1,
                    'name' => $routinesName[$routine],
                    'warmUpId' => $heating_id,
                    'warmUpName' => $heating_data['title'],
                    'daysPerWeek'=> $routine_days_per_week_number,
                    'totalExercises' => $workouts_amount
                );
                $routines_days_per_week_total += $routine_days_per_week_number;

                if( $workouts_amount <= 0 ){
                    $routines[$routine]['exercises'] = array();
                }

                for( $workout = 0; $workout < $routine_workouts_amount; $workout++  ){


                    $super_workout_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_super_serie'][0]);
                    $super_workout_data = $this->get_data( 'exercise', $super_workout_id );

                    $workout_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_workout'][0]);
                    $workout_data = $this->get_data( 'exercise', $workout_id );
                    $note =  maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_note'][0]);

                    $dosage_id = 0;
                    if( is_serialized( $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0] ) ){
                        $dosage_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0])[0];                    
                    }else{
                        $dosage_id = $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0];
                    }

                    $dosage_data = $this->dosing->get_dosing( $dosage_id);
                    $routines[$routine]['exercises'][] = array(
                        'id'=>  $workout_id,
                        'name' => $workout_data['title'],
                        'superWorkOutId' => $super_workout_id,
                        'superWorkOutName' => $super_workout_data['title'],
                        'dosings' => $dosage_data,
                        'note'=> $note
                    );
                }
            }


            $programs[] = array(
                'id'=> $program->ID,
                'name'=> $data['name'][0],
                'sku'=> $data['sku'][0],
                'routinesAmount'=> $routines_amount,
                'daysPerWeekTotal'=> $routines_days_per_week_total,
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

        $program = array();
        $routines = array();
        $data = get_post_meta($id);
        

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


        $routines_days_per_week_total = 0;
        $routinesName = array('A', 'B','C','D','E','F','G');
        for( $routine = 0; $routine < $routines_amount; $routine++ ){
            $workouts_amount = $data['routines_'.$routine.'_workouts'][0];



            $routine_days_per_week_string = $data['routines_'.$routine.'_days_per_week'][0];
            $routine_days_per_week_number = strpos( $routine_days_per_week_string, '2') !== false ? 2 : 3;
            $routine_workouts_amount = $data['routines_'.$routine.'_workouts'][0];


            $heating_id = maybe_unserialize( $data['routines_'.$routine.'_heating'][0] );
            
            $heating_data = $this->get_data('heating',$heating_id);
            $routines[$routine] = array(
                "id" => $routine + 1,
                'name' => $routinesName[$routine],
                'warmUpId' => $heating_id,
                'warmUpName' => $heating_data['title'],
                'daysPerWeek'=> $routine_days_per_week_number,
                'totalExercises' => $workouts_amount
            );
            $routines_days_per_week_total += $routine_days_per_week_number;

            if( $workouts_amount <= 0 ){
                $routines[$routine]['exercises'] = array();
            }


            for( $workout = 0; $workout < $routine_workouts_amount; $workout++  ){
                $super_workout_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_super_serie'][0]);
                $super_workout_data = $this->get_data( 'exercise', $super_workout_id );

                $workout_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_workout'][0]);


                $workout_data = $this->get_data( 'exercise', $workout_id );
                $note =  maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_note'][0]);
                // TODO: TEST IF IS AN ARRAY
                $dosage_id = 0;
                if( is_serialized( $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0] ) ){
                    $dosage_id = maybe_unserialize( $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0])[0];                    
                }else{
                    $dosage_id = $data['routines_'.$routine.'_workouts_'.$workout.'_dosage'][0];
                }

                                
                $dosage_data = $this->dosing->get_dosing( $dosage_id);
                $routines[$routine]['exercises'][] = array(
                    'id'=>  $workout_id,
                    'name' => $workout_data['title'],
                    'superWorkOutId' => $super_workout_id,
                    'superWorkOutName' => $super_workout_data['title'],
                    'dosings' => $dosage_data,
                    'note'=> $note
                );
            }
        }

        $program = array(
            'id'=> $id,
            'name'=> $data['name'][0],
            'sku' => $data['sku'][0],
            'routinesAmount'=> $routines_amount,
            'daysPerWeekTotal'=> $routines_days_per_week_total,
            'athletes' => $athletes,
            'routines' => $routines,
            'trainerId'=> $data['trainer'][0]
            // 'author' => array(
            //     'id'=> $author_id,
            //     'name'=> $author_data->display_name,
            //     'email'=> $author_data->user_email
            // )
        );

        return $program;
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

                    $planifications_fields['athletes'] = array(
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

                                    $heatings = get_posts( array(
                                        'post_type' => 'heating',
                                        'posts_per_page'   => -1,
                                        ) );

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


    public function map_program_fields($program_data){
        $program_data = json_decode(json_encode($program_data), true);
        


        $routines = array();

        foreach($program_data['routines'] as $routines_data ){

            $workouts = array();

            foreach( $routines_data['exercises'] as $exercise_data ){
                $workouts[] = array(
                    'workout'=> $exercise_data['id'],
                    'dosage' => $exercise_data['dosings']['id'],
                    'super_serie' => $exercise_data['superWorkOutId'],
                    'note' => $exercise_data['note']
                );
            }

            $routines[] = array(
                'id'=> $routines_data['id'],
                'days_per_week' => $routines_data['daysPerWeek'],
                'heating' => $routines_data['warmUpId'],
                'workouts' => $workouts,
            );
        }

        $athletes = array();
        
        foreach ($program_data['athletes'] as $athlete ) {
            $athletes[] = $athlete['id'];
        }
            

        $program = array(
            'id'=> $program_data['id'],
            'name'=> $program_data['name'],
            'sku' => $program_data['sku'],
            'athletes'=> $athletes,
            'routines'=> $routines

        );

        return $program;

    }

    public function handle_update_program($program_data, $program_id ){

        $program = $this->map_program_fields( $program_data);        
        
        return $this->update_program($program, $program_id);
    }

    /** 
     *  Update planification
     */
    public function update_program($new_data = null, $program_id ){


        // Update Prrogram Name
        update_field('name', $new_data['name'], $program_id);
        update_field('sku', $new_data['sku'], $program_id);

        $program_post= array(
            'ID'           => $program_id,
            'post_title'   =>  $new_data['name'],
        );
       
      // Update the post into the database
        wp_update_post( $program_post );

        // Chef if new athlete on program. Then create a planification.
        $athletes = get_field('athletes', $program_id);
        
        foreach ( $new_data['athletes'] as $athlete ) {
            if( !in_array( $athlete, $athletes )  ){
                $this->assing_athlete($athlete, $program_id, $new_data['sku']);
            }
        }
        update_field('athletes', $new_data['athletes'], $program_id);
        
        // Update Routine
        $routines = $new_data['routines'];
        

        delete_field('routines', $program_id);

        if( have_rows('routines', $program_id) ){

            while(   have_rows('routines', $program_id) ) : the_row();

            $row_index = get_row_index();

            foreach( $routines as $routine_row_number => $routine ){

                if( $row_index === $routine['id'] ){

                    $this->update_routine_days_per_week( $routine, $routines, $program_id  );
                    $this->bulk_update_workout( $routine['workouts'], $routine['id'], $program_id );
                }else{
                    $this->create_routine( $routine, $routines ,$program_id);
                }

                //        $this->update_workout( $exercise,  $routine['id'], $program_id , $rowNumber );

            }
            endwhile;
        }else{

            foreach( $routines as $routine_row_number => $routine ){
                $this->create_routine( $routine, $routines, $program_id );
            }

        }

        echo json_encode ( $this->get_program_by_id(  $program_id  ) );
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
        update_row( "routines_".($routine['id'] - 1)."_heating", $routine['id'], $routine['heating'], $program_id);

    }

    // Add a new routine into a planification
    public function create_routine($routine_data, $planification_routines, $program_id ){
        if ( $program_id <= 0  ){
            $error = new WP_Error( '001', 'No es posbile crear una rutina sin un ID de planificaicon', 'Some information' );
            return wp_send_json_error($error, 404);
        }

        $max_day_for_week_exceeded = $this->max_day_for_week_exceeded($routine_data, $planification_routines );
        if( $max_day_for_week_exceeded ){
            $error = new WP_Error( '001', 'Excediste la cantidad maxima de dias por semana para una planificacion', 'Some information' );
            return wp_send_json_error($error, 404);
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

    public function bulk_update_workout($workouts, $routine_id, $program_id ){
        
       // update_sub_field( "routines_".($routine_id) - 1 ."_workouts",  $workouts ,  $program_id );
        // add_row( array('routines', $routine_id, 'workouts', $workouts ), $program_id );

        // var_dump($add_workout);die;

        delete_field( "routines_". ($routine_id  - 1 )."_workouts", $program_id);

        //$add_workout = add_row("routines_workouts", $workouts, $program_id);
        
        foreach($workouts as $row => $workout ){
            
            add_sub_row( array('routines', $routine_id, 'workouts'), $workout, $program_id );
            // $add_workout = add_row("routines_".($routine_id - 1)."_workouts", $workout, $program_id);
        }


    }

    /**
     *  Update Workout
     * @param [ workout_id, dosing, super_serie, note ]
     */
    public function update_workout($workout, $routine_id, $program_id, $exercise_row=null){





        // TODO : set ID = position of row for the exercise into the react request.
        $exercise = $workout['id'];
        $dosing = $workout['dosings']['id'];
        $note = $workout['note'];
        $super_serie = $workout['super_serie'];
        $routine_row = $routine_id -1 ;

        if( !empty( $exercise ) ){

            // $row = array(
            //     array(
            //         'workouts' => array(
            //             array(
            //                 'workout' =>  $exercise
            //             ),
            //         ),
            //     )
            // );

            // update_field( "routines", $row,  $program_id );

                update_field( "routines_".$routine_row."_workouts_".$exercise_row."_workout",  $exercise ,  $program_id );
        }

        if( !empty($dosing ) ){
            update_field( "routines_".$routine_row."_workouts_".$exercise_row."_dosage", $dosing,  $program_id);
        }

        if( !empty($note)){
            update_field( "routines_".$routine_row."_workouts_".$exercise_row."_note", $note,  $program_id);
        }

        if( !empty($super_serie) ){
            update_field( "routines_".$routine_row."_workouts_".$exercise_row."_super_serie", $super_serie,  $program_id);
        }

    }

    public function handle_delete_program($program_sku){
        $program = $this->delete_program( $program_sku );
        return $program;
    }

    public function delete_program($program_sku){
        $program_id = $this->get_program_id($program_sku);
        $program = wp_delete_post($program_id);

        if( $program ){
            return $program_sku;
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


    public function clone_program( $program_data ){
        $program = $this->create_program($program_data);
        
        $program_data = $program_data->get_json_params();        
        return $this->handle_update_program($program_data, $program['id']);
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
        $sku  = $planification_data['sku'];
        $trainerId = $planification_data['trainerId'];

        if ( empty( $name ) ) {
            $error = new WP_Error( '001', 'La planificacion al menos debe tener un nombre', 'Some information' );
            return wp_send_json_error($error);
        }



        // Create the planification and return the id
        $program_id = wp_insert_post(array(
            'post_type'=>'program',
            'post_title'=> $name,
            'post_status' => 'publish',
            'post_author'=> (int)$trainerId
        ));


        // Add athletes
        if( !empty($sku) ){
            update_field('sku', $sku, $program_id);
        }

        if( !empty($name) ){
            update_field('name', $name, $program_id );
        }

        update_field('trainer', $trainerId, $program_id );

        $created_program = $this->get_program_by_id( $program_id);

        //echo json_encode( $created_program );
        return $created_program;

    }

    public function get_program_id( $sku){        
        $query = array(
            'posts_per_page'   => -1,
            'post_status'      => 'publish',
            'post_type' => 'program',
            'meta_query'=> array(
                array(
                    'key'=> 'sku',
                    'value'=> $sku,
                    'compare'=> "LIKE",
                )
            )
        );

        $planification =  get_posts( $query );
        return $planification[0]->ID;
    }


    /**
     *  Assign Athlete to program.
     *  This function will create a planification with a copy data from this program.
     */
    public function assing_athlete($athlete_id, $program_id, $program_sku){
        $Planification = new Planification();
            
        // Create the planification
        $planification_id = $Planification->create_planification($program_id, $athlete_id);

        // Add athlete to program
        $athletes = get_field('athletes', $program_id);
        $athletes[] = $athlete_id;
        update_field('athletes', $athletes, $program_id);        

        return array(
            'planification_id' => $planification_id
        );
        

    }


}