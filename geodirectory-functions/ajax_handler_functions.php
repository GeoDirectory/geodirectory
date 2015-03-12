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
 */
function geodir_on_wp_loaded()
{
    do_action('giodir_handle_request_plugins_loaded');
    global $wpdb;


    if (isset($_POST['sendact']) && $_POST['sendact'] == 'send_inqury') {
        geodir_send_inquiry($_REQUEST); // function in custom_functions.php

    } elseif (isset($_POST['sendact']) && $_POST['sendact'] == 'email_frnd') {
        geodir_send_friend($_REQUEST); // function in custom_functions.php

    }
}

/**
 * Geodirectory Post or Get request handler on init.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_on_init()
{

    do_action('giodir_handle_request');
    global $wpdb;

    if (isset($_REQUEST['geodir_signup'])) {
        geodir_user_signup();
    }


    if (get_option('geodir_allow_wpadmin') == '0' && is_user_logged_in() && !current_user_can('manage_options')) {
        show_admin_bar(false);
    }


    if (isset($_REQUEST['ptype']) && $_REQUEST['ptype'] == 'get_markers') {
        include_once(geodir_plugin_path() . '/geodirectory-functions/map-functions/get_markers.php');
        die;
    }

    if (isset($_REQUEST['ptype']) && $_REQUEST['ptype'] == 'ga') {
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
        geodir_getGoogleAnalytics($_REQUEST['ga_page'], $ga_start, $ga_end);
        die;
    }


}



/**
 * Processes GeoDirectory ajax url calls.
 *
 * @see geodir_get_ajax_url()
 * @since 1.0.0
 * @package GeoDirectory
 * @todo check if nonce is required here and if so add one.
 */
