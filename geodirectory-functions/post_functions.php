<?php
/**
 * Post Listing functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */


/**
 * Set post category structure based on given parameters.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_id The post ID.
 * @param string $taxonomy Name of the taxonomy e.g place_category.
 * @param string|int $default_cat Optional. The default category ID.
 * @param array|string $category_str Optional. Blank string if no categories. Else array of categories to set.
 */
function geodir_set_postcat_structure($post_id, $taxonomy, $default_cat = '', $category_str = '')
{

    $post_cat_ids = geodir_get_post_meta($post_id, $taxonomy);
    if (!empty($post_cat_ids))
        $post_cat_array = explode(",", trim($post_cat_ids, ","));

    if (!isset($default_cat) || empty($default_cat)) {
        $default_cat = isset($post_cat_array[0]) ? $post_cat_array[0] : '';
    }else{
        if(!is_int($default_cat)){
            $category = get_term_by('name', $default_cat, $taxonomy);
            if(isset($category->term_id)){
                $default_cat =  $category->term_id;
            }
        }

    }


    geodir_save_post_meta($post_id, 'default_category', $default_cat);

    if (isset($category_str) && empty($category_str)) {

        $post_cat_str = '';
        $post_categories = array();
        if (isset($post_cat_array) && is_array($post_cat_array) && !empty($post_cat_array)) {
            $post_cat_str = implode(",y:#", $post_cat_array);
            $post_cat_str .= ",y:";
            $post_cat_str = substr_replace($post_cat_str, ',y,d:', strpos($post_cat_str, ',y:'), strlen(',y:'));
        }
        $post_categories[$taxonomy] = $post_cat_str;
        $category_str = $post_categories;
    }

    $change_cat_str = $category_str[$taxonomy];

    $default_pos = strpos($change_cat_str, 'd:');

    if ($default_pos === false) {

        $change_cat_str = str_replace($default_cat . ',y:', $default_cat . ',y,d:', $change_cat_str);

    }

    $category_str[$taxonomy] = $change_cat_str;

    update_post_meta($post_id, 'post_categories', $category_str);

}


if (!function_exists('geodir_save_listing')) {
    /**
     * Saves listing in the database using given information.
     *
     * @since 1.0.0
     * @since 1.5.4 New parameter $wp_error added.
     * @since 1.6.18 Admin use only date field should not lost value if saved by user - FIXED
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global object $post The current post object.
     * @global object $current_user Current user object.
     * @global object $gd_session GeoDirectory Session object.
     * @param array $request_info {
     *    Array of request info arguments.
     *
     *    @type string $action                                  Ajax action name.
     *    @type string $geodir_ajax                             Ajax type.
     *    @type string $ajax_action                             Ajax action.
     *    @type string $listing_type                            Listing type.
     *    @type string $pid                                     Default Post ID.
     *    @type string $preview                                 Todo Desc needed.
     *    @type string $add_listing_page_id                     Add listing page ID.
     *    @type string $post_title                              Listing title.
     *    @type string $post_desc                               Listing Description.
     *    @type string $post_tags                               Listing tags.
     *    @type array  $cat_limit                               Category limit.
     *    @type array  $post_category                           Category IDs.
     *    @type array  $post_category_str                       Category string.
     *    @type string $post_default_category                   Default category ID.
     *    @type string $post_address                            Listing address.
     *    @type string $geodir_location_add_listing_country_val Add listing country value.
     *    @type string $post_country                            Listing country.
     *    @type string $geodir_location_add_listing_region_val  Add listing region value.
     *    @type string $post_region                             Listing region.
     *    @type string $geodir_location_add_listing_city_val    Add listing city value.
     *    @type string $post_city                               Listing city.
     *    @type string $post_zip                                Listing zip.
     *    @type string $post_latitude                           Listing latitude.
     *    @type string $post_longitude                          Listing longitude.
     *    @type string $post_mapview                            Listing mapview. Default "ROADMAP".
     *    @type string $post_mapzoom                            Listing mapzoom Default "9".
     *    @type string $geodir_timing                           Business timing info.
     *    @type string $geodir_contact                          Contact number.
     *    @type string $geodir_email                            Business contact email.
     *    @type string $geodir_website                          Business website.
     *    @type string $geodir_twitter                          Twitter link.
     *    @type string $geodir_facebook                         Facebook link.
     *    @type string $geodir_video                            Video link.
     *    @type string $geodir_special_offers                   Special offers.
     *    @type string $post_images                             Post image urls.
     *    @type string $post_imagesimage_limit                  Post images limit.
     *    @type string $post_imagestotImg                       Todo Desc needed.
     *    @type string $geodir_accept_term_condition            Has accepted terms and conditions?.
     *    @type string $geodir_spamblocker                      Todo Desc needed.
     *    @type string $geodir_filled_by_spam_bot               Todo Desc needed.
     *
     * }
     * @param bool $dummy Optional. Is this a dummy listing? Default false.
     * @param bool $wp_error Optional. Allow return of WP_Error on failure. Default false.
     * @return int|string|WP_Error Created post id or WP_Error on failure.
     */
    function geodir_save_listing($request_info = array(), $dummy = false, $wp_error = false)
    {
        global $wpdb, $current_user, $gd_session;

        $last_post_id = '';

        if ($gd_session->get('listing') && !$dummy) {
            $request_info = array();
            $request_session = $gd_session->get('listing');
            $request_info = array_merge($_REQUEST, $request_session);
        } else if (!$gd_session->get('listing') && !$dummy) {
            global $post;
            
            $gd_post = $post;
            if (!empty($gd_post) && is_array($gd_post)) {
                $gd_post = (object)$post;
                
                // Fix WPML duplicate.
                if (geodir_is_wpml() && !empty($request_info['action']) && $request_info['action'] == 'editpost' && !empty($request_info['icl_trid']) && !isset($post['post_date'])) {
                    return false;
                }
            }
            
            $request_info['pid'] = !empty($gd_post->ID) ? $gd_post->ID : (!empty($request_info['post_id']) ? $request_info['post_id'] : NULL);
            $request_info['post_title'] = $request_info['post_title'];
            $request_info['listing_type'] = !empty($gd_post->post_type) ? $gd_post->post_type : (!empty($request_info['post_type']) ? $request_info['post_type'] : get_post_type($request_info['pid']));
            $request_info['post_desc'] = $request_info['content'];
        } else if (!$dummy) {
            return false;
        }

        /**
         * Filter the request_info array.
         *
         * You can use this filter to modify request_info array.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param array $request_info See {@see geodir_save_listing()} for accepted args.
         */
        $request_info = apply_filters('geodir_action_get_request_info', $request_info);

        // Check if we need to save post location as new location
        $location_result = geodir_get_default_location();

        if ($location_result->location_id > 0) {
            if (isset($request_info['post_city']) && isset($request_info['post_region'])) {
                $request_info['post_location'] = array(
                    'city' => $request_info['post_city'],
                    'region' => isset($request_info['post_region']) ? $request_info['post_region'] : '',
                    'country' => isset($request_info['post_country']) ? $request_info['post_country'] : '',
                    'geo_lat' => isset($request_info['post_latitude']) ? $request_info['post_latitude'] : '',
                    'geo_lng' => isset($request_info['post_longitude']) ? $request_info['post_longitude'] : ''
                );

                $post_location_info = $request_info['post_location'];

                if ($location_id = geodir_add_new_location($post_location_info)) {
                    $post_location_id = $location_id;
                }
            } else {
                $post_location_id = $location_result->location_id;
            }
        } else {
            $post_location_id = $location_result->location_id;
        }

        if ($dummy) {
            $post_status = 'publish';
        } else {
            $post_status = geodir_new_post_default_status();
        }

        if (isset($request_info['pid']) && $request_info['pid'] != '') {
            $post_status = get_post_status($request_info['pid']);
        }

        /* fix change of slug on every title edit */
        if (!isset($request_info['post_name'])) {
            $request_info['post_name'] = $request_info['post_title'];

            if (!empty($request_info['pid'])) {
                $post_info = get_post($request_info['pid']);

                if (!empty($post_info) && isset($post_info->post_name)) {
                    $request_info['post_name'] = $post_info->post_name;
                }
            }
        }

        $post = array(
            'post_content' => $request_info['post_desc'],
            'post_status' => $post_status,
            'post_title' => $request_info['post_title'],
            'post_name' => $request_info['post_name'],
            'post_type' => $request_info['listing_type']
        );

        /**
         * Called before a listing is saved to the database.
         *
         * @since 1.0.0
         * @param object $post The post object.
         */
        do_action_ref_array('geodir_before_save_listing', $post);

        $send_post_submit_mail = false;

        // unhook this function so it doesn't loop infinitely
        remove_action('save_post', 'geodir_post_information_save',10,2);

        if (isset($request_info['pid']) && $request_info['pid'] != '') {
            $post['ID'] = $request_info['pid'];

            $last_post_id = wp_update_post($post, $wp_error);
        } else {
            $last_post_id = wp_insert_post($post, $wp_error);

            if (!$dummy && $last_post_id) {
                $send_post_submit_mail = true; // we move post_submit email from here so the rest of the variables are added to the db first(was breaking permalink in email)
                //geodir_sendEmail('','',$current_user->user_email,$current_user->display_name,'','',$request_info,'post_submit',$last_post_id,$current_user->ID);
            }
        }

        if ($wp_error && is_wp_error($last_post_id)) {
            return $last_post_id; // Return WP_Error on save failure.
        }

        if (!$last_post_id) {
            return false; // Save failure.
        }

        // re-hook this function
        add_action('save_post', 'geodir_post_information_save',10,2);

        $post_tags = '';
        if (!isset($request_info['post_tags'])) {

            $post_type = $request_info['listing_type'];
            $post_tags = implode(",", wp_get_object_terms($last_post_id, $post_type . '_tags', array('fields' => 'names')));

        }

        $gd_post_info = array(
            "post_title" => $request_info['post_title'],
            "post_tags" => isset($request_info['post_tags']) ? $request_info['post_tags'] : $post_tags,
            "post_status" => $post_status,
            "post_location_id" => $post_location_id,
            "claimed" => isset($request_info['claimed']) ? $request_info['claimed'] : '',
            "businesses" => isset($request_info['a_businesses']) ? $request_info['a_businesses'] : '',
            "submit_time" => time(),
            "submit_ip" => $_SERVER['REMOTE_ADDR'],
        );

        $payment_info = array();
        $package_info = array();

        $package_info = (array)geodir_post_package_info($package_info, $post);

        $post_package_id = geodir_get_post_meta($last_post_id, 'package_id');

        if (!empty($package_info) && !$post_package_id) {
            if (isset($package_info['days']) && $package_info['days'] != 0) {
                $payment_info['expire_date'] = date('Y-m-d', strtotime("+" . $package_info['days'] . " days"));
            } else {
                $payment_info['expire_date'] = 'Never';
            }

            $payment_info['package_id'] = $package_info['pid'];
            $payment_info['alive_days'] = $package_info['days'];
            $payment_info['is_featured'] = $package_info['is_featured'];

            $gd_post_info = array_merge($gd_post_info, $payment_info);
        }

        $custom_metaboxes = geodir_post_custom_fields('', 'all', $request_info['listing_type']);

        foreach ($custom_metaboxes as $key => $val):

            $name = $val['name'];
            $type = $val['type'];
            $extrafields = $val['extra_fields'];

            if (trim($type) == 'address') {
                $prefix = $name . '_';
                $address = $prefix . 'address';

                if (isset($request_info[$address]) && $request_info[$address] != '') {
                    $gd_post_info[$address] = wp_slash($request_info[$address]);
                }

                if ($extrafields != '') {
                    $extrafields = unserialize($extrafields);


                    if (!isset($request_info[$prefix . 'city']) || $request_info[$prefix . 'city'] == '') {

                        $location_result = geodir_get_default_location();

                        $gd_post_info[$prefix . 'city'] = $location_result->city;
                        $gd_post_info[$prefix . 'region'] = $location_result->region;
                        $gd_post_info[$prefix . 'country'] = $location_result->country;

                        $gd_post_info['post_locations'] = '[' . $location_result->city_slug . '],[' . $location_result->region_slug . '],[' . $location_result->country_slug . ']'; // set all overall post location

                    } else {

                        $gd_post_info[$prefix . 'city'] = $request_info[$prefix . 'city'];
                        $gd_post_info[$prefix . 'region'] = $request_info[$prefix . 'region'];
                        $gd_post_info[$prefix . 'country'] = $request_info[$prefix . 'country'];

                        //----------set post locations when import dummy data-------
                        $location_result = geodir_get_default_location();

                        $gd_post_info['post_locations'] = '[' . $location_result->city_slug . '],[' . $location_result->region_slug . '],[' . $location_result->country_slug . ']'; // set all overall post location
                        //-----------------------------------------------------------------

                    }


                    if (isset($extrafields['show_zip']) && $extrafields['show_zip'] && isset($request_info[$prefix . 'zip'])) {
                        $gd_post_info[$prefix . 'zip'] = $request_info[$prefix . 'zip'];
                    }


                    if (isset($extrafields['show_map']) && $extrafields['show_map']) {

                        if (isset($request_info[$prefix . 'latitude']) && $request_info[$prefix . 'latitude'] != '') {
                            $gd_post_info[$prefix . 'latitude'] = $request_info[$prefix . 'latitude'];
                        }

                        if (isset($request_info[$prefix . 'longitude']) && $request_info[$prefix . 'longitude'] != '') {
                            $gd_post_info[$prefix . 'longitude'] = $request_info[$prefix . 'longitude'];
                        }

                        if (isset($request_info[$prefix . 'mapview']) && $request_info[$prefix . 'mapview'] != '') {
                            $gd_post_info[$prefix . 'mapview'] = $request_info[$prefix . 'mapview'];
                        }

                        if (isset($request_info[$prefix . 'mapzoom']) && $request_info[$prefix . 'mapzoom'] != '') {
                            $gd_post_info[$prefix . 'mapzoom'] = $request_info[$prefix . 'mapzoom'];
                        }

                    }

                    // show lat lng
                    if (isset($extrafields['show_latlng']) && $extrafields['show_latlng'] && isset($request_info[$prefix . 'latlng'])) {
                        $gd_post_info[$prefix . 'latlng'] = $request_info[$prefix . 'latlng'];
                    }
                }

            } elseif (trim($type) == 'file') {
                if (isset($request_info[$name])) {
                    $request_files = array();
                    if ($request_info[$name] != '')
                        $request_files = explode(",", $request_info[$name]);

                    $extrafields = $extrafields != '' ? maybe_unserialize($extrafields) : NULL;
                    geodir_save_post_file_fields($last_post_id, $name, $request_files, $extrafields);
                }
            } elseif (trim($type) == 'datepicker') {
                if (isset($request_info[$name])) {
                    $datetime = '';
                    
                    if (!empty($request_info[$name])) {
                        $date_format = geodir_default_date_format();
                        if (isset($val['extra_fields']) && $val['extra_fields'] != '') {
                            $extra_fields = unserialize($val['extra_fields']);
                            $date_format = isset($extra_fields['date_format']) && $extra_fields['date_format'] != '' ? $extra_fields['date_format'] : $date_format;
                        }

                        // check if we need to change the format or not
                        $date_format_len = strlen(str_replace(' ', '', $date_format));
                        if($date_format_len>5){// if greater then 5 then it's the old style format.

                            $search = array('dd','d','DD','mm','m','MM','yy'); //jQuery UI datepicker format
                            $replace = array('d','j','l','m','n','F','Y');//PHP date format

                            $date_format = str_replace($search, $replace, $date_format);

                            $post_htmlvar_value = $date_format == 'd/m/Y' ? str_replace('/', '-', $request_info[$name]) : $request_info[$name];

                        }else{
                            $post_htmlvar_value = $request_info[$name];
                        }

                        $post_htmlvar_value = geodir_date($post_htmlvar_value, 'Y-m-d', $date_format); // save as sql format Y-m-d
                        $datetime = geodir_maybe_untranslate_date($post_htmlvar_value); // maybe untranslate date string if it was translated
                    }

                    $gd_post_info[$name] = $datetime;
                }
            } else if ($type == 'multiselect') {
                if (isset($request_info[$name])) {
                    $gd_post_info[$name] = $request_info[$name];
                } else {
                    if (isset($request_info['gd_field_' . $name])) {
                        $gd_post_info[$name] = ''; /* fix de-select for multiselect */
                    }
                }
            } else if (isset($request_info[$name])) {
                $gd_post_info[$name] = $request_info[$name];
            }

        endforeach;

        if (isset($request_info['post_dummy']) && $request_info['post_dummy'] != '') {
            $gd_post_info['post_dummy'] = $request_info['post_dummy'];
        }

        // Save post detail info in detail table
        if (!empty($gd_post_info)) {
            geodir_save_post_info($last_post_id, $gd_post_info);
        }


        // Set categories to the listing
        if (isset($request_info['post_category']) && !empty($request_info['post_category'])) {
            $post_category = array();

            foreach ($request_info['post_category'] as $taxonomy => $cat) {

                if ($dummy)
                    $post_category = $cat;
                else {

                    if (!is_array($cat) && strstr($cat, ','))
                        $cat = explode(',', $cat);

                    if (!empty($cat) && is_array($cat))
                        $post_category = array_map('intval', $cat);
                }

                wp_set_object_terms($last_post_id, $post_category, $taxonomy);
            }

            $post_default_category = isset($request_info['post_default_category']) ? $request_info['post_default_category'] : '';

            $post_category_str = isset($request_info['post_category_str']) ? $request_info['post_category_str'] : '';
            geodir_set_postcat_structure($last_post_id, $taxonomy, $post_default_category, $post_category_str);

        }

        $post_tags = '';
        // Set tags to the listing
        if (isset($request_info['post_tags']) && !is_array($request_info['post_tags']) && !empty($request_info['post_tags'])) {
            $post_tags = explode(",", $request_info['post_tags']);
        } elseif (isset($request_info['post_tags']) && is_array($request_info['post_tags'])) {
            if ($dummy)
                $post_tags = $request_info['post_tags'];
        } else {
            if ($dummy)
                $post_tags = array($request_info['post_title']);
        }

        if (is_array($post_tags)) {
            $taxonomy = $request_info['listing_type'] . '_tags';
            wp_set_object_terms($last_post_id, $post_tags, $taxonomy);
        }


        // Insert attachment

        if (isset($request_info['post_images']) && !is_wp_error($last_post_id)) {
            if (!$dummy) {
                $tmpimgArr = trim($request_info['post_images'], ",");
                $tmpimgArr = explode(",", $tmpimgArr);
                geodir_save_post_images($last_post_id, $tmpimgArr, $dummy);
            } else{
                geodir_save_post_images($last_post_id, $request_info['post_images'], $dummy);
            }


        } elseif (!isset($request_info['post_images']) || $request_info['post_images'] == '') {

            /* Delete Attachments
			$postcurr_images = geodir_get_images($last_post_id);

			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ".GEODIR_ATTACHMENT_TABLE." WHERE `post_id` = %d",
					array($last_post_id)
				)
			);
			geodir_remove_attachments($postcurr_images);

			$gd_post_featured_img = array();
			$gd_post_featured_img['featured_image'] = '';
			geodir_save_post_info($last_post_id, $gd_post_featured_img);
			*/

        }

        geodir_remove_temp_images();
        geodir_set_wp_featured_image($last_post_id);

        /**
         * Called after a listing is saved to the database and before any email have been sent.
         *
         * @since 1.0.0
         * @param int $last_post_id The saved post ID.
         * @param array $request_info The post details in an array.
         * @see 'geodir_after_save_listinginfo'
         */
        do_action('geodir_after_save_listing', $last_post_id, $request_info);

        //die;

        if ($send_post_submit_mail) { // if new post send out email
            $to_name = geodir_get_client_name($current_user->ID);
            geodir_sendEmail('', '', $current_user->user_email, $to_name, '', '', $request_info, 'post_submit', $last_post_id, $current_user->ID);
        }
        /*
         * Unset the session so we don't loop.
         */
        $gd_session->un_set('listing');
        return $last_post_id;

    }

}


