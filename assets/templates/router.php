<?php
require_once __DIR__."/user.php";
require_once __DIR__."/courtyards.php";

add_action('rest_api_init', function(){
	register_rest_route( 'user/get', '/info', array(
        'methods' => 'POST',
        'callback' => "getInfo",
    ));
	register_rest_route( 'courtyards', '/get', array(
        'methods' => 'POST',
        'callback' => 'getCourtyards',
    ));
	register_rest_route( 'user', '/save', array(
        'methods' => 'POST',
        'callback' => 'saveInfo',
    ));
});
