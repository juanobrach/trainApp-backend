<?php


class Heating extends Controller{

    public function __construct(){ }

    public function get_all_heatings(){
        $data = array();
        $search_results = get_posts(array( 'post_type'=> 'heating'));
        foreach( $search_results as $heating ){

            $heating_meta = get_post_meta($heating->ID);
            $featured_image = get_the_post_thumbnail_url($heating->ID);


            $heating_category = get_the_terms( $heating->ID, 'exercise_category' );      
            $category = $heating_category[0];


            $video_url = "";
            if( is_serialized( $heating_meta['video'][0] )){
                $video_url = maybe_unserialize( $heating_meta['video'][0] )[0];
            }else{
                $video_url = $heating_meta['video'][0];
            }

            $data[] = array(
                'id'=> $heating->ID,
                'name' => $heating->post_title,
                'description' => $heating_meta['description'][0],
                'category'=>  array(
                    'id' => $category->term_id,
                    'name' => $category->name
                ),
                'image'  => $featured_image,
                'video' => $video_url
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
            $featured_image = get_the_post_thumbnail_url($heating->ID);
            // print_r($heating_meta);

            $exercise_category = get_the_terms( $heating->ID, 'exercise_category' );      
            $category = $exercise_category[0];

            $images_ids =  maybe_unserialize( $heating_meta['images'][0] );
            $images = array();
            foreach( $images_ids as $image_id){
                $images[] = wp_get_attachment_image_src( $image_id, 'normal' )[0];
            }


            $video_url = "";
            if( is_serialized( $heating_meta['video'][0] )){
                $video_url = maybe_unserialize( $heating_meta['video'][0] )[0];
            }else{
                $video_url = $heating_meta['video'][0];
            }

            $data[] = array(
                'id'=> $heating->ID,
                'name' => $heating->post_title,
                'description' => $heating_meta['description'][0],
                'category'=>  array(
                    'id' => $category->term_id,
                    'name' => $category->name
                ),
                'image'  => $featured_image,
                'video' => $video_url
            );
        }
        
        return $data;
    }

}