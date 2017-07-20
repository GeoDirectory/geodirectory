<?php
/**
 * Helper functions, this file contains functions made to make a developer's life easier.
 *
 * @since 1.4.6
 * @package GeoDirectory
 */

/**
 * Get the page ID of the add listing page.
 *
 * @package Geodirectory
 * @since 1.4.6
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_add_listing_page_id(){
    $gd_page_id = get_option('geodir_add_listing_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}

/**
 * Get the page ID of the add listing preview page.
 *
 * @package Geodirectory
 * @since 1.4.6
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_preview_page_id(){
    $gd_page_id = get_option('geodir_preview_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}

/**
 * Get the page ID of the add listing success page.
 *
 * @package Geodirectory
 * @since 1.4.6
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_success_page_id(){
    $gd_page_id = get_option('geodir_success_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}

/**
 * Get the page ID of the add location page.
 *
 * @package Geodirectory
 * @since 1.4.6
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_location_page_id(){
    $gd_page_id = get_option('geodir_location_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}

/**
 * Get the page ID of the GD home page.
 *
 * @package Geodirectory
 * @since 1.5.4
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_home_page_id(){
    $gd_page_id = get_option('geodir_home_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}

/**
 * Get the page ID of the info page.
 *
 * @package Geodirectory
 * @since 1.5.3
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_info_page_id(){
    $gd_page_id = get_option('geodir_info_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}

/**
 * Get the page ID of the login page.
 *
 * @package Geodirectory
 * @since 1.5.3
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_login_page_id(){
    $gd_page_id = get_option('geodir_login_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    return $gd_page_id;
}


/**
 * Get the page ID of the login page.
 *
 * @package Geodirectory
 * @since 1.5.3
 * @return int|null Return the page ID if present or null if not.
 */
function geodir_login_url($args=array()){
    $gd_page_id = get_option('geodir_login_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    if (function_exists('geodir_location_geo_home_link')) {
        remove_filter('home_url', 'geodir_location_geo_home_link', 100000);
    }

    if (geodir_is_wpml()){
        $home_url = icl_get_home_url();
    }else{
        $home_url = home_url();
    }

    if (function_exists('geodir_location_geo_home_link')) {
        add_filter('home_url', 'geodir_location_geo_home_link', 100000, 2);
    }

    if($gd_page_id){
        $post = get_post($gd_page_id);
        $slug = $post->post_name;
        //$login_url = get_permalink($gd_page_id );// get_permalink can only be user after theme-Setup hook, any earlier and it errors
        $login_url = trailingslashit($home_url)."$slug/";
    }else{
        $login_url = trailingslashit($home_url)."?geodir_signup=true";
    }

    if($args){
        $login_url = add_query_arg($args,$login_url );
    }

    /**
     * Filter the GeoDirectory login page url.
     *
     * This filter can be used to change the GeoDirectory page url.
     *
     * @since 1.5.3
     * @package GeoDirectory
     * @param string $login_url The url of the login page.
     * @param array $args The array of query args used.
     * @param int $gd_page_id The page id of the GD login page.
     */
	    return apply_filters('geodir_login_url',$login_url,$args,$gd_page_id);
}

/**
 * Returns info page url
 *
 * @package Geodirectory
 * @since 1.5.4
 * @since 1.5.16 Added WPML lang code to url.
 * @return string Info page url.
 */
function geodir_info_url($args=array()){
    $gd_page_id = get_option('geodir_info_page');

    if (geodir_is_wpml()) {
        $gd_page_id =  geodir_wpml_object_id($gd_page_id, 'page', true);
    }

    if (function_exists('geodir_location_geo_home_link')) {
        remove_filter('home_url', 'geodir_location_geo_home_link', 100000);
    }

    if (geodir_is_wpml()){
        $home_url = icl_get_home_url();
    }else{
        $home_url = home_url();
    }

    if (function_exists('geodir_location_geo_home_link')) {
        add_filter('home_url', 'geodir_location_geo_home_link', 100000, 2);
    }

    if($gd_page_id){
        $post = get_post($gd_page_id);
        $slug = $post->post_name;
        //$login_url = get_permalink($gd_page_id );// get_permalink can only be user after theme-Setup hook, any earlier and it errors
        $info_url = trailingslashit($home_url)."$slug/";
    }else{
        $info_url = trailingslashit($home_url);
    }

    if($args){
        $info_url = add_query_arg($args,$info_url );
    }

    return $info_url;
}

