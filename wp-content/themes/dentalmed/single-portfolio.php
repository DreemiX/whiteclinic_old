<?php
/**
 * The template for displaying all portfolio posts
 */

get_header(); ?>

<div class="spcont">

	<?php /* The loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php
		/* translators: %s: Name of current post */
		the_content(
			sprintf(
				esc_html__( 'Continue reading ', 'dentalmed' ),
				'<span class="meta-nav">&rarr;</span>',
				the_title( '<span class="screen-reader-text">', '</span>', false )
			)
		);

		wp_link_pages(
			array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			)
		);
		?>
	<?php endwhile; ?>
</div><!-- #primary -->
<div class="container">
	<?php comments_template(); ?>
</div>

<?php get_footer(); ?>
