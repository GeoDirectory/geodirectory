<?php
/**
 * Returns current city latitude.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @todo fix location variable
 * @return string
 */
function geodir_get_current_city_lat()
{
    $location = geodir_get_default_location();
    $lat = isset($location_result->city_latitude) ? $location_result->city_latitude : '39.952484';

    return $lat;
}

/**
 * Returns current city longitude.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @todo fix location variable
 * @return string
 */
function geodir_get_current_city_lng()
{
    $location = geodir_get_default_location();
    $lng = isset($location_result->city_longitude) ? $location_result->city_longitude : '-75.163786';
    return $lng;
}


/**
 * Returns the default location.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return object
 */
function geodir_get_default_location()
{
    /**
     * Filter the default location.
     *
     * @since 1.0.0
     * @package GeoDirectory
     *
     * @param string $location_result The default location object.
     */
    return $location_result = apply_filters('geodir_get_default_location', get_option('geodir_default_location'));
}

/**
 * Checks whether the default location is set or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return bool
 */
function geodir_is_default_location_set()
{
    $default_location = geodir_get_default_location();
    if (!empty($default_location))
        return true;
    else
        return false;
}

/**
 * Returns location slug using location string.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $location_string The location string.
 * @return string The location slug.
 */
function create_location_slug($location_string)
{

    /**
     * Filter the location slug.
     *
     * @since 1.0.0
     * @package GeoDirectory
     *
     * @param string $location_string Sanitized location string.
     */
    return urldecode(apply_filters('geodir_location_slug_check', sanitize_title($location_string)));

}

/**
 * Returns location object using location id.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $id The location ID.
 * @return object The location object.
 */
function geodir_get_location($id = '')
{
    /**
     * Filter the location information.
     *
     * @since 1.0.0
     * @package GeoDirectory
     *
     * @param string $id The location ID.
     */
    return $location_result = apply_filters('geodir_get_location_by_id', get_option('geodir_default_location'), $id);
}

/**
 * Returns country selection dropdown box.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $post_country The dropdown default selected country.
 * @param string $prefix Not yet implemented.
 */
function geodir_get_country_dl($post_country = '', $prefix = '')
{
    global $wpdb;

    $rows = $wpdb->get_results("SELECT Country,ISO2 FROM " . GEODIR_COUNTRIES_TABLE . " ORDER BY Country ASC");
    
    $ISO2 = array();
    $countries = array();
    
    foreach ($rows as $row) {
        $ISO2[$row->Country] = $row->ISO2;
        $countries[$row->Country] = __($row->Country, 'geodirectory');
    }
    
    asort($countries);
    
    $out_put = '<option ' . selected('', $post_country, false) . ' value="">' . __('Select Country', 'geodirectory') . '</option>';
    foreach ($countries as $country => $name) {
        $ccode = $ISO2[$country];

        $out_put .= '<option ' . selected($post_country, $country, false) . ' value="' . esc_attr($country) . '" data-country_code="' . $ccode . '">' . $name . '</option>';
    }

    echo $out_put;
}


/**
 * Handles location form submitted data.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_location_form_submit()
{

    global $wpdb, $plugin_prefix;
    if (isset($_REQUEST['add_location'])) {

        $location_info = array(
            'city' => $_REQUEST['city'],
            'region' => $_REQUEST['region'],
            'country' => $_REQUEST['country'],
            'geo_lat' => $_REQUEST['latitude'],
            'geo_lng' => $_REQUEST['longitude'],
            'is_default' => $_REQUEST['is_default'],
            'update_city' => $_REQUEST['update_city']
        );

        $old_location = geodir_get_default_location();

        $locationid = geodir_add_new_location($location_info);

        $default_location = geodir_get_location($locationid);

        //UPDATE AND DELETE LISTING
        $posttype = geodir_get_posttypes();
        if (isset($_REQUEST['listing_action']) && $_REQUEST['listing_action'] == 'delete') {

            foreach ($posttype as $posttypeobj) {
                $post_locations = '[' . $default_location->city_slug . '],[' . $default_location->region_slug . '],[' . $default_location->country_slug . ']'; // set all overall post location

                $sql = $wpdb->prepare(
                    "UPDATE " . $plugin_prefix . $posttypeobj . "_detail SET post_city=%s, post_region=%s, post_country=%s, post_locations=%s WHERE post_location_id=%d AND ( post_city!=%s OR post_region!=%s OR post_country!=%s OR post_locations!=%s OR post_locations IS NULL)",
                    array($_REQUEST['city'], $_REQUEST['region'], $_REQUEST['country'], $post_locations, $locationid, $_REQUEST['city'], $_REQUEST['region'], $_REQUEST['country'], $post_locations)
                );
                $wpdb->query($sql);
            }
        }
    }
}

/**
 * Adds new location using location info.
 *
 * @since 1.0.0
 * @since 1.6.16 Fix country translation.
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $location_info {
 *     Array of location info arguments.
 *
 *     @type string         $city               The city string.
 *     @type string         $region             The region string.
 *     @type string         $country            The country string.
 *     @type string         $geo_lat            The latitude string.
 *     @type string         $geo_lng            The longitude string.
 *     @type string|bool    $is_default         Is this the default location?.
 * }
 * @return string|bool Location ID on success. False when Fail.
 */
