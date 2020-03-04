<?php

class Controller {
    public function get_data($post_type, $post_id){
        $data = array();
        $search_results = get_posts(array( 'post_type'=> $post_type, 'post__in'=> array( $post_id )));
        $meta_data = get_post_meta($post_id);
        $data = $meta_data;
        $data['title'] =  $search_results[0]->post_title; 
        return $data;
    }

    public function get_acf_fields( $fieldsGroup=null ){
        $result = array();
        $search_results = get_posts(array( 'post_type'=>'acf-field'));
        foreach( $search_results as $field ){
            echo "field \n";
            echo "$field->post_name \n";
            print_r($field);
            print_r($fieldsGroup);
            if( in_array( $field->post_name, $fieldsGroup , true)  ){
                $result[] = maybe_unserialize( $field->post_content ); 
            }
        }
        return $result;
    }
}