/**
 * Converts string to title case.
 *
 * This function converts string to title case. Ex: hello world -> Hello World.
 * When mbstring php extension available this function supports all unicode characters.
 *
 * @package Geodirectory
 * @since 1.5.4
 * @param string $string String to convert.
 * @param string $charset Character set to use for conversion.
 * @return string Returns converted string.
 */
function geodir_ucwords($string, $charset='UTF-8') {
    if (function_exists('mb_convert_case')) {
        return mb_convert_case($string, MB_CASE_TITLE, $charset);
    } else {
        return ucwords($string);
    }
}

/**
 * Converts string to lower case.
 *
 * This function converts string to lower case. Ex: HelLO WorLd -> hello world.
 * When mbstring php extension available this function supports all unicode characters.
 *
 * @package Geodirectory
 * @since 1.5.4
 * @param string $string String to convert.
 * @param string $charset Character set to use for conversion.
 * @return string Returns converted string.
 */
function geodir_strtolower($string, $charset='UTF-8') {
    if (function_exists('mb_convert_case')) {
        return mb_convert_case($string, MB_CASE_LOWER, $charset);
    } else {
        return strtolower($string);
    }
}

/**
 * Converts string to upper case.
 *
 * This function converts string to upper case. Ex: HelLO WorLd -> HELLO WORLD.
 * When mbstring php extension available this function supports all unicode characters.
 *
 * @package Geodirectory
 * @since 1.5.4
 * @param string $string String to convert.
 * @param string $charset Character set to use for conversion.
 * @return string Returns converted string.
 */
function geodir_strtoupper($string, $charset='UTF-8') {
    if (function_exists('mb_convert_case')) {
        return mb_convert_case($string, MB_CASE_UPPER, $charset);
    } else {
        return strtoupper($string);
    }
}

/**
 * Returns parsed url and title.
 *
 * This function converts string to url and title if there is "|" separator used in url.
 * Ex: "http://wpgeodirectory.com|GeoDirectory" will return array( url => http://wpgeodirectory.com, label => GeoDirectory ).
 *
 * @package Geodirectory
 * @since 1.5.7
 * @param string $url The website url.
 * @param bool $formatted True if returns formatted url. False if not. Default true.
 * @return array Parsed url and title.
 */
function geodir_parse_custom_field_url($url, $formatted = true) {
	if ($url == '' || !is_string($url)) {
		return NULL;
	}
	$original_url = $url;
	
	$url = stripcslashes($url);
	$parts = explode('|', $url, 2);
	
	$url = trim($parts[0]);
	if ($formatted && $url != '') {
		$url = str_replace( ' ', '%20', $url );
		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $url);
		
		if (0 !== stripos($url, 'mailto:')) {
			$strip = array('%0d', '%0a', '%0D', '%0A');
			$url = _deep_replace($strip, $url);
		}
		
		$url = str_replace(';//', '://', $url);
		
		if (strpos($url, ':') === false && ! in_array($url[0], array('/', '#', '?')) && !preg_match('/^[a-z0-9-]+?\.php/i', $url)) {
			$url = 'http://' . $url;
		}
		
		$url = wp_kses_normalize_entities($url);
		$url = str_replace('&amp;', '&#038;', $url);
		$url = str_replace("'", '&#039;', $url);
	}
	
	$return = array();
	$return['url'] = $url;
	if (!empty($parts[1]) && trim($parts[1]) != '') {
		$return['label'] = trim($parts[1]);
	}

	return $return;
}

/**
 * Set parent categories to fix categories tree structure.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @param array $request_info Listing request info.
 * @return array Modified listing request info.
 */
