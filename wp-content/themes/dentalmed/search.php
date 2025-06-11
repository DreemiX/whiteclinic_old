<?php
/**
 * The template for displaying Search Results pages
 */

get_header(); ?>

<?php if ( have_posts() ) : ?>


	<?php /* The loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content' ); ?>
	<?php endwhile; ?>

	<?php
	Clienticabuilder_Layout::archive_nav();
	?>

<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>


<?php get_footer(); ?>