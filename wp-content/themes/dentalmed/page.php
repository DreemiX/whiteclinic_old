<?php
/*

 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 */

get_header(); ?>


<?php /* The loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="<?php Clienticabuilder_Layout::is_elementor_class(); ?>">
			<?php
			Clienticabuilder_Layout::entry_header();
			?>
            <div class="entry-meta hidden">
				<?php
				Clienticabuilder_Layout::page_entry_meta();
				?>
            </div><!-- .entry-meta -->
            <div class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages(
					array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					)
				); ?>
            </div><!-- .entry-content -->

			<?php edit_post_link( esc_html__( 'Edit', 'dentalmed' ), '<div class="pageedit_link"><span class="edit-link">', '</span></div>' ); ?>
            <div>
				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
            </div>
        </div> <!-- KC container -->
    </article><!-- #post -->

<?php endwhile; ?>

<?php get_footer(); ?>