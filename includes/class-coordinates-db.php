<?php

/**
 * Gets Coordinates from Database
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Location_Lookup_Coordinates_DB {

    /**
     * @param $geoid        int tract geoid
     * @return string
     */
    public static function get_db_coordinates ($geoid) {
        global $wpdb;

        if(!post_type_exists('locations'))
            return 'post type locations is not registered';

        // SELECT post_content FROM wp_posts WHERE post_title = '39049002770'
        $result = $wpdb->get_var($wpdb->prepare(
            'SELECT meta_value 
              FROM %1$s 
              WHERE meta_key = \'%2$s\'',
            $wpdb->postmeta,
            'polygon_'.$geoid
        ));

        $result = substr(trim($result), 1, -1);
        $placemarks = explode('},{', $result); // create array from coordinates string

        $coordinates = array();
        foreach ($placemarks as $va) {
            if (!empty($va)) {
                $coord = explode(', ', $va);
                $lng = explode(' ', $coord[1]);
                $lat = explode(' ', $coord[0]);
                $coordinates[] = array('lat' => (float)$lat[1], 'lng' => (float)$lng[1]);
            }
        }
        return $coordinates;
    }

    /**
     * @param $state       int Two digit state code
     * @return string
     */
    public static function get_db_state ($state) {
        global $wpdb;

        if(!post_type_exists('locations'))
            return 'post type locations is not registered';

        // SELECT post_content FROM wp_posts WHERE post_title = '39049002770'
        $coordinates_array = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE post_title LIKE '$state%'", ARRAY_A );

        $count = count($coordinates_array);
        $coordinates_string = '';
        $i = 0;
        foreach($coordinates_array as $coordinate) {
            $coordinates_string .= '['. $coordinate['post_content'] . ']';
            if ($i < $count - 1) { $coordinates_string .= ','; }
            $i++;
        }

        return $coordinates_string;
    }



}