<?php
/**
 * The template for displaying the footer
 */
?>
<!-- footer -->
<?php
Clienticabuilder_Footer::set_footer_sidebar_layout();
?>
</main>

<?php

Clienticabuilder_Footer::footer();

Clienticabuilder_Layout::scroll_button();

Clienticabuilder_Footer::footer_close_boxed_layout();
?>

<?php wp_footer(); ?>

</body>
</html>