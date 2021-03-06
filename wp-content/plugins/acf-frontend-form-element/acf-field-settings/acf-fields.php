<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}


if ( function_exists( 'acf_add_local_field_group' ) ) {
    $user_roles = acfef_get_user_roles( [ 'administrator' ] );
    $userrole = [];
    acf_add_local_field_group( [
        'key'                   => 'group_acfefuserfields',
        'title'                 => 'ACF Frontend User Fields',
        'fields'                => array(
        array(
        'key'               => 'field_acfef_username',
        'label'             => __( 'Username', 'acf-ele-form' ),
        'name'              => '_username',
        'type'              => 'text',
        'instructions'      => '',
        'required'          => 1,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'prepend'           => '',
        'append'            => '',
        'maxlength'         => '',
    ),
        array(
        'key'               => 'field_acfef_username_read',
        'label'             => __( 'Username (can\'t be changed)', 'acf-ele-form' ),
        'name'              => '_username_read',
        'type'              => 'text',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'disabled'          => 1,
        'prepend'           => '',
        'append'            => '',
        'maxlength'         => '',
        'custom_username'   => 1,
    ),
        array(
        'key'               => 'field_acfef_password',
        'label'             => __( 'Password', 'acf-ele-form' ),
        'name'              => '_user_password',
        'type'              => 'password',
        'instructions'      => '',
        'required'          => 1,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'placeholder'       => '',
        'prepend'           => '',
        'custom_password'   => 1,
        'append'            => '',
    ),
        array(
        'key'               => 'field_acfef_password_confirm',
        'label'             => __( 'Confirm Password', 'acf-ele-form' ),
        'name'              => '_user_password_confirm',
        'type'              => 'password',
        'instructions'      => '',
        'required'          => 1,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'placeholder'       => '',
        'prepend'           => '',
        'custom_password'   => 0,
        'append'            => '',
    ),
        array(
        'key'               => 'field_acfef_email',
        'label'             => __( 'Email', 'acf-ele-form' ),
        'name'              => '_user_email',
        'type'              => 'email',
        'instructions'      => '',
        'required'          => 1,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'custom_email'      => 1,
        'prepend'           => '',
        'append'            => '',
    ),
        array(
        'key'               => 'field_acfef_first_name',
        'label'             => __( 'First Name', 'acf-ele-form' ),
        'name'              => 'first_name',
        'type'              => 'text',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'prepend'           => '',
        'append'            => '',
        'maxlength'         => '',
    ),
        array(
        'key'               => 'field_acfef_last_name',
        'label'             => __( 'Last Name', 'acf-ele-form' ),
        'name'              => 'last_name',
        'type'              => 'text',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'prepend'           => '',
        'append'            => '',
        'maxlength'         => '',
    ),
        array(
        'key'               => 'field_acfef_bio',
        'label'             => __( 'Biographical Info', 'acf-ele-form' ),
        'name'              => 'description',
        'type'              => 'textarea',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'maxlength'         => '',
        'rows'              => '3',
        'new_lines'         => '',
    ),
        $userrole
    ),
        'location'              => array( array( array(
        'param'    => 'current_user',
        'operator' => '==',
        'value'    => 'viewing_front',
    ) ) ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => false,
        'description'           => '',
    ] );
    acf_add_local_field_group( array(
        'key'                   => 'group_acfefposts',
        'title'                 => 'ACF Frontend Post Fields',
        'fields'                => array(
        array(
        'key'               => 'field_acfef_thumbnail_id',
        'label'             => __( 'Featured Image', 'acf-ele-form' ),
        'name'              => '_thumbnail_id',
        'type'              => 'image',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'return_format'     => 'array',
        'preview_size'      => 'medium',
        'library'           => 'all',
        'min_width'         => '',
        'min_height'        => '',
        'min_size'          => '',
        'max_width'         => '',
        'max_height'        => '',
        'max_size'          => '',
        'mime_types'        => '',
    ),
        array(
        'key'               => 'field_acfef_post_excerpt',
        'label'             => __( 'Excerpt', 'acf-ele-form' ),
        'name'              => '_post_excerpt',
        'type'              => 'textarea',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'default_value'     => '',
        'placeholder'       => '',
        'maxlength'         => '',
        'rows'              => '3',
        'new_lines'         => '',
        'custom_content'    => 0,
        'custom_excerpt'    => 1,
    ),
        array(
        'key'               => 'field_acfef_post_categories',
        'label'             => __( 'Categories', 'acf-ele-form' ),
        'name'              => '_post_categories',
        'type'              => 'taxonomy',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'only_front'        => 0,
        'taxonomy'          => 'category',
        'field_type'        => 'multi_select',
        'allow_null'        => 0,
        'add_term'          => 1,
        'save_terms'        => 1,
        'load_terms'        => 1,
        'return_format'     => 'id',
        'multiple'          => 0,
    ),
        array(
        'key'               => 'field_acfef_post_tags',
        'label'             => __( 'Tags', 'acf-ele-form' ),
        'name'              => '_post_tags',
        'type'              => 'taxonomy',
        'instructions'      => '',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper'           => array(
        'width' => '',
        'class' => '',
        'id'    => '',
    ),
        'only_front'        => 0,
        'taxonomy'          => 'post_tag',
        'field_type'        => 'multi_select',
        'allow_null'        => 0,
        'add_term'          => 1,
        'save_terms'        => 1,
        'load_terms'        => 1,
        'return_format'     => 'id',
        'multiple'          => 0,
    )
    ),
        'location'              => array( array( array(
        'param'    => 'current_user',
        'operator' => '==',
        'value'    => 'viewing_front',
    ) ) ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => false,
        'description'           => '',
    ) );
}
