<?php

namespace DynamicContentForElementor\AdminPages;

use DynamicContentForElementor\Plugin;
class Manager
{
    public $features_page;
    public $api;
    public $license;
    /**
     * @var Notices
     */
    public $notices;
    public function __construct()
    {
        $this->features_page = new \DynamicContentForElementor\AdminPages\Features\FeaturesPage();
        $this->api = new \DynamicContentForElementor\AdminPages\Api();
        $this->license = new \DynamicContentForElementor\AdminPages\License();
        $this->notices = new \DynamicContentForElementor\AdminPages\Notices();
        add_action('admin_init', [$this, 'maybe_redirect_to_wizard_on_activation']);
        add_action('admin_menu', [$this, 'add_menu_pages'], 200);
        add_action('admin_notices', [$this, 'warning_old_conditional']);
        add_action('elementor/init', [$this, 'warning_lazyload']);
        add_filter('elementor/admin-top-bar/is-active', [$this, 'deactivate_elementor_top_bar'], 10, 2);
        $this->warning_features_bloat();
    }
    /**
     * Deactivates the Elementor top bar for Dynamic Content for Elementor pages.
     *
     * @param bool $is_active Whether the Elementor top bar is active.
     * @param \WP_Screen $current_screen The current screen.
     * @return bool Whether the Elementor top bar should be active.
     */
    public function deactivate_elementor_top_bar($is_active, $current_screen)
    {
        if ($current_screen && \false !== \strpos($current_screen->id, 'dynamic-content-for-elementor')) {
            return \false;
        }
        return $is_active;
    }
    public function maybe_redirect_to_wizard_on_activation()
    {
        if (!get_transient('dce_activation_redirect')) {
            return;
        }
        if (wp_doing_ajax()) {
            return;
        }
        delete_transient('dce_activation_redirect');
        if (is_network_admin() || isset($_GET['activate-multi'])) {
            return;
        }
        if (get_option('dce_done_activation_redirection')) {
            return;
        }
        update_option('dce_done_activation_redirection', \true);
        wp_safe_redirect(admin_url('admin.php?page=dce-features'));
        exit;
    }
    public static function get_dynamic_ooo_icon_svg_base64()
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88.74 71.31"><path d="M35.65,588.27h27.5c25.46,0,40.24,14.67,40.24,35.25v.2c0,20.58-15,35.86-40.65,35.86H35.65Zm27.81,53.78c11.81,0,19.65-6.51,19.65-18v-.2c0-11.42-7.84-18-19.65-18H55.41v36.26Z" transform="translate(-35.65 -588.27)" fill="#a8abad"/><path d="M121.69,609.94a33.84,33.84,0,0,0-7.56-11.19,36.51,36.51,0,0,0-11.53-7.56A37.53,37.53,0,0,0,88,588.4a43.24,43.24,0,0,0-5.4.34,36.53,36.53,0,0,1,20.76,10,33.84,33.84,0,0,1,7.56,11.19,35.25,35.25,0,0,1,2.7,13.79v.2a34.79,34.79,0,0,1-2.75,13.79,35.21,35.21,0,0,1-19.19,18.94,36.48,36.48,0,0,1-9.27,2.45,42.94,42.94,0,0,0,5.39.35,37.89,37.89,0,0,0,14.67-2.8,35.13,35.13,0,0,0,19.19-18.94,34.79,34.79,0,0,0,2.75-13.79v-.2A35.25,35.25,0,0,0,121.69,609.94Z" transform="translate(-35.65 -588.27)" fill="#a8abad" /></svg>';
        return \base64_encode($svg);
    }
    public function add_menu_pages()
    {
        // Menu
        add_menu_page(DCE_PRODUCT_NAME, DCE_PRODUCT_NAME, 'manage_options', 'dce-features', [$this->features_page, 'page_callback'], 'data:image/svg+xml;base64,' . self::get_dynamic_ooo_icon_svg_base64(), '58.6');
        // Features
        add_submenu_page('dce-features', DCE_PRODUCT_NAME_LONG . ' - ' . esc_html__('Features', 'dynamic-content-for-elementor'), esc_html__('Features', 'dynamic-content-for-elementor'), 'manage_options', 'dce-features', [$this->features_page, 'page_callback']);
        // HTML Templates (only for PDF Generator for Elementor Pro Form or PDF Button)
        if (Plugin::instance()->features->is_feature_active('ext_form_pdf') || Plugin::instance()->features->is_feature_active('wdg_pdf')) {
            add_submenu_page('dce-features', DCE_PRODUCT_NAME_LONG . ' - ' . esc_html__('HTML Templates', 'dynamic-content-for-elementor'), esc_html__('HTML Templates', 'dynamic-content-for-elementor'), 'manage_options', 'edit.php?post_type=' . \DynamicContentForElementor\PdfHtmlTemplates::CPT);
        }
        // Integrations
        add_submenu_page('dce-features', DCE_PRODUCT_NAME_LONG . ' - ' . esc_html__('Integrations', 'dynamic-content-for-elementor'), esc_html__('Integrations', 'dynamic-content-for-elementor'), 'manage_options', 'dce-integrations', [$this->api, 'display_form']);
        // License
        add_submenu_page('dce-features', DCE_PRODUCT_NAME_LONG . ' - ' . esc_html__('License', 'dynamic-content-for-elementor'), esc_html__('License', 'dynamic-content-for-elementor'), 'administrator', 'dce-license', [$this->license, 'show_license_form']);
    }
    /**
     * @return void
     */
    public function warning_lazyload()
    {
        $lazyload = \Elementor\Plugin::instance()->experiments->is_feature_active('e_lazyload');
        if ($lazyload) {
            $msg = esc_html__('The Elementor Experiment Lazy Load is not currently compatible with all Dynamic Content for Elementor features, in particular it causes problems with background images inside a loop.', 'dynamic-content-for-elementor');
            \DynamicContentForElementor\Plugin::instance()->admin_pages->notices->warning($msg, 'lazyload');
        }
    }
    public function warning_old_conditional()
    {
        if (isset($_POST['save-dce-feature'])) {
            return;
            // settings are being saved, we can't be sure of the extension status.
        }
        $features = \DynamicContentForElementor\Plugin::instance()->features->get_features_status();
        if ($features['ext_form_visibility'] === 'active') {
            $msg = esc_html__('It appears that the extension Conditional Fields (old version) for Elementor Pro Form is enabled. Notice that this is a legacy extension that is known to cause problems with form validation. We recommend disabling it if you don’t need it. You can do it from the ', 'dynamic-content-for-elementor');
            $url = admin_url('admin.php?page=dce-features&tab=legacy');
            $msg .= "<a href='{$url}'>" . esc_html__('Features Dashboard', 'dynamic-content-for-elementor') . '</a>.';
            $msg .= ' ' . esc_html__('You can use the new version instead: Conditional Fields for Elementor Pro Form.', 'dynamic-content-for-elementor');
            $msg .= " <a href='https://help.dynamic.ooo/en/articles/5576284-switch-conditional-fields-old-version-to-conditional-fields-v2-for-elementor-pro-form'>" . esc_html__('Read more...', 'dynamic-content-for-elementor') . '</a>';
            \DynamicContentForElementor\Plugin::instance()->admin_pages->notices->error($msg);
        }
    }
    public function warning_features_bloat()
    {
        if (isset($_POST['save-dce-feature'])) {
            return;
            // settings are being saved, we can't be sure of the feature status.
        }
        $features = \DynamicContentForElementor\Plugin::instance()->features->filter(['legacy' => \true], 'NOT');
        $active = \array_filter($features, function ($f) {
            return $f['status'] === 'active';
        });
        $ratio = \count($active) / \count($features);
        if ($ratio > 0.95) {
            $msg = esc_html__('Most features are currently active. This could slow down the Elementor Editor. It is recommended that you disable the features you don\'t need. This can be done on the ', 'dynamic-content-for-elementor');
            $url = admin_url('admin.php?page=dce-features');
            $msg .= "<a href='{$url}'>" . esc_html__('Features Page', 'dynamic-content-for-elementor') . '</a>.';
            $this->notices->warning($msg, 'features_bloat');
        }
    }
}
