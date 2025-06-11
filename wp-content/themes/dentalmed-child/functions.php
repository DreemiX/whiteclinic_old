<?php

function atiframebuilder_enqueue_styles() {

    $parent_style = 'atiframebuilder-ownstyles';

    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'atiframebuilder_enqueue_styles' );
wp_enqueue_script( 'jquery' );

/*======================================
YOU CAN PUT YOUR OWN php CODE AND FUNCTIONS HERE
=======================================*/
// Our custom post type function
function create_posttype() {
 
    register_post_type( 'clinic-services',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Services' ),
                'singular_name' => __( 'Service' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'clinic-services'),
            'show_in_rest' => true,
 
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => __( 'Services', 'Post Type General Name', 'dentalmed' ),
        'singular_name'       => __( 'Service', 'Post Type Singular Name', 'dentalmed' ),
        'menu_name'           => __( 'Services', 'dentalmed' ),
        'parent_item_colon'   => __( 'Parent Service', 'dentalmed' ),
        'all_items'           => __( 'All Services', 'dentalmed' ),
        'view_item'           => __( 'View Service', 'dentalmed' ),
        'add_new_item'        => __( 'Add New Service', 'dentalmed' ),
        'add_new'             => __( 'Add New', 'dentalmed' ),
        'edit_item'           => __( 'Edit Service', 'dentalmed' ),
        'update_item'         => __( 'Update Service', 'dentalmed' ),
        'search_items'        => __( 'Search Service', 'dentalmed' ),
        'not_found'           => __( 'Not Found', 'dentalmed' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'dentalmed' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Services', 'dentalmed' ),
        'description'         => __( 'Services WhiteClinic', 'dentalmed' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'service-cat' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        // добавит поддержку меток к custom post type
        'taxonomies' => array('post_tag')
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'clinic-services', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );

function wptp_register_taxonomy() {
  register_taxonomy( 'service-cat', 'clinic-services',
    array(
      'labels' => array(
        'name'              => 'Services Categories',
        'singular_name'     => 'Service Category',
        'search_items'      => 'Search Services Categories',
        'all_items'         => 'All Service Categories',
        'edit_item'         => 'Edit Service Categories',
        'update_item'       => 'Update Service Category',
        'add_new_item'      => 'Add New Service Category',
        'new_item_name'     => 'New Service Category Name',
        'menu_name'         => 'Service Category',
        ),
      'hierarchical' => true,
      'sort' => true,
      'args' => array( 'orderby' => 'term_order' ),
      'show_admin_column' => true
      )
    );
}
add_action( 'init', 'wptp_register_taxonomy' );


add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
  wp_enqueue_style( 'dashicons' );
}

function create_custom_post_types() {
    // Лікарі
    $labels_doctors = array(
        'name'               => _x( 'Лікарі', 'post type general name', 'dentalmed' ),
        'singular_name'      => _x( 'Лікар', 'post type singular name', 'dentalmed' ),
        'menu_name'          => _x( 'Лікарі', 'admin menu', 'dentalmed' ),
        'name_admin_bar'     => _x( 'Лікар', 'add new on admin bar', 'dentalmed' ),
        'add_new'            => _x( 'Додати нового', 'лікар', 'dentalmed' ),
        'add_new_item'       => __( 'Додати нового лікаря', 'dentalmed' ),
        'new_item'           => __( 'Новий лікар', 'dentalmed' ),
        'edit_item'          => __( 'Редагувати лікаря', 'dentalmed' ),
        'view_item'          => __( 'Переглянути лікаря', 'dentalmed' ),
        'all_items'          => __( 'Всі лікарі', 'dentalmed' ),
        'search_items'       => __( 'Знайти лікарів', 'dentalmed' ),
        'parent_item_colon'  => __( 'Батьківські:', 'dentalmed' ),
        'not_found'          => __( 'Лікарів не знайдено.', 'dentalmed' ),
        'not_found_in_trash' => __( 'Лікарів у кошику не знайдено.', 'dentalmed' )
    );

    $args_doctors = array(
        'labels'             => $labels_doctors,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'doctors' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'doctors', $args_doctors );

    // Акції
    $labels_promotions = array(
        'name'               => _x( 'Акції', 'post type general name', 'dentalmed' ),
        'singular_name'      => _x( 'Акція', 'post type singular name', 'dentalmed' ),
        'menu_name'          => _x( 'Акції', 'admin menu', 'dentalmed' ),
        'name_admin_bar'     => _x( 'Акція', 'add new on admin bar', 'dentalmed' ),
        'add_new'            => _x( 'Додати нову', 'акція', 'dentalmed' ),
        'add_new_item'       => __( 'Додати нову акцію', 'dentalmed' ),
        'new_item'           => __( 'Нова акція', 'dentalmed' ),
        'edit_item'          => __( 'Редагувати акцію', 'dentalmed' ),
        'view_item'          => __( 'Переглянути акцію', 'dentalmed' ),
        'all_items'          => __( 'Всі акції', 'dentalmed' ),
        'search_items'       => __( 'Знайти акції', 'dentalmed' ),
        'parent_item_colon'  => __( 'Батьківські акції:', 'dentalmed' ),
        'not_found'          => __( 'Акцій не знайдено.', 'dentalmed' ),
        'not_found_in_trash' => __( 'Акцій у кошику не знайдено.', 'dentalmed' )
    );

    $args_promotions = array(
        'labels'             => $labels_promotions,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'promotions' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'promotions', $args_promotions );

    // Ціни
    $labels_prices = array(
        'name'               => _x( 'Ціни', 'post type general name', 'dentalmed' ),
        'singular_name'      => _x( 'Ціна', 'post type singular name', 'dentalmed' ),
        'menu_name'          => _x( 'Ціни', 'admin menu', 'dentalmed' ),
        'name_admin_bar'     => _x( 'Ціна', 'add new on admin bar', 'dentalmed' ),
        'add_new'            => _x( 'Додати нову', 'ціна', 'dentalmed' ),
        'add_new_item'       => __( 'Додати нову ціну', 'dentalmed' ),
        'new_item'           => __( 'Нова ціна', 'dentalmed' ),
        'edit_item'          => __( 'Редагувати ціну', 'dentalmed' ),
        'view_item'          => __( 'Переглянути ціну', 'dentalmed' ),
        'all_items'          => __( 'Всі ціни', 'dentalmed' ),
        'search_items'       => __( 'Знайти ціни', 'dentalmed' ),
        'parent_item_colon'  => __( 'Батьківські ціни:', 'dentalmed' ),
        'not_found'          => __( 'Цін не знайдено.', 'dentalmed' ),
        'not_found_in_trash' => __( 'Цін у кошику не знайдено.', 'dentalmed' )
    );

    $args_prices = array(
        'labels'             => $labels_prices,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'prices' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'prices', $args_prices );
}

add_action( 'init', 'create_custom_post_types' );

function register_promotion_tags() {
    $labels = array(
        'name'                       => _x( 'Теги акцій', 'taxonomy general name', 'dentalmed' ),
        'singular_name'              => _x( 'Тег акції', 'taxonomy singular name', 'dentalmed' ),
        'search_items'               => __( 'Знайти теги акцій', 'dentalmed' ),
        'popular_items'              => __( 'Популярні теги акцій', 'dentalmed' ),
        'all_items'                  => __( 'Всі теги акцій', 'dentalmed' ),
        'edit_item'                  => __( 'Редагувати тег акції', 'dentalmed' ),
        'update_item'                => __( 'Оновити тег акції', 'dentalmed' ),
        'add_new_item'               => __( 'Додати новий тег акції', 'dentalmed' ),
        'new_item_name'              => __( 'Нова назва тега акції', 'dentalmed' ),
        'separate_items_with_commas' => __( 'Розділіть теги комами', 'dentalmed' ),
        'add_or_remove_items'        => __( 'Додати або видалити теги', 'dentalmed' ),
        'choose_from_most_used'      => __( 'Вибрати з найбільш використовуваних тегів', 'dentalmed' ),
        'not_found'                  => __( 'Тегів не знайдено', 'dentalmed' ),
        'menu_name'                  => __( 'Теги акцій', 'dentalmed' ),
    );

    $args = array(
        'hierarchical'          => false, // Не ієрархічна таксономія, схожа на теги
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'promotion-tag' ),
    );

    register_taxonomy( 'promotion-tag', 'promotions', $args );
}

add_action( 'init', 'register_promotion_tags', 0 );

function register_taxonomy_for_prices() {
    $labels = array(
        'name'              => _x('Категорії цін', 'taxonomy general name', 'dentalmed'),
        'singular_name'     => _x('Категорія цін', 'taxonomy singular name', 'dentalmed'),
        'search_items'      => __('Знайти категорії цін', 'dentalmed'),
        'all_items'         => __('Всі категорії цін', 'dentalmed'),
        'parent_item'       => __('Батьківська категорія цін', 'dentalmed'),
        'parent_item_colon' => __('Батьківська категорія цін:', 'dentalmed'),
        'edit_item'         => __('Редагувати категорію цін', 'dentalmed'),
        'update_item'       => __('Оновити категорію цін', 'dentalmed'),
        'add_new_item'      => __('Додати нову категорію цін', 'dentalmed'),
        'new_item_name'     => __('Нова назва категорії цін', 'dentalmed'),
        'menu_name'         => __('Категорії цін', 'dentalmed'),
    );

    $args = array(
        'hierarchical'      => true, // Сетьте true, якщо потрібні категорії (як у стандартних постах), а false, якщо потрібні мітки (tags)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'price-category'),
    );

    register_taxonomy('price_category', array('prices'), $args);
}
add_action('init', 'register_taxonomy_for_prices');







/* Вимкнути XML-RPC */
add_filter('xmlrpc_enabled', '__return_false');

/*DOM
function remove_empty_dom_elements() {
   <?php 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var allElements = document.getElementsByTagName("*");

            for (var i = 0, len = allElements.length; i < len; i++) {
                var element = allElements[i];

                // Перевірка, чи елемент пустий (без тексту та вкладених елементів)
                if (element.childNodes.length === 0 && element.innerText.trim() === '') {
                    element.parentNode.removeChild(element);
                }
            }
        });
    </script>
     ?>
}
add_action('wp_footer', 'remove_empty_dom_elements');

*/


/*прибирає псевдо елемент
function disableAfterPseudoElement(elementId) {
    var element = document.getElementById(elementId);
    if (element) {
        var style = document.createElement('style');
        style.innerHTML = '#' + elementId + ':after { content: none; }';
        document.head.appendChild(style);
    }
}*/




function enqueue_custom_scripts() {
    wp_enqueue_script('custom-popup-close', get_stylesheet_directory_uri() . '/custom-popup-close.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');




// Функціонал для оновлення цін по АПІ///

function update_service_prices_from_api() {
    $start = get_option('update_service_prices_start', 0);
    $limit = 10; // Кількість елементів для обробки за один раз

    $api_url = 'https://cliniccards.com/api/prices';
    $response = wp_remote_get($api_url, [
        'headers' => [
            'Token' => 'XOVdV2DE7Qmq5jKtTG6KM8Ey7FIQnGcAhG1A',
            'Content-Type' => 'application/json'
        ]
    ]);

    if (is_wp_error($response)) {
        error_log('Помилка при зверненні до API: ' . $response->get_error_message());
        return;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $data = $body['data'];

    // Розділення на порції
    $data_chunk = array_slice($data, $start, $limit);

    $groupedPrices = [];
    foreach ($data_chunk as $item) {
        $groupedPrices[$item['group_name']][] = $item;
    }

    $flexible_content = [];
    foreach ($groupedPrices as $group_name => $items) {
        $layouts = [];
        foreach ($items as $item) {
            $layouts[] = [
                'acf_fc_layout' => 'prices_layout', // Назва вашого layout
                'item_name' => $item['item_name'],
                'item_price' => $item['item_price'],
                'item_code' => $item['item_code'],
            ];
        }

        $flexible_content[] = [
            'group_name' => $group_name, 
            'layouts' => $layouts
        ];
    }

    update_field('field_660a99a883cc2', $flexible_content, 'option');

    $new_start = $start + $limit;
    update_option('update_service_prices_start', $new_start >= count($data) ? 0 : $new_start);
}

if (!wp_next_scheduled('update_service_prices_hook')) {
    wp_schedule_event(time(), 'hourly', 'update_service_prices_hook');
}

add_action('update_service_prices_hook', 'update_service_prices_from_api');




?>