function geodir_attach_parent_categories($request_info) {
	if (!empty($request_info['post_category']) && is_array($request_info['post_category'])) {
		foreach ($request_info['post_category'] as $taxomony => $term_ids) {			
			$attach_term_ids = array();
			
			if (!empty($term_ids) && is_array($term_ids) && taxonomy_exists($taxomony) && strpos($taxomony, 'category') !== false) {
				$attach_term_ids = geodir_add_parent_terms($term_ids, $taxomony);
				
				if (!empty($attach_term_ids)) {
					if (!isset($request_info['post_default_category'])) {
						$request_info['post_default_category'] = $attach_term_ids[0];
					}
					$request_info['post_category'][$taxomony] = $attach_term_ids;
				}
			}
		}
	}
	
	return $request_info;
}

/**
 * Add the parent terms in current terms.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @param int|array $term_ids Term id or Terms array.
 * @param string $taxomony Category taxonomy of post type.
 * @return array Modified term ids.
 */
function geodir_add_parent_terms($term_ids, $taxomony) {	
	if (is_int($term_ids)) {
		$term_ids = array($term_ids);
	}
	
	$parent_terms = array();
	
	foreach ($term_ids as $term_id) {
		$parent_terms[] = $term_id;
		$term_parents = geodir_get_category_parents($term_id, $taxomony, $parent_terms);
		
		if (!empty($term_parents)) {
			$parent_terms = array_merge($parent_terms, $term_parents);
		}
	}

	return $parent_terms;
}

/**
 * Get the parent categories of current id.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @param int $id Category id.
 * @param string $taxomony Category taxonomy of post type.
 * @param array $visited Array of category ids already included.
 * @param array $parents Array of category ids.
 * @return array Category ids.
 */
function geodir_get_category_parents($id, $taxomony, $visited = array(), $parents = array()) {
	$parent = get_term($id, $taxomony);
	if (is_wp_error($parent)) {
		return $parents;
	}

	if (isset($parent->parent) && $parent->parent && ($parent->parent != $parent->term_id) && !in_array($parent->parent, $visited)) {
		$visited[] = $parent->parent;
		$parents[] = $parent->parent;
		$parents = geodir_get_category_parents($parent->parent, $taxomony, $visited, $parents);
	}

	return $parents;
}

if (!function_exists('geodir_get_ip')) {
/**
 * Get the visitor's IP address.
 *
 * @since 1.5.7
 * @package GeoDirectory
 *
 * @return string The IP address.
 */
function geodir_get_ip() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	/**
	 * Filter the the visitor's IP address.
	 *
	 * @since 1.5.7
	 * @package GeoDirectory
	 *
	 * @param string $ip The IP address.
	 */
	return apply_filters('geodir_get_ip', $ip);
}
}

/**
 * Register die handler for gd_die()
 *
 * @since 1.5.9
 * @package GeoDirectory
 */
function _gd_die_handler() {
    if ( defined( 'GD_TESTING_MODE' ) ) {
        return '_gd_die_handler';
    } else {
        die();
    }
}

/**
 * Wrapper function for wp_die(). This function adds filters for wp_die() which
 * kills execution of the script using wp_die(). This allows us to then to work
 * with functions using gd_die() in the unit tests.
 *
 * @since 1.5.9
 * @package GeoDirectory
 * @param string $message Optional. Error message.
 * @param string $title   Optional. Error title.
 * @param int $status     Optional. Status code.
 */
function gd_die( $message = '', $title = '', $status = 400 ) {
    add_filter( 'wp_die_ajax_handler', '_gd_die_handler', 10, 3 );
    add_filter( 'wp_die_handler', '_gd_die_handler', 10, 3 );
    wp_die( $message, $title, array( 'response' => $status ));
}

/*
 * Matches each symbol of PHP date format standard with jQuery equivalent codeword
 *
 * @since 1.6.5
 * @param string $php_format The PHP date format.
 * @return string The jQuery format date string.
 */
