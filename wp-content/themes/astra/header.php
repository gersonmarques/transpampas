<?php

/**

 * The header for Astra Theme.

 *

 * This is the template that displays all of the <head> section and everything up until <div id="content">

 *

 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials

 *

 * @package Astra

 * @since 1.0.0

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}



?><!DOCTYPE html>

<?php astra_html_before(); ?>

<html <?php language_attributes(); ?>>

<head>

<?php astra_head_top(); ?>

<!-- Usado no plugin de transporte -->
<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/vendor/bootstrap/css/bootstrap.min.css" ?>" />
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery-3.5.1.min.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery.mask.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/bootstrap/js/bootstrap.min.js"?>"></script>
<!-- Usado no plugin de transporte -->

<meta charset="<?php bloginfo( 'charset' ); ?>">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="profile" href="https://gmpg.org/xfn/11">



<?php wp_head(); ?>

<?php astra_head_bottom(); ?>

</head>



<body <?php astra_schema_body(); ?> <?php body_class(); ?>>



<?php astra_body_top(); ?>

<?php wp_body_open(); ?>

<div 

	<?php

	echo astra_attr(

		'site',

		array(

			'id'    => 'page',

			'class' => 'hfeed site',

		)

	);

	?>

>

	<a class="skip-link screen-reader-text" href="#content"><?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?></a>



	<?php astra_header_before(); ?>



	<?php astra_header(); ?>



	<?php astra_header_after(); ?>



	<?php astra_content_before(); ?>



	<div id="content" class="site-content">



		<div class="ast-container">



		<?php astra_content_top(); ?>

