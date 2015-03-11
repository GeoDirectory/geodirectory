<?php
/**
 * Pluign general functions
 *
 * @since 1.0.0
 * @package GeoDirectory
 */


/**
 * Get All Plugin functions from WordPress
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/*-----------------------------------------------------------------------------------*/
/* Helper functions */
/*-----------------------------------------------------------------------------------*/

/**
 * Return the plugin url.
 *
 * Return the plugin folder url WITHOUT TRAILING SLASH.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return string example url eg: http://wpgeo.directory/wp-content/plugins/geodirectory
 */
function geodir_plugin_url()
{

    if (is_ssl()) :
        return str_replace('http://', 'https://', WP_PLUGIN_URL) . "/" . plugin_basename(dirname(dirname(__FILE__)));
    else :
        return WP_PLUGIN_URL . "/" . plugin_basename(dirname(dirname(__FILE__)));
    endif;
}


/**
 * Return the plugin path.
 *
 * Return the plugin folder path WITHOUT TRAILING SLASH.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return string example url eg: /home/geo/public_html/wp-content/plugins/geodirectory
 */
function geodir_plugin_path()
{
    return WP_PLUGIN_DIR . "/" . plugin_basename(dirname(dirname(__FILE__)));
}

/**
 * Check if a GeoDirectory addon plugin is active.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $plugin plugin uri
 * @return bool true or false
 * @todo check if this is faster than normal WP check and remvoe if not.
 */
function geodir_is_plugin_active($plugin)
{
    $active_plugins = get_option('active_plugins');
    foreach ($active_plugins as $key => $active_plugin) {
        if (strstr($active_plugin, $plugin)) return true;
    }
    return false;
}


/**
 * Return the formatted date.
 *
 * Return a formatted date from a date/time string according to WordPress date format. $date must be in format : 'Y-m-d H:i:s'.
 *
 * @since 1.0.0
 * @param string $date must be in format: 'Y-m-d H:i:s'
 * @return bool|int|string the formatted date
 */
function geodir_get_formated_date($date)
{
    return mysql2date(get_option('date_format'), $date);
}

/**
 * Return the formatted time.
 *
 * Return a formatted time from a date/time string according to WordPress time format. $time must be in format : 'Y-m-d H:i:s'.
 *
 * @since 1.0.0
 * @param string $time must be in format: 'Y-m-d H:i:s'
 * @return bool|int|string the formatted time
 */
function geodir_get_formated_time($time)
{
    return mysql2date(get_option('time_format'), $time, $translate = true);
}

/**
 * Create Url through query string var
 **/
/**
 * Return a formatted link with parameters.
 *
 * Returns a link with new parameters and currently used parameters regardless of ? or & in the $url parameter.
 *
 * @param string $url The main url to be used.
 * @param array $params
 * @param bool  $use_existing_arguments
 * @return string
 */
function geodir_getlink($url, $params = array(), $use_existing_arguments = false)
{
    if ($use_existing_arguments) $params = $params + $_GET;
    if (!$params) return $url;
    $link = $url;
    if (strpos($link, '?') === false) $link .= '?'; //If there is no '?' add one at the end
    elseif (strpos($link, '//maps.google.com/maps/api/js?sensor=false&language=')) $link .= '&amp;'; //If there is no '&' at the END, add one.
    elseif (!preg_match('/(\?|\&(amp;)?)$/', $link)) $link .= '&'; //If there is no '&' at the END, add one.

    $params_arr = array();
    foreach ($params as $key => $value) {
        if (gettype($value) == 'array') { //Handle array data properly
            foreach ($value as $val) {
                $params_arr[] = $key . '[]=' . urlencode($val);
            }
        } else {
            $params_arr[] = $key . '=' . urlencode($value);
        }
    }
    $link .= implode('&', $params_arr);

    return $link;
}


/**
 * @param string $post_type
 * @return string|void
 */
function geodir_get_addlisting_link($post_type = '')
{
    global $wpdb;

    //$check_pkg  = $wpdb->get_var("SELECT pid FROM ".GEODIR_PRICE_TABLE." WHERE post_type='".$post_type."' and status != '0'");
    $check_pkg = 1;
    if (post_type_exists($post_type) && $check_pkg) {

        $add_listing_link = get_page_link(get_option('geodir_add_listing_page'));

        return add_query_arg(array('listing_type' => $post_type), $add_listing_link);
    } else
        return get_bloginfo('url');
}

/**
 * @return string
 */
function geodir_curPageURL()
{
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    //return str_replace("www.", "", $pageURL);
    return apply_filters('geodir_curPageURL',$pageURL);
}


/**
 * Clean variables
 * This function is used to create posttype, posts, taxonomy and terms slug
 **/
function geodir_clean($string)
{

    $string = trim(strip_tags(stripslashes($string)));
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-\_]/', '', $string); // Removes special chars.
    $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

    return $string;
}

/**
 * Get Week Days
 **/
function geodir_get_weekday()
{
    return array(__('Sunday', GEODIRECTORY_TEXTDOMAIN), __('Monday', GEODIRECTORY_TEXTDOMAIN), __('Tuesday', GEODIRECTORY_TEXTDOMAIN), __('Wednesday', GEODIRECTORY_TEXTDOMAIN), __('Thursday', GEODIRECTORY_TEXTDOMAIN), __('Friday', GEODIRECTORY_TEXTDOMAIN), __('Saturday', GEODIRECTORY_TEXTDOMAIN));
}

/**
 * Get Weeks
 **/
function geodir_get_weeks()
{
    return array(__('First', GEODIRECTORY_TEXTDOMAIN), __('Second', GEODIRECTORY_TEXTDOMAIN), __('Third', GEODIRECTORY_TEXTDOMAIN), __('Fourth', GEODIRECTORY_TEXTDOMAIN), __('Last', GEODIRECTORY_TEXTDOMAIN));
}


/**
 * Check that page is
 **/
function geodir_is_page($gdpage = '')
{

    global $wp_query, $post;
    //if(!is_admin()):

    switch ($gdpage):
        case 'add-listing':

            if (is_page() && get_query_var('page_id') == get_option('geodir_add_listing_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'add_listing')) {
                return true;
            }

            break;
        case 'preview':
            if ((is_page() && get_query_var('page_id') == get_option('geodir_preview_page')) && isset($_REQUEST['listing_type'])
                && in_array($_REQUEST['listing_type'], geodir_get_posttypes())
            )
                return true;
            break;
        case 'listing-success':
            if (is_page() && get_query_var('page_id') == get_option('geodir_success_page'))
                return true;
            break;
        case 'detail':
            if (is_single() && in_array(get_query_var('post_type'), geodir_get_posttypes()))
                return true;
            break;
        case 'listing':
            if (is_page() && get_query_var('page_id') == get_option('geodir_listing_page'))
                return true;
            if (is_tax() && geodir_get_taxonomy_posttype()) {
                global $current_term, $taxonomy, $term;

                return true;
            }
            if (is_post_type_archive() && in_array(get_query_var('post_type'), geodir_get_posttypes()))
                return true;

            break;
        case 'location':
            if (is_page() && get_query_var('page_id') == get_option('geodir_location_page'))
                return true;
            break;
        case 'author':
            if (is_author() && isset($_REQUEST['geodir_dashbord']))
                return true;
            break;
        case 'search':
            if (is_search() && isset($_REQUEST['geodir_search']))
                return true;
            break;
        default:
            return false;
            break;

    endswitch;

    //endif;

    return false;
}

/**
 * @param $wp
 */
function geodir_set_is_geodir_page($wp)
{
    if (!is_admin()) {
        //$wp->query_vars['gd_is_geodir_page'] = false;	
        //print_r()
        if (empty($wp->query_vars) || !array_diff(array_keys($wp->query_vars), array('preview', 'page', 'paged', 'cpage'))) {
            if (get_option('geodir_set_as_home'))
                $wp->query_vars['gd_is_geodir_page'] = true;
        }

        if (!isset($wp->query_vars['gd_is_geodir_page']) && isset($wp->query_vars['page_id'])) {
            if (
                $wp->query_vars['page_id'] == get_option('geodir_add_listing_page')
                || $wp->query_vars['page_id'] == get_option('geodir_preview_page')
                || $wp->query_vars['page_id'] == get_option('geodir_success_page')
                || $wp->query_vars['page_id'] == get_option('geodir_listing_page')
                || $wp->query_vars['page_id'] == get_option('geodir_location_page')
            )
                $wp->query_vars['gd_is_geodir_page'] = true;
        }

        if (!isset($wp->query_vars['gd_is_geodir_page']) && isset($wp->query_vars['pagename'])) {
            $page = get_page_by_path($wp->query_vars['pagename']);

            if (!empty($page) && (
                    $page->ID == get_option('geodir_add_listing_page')
                    || $page->ID == get_option('geodir_preview_page')
                    || $page->ID == get_option('geodir_success_page')
                    || $page->ID == get_option('geodir_listing_page')
                    || $page->ID == get_option('geodir_location_page'))
            )
                $wp->query_vars['gd_is_geodir_page'] = true;
        }

        if (!isset($wp->query_vars['gd_is_geodir_page']) && isset($wp->query_vars['post_type']) && $wp->query_vars['post_type'] != '') {
            $requested_post_type = $wp->query_vars['post_type'];
            // check if this post type is geodirectory post types 
            $post_type_array = geodir_get_posttypes();
            if (in_array($requested_post_type, $post_type_array)) {
                $wp->query_vars['gd_is_geodir_page'] = true;
            }
        }

        if (!isset($wp->query_vars['gd_is_geodir_page'])) {
            $geodir_taxonomis = geodir_get_taxonomies('', true);
            foreach ($geodir_taxonomis as $taxonomy) {
                if (array_key_exists($taxonomy, $wp->query_vars)) {
                    $wp->query_vars['gd_is_geodir_page'] = true;
                    break;
                }
            }
        }

        if (!isset($wp->query_vars['gd_is_geodir_page']) && isset($wp->query_vars['author_name']) && isset($_REQUEST['geodir_dashbord']))
            $wp->query_vars['gd_is_geodir_page'] = true;


        if (!isset($wp->query_vars['gd_is_geodir_page']) && isset($_REQUEST['geodir_search']))
            $wp->query_vars['gd_is_geodir_page'] = true;
        //echo $wp->query_vars['gd_is_geodir_page'] ;
        /*echo "<pre>" ;
		print_r($wp) ;
		echo "</pre>" ;
	//	exit();
			*/
    } // end of is admin
}

/**
 * @return bool
 */
function geodir_is_geodir_page()
{
    global $wp;
    if (isset($wp->query_vars['gd_is_geodir_page']) && $wp->query_vars['gd_is_geodir_page'])
        return true;
    else
        return false;
}

/**
 * Get plugin image sizes
 */
if (!function_exists('geodir_get_imagesize')) {
    /**
     * @param string $size
     * @return array|mixed|void|WP_Error
     */
    function geodir_get_imagesize($size = '')
    {

        $imagesizes = array('list-thumb' => array('w' => 283, 'h' => 188),
            'thumbnail' => array('w' => 125, 'h' => 125),
            'widget-thumb' => array('w' => 50, 'h' => 50),
            'slider-thumb' => array('w' => 100, 'h' => 100)
        );

        $imagesizes = apply_filters('geodir_imagesizes', $imagesizes);

        if (!empty($size) && array_key_exists($size, $imagesizes)) {

            return apply_filters('geodir_get_imagesize_' . $size, $imagesizes[$size]);

        } elseif (!empty($size)) {

            return new WP_Error('geodir_no_imagesize', __("Given image size is not valid", GEODIRECTORY_TEXTDOMAIN));

        }

        return $imagesizes;
    }
}

/**
 * Get an image size
 *
 * Variable is filtered by geodir_get_image_size_{image_size}
 */
/*
function geodir_get_image_size( $image_size ) {
	$return = '';
	switch ($image_size) :
		case "list_thumbnail_size" : $return = get_option('geodirectory_list_thumbnail_size'); break;
	endswitch;
	return apply_filters( 'geodir_get_image_size_'.$image_size, $return );
}
*/


if (!function_exists('createRandomString')) {
    /**
     * @return string
     */
    function createRandomString()
    {
        $chars = "abcdefghijkmlnopqrstuvwxyz1023456789";
        srand((double)microtime() * 1000000);
        $i = 0;
        $rstring = '';
        while ($i <= 25) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $rstring = $rstring . $tmp;
            $i++;
        }
        return $rstring;
    }
}

