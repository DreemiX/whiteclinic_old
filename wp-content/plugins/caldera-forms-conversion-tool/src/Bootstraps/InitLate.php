<?php

namespace NinjaForms\CfConversionTool\Bootstraps;

use NinjaForms\CfConversionTool\Factories\CfTranslatorFactory;
use NinjaForms\CfConversionTool\Master;

use \Caldera_Forms_Forms;
use \WP_REST_Response;
use \WP_Error;

/**
 * Bootstrap functionality hooked at init priority 15
 *
 * Default priority is 10; this runs after all init functions not explicitly
 * prioritized.
 *
 * The bootstrap file calls this class with an action hook at 'init' priority
 *      15.  This enables anything in the class to run after any init hooks not
 *      explicitly prioritized after init 15.  This can be used for situations
 *      where other plugins must be loaded and initialized before the
 *      functionality contained herein.
 *
 * @package Initializing
 */
class InitLate
{

    /** @var Master */
    protected $master;

    /**
     * Trigger
     *  @var string 
     */
    protected $trigger = '';

    /**
     * String JSON
     *
     * @var array
     */
    protected $jsonDecoded = [];

    public function __construct(Master $master)
    {
        $this->master = $master;

        /**
         * Load admin scripts
         */
        add_action( 'admin_enqueue_scripts', [$this, 'load_admin_scripts'] );
        /**
         * Add Rest endpoint
         */
        add_action( 'rest_api_init', [$this, 'add_rest_endpoint'] );

        if (!$this->preCheck()) {
            return;
        }
        if ([] !== $this->jsonDecoded && 'translateCf' === $this->trigger) {

            $translator =  (new CfTranslatorFactory())->makeTranslator($this->jsonDecoded);
            $translator->handle();
        } else {
            echo 'not Done';
        }
    }


    /**
     * Check that any required external functionality is present
     *
     * For example, if other plugins are required, perform a check here; The
     * plugins may be active, but may not have initialized fully, so ensure that
     * the required specific functionality is available.  If it is not yet
     * available, move the dependent method to a later action hook.
     *
     * Since CivCrmSdk has added a 'bypass' function, we don't have to stop
     * functionality, instead we set the bypass that tells CiviCrmSdk to provide
     * the mock object instead of actual CiviObjects.
     */
    protected function preCheck(): bool
    {
        $return = false;

        $this->trigger = \filter_input(\INPUT_GET, 'trigger');

        $filename =  \filter_input(\INPUT_GET, 'filename');

        if (!\is_null($this->trigger)  && !\is_null($filename)) {
            $return = true;

            $this->jsonDecoded = $this->master->configure($filename, 'json');
        }

        return $return;
    }

    /**
     * Load admin Scripts
     *
     * @since 0.0.1
     */
    function load_admin_scripts() {
        $cf_forms = false;
        if(class_exists("Caldera_Forms_Forms")){
            $cf_forms = Caldera_Forms_Forms::get_forms(true);
        }
        $screen = get_current_screen();
        if( $screen->id === 'toplevel_page_caldera-forms' ){

            wp_enqueue_script('cf_conversion_tool_cf_admin', CF_CONVERSION_TOOL_URL . 'build/admin.js', ['wp-components', 'wp-api-fetch', 'jquery'], "0.0.1", true );
            wp_localize_script('cf_conversion_tool_cf_admin', 'cf_conversion_tool_vars', [
                "admin_url" =>  get_admin_url(),
                "rest_url"  =>  get_rest_url(),
                "nonce"  =>  wp_create_nonce( 'cf_conversion_tool_nonce' ),
                "rest_nonce"  =>  wp_create_nonce( 'wp_rest' ),
                "nf_security_nonce" =>  wp_create_nonce( 'ninja_forms_batch_nonce' ),
                "cf_forms"  => $cf_forms
            ] );
            wp_set_script_translations( 'cf_conversion_tool_cf_admin', 'cf_conversion_tool', CF_CONVERSION_TOOL_PATH . '/languages' );
            wp_enqueue_style( 'cf_conversion_tool_cf_admin_style', CF_CONVERSION_TOOL_URL . 'build/styles.scss.css', ['wp-components'], "0.0.1", false);

        }

    }

    /**
     * Add Rest API endpoint to trigger form conversion
     *
     * @since 0.0.1
     */
    function add_rest_endpoint() {

        register_rest_route( 'nf-formbuilder', 'convert-cf', [
          'methods' => 'POST',
          'callback' => [$this, 'convert_cf'],
          'permission_callback' => [ $this, 'cf_permissions_check' ]
        ]);

    }

    /**
	 * Permission to translate CF Form to NF
	 *
	 * @since 0.0.1
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	function cf_permissions_check( \WP_REST_Request $request ){
        
        //Check rest Nonce
        if ( ! wp_verify_nonce( $request->get_header( "x_wp_nonce" ), 'wp_rest' ) ) {
            return false; 
        }

        //Set default to false
        $allowed = false;

        //Check Capability of logged in user
        $allowed = current_user_can("manage_options");
        
		/**
		 * Filter permissions for Translating CF forms via REST API
		 *
		 * @since 0.0.1
		 *
		 * @param bool $allowed Is request authorized?
		 * @param WP_REST_Request $request The current request
		 */
		return apply_filters( 'cf_conversion_tool_allow_cf_form_translation', $allowed, $request );

	}

    /**
     * Trigger CF to NF Form conversion
     *
     * @since 0.0.1
     * 
     * @param WP_REST_Request $request
     * 
     * @return object Rest response
     */
    function convert_cf( \WP_REST_Request $request ) {

        try{
            // Get params and check if they're not empty
            $request_data = $request->get_json_params();
            if(!empty($request_data) && !empty($request_data["form"]) ){
                
                //Set CF Form data
                $this->jsonDecoded = $request_data["form"];

                //Set data into Translator and translate it
                $translator = (new CfTranslatorFactory())->makeTranslator($this->jsonDecoded);

                //Return conversion
                $form = $translator->getNffConstruct();

                //Build response
                $response = new WP_REST_Response( [
                    'form'  =>  $form
                ]);

            }
            else {

                $response = new WP_Error('missing data to perform request',  __("Missing data in request to server", "cf_conversion_tool") );
            
            }
        } catch (Exception $e){
            $response = new WP_Error( 'exception thrown', $e );
        }

        // Return the reponse object
        return rest_ensure_response( $response );
      }
}