function geodir_date_format_php_to_jqueryui( $php_format ) {
	$symbols = array(
		// Day
		'd' => 'dd',
		'D' => 'D',
		'j' => 'd',
		'l' => 'DD',
		'N' => '',
		'S' => '',
		'w' => '',
		'z' => 'o',
		// Week
		'W' => '',
		// Month
		'F' => 'MM',
		'm' => 'mm',
		'M' => 'M',
		'n' => 'm',
		't' => '',
		// Year
		'L' => '',
		'o' => '',
		'Y' => 'yy',
		'y' => 'y',
		// Time
		'a' => 'tt',
		'A' => 'TT',
		'B' => '',
		'g' => 'h',
		'G' => 'H',
		'h' => 'hh',
		'H' => 'HH',
		'i' => 'mm',
		's' => '',
		'u' => ''
	);

	$jqueryui_format = "";
	$escaping = false;

	for ( $i = 0; $i < strlen( $php_format ); $i++ ) {
		$char = $php_format[$i];

		// PHP date format escaping character
		if ( $char === '\\' ) {
			$i++;

			if ( $escaping ) {
				$jqueryui_format .= $php_format[$i];
			} else {
				$jqueryui_format .= '\'' . $php_format[$i];
			}

			$escaping = true;
		} else {
			if ( $escaping ) {
				$jqueryui_format .= "'";
				$escaping = false;
			}

			if ( isset( $symbols[$char] ) ) {
				$jqueryui_format .= $symbols[$char];
			} else {
				$jqueryui_format .= $char;
			}
		}
	}

	return $jqueryui_format;
}

/**
 * Maybe untranslate date string for saving to the database.
 *
 * @param string $date The date string.
 *
 * @return string The untranslated date string.
 * @since 1.6.5
 * @since 1.6.22 Genitive month added.
 */
function geodir_maybe_untranslate_date($date) {
	global $wp_locale;
    
	$english_long_months = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
	);

	$non_english_long_months  = array(
		__('January'),
		__('February'),
		__('March'),
		__('April'),
		__('May'),
		__('June'),
		__('July'),
		__('August'),
		__('September'),
		__('October'),
		__('November'),
		__('December'),
	);
	$date = str_replace($non_english_long_months,$english_long_months,$date);
    
	if ( !empty( $wp_locale ) ) {
		$date = str_replace( array_values( $wp_locale->month_genitive ), $english_long_months, $date );
	}

	$english_short_months = array(
		' Jan ',
		' Feb ',
		' Mar ',
		' Apr ',
		' May ',
		' Jun ',
		' Jul ',
		' Aug ',
		' Sep ',
		' Oct ',
		' Nov ',
		' Dec ',
	);

	$non_english_short_months = array(
		' '._x( 'Jan', 'January abbreviation' ).' ',
		' '._x( 'Feb', 'February abbreviation' ).' ',
		' '._x( 'Mar', 'March abbreviation' ).' ',
		' '._x( 'Apr', 'April abbreviation' ).' ',
		' '._x( 'May', 'May abbreviation' ).' ',
		' '._x( 'Jun', 'June abbreviation' ).' ',
		' '._x( 'Jul', 'July abbreviation' ).' ',
		' '._x( 'Aug', 'August abbreviation' ).' ',
		' '._x( 'Sep', 'September abbreviation' ).' ',
		' '._x( 'Oct', 'October abbreviation' ).' ',
		' '._x( 'Nov', 'November abbreviation' ).' ',
		' '._x( 'Dec', 'December abbreviation' ).' ',
	);

	$date = str_replace($non_english_short_months,$english_short_months,$date);


	return $date;
}

/**
 * Convert date to given format.
 *
 * @since 1.6.7
 * @since 1.6.22 $locale parameter added to fix already translated dates.
 *
 * @param string $date_input The date string.
 * @param string $date_to The destination date format.
 * @param string $date_from The source date format.
 * @param bool $locale True to retrieve the date in localized format. Default false.
 * @return string The formatted date.
 */
