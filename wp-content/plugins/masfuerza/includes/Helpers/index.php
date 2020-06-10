<?php

function pr( $data ){
    echo "<pre>";
    print_r( $data );
    echo "</pre>";
}

function logger( $data, $log_file_dir ){
    $log_content = file_get_contents($log_file_dir);
    $log_content_arr = json_decode($log_content);
    array_push($log_content_arr, $data );
    $log_as_json_data = json_encode($log_content_arr, JSON_PRETTY_PRINT);
    file_put_contents( $log_file_dir, $log_as_json_data );
}