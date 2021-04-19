<?php 
// Template Name: Listagem das solicitações do usuário

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}



get_header(); ?>


	<div id="primary" <?php astra_primary_class(); ?>>



		<?php astra_primary_content_top(); ?>


        <?php astra_entry_content_before(); ?>

		<?php 
        the_content();
        get_template_part('template-transport/list-solicitacao'); ?>



		<?php astra_primary_content_bottom(); ?>



	</div><!-- #primary -->

<?php get_footer(); ?>