if (!function_exists('geodir_getDistanceRadius')) {
    /**
     * @param string $uom
     * @return float
     */
    function geodir_getDistanceRadius($uom = 'km')
    {
//	Use Haversine formula to calculate the great circle distance between two points identified by longitude and latitude
        switch (strtolower($uom)):
            case 'km'    :
                $earthMeanRadius = 6371.009; // km
                break;
            case 'm'    :
            case 'meters'    :
                $earthMeanRadius = 6371.009 * 1000; // km
                break;
            case 'miles'    :
                $earthMeanRadius = 3958.761; // miles
                break;
            case 'yards'    :
            case 'yds'    :
                $earthMeanRadius = 3958.761 * 1760; // yards
                break;
            case 'feet'    :
            case 'ft'    :
                $earthMeanRadius = 3958.761 * 1760 * 3; // feet
                break;
            case 'nm'    :
                $earthMeanRadius = 3440.069; //  miles
                break;
            default:
                $earthMeanRadius = 3958.761; // miles
                break;
        endswitch;
        return $earthMeanRadius;
    }
}


if (!function_exists('geodir_calculateDistanceFromLatLong')) {
    /**
     * @param        $point1
     * @param        $point2
     * @param string $uom
     * @return float
     */
    function geodir_calculateDistanceFromLatLong($point1, $point2, $uom = 'km')
    {
//	Use Haversine formula to calculate the great circle distance between two points identified by longitude and latitude

        $earthMeanRadius = geodir_getDistanceRadius($uom);

        $deltaLatitude = deg2rad($point2['latitude'] - $point1['latitude']);
        $deltaLongitude = deg2rad($point2['longitude'] - $point1['longitude']);
        $a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) +
            cos(deg2rad($point1['latitude'])) * cos(deg2rad($point2['latitude'])) *
            sin($deltaLongitude / 2) * sin($deltaLongitude / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthMeanRadius * $c;
        return $distance;

    }
}


if (!function_exists('geodir_sendEmail')) {
    /**
     * @param        $fromEmail
     * @param        $fromEmailName
     * @param        $toEmail
     * @param        $toEmailName
     * @param        $to_subject
     * @param        $to_message
     * @param string $extra
     * @param        $message_type
     * @param string $post_id
     * @param string $user_id
     */
    function geodir_sendEmail($fromEmail, $fromEmailName, $toEmail, $toEmailName, $to_subject, $to_message, $extra = '', $message_type, $post_id = '', $user_id = '')
    {
        $login_details = '';

        if ($message_type == 'send_friend') {
            $subject = stripslashes(get_option('geodir_email_friend_subject'));
            $message = stripslashes(get_option('geodir_email_friend_content'));
        } elseif ($message_type == 'send_enquiry') {
            $subject = stripslashes(get_option('geodir_email_enquiry_subject'));
            $message = stripslashes(get_option('geodir_email_enquiry_content'));
        } elseif ($message_type == 'forgot_password') {
            $subject = stripslashes(get_option('geodir_forgot_password_subject'));
            $message = stripslashes(get_option('geodir_forgot_password_content'));
            $login_details = $to_message;
        } elseif ($message_type == 'registration') {
            $subject = stripslashes(get_option('geodir_registration_success_email_subject'));
            $message = stripslashes(get_option('geodir_registration_success_email_content'));
            $login_details = $to_message;
        } elseif ($message_type == 'post_submit') {
            $subject = stripslashes(get_option('geodir_post_submited_success_email_subject'));
            $message = stripslashes(get_option('geodir_post_submited_success_email_content'));
        } elseif ($message_type == 'listing_published') {
            $subject = stripslashes_deep(get_option('geodir_post_published_email_subject'));
            $message = stripslashes_deep(get_option('geodir_post_published_email_content'));
        }

        $to_message = nl2br($to_message);
        $sitefromEmail = get_option('site_email');
        $sitefromEmailName = get_site_emailName();
        $productlink = get_permalink($post_id);

        $posted_date = '';
        $listingLink = '';

        $post_info = get_post($post_id);

        if ($post_info) {
            $posted_date = $post_info->post_date;
            $listingLink = '<a href="' . $productlink . '"><b>' . $post_info->post_title . '</b></a>';
        }
        $siteurl = home_url();
        $siteurl_link = '<a href="' . $siteurl . '">' . $siteurl . '</a>';
        $loginurl = home_url() . '/?geodir_signup=true';
        $loginurl_link = '<a href="' . $loginurl . '">login</a>';

        if ($fromEmail == '') {
            $fromEmail = get_option('site_email');
        }

        if ($fromEmailName == '') {
            $fromEmailName = get_option('site_email_name');
        }

        $search_array = array('[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#to_name#]', '[#from_name#]', '[#subject#]', '[#comments#]', '[#login_url#]', '[#login_details#]', '[#client_name#]', '[#posted_date#]');
        $replace_array = array($listingLink, $siteurl_link, $post_id, $sitefromEmailName, $toEmailName, $fromEmailName, $to_subject, $to_message, $loginurl_link, $login_details, $toEmailName, $posted_date);
        $message = str_replace($search_array, $replace_array, $message);

        $search_array = array('[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#to_name#]', '[#from_name#]', '[#subject#]', '[#client_name#]', '[#posted_date#]');
        $replace_array = array($listingLink, $siteurl_link, $post_id, $sitefromEmailName, $toEmailName, $fromEmailName, $to_subject, $toEmailName, $posted_date);
        $subject = str_replace($search_array, $replace_array, $subject);

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        //$headers .= 'To: '.$toEmailName.' <'.$toEmail.'>' . "\r\n"; //  this causes wp_mail to fail on some servers, best just leave it out
        $headers .= 'From: ' . $sitefromEmailName . ' <' . $sitefromEmail . '>' . "\r\n";


        @wp_mail($toEmail, $subject, $message, $headers);

        ///////// ADMIN BCC EMIALS
        $adminEmail = get_bloginfo('admin_email');

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        //$headers .= 'To: <'.$adminEmail.'>' . "\r\n";//  this causes wp_mail to fail on some servers, best just leave it out
        $headers .= 'From: ' . $sitefromEmailName . ' <' . $sitefromEmail . '>' . "\r\n";


        if ($message_type == 'post_submit') {

            $subject = stripslashes(get_option('geodir_post_submited_success_email_subject_admin'));
            $message = stripslashes(get_option('geodir_post_submited_success_email_content_admin'));


            $search_array = array('[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#to_name#]', '[#from_name#]', '[#subject#]', '[#comments#]', '[#login_url#]', '[#login_details#]', '[#client_name#]', '[#posted_date#]');
            $replace_array = array($listingLink, $siteurl_link, $post_id, $sitefromEmailName, $toEmailName, $fromEmailName, $to_subject, $to_message, $loginurl_link, $login_details, $toEmailName, $posted_date);
            $message = str_replace($search_array, $replace_array, $message);

            $search_array = array('[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#to_name#]', '[#from_name#]', '[#subject#]', '[#client_name#]', '[#posted_date#]');
            $replace_array = array($listingLink, $siteurl_link, $post_id, $sitefromEmailName, $toEmailName, $fromEmailName, $to_subject, $toEmailName, $posted_date);
            $subject = str_replace($search_array, $replace_array, $subject);

            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);

        }

        if ($message_type == 'registration' && get_option('geodir_bcc_new_user')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }

        if ($message_type == 'send_friend' && get_option('geodir_bcc_friend')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }

        if ($message_type == 'send_enquiry' && get_option('geodir_bcc_enquiry')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }
        if ($message_type == 'listing_published' && get_option('geodir_bcc_listing_published')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }

    }
}


/**
 *
 */
function geodir_taxonomy_breadcrumb()
{

    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $parent = $term->parent;

    while ($parent):
        $parents[] = $parent;
        $new_parent = get_term_by('id', $parent, get_query_var('taxonomy'));
        $parent = $new_parent->parent;
    endwhile;

    if (!empty($parents)):
        $parents = array_reverse($parents);

        foreach ($parents as $parent):
            $item = get_term_by('id', $parent, get_query_var('taxonomy'));
            $url = get_term_link($item, get_query_var('taxonomy'));
            echo '<li> > <a href="' . $url . '">' . $item->name . '</a></li>';
        endforeach;

    endif;

    echo '<li> > ' . $term->name . '</li>';
}


/**
 *
 */