/**
 * Get post custom fields.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $post The current post object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int|string $post_id Optional. The post ID.
 * @return object|bool Returns full post details as an object. If no details returns false.
 */
function geodir_get_post_info($post_id = '')
{

    global $wpdb, $plugin_prefix, $post, $post_info;

    if ($post_id == '' && !empty($post))
        $post_id = $post->ID;

    $post_type = get_post_type($post_id);

    $all_postypes = geodir_get_posttypes();

    if (!in_array($post_type, $all_postypes))
        return false;

    $table = $plugin_prefix . $post_type . '_detail';

    /**
     * Apply Filter to change Post info
     *
     * You can use this filter to change Post info.
     *
     * @since 1.0.0
     * @package GeoDirectory
     */
    $query = apply_filters('geodir_post_info_query', $wpdb->prepare("SELECT p.*,pd.* FROM " . $wpdb->posts . " p," . $table . " pd
			  WHERE p.ID = pd.post_id
			  AND pd.post_id = %d", $post_id));

    $post_detail = $wpdb->get_row($query);

    return (!empty($post_detail)) ? $post_info = $post_detail : $post_info = false;

}


if (!function_exists('geodir_save_post_info')) {
    /**
     * Saves post detail info in detail table.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $post_id The post ID.
     * @param array $postinfo_array {
     *    Post info that needs to be saved in detail table.
     *
     *    @type string $post_title              Listing title.
     *    @type string $post_tags               Listing tags.
     *    @type string $post_status             Listing post status.
     *    @type string $post_location_id        Listing location ID.
     *    @type string $claimed                 Todo Desc needed.
     *    @type string $businesses              Todo Desc needed.
     *    @type int    $submit_time             Submitted time in unix timestamp.
     *    @type string $submit_ip               Submitted IP.
     *    @type string $expire_date             Listing expiration date.
     *    @type int    $package_id              Listing package ID.
     *    @type int    $alive_days              Todo Desc needed.
     *    @type int    $is_featured             Is this a featured listing?.
     *    @type string $post_address            Listing address.
     *    @type string $post_city               Listing city.
     *    @type string $post_region             Listing region.
     *    @type string $post_country            Listing country.
     *    @type string $post_locations          Listing locations.
     *    @type string $post_zip                Listing zip.
     *    @type string $post_latitude           Listing latitude.
     *    @type string $post_longitude          Listing longitude.
     *    @type string $post_mapview            Listing mapview. Default "ROADMAP".
     *    @type string $post_mapzoom            Listing mapzoom Default "9".
     *    @type string $geodir_timing           Business timing info.
     *    @type string $geodir_contact          Contact number.
     *    @type string $geodir_email            Business contact email.
     *    @type string $geodir_website          Business website.
     *    @type string $geodir_twitter          Twitter link.
     *    @type string $geodir_facebook         Facebook link.
     *    @type string $geodir_video            Video link.
     *    @type string $geodir_special_offers   Special offers.
     *
     * }
     * @return bool
     */
    function geodir_save_post_info($post_id, $postinfo_array = array())
    {
        global $wpdb, $plugin_prefix;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        /**
         * Filter to change Post info
         *
         * You can use this filter to change Post info.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param array $postinfo_array See {@see geodir_save_post_info()} for accepted args.
         * @param int $post_id The post ID.
         */
        $postmeta = apply_filters('geodir_listinginfo_request', $postinfo_array, $post_id);

        $query_string_escaped = '';
        $query_string_array = array();

        if (!empty($postmeta) && $post_id) {

            $columns = $wpdb->get_col("show columns from $table");
            foreach ($postmeta as $mkey => $mval) {
                if(in_array($mkey,$columns)) {
                    if (is_array($mval)) {
                        $mval = implode(",", $mval);
                    }
                    $query_string_escaped .= " $mkey = %s, "; // we can set the key here as we check if the column exists above
                    $query_string_array[] = stripslashes($mval); // we strip slashes as we are using wpdb prepare

                }
            }

            $query_string_escaped = trim($query_string_escaped, ", ");

            if (empty($query_string_array) || trim($query_string_escaped) == '') {
                return false;
            }

            $query_string_array = str_replace(array("'%", "%'"), array("'%%", "%%'"), $query_string_array);


            /**
             * Called before saving the listing info.
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param array $postinfo_array See {@see geodir_save_post_info()} for accepted args.
             * @param int $post_id The post ID.
             */
            do_action('geodir_before_save_listinginfo', $postinfo_array, $post_id);

            if ($wpdb->get_var($wpdb->prepare("SELECT post_id from " . $table . " where post_id = %d", array($post_id)))) {

                $query_string_array[] = $post_id;
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE " . $table . " SET " . $query_string_escaped . " where post_id =%d",
                        $query_string_array
                    )
                );


            } else {

                array_unshift($query_string_array, $post_id);
                $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO " . $table . " SET post_id = %d," . $query_string_escaped,
                        $query_string_array
                    )
                );
                
            }

            /**
             * Called after saving the listing info.
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param array $postinfo_array Post info that needs to be saved in detail table.
             * @param int $post_id The post ID.
             * @see 'geodir_after_save_listing'
             */
            do_action('geodir_after_save_listinginfo', $postinfo_array, $post_id);

            return true;
        } else
            return false;

    }
}


