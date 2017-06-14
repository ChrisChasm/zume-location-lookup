<?php

/**
 * Placemark info class
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Location_Lookup_Placemark_Info {

    /**
     * Get coordinates from KML file
     * @param $state
     * @param $geoid
     * @return string
     */
    public static function get_placemark_zoom ($geoid, $state ) {

        $file = get_file_path_by_key_LL ($state);

        $kml_object = simplexml_load_file($file);

        $ALAND = '';

        foreach ($kml_object->Document->Folder->Placemark as $mark) {
            $element_geoid = $mark->ExtendedData->SchemaData->SimpleData[4];

            if ($element_geoid == $geoid) { // FILTER RETURN TO TRACT NUMBER
                $ALAND = $mark->ExtendedData->SchemaData->SimpleData[7];
            }
        }

        $zoom = get_zoom_size_LL($ALAND);

        return $zoom;
    }



}