function geodir_breadcrumb()
{
    global $wp_query, $geodir_add_location_url;

    $separator = apply_filters('geodir_breadcrumb_separator', ' > ');

    if (!is_home()) {
        $breadcrumb = '';
        $url_categoris = '';
        $breadcrumb .= '<div class="geodir-breadcrumb clearfix"><ul id="breadcrumbs">';
        $breadcrumb .= '<li>' . apply_filters('geodir_breadcrumb_first_link', '<a href="' . get_option('home') . '">' . __('Home', GEODIRECTORY_TEXTDOMAIN) . '</a>') . '</li>';

        $gd_post_type = geodir_get_current_posttype();
        $post_type_info = get_post_type_object($gd_post_type);

        remove_filter('post_type_archive_link', 'geodir_get_posttype_link');

        $listing_link = get_post_type_archive_link($gd_post_type);

        add_filter('post_type_archive_link', 'geodir_get_posttype_link', 10, 2);
        $listing_link = rtrim($listing_link, '/');
        $listing_link .= '/';

        $post_type_for_location_link = $listing_link;
        $location_terms = geodir_get_current_location_terms('query_vars');
        $location_link = $post_type_for_location_link;

        if (geodir_is_page('detail') || geodir_is_page('listing')) {
            global $post;
            $is_location_last = '';
            $is_taxonomy_last = '';
            $breadcrumb .= '<li>';
            if (get_query_var($gd_post_type . 'category'))
                $gd_taxonomy = $gd_post_type . 'category';
            elseif (get_query_var($gd_post_type . '_tags'))
                $gd_taxonomy = $gd_post_type . '_tags';

            $breadcrumb .= $separator . '<a href="' . $listing_link . '">' . __(ucfirst($post_type_info->label), GEODIRECTORY_TEXTDOMAIN) . '</a>';
            if (!empty($gd_taxonomy) || geodir_is_page('detail'))
                $is_location_last = false;
            else
                $is_location_last = true;

            if (!empty($gd_taxonomy) && geodir_is_page('listing'))
                $is_taxonomy_last = true;
            else
                $is_taxonomy_last = false;

            if (!empty($location_terms)) {
                $geodir_get_locations = function_exists('get_actual_location_name') ? true : false;

                foreach ($location_terms as $key => $location_term) {
                    if ($location_term != '') {
                        $gd_location_link_text = preg_replace('/-(\d+)$/', '', $location_term);
                        $gd_location_link_text = preg_replace('/[_-]/', ' ', $gd_location_link_text);
                        $gd_location_link_text = ucfirst($gd_location_link_text);

                        $location_term_actual_country = '';
                        $location_term_actual_region = '';
                        $location_term_actual_city = '';
                        if ($geodir_get_locations) {
                            if ($key == 'gd_country') {
                                $location_term_actual_country = get_actual_location_name('country', $location_term, true);
                            } else if ($key == 'gd_region') {
                                $location_term_actual_region = get_actual_location_name('region', $location_term, true);
                            } else if ($key == 'gd_city') {
                                $location_term_actual_city = get_actual_location_name('city', $location_term, true);
                            }
                        } else {
                            $location_info = geodir_get_location();

                            if (!empty($location_info) && isset($location_info->location_id)) {
                                if ($key == 'gd_country') {
                                    $location_term_actual_country = __($location_info->country, GEODIRECTORY_TEXTDOMAIN);
                                } else if ($key == 'gd_region') {
                                    $location_term_actual_region = __($location_info->region, GEODIRECTORY_TEXTDOMAIN);
                                } else if ($key == 'gd_city') {
                                    $location_term_actual_city = __($location_info->city, GEODIRECTORY_TEXTDOMAIN);
                                }
                            }
                        }

                        if ($is_location_last && $key == 'gd_country' && !(isset($location_terms['gd_region']) && $location_terms['gd_region'] != '') && !(isset($location_terms['gd_city']) && $location_terms['gd_city'] != '')) {
                            $breadcrumb .= $location_term_actual_country != '' ? $separator . $location_term_actual_country : $separator . $gd_location_link_text;
                        } else if ($is_location_last && $key == 'gd_region' && !(isset($location_terms['gd_city']) && $location_terms['gd_city'] != '')) {
                            $breadcrumb .= $location_term_actual_region != '' ? $separator . $location_term_actual_region : $separator . $gd_location_link_text;
                        } else if ($is_location_last && $key == 'gd_city') {
                            $breadcrumb .= $location_term_actual_city != '' ? $separator . $location_term_actual_city : $separator . $gd_location_link_text;
                        } else {
                            if (get_option('permalink_structure') != '') {
                                $location_link .= $location_term . '/';
                            } else {
                                $location_link .= "&$key=" . $location_term;
                            }

                            if ($key == 'gd_country' && $location_term_actual_country != '') {
                                $gd_location_link_text = $location_term_actual_country;
                            } else if ($key == 'gd_region' && $location_term_actual_region != '') {
                                $gd_location_link_text = $location_term_actual_region;
                            } else if ($key == 'gd_city' && $location_term_actual_city != '') {
                                $gd_location_link_text = $location_term_actual_city;
                            }

                            $breadcrumb .= $separator . '<a href="' . $location_link . '">' . $gd_location_link_text . '</a>';
                        }
                    }
                }
            }

            if (!empty($gd_taxonomy)) {
                $term_index = 1;


                //if(get_option('geodir_add_categories_url'))
                {
                    if (get_query_var($gd_post_type . '_tags')) {
                        $cat_link = $listing_link . 'tags/';
                    } else
                        $cat_link = $listing_link;

                    foreach ($location_terms as $key => $location_term) {
                        if ($location_term != '') {
                            if (get_option('permalink_structure') != '') {
                                $cat_link .= $location_term . '/';

                            }
                        }
                    }

                    $term_array = explode("/", trim($wp_query->query[$gd_taxonomy], "/"));
                    foreach ($term_array as $term) {
                        $term_link_text = preg_replace('/-(\d+)$/', '', $term);
                        $term_link_text = preg_replace('/[_-]/', ' ', $term_link_text);

                        // get term actual name
                        $term_info = get_term_by('slug', $term, $gd_taxonomy, 'ARRAY_A');
                        if (!empty($term_info) && isset($term_info['name']) && $term_info['name'] != '') {
                            $term_link_text = urldecode($term_info['name']);
                        } else {
                            $term_link_text = ucwords(urldecode($term_link_text));
                        }

                        if ($term_index == count($term_array) && $is_taxonomy_last)
                            $breadcrumb .= $separator . $term_link_text;
                        else {
                            $cat_link .= $term . '/';
                            $breadcrumb .= $separator . '<a href="' . $cat_link . '">' . $term_link_text . '</a>';
                        }
                        $term_index++;
                    }
                }


            }

            if (geodir_is_page('detail'))
                $breadcrumb .= $separator . get_the_title();

            $breadcrumb .= '</li>';


        } elseif (geodir_is_page('author')) {
            $user_id = get_current_user_id();
            $author_link = get_author_posts_url($user_id);
            $default_author_link = geodir_getlink($author_link, array('geodir_dashbord' => 'true', 'stype' => 'gd_place'), false);

            // author page link
            $default_author_link = apply_filters('geodir_dashboard_author_link', $default_author_link, $user_id);

            $breadcrumb .= '<li>';
            $breadcrumb .= $separator . '<a href="' . $default_author_link . '">' . __('My Dashboard', GEODIRECTORY_TEXTDOMAIN) . '</a>';

            if (isset($_REQUEST['list'])) {
                $author_link = geodir_getlink($author_link, array('geodir_dashbord' => 'true', 'stype' => $_REQUEST['stype']), false);

                // author page link
                $author_link = apply_filters('geodir_dashboard_author_link', $author_link, $user_id, $_REQUEST['stype']);

                $breadcrumb .= $separator . '<a href="' . $author_link . '">' . __(ucfirst($post_type_info->label), GEODIRECTORY_TEXTDOMAIN) . '</a>';
                $breadcrumb .= $separator . ucfirst(__('My', GEODIRECTORY_TEXTDOMAIN) . ' ' . $_REQUEST['list']);
            } else
                $breadcrumb .= $separator . __(ucfirst($post_type_info->label), GEODIRECTORY_TEXTDOMAIN);

            $breadcrumb .= '</li>';
        } elseif (is_category() || is_single()) {
            $category = get_the_category();
            if (is_category()) {
                $breadcrumb .= '<li>' . $separator . $category[0]->cat_name . '</li>';
            }
            if (is_single()) {
                $breadcrumb .= '<li>' . $separator . '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->cat_name . '</a></li>';
                $breadcrumb .= '<li>' . $separator . get_the_title() . '</li>';
            }
            /* End of my version ##################################################### */
        } else if (is_page()) {
            $page_title = get_the_title();

            if (geodir_is_page('location')) {
                $page_title = defined('GD_LOCATION') ? GD_LOCATION : __('Location', GEODIRECTORY_TEXTDOMAIN);
            }

            $breadcrumb .= '<li>' . $separator;
            $breadcrumb .= stripslashes_deep($page_title);
            $breadcrumb .= '</li>';
        } else if (is_tag()) {
            $separator . single_tag_title();
        } else if (is_day()) {
            $breadcrumb .= "<li> " . $separator . __(" Archive for", GEODIRECTORY_TEXTDOMAIN) . " ";
            the_time('F jS, Y');
            $breadcrumb .= '</li>';
        } else if (is_month()) {
            $breadcrumb .= "<li> " . $separator . __(" Archive for", GEODIRECTORY_TEXTDOMAIN) . " ";
            the_time('F, Y');
            $breadcrumb .= '</li>';
        } else if (is_year()) {
            $breadcrumb .= "<li> " . $separator . __(" Archive for", GEODIRECTORY_TEXTDOMAIN) . " ";
            the_time('Y');
            $breadcrumb .= '</li>';
        } else if (is_author()) {
            $breadcrumb .= "<li> " . $separator . __(" Author Archive", GEODIRECTORY_TEXTDOMAIN);
            $breadcrumb .= '</li>';
        } else if (isset($_GET['paged']) && !empty($_GET['paged'])) {
            $breadcrumb .= "<li>" . $separator . __("Blog Archives", GEODIRECTORY_TEXTDOMAIN);
            $breadcrumb .= '</li>';
        } else if (is_search()) {
            $breadcrumb .= "<li> " . $separator . __(" Search Results", GEODIRECTORY_TEXTDOMAIN);
            $breadcrumb .= '</li>';
        }
        $breadcrumb .= '</ul></div>';

        echo $breadcrumb = apply_filters('geodir_breadcrumb', $breadcrumb, $separator);
    }
}


add_action("admin_init", "geodir_allow_wpadmin"); // check user is admin
if (!function_exists('geodir_allow_wpadmin')) {
    /**
     *
     */
    function geodir_allow_wpadmin()
    {
        global $wpdb;
        if (get_option('geodir_allow_wpadmin') == '0' && is_user_logged_in() && (!isset($_REQUEST['action']))) // checking action in request to allow ajax request go through
        {
            if (current_user_can('manage_options')) {
            } else {

                wp_redirect(home_url());
                exit;
            }

        }
    }
}


/* Move Images from a url to upload directory */
/**
 * @param $url
 * @return array|WP_Error
 */
function fetch_remote_file($url)
{
    // extract the file name and extension from the url
    require_once(ABSPATH . 'wp-includes/pluggable.php');
    $file_name = basename($url);
    if (strpos($file_name, '?') !== false) {
        list($file_name) = explode('?', $file_name);
    }


    // get placeholder file in the upload dir with a unique, sanitized filename

    $post_upload_date = isset($post['upload_date']) ? $post['upload_date'] : '';

    $upload = wp_upload_bits($file_name, 0, '', $post_upload_date);
    if ($upload['error'])
        return new WP_Error('upload_dir_error', $upload['error']);

    // fetch the remote url and write it to the placeholder file
    $headers = wp_get_http($url, $upload['file']);

    // request failed
    if (!$headers) {
        @unlink($upload['file']);
        return new WP_Error('import_file_error', __('Remote server did not respond', GEODIRECTORY_TEXTDOMAIN));
    }

    // make sure the fetch was successful
    if ($headers['response'] != '200') {
        @unlink($upload['file']);
        return new WP_Error('import_file_error', sprintf(__('Remote server returned error response %1$d %2$s', GEODIRECTORY_TEXTDOMAIN), esc_html($headers['response']), get_status_header_desc($headers['response'])));
    }

    $filesize = filesize($upload['file']);

    if (isset($headers['content-length']) && $filesize != $headers['content-length']) {
        @unlink($upload['file']);
        return new WP_Error('import_file_error', __('Remote file is incorrect size', GEODIRECTORY_TEXTDOMAIN));
    }

    if (0 == $filesize) {
        @unlink($upload['file']);
        return new WP_Error('import_file_error', __('Zero size file downloaded', GEODIRECTORY_TEXTDOMAIN));
    }


    return $upload;
}

/**
 * @return mixed|void
 */
