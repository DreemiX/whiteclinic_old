<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>

<?php /* The loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

	<?php

	get_template_part( 'content' );
	?>

<?php endwhile; ?>

<?php get_footer(); ?>
