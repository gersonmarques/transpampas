<?php

namespace ACFFrontendForm\Module;

use  Elementor\Core\Base\Module ;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

class ACFEFS_Module extends Module
{
    private  $components = array() ;
    public function get_name()
    {
        return 'acfef_settings';
    }
    
    public function acfef_plugin_page()
    {
        global  $acfef_settings ;
        $acfef_settings = add_menu_page(
            'ACF Frontend',
            'ACF Frontend',
            'manage_options',
            'acfef-settings',
            [ $this, 'acfef_admin_settings_page' ],
            'dashicons-feedback',
            '87.87778'
        );
        add_submenu_page(
            'acfef-settings',
            __( 'Settings', 'acf-frontend-form-element' ),
            __( 'Settings', 'acf-frontend-form-element' ),
            'manage_options',
            'acfef-settings',
            '',
            0
        );
    }
    
    function acfef_admin_settings_page()
    {
        global  $acfef_active_tab ;
        $acfef_active_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'welcome' );
        ?>

		<h2 class="nav-tab-wrapper">
		<?php 
        do_action( 'acfef_settings_tabs' );
        ?>
		</h2>
		<?php 
        do_action( 'acfef_settings_content' );
    }
    
    public function add_tabs()
    {
        add_action( 'acfef_settings_tabs', [ $this, 'acfef_settings_tabs' ], 1 );
        add_action( 'acfef_settings_content', [ $this, 'acfef_settings_render_options_page' ] );
    }
    
    public function acfef_settings_tabs()
    {
        global  $acfef_active_tab ;
        ?>
		<a class="nav-tab <?php 
        echo  ( $acfef_active_tab == 'welcome' || '' ? 'nav-tab-active' : '' ) ;
        ?>" href="<?php 
        echo  admin_url( '?page=acfef-settings&tab=welcome' ) ;
        ?>"><?php 
        _e( 'Welcome', 'acf-frontend-form-element' );
        ?> </a>
		<a class="nav-tab <?php 
        echo  ( $acfef_active_tab == 'local-avatar' || '' ? 'nav-tab-active' : '' ) ;
        ?>" href="<?php 
        echo  admin_url( '?page=acfef-settings&tab=local-avatar' ) ;
        ?>"><?php 
        _e( 'Local Avatar', 'acf-frontend-form-element' );
        ?> </a>		
		<a class="nav-tab <?php 
        echo  ( $acfef_active_tab == 'uploads-privacy' || '' ? 'nav-tab-active' : '' ) ;
        ?>" href="<?php 
        echo  admin_url( '?page=acfef-settings&tab=uploads-privacy' ) ;
        ?>"><?php 
        _e( 'Uploads Privacy', 'acf-frontend-form-element' );
        ?> </a>
		<?php 
    }
    
    public function acfef_settings_render_options_page()
    {
        global  $acfef_active_tab ;
        
        if ( '' || 'welcome' == $acfef_active_tab ) {
            ?>
		<style>p.acfef-text{font-size:20px}</style>
		<h3><?php 
            _e( 'Hello and welcome', 'acf-frontend-form-element' );
            ?></h3>
		<p class="acfef-text"><?php 
            _e( 'If this is your first time using ACF Frontend, we recommend you watch Paul Charlton from WPTuts beautifully explain how to use it.', 'acf-frontend-form-element' );
            ?></p>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/iHx7krTqRN0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><br><p class="acfef-text"><?php 
            _e( 'Here is a video where our lead developer and head of support, explains the basic usage of ACF Frontend.', 'acf-frontend-form-element' );
            ?></p>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/lMkZzOVVra8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><br>
		<p class="acfef-text"><?php 
            _e( 'If you have any questions at all please feel welcome to email shabti at', 'acf-frontend-form-element' );
            ?> <a href="mailto:shabti@frontendform.com">shabti@frontendform.com</a> <?php 
            _e( 'or on whatsapp', 'acf-frontend-form-element' );
            ?> <a href="https://api.whatsapp.com/send?phone=972584526441">+972-58-452-6441</a></p>
		<?php 
        }
        
        
        if ( 'local-avatar' == $acfef_active_tab ) {
            $this->local_avatar = get_option( 'local_avatar' );
            ?>

			<div class="wrap">
				<?php 
            settings_errors();
            ?>
				<form method="post" action="options.php">
					<?php 
            settings_fields( 'local_avatar_settings' );
            do_settings_sections( 'local-avatar-settings-admin' );
            submit_button();
            ?>
				</form>
			</div>
		<?php 
        }
        
        
        if ( 'uploads-privacy' == $acfef_active_tab ) {
            $this->uploads_privacy = get_option( 'filter_media_author' );
            ?>

			<div class="wrap">
				<?php 
            settings_errors();
            ?>
				<form method="post" action="options.php">
					<?php 
            settings_fields( 'uploads_privacy_settings' );
            do_settings_sections( 'uploads-privacy-settings-admin' );
            submit_button();
            ?>
				</form>
			</div>
		<?php 
        }
    
    }
    
    public function cpt_acfef_payment()
    {
        require_once __DIR__ . '/admin-pages/custom-fields.php';
        if ( !get_field( 'acfef_payments_active', 'options' ) ) {
            return;
        }
        require_once __DIR__ . '/admin-pages/custom-post-types.php';
    }
    
    public function acfef_settings_sections()
    {
        require_once __DIR__ . '/admin-pages/local-avatar/settings.php';
        new ACFEF_Local_Avatar_Settings( $this );
        require_once __DIR__ . '/admin-pages/uploads-privacy/settings.php';
        new ACFEF_Uploads_Privacy_Settings( $this );
    }
    
    public function acfef_form_head()
    {
        
        if ( is_admin() ) {
            $current_screen = get_current_screen();
            if ( isset( $current_screen->id ) && $current_screen->id === 'toplevel_page_acfef-settings' ) {
                acf_form_head();
            }
        }
    
    }
    
    public function __construct()
    {
        add_action( 'init', [ $this, 'cpt_acfef_payment' ] );
        add_action( 'admin_menu', [ $this, 'acfef_plugin_page' ] );
        add_action( 'admin_init', [ $this, 'acfef_settings_sections' ] );
        add_action( 'current_screen', [ $this, 'acfef_form_head' ] );
        $this->add_tabs();
    }

}