function geodir_max_upload_size()
{
    $max_filesize = (float)get_option('geodir_upload_max_filesize', 2);

    if ($max_filesize > 0 && $max_filesize < 1) {
        $max_filesize = (int)($max_filesize * 1024) . 'kb';
    } else {
        $max_filesize = $max_filesize > 0 ? $max_filesize . 'mb' : '2mb';
    }

    return apply_filters('geodir_default_image_upload_size_limit', $max_filesize);
}

/* ------------------------------------------------------------------*/
/* Check if dummy folder exists or not , if not then fatch from live url */
/*--------------------------------------------------------------------*/
/**
 * @return bool
 */
function geodir_dummy_folder_exists()
{
    $path = geodir_plugin_path() . '/geodirectory-admin/dummy/';
    if (!is_dir($path))
        return false;
    else
        return true;

}

/* ------------------------------------------------------------------*/
/* Get the author info*/
/*--------------------------------------------------------------------*/
/**
 * @param $aid
 * @return mixed
 */
function  geodir_get_author_info($aid)
{
    global $wpdb;
    /*$infosql = "select * from $wpdb->users where ID=$aid";*/
    $infosql = $wpdb->prepare("select * from $wpdb->users where ID=%d", array($aid));
    $info = $wpdb->get_results($infosql);
    if ($info) {
        return $info[0];
    }
}

if (!function_exists('adminEmail')) {
    /**
     * @param        $page_id
     * @param        $user_id
     * @param        $message_type
     * @param string $custom_1
     */
    function adminEmail($page_id, $user_id, $message_type, $custom_1 = '')
    {
        global $wpdb;
        if ($message_type == 'expiration') {
            $subject = stripslashes(get_option('renew_email_subject'));
            $client_message = stripslashes(get_option('renew_email_content'));
        } elseif ($message_type == 'post_submited') {
            $subject = get_option('post_submited_success_email_subject_admin');
            $client_message = get_option('post_submited_success_email_content_admin');
        } elseif ($message_type == 'renew') {
            $subject = get_option('post_renew_success_email_subject_admin');
            $client_message = get_option('post_renew_success_email_content_admin');
        } elseif ($message_type == 'upgrade') {
            $subject = get_option('post_upgrade_success_email_subject_admin');
            $client_message = get_option('post_upgrade_success_email_content_admin');
        } elseif ($message_type == 'claim_approved') {
            $subject = get_option('claim_approved_email_subject');
            $client_message = get_option('claim_approved_email_content');
        } elseif ($message_type == 'claim_rejected') {
            $subject = get_option('claim_rejected_email_subject');
            $client_message = get_option('claim_rejected_email_content');
        } elseif ($message_type == 'claim_requested') {
            $subject = get_option('claim_email_subject_admin');
            $client_message = get_option('claim_email_content_admin');
        } elseif ($message_type == 'auto_claim') {
            $subject = get_option('auto_claim_email_subject');
            $client_message = get_option('auto_claim_email_content');
        } elseif ($message_type == 'payment_success') {
            $subject = get_option('post_payment_success_admin_email_subject');
            $client_message = get_option('post_payment_success_admin_email_content');
        } elseif ($message_type == 'payment_fail') {
            $subject = get_option('post_payment_fail_admin_email_subject');
            $client_message = get_option('post_payment_fail_admin_email_content');
        }
        $transaction_details = $custom_1;
        $fromEmail = get_option('site_email');
        $fromEmailName = get_site_emailName();
//$alivedays = get_post_meta($page_id,'alive_days',true);
        $pkg_limit = get_property_price_info_listing($page_id);
        $alivedays = $pkg_limit['days'];
        $productlink = get_permalink($page_id);
        $post_info = get_post($page_id);
        $post_date = date('dS F,Y', strtotime($post_info->post_date));
        $listingLink = '<a href="' . $productlink . '"><b>' . $post_info->post_title . '</b></a>';
        $loginurl = home_url() . '/?ptype=login';
        $loginurl_link = '<a href="' . $loginurl . '">login</a>';
        $siteurl = home_url();
        $siteurl_link = '<a href="' . $siteurl . '">' . $fromEmailName . '</a>';
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        $display_name = $user_info->first_name;
        $user_login = $user_info->user_login;
        $number_of_grace_days = get_option('ptthemes_listing_preexpiry_notice_days');
        if ($number_of_grace_days == '') {
            $number_of_grace_days = 1;
        }
        if ($post_info->post_type == 'event') {
            $post_type = 'event';
        } else {
            $post_type = 'listing';
        }
        $renew_link = '<a href="' . $siteurl . '?ptype=post_' . $post_type . '&renew=1&pid=' . $page_id . '">' . RENEW_LINK . '</a>';
        $search_array = array('[#client_name#]', '[#listing_link#]', '[#posted_date#]', '[#number_of_days#]', '[#number_of_grace_days#]', '[#login_url#]', '[#username#]', '[#user_email#]', '[#site_name_url#]', '[#renew_link#]', '[#post_id#]', '[#site_name#]', '[#transaction_details#]');
        $replace_array = array($display_name, $listingLink, $post_date, $alivedays, $number_of_grace_days, $loginurl_link, $user_login, $user_email, $siteurl_link, $renew_link, $page_id, $fromEmailName, $transaction_details);
        $client_message = str_replace($search_array, $replace_array, $client_message);
        $subject = str_replace($search_array, $replace_array, $subject);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
//$headers .= 'To: <'.$fromEmail.'>' . "\r\n";
        $headers .= 'From: ' . $fromEmailName . ' <' . $fromEmail . '>' . "\r\n";
        @wp_mail($fromEmail, $subject, $client_message, $headers);///To client email
    }
}
################################# ADMIN EMAIL FUNCTION END ##############################################################
################################# SEND EMAIL FUNCTION START #############################################################
if (!function_exists('sendEmail')) {
    /**
     * @param        $fromEmail
     * @param        $fromEmailName
     * @param        $toEmail
     * @param        $toEmailName
     * @param        $to_subject
     * @param        $to_message
     * @param string $extra
     * @param        $message_type
     * @param string $post_id
     * @param string $user_id
     */
    function sendEmail($fromEmail, $fromEmailName, $toEmail, $toEmailName, $to_subject, $to_message, $extra = '', $message_type, $post_id = '', $user_id = '')
    {
        $login_details = '';
        if ($message_type == 'send_friend') {
            $subject = stripslashes(get_option('email_friend_subject'));
            $message = stripslashes(get_option('email_friend_content'));
        } elseif ($message_type == 'send_enquiry') {
            $subject = get_option('email_enquiry_subject');
            $message = get_option('email_enquiry_content');
        } elseif ($message_type == 'forgot_password') {
            $subject = get_option('forgot_password_subject');
            $message = get_option('forgot_password_content');
            $login_details = $to_message;
        } elseif ($message_type == 'registration') {
            $subject = get_option('registration_success_email_subject');
            $message = get_option('registration_success_email_content');
            $login_details = $to_message;
        }
        $to_message = nl2br($to_message);
        $sitefromEmail = get_option('site_email');
        $sitefromEmailName = get_site_emailName();
        $productlink = get_permalink($post_id);
        $post_info = get_post($post_id);
        $listingLink = '<a href="' . $productlink . '"><b>' . $post_info->post_title . '</b></a>';
        $siteurl = home_url();
        $siteurl_link = '<a href="' . $siteurl . '">' . $siteurl . '</a>';
        $loginurl = home_url() . '/?ptype=login';
        $loginurl_link = '<a href="' . $loginurl . '">login</a>';
        if ($fromEmail == '') {
            $fromEmail = get_option('site_email');
        }
        if ($fromEmailName == '') {
            $fromEmailName = get_option('site_email_name');
        }
        $search_array = array('[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#to_name#]', '[#from_name#]', '[#subject#]', '[#comments#]', '[#login_url#]', '[#login_details#]', '[#client_name#]');
        $replace_array = array($listingLink, $siteurl_link, $post_id, $sitefromEmailName, $toEmailName, $fromEmailName, $to_subject, $to_message, $loginurl_link, $login_details, $toEmailName);
        $message = str_replace($search_array, $replace_array, $message);

        $search_array = array('[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#to_name#]', '[#from_name#]', '[#subject#]', '[#client_name#]');
        $replace_array = array($listingLink, $siteurl_link, $post_id, $sitefromEmailName, $toEmailName, $fromEmailName, $to_subject, $toEmailName);
        $subject = str_replace($search_array, $replace_array, $subject);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
//$headers .= 'To: '.$toEmailName.' <'.$toEmail.'>' . "\r\n";
        $headers .= 'From: ' . $sitefromEmailName . ' <' . $sitefromEmail . '>' . "\r\n";
        @wp_mail($toEmail, $subject, $message, $headers);

///////// ADMIN BCC EMIALS
        if ($message_type == 'registration') {
            $message_raw = explode(__("Password:", GEODIRECTORY_TEXTDOMAIN), $message);
            $message_raw2 = explode("</p>", $message_raw[1], 2);
            $message = $message_raw[0] . __('Password:', GEODIRECTORY_TEXTDOMAIN) . ' **********</p>' . $message_raw2[1];
        }
        $adminEmail = get_bloginfo('admin_email');

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
//$headers .= 'To: <'.$adminEmail.'>' . "\r\n";
        $headers .= 'From: ' . $sitefromEmailName . ' <' . $sitefromEmail . '>' . "\r\n";
        if ($message_type == 'registration' && get_option('bcc_new_user')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }
        if ($message_type == 'send_friend' && get_option('bcc_friend')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }
        if ($message_type == 'send_enquiry' && get_option('bcc_enquiry')) {
            $subject .= ' - ADMIN BCC COPY';
            @wp_mail($adminEmail, $subject, $message, $headers);
        }
    }
}
/*--------------------------------------------------------------------*/
/* Functions */
/*--------------------------------------------------------------------*/

/*
Language translation helper functions
*/

// function to get the translated category id's
/**
 * @param $ids_array
 * @param $type
 * @return array
 */
function gd_lang_object_ids($ids_array, $type)
{
    if (function_exists('icl_object_id')) {
        $res = array();
        foreach ($ids_array as $id) {
            $xlat = icl_object_id($id, $type, false);
            if (!is_null($xlat)) $res[] = $xlat;
        }
        return $res;
    } else {
        return $ids_array;
    }
}


// function to add class to body when multi post type is active
/**
 * @param $classes
 * @return array
 */
function geodir_custom_posts_body_class($classes)
{
    global $wpdb;
    $post_types = geodir_get_posttypes('object');
    if (!empty($post_types) && count((array)$post_types) > 1) {
        $classes[] = 'geodir_custom_posts';
    }

    // fix body class for signup page
    if (isset($_REQUEST['geodir_signup'])) {
        $new_classes = array();
        $new_classes[] = 'signup page-geodir-signup';
        if (!empty($classes)) {
            foreach ($classes as $class) {
                if ($class && $class != 'home' && $class != 'blog') {
                    $new_classes[] = $class;
                }
            }
        }
        $classes = $new_classes;
    }

    return $classes;
}

add_filter('body_class', 'geodir_custom_posts_body_class'); // let's add a class to the body so we can style the new addition to the search


/**
 * @return mixed|void
 */
