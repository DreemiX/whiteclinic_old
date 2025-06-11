<?php
/*
The file contain functions for Woocommerce.
*/

class Clienticabuilder_Woocommerce {

    public function __construct() {

        /* Shop Settings */
        add_action( 'after_setup_theme', array( $this, 'woocommerce_support' ) );

        // Change columns count to 3
        add_filter('loop_shop_columns', array( $this, 'loop_columns' ) );

        add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );

	    add_action( 'wp_enqueue_scripts', array( $this, 'woo_css' ) );

        add_filter('woocommerce_billing_fields', array( $this, 'custom_billing_fields' ) );

        add_filter( 'woocommerce_checkout_fields' , array( $this, 'override_checkout_fields_ek' ), 99 );


    }

    public function woocommerce_support() {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    public function loop_columns() {
        if ( ! empty( Clienticabuilder_Core::$page_options['product_columns'] ) ) {
            $quo = Clienticabuilder_Core::$page_options['product_columns'];
        } else {
            $quo = 4;
        }
        return $quo; // number of products per row
    }

    public function related_products_args($args){
        if ( ! empty( Clienticabuilder_Core::$page_options['relates_product_products'] ) ) {
            $rpp = Clienticabuilder_Core::$page_options['relates_product_products'];
        } else {
            $rpp = 4;
        }
        if ( ! empty( Clienticabuilder_Core::$page_options['relates_product_columns'] ) ) {
            $rpc = Clienticabuilder_Core::$page_options['relates_product_columns'];
        } else {
            $rpc = 4;
        }
        $args['posts_per_page'] = $rpp; // number of related products
        $args['columns'] = $rpc; // arranged in 2 columns
        return $args;
    }

    public function woo_css() {
	    if ( ! empty( Clienticabuilder_Core::$page_options['product_columns'] ) ) {
		    $quo = Clienticabuilder_Core::$page_options['product_columns'];
	    } else {
		    $quo = 4;
	    }

	    if ( ! empty( Clienticabuilder_Core::$page_options['relates_product_columns'] ) ) {
		    $rpc = Clienticabuilder_Core::$page_options['relates_product_columns'];
	    } else {
		    $rpc = 4;
	    }
        $woo_cust_css = '';
        if ( ! empty( Clienticabuilder_Core::$page_options['product_columns'] ) ) {
            $woo_cust_css .= '
            
            html .woocommerce ul.products li.product {width: calc((103.8% /' . $quo . ') - 3.8%);margin-right: 3.8%;}
            html .woocommerce ul.products li.product:nth-child(' . $quo . 'n+1), html .woocommerce-page ul.products li.product:nth-child(' . $quo . 'n+1), html .woocommerce-page[class*=columns-] ul.products li.product:nth-child(' . $quo . 'n+1), html .woocommerce[class*=columns-] ul.products li.product:nth-child(' . $quo . 'n+1) { clear:both}
            
	        ';
        }
        if ( ! empty( Clienticabuilder_Core::$page_options['relates_product_columns'] ) ) {
            $woo_cust_css .= '
            html .woocommerce-page .related.products ul.products li.product:nth-child(' . $quo . 'n+1) {clear:none}
            html .woocommerce-page .related.products ul.products li.product:nth-child(' . $rpc . 'n+1) {clear:both}
            html .woocommerce .related.products ul.products li.product {width: calc((103.8%/' . $rpc . ') - 3.8%); margin-right: 3.8%;}
            html .woocommerce .related.products ul.products li.product:nth-child(' . $rpc . 'n) {margin-right: 0;}
            ';

        }
        if ( ! empty( $woo_cust_css ) ) {
            wp_add_inline_style( 'clienticabuilder-ownstyles', $woo_cust_css );
        }
    }

    //remove some fields from billing form
    //ref - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
    public function custom_billing_fields( $fields = array() ) {
        if ( isset( Clienticabuilder_Core::$page_options['woocomp'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woocomp'] ) {
                unset( $fields['billing_company'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['wooadd1'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['wooadd1'] ) {
                unset( $fields['billing_address_1'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['wooadd2'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['wooadd2'] ) {
                unset( $fields['billing_address_2'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woostate'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woostate']) {
                unset( $fields['billing_state'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woocity'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woocity'] ) {
                unset( $fields['billing_city'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woophone'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woophone'] ) {
                unset( $fields['billing_phone'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woopostcode'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woopostcode'] ) {
                unset( $fields['billing_postcode'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woocountry'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woocountry'] ) {
                unset( $fields['billing_country'] );
            }
        }

        return $fields;
    }

    // Remove some fields from billing form
    // Our hooked in function - $fields is passed via the filter!
    // Get all the fields - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/

    public function override_checkout_fields_ek( $fields ) {
        if ( isset( Clienticabuilder_Core::$page_options['woocomp'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woocomp'] ) {
                unset( $fields['billing']['billing_company'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['wooadd1'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['wooadd1'] ) {
                unset( $fields['billing']['billing_address_1'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woopostcode'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woopostcode'] ) {
                unset( $fields['billing']['billing_postcode'] );
            }
        }
        if ( isset( Clienticabuilder_Core::$page_options['woostate'] ) ) {
            if ( '0' === Clienticabuilder_Core::$page_options['woostate'] ) {
                unset( $fields['billing']['billing_state'] );
            }
        }
        return $fields;
    }


}

?>
