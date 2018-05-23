<?php
/**
 * Functions that are called via ajax.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */


/**
 * Geodirectory Post or Get request handler on wp_loaded.
 *
 * @since 1.3.5
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_on_wp_loaded()
{
    /**
     * Called on the wp_loaded WP hook and used to send the send inquiry forms.
     *
     * @since 1.0.0
     */
    do_action('giodir_handle_request_plugins_loaded');
    global $wpdb;


    if (isset($_POST['sendact']) && $_POST['sendact'] == 'send_inqury') {
        geodir_send_inquiry($_REQUEST); // function in custom_functions.php

    }

}

/**
 * Geodirectory Post or Get request handler on wp_loaded.
 *
 * @since 1.3.5
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_on_wp()
{
    if(geodir_is_page('login')) {
        geodir_user_signup();
    }

}

/**
 * Geodirectory Post or Get request handler on init.
 *
 * @since 1.0.0
 * @since 1.6.18 Option added to disable overwrite by Yoast SEO titles & metas on GD pages.
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_on_init()
{
    /**
     * Called on the wp_init WP hook at the start of the geodir_on_init() function.
     *
     * @since 1.0.0
     */
    do_action('giodir_handle_request');
    global $wpdb;




    if (get_option('geodir_allow_wpadmin') == '0' && is_user_logged_in() && !current_user_can('manage_options') && !class_exists('BuddyPress')) {
        show_admin_bar(false);
    }


    if (isset($_REQUEST['ptype']) && $_REQUEST['ptype'] == 'get_markers') {
        /**
         * Contains map marker functions.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        include_once(geodir_plugin_path() . '/geodirectory-functions/map-functions/get_markers.php');
        die;
    }
    
    if ( class_exists( 'WPSEO_Frontend' ) && !is_admin() ) {
        add_action( 'template_redirect', 'geodir_remove_yoast_seo_metas' );
    }
}


/**
 * Processes GeoDirectory ajax url calls.
 *
 * @see geodir_get_ajax_url()
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $current_user Current user object.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @todo check if nonce is required here and if so add one.
 */
function geodir_ajax_handler() {
    global $wpdb, $gd_session,$post;

    if (isset($_REQUEST['gd_listing_view']) && $_REQUEST['gd_listing_view'] != '') {
		$gd_session->set('gd_listing_view', $_REQUEST['gd_listing_view']);
        echo '1';
    }

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'category_ajax') {
        if (isset($_REQUEST['main_catid']) && isset($_REQUEST['cat_tax']) && isset($_REQUEST['exclude']))
            geodir_addpost_categories_html($_REQUEST['cat_tax'], $_REQUEST['main_catid'], '', '', '', $_REQUEST['exclude']);
        else if (isset($_REQUEST['catpid']) && isset($_REQUEST['cat_tax']))
            geodir_editpost_categories_html($_REQUEST['cat_tax'], $_REQUEST['catpid']);
    }

    if ((isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'admin_ajax') || isset($_REQUEST['create_field']) || isset($_REQUEST['sort_create_field'])) {
        if (current_user_can('manage_options')) {
            /**
             * Contains admin ajax handling functions.
             *
             * @since 1.0.0
             * @package GeoDirectory
             */
            include_once(geodir_plugin_path() . '/geodirectory-admin/geodir_admin_ajax.php');
        } else {
            wp_redirect(geodir_login_url());
            gd_die();
        }
    }

    if (isset($_REQUEST['geodir_autofill']) && $_REQUEST['geodir_autofill'] != '' && isset($_REQUEST['_wpnonce'])) {
        if (current_user_can('manage_options')) {
            switch ($_REQUEST['geodir_autofill']):
                case "geodir_dummy_delete" :
                    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'geodir_dummy_posts_insert_noncename'))
                        return;

                    $datatype = isset($_REQUEST['datatype']) ? sanitize_key($_REQUEST['datatype']) : '';
                    if (isset($_REQUEST['posttype']))
                        /**
                         * Used to delete the dummy post data per post type.
                         *
                         * Uses dynamic hook, geodir_delete_dummy_posts_$_REQUEST['posttype'].
                         *
                         * @since 1.6.11
                         * @param string $posttype The post type to insert.
                         * @param string $datatype The type of dummy data to insert.
                         */
                        do_action('geodir_delete_dummy_posts' ,sanitize_key($_REQUEST['posttype']),$datatype);
                    break;
                case "geodir_dummy_insert" :
                    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'geodir_dummy_posts_insert_noncename'))
                        return;

                    global $city_bound_lat1, $city_bound_lng1, $city_bound_lat2, $city_bound_lng2;
                    $city_bound_lat1 = $_REQUEST['city_bound_lat1'];
                    $city_bound_lng1 = $_REQUEST['city_bound_lng1'];
                    $city_bound_lat2 = $_REQUEST['city_bound_lat2'];
                    $city_bound_lng2 = $_REQUEST['city_bound_lng2'];

                    if (isset($_REQUEST['posttype'])){
                        /**
                         * Used to insert the dummy post data per post type.
                         *
                         * Uses dynamic hook, geodir_insert_dummy_posts_$_REQUEST['posttype'].
                         *
                         * @since 1.6.11
                         * @param string $posttype The post type to insert.
                         * @param string $datatype The type of dummy data to insert.
                         * @param int $post_index The item number to insert.
                         */
                        do_action('geodir_insert_dummy_posts',sanitize_key($_REQUEST['posttype']),sanitize_key($_REQUEST['datatype']),absint($_REQUEST['insert_dummy_post_index']));
                    }


                    break;
            endswitch;
        } else {
            wp_redirect(geodir_login_url());
            exit();
        }
    }

    if (isset($_REQUEST['popuptype']) && $_REQUEST['popuptype'] != '' && isset($_REQUEST['post_id']) && $_REQUEST['post_id'] != '') {

        if ($_REQUEST['popuptype'] == 'b_send_inquiry') {
            $template = locate_template(array("geodirectory/popup-forms.php"));
            if (!$template) {
                $template = geodir_plugin_path() . '/geodirectory-templates/popup-forms.php';
            }
            require_once($template);
        }

        gd_die();
    }

    /*if(isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'filter_ajax'){
        include_once ( geodir_plugin_path() . '/geodirectory-templates/advance-search-form.php');
    }*/

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'map_ajax') {
        /**
         * Contains map marker functions.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        include_once(geodir_plugin_path() . '/geodirectory-functions/map-functions/get_markers.php');
    }

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'favorite') {
        if (is_user_logged_in()) {
            switch ($_REQUEST['ajax_action']):
                case "add" :
                    geodir_add_to_favorite((int)$_REQUEST['pid']);
                    break;
                case "remove" :
                    geodir_remove_from_favorite((int)$_REQUEST['pid']);
                    break;
            endswitch;
        } else {
            wp_redirect(geodir_login_url());
            exit();
        }
    }

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'add_listing') {

        $is_current_user_owner = true;
        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
            $is_current_user_owner = geodir_listing_belong_to_current_user((int)$_REQUEST['pid']);
        }

        $request = $gd_session->get('listing');

        if (is_user_logged_in() && $is_current_user_owner) {

            switch ($_REQUEST['ajax_action']):
                case "add":
                case "update":

                    if (isset($request['geodir_spamblocker']) && $request['geodir_spamblocker'] == '64' && isset($request['geodir_filled_by_spam_bot']) && $request['geodir_filled_by_spam_bot'] == '') {
                        $last_id = geodir_save_listing();

                        if ($last_id) {
                            //$redirect_to = get_permalink( $last_id );
                            $redirect_to = geodir_getlink(get_permalink(geodir_success_page_id()), array('pid' => $last_id));

                        } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                            $redirect_to = get_permalink(geodir_add_listing_page_id());
                            $redirect_to = geodir_getlink($redirect_to, array('pid' => $post->pid), false);
                        } else
                            $redirect_to = get_permalink(geodir_add_listing_page_id());

                        wp_redirect($redirect_to);
                    } else {
                        $gd_session->un_set('listing');
                        wp_redirect(home_url());
                    }

                    break;
                case "cancel" :

                    $gd_session->un_set('listing');

                    if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '' && get_permalink($_REQUEST['pid']))
                        wp_redirect(get_permalink($_REQUEST['pid']));
                    else {
                        geodir_remove_temp_images();
                        wp_redirect(geodir_getlink(get_permalink(geodir_add_listing_page_id()), array('listing_type' => $_REQUEST['listing_type'])));
                    }

                    break;

                case "publish" :

                    if (isset($request['geodir_spamblocker']) && $request['geodir_spamblocker'] == '64' && isset($request['geodir_filled_by_spam_bot']) && $request['geodir_filled_by_spam_bot'] == '') {

                        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                            $new_post = array();
                            $new_post['ID'] = $_REQUEST['pid'];

                            $lastid = wp_update_post($new_post);

                            $gd_session->un_set('listing');
                            wp_redirect(get_permalink($lastid));
                        } else {
                            $last_id = geodir_save_listing();

                            if ($last_id) {
                                //$redirect_to = get_permalink( $last_id );
                                $redirect_to = geodir_getlink(get_permalink(geodir_success_page_id()), array('pid' => $last_id));
                            } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                                $redirect_to = get_permalink(geodir_add_listing_page_id());
                                $redirect_to = geodir_getlink($redirect_to, array('pid' => $post->pid), false);
                            } else
                                $redirect_to = get_permalink(geodir_add_listing_page_id());

                            $gd_session->un_set('listing');
                            wp_redirect($redirect_to);
                        }
                    } else {
                        $gd_session->un_set('listing');
                        wp_redirect(home_url());
                    }

                    break;
                case "delete" :
                    if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                        global $current_user;

                        if (get_option('geodir_disable_perm_delete')) {
                            $lastid = wp_trash_post($_REQUEST['pid']);
                        } else {
                            $lastid = wp_delete_post($_REQUEST['pid']);
                        }

                        if ($lastid && !is_wp_error($lastid))
                            wp_redirect($_SERVER['HTTP_REFERER']);

                        //wp_redirect( geodir_getlink(get_author_posts_url($current_user->ID),array('geodir_dashbord'=>'true','stype'=>$post_type ),false) );
                    }
                    break;
            endswitch;

            $gd_session->un_set('listing');
        } else {
            $gd_session->un_set('listing');
            wp_redirect(geodir_login_url());
            exit();
        }
    }

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'user_login') {
        /**
         * Contains registration and login functions.
         * @todo Fix the file path.
         *
         * @since 1.0.0
         * @package GeoDirectory
         */
        include_once(geodir_plugin_path() . '/geodirectory-functions/geodirectory_reg.php');
    }

    if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'geodir_get_term_list') {
        $args = array('taxonomy' => sanitize_text_field($_REQUEST['term']));
        if (!empty($_REQUEST['parent_only'])) {
            $args['parent'] = 0;
        }
        $terms_o = get_terms($args);

        // Skip terms which has no listing
        if (!empty($terms_o)) {
            $filter_terms = array();

            foreach ($terms_o as $term) {
                if (isset($term->count) && $term->count > 0) {
                    $filter_terms[] = $term;
                }
            }
            $terms_o = $filter_terms;
        }

        $terms = geodir_sort_terms($terms_o, 'count');
        geodir_helper_cat_list_output($terms, intval($_REQUEST['limit']));
        exit();
    }
    
    if ( !empty($_REQUEST['geodir_ajax'] ) && $_REQUEST['geodir_ajax'] == 'duplicate' && geodir_is_wpml() ) {
        if ( !empty( $_REQUEST['_nonce'] ) && wp_verify_nonce( $_REQUEST['_nonce'], 'geodir_duplicate_nonce' ) ) {
            $json = array();
            $json['success'] = false;
            
            $post_id = !empty( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : 0;
            $langs = !empty( $_REQUEST['dups'] ) ? explode( ',', sanitize_text_field( $_REQUEST['dups'] ) ) : array();
            
            if ( !empty( $post_id ) && !empty( $langs ) ) {
                if ( geodir_wpml_allowed_to_duplicate( $post_id ) ) {
                    global $sitepress;
                    
                    $element_type = 'post_' . get_post_type( $post_id );
                    $master_post_id = $sitepress->get_original_element_id( $post_id, $element_type );
                    
                    if ( $master_post_id == $post_id ) {
                        $_REQUEST['icl_ajx_action'] = 'make_duplicates';
                        
                        foreach ( $langs as $lang ) {
                            $return = $sitepress->make_duplicate( $master_post_id, $lang );
                        }
                        $json['success'] = true;
                    } else {
                        $json['error'] = __( 'Translation can be done from original listing only.', 'geodirectory' );
                    }
                } else {
                    $json['error'] = __( 'You are not allowed to translate this listing.', 'geodirectory' );
                }
            }
            
            wp_send_json( $json );
        }
    }

    gd_die();
}


function geodir_show_ga_stats(){
    if (isset($_REQUEST['ga_start'])) {
        $ga_start = $_REQUEST['ga_start'];
    } else {
        $ga_start = '';
    }
    if (isset($_REQUEST['ga_end'])) {
        $ga_end = $_REQUEST['ga_end'];
    } else {
        $ga_end = '';
    }
    try {
        geodir_getGoogleAnalytics($_REQUEST['ga_page'], $ga_start, $ga_end);
    } catch (Exception $e) {
        geodir_error_log( wp_sprintf( __( 'GD Google Analytics API Error(%s) : %s', 'geodirectory' ), $e->getCode(), $e->getMessage() ) );
    }
    die;
}
add_action( 'wp_ajax_gdga', 'geodir_show_ga_stats' );
add_action( 'wp_ajax_nopriv_gdga', 'geodir_show_ga_stats' );