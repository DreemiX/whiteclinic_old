<?php
/**
 * The template for displaying image attachments
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="entry-meta">
					<?php
					$published_text = '<span class="attachment-meta">' . esc_html__( 'Published on', 'dentalmed' ) . ' <time class="entry-date" datetime="%1$s">%2$s</time> in <a href="%3$s" title="' . esc_attr__( 'Return to', 'dentalmed' ) . ' %4$s" rel="gallery">%5$s</a></span>';
					$post_title     = get_the_title( $post->post_parent );
					if ( empty( $post_title ) || 0 === $post->post_parent ) {
						$published_text = '<span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time></span>';
					}

					printf(
						$published_text,
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_url( get_permalink( $post->post_parent ) ),
						esc_attr( wp_strip_all_tags( $post_title ) ),
						$post_title
					);

					$metadata = wp_get_attachment_metadata();
					printf( '<span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s">%3$s </a></span>',
						esc_url( wp_get_attachment_url() ),
						esc_attr__( 'Link to full-size image', 'dentalmed' ),
						esc_attr__( 'Full resolution', 'dentalmed' )

					);

					edit_post_link( esc_attr__( 'Edit', 'dentalmed' ), '<span class="edit-link">', '</span>' );
					?>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-content">
                <nav id="image-navigation" class="navigation image-navigation">
                    <span class="nav-previous"><span
                                class="meta-nav">&larr;</span> <?php previous_image_link( false, esc_html__( 'Previous', 'dentalmed' ) ); ?></span>
                    <span class="nav-next"><?php next_image_link( false, esc_html__( 'Next', 'dentalmed' ) ); ?>
                        <span class="meta-nav">&rarr;</span></span>
                </nav><!-- #image-navigation -->

                <div class="entry-attachment">
                    <div class="attachment">
						<?php
						Clienticabuilder_Layout::the_attached_image();
						?>

						<?php if ( has_excerpt() ) : ?>
                            <div class="entry-caption">
								<?php the_excerpt(); ?>
                            </div>
						<?php endif; ?>
                    </div><!-- .attachment -->
                </div><!-- .entry-attachment -->

				<?php if ( ! empty( $post->post_content ) ) : ?>
                    <div class="entry-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dentalmed' ),
							'after'  => '</div>'
						) ); ?>
                    </div><!-- .entry-description -->
				<?php endif; ?>

            </div><!-- .entry-content -->
        </article><!-- #post -->

		<?php comments_template(); ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
