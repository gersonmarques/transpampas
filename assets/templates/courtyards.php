<?php
    function getCourtyards(WP_REST_Request $request) 
    {   
        global $wpdb;
        try {
            $data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}patios ", ARRAY_A);
            return $data;
        } catch (\Throwable $th) {
            return array();
        }
        
    }