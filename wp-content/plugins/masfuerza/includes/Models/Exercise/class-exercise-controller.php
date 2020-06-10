<?php
class Exercise extends Controller{

    public function __construct(){ }



    public function get_exercise_categories(){
        $categories_query = get_terms( 'exercise_category' );
        
        $categories = array();
        foreach( $categories_query as $category ){
            $categories[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'count' => $category->count 
            );
        }

        return $categories;        

    }

    public function get_all_exercises(){
        $data = array();
        $search_results = get_posts(array( 
            'post_type'=> 'exercise', 
            "posts_per_page" =>  -1,
            'orderby' => 'title',
            'order'   => 'ASC'
        ));
        

        foreach( $search_results as $excercise ){
            
            $excercise_meta = get_post_meta($excercise->ID);
            $featured_image = get_the_post_thumbnail_url($excercise->ID);
            

            $exercise_category = get_the_terms( $excercise->ID, 'exercise_category' );      
            $category = $exercise_category[0];
            
          

            $video_url = "";
            if( is_serialized( $excercise_meta['video'][0] )){
                $video_url = maybe_unserialize( $excercise_meta['video'][0] )[0];
            }else{
                $video_url = $excercise_meta['video'][0];
            }

            
            $data[] = array(
                'id'=> $excercise->ID,
                'name' => $excercise->post_title,
                'description' => $excercise_meta['description'][0],
                'category' => array(
                    'id' => $category->term_id,
                    'name' => $category->name
                ),
                'image'  => $featured_image,
                'video' => $video_url
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

            
            $exercise_category = get_the_terms( $excercise->ID, 'exercise_category' );      
            $category = $exercise_category[0];

            $images_ids =  maybe_unserialize( $excercise_meta['images'][0] );
            $images = array();
            foreach( $images_ids as $image_id){
                $images[] = wp_get_attachment_image_src( $image_id, 'normal' )[0];
            }

            $video_url = maybe_unserialize( $excercise_meta['video'][0] );

            $data = array(
                'name' => $excercise->post_title,
                'description' => $excercise_meta['description'][0],
                'category' => array(
                    'id' => $category->term_id,
                    'name' => $category->name
                ),
                'images'  => $images,
                'video' => $video_url
            );
        }
        
        return $data;
    }

}