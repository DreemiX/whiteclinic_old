<?php
/**
 * The template for displaying a "No posts found" message
 */
?>
<?php
if ( Clienticabuilder_Helpers::is_search() ) {

    ?>
    <div class="sres">
        <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'dentalmed' ); ?></p>
        <?php get_search_form(); ?>
    </div>
    <?php
} else {

    ?>
    <div class="e404">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                Clienticabuilder_Layout::title_404();
                ?>
            </h1>
        </header>

        <?php if ( Clienticabuilder_Helpers::is_home() && current_user_can( 'publish_posts' ) ) : ?>

            <p><?php printf( esc_html__( 'Ready to publish your first post?', 'dentalmed' ), '<a href="%1$s">' . esc_html__( 'Get started here.', 'dentalmed' ) . '</a>', esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

        <?php elseif ( Clienticabuilder_Helpers::is_search() ) : ?>

            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'dentalmed' ); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'dentalmed' ); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>

    </div>
    <?php
}
?>


