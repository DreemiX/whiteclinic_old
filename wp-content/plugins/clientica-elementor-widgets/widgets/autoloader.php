<?php
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 */

spl_autoload_register( 'abew_autoload' );

/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * The argument should be in the form: SSC\Namespace. The
 * function will then break the fully-qualified class name into its pieces and
 * will then build a file to the path based on the namespace.
 *
 * The namespaces in this plugin map to the paths in the directory structure.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */
function abew_autoload( $class_name ) {

    // If the specified $class_name does not include our namespace, duck out.

    if ( false === strpos( $class_name, 'Clientica_Elementor_Widgets' ) ) {
        return;
    }
	$file_name = '';
//	$class      = 'class-' . strtolower( array_pop($parts) );
//	$folders    = strtolower( implode(DIRECTORY_SEPARATOR, $parts) );
//	$classpath     = dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $folders . DIRECTORY_SEPARATOR . $class . '.php';

    // Split the class name into an array to read the namespace and class.
    $file_parts = explode( '\\', $class_name );

    // Do a reverse loop through $file_parts to build the path to the file.
    $namespace = '';
    for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

        // Read the current component of the file part.
        $current = strtolower( $file_parts[ $i ] );

        $current = str_ireplace( '_', '-', $current );

        // If we're at the first entry, then we're at the filename.
        if ( count( $file_parts ) - 1 === $i ) {

            /* If 'interface' is contained in the parts of the file name, then
             * define the $file_name differently so that it's properly loaded.
             * Otherwise, just set the $file_name equal to that of the class
             * filename structure.
             */
            if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) ) {

                // Grab the name of the interface from its qualified name.
                $interface_name = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
	            array_pop ( $interface_name );
	            $interface_name = array_map ( function($i){
		            return strtolower($i);
	            } , $interface_name );
                $interface_name = implode( '-', $interface_name );

                $file_name = "interface-$interface_name.php";

            } else {
                $file_name = "class-$current.php";
            }
        } else {
            $namespace = DIRECTORY_SEPARATOR . $current . $namespace;
        }
    }

    // Now build a path to the file using mapping to the file location.
    $filepath  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
    $filepath .= $file_name;

    // If the file exists in the specified path, then include it.
    if ( file_exists( $filepath ) ) {
        include_once( $filepath );
    } else {
        wp_die(
	        sprintf( esc_html__( "The file attempting to be loaded at %s does not exist.", 'ssc' ), $filepath )
        );
    }
}
