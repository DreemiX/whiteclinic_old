<?php
/**
 * The template for displaying Post Format pages
 *
 * Used to display archive-type pages for posts with a post format.
 * If you'd like to further customize these Post Format views, you may create a
 * new template file for each specific one.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>
            <header class="archive-header">
                <h1 class="archive-title"><?php printf( esc_html__( '%s Archives', 'dentalmed' ), '<span>' . esc_html( get_post_format_string( get_post_format() ) ) . '</span>' ); ?></h1>
            </header><!-- .archive-header -->

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content' ); ?>
			<?php endwhile; ?>

			<?php
			Clienticabuilder_Layout::paging_nav();
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

    </div><!-- #content -->
</div><!-- #primary -->


<?php get_footer(); ?>
