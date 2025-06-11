<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header(); ?>
<?php
if ( Clienticabuilder_Helpers::is_search() ) {
    ?>
    <header class="page-header">
        <h1 class="page-title"><?php Clienticabuilder_Layout::title_404(); ?></h1>
    </header>
    <p><?php Clienticabuilder_Layout::descr_404(); ?></p>
    <?php get_search_form(); ?>
    <?php
} else {
    ?>
    <div class="e404">

        <?php Clienticabuilder_Layout::icon_404(); ?>

        <header class="page-header">
            <h1 class="page-title"><?php Clienticabuilder_Layout::title_404(); ?></h1>
        </header>
        <p><?php Clienticabuilder_Layout::descr_404(); ?></p>
        <?php get_search_form(); ?>

    </div>
    <?php
}
?>


<?php get_footer(); ?>