if (!function_exists('geodir_save_post_meta')) {
    /**
     * Save or update post custom fields.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $post_id The post ID.
     * @param string $postmeta Detail table column name.
     * @param string $meta_value Detail table column value.
     * @return void|bool
     */
    function geodir_save_post_meta($post_id, $postmeta = '', $meta_value = '')
    {

        global $wpdb, $plugin_prefix;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        if ($postmeta != '' && geodir_column_exist($table, $postmeta) && $post_id) {

            if (is_array($meta_value)) {
                $meta_value = implode(",", $meta_value);
            }

            if ($wpdb->get_var($wpdb->prepare("SELECT post_id from " . $table . " where post_id = %d", array($post_id)))) {

                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE " . $table . " SET " . $postmeta . " = '" . $meta_value . "' where post_id =%d",
                        array($post_id)
                    )
                );

            } else {

                $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO " . $table . " SET post_id = %d, " . $postmeta . " = '" . $meta_value . "'",
                        array($post_id)
                    )
                );
            }


        } else
            return false;
    }
}

if (!function_exists('geodir_delete_post_meta')) {
    /**
     * Delete post custom fields.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $post_id The post ID.
     * @param string $postmeta Detail table column name.
     * @todo check if this is depreciated
     * @todo Fix unknown variable mval
     * @return bool
     */
    function geodir_delete_post_meta($post_id, $postmeta)
    {

        global $wpdb, $plugin_prefix;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        if (is_array($postmeta) && !empty($postmeta) && $post_id) {
            $post_meta_set_query = '';

            foreach ($postmeta as $mkey) {
                if ($mval != '')
                    $post_meta_set_query .= $mkey . " = '', ";
            }

            $post_meta_set_query = trim($post_meta_set_query, ", ");
            
            if (empty($post_meta_set_query) || trim($post_meta_set_query) == '') {
                return false;
            }

            if ($wpdb->get_var("SHOW COLUMNS FROM " . $table . " WHERE field = '" . $postmeta . "'") != '') {

                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE " . $table . " SET " . $post_meta_set_query . " where post_id = %d",
                        array($post_id)
                    )
                );

                return true;
            }

        } elseif ($postmeta != '' && $post_id) {
            if ($wpdb->get_var("SHOW COLUMNS FROM " . $table . " WHERE field = '" . $postmeta . "'") != '') {

                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE " . $table . " SET " . $postmeta . "= '' where post_id = %d",
                        array($post_id)
                    )
                );

                return true;
            }

        } else
            return false;
    }
}


if (!function_exists('geodir_get_post_meta')) {
    /**
     * Get post custom meta.
     *
     * @since 1.0.0
     * @since 1.6.20 Hook added to filter value.
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $post_id The post ID.
     * @param string $meta_key The meta key to retrieve.
     * @param bool $single Optional. Whether to return a single value. Default false.
     * @todo single variable not yet implemented.
     * @return bool|mixed|null|string Will be an array if $single is false. Will be value of meta data field if $single is true.
     */
    function geodir_get_post_meta($post_id, $meta_key, $single = false) {
        if (!$post_id) {
            return false;
        }
        global $wpdb, $plugin_prefix;

        $all_postypes = geodir_get_posttypes();

        $post_type = get_post_type($post_id);

        if (!in_array($post_type, $all_postypes))
            return false;

        $table = $plugin_prefix . $post_type . '_detail';

        if ($wpdb->get_var("SHOW COLUMNS FROM " . $table . " WHERE field = '" . $meta_key . "'") != '') {
            $meta_value = $wpdb->get_var($wpdb->prepare("SELECT " . $meta_key . " from " . $table . " where post_id = %d", array($post_id)));
            
            if ($meta_value && $meta_value !== '') {
                $meta_value = maybe_serialize($meta_value);
            }
        } else {
            $meta_value = false;
        }
        
        /**
         * Filter the listing custom meta.
         *
         * @since 1.6.20
         * 
         * @param bool|mixed|null|string $meta_value Will be an array if $single is false. Will be value of meta data field if $single is true.
         * @param int $post_id The post ID.
         * @param string $meta_key The meta key to retrieve.
         * @param bool $single Optional. Whether to return a single value. Default false.
         */
        return apply_filters( 'geodir_get_post_meta', $meta_value, $post_id, $meta_key, $single );
    }
}


if (!function_exists('geodir_save_post_images')) {
    /**
     * Save post attachments.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @global object $current_user Current user object.
     * @param int $post_id The post ID.
     * @param array $post_image Post image urls as an array.
     * @param bool $dummy Optional. Is this a dummy listing? Default false.
     */
    function geodir_save_post_images($post_id = 0, $post_image = array(), $dummy = false)
    {


        global $wpdb, $plugin_prefix, $current_user;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        $post_images = geodir_get_images($post_id);

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . $table . " SET featured_image = '' where post_id =%d",
                array($post_id)
            )
        );

        $invalid_files = $post_images;
        $valid_file_ids = array();
        $valid_files_condition = '';
        $geodir_uploaddir = '';

        $remove_files = array();

        if (!empty($post_image)) {

            $uploads = wp_upload_dir();
            $uploads_dir = $uploads['path'];

            $geodir_uploadpath = $uploads['path'];
            $geodir_uploadurl = $uploads['url'];
            $sub_dir = isset($uploads['subdir']) ? $uploads['subdir'] : '';

            $invalid_files = array();
            $postcurr_images = array();

            for ($m = 0; $m < count($post_image); $m++) {
                $menu_order = $m + 1;

                $file_path = '';
                /* --------- start ------- */

                $split_img_path = explode(str_replace(array('http://','https://'),'',$uploads['baseurl']), str_replace(array('http://','https://'),'',$post_image[$m]));

                $split_img_file_path = isset($split_img_path[1]) ? $split_img_path[1] : '';


                if (!$find_image = $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE file=%s AND post_id = %d", array($split_img_file_path, $post_id)))) {

                    /* --------- end ------- */
                    $curr_img_url = $post_image[$m];

                    $image_name_arr = explode('/', $curr_img_url);

                    $count_image_name_arr = count($image_name_arr) - 2;

                    $count_image_name_arr = ($count_image_name_arr >= 0) ? $count_image_name_arr : 0;

                    $curr_img_dir = $image_name_arr[$count_image_name_arr];

                    $filename = end($image_name_arr);
                    if (strpos($filename, '?') !== false) {
                        list($filename) = explode('?', $filename);
                    }

                    $curr_img_dir = str_replace($uploads['baseurl'], "", $curr_img_url);
                    $curr_img_dir = str_replace($filename, "", $curr_img_dir);

                    $img_name_arr = explode('.', $filename);

                    $file_title = isset($img_name_arr[0]) ? $img_name_arr[0] : $filename;
                    if (!empty($img_name_arr) && count($img_name_arr) > 2) {
                        $new_img_name_arr = $img_name_arr;
                        if (isset($new_img_name_arr[count($img_name_arr) - 1])) {
                            unset($new_img_name_arr[count($img_name_arr) - 1]);
                            $file_title = implode('.', $new_img_name_arr);
                        }
                    }
                    $file_title = sanitize_file_name($file_title);
                    $file_name = sanitize_file_name($filename);

                    $arr_file_type = wp_check_filetype($filename);

                    $uploaded_file_type = $arr_file_type['type'];

                    // Set an array containing a list of acceptable formats
                    $allowed_file_types = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

                    // If the uploaded file is the right format
                    if (in_array($uploaded_file_type, $allowed_file_types)) {
                        if (!function_exists('wp_handle_upload')) {
                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                        }

                        if (!is_dir($geodir_uploadpath)) {
                            mkdir($geodir_uploadpath);
                        }

                        $external_img = false;
                        if (strpos( str_replace( array('http://','https://'),'',$curr_img_url ), str_replace(array('http://','https://'),'',$uploads['baseurl'] ) ) !== false) {
                        } else {
                            $external_img = true;
                        }

                        if ($dummy || $external_img) {
                            $uploaded_file = array();
                            $uploaded = (array)fetch_remote_file($curr_img_url);

                            if (isset($uploaded['error']) && empty($uploaded['error'])) {
                                $new_name = basename($uploaded['file']);
                                $uploaded_file = $uploaded;
                            }else{
                                print_r($uploaded);exit;
                            }
                            $external_img = false;
                        } else {
                            $new_name = $post_id . '_' . $file_name;

                            if ($curr_img_dir == $sub_dir) {
                                $img_path = $geodir_uploadpath . '/' . $filename;
                                $img_url = $geodir_uploadurl . '/' . $filename;
                            } else {
                                $img_path = $uploads_dir . '/temp_' . $current_user->data->ID . '/' . $filename;
                                $img_url = $uploads['url'] . '/temp_' . $current_user->data->ID . '/' . $filename;
                            }

                            $uploaded_file = '';

                            if (file_exists($img_path)) {
                                $uploaded_file = copy($img_path, $geodir_uploadpath . '/' . $new_name);
                                $file_path = '';
                            } else if (file_exists($uploads['basedir'] . $curr_img_dir . $filename)) {
                                $uploaded_file = true;
                                $file_path = $curr_img_dir . '/' . $filename;
                            }

                            if ($curr_img_dir != $geodir_uploaddir && file_exists($img_path))
                                unlink($img_path);
                        }

                        if (!empty($uploaded_file)) {
                            if (!isset($file_path) || !$file_path) {
                                $file_path = $sub_dir . '/' . $new_name;
                            }

                            $postcurr_images[] = str_replace(array('http://','https://'),'',$uploads['baseurl'] . $file_path);

                            if ($menu_order == 1) {

                                $wpdb->query($wpdb->prepare("UPDATE " . $table . " SET featured_image = %s where post_id =%d", array($file_path, $post_id)));

                            }

                            // Set up options array to add this file as an attachment
                            $attachment = array();
                            $attachment['post_id'] = $post_id;
                            $attachment['title'] = $file_title;
                            $attachment['content'] = '';
                            $attachment['file'] = $file_path;
                            $attachment['mime_type'] = $uploaded_file_type;
                            $attachment['menu_order'] = $menu_order;
                            $attachment['is_featured'] = 0;

                            $attachment_set = '';

                            foreach ($attachment as $key => $val) {
                                if ($val != '')
                                    $attachment_set .= $key . " = '" . $val . "', ";
                            }

                            $attachment_set = trim($attachment_set, ", ");

                            $wpdb->query("INSERT INTO " . GEODIR_ATTACHMENT_TABLE . " SET " . $attachment_set);

                            $valid_file_ids[] = $wpdb->insert_id;
                        }

                    }


                } else {
                    $valid_file_ids[] = $find_image;

                    $postcurr_images[] = str_replace(array('http://','https://'),'',$post_image[$m]);

                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE " . GEODIR_ATTACHMENT_TABLE . " SET menu_order = %d where file =%s AND post_id =%d",
                            array($menu_order, $split_img_path[1], $post_id)
                        )
                    );

                    if ($menu_order == 1)
                        $wpdb->query($wpdb->prepare("UPDATE " . $table . " SET featured_image = %s where post_id =%d", array($split_img_path[1], $post_id)));

                }


            }

            if (!empty($valid_file_ids)) {

                $remove_files = $valid_file_ids;

                $remove_files_length = count($remove_files);
                $remove_files_format = array_fill(0, $remove_files_length, '%d');
                $format = implode(',', $remove_files_format);
                $valid_files_condition = " ID NOT IN ($format) AND ";

            }

            //Get and remove all old images of post from database to set by new order

            if (!empty($post_images)) {

                foreach ($post_images as $img) {

                    if (!in_array(str_replace(array('http://','https://'),'',$img->src), $postcurr_images)) {

                        $invalid_files[] = (object)array('src' => $img->src);

                    }

                }

            }

            $invalid_files = (object)$invalid_files;
        }

        $remove_files[] = $post_id;

        $wpdb->query($wpdb->prepare("DELETE FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE " . $valid_files_condition . " post_id = %d", $remove_files));

        if (!empty($invalid_files))
            geodir_remove_attachments($invalid_files);
    }

}