function geodir_map_zoom_level()
{

    return apply_filters('geodir_map_zoom_level', array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19));

}


/**
 * @param $geodir_option_name
 */
function geodir_option_version_backup($geodir_option_name)
{
    $version_date = time();
    $geodir_option = get_option($geodir_option_name);

    if (!empty($geodir_option)) {
        add_option($geodir_option_name . '_' . $version_date, $geodir_option);
    }
}

/* display add listing page for wpml */
/**
 * @param $page_id
 * @return mixed
 */
function get_page_id_geodir_add_listing_page($page_id)
{
    if (geodir_wpml_multilingual_status()) {
        $post_type = 'post_page';
        $page_id = geodir_get_wpml_element_id($page_id, $post_type);
    }
    return $page_id;
}

/**
 * @return bool
 */
function geodir_wpml_multilingual_status()
{
    if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
        return true;
    }
    return false;
}

/**
 * @param $page_id
 * @param $post_type
 * @return mixed
 */
function geodir_get_wpml_element_id($page_id, $post_type)
{
    global $sitepress;
    if (geodir_wpml_multilingual_status() && !empty($sitepress) && isset($sitepress->queries)) {
        $trid = $sitepress->get_element_trid($page_id, $post_type);

        if ($trid > 0) {
            $translations = $sitepress->get_element_translations($trid, $post_type);

            $lang = $sitepress->get_current_language();
            $lang = $lang ? $lang : $sitepress->get_default_language();

            if (!empty($translations) && !empty($lang) && isset($translations[$lang]) && isset($translations[$lang]->element_id) && !empty($translations[$lang]->element_id)) {
                $page_id = $translations[$lang]->element_id;
            }
        }
    }
    return $page_id;
}

/**
 *
 */
function geodir_wpml_check_element_id()
{
    global $sitepress;
    if (geodir_wpml_multilingual_status() && !empty($sitepress) && isset($sitepress->queries)) {
        $el_type = 'post_page';
        $el_id = get_option('geodir_add_listing_page');
        $default_lang = $sitepress->get_default_language();
        $el_details = $sitepress->get_element_language_details($el_id, $el_type);

        if (!($el_id > 0 && $default_lang && !empty($el_details) && isset($el_details->language_code) && $el_details->language_code == $default_lang)) {
            if (!$el_details->source_language_code) {
                $sitepress->set_element_language_details($el_id, $el_type, '', $default_lang);
                $sitepress->icl_translations_cache->clear();
            }
        }
    }
}

/**
 * @param $query_args
 * @return string
 */
function geodir_widget_listings_get_order($query_args)
{
    global $wpdb, $plugin_prefix, $gd_query_args_widgets;

    $query_args = $gd_query_args_widgets;
    if (empty($query_args) || empty($query_args['is_geodir_loop'])) {
        return $wpdb->posts . ".post_date DESC, ";
    }

    $post_type = empty($query_args['post_type']) ? 'gd_place' : $query_args['post_type'];
    $table = $plugin_prefix . $post_type . '_detail';

    $sort_by = !empty($query_args['order_by']) ? $query_args['order_by'] : '';

    switch ($sort_by) {
        case 'latest':
        case 'newest':
            $orderby = $wpdb->posts . ".post_date DESC, ";
            break;
        case 'featured':
            $orderby = $table . ".is_featured ASC, ";
            break;
        case 'az':
            $orderby = $wpdb->posts . ".post_title ASC, ";
            break;
        case 'high_review':
            $orderby = $wpdb->posts . ".comment_count DESC, ";
            break;
        case 'high_rating':
            $orderby = "( " . $table . ".overall_rating  ) DESC, ";
            break;
        case 'random':
            $orderby = "RAND(), ";
            break;
        default:
            $orderby = $wpdb->posts . ".post_title ASC, ";
            break;
    }

    return $orderby;
}

/**
 * @param array $query_args
 * @return mixed
 */
function geodir_get_widget_listings($query_args = array())
{
    global $wpdb, $plugin_prefix, $table_prefix;
    $GLOBALS['gd_query_args_widgets'] = $query_args;
    $gd_query_args_widgets = $query_args;

    $post_type = empty($query_args['post_type']) ? 'gd_place' : $query_args['post_type'];
    $table = $plugin_prefix . $post_type . '_detail';

    $fields = $wpdb->posts . ".*, " . $table . ".*";
    $fields = apply_filters('geodir_filter_widget_listings_fields', $fields, $table, $post_type);

    $join = "INNER JOIN " . $table . " ON (" . $table . ".post_id = " . $wpdb->posts . ".ID)";

    ########### WPML ###########

    if (function_exists('icl_object_id')) {
        global $sitepress;
        $lang_code = ICL_LANGUAGE_CODE;
        $default_lang_code = $sitepress->get_default_language();
        if ($lang_code) {
            $join .= " JOIN " . $table_prefix . "icl_translations icl_t ON icl_t.element_id = " . $table_prefix . "posts.ID";
        }

    }

    ########### WPML ###########

    $join = apply_filters('geodir_filter_widget_listings_join', $join, $post_type);

    $post_status = is_super_admin() ? " OR " . $wpdb->posts . ".post_status = 'private'" : '';

    $where = " AND ( " . $wpdb->posts . ".post_status = 'publish' " . $post_status . " ) AND " . $wpdb->posts . ".post_type = '" . $post_type . "'";

    ########### WPML ###########
    if (function_exists('icl_object_id')) {
        if ($lang_code) {
            $where .= " AND icl_t.language_code = '$lang_code' ";
        }
    }
    ########### WPML ###########

    $where = apply_filters('geodir_filter_widget_listings_where', $where, $post_type);
    $where = $where != '' ? " WHERE 1=1 " . $where : '';

    $groupby = " GROUP BY $wpdb->posts.ID ";
    $groupby = apply_filters('geodir_filter_widget_listings_groupby', $groupby, $post_type);

    $orderby = geodir_widget_listings_get_order($query_args);
    $orderby = apply_filters('geodir_filter_widget_listings_orderby', $orderby, $table, $post_type);
    $orderby .= $wpdb->posts . ".post_title ASC";
    $orderby = $orderby != '' ? " ORDER BY " . $orderby : '';

    $limit = !empty($query_args['posts_per_page']) ? $query_args['posts_per_page'] : 5;
    $limit = apply_filters('geodir_filter_widget_listings_limit', $limit, $post_type);

    $limit = $limit > 0 ? " LIMIT " . (int)$limit : "";

    $sql = "SELECT SQL_CALC_FOUND_ROWS " . $fields . " FROM " . $wpdb->posts . "
		" . $join . "
		" . $where . "
		" . $groupby . "
		" . $orderby . "
		" . $limit;
    //echo '<pre>sql : '; print_r($sql); echo '</pre>';// exit;

    $rows = $wpdb->get_results($sql);

    unset($GLOBALS['gd_query_args_widgets']);
    unset($gd_query_args_widgets);

    return $rows;
}

/**
 * @param $fields
 * @return mixed
 */
function geodir_function_widget_listings_fields($fields)
{
    global $wpdb, $plugin_prefix, $gd_query_args_widgets;

    $query_args = $gd_query_args_widgets;
    if (empty($query_args) || empty($query_args['is_geodir_loop'])) {
        return $fields;
    }
    $post_type = empty($query_args['post_type']) ? 'gd_place' : $query_args['post_type'];
    $table = $plugin_prefix . $post_type . '_detail';

    /*
	if ( $post_type == 'gd_event' && defined( 'EVENT_SCHEDULE' ) ) {
		$fields .= $fields != '' ? ", " . EVENT_SCHEDULE . ".*" : EVENT_SCHEDULE . ".*";
	}
	*/
    return $fields;
}

/**
 * @param $join
 * @return string
 */
function geodir_function_widget_listings_join($join)
{
    global $wpdb, $plugin_prefix, $gd_query_args_widgets;

    $query_args = $gd_query_args_widgets;
    if (empty($query_args) || empty($query_args['is_geodir_loop'])) {
        return $where;
    }

    $post_type = empty($query_args['post_type']) ? 'gd_place' : $query_args['post_type'];
    $table = $plugin_prefix . $post_type . '_detail';

    if (!empty($query_args['with_pics_only'])) {
        $join .= " LEFT JOIN " . GEODIR_ATTACHMENT_TABLE . " ON ( " . GEODIR_ATTACHMENT_TABLE . ".post_id=" . $table . ".post_id AND " . GEODIR_ATTACHMENT_TABLE . ".mime_type LIKE '%image%' )";
    }

    /*
	if ( $post_type == 'gd_event' && defined( 'EVENT_SCHEDULE' ) ) {
		$join .= " INNER JOIN " . EVENT_SCHEDULE ." ON (" . EVENT_SCHEDULE .".event_id = " . $wpdb->posts . ".ID)";
	}
	*/

    if (!empty($query_args['tax_query'])) {
        $tax_queries = get_tax_sql($query_args['tax_query'], $wpdb->posts, 'ID');
        if (!empty($tax_queries['join']) && !empty($tax_queries['where'])) {
            $join .= $tax_queries['join'];
        }
    }

    return $join;
}

/**
 * @param $where
 * @return string
 */
function geodir_function_widget_listings_where($where)
{
    global $wpdb, $plugin_prefix, $gd_query_args_widgets;

    $query_args = $gd_query_args_widgets;
    if (empty($query_args) || empty($query_args['is_geodir_loop'])) {
        return $where;
    }
    $post_type = empty($query_args['post_type']) ? 'gd_place' : $query_args['post_type'];
    $table = $plugin_prefix . $post_type . '_detail';

    if (!empty($query_args)) {
        if (!empty($query_args['gd_location']) && function_exists('geodir_default_location_where')) {
            $where = geodir_default_location_where($where, $table);
        }

        if (!empty($query_args['show_featured_only'])) {
            $where .= " AND " . $table . ".is_featured = '1'";
        }

        if (!empty($query_args['show_special_only'])) {
            $where .= " AND ( " . $table . ".geodir_special_offers != '' AND " . $table . ".geodir_special_offers IS NOT NULL )";
        }

        if (!empty($query_args['with_pics_only'])) {
            $where .= " AND " . GEODIR_ATTACHMENT_TABLE . ".ID IS NOT NULL ";
        }

        if (!empty($query_args['featured_image_only'])) {
            $where .= " AND " . $table . ".featured_image IS NOT NULL AND " . $table . ".featured_image!='' ";
        }

        if (!empty($query_args['with_videos_only'])) {
            $where .= " AND ( " . $table . ".geodir_video != '' AND " . $table . ".geodir_video IS NOT NULL )";
        }

        if (!empty($query_args['tax_query'])) {
            $tax_queries = get_tax_sql($query_args['tax_query'], $wpdb->posts, 'ID');

            if (!empty($tax_queries['join']) && !empty($tax_queries['where'])) {
                $where .= $tax_queries['where'];
            }
        }
    }

    return $where;
}

/**
 * @param $orderby
 * @return mixed
 */