function geodir_add_new_location($location_info = array())
{
    global $wpdb;

    if (!empty($location_info)) {
        $location_city = ($location_info['city'] != '') ? $location_info['city'] : 'all';
        $location_region = ($location_info['region'] != '') ? $location_info['region'] : 'all';
        $location_country = ($location_info['country'] != '') ? geodir_get_normal_country($location_info['country']) : 'all';
        $location_lat = ($location_info['geo_lat'] != '') ? $location_info['geo_lat'] : '';
        $location_lng = ($location_info['geo_lng'] != '') ? $location_info['geo_lng'] : '';
        $is_default = isset($location_info['is_default']) ? $location_info['is_default'] : '';
        $country_slug = create_location_slug(__($location_country, 'geodirectory'));
        $region_slug = create_location_slug($location_region);
        $city_slug = create_location_slug($location_city);
        
        /**
         * Filter add new location data.
         *
         * @since 1.0.0
         */
        $geodir_location = (object)apply_filters('geodir_add_new_location', array('location_id' => 0,
            'country' => $location_country,
            'region' => $location_region,
            'city' => $location_city,
            'country_slug' => $country_slug,
            'region_slug' => $region_slug,
            'city_slug' => $city_slug,
            'city_latitude' => $location_lat,
            'city_longitude' => $location_lng,
            'is_default' => $is_default
        ));

        /* // Not allowed to create country in DB : 2016-12-09
        if ($geodir_location->country) {

            $get_country = $wpdb->get_var($wpdb->prepare("SELECT Country FROM " . GEODIR_COUNTRIES_TABLE . " WHERE Country=%s", array($geodir_location->country)));

            if (empty($get_country)) {

                $wpdb->query($wpdb->prepare("INSERT INTO " . GEODIR_COUNTRIES_TABLE . " (Country, Title) VALUES (%s,%s)", array($geodir_location->country, $geodir_location->country)));

            }

        }
        */

        if ($geodir_location->is_default)
            update_option('geodir_default_location', $geodir_location);

        return $geodir_location->location_id;

    } else {
        return false;
    }
}

/**
 * Returns random float number.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $min The minimum number. Default: 0.
 * @param int $max The maximum number. Default: 1.
 * @return float
 */
function geodir_random_float($min = 0, $max = 1)
{
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}

/**
 * Returns address using latitude and longitude.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $lat Latitude string.
 * @param string $lng Longitude string.
 * @return string|bool Returns address on success.
 */
function geodir_get_address_by_lat_lan($lat, $lng)
{
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim($lat) . ',' . trim($lng) . "&key=" . geodir_get_map_api_key();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response);
    $status = $data->status;
    if ($status == "OK") {
        return $data->results[0]->address_components;
    } else
        return false;
}

/**
 * Returns current location terms.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wp WordPress object.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @param string $location_array_from Place to look for location array. Default: 'session'.
 * @param string $gd_post_type The post type.
 * @return array The location term array.
 */