function geodir_date( $date_input, $date_to, $date_from = '', $locale = false ) {
    if ( empty( $date_input ) || empty( $date_to ) ) {
        return NULL;
    }
    
    $date_input = geodir_maybe_untranslate_date( $date_input );
    
    $timestamp = 0;
    if (!empty( $date_from ) && function_exists( 'date_create_from_format' ) ) {
        $datetime = date_create_from_format( $date_from, $date_input );
        if ( !empty( $datetime ) ) {
            $timestamp = $datetime->getTimestamp();
        }
    }
    
    if ( empty( $timestamp ) ) {
        $date = strpos( $date_input, '/' ) !== false ? str_replace( '/', '-', $date_input ) : $date_input;
        $timestamp = strtotime( $date );
    }
    
    $date = date_i18n( $date_to, $timestamp );
    
    if ( !$locale ) {
        $date = geodir_maybe_untranslate_date( $date );
    }
    
    /**
     * Filter the the date format conversion.
     *
     * @since 1.6.7
     * @package GeoDirectory
     *
     * @param string $date The date string.
     * @param string $date_input The date input.
     * @param string $date_to The destination date format.
     * @param string $date_from The source date format.
     * @param bool $locale True to retrieve the date in localized format.
     */
    return apply_filters( 'geodir_date', $date, $date_input, $date_to, $date_from, $locale );
}

/**
 * Truncates the text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ellipsis if the text is longer than length.
 *
 * @since 1.6.16
 * @package GeoDirectory
 *
 * @param string $text String to truncate.
 * @param int    $length Length of returned string, including ellipsis.
 * @param array  $options {
 *     An array of HTML attributes and options.
 *
 *     @type string $ellipsis Will be used as ending and appended to the trimmed string. Ex: "...".
 *     @type bool   $exact If false, $text will not be cut mid-word.
 *     @type bool   $html If true, HTML tags would be handled correctly.
 *     @type bool   $trimWidth If true, $text will be truncated with the width.
 * }
 * @return string Trimmed string.
 */
function geodir_excerpt($text, $length = 100, $options = array()) {
    if (!(int)$length > 0) {
        return $text;
    }
    $default = array(
        'ellipsis' => '', 'exact' => true, 'html' => true, 'trimWidth' => false,
	);
    if (!empty($options['html']) && function_exists('mb_internal_encoding') && strtolower(mb_internal_encoding()) === 'utf-8') {
        $default['ellipsis'] = "";
    }
    $options += $default;

    $prefix = '';
    $suffix = $options['ellipsis'];

    if ($options['html']) {
        $ellipsisLength = geodir_strlen(strip_tags($options['ellipsis']), $options);

        $truncateLength = 0;
        $totalLength = 0;
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            $contentLength = geodir_strlen($tag[3], $options);

            if ($truncate === '') {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/i', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } elseif (preg_match('/<\/([\w]+)[^>]*>/', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }

                $prefix .= $tag[1];

                if ($totalLength + $contentLength + $ellipsisLength > $length) {
                    $truncate = $tag[3];
                    $truncateLength = $length - $totalLength;
                } else {
                    $prefix .= $tag[3];
                }
            }

            $totalLength += $contentLength;
            if ($totalLength > $length) {
                break;
            }
        }

        if ($totalLength <= $length) {
            return $text;
        }

        $text = $truncate;
        $length = $truncateLength;

        foreach ($openTags as $tag) {
            $suffix .= '</' . $tag . '>';
        }
    } else {
        if (geodir_strlen($text, $options) <= $length) {
            return $text;
        }
        $ellipsisLength = geodir_strlen($options['ellipsis'], $options);
    }

    $result = geodir_substr($text, 0, $length - $ellipsisLength, $options);

    if (!$options['exact']) {
        if (geodir_substr($text, $length - $ellipsisLength, 1, $options) !== ' ') {
            $result = geodir_remove_last_word($result);
        }

        // Do not need to count ellipsis in the cut, if result is empty.
        if (!strlen($result)) {
            $result = geodir_substr($text, 0, $length, $options);
        }
    }

    return $prefix . $result . $suffix;
}