function geodir_function_widget_listings_orderby($orderby)
{
    global $wpdb, $plugin_prefix, $gd_query_args_widgets;

    $query_args = $gd_query_args_widgets;
    if (empty($query_args) || empty($query_args['is_geodir_loop'])) {
        return $orderby;
    }

    $post_type = empty($query_args['post_type']) ? 'gd_place' : $query_args['post_type'];
    $table = $plugin_prefix . $post_type . '_detail';

    /*
	if ( $post_type == 'gd_event' && defined( 'EVENT_SCHEDULE' ) ) {
		$orderby .= EVENT_SCHEDULE . ".event_date ASC, " . EVENT_SCHEDULE . ".event_starttime ASC , " . $table . ".is_featured ASC, ";
	}
	*/

    return $orderby;
}

/**
 * @param $limit
 * @return int
 */
function geodir_function_widget_listings_limit($limit)
{
    global $wpdb, $plugin_prefix, $gd_query_args_widgets;

    $query_args = $gd_query_args_widgets;
    if (empty($query_args) || empty($query_args['is_geodir_loop'])) {
        return $limit;
    }

    if (!empty($query_args) && !empty($query_args['posts_per_page'])) {
        $limit = (int)$query_args['posts_per_page'];
    }

    return $limit;
}

// wp media large width
/**
 * @param int    $default
 * @param string $params
 * @return int|mixed|void
 */
function geodir_media_image_large_width($default = 800, $params = '')
{
    $large_size_w = get_option('large_size_w');
    $large_size_w = $large_size_w > 0 ? $large_size_w : $default;
    $large_size_w = absint($large_size_w);

    if (!get_option('geodir_use_wp_media_large_size')) {
        $large_size_w = 800;
    }

    $large_size_w = apply_filters('geodir_filter_media_image_large_width', $large_size_w, $default, $params);
    return $large_size_w;
}

// wp media large height
/**
 * @param int    $default
 * @param string $params
 * @return int|mixed|void
 */
function geodir_media_image_large_height($default = 800, $params = '')
{
    $large_size_h = get_option('large_size_h');
    $large_size_h = $large_size_h > 0 ? $large_size_h : $default;
    $large_size_h = absint($large_size_h);

    if (!get_option('geodir_use_wp_media_large_size')) {
        $large_size_h = 800;
    }

    $large_size_h = apply_filters('geodir_filter_media_image_large_height', $large_size_h, $default, $params);

    return $large_size_h;
}

/**
 * @param      $type
 * @param      $name
 * @param bool $translate
 * @return mixed|null|string|void
 */
function geodir_sanitize_location_name($type, $name, $translate = true)
{
    if ($name == '') {
        return NULL;
    }

    $type = $type == 'gd_country' ? 'country' : $type;
    $type = $type == 'gd_region' ? 'region' : $type;
    $type = $type == 'gd_city' ? 'city' : $type;

    $return = $name;
    if (function_exists('get_actual_location_name')) {
        $return = get_actual_location_name($type, $name, $translate);
    } else {
        $return = preg_replace('/-(\d+)$/', '', $return);
        $return = preg_replace('/[_-]/', ' ', $return);
        $return = ucwords($return);
        $return = $translate ? __($return, GEODIRECTORY_TEXTDOMAIN) : $return;
    }

    return $return;
}


/**
 * @param $number
 */
function geodir_comments_number($number)
{

    if ($number > 1) {
        $output = str_replace('%', number_format_i18n($number), __('% Reviews', GEODIRECTORY_TEXTDOMAIN));
    } elseif ($number == 0 || $number == '') {
        $output = __('No Reviews', GEODIRECTORY_TEXTDOMAIN);
    } else { // must be one
        $output = __('1 Review', GEODIRECTORY_TEXTDOMAIN);
    }
    echo $output;
}

/**
 * @return bool
 */
function is_page_geodir_home()
{
    global $wpdb;
    $cur_url = str_replace(array("https://", "http://", "www."), array('', '', ''), geodir_curPageURL());
    $home_url = home_url('', 'http');
    $home_url = str_replace("www.", "", $home_url);
    if ((strpos($home_url, $cur_url) !== false || strpos($home_url . '/', $cur_url) !== false) && get_option('geodir_set_as_home')) {
        return true;
    } else {
        return false;
    }

}


/**
 * @param $url
 * @return string|void
 */
function geodir_wpseo_homepage_canonical($url)
{
    global $post;

    if (is_page_geodir_home()) {
        return home_url();
    }

    return $url;
}

add_filter('wpseo_canonical', 'geodir_wpseo_homepage_canonical', 10);
add_filter('aioseop_canonical_url', 'geodir_wpseo_homepage_canonical', 10);

/**
 * @param $extra
 * @return string
 */
function geodir_googlemap_script_extra_details_page($extra)
{
    global $post;
    $add_google_places_api = false;
    if (isset($post->post_content) && has_shortcode($post->post_content, 'add_listing')) {
        $add_google_places_api = true;
    }
    if (!str_replace('libraries=places', '', $extra) && (geodir_is_page('detail') || $add_google_places_api)) {
        $extra .= "&amp;libraries=places";
    }

    return $extra;
}

add_filter('geodir_googlemap_script_extra', 'geodir_googlemap_script_extra_details_page', 101, 1);


/**
 * @param string $args
 * @param string $instance
 */
function geodir_popular_post_category_output($args = '', $instance = '')
{
    // prints the widget
    global $wpdb, $plugin_prefix, $geodir_post_category_str;
    extract($args, EXTR_SKIP);

    echo $before_widget;

    $title = empty($instance['title']) ? __('Popular Categories', GEODIRECTORY_TEXTDOMAIN) : apply_filters('widget_title', __($instance['title'], GEODIRECTORY_TEXTDOMAIN));

    $category_limit = isset($instance['category_limit']) && $instance['category_limit'] > 0 ? (int)$instance['category_limit'] : 15;


    $gd_post_type = geodir_get_current_posttype();
    $taxonomy = array();
    if (!empty($gd_post_type)) {
        $taxonomy[] = $gd_post_type . "category";
    } else {
        $taxonomy = geodir_get_taxonomies($gd_post_type);
    }

    $terms = get_terms($taxonomy);
    $a_terms = array();
    $b_terms = array();

    foreach ($terms as $term) {


        if ($term->count > 0) {
            $a_terms[$term->taxonomy][] = $term;
        }

    }

    foreach ($a_terms as $b_key => $b_val) {
        $b_terms[$b_key] = geodir_sort_terms($b_val, 'count');
    }

    $tax_change_output = '';
    if (count($b_terms) > 1) {
        $tax_change_output .= "<select data-limit='$category_limit' class='geodir-cat-list-tax'  onchange='geodir_get_post_term(this);'>";
        foreach ($b_terms as $key => $val) {
            $ptype = get_post_type_object(str_replace("category", "", $key));
            $tax_change_output .= "<option value='$key' >" . __($ptype->labels->singular_name, GEODIRECTORY_TEXTDOMAIN) . " " . __('Categories', GEODIRECTORY_TEXTDOMAIN) . "</option>";
        }
        $tax_change_output .= "</select>";


    }


    if (!empty($b_terms)) {

        $terms = reset($b_terms);// get the first array
        global $cat_count;//make global so we can change via function
        $cat_count = 0;
        ?>
        <div class="geodir-category-list-in clearfix">
            <div class="geodir-cat-list clearfix">
                <?php
                echo $before_title . __($title) . $after_title;

                echo $tax_change_output;

                echo '<ul class="geodir-popular-cat-list">';

                geodir_helper_cat_list_output($terms, $category_limit);

                echo '</ul>';
                ?>
            </div>
            <?php
            $hide = '';
            if ($cat_count < $category_limit) {
                $hide = 'style="display:none;"';
            }
            echo "<div class='geodir-cat-list-more' $hide >";
            echo '<a href="javascript:void(0)" class="geodir-morecat geodir-showcat">' . __('More Categories', GEODIRECTORY_TEXTDOMAIN) . '</a>';
            echo '<a href="javascript:void(0)" class="geodir-morecat geodir-hidecat geodir-hide">' . __('Less Categories', GEODIRECTORY_TEXTDOMAIN) . '</a>';
            echo "</div>";
            /* add scripts */
            add_action('wp_footer', 'geodir_popular_category_add_scripts', 100);


            ?>
        </div>
    <?php

    }
    echo $after_widget;


}

/**
 * @param $terms
 * @param $category_limit
 */
function geodir_helper_cat_list_output($terms, $category_limit)
{
    global $geodir_post_category_str, $cat_count;
    $term_icons = geodir_get_term_icon();

    $geodir_post_category_str = array();


    foreach ($terms as $cat) {// print_r($cat);
        $post_type = str_replace("category", "", $cat->taxonomy);

        $term_icon_url = $term_icons[$cat->term_id];


        $cat_count++;


        $geodir_post_category_str[] = array('posttype' => $post_type, 'termid' => $cat->term_id);

        $class_row = $cat_count > $category_limit ? 'geodir-pcat-hide geodir-hide' : 'geodir-pcat-show';
        $total_post = $cat->count;

        echo '<li class="' . $class_row . '"><a href="' . get_term_link($cat, $cat->taxonomy) . '">';
        echo '<img alt="' . $cat->name . ' icon" class="" style="height:20px;vertical-align:middle;" src="' . $term_icon_url . '"/> ';
        echo ucwords($cat->name) . ' (<span class="geodir_term_class geodir_link_span geodir_category_class_' . $post_type . '_' . $cat->term_id . '" >' . $total_post . '</span>) ';
        echo '</a></li>';
    }


}

/**
 * @param string $args
 * @param string $instance
 */
