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
      
        echo json_encode($fields);
        return;
    }