/**
 * Get string length.
 *
 * ### Options:
 *
 * - `html` If true, HTML entities will be handled as decoded characters.
 * - `trimWidth` If true, the width will return.
 *
 * @since 1.6.16
 * @package GeoDirectory
 *
 * @param string $text The string being checked for length
 * @param array  $options {
 *     An array of options.
 *
 *     @type bool $html If true, HTML entities will be handled as decoded characters.
 *     @type bool $trimWidth If true, the width will return.
 * }
 * @return int
 */
function geodir_strlen($text, array $options) {
    if (empty($options['trimWidth'])) {
        $strlen = 'geodir_utf8_strlen';
    } else {
        $strlen = 'geodir_utf8_strwidth';
    }

    if (empty($options['html'])) {
        return $strlen($text);
    }

    $pattern = '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i';
    $replace = preg_replace_callback(
        $pattern,
        function ($match) use ($strlen) {
            $utf8 = html_entity_decode($match[0], ENT_HTML5 | ENT_QUOTES, 'UTF-8');

            return str_repeat(' ', $strlen($utf8, 'UTF-8'));
        },
        $text
    );

    return $strlen($replace);
}

/**
 * Return part of a string.
 *
 * @since 1.6.16
 * @package GeoDirectory
 *
 * @param string $text The input string.
 * @param int $start The position to begin extracting.
 * @param int $length The desired length.
 * @param array  $options {
 *     An array of options.
 *
 *     @type bool $html If true, HTML entities will be handled as decoded characters.
 *     @type bool $trimWidth If true, will be truncated with specified width.
 * }
 * @return string
 */
function geodir_substr($text, $start, $length, array $options) {
    if (empty($options['trimWidth'])) {
        $substr = 'geodir_utf8_substr';
    } else {
        $substr = 'geodir_utf8_strimwidth';
    }

    $maxPosition = geodir_strlen($text, array('trimWidth' => false) + $options);
    if ($start < 0) {
        $start += $maxPosition;
        if ($start < 0) {
            $start = 0;
        }
    }
    if ($start >= $maxPosition) {
        return '';
    }

    if ($length === null) {
        $length = geodir_strlen($text, $options);
    }

    if ($length < 0) {
        $text = geodir_substr($text, $start, null, $options);
        $start = 0;
        $length += geodir_strlen($text, $options);
    }

    if ($length <= 0) {
        return '';
    }

    if (empty($options['html'])) {
        return (string)$substr($text, $start, $length);
    }

    $totalOffset = 0;
    $totalLength = 0;
    $result = '';

    $pattern = '/(&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};)/i';
    $parts = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    foreach ($parts as $part) {
        $offset = 0;

        if ($totalOffset < $start) {
            $len = geodir_strlen($part, array('trimWidth' => false) + $options);
            if ($totalOffset + $len <= $start) {
                $totalOffset += $len;
                continue;
            }

            $offset = $start - $totalOffset;
            $totalOffset = $start;
        }

        $len = geodir_strlen($part, $options);
        if ($offset !== 0 || $totalLength + $len > $length) {
            if (strpos($part, '&') === 0 && preg_match($pattern, $part) && $part !== html_entity_decode($part, ENT_HTML5 | ENT_QUOTES, 'UTF-8') ) {
                // Entities cannot be passed substr.
                continue;
            }

            $part = $substr($part, $offset, $length - $totalLength);
            $len = geodir_strlen($part, $options);
        }

        $result .= $part;
        $totalLength += $len;
        if ($totalLength >= $length) {
            break;
        }
    }

    return $result;
}

/**
 * Removes the last word from the text.
 *
 * @since 1.6.16
 * @package GeoDirectory
 *
 * @param string $text The input text.
 * @return string
 */
function geodir_remove_last_word($text) {
    $spacepos = geodir_utf8_strrpos($text, ' ');

    if ($spacepos !== false) {
        $lastWord = geodir_utf8_strrpos($text, $spacepos);

        // Some languages are written without word separation.
        // We recognize a string as a word if it does not contain any full-width characters.
        if (geodir_utf8_strwidth($lastWord) === geodir_utf8_strlen($lastWord)) {
            $text = geodir_utf8_substr($text, 0, $spacepos);
        }

        return $text;
    }

    return '';
}

