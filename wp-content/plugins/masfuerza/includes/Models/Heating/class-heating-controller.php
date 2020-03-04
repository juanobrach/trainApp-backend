<?php


class Heating extends Controller{

    public function __construct(){ }

    public function get_all_heatings(){
        $data = array();
        $search_results = get_posts(array( 'post_type'=> 'heating'));
        foreach( $search_results as $heating ){
            $data[$heating->ID] = array(
                'name' => $heating->post_title
            );
        }
        return $data;
    }

}