/**
 * Remove current user's temporary images.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $current_user Current user object.
 **/
function geodir_remove_temp_images()
{

    global $current_user;

    $uploads = wp_upload_dir();
    $uploads_dir = $uploads['path'];

    /*	if(is_dir($uploads_dir.'/temp_'.$current_user->data->ID)){

			$dirPath = $uploads_dir.'/temp_'.$current_user->data->ID;
			if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
				$dirPath .= '/';
			}
			$files = glob($dirPath . '*', GLOB_MARK);
			foreach ($files as $file) {
				if (is_dir($file)) {
					self::deleteDir($file);
				} else {
					unlink($file);
				}
			}
			rmdir($dirPath);
	}	*/

    $dirname = $uploads_dir . '/temp_' . $current_user->ID;
    geodir_delete_directory($dirname);
}


/**
 * Delete a directory.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $dirname Directory name that needs to be deleted.
 * @return bool
 */
function geodir_delete_directory($dirname)
{
    $dir_handle = '';
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                geodir_delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;

}


if (!function_exists('geodir_remove_attachments')) {
    /**
     * Remove post attachments.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param array $postcurr_images Array of image objects.
     */
    function geodir_remove_attachments($postcurr_images = array())
    {
        // Unlink all past images of post
        if (!empty($postcurr_images)) {

            $uploads = wp_upload_dir();
            $uploads_dir = $uploads['path'];

            foreach ($postcurr_images as $postimg) {
                $image_name_arr = explode('/', $postimg->src);
                $filename = end($image_name_arr);
                if (file_exists($uploads_dir . '/' . $filename))
                    unlink($uploads_dir . '/' . $filename);
            }

        } // endif
        // Unlink all past images of post end
    }
}

if (!function_exists('geodir_get_featured_image')) {
    /**
     * Gets the post featured image.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global object $post The current post object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int|string $post_id The post ID.
     * @param string $size Optional. Thumbnail size. Default: thumbnail.
     * @param bool $no_image Optional. Do you want to return the default image when no image is available? Default: false.
     * @param bool|string $file Optional. The file path from which you want to get the image details. Default: false.
     * @return bool|object Image details as an object.
     */
    function geodir_get_featured_image($post_id = '', $size = '', $no_image = false, $file = false)
    {

        /*$img_arr['src'] = get_the_post_thumbnail_url( $post_id,  'medium');//medium/thumbnail
        $img_arr['path'] = '';
        $img_arr['width'] = '';
        $img_arr['height'] = '';
        $img_arr['title'] = '';
        return (object)$img_arr;*/
        global $wpdb, $plugin_prefix, $post;

        if (isset($post->ID) && isset($post->post_type) && $post->ID == $post_id) {
            $post_type = $post->post_type;
        } else {
            $post_type = get_post_type($post_id);
        }

        if (!in_array($post_type, geodir_get_posttypes())) {
            return false;// if not a GD CPT return;
        }


        $list_img_size = get_option('geodir_listing_img_size','default');

        if( $size=='list-thumb' && $list_img_size != 'default' ){
            $fimg = get_the_post_thumbnail_url($post_id,$list_img_size);
            if($fimg){
                $uploads = wp_upload_dir(); 
                $uploads_baseurl = $uploads['baseurl'];
                $file = str_replace($uploads_baseurl,'',$fimg);
            }
        }

        $table = $plugin_prefix . $post_type . '_detail';

        if (!$file) {
            if (isset($post->featured_image)) {
                $file = $post->featured_image;
            } else {
                $file = $wpdb->get_var($wpdb->prepare("SELECT featured_image FROM " . $table . " WHERE post_id = %d", array($post_id)));
            }
        }

        if ($file != NULL && $file != '' && (($uploads = wp_upload_dir()) && false === $uploads['error'])) {
            $img_arr = array();

            $file_info = pathinfo($file);
            $sub_dir = '';
            if ($file_info['dirname'] != '.' && $file_info['dirname'] != '..') {
                $sub_dir = stripslashes_deep($file_info['dirname']);
            }

            $uploads = wp_upload_dir(trim($sub_dir, '/')); // Array of key => value pairs
            $uploads_baseurl = $uploads['baseurl'];
            $uploads_path = $uploads['path'];

            $file_name = $file_info['basename'];

            $uploads_url = $uploads_baseurl . $sub_dir;
            /*
             * Allows the filter of image src for such things as CDN change.
             *
             * @since 1.5.7
             * @param string $url The full image url.
             * @param string $file_name The image file name and directory path.
             * @param string $uploads_url The server upload directory url.
             * @param string $uploads_baseurl The uploads dir base url.
             */
            $img_arr['src'] = apply_filters('geodir_get_featured_image_src',$uploads_url . '/' . $file_name,$file_name,$uploads_url,$uploads_baseurl);
            $img_arr['path'] = $uploads_path . '/' . $file_name;
            $width = 0;
            $height = 0;
            if (is_file($img_arr['path']) && file_exists($img_arr['path'])) {
                $imagesize = getimagesize($img_arr['path']);
                $width = !empty($imagesize) && isset($imagesize[0]) ? $imagesize[0] : '';
                $height = !empty($imagesize) && isset($imagesize[1]) ? $imagesize[1] : '';
            }
            $img_arr['width'] = $width;
            $img_arr['height'] = $height;
            $img_arr['title'] = $post->post_title;
        } elseif ($post_images = geodir_get_images($post_id, $size, $no_image, 1)) {
            foreach ($post_images as $image) {
                return $image;
            }
        } else if ($no_image) {
            $img_arr = array();

            $default_img = '';
            if (isset($post->default_category) && $post->default_category) {
                $default_cat = $post->default_category;
            } else {
                $default_cat = geodir_get_post_meta($post_id, 'default_category', true);
            }

            if ($default_catimg = geodir_get_default_catimage($default_cat, $post_type))
                $default_img = $default_catimg['src'];
            elseif ($no_image) {
                $default_img = get_option('geodir_listing_no_img');
            }

            if (!empty($default_img)) {
                $uploads = wp_upload_dir(); // Array of key => value pairs
                $uploads_baseurl = $uploads['baseurl'];
                $uploads_path = $uploads['path'];

                $img_arr = array();

                $file_info = pathinfo($default_img);

                $file_name = $file_info['basename'];

                $img_arr['src'] = $default_img;
                $img_arr['path'] = $uploads_path . '/' . $file_name;

                $width = 0;
                $height = 0;
                if (is_file($img_arr['path']) && file_exists($img_arr['path'])) {
                    $imagesize = getimagesize($img_arr['path']);
                    $width = !empty($imagesize) && isset($imagesize[0]) ? $imagesize[0] : '';
                    $height = !empty($imagesize) && isset($imagesize[1]) ? $imagesize[1] : '';
                }
                $img_arr['width'] = $width;
                $img_arr['height'] = $height;

                $img_arr['title'] = $post->post_title; // add the title to the array
            }
        }

        if (!empty($img_arr))
            return (object)$img_arr;//return (object)array( 'src' => $file_url, 'path' => $file_path );
        else
            return false;
    }
}

if (!function_exists('geodir_show_featured_image')) {
    /**
     * Gets the post featured image.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param int|string $post_id The post ID.
     * @param string $size Optional. Thumbnail size. Default: thumbnail.
     * @param bool $no_image Optional. Do you want to return the default image when no image is available? Default: false.
     * @param bool $echo Optional. Do you want to print it instead of returning it? Default: true.
     * @param bool|string $fimage Optional. The file path from which you want to get the image details. Default: false.
     * @return bool|string Returns image html.
     */
    function geodir_show_featured_image($post_id = '', $size = 'thumbnail', $no_image = false, $echo = true, $fimage = false)
    {
        $image = geodir_get_featured_image($post_id, $size, $no_image, $fimage);

        $html = geodir_show_image($image, $size, $no_image, false);

        if (!empty($html) && $echo) {
            echo $html;
        } elseif (!empty($html)) {
            return $html;
        } else
            return false;
    }
}

if (!function_exists('geodir_get_images')) {
    /**
     * Gets the post images.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @param int $post_id The post ID.
     * @param string $img_size Optional. Thumbnail size.
     * @param bool $no_images Optional. Do you want to return the default image when no image is available? Default: false.
     * @param bool $add_featured Optional. Do you want to include featured images too? Default: true.
     * @param int|string $limit Optional. Number of images.
     * @return array|bool Returns images as an array. Each item is an object.
     */
    function geodir_get_images($post_id = 0, $img_size = '', $no_images = false, $add_featured = true, $limit = '')
    {
        global $wpdb;
        if ($limit) {
            $limit_q = " LIMIT $limit ";
        } else {
            $limit_q = '';
        }
        $not_featured = '';
        $sub_dir = '';
        if (!$add_featured)
            $not_featured = " AND is_featured = 0 ";

        $arrImages = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE mime_type like %s AND post_id = %d" . $not_featured . " ORDER BY menu_order ASC, ID DESC $limit_q ",
                array('%image%', $post_id)
            )
        );

        $counter = 0;
        $return_arr = array();

        if (!empty($arrImages)) {
            foreach ($arrImages as $attechment) {

                $img_arr = array();
                $img_arr['id'] = $attechment->ID;
                $img_arr['user_id'] = isset($attechment->user_id) ? $attechment->user_id : 0;

                $file_info = pathinfo($attechment->file);

                if ($file_info['dirname'] != '.' && $file_info['dirname'] != '..')
                    $sub_dir = stripslashes_deep($file_info['dirname']);

                $uploads = wp_upload_dir(trim($sub_dir, '/')); // Array of key => value pairs
                $uploads_baseurl = $uploads['baseurl'];
                $uploads_path = $uploads['path'];

                $file_name = $file_info['basename'];

                $uploads_url = $uploads_baseurl . $sub_dir;
                /*
                * Allows the filter of image src for such things as CDN change.
                *
                * @since 1.5.7
                * @param string $url The full image url.
                * @param string $file_name The image file name and directory path.
                * @param string $uploads_url The server upload directory url.
                * @param string $uploads_baseurl The uploads dir base url.
                */
                $img_arr['src'] = apply_filters('geodir_get_images_src',$uploads_url . '/' . $file_name,$file_name,$uploads_url,$uploads_baseurl);
                $img_arr['path'] = $uploads_path . '/' . $file_name;
                $width = 0;
                $height = 0;
                if (is_file($img_arr['path']) && file_exists($img_arr['path'])) {
                    $imagesize = getimagesize($img_arr['path']);
                    $width = !empty($imagesize) && isset($imagesize[0]) ? $imagesize[0] : '';
                    $height = !empty($imagesize) && isset($imagesize[1]) ? $imagesize[1] : '';
                }
                $img_arr['width'] = $width;
                $img_arr['height'] = $height;

                $img_arr['file'] = $file_name; // add the title to the array
                $img_arr['title'] = $attechment->title; // add the title to the array
                $img_arr['caption'] = isset($attechment->caption) ? $attechment->caption : ''; // add the caption to the array
                $img_arr['content'] = $attechment->content; // add the description to the array
                $img_arr['is_approved'] = isset($attechment->is_approved) ? $attechment->is_approved : ''; // used for user image moderation. For backward compatibility Default value is 1.

                $return_arr[] = (object)$img_arr;

                $counter++;
            }
            //return (object)$return_arr;
            /**
             * Filter the images array so things can be changed.
             *
             * @since 1.6.20
             * @param array $return_arr The array of image objects.
             */
            return apply_filters('geodir_get_images_arr',$return_arr);
        } else if ($no_images) {
            $default_img = '';
            $default_cat = geodir_get_post_meta($post_id, 'default_category', true);
            $post_type = get_post_type($post_id);
            if ($default_catimg = geodir_get_default_catimage($default_cat, $post_type))
                $default_img = $default_catimg['src'];
            elseif ($no_images) {
                $default_img = get_option('geodir_listing_no_img');
            }

            if (!empty($default_img)) {
                $uploads = wp_upload_dir(); // Array of key => value pairs
                
                $image_path = $default_img;
                if (!path_is_absolute($image_path)) {
                    $image_path = str_replace($uploads['baseurl'], $uploads['basedir'], $image_path);
                }

                $file_info = pathinfo($default_img);
                $file_name = $file_info['basename'];

                $width = '';
                $height = '';
                if (is_file($image_path) && file_exists($image_path)) {
                    $imagesize = getimagesize($image_path);
                    $width = !empty($imagesize) && isset($imagesize[0]) ? $imagesize[0] : '';
                    $height = !empty($imagesize) && isset($imagesize[1]) ? $imagesize[1] : '';
                }
                
                $img_arr = array();
                $img_arr['src'] = $default_img;
                $img_arr['path'] = $image_path;
                $img_arr['width'] = $width;
                $img_arr['height'] = $height;
                $img_arr['file'] = $file_name; // add the title to the array
                $img_arr['title'] = $file_info['filename']; // add the title to the array
                $img_arr['content'] = $file_info['filename']; // add the description to the array

                $return_arr[] = (object)$img_arr;

                /**
                 * Filter the images array so things can be changed.
                 * 
                 * @since 1.6.20
                 * @param array $return_arr The array of image objects.
                 */
                return apply_filters('geodir_get_images_arr',$return_arr);
            } else
                return false;
        }
    }
}