function geodir_tool_restore_cpt_from_taxonomies(){

	$cpts = get_option('geodir_post_types');

	if(!empty($cpts)){return;}

	$taxonomies = get_option('geodir_taxonomies');

	if(empty($taxonomies)){return;}

	$cpts = array();

	foreach($taxonomies as $key => $val){

		if(strpos($val['listing_slug'], '/') === false) {
			$cpts[$val['object_type']] = array('cpt'=>$val['object_type'],'slug'=>$val['listing_slug']);
		}

	}

	if(empty($cpts)){return;}


	$cpts_restore = $cpts;

	foreach($cpts as $cpt){


		$is_custom = $cpt['cpt']=='gd_place' ? 0 : 1;

		$cpts_restore[$cpt['cpt']] = array (
				'labels' =>
					array (
						'name' => $cpt['slug'],
						'singular_name' => $cpt['slug'],
						'add_new' => 'Add New',
						'add_new_item' => 'Add New '.$cpt['slug'],
						'edit_item' => 'Edit '.$cpt['slug'],
						'new_item' => 'New '.$cpt['slug'],
						'view_item' => 'View '.$cpt['slug'],
						'search_items' => 'Search '.$cpt['slug'],
						'not_found' => 'No '.$cpt['slug'].' Found',
						'not_found_in_trash' => 'No '.$cpt['slug'].' Found In Trash',
						'label_post_profile' => '',
						'label_post_info' => '',
						'label_post_images' => '',
						'label_post_map' => '',
						'label_reviews' => '',
						'label_related_listing' => '',
					),
				'can_export' => true,
				'capability_type' => 'post',
				'description' => '',
				'has_archive' => $cpt['slug'],
				'hierarchical' => false,
				'map_meta_cap' => true,
				'menu_icon' => '',
				'public' => true,
				'query_var' => true,
				'rewrite' =>
					array (
						'slug' => $cpt['slug'],
						'with_front' => false,
						'hierarchical' => true,
						'feeds' => true,
					),
				'supports' =>
					array (
						0 => 'title',
						1 => 'editor',
						2 => 'author',
						3 => 'thumbnail',
						4 => 'excerpt',
						5 => 'custom-fields',
						6 => 'comments',
					),
				'taxonomies' =>
					array (
						0 => $cpt['cpt'].'category',
						1 => $cpt['cpt'].'_tags',
					),
				'is_custom' => $is_custom,
				'listing_order' => '1',
				'seo' =>
					array (
						'meta_keyword' => '',
						'meta_description' => '',
					),
				'show_in_nav_menus' => 1,
				'link_business' => 0,
				'linkable_to' => '',
				'linkable_from' => '',
			);
	}


	update_option('geodir_post_types',$cpts_restore);

}

/**
 * Get truncated string with specified width.
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The string being decoded.
 * @param int $start The start position offset. Number of characters from the beginning of string.
 *                      For negative value, number of characters from the end of the string.
 * @param int $width The width of the desired trim. Negative widths count from the end of the string.
 * @param string $trimmaker A string that is added to the end of string when string is truncated. Ex: "...".
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return string
 */
function geodir_utf8_strimwidth( $str, $start, $width, $trimmaker = '', $encoding = 'UTF-8' ) {
    if ( function_exists( 'mb_strimwidth' ) ) {
        return mb_strimwidth( $str, $start, $width, $trimmaker, $encoding );
    }
    
    return geodir_utf8_substr( $str, $start, $width, $encoding ) . $trimmaker;
}

/**
 * Get the string length.
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The string being checked for length. 
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return int Returns the number of characters in string.
 */
function geodir_utf8_strlen( $str, $encoding = 'UTF-8' ) {
    if ( function_exists( 'mb_strlen' ) ) {
        return mb_strlen( $str, $encoding );
    }
        
    return strlen( $str );
}

