<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Usado no plugin de transporte -->
	<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/vendor/bootstrap/css/bootstrap.min.css" ?>" />
    <script src="<?= get_site_url()."/assets/vendor/jquery/jquery-3.5.1.min.js"?>"></script>
    <script src="<?= get_site_url()."/assets/vendor/jquery/jquery.mask.js"?>"></script>
    <script src="<?= get_site_url()."/assets/vendor/bootstrap/js/bootstrap.min.js"?>"></script>
	<!-- Usado no plugin de transporte -->

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php $viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' ); ?>
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
hello_elementor_body_open();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	get_template_part( 'template-parts/header' );
}