if (!function_exists('geodir_show_image')) {
    /**
     * Show image using image details.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param array|object $request Image info either as an array or object.
     * @param string $size Optional. Thumbnail size. Default: thumbnail.
     * @param bool $no_image Optional. Do you want to return the default image when no image is available? Default: false.
     * @param bool $echo Optional. Do you want to print it instead of returning it? Default: true.
     * @return bool|string Returns image html.
     */
    function geodir_show_image($request = array(), $size = 'thumbnail', $no_image = false, $echo = true)
    {
        $image = new stdClass();

        $html = '';
        if (!empty($request)) {
            if (!is_object($request)){
                $request = (object)$request;
            }

            if (isset($request->src) && !isset($request->path)) {
                $request->path = $request->src;
            }

            /*
             * getimagesize() works faster from path than url so we try and get path if we can.
             */
            $upload_dir = wp_upload_dir();
            $img_no_http = str_replace(array("http://", "https://"), "", $request->path);
            $upload_no_http = str_replace(array("http://", "https://"), "", $upload_dir['baseurl']);
            if (strpos($img_no_http, $upload_no_http) !== false) {
                $request->path = str_replace( $img_no_http,$upload_dir['basedir'], $request->path);
            }
            
            $width = 0;
            $height = 0;
            if (is_file($request->path) && file_exists($request->path)) {
                $imagesize = getimagesize($request->path);
                $width = !empty($imagesize) && isset($imagesize[0]) ? $imagesize[0] : '';
                $height = !empty($imagesize) && isset($imagesize[1]) ? $imagesize[1] : '';
            }


            $image->src = $request->src;
            $image->width = $width;
            $image->height = $height;
            $image->title = isset($request->title) ? $request->title : '';

            $max_size = (object)geodir_get_imagesize($size);

            if (!is_wp_error($max_size)) {
                if ($image->width) {
                    if ($image->height >= $image->width) {
                        $width_per = round(((($image->width * ($max_size->h / $image->height)) / $max_size->w) * 100), 2);
                    } else if ($image->width < ($max_size->h)) {
                        $width_per = round((($image->width / $max_size->w) * 100), 2);
                    } else
                        $width_per = 100;
                }

                if (is_admin() && !isset($_REQUEST['geodir_ajax'])){
                    $html = '<div class="geodir_thumbnail"><img style="max-height:' . $max_size->h . 'px;" alt="place image" src="' . $image->src . '"  /></div>';
                } else {
                    if($size=='widget-thumb' || !get_option('geodir_lazy_load',1)){
                        $html = '<div class="geodir_thumbnail" style="background-image:url(\'' . $image->src . '\');" title="'.$image->title.'" aria-label="'.$image->title.'" ></div>';
                    }else{
                        $html = '<div data-src="'.str_replace(' ','%20',$image->src).'" class="geodir_thumbnail geodir_lazy_load_thumbnail" title="'.$image->title.'" aria-label="'.$image->title.'"></div>';
                    }

                }
            }
        }

        if (!empty($html) && $echo) {
            echo $html;
        } elseif (!empty($html)) {
            return $html;
        } else
            return false;
    }
}

if (!function_exists('geodir_set_post_terms')) {
    /**
     * Set post Categories.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $post_id The post ID.
     * @param array $terms An array of term objects.
     * @param array $tt_ids An array of term taxonomy IDs.
     * @param string $taxonomy Taxonomy slug.
     */
    function geodir_set_post_terms($post_id, $terms, $tt_ids, $taxonomy)
    {
        global $wpdb, $plugin_prefix;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        if (in_array($post_type, geodir_get_posttypes()) && !wp_is_post_revision($post_id)) {

            if ($taxonomy == $post_type . '_tags') {
                if (isset($_POST['action']) && $_POST['action'] == 'inline-save') {
                    geodir_save_post_meta($post_id, 'post_tags', $terms);
                }
            } elseif ($taxonomy == $post_type . 'category') {
                $srcharr = array('"', '\\');
                $replarr = array("&quot;", '');

                $post_obj = get_post($post_id);

                $cat_ids = array('0');
                if (is_array($tt_ids))
                    $cat_ids = $tt_ids;


                if (!empty($cat_ids)) {
                    $cat_ids_array = $cat_ids;
                    $cat_ids_length = count($cat_ids_array);
                    $cat_ids_format = array_fill(0, $cat_ids_length, '%d');
                    $format = implode(',', $cat_ids_format);

                    $cat_ids_array_del = $cat_ids_array;
                    $cat_ids_array_del[] = $post_id;

                    $wpdb->get_var(
                        $wpdb->prepare(
                            "DELETE from " . GEODIR_ICON_TABLE . " WHERE cat_id NOT IN ($format) AND post_id = %d ",
                            $cat_ids_array_del
                        )
                    );


                    $post_term = $wpdb->get_col(
                        $wpdb->prepare(
                            "SELECT term_id FROM " . $wpdb->term_taxonomy . " WHERE term_taxonomy_id IN($format) GROUP BY term_id",
                            $cat_ids_array
                        )
                    );

                }

                $post_marker_json = '';

                if (!empty($post_term)):

                    foreach ($post_term as $cat_id):

                        $term_icon_url = geodir_get_tax_meta($cat_id, 'ct_cat_icon', false, $post_type);
                        $term_icon = isset($term_icon_url['src']) ? $term_icon_url['src'] : '';

                        $post_title = $post_obj->title;
                        $title = str_replace($srcharr, $replarr, $post_title);

                        $lat = geodir_get_post_meta($post_id, 'post_latitude', true);
                        $lng = geodir_get_post_meta($post_id, 'post_longitude', true);

                        $timing = ' - ' . date('D M j, Y', strtotime(geodir_get_post_meta($post_id, 'st_date', true)));
                        $timing .= ' - ' . geodir_get_post_meta($post_id, 'st_time', true);

                        $json = '{';
                        $json .= '"id":"' . $post_id . '",';
                        $json .= '"lat_pos": "' . $lat . '",';
                        $json .= '"long_pos": "' . $lng . '",';
                        $json .= '"marker_id":"' . $post_id . '_' . $cat_id . '",';
                        $json .= '"icon":"' . $term_icon . '",';
                        $json .= '"group":"catgroup' . $cat_id . '"';
                        $json .= '}';


                        if ($cat_id == geodir_get_post_meta($post_id, 'default_category', true))
                            $post_marker_json = $json;


                        if ($wpdb->get_var($wpdb->prepare("SELECT post_id from " . GEODIR_ICON_TABLE . " WHERE post_id = %d AND cat_id = %d", array($post_id, $cat_id)))) {

                            $json_query = $wpdb->prepare("UPDATE " . GEODIR_ICON_TABLE . " SET
										post_title = %s,
										json = %s
										WHERE post_id = %d AND cat_id = %d ",
                                array($post_title, $json, $post_id, $cat_id));

                        } else {

                            $json_query = $wpdb->prepare("INSERT INTO " . GEODIR_ICON_TABLE . " SET
										post_id = %d,
										post_title = %s,
										cat_id = %d,
										json = %s",
                                array($post_id, $post_title, $cat_id, $json));

                        }

                        $wpdb->query($json_query);

                    endforeach;

                endif;

                if (!empty($post_term) && is_array($post_term)) {
                    $categories = implode(',', $post_term);

                    if ($categories != '' && $categories != 0) $categories = ',' . $categories . ',';

                    if (empty($post_marker_json))
                        $post_marker_json = isset($json) ? $json : '';

                    if ($wpdb->get_var($wpdb->prepare("SELECT post_id from " . $table . " where post_id = %d", array($post_id)))) {

                        $wpdb->query(
                            $wpdb->prepare(
                                "UPDATE " . $table . " SET
								" . $taxonomy . " = %s,
								marker_json = %s
								where post_id = %d",
                                array($categories, $post_marker_json, $post_id)
                            )
                        );

                        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'inline-save') {

                            $categories = trim($categories, ',');

                            if ($categories) {

                                $categories = explode(',', $categories);

                                $default_category = geodir_get_post_meta($post_id, 'default_category', true);

                                if (!in_array($default_category, $categories)) {

                                    $wpdb->query(
                                        $wpdb->prepare(
                                            "UPDATE " . $table . " SET
											default_category = %s
											where post_id = %d",
                                            array($categories[0], $post_id)
                                        )
                                    );

                                    $default_category = $categories[0];

                                }

                                if ($default_category == '')
                                    $default_category = $categories[0];

                                geodir_set_postcat_structure($post_id, $taxonomy, $default_category, '');

                            }

                        }


                    } else {

                        $wpdb->query(
                            $wpdb->prepare(
                                "INSERT INTO " . $table . " SET
								post_id = %d,
								" . $taxonomy . " = %s,
								marker_json = %s ",

                                array($post_id, $categories, $post_marker_json)
                            )
                        );
                    }
                }
            }
        }
    }
}