function geodir_ajax_handler()
{
    global $wpdb;

    if (isset($_REQUEST['gd_listing_view']) && $_REQUEST['gd_listing_view'] != '') {
        $_SESSION['gd_listing_view'] = $_REQUEST['gd_listing_view'];
        echo '1';
    }


    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'category_ajax') {

        if (isset($_REQUEST['main_catid']) && isset($_REQUEST['cat_tax']) && isset($_REQUEST['exclude']))
            geodir_addpost_categories_html($_REQUEST['cat_tax'], $_REQUEST['main_catid'], '', '', '', $_REQUEST['exclude']);

        elseif (isset($_REQUEST['catpid']) && isset($_REQUEST['cat_tax']))
            geodir_editpost_categories_html($_REQUEST['cat_tax'], $_REQUEST['catpid']);

    }

    if ((isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'admin_ajax') || isset($_REQUEST['create_field']) || isset($_REQUEST['sort_create_field'])) {
        if (current_user_can('manage_options')) {
            include_once(geodir_plugin_path() . '/geodirectory-admin/geodir_admin_ajax.php');
        } else {
            wp_redirect(home_url() . '/?geodir_signup=true');
            exit();
        }
    }

    if (isset($_REQUEST['geodir_autofill']) && $_REQUEST['geodir_autofill'] != '' && isset($_REQUEST['_wpnonce'])) {
        if (current_user_can('manage_options')) {
            switch ($_REQUEST['geodir_autofill']):
                case "geodir_dummy_delete" :
                    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'geodir_dummy_posts_delete_noncename'))
                        return;

                    if (isset($_REQUEST['posttype']))
                        do_action('geodir_delete_dummy_posts_' . $_REQUEST['posttype']);
                    break;
                case "geodir_dummy_insert" :
                    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'geodir_dummy_posts_insert_noncename'))
                        return;

                    global $dummy_post_index, $city_bound_lat1, $city_bound_lng1, $city_bound_lat2, $city_bound_lng2;
                    $dummy_post_index = $_REQUEST['insert_dummy_post_index'];
                    $city_bound_lat1 = $_REQUEST['city_bound_lat1'];
                    $city_bound_lng1 = $_REQUEST['city_bound_lng1'];
                    $city_bound_lat2 = $_REQUEST['city_bound_lat2'];
                    $city_bound_lng2 = $_REQUEST['city_bound_lng2'];

                    if (isset($_REQUEST['posttype']))
                        do_action('geodir_insert_dummy_posts_' . $_REQUEST['posttype']);

                    break;
            endswitch;
        } else {
            wp_redirect(home_url() . '/?geodir_signup=true');
            exit();
        }
    }

    if (isset($_REQUEST['geodir_import_data']) && $_REQUEST['geodir_import_data'] != '') {
        if (current_user_can('manage_options')) {
            geodir_import_data();
        } else {
            wp_redirect(home_url() . '/?geodir_signup=true');
            exit();
        }
    }

    if (isset($_REQUEST['popuptype']) && $_REQUEST['popuptype'] != '' && isset($_REQUEST['post_id']) && $_REQUEST['post_id'] != '') {

        if ($_REQUEST['popuptype'] == 'b_send_inquiry' || $_REQUEST['popuptype'] == 'b_sendtofriend')
            require_once(geodir_plugin_path() . '/geodirectory-templates/popup-forms.php');

        exit;
    }

    /*if(isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'filter_ajax'){
        include_once ( geodir_plugin_path() . '/geodirectory-templates/advance-search-form.php');
    }*/

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'map_ajax') {
        include_once(geodir_plugin_path() . '/geodirectory-functions/map-functions/get_markers.php');
    }


    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'favorite') {
        if (is_user_logged_in()) {
            switch ($_REQUEST['ajax_action']):
                case "add" :
                    geodir_add_to_favorite($_REQUEST['pid']);
                    break;
                case "remove" :
                    geodir_remove_from_favorite($_REQUEST['pid']);
                    break;
            endswitch;
        } else {
            wp_redirect(home_url() . '/?geodir_signup=true');
            exit();
        }
    }

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'add_listing') {

        $is_current_user_owner = true;
        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
            $is_current_user_owner = geodir_listing_belong_to_current_user($_REQUEST['pid']);
        }

        $request = isset($_SESSION['listing']) ? unserialize($_SESSION['listing']) : '';

        if (is_user_logged_in() && $is_current_user_owner) {

            switch ($_REQUEST['ajax_action']):
                case "add":
                case "update":

                    if (isset($request['geodir_spamblocker']) && $request['geodir_spamblocker'] == '64' && isset($request['geodir_filled_by_spam_bot']) && $request['geodir_filled_by_spam_bot'] == '') {

                        $last_id = geodir_save_listing();

                        if ($last_id) {
                            //$redirect_to = get_permalink( $last_id );
                            $redirect_to = geodir_getlink(get_permalink(get_option('geodir_success_page')), array('pid' => $last_id));

                        } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                            $redirect_to = get_permalink(get_option('geodir_add_listing_page'));
                            $redirect_to = geodir_getlink($redirect_to, array('pid' => $post->pid), false);
                        } else
                            $redirect_to = get_permalink(get_option('geodir_add_listing_page'));

                        wp_redirect($redirect_to);

                    } else {

                        if (isset($_SESSION['listing']))
                            unset($_SESSION['listing']);
                        wp_redirect(home_url());

                    }

                    break;
                case "cancel" :

                    unset($_SESSION['listing']);

                    if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '' && get_permalink($_REQUEST['pid']))
                        wp_redirect(get_permalink($_REQUEST['pid']));
                    else {
                        geodir_remove_temp_images();
                        wp_redirect(geodir_getlink(get_permalink(get_option('geodir_add_listing_page')), array('listing_type' => $_REQUEST['listing_type'])));
                    }

                    break;

                case "publish" :

                    if (isset($request['geodir_spamblocker']) && $request['geodir_spamblocker'] == '64' && isset($request['geodir_filled_by_spam_bot']) && $request['geodir_filled_by_spam_bot'] == '') {

                        if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {

                            $new_post = array();
                            $new_post['ID'] = $_REQUEST['pid'];
                            //$new_post['post_status'] = 'publish';

                            $lastid = wp_update_post($new_post);

                            if (isset($_SESSION['listing']))unset($_SESSION['listing']);
                            wp_redirect(get_permalink($lastid));
                        } else {

                            $last_id = geodir_save_listing();

                            if ($last_id) {
                                //$redirect_to = get_permalink( $last_id );
                                $redirect_to = geodir_getlink(get_permalink(get_option('geodir_success_page')), array('pid' => $last_id));
                            } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
                                $redirect_to = get_permalink(get_option('geodir_add_listing_page'));
                                $redirect_to = geodir_getlink($redirect_to, array('pid' => $post->pid), false);
                            } else
                                $redirect_to = get_permalink(get_option('geodir_add_listing_page'));

                            if (isset($_SESSION['listing']))unset($_SESSION['listing']);
                            wp_redirect($redirect_to);
                        }

                    } else {

                        if (isset($_SESSION['listing']))unset($_SESSION['listing']);
                        wp_redirect(home_url());

                    }

                    break;
                case "delete" :
                    if (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {

                        global $current_user;
                        get_currentuserinfo();
                        $post_type = get_post_type($_REQUEST['pid']);
                        $lastid = wp_delete_post($_REQUEST['pid']);
                        if ($lastid && !is_wp_error($lastid))
                            wp_redirect($_SERVER['HTTP_REFERER']);

                        //wp_redirect( geodir_getlink(get_author_posts_url($current_user->ID),array('geodir_dashbord'=>'true','stype'=>$post_type ),false) );
                    }
                    break;
            endswitch;

        } else {
            wp_redirect(home_url() . '/?geodir_signup=true');
            exit();
        }

    }

    if (isset($_REQUEST['geodir_ajax']) && $_REQUEST['geodir_ajax'] == 'user_login') {

        include_once(geodir_plugin_path() . '/geodirectory-functions/geodirectory_reg.php');
    }


    if (isset($_REQUEST['ajax_action']) && $_REQUEST['ajax_action'] == 'geodir_get_term_list') {
        $terms_o = get_terms(sanitize_text_field($_REQUEST['term']));
        $terms = geodir_sort_terms($terms_o, 'count');
        geodir_helper_cat_list_output($terms, intval($_REQUEST['limit']));
        exit();

    }


    die;


}



