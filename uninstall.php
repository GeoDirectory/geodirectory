<?php
/**
 * Uninstall GeoDirectory
 *
 * Uninstalling GeoDirectory deletes data, tables and options.
 *
 * @package GeoDirectory_Advance_Search_Filters
 * @since 1.6.9
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

function geodir_uninstall(){
    global $wpdb;

    // Pages.
    wp_delete_post( get_option('geodir_location_page'), true );
    wp_delete_post( get_option('geodir_success_page'), true );
    wp_delete_post( get_option('geodir_preview_page'), true );
    wp_delete_post( get_option('geodir_add_listing_page'), true );
    wp_delete_post( get_option('geodir_home_page'), true );
    wp_delete_post( get_option('geodir_info_page'), true );
    wp_delete_post( get_option('geodir_login_page'), true );

    // Delete usermeta.
    $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'gd\_user\_favourite\_post%';" );

    // remove post types
    $post_types = get_option('geodir_post_types');

    // Delete posts.
    if ( ! empty( $post_types ) ) {
        foreach ( $post_types as $post_type => $data ) {
            $wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type LIKE '{$post_type}';" );

            // Delete post menu
            $wpdb->query( "DELETE posts FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} meta ON posts.ID = meta.post_id WHERE posts.post_type= 'nav_menu_item' AND meta.meta_key = '_menu_item_object' AND meta.meta_value = '{$post_type}';" );
            $wpdb->query( "DELETE posts FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} meta ON posts.ID = meta.post_id WHERE posts.post_type= 'nav_menu_item' AND meta.meta_key = '_menu_item_url' AND meta.meta_value LIKE '%listing_type={$post_type}%';" );
        }
    }

    // Delete post meta.
    $wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

    // Delete orphan attachment.
    $wpdb->query( "DELETE post1 FROM {$wpdb->posts} post1 LEFT JOIN {$wpdb->posts} post2 ON post1.post_parent = post2.ID WHERE post1.post_parent > 0 AND post1.post_type = 'attachment' AND post2.ID IS NULL;" );

    // Delete term taxonomies.
    if ( ! empty( $post_types ) ) {
        foreach ( $post_types as $post_type => $data  ) {
            $wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy LIKE '{$post_type}category' OR taxonomy LIKE '{$post_type}_tags';" );
        }
    }

    // Delete orphan relationships.
    $wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

    // Delete orphan terms.
    $wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

    // Delete orphan term meta.
    $wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

    // Comments
    $wpdb->query( "DELETE comments FROM {$wpdb->comments} AS comments LEFT JOIN {$wpdb->posts} AS posts ON posts.ID = comments.comment_post_ID WHERE posts.ID IS NULL;" );
    $wpdb->query( "DELETE meta FROM {$wpdb->commentmeta} meta LEFT JOIN {$wpdb->comments} comments ON comments.comment_ID = meta.comment_id WHERE comments.comment_ID IS NULL;" );

    // Options
    // Delete settings
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'geodir_settings' OR option_name LIKE 'geodirectory\_%' OR option_name LIKE 'geodir\_%' OR option_name LIKE 'tax_meta_gd\_%' OR  option_name LIKE 'gd\_%' AND option_name LIKE '%category\_installed' ;" );

    // Extra options
    $extra_options = array(
        "widget_popular_post_category",
        "widget_popular_post_view",
        "gd_theme_compats",
        "gd_theme_compats",
        "theme_compatibility_setting",
        "skip_install_geodir_pages",
        "widget_social_like_widget",
        "widget_widget_subscribewidget",
        "widget_advtwidget",
        "widget_widget_flickrwidget",
        "widget_widget_twidget",
        "widget_listing_slider_view",
        "widget_geodir_recent_reviews",
        "widget_post_related_listing",
        "widget_bestof_widget",
        "gd_place_dummy_data_type",
        "gd_theme_compat",
        "gd_term_icons",
    );

    foreach( $extra_options as $option){
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name= '$option';" );
    }

    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'widget\_gd\_%' OR option_name LIKE 'widget\_geodir\_%' ;" );

    // Delete transients
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient__gd_activation_redirect' OR option_name LIKE '\_transient\_geodir\_%' OR option_name LIKE '\_transient\_gd_addons_section\_%' OR option_name LIKE '\_transient\_gd_avg\_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout__gd_activation_redirect' OR option_name LIKE '\_timeout\_transient\_geodir\_%' OR option_name LIKE '\_timeout\_transient\_gd_addons_section\_%' OR option_name LIKE '\_timeout\_transient\_gd_avg\_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient__gd_activation_redirect' OR option_name LIKE '\_site\_transient\_geodir\_%' OR option_name LIKE '\_site\_transient\_gd_addons_section\_%' OR option_name LIKE '\_site\_transient\_gd_avg\_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_timeout__gd_activation_redirect' OR option_name LIKE '\_site\_transient\_timeout\_geodir\_%' OR option_name LIKE '\_site\_transient\_timeout\_gd_addons_section\_%' OR option_name LIKE '\_site\_transient\_timeout\_gd_avg\_%'" );

    // Drop tables
    $plugin_prefix = $wpdb->prefix . 'geodir_';
    $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . 'countries' );
    $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . 'custom_fields' );
    $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . 'post_icon' );
    $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . 'attachments' );
    $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . 'post_review' );
    $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . 'custom_sort_fields' );


    // Delete term taxonomies.
    if ( ! empty( $post_types ) ) {
        foreach ( $post_types as $post_type => $data  ) {
            $wpdb->query( "DROP TABLE IF EXISTS ". $plugin_prefix . $post_type . '_detail' );

        }
    }

    // Clear any cached data that has been removed.
    wp_cache_flush();

}

if (get_option('geodir_un_geodirectory')) {
    $wpdb->hide_errors();
    
    /*
    if (!defined('GEODIRECTORY_VERSION')) {
        // Load plugin file.
        include_once('geodirectory.php');
    }
    */

    geodir_uninstall();

    // Delete default data.
    delete_option('geodir_default_data_installed');
}

