<?php
/**
 * The template for displaying Author archive pages
 */

get_header(); ?>


<?php if ( have_posts() ) : ?>

	<?php
	/*
	 * Queue the first post, that way we know what author
	 * we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	the_post();
	?>


	<?php
	/*
	 * Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();
	?>


	<?php /* The loop */ ?>
	<?php while ( have_posts() ) :the_post(); ?>
		<?php get_template_part( 'content' ); ?>
	<?php endwhile; ?>

	<?php
	Clienticabuilder_Layout::paging_nav();
	?>

<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>


<?php get_footer(); ?>
