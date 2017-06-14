<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1
 * @package    Disciple_Tools
 * @subpackage Disciple_Tools/includes/admin
 * @author     
 */
class Location_Lookup_Activator {


	/**
	 * Activities to run during installation.
	 *
	 * Long Description.
	 *
	 * @since    0.1
	 */
	public static function activate() {

	    // Adds years available for census tracts.
	    add_option('_location_lookup_census_years', array('2016'));

	}

}
