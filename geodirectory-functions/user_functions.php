<?php

/**
 * Get user's favorite listing count.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $user_id Optional. The user id to get, defaults to current user.
 * @global object $wpdb WordPress Database object.
 * @global object $current_user Current user object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @return array User listing count for each post type.
 */
function geodir_user_favourite_listing_count($user_id=false)
{
    global $wpdb, $plugin_prefix, $current_user;

    if(!$user_id){$user_id = $current_user->ID;}
    if(!$user_id){return array();}

    $site_id = '';
    if ( is_multisite() ) {
        $blog_id = get_current_blog_id();
        if($blog_id && $blog_id!='1'){$site_id  = '_' . $blog_id ;}
    }

    $user_favorites = geodir_get_user_favourites($user_id);
    $all_posts = get_option('geodir_favorite_link_user_dashboard');

    $user_listing = array();
    if (is_array($all_posts) && !empty($all_posts) && is_array($user_favorites) && !empty($user_favorites)) {
        $user_favorites = "'" . implode("','", $user_favorites) . "'";

        foreach ($all_posts as $ptype) {
            $total_posts = $wpdb->get_var("SELECT count( ID ) FROM " . $wpdb->prefix . "posts WHERE  post_type='" . $ptype . "' AND post_status = 'publish' AND ID IN (" . $user_favorites . ")");

            if ($total_posts > 0) {
                $user_listing[$ptype] = $total_posts;
            }
        }
    }

    return $user_listing;
}



/**
 * User functions.
 *
 * @since 1.5.9
 * @package GeoDirectory
 */

function geodir_user_show_favourites($user_id='',$output_type='select'){
    // My Favourites in Dashboard
    $show_favorite_link_user_dashboard = get_option('geodir_favorite_link_user_dashboard');
    $user_favourite = geodir_user_favourite_listing_count($user_id);

    if (!empty($show_favorite_link_user_dashboard) && !empty($user_favourite)) {
        $favourite_links = '';
        $post_types = geodir_get_posttypes('object');

        $author_link = get_author_posts_url($user_id);
        $author_link = geodir_getlink($author_link, array('geodir_dashbord' => 'true'), false);

        foreach ($post_types as $key => $postobj) {
            if (in_array($key, $show_favorite_link_user_dashboard) && array_key_exists($key, $user_favourite)) {
                $name = $postobj->labels->name;
                $post_type_link = geodir_getlink($author_link, array('stype' => $key, 'list' => 'favourite'), false);

                $selected = '';

                if (isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite' && isset($_REQUEST['stype']) && $_REQUEST['stype'] == $key && isset($_REQUEST['geodir_dashbord'])) {
                    $selected = 'selected="selected"';
                }
                /**
                 * Filter favorite listing link.
                 *
                 * @since 1.0.0
                 * @param string $post_type_link Favorite listing link.
                 * @param string $key Favorite listing array key.
                 * @param int $current_user->ID Current user ID.
                 */
                $post_type_link = apply_filters('geodir_dashboard_link_favorite_listing', $post_type_link, $key, $user_id);

                if($output_type=='select'){
                    $favourite_links .= '<option ' . $selected . ' value="' . $post_type_link . '">' . __(geodir_utf8_ucfirst($name), 'geodirectory') . '</option>';
                }elseif($output_type=='link'){
                    $favourite_links[] = '<a href="' . $post_type_link . '">' . __(geodir_utf8_ucfirst($name), 'geodirectory') . '</a>';
                }


            }
        }

        if ($favourite_links != '') {
            $user = get_user_by( 'ID', $user_id );
            if($output_type=='select') {
                ?>
                <li>
                    <select id="geodir_my_favourites" class="chosen_select" onchange="window.location.href=this.value"
                            option-autoredirect="1" name="geodir_my_favourites" option-ajaxchosen="false"
                            data-placeholder="<?php echo esc_attr(__('My Favorites', 'geodirectory')); ?>">
                        <option value="" disabled="disabled" selected="selected"
                                style='display:none;'><?php echo geodir_utf8_ucfirst(esc_attr(sprintf(__("%s's Favorites", 'geodirectory'), $user->user_nicename))); ?></option>
                        <?php echo $favourite_links; ?>
                    </select>
                </li>
            <?php
            }elseif($output_type=='link'){
                if(!empty($favourite_links)){
                    echo implode(" | ",$favourite_links);
                }

            }
        }
    }
}



function geodir_user_show_listings($user_id='',$output_type='select'){

    $show_listing_link_user_dashboard = get_option('geodir_listing_link_user_dashboard');
    $user_listing = geodir_user_post_listing_count($user_id);

    if (!empty($show_listing_link_user_dashboard) && !empty($user_listing)) {
        $listing_links = array();

        $post_types = geodir_get_posttypes('object');

        $author_link = get_author_posts_url($user_id);
        $author_link = geodir_getlink($author_link, array('geodir_dashbord' => 'true'), false);

        foreach ($post_types as $key => $postobj) {
            if (in_array($key, $show_listing_link_user_dashboard) && array_key_exists($key, $user_listing)) {
                $name = $postobj->labels->name;
                $listing_link = geodir_getlink($author_link, array('stype' => $key), false);

                $selected = '';
                if (!isset($_REQUEST['list']) && isset($_REQUEST['geodir_dashbord']) && isset($_REQUEST['stype']) && $_REQUEST['stype'] == $key) {
                    $selected = 'selected="selected"';
                }

                /**
                 * Filter my listing link.
                 *
                 * @since 1.0.0
                 * @param string $listing_link My listing link.
                 * @param string $key My listing array key.
                 * @param int $current_user->ID Current user ID.
                 */
                $listing_link = apply_filters('geodir_dashboard_link_my_listing', $listing_link, $key, $user_id);
                if($output_type=='select') {
                    $listing_links .= '<option ' . $selected . ' value="' . $listing_link . '">' . __(geodir_utf8_ucfirst($name), 'geodirectory') . '</option>';
                }elseif($output_type=='link'){
                    $listing_links[] = '<a href="' .$listing_link . '">' . __(geodir_utf8_ucfirst($name), 'geodirectory') . '</a>';
                }
            }
        }

        if ($listing_links != '') {
            $user = get_user_by( 'ID', $user_id );
            if($output_type=='select') {
                ?>
                <li>
                    <select id="geodir_my_listings" class="chosen_select" onchange="window.location.href=this.value"
                            option-autoredirect="1" name="geodir_my_listings" option-ajaxchosen="false"
                            data-placeholder="<?php echo esc_attr(__('My Listings', 'geodirectory')); ?>">
                        <option value="" disabled="disabled" selected="selected"
                                style='display:none;'><?php echo geodir_utf8_ucfirst(esc_attr(sprintf(__("%s's Listings", 'geodirectory'), $user->user_nicename))); ?></option>
                        <?php echo $listing_links; ?>
                    </select>
                </li>
            <?php
            }elseif($output_type=='link'){
                if(!empty($listing_links )){
                    echo implode(" | ",$listing_links );
                }

            }
        }
    }

}

/**
 * Get the array of user favourites.
 *
 * @param string $user_id
 * @since 1.6.24
 * @return mixed
 */
function geodir_get_user_favourites($user_id=''){
    if(!$user_id){$user_id = get_current_user_id();}
    $site_id = '';
    if ( is_multisite() ) {
        $blog_id = get_current_blog_id();
        if($blog_id && $blog_id!='1'){$site_id  = '_' . $blog_id ;}
    }

    return get_user_meta($user_id, 'gd_user_favourite_post'.$site_id, true);
}