function geodir_listing_slider_widget_output($args = '', $instance = '')
{
    // prints the widget
    extract($args, EXTR_SKIP);

    echo $before_widget;

    $title = empty($instance['title']) ? '' : apply_filters('widget_title', __($instance['title'], GEODIRECTORY_TEXTDOMAIN));

    $post_type = empty($instance['post_type']) ? 'gd_place' : apply_filters('widget_post_type', $instance['post_type']);

    $category = empty($instance['category']) ? '0' : apply_filters('widget_category', $instance['category']);

    $post_number = empty($instance['post_number']) ? '5' : apply_filters('widget_post_number', $instance['post_number']);

    $show_title = empty($instance['show_title']) ? '' : apply_filters('widget_show_title', $instance['show_title']);

    $slideshow = empty($instance['slideshow']) ? 0 : apply_filters('widget_slideshow', $instance['slideshow']);

    $animationLoop = empty($instance['animationLoop']) ? 0 : apply_filters('widget_animationLoop', $instance['animationLoop']);

    $directionNav = empty($instance['directionNav']) ? 0 : apply_filters('widget_directionNav', $instance['directionNav']);

    $slideshowSpeed = empty($instance['slideshowSpeed']) ? 5000 : apply_filters('widget_slideshowSpeed', $instance['slideshowSpeed']);

    $animationSpeed = empty($instance['animationSpeed']) ? 600 : apply_filters('widget_animationSpeed', $instance['animationSpeed']);

    $animation = empty($instance['animation']) ? 'slide' : apply_filters('widget_animation', $instance['animation']);
    $list_sort = empty($instance['list_sort']) ? 'latest' : apply_filters('widget_list_sort', $instance['list_sort']);
    $show_featured_only = !empty($instance['show_featured_only']) ? 1 : NULL;
    ?>
    <script type="text/javascript">
        jQuery(window).load(function () {
            jQuery('#geodir_widget_carousel').flexslider({
                animation: "slide",
                selector: ".geodir-slides > li",
                namespace: "geodir-",
                controlNav: false,
                directionNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 75,
                itemMargin: 5,
                asNavFor: '#geodir_widget_slider',
                rtl: <?php echo ( is_rtl() ? 'true' : 'false' ); /* fix rtl issue */ ?>
            });

            jQuery('#geodir_widget_slider').flexslider({
                animation: "<?php echo $animation;?>",
                selector: ".geodir-slides > li",
                namespace: "geodir-",
                controlNav: true,
                animationLoop: <?php echo $animationLoop;?>,
                slideshow: <?php echo $slideshow;?>,
                slideshowSpeed: <?php echo $slideshowSpeed;?>,
                animationSpeed: <?php echo $animationSpeed;?>,
                directionNav: <?php echo $directionNav;?>,
                sync: "#geodir_widget_carousel",
                start: function (slider) {
                    jQuery('.geodir-listing-flex-loader').hide();
                    jQuery('#geodir_widget_slider').css({'visibility': 'visible'});
                    jQuery('#geodir_widget_carousel').css({'visibility': 'visible'});
                },
                rtl: <?php echo ( is_rtl() ? 'true' : 'false' ); /* fix rtl issue */ ?>
            });
        });
    </script>
    <?php
    $query_args = array(
        'posts_per_page' => $post_number,
        'is_geodir_loop' => true,
        'post_type' => $post_type,
        'order_by' => $list_sort
    );
    if ($show_featured_only) {
        $query_args['show_featured_only'] = 1;
    }

    if ($category != 0 || $category != '') {
        $category_taxonomy = geodir_get_taxonomies($post_type);
        $tax_query = array(
            'taxonomy' => $category_taxonomy[0],
            'field' => 'id',
            'terms' => $category
        );

        $query_args['tax_query'] = array($tax_query);
    }

    // we want listings with featured image only
    $query_args['featured_image_only'] = 1;

    if ($post_type == 'gd_event') {
        $query_args['gedir_event_listing_filter'] = 'upcoming';
    }// show only upcomming events

    $widget_listings = geodir_get_widget_listings($query_args);
    if (!empty($widget_listings) || $with_no_results) {
        if ($title) {
            echo $before_title . $title . $after_title;
        }

        global $post;

        $current_post = $post;// keep current post info

        $widget_main_slides = '';
        $nav_slides = '';
        $widget_slides = 0;

        foreach ($widget_listings as $widget_listing) {
            global $gd_widget_listing_type;
            $post = $widget_listing;
            $widget_image = geodir_get_featured_image($post->ID, 'thumbnail', get_option('geodir_listing_no_img'));

            if (!empty($widget_image)) {
                if ($widget_image->height >= 200) {
                    $widget_spacer_height = 0;
                } else {
                    $widget_spacer_height = ((200 - $widget_image->height) / 2);
                }

                $widget_main_slides .= '<li class="geodir-listing-slider-widget"><img class="geodir-listing-slider-spacer" src="' . geodir_plugin_url() . "/geodirectory-assets/images/spacer.gif" . '" alt="' . $widget_image->title . '" title="' . $widget_image->title . '" style="max-height:' . $widget_spacer_height . 'px !important;margin:0 auto;" width="100" />';

                $title = '';
                if ($show_title) {
                    $title = '<div class="geodir-slider-title"><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></div>';
                }

                $widget_main_slides .= $title . '<img src="' . $widget_image->src . '" alt="' . $widget_image->title . '" title="' . $widget_image->title . '" style="max-height:200px;margin:0 auto;" /></li>';
                $nav_slides .= '<li><img src="' . $widget_image->src . '" alt="' . $widget_image->title . '" title="' . $widget_image->title . '" style="max-height:48px;margin:0 auto;" /></li>';
                $widget_slides++;
            }
        }
        ?>
        <div class="flex-container" style="min-height:200px;">
            <div class="geodir-listing-flex-loader"><i class="fa fa-refresh fa-spin"></i></div>
            <div id="geodir_widget_slider" class="geodir_flexslider">
                <ul class="geodir-slides clearfix"><?php echo $widget_main_slides; ?></ul>
            </div>
            <?php if ($widget_slides > 1) { ?>
                <div id="geodir_widget_carousel" class="geodir_flexslider">
                    <ul class="geodir-slides clearfix"><?php echo $nav_slides; ?></ul>
                </div>
            <?php } ?>
        </div>
        <?php
        $GLOBALS['post'] = $current_post;
        setup_postdata($current_post);
    }
    echo $after_widget;
}


/**
 * @param string $args
 * @param string $instance
 */
function geodir_loginwidget_output($args = '', $instance = '')
{
    //print_r($args);
    //print_r($instance);
    // prints the widget
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? __('My Dashboard', GEODIRECTORY_TEXTDOMAIN) : apply_filters('widget_title', __($instance['title'], GEODIRECTORY_TEXTDOMAIN));

    echo $before_widget;
    echo $before_title . $title . $after_title;

    if (is_user_logged_in()) {
        global $current_user;

        $login_url = geodir_getlink(home_url(), array('geodir_signup' => 'true'), false);
        $add_listurl = get_permalink(get_option('geodir_add_listing_page'));
        $add_listurl = geodir_getlink($add_listurl, array('listing_type' => 'gd_place'));
        $author_link = get_author_posts_url($current_user->data->ID);
        $author_link = geodir_getlink($author_link, array('geodir_dashbord' => 'true'), false);

        echo '<ul class="geodir-loginbox-list">';
        ob_start();
        ?>
        <li><a class="signin"
               href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', GEODIRECTORY_TEXTDOMAIN); ?></a></li>
        <?php
        $post_types = geodir_get_posttypes('object');
        $show_add_listing_post_types_main_nav = get_option('geodir_add_listing_link_user_dashboard');
        $geodir_allow_posttype_frontend = get_option('geodir_allow_posttype_frontend');

        if (!empty($show_add_listing_post_types_main_nav)) {
            $addlisting_links = '';
            foreach ($post_types as $key => $postobj) {

                if (in_array($key, $show_add_listing_post_types_main_nav)) {

                    if ($add_link = geodir_get_addlisting_link($key)) {

                        $name = $postobj->labels->name;

                        $selected = '';
                        if (geodir_get_current_posttype() == $key && geodir_is_page('add-listing'))
                            $selected = 'selected="selected"';

                        // hook for add listing link
                        $add_link = apply_filters('geodir_dashboard_link_add_listing', $add_link, $key, $current_user->ID);

                        $addlisting_links .= '<option ' . $selected . ' value="' . $add_link . '">' . __(ucfirst($name), GEODIRECTORY_TEXTDOMAIN) . '</option>';

                    }
                }

            }

            if ($addlisting_links != '') { ?>

                <li><select id="geodir_add_listing" class="chosen_select" onchange="window.location.href=this.value"
                            option-autoredirect="1" name="geodir_add_listing" option-ajaxchosen="false"
                            data-placeholder="<?php echo esc_attr(__('Add Listing', GEODIRECTORY_TEXTDOMAIN)); ?>">
                        <option value=""></option>
                        <?php echo $addlisting_links; ?>
                    </select></li> <?php

            }

        }
        // My Favourites in Dashboard
        $show_favorite_link_user_dashboard = get_option('geodir_favorite_link_user_dashboard');
        $user_favourite = geodir_user_favourite_listing_count();

        if (!empty($show_favorite_link_user_dashboard) && !empty($user_favourite)) {
            $favourite_links = '';

            foreach ($post_types as $key => $postobj) {
                if (in_array($key, $show_favorite_link_user_dashboard) && array_key_exists($key, $user_favourite)) {
                    $name = $postobj->labels->name;
                    $post_type_link = geodir_getlink($author_link, array('stype' => $key, 'list' => 'favourite'), false);

                    $selected = '';

                    if (isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite' && isset($_REQUEST['stype']) && $_REQUEST['stype'] == $key && isset($_REQUEST['geodir_dashbord'])) {
                        $selected = 'selected="selected"';
                    }
                    // hook for favorite listing link
                    $post_type_link = apply_filters('geodir_dashboard_link_favorite_listing', $post_type_link, $key, $current_user->ID);

                    $favourite_links .= '<option ' . $selected . ' value="' . $post_type_link . '">' . __(ucfirst($name), GEODIRECTORY_TEXTDOMAIN) . '</option>';
                }
            }

            if ($favourite_links != '') {
                ?>
                <li>
                    <select id="geodir_my_favourites" class="chosen_select" onchange="window.location.href=this.value"
                            option-autoredirect="1" name="geodir_my_favourites" option-ajaxchosen="false"
                            data-placeholder="<?php echo esc_attr(__('My Favorites', GEODIRECTORY_TEXTDOMAIN)); ?>">
                        <option value=""></option>
                        <?php echo $favourite_links; ?>
                    </select>
                </li>
            <?php
            }
        }


        $show_listing_link_user_dashboard = get_option('geodir_listing_link_user_dashboard');
        $user_listing = geodir_user_post_listing_count();

        if (!empty($show_listing_link_user_dashboard) && !empty($user_listing)) {
            $listing_links = '';

            foreach ($post_types as $key => $postobj) {
                if (in_array($key, $show_listing_link_user_dashboard) && array_key_exists($key, $user_listing)) {
                    $name = $postobj->labels->name;
                    $listing_link = geodir_getlink($author_link, array('stype' => $key), false);

                    $selected = '';
                    if (!isset($_REQUEST['list']) && isset($_REQUEST['geodir_dashbord']) && isset($_REQUEST['stype']) && $_REQUEST['stype'] == $key) {
                        $selected = 'selected="selected"';
                    }

                    // hook for my listing link
                    $listing_link = apply_filters('geodir_dashboard_link_my_listing', $listing_link, $key, $current_user->ID);

                    $listing_links .= '<option ' . $selected . ' value="' . $listing_link . '">' . __(ucfirst($name), GEODIRECTORY_TEXTDOMAIN) . '</option>';
                }
            }

            if ($listing_links != '') {
                ?>
                <li>
                    <select id="geodir_my_listings" class="chosen_select" onchange="window.location.href=this.value"
                            option-autoredirect="1" name="geodir_my_listings" option-ajaxchosen="false"
                            data-placeholder="<?php echo esc_attr(__('My Listings', GEODIRECTORY_TEXTDOMAIN)); ?>">
                        <option value=""></option>
                        <?php echo $listing_links; ?>
                    </select>
                </li>
            <?php
            }
        }

        $dashboard_link = ob_get_clean();

        echo apply_filters('geodir_dashboard_links', $dashboard_link);
        echo '</ul>';
    } else {
        ?>

        <form name="loginform" class="loginform1"
              action="<?php echo apply_filters('geodir_signup_reg_submit_link', home_url() . '/index.php?geodir_signup=true'); ?>"
              method="post">
            <div class="geodir_form_row"><input placeholder="<?php _e('Email', GEODIRECTORY_TEXTDOMAIN); ?>" name="log"
                                                type="text" class="textfield user_login1"/> <span
                    class="user_loginInfo"></span></div>
            <div class="geodir_form_row"><input placeholder="<?php _e('Password', GEODIRECTORY_TEXTDOMAIN); ?>"
                                                name="pwd" type="password"
                                                class="textfield user_pass1 input-text"/><span
                    class="user_passInfo"></span></div>

            <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars(geodir_curPageURL()); ?>"/>
            <input type="hidden" name="testcookie" value="1"/>

            <div class="geodir_form_row clearfix"><input type="submit" name="submit"
                                                         value="<?php echo SIGN_IN_BUTTON; ?>" class="b_signin"/>

                <p class="geodir-new-forgot-link">
                    <a href="<?php echo apply_filters('geodir_signup_reg_form_link', home_url() . '/?geodir_signup=true&amp;page1=sign_up'); ?>"
                       class="goedir-newuser-link"><?php echo NEW_USER_TEXT; ?></a>

                    <a href="<?php echo apply_filters('geodir_signup_forgot_form_link', home_url() . '/?geodir_signup=true&amp;page1=sign_in'); ?>"
                       class="goedir-forgot-link"><?php echo FORGOT_PW_TEXT; ?></a></p></div>
        </form>
    <?php }

    echo $after_widget;
}


