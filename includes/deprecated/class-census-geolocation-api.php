<?php

/**
 * Location_Lookup_Tabs
 *
 * @class Location_Lookup_Tabs
 * @version	0.1
 * @since 0.1
 * @package	Location_Lookup_Tabs
 * @author Chasm.Solutions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Location_Lookup_Census_Geolocation {

    /**
     * Constructor function.
     * @access  public
     * @since   0.1
     */
    public function __construct () {} // End __construct()

    /**
     * Gets the census data query object using longitude and latitude
     * @param $lng
     * @param $lat
     * @param $type
     * @return array|mixed|object
     */
    public static function query_census_api ($lng, $lat, $type = 'full_object') {

        $tract_address = 'https://geocoding.geo.census.gov/geocoder/geographies/coordinates?x='.$lng.'&y='.$lat.'&benchmark=4&vintage=4&format=json';
        $census_result = json_decode(file_get_contents($tract_address));


        if($census_result == '' || !isset($census_result->result->geographies->{'Census Tracts'}[0]->STATE)) { /* Census API gives false errors. This is attempting to try a couple times before returning error. */

            $census_result = json_decode(file_get_contents($tract_address));

            if($census_result == '' || !isset($census_result->result->geographies->{'Census Tracts'}[0]->STATE)) {

                sleep ( 1 ); // wait 1 second, then try again

                $census_result = json_decode(file_get_contents($tract_address));

                if($census_result == '' || !isset($census_result->result->geographies->{'Census Tracts'}[0]->STATE)) {
                    return 'ZERO_RESULTS';
                }
            }
        }

        if ($type == 'core') {

            $state_code = $census_result->result->geographies->{'Census Tracts'}[0]->STATE;
            $tract_county = $census_result->result->geographies->{'Census Tracts'}[0]->COUNTY;
            $tract_geoid = $census_result->result->geographies->{'Census Tracts'}[0]->GEOID;
            $tract_lng = $census_result->result->geographies->{'Census Tracts'}[0]->CENTLON;
            $tract_lat = $census_result->result->geographies->{'Census Tracts'}[0]->CENTLAT;
            $tract_size = $census_result->result->geographies->{'Census Tracts'}[0]->AREALAND;

            $zoom = get_zoom_size_LL ($tract_size);

            return array(
                'state' => $state_code,
                'county' => $tract_county,
                'geoid' => $tract_geoid,
                'lat' => $tract_lat,
                'lng' => $tract_lng,
                'size' => $tract_size,
                'zoom' => $zoom,
            );

        } elseif ($type == 'geoid') {
            if($census_result->result->geographies->{'Census Tracts'}[0]) {
                return $tract_geoid = $census_result->result->geographies->{'Census Tracts'}[0]->GEOID;
            } else {
                return false;
            }
        } else {
            return $census_result; // full_object returned
        }
    }



}