function geodir_get_current_location_terms($location_array_from = 'session', $gd_post_type = '')
{
    global $wp, $gd_session;
    $location_array = array();
    if ($location_array_from == 'session') {
        if ($gd_session->get('gd_country') == 'me' || $gd_session->get('gd_region') == 'me' || $gd_session->get('gd_city') == 'me') {
            return $location_array;
        }

        $country = isset($_REQUEST['gd_country']) ? $_REQUEST['gd_country'] : $gd_session->get('gd_country');
        if ($country != '' && $country)
            $location_array['gd_country'] = urldecode($country);

        $region = isset($_REQUEST['gd_region']) ? $_REQUEST['gd_region'] : $gd_session->get('gd_region');
        if ($region != '' && $region)
            $location_array['gd_region'] = urldecode($region);

        $city = isset($_REQUEST['gd_city']) ? $_REQUEST['gd_city'] : $gd_session->get('gd_city');
        if ($city != '' && $city)
            $location_array['gd_city'] = urldecode($city);
    } else {
        if ((isset($wp->query_vars['gd_country']) && $wp->query_vars['gd_country'] == 'me') || (isset($wp->query_vars['gd_region']) && $wp->query_vars['gd_region'] == 'me') || (isset($wp->query_vars['gd_city']) && $wp->query_vars['gd_city'] == 'me')) {
            return $location_array;
        }

        $country = (isset($wp->query_vars['gd_country']) && $wp->query_vars['gd_country'] != '') ? $wp->query_vars['gd_country'] : '';

        $region = (isset($wp->query_vars['gd_region']) && $wp->query_vars['gd_region'] != '') ? $wp->query_vars['gd_region'] : '';

        $city = (isset($wp->query_vars['gd_city']) && $wp->query_vars['gd_city'] != '') ? $wp->query_vars['gd_city'] : '';

        if ($country != '')
            $location_array['gd_country'] = urldecode($country);

        if ($region != '')
            $location_array['gd_region'] = urldecode($region);

        if ($city != '')
            $location_array['gd_city'] = urldecode($city);
			
		// Fix category link in ajax popular category widget on change post type
		if (empty($location_array) && defined('DOING_AJAX') && DOING_AJAX) {
			$location_array = geodir_get_current_location_terms('session');
		}
    }


	/**
	 * Filter the location terms.
	 *
	 * @since 1.4.6
     * @package GeoDirectory
	 *
     * @param array $location_array {
     *    Attributes of the location_array.
     *
     *    @type string $gd_country The country slug.
     *    @type string $gd_region The region slug.
     *    @type string $gd_city The city slug.
     *
     * }
	 * @param string $location_array_from Source type of location terms. Default session.
	 * @param string $gd_post_type WP post type.
	 */
	$location_array = apply_filters( 'geodir_current_location_terms', $location_array, $location_array_from, $gd_post_type );

    return $location_array;

}

/**
 * Returns location link based on location type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $which_location Location link type. Default: 'current'.
 * @return bool|string
 */
function geodir_get_location_link($which_location = 'current') {
    $location_link = get_permalink(geodir_location_page_id());

    if ($which_location == 'base') {
        return $location_link;
    } else {
        $location_terms = geodir_get_current_location_terms();

        if (!empty($location_terms)) {
            if (get_option('permalink_structure') != '') {
                $location_terms = implode("/", $location_terms);
                $location_terms = rtrim($location_terms, '/');
                $location_link .= $location_terms;
            } else {
                $location_link = geodir_getlink($location_link, $location_terms);
            }
        }
    }
    return $location_link;
}

/**
 * Returns openstreetmap address using latitude and longitude.
 *
 * @since 1.6.5
 * @package GeoDirectory
 * @param string $lat Latitude string.
 * @param string $lng Longitude string.
 * @return array|bool Returns address on success.
 */
function geodir_get_osm_address_by_lat_lan($lat, $lng) {
    $url = is_ssl() ? 'https:' : 'http:';
    $url .= '//nominatim.openstreetmap.org/reverse?format=json&lat=' . trim($lat) . '&lon=' . trim($lng) . '&zoom=16&addressdetails=1&email=' . get_option('admin_email');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response);
    
    if (!empty($data) && !empty($data->address)) {
        $address_fields = array('public_building', 'house', 'house_number', 'bakery', 'footway', 'street', 'road', 'village', 'attraction', 'pedestrian', 'neighbourhood', 'suburb');
        $formatted_address = (array)$data->address;
        
        foreach ( $data->address as $key => $value ) {
            if (!in_array($key, $address_fields)) {
                unset($formatted_address[$key]);
            }
        }
        $data->formatted_address = !empty($formatted_address) ? implode(', ', $formatted_address) : '';
        
        return $data;
    } else {
        return false;
    }
}

/**
 * Get normal untranslated country name.
 *
 * @since 1.6.16
 * @since 1.6.22 $default parameter added.
 * @package GeoDirectory
 * @param string $country The country name.
 * @param string $default If '1', returns default value on country not found.
 * @return string Returns the country.
 */
