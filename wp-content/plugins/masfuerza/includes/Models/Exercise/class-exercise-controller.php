<?php
class Exercise extends Controller{

    public function __construct(){ }

    public function get_all_exercises(){
        $data = array();
        $search_results = get_posts(array( 'post_type'=> 'exercise'));


        foreach( $search_results as $excercise ){
            
            $excercise_meta = get_post_meta($excercise->ID);
            // print_r($excercise_meta);

            $category_object = get_term( $excercise_meta['category'][0] );
            $category = $category_object->name;

            $images_ids =  maybe_unserialize( $excercise_meta['images'][0] );
            $images = array();
            foreach( $images_ids as $image_id){
                $images[] = wp_get_attachment_image_src( $image_id, 'normal' )[0];
            }
            $data[] = array(
                'id'=> $excercise->ID,
                'name' => $excercise->post_title,
                'description' => $excercise_meta['description'][0],
                'category' => $category,
                'images'  => $images
            );
        }
        return $data;
    }

    public function get_exercise( $exerciseId ){
        $data = array();
        $search_results = get_posts(array( 
            'post_type'=> 'exercise',
            'post__in' => array($exerciseId)
        ));

        foreach( $search_results as $excercise ){
            
            $excercise_meta = get_post_meta($excercise->ID);
            // print_r($excercise_meta);

            $category_object = get_term( $excercise_meta['category'][0] );
            $category = $category_object->name;

            $images_ids =  maybe_unserialize( $excercise_meta['images'][0] );
            $images = array();
            foreach( $images_ids as $image_id){
                $images[] = wp_get_attachment_image_src( $image_id, 'normal' )[0];
            }
            $data = array(
                'name' => $excercise->post_title,
                'description' => $excercise_meta['description'][0],
                'category' => $category,
                'images'  => $images
            );
        }
        
        return $data;
    }

}