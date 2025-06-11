<?php

require CLIENTICA_INSTALLER_PATH . '/modules/wordpress-importer/wordpress-importer.php';


if ( class_exists( 'WP_Import' ) ) {
	class Clientica_Import extends WP_Import {


		/**
		 * Attempt to create a new menu item from import data
		 *
		 * Fails for draft, orphaned menu items and those without an associated nav_menu
		 * or an invalid nav_menu term. If the post type or term object which the menu item
		 * represents doesn't exist then the menu item will not be imported (waits until the
		 * end of the import to retry again before discarding).
		 *
		 * @param array $item Menu item details from WXR file
		 */
		function process_menu_item( $item ) {
			// skip draft, orphaned menu items
			if ( 'draft' == $item['status'] )
				return;

			$menu_slug = false;
			if ( isset($item['terms']) ) {
				// loop through terms, assume first nav_menu term is correct menu
				foreach ( $item['terms'] as $term ) {
					if ( 'nav_menu' == $term['domain'] ) {
						$menu_slug = $term['slug'];
						break;
					}
				}
			}

			// no nav_menu term associated with this menu item
			if ( ! $menu_slug ) {
				_e( 'Menu item skipped due to missing menu slug', 'clientica-installer' );
				echo '<br />';
				return;
			}

			$menu_id = term_exists( $menu_slug, 'nav_menu' );
			if ( ! $menu_id ) {
				printf( esc_attr__( 'Menu item skipped due to invalid menu slug: %s', 'clientica-installer' ), esc_html( $menu_slug ) );
				echo '<br />';
				return;
			} else {
				$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
			}

			foreach ( $item['postmeta'] as $meta ) {
				${$meta['key']} = $meta['value'];
				$menu_item_meta[$meta['key']] = $meta['value'];
			}

			if ( 'taxonomy' == $_menu_item_type && isset( $this->processed_terms[intval($_menu_item_object_id)] ) ) {
				$_menu_item_object_id = $this->processed_terms[intval($_menu_item_object_id)];
			} else if ( 'post_type' == $_menu_item_type && isset( $this->processed_posts[intval($_menu_item_object_id)] ) ) {
				$_menu_item_object_id = $this->processed_posts[intval($_menu_item_object_id)];
			} else if ( 'custom' != $_menu_item_type ) {
				// associated object is missing or not imported yet, we'll retry later
				$this->missing_menu_items[] = $item;
				return;
			}

			if ( isset( $this->processed_menu_items[intval($_menu_item_menu_item_parent)] ) ) {
				$_menu_item_menu_item_parent = $this->processed_menu_items[intval($_menu_item_menu_item_parent)];
			} else if ( $_menu_item_menu_item_parent ) {
				$this->menu_item_orphans[intval($item['post_id'])] = (int) $_menu_item_menu_item_parent;
				$_menu_item_menu_item_parent = 0;
			}

			// wp_update_nav_menu_item expects CSS classes as a space separated string
			$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
			if ( is_array( $_menu_item_classes ) )
				$_menu_item_classes = implode( ' ', $_menu_item_classes );

			$args = array(
				'menu-item-object-id' => $_menu_item_object_id,
				'menu-item-object' => $_menu_item_object,
				'menu-item-parent-id' => $_menu_item_menu_item_parent,
				'menu-item-position' => intval( $item['menu_order'] ),
				'menu-item-type' => $_menu_item_type,
				'menu-item-title' => $item['post_title'],
				'menu-item-url' => $_menu_item_url,
				'menu-item-description' => $item['post_content'],
				'menu-item-attr-title' => $item['post_excerpt'],
				'menu-item-target' => $_menu_item_target,
				'menu-item-classes' => $_menu_item_classes,
				'menu-item-xfn' => $_menu_item_xfn,
				'menu-item-status' => $item['status']
			);

			$id = wp_update_nav_menu_item( $menu_id, 0, $args );
			//if ( $id && ! is_wp_error( $id ) )
			//$this->processed_menu_items[intval($item['post_id'])] = (int) $id;

			if ( $id && is_wp_error( $id ) ) {
				echo $id->get_error_message();
				echo '<br>';
			}
			else {
				foreach ( $args as $a => $arg ) {
					unset( $menu_item_meta[ '_' . str_replace('-', '_', $a) ]);
				}
				unset ( $menu_item_meta['_menu_item_menu_item_parent'] );
				$menu_item_meta = array_diff_assoc( $menu_item_meta, $args );
				if ( ! empty ( $menu_item_meta ) ) {
					foreach( $menu_item_meta as $key => $value ) {
						update_post_meta( (int) $id, $key, maybe_unserialize( $value ) );
					}
				}
			}

			$this->processed_menu_items[intval($item['post_id'])] = (int) $id;

		}
	}



	function clientica_wordpress_importer_init() {
		if ( ! function_exists( 'wordpress_importer_init' ) ) {
			$GLOBALS['wp_import'] = new Clientica_Import();
			register_importer( 'wordpress',
				'WordPress',
				__( 'Import <strong>posts, pages, comments, custom fields, categories, and tags</strong> from a WordPress export file.',
					'clientica-installer' ),
				array( $GLOBALS['wp_import'], 'dispatch' ) );
		}
	}

	add_action( 'admin_init', 'clientica_wordpress_importer_init' );
}