function geodir_get_normal_country($country, $default = '1') {
    global $wpdb;
    if ($result = geodir_get_country_by_name($country)) {
        return $result;
    }
    
    if (defined('POST_LOCATION_TABLE')) {
        $rows = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT country FROM " . POST_LOCATION_TABLE . " WHERE country NOT LIKE %s ORDER BY location_id ASC", $country));
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $translated = __($row->country, 'geodirectory');
                if (geodir_strtolower($translated) == geodir_strtolower($country) && $result = geodir_get_country_by_name($row->country)) {
                    return $result;
                }
            }
        }
        
        $rows = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT country FROM " . POST_LOCATION_TABLE . " WHERE country_slug LIKE %s AND country NOT LIKE %s ORDER BY location_id", $country, $country ) );
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $translated = __($row->country, 'geodirectory');
                if (geodir_strtolower($translated) == geodir_strtolower($country) && $result = geodir_get_country_by_name($row->country)) {
                    return $result;
                }
            }
        }
    }
    
    if ( $default === '0' ) {
        return NULL;
    }
    
    $default_location = geodir_get_default_location();
    if (!empty($default_location->country) && $result = geodir_get_country_by_name($default_location->country)) {
        return $result;
    }
    
    if (!empty($default_location->country_slug) && $result = geodir_get_country_by_name($default_location->country_slug)) {
        return $result;
    }
    
    if (!empty($default_location->country_ISO2) && $result = geodir_get_country_by_name($default_location->country_ISO2, true)) {
        return $result;
    }
    
    return $country;
}

/**
 * Get ISO2 of the country.
 *
 * @since 1.6.16
 * @package GeoDirectory
 * @param string $country The country name.
 * @return string Country ISO2 code.
 */
function geodir_get_country_iso2($country) {
    global $wpdb;
    
    if ($result = $wpdb->get_var($wpdb->prepare("SELECT ISO2 FROM " . GEODIR_COUNTRIES_TABLE . " WHERE Country LIKE %s", $country))) {
        return $result;
    }
    if ($result = $wpdb->get_var($wpdb->prepare("SELECT ISO2 FROM " . GEODIR_COUNTRIES_TABLE . " WHERE Country LIKE %s", geodir_get_normal_country($country)))) {
        return $result;
    }
    
    return $country;
}

/**
 * Get the country name from DB.
 *
 * @since 1.6.16
 * @package GeoDirectory
 * @param string $country The country name or iso2.
 * @param bool $iso2 If true it searches by country iso2.
 * @return string|null Country ISO2 code.
 */
function geodir_get_country_by_name($country, $iso2 = false) {
    global $wpdb;
    
    if ($result = $wpdb->get_var($wpdb->prepare("SELECT Country FROM " . GEODIR_COUNTRIES_TABLE . " WHERE Country LIKE %s", $country))) {
        return $result;
    }
    if ($iso2 && $result = $wpdb->get_var($wpdb->prepare("SELECT Country FROM " . GEODIR_COUNTRIES_TABLE . " WHERE ISO2 LIKE %s", $country))) {
        return $result;
    }
    
    return NULL;
}


/**
 * Replace the location variables.
 *
 * @since   1.6.16
 * @package GeoDirectory
 *
 * @global object $wp WordPress object.
 *
 * @param string $content       The content with variables.
 * @param array $location_array The array of location variables.
 * @param string $sep           The separator, Optional.
 * @param string $gd_page       The page being filtered. Optional.
 * @return string Filtered content.
 */
function geodir_replace_location_variables($content, $location_array = array(), $sep = NULL, $gd_page = '') {

    if (empty($content)) {
        return $content;
    }


    $location_replace_vars = geodir_location_replace_vars($location_array, $sep, $gd_page);

    if (!empty($location_replace_vars)) {
        foreach ($location_replace_vars as $search => $replace) {
            if (!empty($search) && strpos($content, $search) !== false) {
                $content = str_replace($search, $replace, $content);
            }
        }
    }

    return $content;
}
add_filter('geodir_replace_location_variables', 'geodir_replace_location_variables');


