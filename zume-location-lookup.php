<?php
/*
   Plugin Name: Zume Location Lookup
   Plugin URI: http://wordpress.org/extend/plugins/location-lookup/
   Version: 0.1
   Author: Chris Chasm
   Description: Looks up a geo location from an address using U.S. census api
   Text Domain: location-lookup
   License: GPLv3
  */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Location_Lookup to prevent the need to use globals.
 *
 * @since  0.1
 * @return object Location_Lookup
 */

// Adds the Location_Lookup after plugins load
add_action( 'plugins_loaded', 'Location_Lookup' );

// Creates the instance
function Location_Lookup() {
    return Location_Lookup::instance();
}

class Location_Lookup {

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $token;
    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $version;
    /**
     * The post type.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $location;
    /**
     * The tabs and content pages.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $tabs;

    public $path;

    /**
     * Location_Lookup The single instance of Location_Lookup.
     * @var 	object
     * @access  private
     * @since 	0.1
     */
    private static $_instance = null;

    /**
     * Main Location_Lookup Instance
     *
     * Ensures only one instance of Location_Lookup is loaded or can be loaded.
     *
     * @since 0.1
     * @static
     * @return Location_Lookup instance
     */
    public static function instance () {
        if ( is_null( self::$_instance ) )
            self::$_instance = new self();
        return self::$_instance;
    } // End instance()

    /**
     * Constructor function.
     * @access  public
     * @since   0.1
     */
    public function __construct () {

        $this->token = 'location_lookup';
        $this->version = '0.1';
        $this->path = plugin_dir_path(__FILE__);

        if(is_admin()) {

            // tab menu
            require_once('admin/tabs.php');
            $this->tabs = Location_Lookup_Tabs::instance();
            require_once('admin/tab-state.php');
            require_once('admin/tab-js-tract-lookup.php');


        }

        // Helper
//        require_once('includes/class-map.php');
//        require_once('includes/class-coordinates-kml.php');
//        require_once('includes/class-coordinates-db.php');
        require_once('includes/functions.php');
        require_once('includes/class-placemark-info.php');


        // API
        require_once('includes/locations-rest-controller.php');
        require_once('includes/locations-rest-api.php');
        $this->rest = Location_Lookup_REST_API::instance();


    } // End __construct()

    /**
     * Log the plugin version number.
     * @access  private
     * @since   0.1
     */
    public function _log_version_number () {
        // Log the version number.
        update_option( $this->token . '-version', $this->version );
    } // End _log_version_number()

    /**
     * Cloning is forbidden.
     * @access public
     * @since 0.1
     */
    public function __clone () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __clone()
    /**
     * Unserializing instances of this class is forbidden.
     * @access public
     * @since 0.1
     */
    public function __wakeup () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __wakeup()

}

/**
 * Activation Hook
 * The code that runs during plugin activation.
 * This action is documented in includes/admin/class-activator.php
 */
function activate_location_lookup() {
    require_once plugin_dir_path(__FILE__) . 'admin/class-activator.php';
    Location_Lookup_Activator::activate();
}

/**
 * Deactivation Hook
 * The code that runs during plugin deactivation.
 * This action is documented in includes/admin/class-deactivator.php
 */
function deactivate_location_lookup() {
    require_once plugin_dir_path(__FILE__) . 'admin/class-deactivator.php';
    Location_Lookup_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_location_lookup');
register_deactivation_hook(__FILE__, 'deactivate_location_lookup');