if (!function_exists('geodir_get_infowindow_html')) {
    /**
     * Set post Map Marker info html.
     *
     * @since 1.0.0
     * @since 1.5.4 Modified to add new action "geodir_infowindow_meta_before".
     * @since 1.6.16 Changes for disable review stars for certain post type.
     * @since 1.6.18 Fix: Map marker not showing custom fields in bubble info.
     * @package GeoDirectory
     * @global array $geodir_addon_list List of active GeoDirectory extensions.
     * @global object $gd_session GeoDirectory Session object.
     * @param object $postinfo_obj The post details object.
     * @param string $post_preview Is this a post preview?.
     * @global object $post WordPress Post object.
     * @return mixed|string|void
     */
    function geodir_get_infowindow_html($postinfo_obj, $post_preview = '') {
        global $preview, $post, $gd_session;
        $srcharr = array("'", "/", "-", '"', '\\');
        $replarr = array("&prime;", "&frasl;", "&ndash;", "&ldquo;", '');

        if ($gd_session->get('listing') && isset($post_preview) && $post_preview != '') {
            $ID = '';
            $plink = '';

            if (isset($postinfo_obj->pid)) {
                $ID = $postinfo_obj->pid;
                $plink = get_permalink($ID);
            }

            $title = str_replace($srcharr, $replarr, ($postinfo_obj->post_title));
            $lat = $postinfo_obj->post_latitude;
            $lng = $postinfo_obj->post_longitude;
        } else {
            $ID = $postinfo_obj->post_id;
            $title = str_replace($srcharr, $replarr, htmlentities($postinfo_obj->post_title, ENT_COMPAT, 'UTF-8')); // fix by Stiofan
            $title = wp_specialchars_decode($title); // Fixed #post-320722 on 2016-12-08
            $plink = get_permalink($ID);
            $lat = htmlentities(geodir_get_post_meta($ID, 'post_latitude', true));
            $lng = htmlentities(geodir_get_post_meta($ID, 'post_longitude', true));
        }
        
        // Some theme overwrites global gd listing $post
        if (!empty($ID) && (!empty($post->ID) && $post->ID != $ID) || empty($post)) {
            $post = geodir_get_post_info($ID);
        }
        
        $post_type = $ID ? get_post_type($ID) : '';

        // filter field as per price package
        global $geodir_addon_list;
        if ($post_type && defined('GEODIRPAYMENT_VERSION')) {
            $package_id = isset($postinfo_obj->package_id) && $postinfo_obj->package_id ? $postinfo_obj->package_id : NULL;
            $field_name = 'geodir_contact';
            if (!check_field_visibility($package_id, $field_name, $post_type)) {
                $contact = '';
            }

            $field_name = 'geodir_timing';
            if (!check_field_visibility($package_id, $field_name, $post_type)) {
                $timing = '';
            }
        }

        if ($lat && $lng) {
            ob_start(); ?>
            <div class="gd-bubble" style="">
                <div class="gd-bubble-inside">
                    <?php
                    $comment_count = '';
                    $rating_star = '';
                    if ($ID != '' && $post_type != '' && !geodir_cpt_has_rating_disabled($post_type)) {
                        $rating_star = '';
                        $comment_count = geodir_get_review_count_total($ID);

                        if (!$preview) {
                            $post_avgratings = geodir_get_post_rating($ID);

                            $rating_star = geodir_get_rating_stars($post_avgratings, $ID, false);

                            /**
                             * Filter to change rating stars
                             *
                             * You can use this filter to change Rating stars.
                             *
                             * @since 1.0.0
                             * @package GeoDirectory
                             * @param string $rating_star Rating stars.
                             * @param float $post_avgratings Average ratings of the post.
                             * @param int $ID The post ID.
                             */
                            $rating_star = apply_filters('geodir_review_rating_stars_on_infowindow', $rating_star, $post_avgratings, $ID);
                        }
                    }
                    ?>
                    <div class="geodir-bubble_desc">
                        <h4>
                            <a href="<?php if ($plink != '') {
                                echo $plink;
                            } else {
                                echo 'javascript:void(0);';
                            } ?>"><?php echo $title; ?></a>
                        </h4>
                        <?php
                        if ($gd_session->get('listing') && isset($post_preview) && $post_preview != '') {
                            $post_images = array();
                            if (!empty($postinfo_obj->post_images)) {
                                $post_images = explode(",", $postinfo_obj->post_images);
                            }

                            if (!empty($post_images)) {
                                ?>
                                <div class="geodir-bubble_image"><a href="<?php if ($plink != '') {
                                        echo $plink;
                                    } else {
                                        echo 'javascript:void(0);';
                                    } ?>"><img alt="bubble image" style="max-height:50px;"
                                               src="<?php echo $post_images[0]; ?>"/></a></div>
                            <?php
                            }else{
                                echo '<div class="geodir-bubble_image"></div>';
                            }
                        } else {
                            if ($image = geodir_show_featured_image($ID, 'widget-thumb', true, false, $postinfo_obj->featured_image)) {
                                ?>
                                <div class="geodir-bubble_image"><a href="<?php echo $plink; ?>"><?php echo $image; ?></a></div>
                            <?php
                            }else{
                                echo '<div class="geodir-bubble_image"></div>';
                            }
                        }
                        ?>
                        <div class="geodir-bubble-meta-side">
                            <?php
                            /**
                             * Fires before the meta info in the map info window.
                             *
                             * This can be used to add more info to the map info window before the normal meta info.
                             *
                             * @since 1.5.4
                             * @param int $ID The post id.
                             * @param object $postinfo_obj The posts info as an object.
                             * @param bool|string $post_preview True if currently in post preview page. Empty string if not.                           *
                             */
                            do_action('geodir_infowindow_meta_before', $ID, $postinfo_obj, $post_preview);

                            echo geodir_show_listing_info('mapbubble');

                            /**
                             * Fires after the meta info in the map info window.
                             *
                             * This can be used to add more info to the map info window after the normal meta info.
                             *
                             * @since 1.4.2
                             * @param object $postinfo_obj The posts info as an object.
                             * @param bool|string $post_preview True if currently in post preview page. Empty string if not.                           *
                             */
                            do_action('geodir_infowindow_meta_after',$postinfo_obj,$post_preview );
                            ?>
                        </div>
                        <?php
                        if ($ID) {
                            $post_author = isset($postinfo_obj->post_author) ? $postinfo_obj->post_author : get_post_field('post_author', $ID);
                            ?>
                            <div class="geodir-bubble-meta-fade"></div>
                            <div class="geodir-bubble-meta-bottom">
                                <?php if ($rating_star != '') { ?>
                                <span class="geodir-bubble-rating"><?php echo $rating_star;?></span>
                                <?php } ?>
                                <span class="geodir-bubble-fav"><?php echo geodir_favourite_html($post_author, $ID);?></span>
                                <span class="geodir-bubble-reviews">
                                    <a href="<?php echo get_comments_link($ID); ?>" class="geodir-pcomments"><i class="fa fa-comments"></i> <?php echo get_comments_number($ID); ?></a>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
            $html = ob_get_clean();
            /**
             * Filter to change infowindow html
             *
             * You can use this filter to change infowindow html.
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param string $html Infowindow html.
             * @param object $postinfo_obj The Post object.
             * @param bool|string $post_preview Is this a post preview?
             */
            $html = apply_filters('geodir_custom_infowindow_html', $html, $postinfo_obj, $post_preview);
            return $html;
        }
    }
}


/**
 * Default post status for new posts.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return string Returns the default post status for new posts. Ex: draft, publish etc.
 */
function geodir_new_post_default_status()
{

    $status = get_option( 'geodir_new_post_default_status' );

    if ( empty( $status ) ) {
        $status = 'publish';
    }

    /**
     * Filter the new post status.
     *
     * @since 1.6.23
     */
    return apply_filters( 'geodir_new_post_default_status', $status );

}

if (!function_exists('geodir_change_post_status')) {
    /**
     * Change post status of a post.
     *
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int|string $post_id The post ID.
     * @param string $status New post status. Ex: draft, publish etc.
     */
    function geodir_change_post_status($post_id = '', $status = '')
    {
        global $wpdb, $plugin_prefix;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . $table . " SET post_status=%s WHERE post_id=%d",
                array($status, $post_id)
            )
        );


    }
}

/**
 * Set post status of a post.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param int $pid The post ID.
 * @param string $status Post status. Ex: draft, publish etc.
 */
function geodir_set_post_status($pid, $status)
{
    if ($pid) {
        global $wpdb;
        $my_post = array();
        $my_post['post_status'] = $status;
        $my_post['ID'] = $pid;
        $last_postid = wp_update_post($my_post);
    }
}


/**
 * Update post status of a post.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $new_status New post status. Ex: draft, publish etc.
 * @param string $old_status Old post status. Ex: draft, publish etc.
 * @param object $post The post object.
 */
function geodir_update_poststatus($new_status, $old_status, $post)
{
    global $wpdb;

    $geodir_posttypes = geodir_get_posttypes();

    if (!wp_is_post_revision($post->ID) && in_array($post->post_type, $geodir_posttypes)) {

        geodir_change_post_status($post->ID, $new_status);
    }
}


if (!function_exists('geodir_update_listing_info')) {
    /**
     * Update post info.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $updatingpost The updating post ID.
     * @param int $temppost The temporary post ID.
     * @todo fix post_id variable
     */
    function geodir_update_listing_info($updatingpost, $temppost)
    {

        global $wpdb, $plugin_prefix;

        $post_type = get_post_type($post_id);

        $table = $plugin_prefix . $post_type . '_detail';

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . $table . " SET `post_id` = %d WHERE `post_id` = %d",
                array($updatingpost, $temppost)
            )
        );

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . GEODIR_ICON_TABLE . " SET `post_id` = %d WHERE `post_id` = %d",
                array($updatingpost, $temppost)
            )
        );

        /* Update Attachments*/

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . GEODIR_ATTACHMENT_TABLE . " SET `post_id` = %d WHERE `post_id` = %d",
                array($updatingpost, $temppost)
            )
        );

    }
}


if (!function_exists('geodir_delete_listing_info')) {
    /**
     * Delete Listing info from details table for the given post id.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param int $deleted_postid The post ID.
     * @param bool $force Optional. Do you want to force delete it? Default: false.
     * @return bool|void
     */
    function geodir_delete_listing_info($deleted_postid, $force = false)
    {
        global $wpdb, $plugin_prefix;

        // check for multisite deletions
        if (strpos($plugin_prefix, $wpdb->prefix) !== false) {
        } else {
            return;
        }

        $post_type = get_post_type($deleted_postid);

        $all_postypes = geodir_get_posttypes();

        if (!in_array($post_type, $all_postypes))
            return false;

        $table = $plugin_prefix . $post_type . '_detail';

        /* Delete custom post meta*/
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM " . $table . " WHERE `post_id` = %d",
                array($deleted_postid)
            )
        );

        /* Delete post map icons*/

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM " . GEODIR_ICON_TABLE . " WHERE `post_id` = %d",
                array($deleted_postid)
            )
        );

        /* Delete Attachments*/
        $postcurr_images = geodir_get_images($deleted_postid);

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE `post_id` = %d",
                array($deleted_postid)
            )
        );
        geodir_remove_attachments($postcurr_images);

    }
}


if (!function_exists('geodir_add_to_favorite')) {
    /**
     * This function would add listing to favorite listing.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $current_user Current user object.
     * @param int $post_id The post ID.
     */
    function geodir_add_to_favorite($post_id)
    {

        global $current_user;

        /**
         * Filter to modify "Unfavorite" text
         *
         * You can use this filter to rename "Unfavorite" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $remove_favourite_text = apply_filters('geodir_remove_favourite_text', REMOVE_FAVOURITE_TEXT);

        /**
         * Filter to modify "Remove from Favorites" text
         *
         * You can use this filter to rename "Remove from Favorites" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $unfavourite_text = apply_filters('geodir_unfavourite_text', UNFAVOURITE_TEXT);

        /**
         * Filter to modify "fa fa-heart" icon
         *
         * You can use this filter to change "fa fa-heart" icon to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $favourite_icon = apply_filters('geodir_favourite_icon', 'fa fa-heart');

        $user_meta_data = get_user_meta($current_user->data->ID, 'gd_user_favourite_post', true);
        $user_meta_data = !empty($user_meta_data) && is_array($user_meta_data) ? $user_meta_data : array();

        if (empty($user_meta_data) || (!empty($user_meta_data) && !in_array($post_id, $user_meta_data))) {
            $user_meta_data[] = $post_id;
        }

        update_user_meta($current_user->data->ID, 'gd_user_favourite_post', $user_meta_data);

        /**
         * Called before adding the post from favourites.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param int $post_id The post ID.
         */
        do_action('geodir_before_add_from_favorite', $post_id);

        echo '<a href="javascript:void(0);" title="' . $remove_favourite_text . '" class="geodir-removetofav-icon" onclick="javascript:addToFavourite(\'' . $post_id . '\',\'remove\');"><i class="'. $favourite_icon .'"></i> ' . $unfavourite_text . '</a>';

        /**
         * Called after adding the post from favourites.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param int $post_id The post ID.
         */
        do_action('geodir_after_add_from_favorite', $post_id);

    }
}

