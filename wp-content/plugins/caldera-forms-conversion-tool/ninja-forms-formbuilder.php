<?php
/*
Plugin Name: Caldera Forms Conversion Tool
Description: Convert Caldera Forms forms to Ninja Forms forms.
Version: 1.0.0
Author: Saturday Drive
Text Domain: cf_conversion_tool
Domain Path: /languages/

Copyright 2021 WP Ninjas.
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!defined('CF_CONVERSION_TOOL_PATH')) {
    define('CF_CONVERSION_TOOL_PATH', plugin_dir_path(__FILE__));
}
if (!defined('CF_CONVERSION_TOOL_URL')) {
    define('CF_CONVERSION_TOOL_URL', plugin_dir_url(__FILE__));
}
if (!defined('CF_CONVERSION_TOOL_BASENAME')) {
    define('CF_CONVERSION_TOOL_BASENAME', plugin_basename(__FILE__));
}

add_action('plugins_loaded', 'cf_conversion_tool_plugins_loaded', 0);


function cf_conversion_tool_plugins_loaded()
{
    if (version_compare(PHP_VERSION, '7.1.0', '>=')) {
        if (class_exists('Ninja_Forms')) {
            include_once __DIR__ . '/bootstrap.php';
        } else {
            //Ninja Forms is not active
        }
    } else {
        //add_action('admin_notices', 'cf_conversion_tool_php_nag');
    }
}
