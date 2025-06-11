<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<div class="<?php Clienticabuilder_Blog::number_of_columns(); ?>">

	<?php if ( have_posts() ) : ?>


		<?php

		/* The loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content' ); ?>
		<?php endwhile; ?>

		<?php
		Clienticabuilder_Layout::archive_nav();
		?>

	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>

</div><!-- #primary -->

<?php get_footer(); ?>