function geodir_location_replace_vars($location_array = array(), $sep = NULL, $gd_page = ''){

    global $wp;
    
    $location_manager = defined('GEODIRLOCATION_VERSION') ? true : false;

    if (empty($location_array)) {
        $location_array = geodir_get_current_location_terms('query_vars');
    }

    $location_terms = array();
    $location_terms['gd_neighbourhood'] = !empty($wp->query_vars['gd_neighbourhood']) ? $wp->query_vars['gd_neighbourhood'] : '';
    $location_terms['gd_city'] = !empty($wp->query_vars['gd_city']) ? $wp->query_vars['gd_city'] : '';
    $location_terms['gd_region'] = !empty($wp->query_vars['gd_region']) ? $wp->query_vars['gd_region'] : '';
    $location_terms['gd_country'] = !empty($wp->query_vars['gd_country']) ? $wp->query_vars['gd_country'] : '';

    $location_names = array();
    foreach ($location_terms as $type => $location) {
        $location_name = $location;

        if (!empty($location_name)) {
            if ($location_manager) {
                $location_type = strpos($type, 'gd_') === 0 ? substr($type, 3) : $type;
                $location_name = get_actual_location_name($location_type, $location, true);
            } else {
                $location_name = preg_replace( '/-(\d+)$/', '', $location_name);
                $location_name = preg_replace( '/[_-]/', ' ', $location_name );
                $location_name = __(geodir_ucwords($location_name), 'geodirectory');
            }
        }

        $location_names[$type] = $location_name;
    }

    $location_single = '';
    foreach ($location_terms as $type => $location) {
        if (!empty($location)) {
            if (!empty($location_names[$type])) {
                $location_single = $location_names[$type];
            } else {
                if ($location_manager) {
                    $location_type = strpos($type, 'gd_') === 0 ? substr($type, 3) : $type;
                    $location_single = get_actual_location_name($location_type, $location, true);
                } else {
                    $location_name = preg_replace( '/-(\d+)$/', '', $location);
                    $location_name = preg_replace( '/[_-]/', ' ', $location_name );
                    $location_single = __(geodir_ucwords($location_name), 'geodirectory');
                }
            }
            break;
        }
    }

    $full_location = array();
    if (!empty($location_array)) {
        $location_array = array_reverse($location_array);

        foreach ($location_array as $type => $location) {
            if (!empty($location_names[$type])) {
                $location_name = $location_names[$type];
            } else {
                if ($location_manager) {
                    $location_type = strpos($type, 'gd_') === 0 ? substr($type, 3) : $type;
                    $location_name = get_actual_location_name($location_type, $location, true);
                } else {
                    $location_name = preg_replace( '/-(\d+)$/', '', $location);
                    $location_name = preg_replace( '/[_-]/', ' ', $location_name );
                    $location_name = __(geodir_ucwords($location_name), 'geodirectory');
                }
            }

            $full_location[] = $location_name;
        }

        if (!empty($full_location)) {
            $full_location = array_unique($full_location);
        }
    }
    $full_location = !empty($full_location) ? implode(', ', $full_location): '';
    
    if ( empty( $full_location ) ) {
        /**
         * Filter the text in meta description in full location is empty.
         *
         * @since 1.6.22
         * 
         * @param string $full_location Default: Empty.
         * @param array  $location_array The array of location variables.
         * @param string $gd_page       The page being filtered.
         * @param string $sep           The separator.
         */
         $full_location = apply_filters( 'geodir_meta_description_location_empty_text', '', $location_array, $gd_page, $sep );
    }
    
    if ( empty( $location_single ) ) {
        /**
         * Filter the text in meta description in single location is empty.
         *
         * @since 1.6.22
         * 
         * @param string $location_single Default: Empty.
         * @param array $location_array The array of location variables.
         * @param string $gd_page       The page being filtered.
         * @param string $sep           The separator.
         */
         $location_single = apply_filters( 'geodir_meta_description_single_location_empty_text', '', $location_array, $gd_page, $sep );
    }

    $location_replace_vars = array();
    $location_replace_vars['%%location_sep%%'] = $sep !== NULL ? $sep : '|';
    $location_replace_vars['%%location%%'] = $full_location;
    $location_replace_vars['%%in_location%%'] = $full_location != '' ? sprintf( _x('in %s','in location', 'geodirectory'), $full_location ) : '';
    $location_replace_vars['%%location_single%%'] = $location_single;
    $location_replace_vars['%%in_location_single%%'] = $location_single != '' ? sprintf( _x('in %s','in location', 'geodirectory'), $location_single ) : '';

    foreach ($location_names as $type => $name) {
        $location_type = strpos($type, 'gd_') === 0 ? substr($type, 3) : $type;

        $location_replace_vars['%%location_' . $location_type . '%%'] = $name;
        $location_replace_vars['%%in_location_' . $location_type . '%%'] = !empty($name) ? sprintf( _x('in %s','in location', 'geodirectory'), $name ) : '';
    }

    /**
     * Filter the location terms variables to search & replace.
     *
     * @since   1.6.16
     * @package GeoDirectory
     *
     * @param array $location_replace_vars The array of search & replace variables.
     * @param array $location_array The array of location variables.
     * @param string $gd_page       The page being filtered.
     * @param string $sep           The separator.
     */
    return apply_filters( 'geodir_filter_location_replace_variables', $location_replace_vars, $location_array, $gd_page, $sep );
}