/**
 * @param string $args
 * @param string $instance
 */
function geodir_popular_postview_output($args = '', $instance = '')
{
    //print_r($args);
    //print_r($instance);
    // prints the widget
    extract($args, EXTR_SKIP);

    echo $before_widget;

    $title = empty($instance['title']) ? ucwords($instance['category_title']) : apply_filters('widget_title', __($instance['title'], GEODIRECTORY_TEXTDOMAIN));
    $post_type = empty($instance['post_type']) ? 'gd_place' : apply_filters('widget_post_type', $instance['post_type']);
    $category = empty($instance['category']) ? '0' : apply_filters('widget_category', $instance['category']);
    $post_number = empty($instance['post_number']) ? '5' : apply_filters('widget_post_number', $instance['post_number']);
    $layout = empty($instance['layout']) ? 'gridview_onehalf' : apply_filters('widget_layout', $instance['layout']);
    $add_location_filter = empty($instance['add_location_filter']) ? '0' : apply_filters('widget_layout', $instance['add_location_filter']);
    $listing_width = empty($instance['listing_width']) ? '' : apply_filters('widget_listing_width', $instance['listing_width']);
    $list_sort = empty($instance['list_sort']) ? 'latest' : apply_filters('widget_list_sort', $instance['list_sort']);
    $use_viewing_post_type = !empty($instance['use_viewing_post_type']) ? true : false;

    // set post type to current viewing post type
    if ($use_viewing_post_type) {
        $current_post_type = geodir_get_current_posttype();
        if ($current_post_type != '' && $current_post_type != $post_type) {
            $post_type = $current_post_type;
            $category = array(); // old post type category will not work for current changed post type
        }
    }
    // replace widget title dynamically
    $posttype_plural_label = __(get_post_type_plural_label($post_type), GEODIRECTORY_TEXTDOMAIN);
    $posttype_singular_label = __(get_post_type_singular_label($post_type), GEODIRECTORY_TEXTDOMAIN);

    $title = str_replace("%posttype_plural_label%", $posttype_plural_label, $title);
    $title = str_replace("%posttype_singular_label%", $posttype_singular_label, $title);

    if (isset($instance['character_count'])) {
        $character_count = apply_filters('widget_list_character_count', $instance['character_count']);
    } else {
        $character_count = '';
    }

    if (empty($title) || $title == 'All') {
        $title .= ' ' . __(get_post_type_plural_label($post_type), GEODIRECTORY_TEXTDOMAIN);
    }

    $location_url = array();
    $city = get_query_var('gd_city');
    if (!empty($city)) {
        if (get_option('geodir_show_location_url') == 'all') {
            $country = get_query_var('gd_country');
            $region = get_query_var('gd_region');

            if (!empty($country)) {
                $location_url[] = $country;
            }

            if (!empty($region)) {
                $location_url[] = $region;
            }
        }

        $location_url[] = $city;
    }

    $location_url = implode("/", $location_url);

    if (get_option('permalink_structure')) {
        $viewall_url = get_post_type_archive_link($post_type);
    } else {
        $viewall_url = get_post_type_archive_link($post_type);
    }

    if (!empty($category) && $category[0] != '0') {
        global $geodir_add_location_url;

        $geodir_add_location_url = '0';

        if ($add_location_filter != '0') {
            $geodir_add_location_url = '1';
        }

        $viewall_url = get_term_link((int)$category[0], $post_type . 'category');

        $geodir_add_location_url = NULL;
    }
    $query_args = array(
        'posts_per_page' => $post_number,
        'is_geodir_loop' => true,
        'gd_location' => $add_location_filter ? true : false,
        'post_type' => $post_type,
        'order_by' => $list_sort
    );

    if ($character_count) {
        $query_args['excerpt_length'] = $character_count;
    }

    if (!empty($instance['show_featured_only'])) {
        $query_args['show_featured_only'] = 1;
    }

    if (!empty($instance['show_special_only'])) {
        $query_args['show_special_only'] = 1;
    }

    if (!empty($instance['with_pics_only'])) {
        $query_args['with_pics_only'] = 0;
        $query_args['featured_image_only'] = 1;
    }

    if (!empty($instance['with_videos_only'])) {
        $query_args['with_videos_only'] = 1;
    }
    $with_no_results = !empty($instance['without_no_results']) ? false : true;

    if (!empty($category) && $category[0] != '0') {
        $category_taxonomy = geodir_get_taxonomies($post_type);

        ######### WPML #########
        if (function_exists('icl_object_id')) {
            $category = gd_lang_object_ids($category, $category_taxonomy[0]);
        }
        ######### WPML #########

        $tax_query = array(
            'taxonomy' => $category_taxonomy[0],
            'field' => 'id',
            'terms' => $category
        );

        $query_args['tax_query'] = array($tax_query);
    }

    global $gridview_columns_widget, $geodir_is_widget_listing;

    $widget_listings = geodir_get_widget_listings($query_args);

    if (!empty($widget_listings) || $with_no_results) {
        ?>
        <div class="geodir_locations geodir_location_listing">
            <?php do_action('geodir_before_view_all_link_in_widget'); ?>
            <div class="geodir_list_heading clearfix">
                <?php echo $before_title . $title . $after_title; ?>
                <a href="<?php echo $viewall_url; ?>"
                   class="geodir-viewall"><?php _e('View all', GEODIRECTORY_TEXTDOMAIN); ?></a>
            </div>
            <?php do_action('geodir_after_view_all_link_in_widget'); ?>
            <?php
            if (strstr($layout, 'gridview')) {
                $listing_view_exp = explode('_', $layout);
                $gridview_columns_widget = $layout;
                $layout = $listing_view_exp[0];
            } else {
                $gridview_columns_widget = '';
            }

            $template = apply_filters("geodir_template_part-widget-listing-listview", geodir_locate_template('widget-listing-listview'));
            if (!isset($character_count)) {
                $character_count = $character_count == '' ? 50 : apply_filters('widget_character_count', $character_count);
            }

            global $post, $map_jason, $map_canvas_arr;

            $current_post = $post;
            $current_map_jason = $map_jason;
            $current_map_canvas_arr = $map_canvas_arr;
            $geodir_is_widget_listing = true;

            include($template);

            $geodir_is_widget_listing = false;

            $GLOBALS['post'] = $current_post;
            setup_postdata($current_post);
            $map_jason = $current_map_jason;
            $map_canvas_arr = $current_map_canvas_arr;
            ?>
        </div>
    <?php
    }
    echo $after_widget;

}


/*-----------------------------------------------------------------------------------*/
/*  Review count functions
/*-----------------------------------------------------------------------------------*/
/**
 * @param $term_id
 * @param $taxonomy
 * @param $post_type
 * @return mixed
 */
function geodir_count_reviews_by_term_id($term_id, $taxonomy, $post_type)
{

    global $wpdb, $plugin_prefix;
    $detail_table = $plugin_prefix . $post_type . '_detail';

    $sql = "SELECT COALESCE(SUM(rating_count),0) FROM " . $detail_table . " WHERE post_status = 'publish' AND rating_count > 0 AND FIND_IN_SET(" . $term_id . ", " . $taxonomy . ")";
    $count = $wpdb->get_var($sql);

    return $count;
}

/**
 * @param bool $force_update
 * @return array|mixed|void
 */
function geodir_count_reviews_by_terms($force_update = false)
{

    $option_data = apply_filters('geodir_count_reviews_by_terms_before', '', $force_update);
    if (!empty($option_data)) {
        return $option_data;
    }

    $option_data = get_option('geodir_global_review_count');

    if (!$option_data OR $force_update) {
        $post_types = geodir_get_posttypes();
        $term_array = array();
        foreach ($post_types as $post_type) {

            $taxonomy = geodir_get_taxonomies($post_type);
            $taxonomy = $taxonomy[0];

            $args = array(
                'hide_empty' => false
            );

            $terms = get_terms($taxonomy, $args);

            foreach ($terms as $term) {
                $count = geodir_count_reviews_by_term_id($term->term_id, $taxonomy, $post_type);
                $children = get_term_children($term->term_id, $taxonomy);
                /*if ( is_array( $children ) ) {
					foreach ( $children as $child_id ) {
						$child_count = geodir_count_reviews_by_term_id($child_id, $taxonomy, $post_type);
						$count = $count + $child_count;
					}
				}*/
                $term_array[$term->term_id] = $count;
            }
        }

        update_option('geodir_global_review_count', $term_array);
        //clear cache
        wp_cache_delete('geodir_global_review_count');
        return $term_array;
    } else {
        return $option_data;
    }
}

/**
 * @return bool
 */
function geodir_term_review_count_force_update()
{
    geodir_count_reviews_by_terms(true);
    return true;
}


/*-----------------------------------------------------------------------------------*/
/*  Term count functions
/*-----------------------------------------------------------------------------------*/
//function geodir_count_posts_by_term($data, $term) {
/**
 * @param $data
 * @param $term
 * @return int
 */
function geodir_count_posts_by_term($data, $term)
{

    if ($data) {
        if (isset($data[$term->term_id])) {
            return $data[$term->term_id];
        } else {
            return 0;
        }
    } else {
        return $term->count;
    }
}

/**
 * @param $terms
 * @return mixed
 */
function geodir_sort_terms_by_count($terms)
{
    usort($terms, "geodir_sort_by_count_obj");
    return $terms;
}

/**
 * @param $terms
 * @return mixed
 */
function geodir_sort_terms_by_review_count($terms)
{
    usort($terms, "geodir_sort_by_review_count_obj");
    return $terms;
}

/**
 * @param        $terms
 * @param string $sort
 * @return mixed
 */
function geodir_sort_terms($terms, $sort = 'count')
{
    if ($sort == 'count') {
        return geodir_sort_terms_by_count($terms);
    }
    if ($sort == 'review_count') {
        return geodir_sort_terms_by_review_count($terms);
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Utils
/*-----------------------------------------------------------------------------------*/
/**
 * @param $a
 * @param $b
 * @return bool
 */
function geodir_sort_by_count($a, $b)
{
    return $a['count'] < $b['count'];
}

/**
 * @param $a
 * @param $b
 * @return bool
 */
function geodir_sort_by_count_obj($a, $b)
{
    return $a->count < $b->count;
}

/**
 * @param $a
 * @param $b
 * @return bool
 */
function geodir_sort_by_review_count_obj($a, $b)
{
    return $a->review_count < $b->review_count;
}


