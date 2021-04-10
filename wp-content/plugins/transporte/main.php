<?php
/**
 * Plugin Name: Transporte plugin
 * Description: Plugin de cadastro de pátios e consulta de orçamentos 
 * Version: 1.0
 * Author: Bruno dos Reis
 */

define( 'TRANSPORTE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'admin_menu','transporte_item_menu' );

function  transporte_item_menu(){

    /* Register our setting */
    register_setting(
        'transporte',
        'transporte',
        'transporte_sanitize'
    );

    /* Adding plugin in menu*/
    $settings_page = add_menu_page(
        'Transporte',
        'Transporte',
        'manage_options', 
        'transporte',
        'request_transport_settings_page',
        'dashicons-car',
        6
    );

    add_submenu_page(
        'transporte',
        'Pátios',
        'Pátios',
        'manage_options',
        'add_courtyards',
        'courtyards_settings_page'
    );

    add_submenu_page(
        'transporte',
        'Orçamentos',
        'Orçamentos',
        'manage_options',
        'list_requests',
        'request_transport_settings_page'
    );

    // $page_hook_id = question_setings_page_id();
    // add_action('admin_enqueue_scripts', 'question_enqueue_scripts');

}   

function courtyards_settings_page(){
    $path = plugin_dir_url( __FILE__ );
    wp_enqueue_script( 'validation_js', $path . 'src/js/validation.js',array( 'jquery' ),'1.0.0', true );
    require_once dirname( __FILE__ ) . '/src/courtyards/courtyards_html.php';
    global $db;
    $courtyards = new Courtyards_html();
    $courtyards->html_add_courtyards();
}


function request_transport_settings_page(){
    $path = plugin_dir_url( __FILE__ );
    wp_enqueue_script( 'validation_js', $path . 'src/js/validation.js',array( 'jquery' ),'1.0.0', true );
    require_once dirname( __FILE__ ) . '/src/request_transport/request_transport_html.php';
    global $db;
    $courtyards = new Request_transport_html();
    $courtyards->html_list_requests_transport();
}

function question_list_page(){

}