/**
 * Find position of first occurrence of string in a string
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The string being checked.
 * @param string $find The string to find in input string.
 * @param int $offset The search offset. Default "0". A negative offset counts from the end of the string.
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return int Returns the position of the first occurrence of search in the string.
 */
function geodir_utf8_strpos( $str, $find, $offset = 0, $encoding = 'UTF-8' ) {
    if ( function_exists( 'mb_strpos' ) ) {
        return mb_strpos( $str, $find, $offset, $encoding );
    }
        
    return strpos( $str, $find, $offset );
}

/**
 * Find position of last occurrence of a string in a string.
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The string being checked, for the last occurrence of search.
 * @param string $find The string to find in input string.
 * @param int $offset Specifies begin searching an arbitrary number of characters into the string.
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return int Returns the position of the last occurrence of search.
 */
function geodir_utf8_strrpos( $str, $find, $offset = 0, $encoding = 'UTF-8' ) {
    if ( function_exists( 'mb_strrpos' ) ) {
        return mb_strrpos( $str, $find, $offset, $encoding );
    }
        
    return strrpos( $str, $find, $offset );
}

/**
 * Get the part of string.
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The string to extract the substring from.
 * @param int $start If start is non-negative, the returned string will start at the entered position in string, counting from zero.
 *                      If start is negative, the returned string will start at the entered position from the end of string. 
 * @param int|null $length Maximum number of characters to use from string.
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return string
 */
function geodir_utf8_substr( $str, $start, $length = null, $encoding = 'UTF-8' ) {
    if ( function_exists( 'mb_substr' ) ) {
        if ( $length === null ) {
            return mb_substr( $str, $start, geodir_utf8_strlen( $str, $encoding ), $encoding );
        } else {
            return mb_substr( $str, $start, $length, $encoding );
        }
    }
        
    return substr( $str, $start, $length );
}

/**
 * Get the width of string.
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The string being decoded.
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return string The width of string.
 */
function geodir_utf8_strwidth( $str, $encoding = 'UTF-8' ) {
	if ( function_exists( 'mb_strwidth' ) ) {
		return mb_strwidth( $str, $encoding );
	}

	return geodir_utf8_strlen( $str, $encoding );
}

/**
 * Get a string with the first character of string capitalized.
 *
 * @since 1.6.18
 * @package Geodirectory
 *
 * @param string $str The input string.
 * @param bool $lower_str_end If true it returns string lowercased except first character.
 * @param string $encoding The encoding parameter is the character encoding. Default "UTF-8".
 * @return string The resulting string.
 */
function geodir_utf8_ucfirst( $str, $lower_str_end = false, $encoding = 'UTF-8' ) {
    if ( function_exists( 'mb_strlen' ) ) {
        $first_letter = geodir_strtoupper( geodir_utf8_substr( $str, 0, 1, $encoding ), $encoding );
        $str_end = "";
        
        if ( $lower_str_end ) {
            $str_end = geodir_strtolower( geodir_utf8_substr( $str, 1, geodir_utf8_strlen( $str, $encoding ), $encoding ), $encoding );
        } else {
            $str_end = geodir_utf8_substr( $str, 1, geodir_utf8_strlen( $str, $encoding ), $encoding );
        }
        
        return $first_letter . $str_end;
    }

    return ucfirst( $str );
}

function geodir_total_listings_count($post_type = false)
{
	global $wpdb;

	$count = 0;
	
	if ($post_type) {
		$count = $count + $wpdb->get_var("select count(post_id) from " . $wpdb->prefix . "geodir_" . $post_type . "_detail");
	} else {
		$all_postypes = geodir_get_posttypes();

		if (!empty($all_postypes)) {
			foreach ($all_postypes as $key) {
				$count = $count + $wpdb->get_var("select count(post_id) from " . $wpdb->prefix . "geodir_" . $key . "_detail");
			}
		}	
	}

	return $count;
}

function geodir_get_diagnose_step_max_items() {
	return 5;
}