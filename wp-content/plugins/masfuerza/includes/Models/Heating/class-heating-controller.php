<?php


class Heating extends Controller{

    public function __construct(){ }

    public function get_all_heatings(){
        $data = array();
        $search_results = get_posts(array( 'post_type'=> 'heating'));
        foreach( $search_results as $heating ){

            $heating_meta = get_post_meta($heating->ID);


            $heating_category = get_the_terms( $heating->ID, 'exercise_category' );      
            $category = $heating_category[0]->name;

            $data[] = array(
                'id'=> $heating->ID,
                'category'=> $category,
                'description' => $heating_meta['description'][0],
                'name' => $heating->post_title
            );
        }
        return $data;
    }


    public function get_heating( $heatingId ){
        $data = array();
        $search_results = get_posts(array( 
            'post_type'=> 'heating',
            'post__in' => array($heatingId)
        ));

        foreach( $search_results as $heating ){
            
            $heating_meta = get_post_meta($heating->ID);
            // print_r($heating_meta);

            $exercise_category = get_the_terms( $heating->ID, 'exercise_category' );      
            $category = $exercise_category[0]->name;

            $images_ids =  maybe_unserialize( $heating_meta['images'][0] );
            $images = array();
            foreach( $images_ids as $image_id){
                $images[] = wp_get_attachment_image_src( $image_id, 'normal' )[0];
            }
            $data = array(
                'name' => $heating->post_title,
                'description' => $heating_meta['description'][0],
                'category' => $category,
                'images'  => $images
            );
        }
        
        return $data;
    }

}