if (!function_exists('geodir_remove_from_favorite')) {
    /**
     * This function would remove the favourited property earlier.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $current_user Current user object.
     * @param int $post_id The post ID.
     */
    function geodir_remove_from_favorite($post_id)
    {
        global $current_user;

        /**
         * Filter to modify "Add to Favorites" text
         *
         * You can use this filter to rename "Add to Favorites" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $add_favourite_text = apply_filters('geodir_add_favourite_text', ADD_FAVOURITE_TEXT);

        /**
         * Filter to modify "Favourite" text
         *
         * You can use this filter to rename "Favourite" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $favourite_text = apply_filters('geodir_favourite_text', FAVOURITE_TEXT);

        /**
         * Filter to modify "fa fa-heart" icon
         *
         * You can use this filter to change "fa fa-heart" icon to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $favourite_icon = apply_filters('geodir_favourite_icon', 'fa fa-heart');

        $user_meta_data = array();
        $user_meta_data = get_user_meta($current_user->data->ID, 'gd_user_favourite_post', true);

        if (!empty($user_meta_data)) {

            if (($key = array_search($post_id, $user_meta_data)) !== false) {
                unset($user_meta_data[$key]);
            }

        }

        update_user_meta($current_user->data->ID, 'gd_user_favourite_post', $user_meta_data);

        /**
         * Called before removing the post from favourites.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param int $post_id The post ID.
         */
        do_action('geodir_before_remove_from_favorite', $post_id);

        echo '<a href="javascript:void(0);"  title="' . $add_favourite_text . '" class="geodir-addtofav-icon" onclick="javascript:addToFavourite(\'' . $post_id . '\',\'add\');"><i class="'. $favourite_icon .'"></i> ' . $favourite_text . '</a>';

        /**
         * Called after removing the post from favourites.
         *
         * @since 1.0.0
         * @package GeoDirectory
         * @param int $post_id The post ID.
         */
        do_action('geodir_after_remove_from_favorite', $post_id);

    }
}

if (!function_exists('geodir_favourite_html')) {
    /**
     * This function would display the html content for add to favorite or remove from favorite.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $current_user Current user object.
     * @global object $post The current post object.
     * @param int $user_id The user ID.
     * @param int $post_id The post ID.
     */
    function geodir_favourite_html($user_id, $post_id)
    {

        global $current_user, $post;

        /**
         * Filter to modify "Add to Favorites" text
         *
         * You can use this filter to rename "Add to Favorites" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $add_favourite_text = apply_filters('geodir_add_favourite_text', ADD_FAVOURITE_TEXT);

        /**
         * Filter to modify "Favourite" text
         *
         * You can use this filter to rename "Favourite" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $favourite_text = apply_filters('geodir_favourite_text', FAVOURITE_TEXT);

        /**
         * Filter to modify "Unfavorite" text
         *
         * You can use this filter to rename "Unfavorite" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $remove_favourite_text = apply_filters('geodir_remove_favourite_text', REMOVE_FAVOURITE_TEXT);

        /**
         * Filter to modify "Remove from Favorites" text
         *
         * You can use this filter to rename "Remove from Favorites" text to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $unfavourite_text = apply_filters('geodir_unfavourite_text', UNFAVOURITE_TEXT);

        /**
         * Filter to modify "fa fa-heart" icon
         *
         * You can use this filter to change "fa fa-heart" icon to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $favourite_icon = apply_filters('geodir_favourite_icon', 'fa fa-heart');

        /**
         * Filter to modify "fa fa-heart" icon for "remove from favorites" link
         *
         * You can use this filter to change "fa fa-heart" icon to something else.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        $unfavourite_icon = apply_filters('geodir_unfavourite_icon', 'fa fa-heart');

        $user_meta_data = '';
        if (isset($current_user->data->ID))
            $user_meta_data = get_user_meta($current_user->data->ID, 'gd_user_favourite_post', true);

        if (!empty($user_meta_data) && in_array($post_id, $user_meta_data)) {
            ?><span class="geodir-addtofav favorite_property_<?php echo $post_id;?>"  ><a
                class="geodir-removetofav-icon" href="javascript:void(0);"
                onclick="javascript:addToFavourite(<?php echo $post_id;?>,'remove');"
                title="<?php echo $remove_favourite_text;?>"><i class="<?php echo $unfavourite_icon; ?>"></i> <?php echo $unfavourite_text;?>
            </a>   </span><?php

        } else {

            if (!isset($current_user->data->ID) || $current_user->data->ID == '') {
                $script_text = 'javascript:window.location.href=\'' . geodir_login_url() . '\'';
            } else
                $script_text = 'javascript:addToFavourite(' . $post_id . ',\'add\')';

            ?><span class="geodir-addtofav favorite_property_<?php echo $post_id;?>"><a class="geodir-addtofav-icon"
                                                                                        href="javascript:void(0);"
                                                                                        onclick="<?php echo $script_text;?>"
                                                                                        title="<?php echo $add_favourite_text;?>"><i
                    class="<?php echo $favourite_icon; ?>"></i> <?php echo $favourite_text;?></a></span>
        <?php }
    }
}


/**
 * Get post count for the given category / term.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param object|array $term category / term object that need to be processed.
 * @return bool|int|null|string Post count.
 */
function geodir_get_cat_postcount($term = array())
{

    if (!empty($term)) {

        global $wpdb, $plugin_prefix;

        $where = '';
        $join = '';
        if (get_query_var('gd_country') != '' || get_query_var('gd_region') != '' || get_query_var('gd_city') != '') {
            $taxonomy_obj = get_taxonomy($term->taxonomy);

            $post_type = $taxonomy_obj->object_type[0];

            $table = $plugin_prefix . $post_type . '_detail';

            /**
             * Filter to modify the 'join' query
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param object|array $term category / term object that need to be processed.
             * @param string $join The join query.
             */
            $join = apply_filters('geodir_cat_post_count_join', $join, $term);

            /**
             * Filter to modify the 'where' query
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param object|array $term category / term object that need to be processed.
             * @param string $where The where query.
             */
            $where = apply_filters('geodir_cat_post_count_where', $where, $term);

            $count_query = "SELECT count(post_id) FROM
							" . $table . " as pd " . $join . "
							WHERE pd.post_status='publish' AND FIND_IN_SET('" . $term->term_id . "'," . $term->taxonomy . ") " . $where;

            $cat_post_count = $wpdb->get_var($count_query);
            if (empty($cat_post_count) || is_wp_error($cat_post_count))
                $cat_post_count = 0;

            return $cat_post_count;

        } else

            return $term->count;
    }
    return false;

}


/**
 * Allow add post type from front end
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_allow_post_type_frontend()
{
    $geodir_allow_posttype_frontend = get_option('geodir_allow_posttype_frontend');

    if (!is_admin() && isset($_REQUEST['listing_type'])
        && !empty($geodir_allow_posttype_frontend)
        && !in_array($_REQUEST['listing_type'], $geodir_allow_posttype_frontend)
    ) {

        wp_redirect(home_url());
        exit;

    }

}

/**
 * Changing excerpt length.
 *
 * @since 1.0.0
 * @since 1.5.7 Hide description when description word limit is 0.
 * @package GeoDirectory
 * @global object $wp_query WordPress Query object.
 * @global bool $geodir_is_widget_listing Is this a widget listing?
 * @param int $length Optional. Old length.
 * @return mixed|void Returns length.
 */
function geodir_excerpt_length($length)
{
    global $wp_query, $geodir_is_widget_listing;
	if ($geodir_is_widget_listing) {
		return $length;
	}
	
    if (isset($wp_query->query_vars['is_geodir_loop']) && $wp_query->query_vars['is_geodir_loop'] && get_option('geodir_desc_word_limit'))
        $length = get_option('geodir_desc_word_limit');
    elseif (get_query_var('excerpt_length'))
        $length = get_query_var('excerpt_length');

    if (geodir_is_page('author') && get_option('geodir_author_desc_word_limit'))
        $length = get_option('geodir_author_desc_word_limit');

    return $length;
}

/**
 * Changing excerpt more.
 *
 * @since 1.0.0
 * @since 1.5.4 Now only applied to GD post types.
 * @package GeoDirectory
 * @global object $post The current post object.
 * @param string $more Optional. Old string.
 * @return string Returns read more link.
 */
function geodir_excerpt_more($more)
{
    global $post;
    $all_postypes = geodir_get_posttypes();
    if (is_array($all_postypes) && in_array($post->post_type, $all_postypes)) {
        $out = ' <a class="excerpt-read-more" href="' .  get_permalink($post->ID) . '" title="' . get_the_title($post->ID) . '">';
        /**
         * Filter excerpt read more text.
         *
         * @since 1.0.0
         */
        $out .= apply_filters( 'geodir_max_excerpt_end', __( 'Read more [...]', 'geodirectory' ) );
        $out .= '</a>';
        return $out;
    }

    return $more;
}


/**
 * Update markers on category Edit.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param string $term_id The term ID as string.
 * @param int $tt_id The term taxonomy ID.
 * @param string $taxonomy The taxonomy slug.
 */
function geodir_update_markers_oncatedit($term_id, $tt_id, $taxonomy)
{
    global $plugin_prefix, $wpdb;

    $gd_taxonomies = geodir_get_taxonomies();

    if (is_array($gd_taxonomies) && in_array($taxonomy, $gd_taxonomies)) {

        $geodir_post_type = geodir_get_taxonomy_posttype($taxonomy);
        $table = $plugin_prefix . $geodir_post_type . '_detail';

        $path_parts = pathinfo($_REQUEST['ct_cat_icon']['src']);
        $term_icon = $path_parts['dirname'] . '/cat_icon_' . $term_id . '.png';

        $posts = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT post_id,post_title,post_latitude,post_longitude,default_category FROM " . $table . " WHERE FIND_IN_SET(%s,%1\$s ) ",
                array($term_id, $taxonomy)
            )
        );

        if (!empty($posts)):
            foreach ($posts as $post_obj) {

                $lat = $post_obj->post_latitude;
                $lng = $post_obj->post_longitude;

                $json = '{';
                $json .= '"id":"' . $post_obj->post_id . '",';
                $json .= '"lat_pos": "' . $lat . '",';
                $json .= '"long_pos": "' . $lng . '",';
                $json .= '"marker_id":"' . $post_obj->post_id . '_' . $term_id . '",';
                $json .= '"icon":"' . $term_icon . '",';
                $json .= '"group":"catgroup' . $term_id . '"';
                $json .= '}';

                if ($post_obj->default_category == $term_id) {

                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE " . $table . " SET marker_json = %s where post_id = %d",
                            array($json, $post_obj->post_id)
                        )
                    );
                }

                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE " . GEODIR_ICON_TABLE . " SET json = %s WHERE post_id = %d AND cat_id = %d",
                        array($json, $post_obj->post_id, $term_id)
                    )
                );

            }


        endif;

    }

}

/**
 * Get listing author id.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int|string $listing_id The post ID.
 * @return string|int The author ID.
 */
function geodir_get_listing_author($listing_id = '')
{
    if ($listing_id == '') {
        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
            $listing_id = $_REQUEST['pid'];
        }
    }
    $listing = get_post(strip_tags($listing_id));
    $listing_author_id = $listing->post_author;
    return $listing_author_id;
}


/**
 * Check whether a listing belongs to a user or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int|string $listing_id The post ID.
 * @param int $user_id The user ID.
 * @return bool
 */
function geodir_lisiting_belong_to_user($listing_id, $user_id)
{
    $listing_author_id = geodir_get_listing_author($listing_id);
    if ($listing_author_id == $user_id)
        return true;
    else
        return false;

}

/**
 * Check whether a listing belongs to current user or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $current_user Current user object.
 * @param int|string $listing_id The post ID.
 * @param bool $exclude_admin Optional. Do you want to exclude admin from the check?. Default true.
 * @return bool
 */
function geodir_listing_belong_to_current_user($listing_id = '', $exclude_admin = true)
{
    global $current_user;
    if ($exclude_admin) {
        foreach ($current_user->caps as $key => $caps) {
            if (geodir_strtolower($key) == 'administrator') {
                return true;
                break;
            }
        }
    }

    $result = geodir_lisiting_belong_to_user($listing_id, $current_user->ID);

    /**
     * Filter if the listing belongs to a user.
     *
     * @since 1.6.23
     * @param bool $result The result, true:false
     * @param int $listing_id The post ID.
     * @param int $current_user->ID The current user ID.
     * @param bool $exclude_admin Do you want to exclude admin from the check?. Default true.
     * return bool
     */
    return apply_filters('geodir_listing_belong_to_current_user',$result,$listing_id,$current_user->ID,$exclude_admin);
}


/**
 * Delete only supported attachments. This function is hooked to wp_delete_file filter.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $file. The File path.
 * @return string File path if valid. Else empty string.
 */
function geodir_only_supportable_attachments_remove($file)
{

    global $wpdb;

    $matches = array();

    $pattern = '/-\d+x\d+\./';
    preg_match($pattern, $file, $matches, PREG_OFFSET_CAPTURE);

    if (empty($matches))
        return '';
    else
        return $file;

}


/**
 * Set first image as post's featured image.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int $post_id The post ID.
 */
function geodir_set_wp_featured_image($post_id)
{

    global $wpdb, $plugin_prefix;
    $uploads = wp_upload_dir();
//	print_r($uploads ) ;
    $post_first_image = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE post_id = %d and menu_order = 1  ", array($post_id)
        )
    );

    $old_attachment_name = '';
    $post_thumbnail_id = '';
    if (has_post_thumbnail($post_id)) {

        if (has_post_thumbnail($post_id)) {

            $post_thumbnail_id = get_post_thumbnail_id($post_id);

            $old_attachment_name = basename(get_attached_file($post_thumbnail_id));

        }
    }

    if (!empty($post_first_image)) {

        $post_type = get_post_type($post_id);

        $table_name = $plugin_prefix . $post_type . '_detail';

        $wpdb->query("UPDATE " . $table_name . " SET featured_image='" . $post_first_image[0]->file . "' WHERE post_id =" . $post_id);

        $new_attachment_name = basename($post_first_image[0]->file);

        if (geodir_strtolower($new_attachment_name) != geodir_strtolower($old_attachment_name)) {

            if (has_post_thumbnail($post_id) && $post_thumbnail_id != '' && (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'delete')) {

                add_filter('wp_delete_file', 'geodir_only_supportable_attachments_remove');

                wp_delete_attachment($post_thumbnail_id);

            }
            $filename = $uploads['basedir'] . $post_first_image[0]->file;

            $attachment = array(
                'post_mime_type' => $post_first_image[0]->mime_type,
                'guid' => $uploads['baseurl'] . $post_first_image[0]->file,
                'post_parent' => $post_id,
                'post_title' => preg_replace('/\.[^.]+$/', '', $post_first_image[0]->title),
                'post_content' => ''
            );


            $id = wp_insert_attachment($attachment, $filename, $post_id);

            if (!is_wp_error($id)) {

                set_post_thumbnail($post_id, $id);

                require_once(ABSPATH . 'wp-admin/includes/image.php');
                wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $filename));

            }

        }

    } else {
        //set_post_thumbnail($post_id,-1);

        if (has_post_thumbnail($post_id) && $post_thumbnail_id != '' && (!isset($_REQUEST['action']) || $_REQUEST['action'] != 'delete'))
            wp_delete_attachment($post_thumbnail_id);

    }
}


/**
 * Function to copy custom meta info on WPML copy.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table_prefix WordPress Database Table prefix.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function gd_copy_original_translation()
{
    if (geodir_is_wpml()) {
        global $wpdb, $table_prefix, $plugin_prefix;
        $post_id = absint($_POST['post_id']);
        $upload_dir = wp_upload_dir();
        $post_type = get_post_type($_POST['post_id']);
        $table = $plugin_prefix . $post_type . '_detail';

        $post_arr = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $wpdb->posts p JOIN " . $table . " gd ON gd.post_id=p.ID WHERE p.ID=%d LIMIT 1",
            array($post_id)
        )
            , ARRAY_A);

        $arrImages = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM " . GEODIR_ATTACHMENT_TABLE . " WHERE mime_type like %s AND post_id = %d ORDER BY menu_order ASC, ID DESC ",
                array('%image%', $post_id)
            )
        );
        if ($arrImages) {
            $image_arr = array();
            foreach ($arrImages as $img) {
                $image_arr[] = $upload_dir['baseurl'] . $img->file;
            }
            $comma_separated = implode(",", $image_arr);
            $post_arr[0]['post_images'] = $comma_separated;
        }


        $cats = $post_arr[0][$post_arr[0]['post_type'] . 'category'];
        $cat_arr = array_filter(explode(",", $cats));
        $trans_cat = array();
        foreach ($cat_arr as $cat) {
            $trans_cat[] = geodir_wpml_object_id($cat, $post_arr[0]['post_type'] . 'category', false);
        }


        $post_arr[0]['categories'] = array_filter($trans_cat);
//print_r($image_arr);
        //print_r($arrImages);
        //echo $_REQUEST['lang'];
//print_r($post_arr);
//print_r($trans_cat);
        echo json_encode($post_arr[0]);

    }
    die();
}


add_action('wp_ajax_gd_copy_original_translation', 'gd_copy_original_translation');
//add_action('wp_ajax_nopriv_dc_update_profile', 'dc_update_profile_callback');


/**
 * Get custom fields info using listing post type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $listing_type The listing post type.
 * @return mixed|void|array custom fields info as an array.
 */
function geodir_get_custom_fields_type($listing_type = '')
{

    global $wpdb;

    if ($listing_type == '')
        $listing_type = 'gd_place';

    $fields_info = array();

    $get_data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT htmlvar_name, field_type, extra_fields FROM " . GEODIR_CUSTOM_FIELDS_TABLE . " WHERE post_type=%s AND is_active='1'",
            array($listing_type)
        )
    );

    if (!empty($get_data)) {

        foreach ($get_data as $data) {

            if ($data->field_type == 'address') {

                $extra_fields = unserialize($data->extra_fields);

                $prefix = $data->htmlvar_name . '_';

                $fields_info[$prefix . 'address'] = $data->field_type;

                if (isset($extra_fields['show_zip']) && $extra_fields['show_zip'])
                    $fields_info[$prefix . 'zip'] = $data->field_type;

            } else {

                $fields_info[$data->htmlvar_name] = $data->field_type;

            }

        }

    }

    /**
     * Filter to modify custom fields info using listing post type.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @return array $fields_info Custom fields info.
     * @param string $listing_type The listing post type.
     */
    return apply_filters('geodir_get_custom_fields_type', $fields_info, $listing_type);
}


/**
 * Called when post updated.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_ID The post ID.
 * @param object $post_after The post object after update.
 * @param object $post_before The post object before update.
 */
function geodir_function_post_updated($post_ID, $post_after, $post_before)
{
    $post_type = get_post_type($post_ID);

    if ($post_type != '' && in_array($post_type, geodir_get_posttypes())) {
        // send notification to client when post moves from draft to publish
        if (!empty($post_after->post_status) && $post_after->post_status == 'publish' && !empty($post_before->post_status) && ($post_before->post_status == 'draft' || $post_before->post_status == 'auto-draft' || $post_before->post_status == 'pending')) {
            $post_author_id = !empty($post_after->post_author) ? $post_after->post_author : NULL;
            $post_author_data = get_userdata($post_author_id);

            $to_name = geodir_get_client_name($post_author_id);

            $from_email = geodir_get_site_email_id();
            $from_name = get_site_emailName();
            $to_email = $post_author_data->user_email;

            if (!is_email($to_email) && !empty($post_author_data->user_email)) {
                $to_email = $post_author_data->user_email;
            }

            $message_type = 'listing_published';

            if (get_option('geodir_post_published_email_subject') == '') {
                update_option('geodir_post_published_email_subject', __('Listing Published Successfully', 'geodirectory'));
            }

            if (get_option('geodir_post_published_email_content') == '') {
                update_option('geodir_post_published_email_content', __("<p>Dear [#client_name#],</p><p>Your listing [#listing_link#] has been published. This email is just for your information.</p><p>[#listing_link#]</p><br><p>Thank you for your contribution.</p><p>[#site_name#]</p>", 'geodirectory'));
            }

            /**
             * Called before sending the email when listing gets published.
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param object $post_after The post object after update.
             * @param object $post_before The post object before update.
             */
            do_action('geodir_before_listing_published_email', $post_after, $post_before);
            if (is_email($to_email)) {
                geodir_sendEmail($from_email, $from_name, $to_email, $to_name, '', '', '', $message_type, $post_ID);
            }

            /**
             * Called after sending the email when listing gets published.
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param object $post_after The post object after update.
             * @param object $post_before The post object before update.
             */
            do_action('geodir_after_listing_published_email', $post_after, $post_before);
        }
    }
}

add_action('wp_head', 'geodir_fb_like_thumbnail');


/**
 * Adds the featured image to the place details page header so facebook can use it when sharing the link.
 *
 * @since 1.4.9
 * @since 1.6.22 Added image from location page.
 * @package GeoDirectory
 */
function geodir_fb_like_thumbnail(){

    $facebook_image = '';

    if(is_single()){// single post
        global $post;
        if(isset($post->featured_image) && $post->featured_image){
            $upload_dir = wp_upload_dir();
            $facebook_image = $upload_dir['baseurl'].$post->featured_image;

        }
    }elseif(geodir_is_page('location')){// location page
        if (function_exists('geodir_get_location_seo')) {
            $seo = geodir_get_location_seo();
            if (isset($seo->seo_image) && $seo->seo_image) {
                $image = wp_get_attachment_image_src($seo->seo_image, 'full');
                $facebook_image = isset($image[0]) ? $image[0] : '';
            }
        }

        if(!$facebook_image){
            global $post;

            if (has_post_thumbnail( $post->ID ) ){
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                $facebook_image = isset($image[0]) ? $image[0] : '';
            }
        }
    }


    /**
     * Filter the facebook share image.
     *
     * @since 1.6.22
     * @param string $facebook_image The image URL or blank.
     */
    $facebook_image = apply_filters('geodir_fb_share_image',$facebook_image);

    if($facebook_image){
        echo "\n\n<!-- GD Facebook Like Thumbnail -->\n<link rel=\"image_src\" href=\"$facebook_image\" />\n<!-- End GD Facebook Like Thumbnail -->\n\n";
    }


}

add_action( 'save_post', 'geodir_clear_map_cache_on_save', 10,2 );


/**
 * Delete the map cache files.
 *
 * @since 1.6.22
 */
function geodir_delete_map_cache(){
    $files = glob(realpath(dirname(__FILE__))."/map-functions/map-cache/*.json"); // get all file names
    foreach($files as $file){ // iterate files
        if(is_file($file))
            unlink($file); // delete file
    }
}


/**
 * Clear the map cache on post save.
 *
 * @since 1.6.22
 * @param object $post WordPress Post object.
 * @param int $post_id The post ID.
 */
function geodir_clear_map_cache_on_save($post_id, $post) {

    if(!get_option('geodir_enable_map_cache')){
        return;
    }

    if ( isset( $post->post_type ) && ( $post->post_type == 'nav_menu_item' || $post->post_type == 'page' || $post->post_type == 'post' ) ) {
        return;
    }

    $geodir_posttypes = geodir_get_posttypes();

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! wp_is_post_revision( $post_id ) && isset( $post->post_type ) && in_array( $post->post_type, $geodir_posttypes ) ) {
        geodir_delete_map_cache();
    }

}

/**
 * Set the post name for the pending listing.
 *
 * @since 1.6.22
 * @since 1.6.23 Fix post name for autosaved listing from backend.
 *
 * @param array $data    An array of slashed post data.
 * @param array $postarr An array of sanitized, but otherwise unmodified post data.
 * @return array Filtered post data.
 */
function geodir_fix_pending_listing_post_name( $data, $postarr ) {
    // Dont' update post name for autosaves
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $data;
    }

    if ( !empty( $data['post_name'] ) || empty( $data['post_status'] ) || empty( $data['post_type'] ) || empty( $data['post_title'] ) ) {
        return $data;
    }

    if ( ( 'draft' == $data['post_status'] || 'pending' == $data['post_status'] ) && in_array( $data['post_type'], geodir_get_posttypes() ) ) {
        $data['post_name'] = wp_unique_post_slug( sanitize_title( $data['post_title'] ), ( !empty( $postarr['ID'] ) ? $postarr['ID'] : 0 ), '', $data['post_type'], $data['post_parent'] );
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'geodir_fix_pending_listing_post_name', 10, 2 );