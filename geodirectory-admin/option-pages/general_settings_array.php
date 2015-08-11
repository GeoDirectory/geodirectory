<?php
/**
 * General tab settings.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global array $geodir_settings Geodirectory settings array.
 */
global $geodir_settings;

/**
 * function to get post type options.
 *
 * @since 1.5.1
 * @package GeoDirectory
 */
function geodir_post_type_select_options() {
    $options = array();

    $post_types = geodir_get_posttypes('object');

    foreach ($post_types as $key => $post_types_obj) {
        $options[$key] = __($post_types_obj->labels->singular_name, GEODIRECTORY_TEXTDOMAIN);
    }
    return $options;
}

/**
 * Filter GD general settings array.
 *
 * @since 1.0.0
 * @since 1.5.1 Default posttype in popular categories widget setting added.
 * @package GeoDirectory
 */
$general_options = apply_filters('geodir_general_options', array(

    array('name' => __('General', GEODIRECTORY_TEXTDOMAIN), 'type' => 'title', 'desc' => '', 'id' => 'general_options'),

    array('name' => __('General Options', GEODIRECTORY_TEXTDOMAIN), 'type' => 'sectionstart', 'id' => 'general_options'),

    array(
        'name' => __('Sender name', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('(Name that will be shown as email sender when users receive emails from this site)', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'site_email_name',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => get_bloginfo('name') // Default value for the page title - changed in settings
    ),

    array(
        'name' => __('Email address', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('(Emails to users will be sent via this mail ID)', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'site_email',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => get_bloginfo('admin_email') // Default value for the page title - changed in settings
    ),


    /*array(
        'name' => __( 'Display Category', GEODIRECTORY_TEXTDOMAIN ),
        'desc' 		=> __( 'Allow users to select category at add listing page', GEODIRECTORY_TEXTDOMAIN ),
        'tip' 		=> '',
        'id' 		=> 'geodir_category_dislay',
        'css' 		=> 'min-width:300px;',
        'std' 		=> 'checkbox',
        'type' 		=> 'select',
        'class'		=> 'chosen_select',
        'options' => array_unique(apply_filters('geodir_category_display', array(
            'select' => __( 'Category drop down', GEODIRECTORY_TEXTDOMAIN ),
            'checkbox' => __( 'Category checkboxs', GEODIRECTORY_TEXTDOMAIN ),
            'radio' => __( 'Category radio', GEODIRECTORY_TEXTDOMAIN ),
            ))
        )
    ),


    */
    array(
        'name' => __('Allow user to see wp-admin area', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Yes', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_allow_wpadmin',
        'std' => '1',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Allow user to see wp-admin area', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('No', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_allow_wpadmin',
        'std' => '0',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),

    array(
        'name' => __('Allow user to choose own password', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Yes', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_allow_cpass',
        'std' => '1',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Allow user to choose own password', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('No', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_allow_cpass',
        'std' => '0',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),
    array(
        'name' => __('Disable rating on comments', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Disable rating without disabling comments on listings', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_disable_rating',
        'type' => 'checkbox',
        'std' => '0'
    ),
    array(
        'name' => __('Maxi upload file size(in mb)', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('(Maximum upload file size in MB, 1 MB = 1024 KB. Must be greater then 0(ZERO), for ex: 2 )', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_upload_max_filesize',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => '2'
    ),
    /*array(
        'name' => __( 'Disable standard place taxonomie?', GEODIRECTORY_TEXTDOMAIN ),
        'desc' 		=> __( 'Yes', GEODIRECTORY_TEXTDOMAIN ),
        'id' 		=> 'geodir_disable_place_tax',
        'std' 		=> '0',
        'type' 		=> 'radio',
        'value'		=> '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __( 'Disable standard place taxonomie?', GEODIRECTORY_TEXTDOMAIN ),
        'desc' 		=> __( 'No', GEODIRECTORY_TEXTDOMAIN ),
        'id' 		=> 'geodir_disable_place_tax',
        'std' 		=> '1',
        'type' 		=> 'radio',
        'value'		=> '0',
        'radiogroup' => 'end'
    ),*/


    array('type' => 'sectionend', 'id' => 'general_options'),
	
	array('name' => __('Widget Options', GEODIRECTORY_TEXTDOMAIN), 'type' => 'sectionstart', 'id' => 'general_widget_options'),
	array(
		'name' => __( 'Default posttype in popular categories widget', GEODIRECTORY_TEXTDOMAIN ),
		'desc' => __( 'Set the default post type for popular post category widget.', GEODIRECTORY_TEXTDOMAIN ),
		'tip' => '',
		'id' => 'geodir_category_widget_cpt',
		'css' => 'min-width:300px;',
		'std' => '',
		'type' => 'select',
		'placeholder_text' => __( 'Select post type', GEODIRECTORY_TEXTDOMAIN ),
		'class' => 'chosen_select',
		'options' => array_unique(geodir_post_type_select_options())
	),
	array('type' => 'sectionend', 'id' => 'general_widget_options'),

));/* General Options End*/

/**
 * Filter GD Google Analytic Settings array.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
$google_analytic_settings = apply_filters('geodir_google_analytic_settings', array(

    array('name' => __('Google Analytics', GEODIRECTORY_TEXTDOMAIN), 'type' => 'title', 'desc' => '', 'id' => 'google_analytic_settings'),

    array('name' => __('Google Analytic Settings', GEODIRECTORY_TEXTDOMAIN), 'type' => 'sectionstart', 'id' => 'google_analytic_settings'),



    array(
        'name' => __('Show business owner google analytics stats?', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Yes', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_ga_stats',
        'std' => '0',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Show business owner Google Analytics stats?', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('No', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_ga_stats',
        'std' => '1',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),

    array(
        'name' => __('Google analytics "Profile ID(ie: ga:12345678)?', GEODIRECTORY_TEXTDOMAIN) . ' ' .
            '<a target="_blank" href="https://docs.wpgeodirectory.com/gd-core-plugin-google-analytics/">' . __('help', GEODIRECTORY_TEXTDOMAIN) . '</a>',
        'desc' => '',
        'id' => 'geodir_ga_id',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => '' // Default value for the page title - changed in settings
    ),

    array(
        'name' => __('Client ID', GEODIRECTORY_TEXTDOMAIN) . ' ' .
            '<a target="_blank" href="https://docs.wpgeodirectory.com/gd-core-plugin-google-analytics/">' . __('help', GEODIRECTORY_TEXTDOMAIN) . '</a>',
        'desc' => '',
        'id' => 'geodir_ga_client_id',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => '' // Default value for the page title - changed in settings
    ),

    array(
        'name' => __('Client secret', GEODIRECTORY_TEXTDOMAIN) . ' ' .
            '<a target="_blank" href="https://docs.wpgeodirectory.com/gd-core-plugin-google-analytics/">' . __('help', GEODIRECTORY_TEXTDOMAIN) . '</a>',
        'desc' => '',
        'id' => 'geodir_ga_client_secret',
        'type' => 'password',
        'css' => 'min-width:300px;',
        'std' => '' // Default value for the page title - changed in settings
    ),

    array(
        'name' => __('Google analytics access', GEODIRECTORY_TEXTDOMAIN),
        'desc' => '',
        'id' => 'geodir_ga_token',
        'type' => 'google_analytics',
        'css' => 'min-width:300px;',
        'std' => '' // Default value for the page title - changed in settings
    ),


    array(
        'name' => __('Google analytics tracking code', GEODIRECTORY_TEXTDOMAIN),
        'desc' => '',
        'id' => 'geodir_ga_tracking_code',
        'type' => 'textarea',
        'css' => 'min-width:300px;',
        'std' => '' // Default value for the page title - changed in settings
    ),


    array('type' => 'sectionend', 'id' => 'google_analytic_settings'),

)); // google_analytic_settings End

/**
 * Filter GD search Settings array.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
$search_settings = apply_filters('geodir_search_settings', array(

    array('name' => __('Search', GEODIRECTORY_TEXTDOMAIN), 'type' => 'title', 'desc' => '', 'id' => 'search_settings'),

    array('name' => __('Search Settings', GEODIRECTORY_TEXTDOMAIN), 'type' => 'sectionstart', 'id' => 'search_settings'),

    array(
        'name' => __('Limit squared distance area to X miles (helps improve search speed)', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Enter whole number only ex. 40 (Tokyo is largest city in the world @40 sq miles) LEAVE BLANK FOR NO DISTANCE LIMIT', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_dist',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => '40' // Default value for the page title - changed in settings
    ),

    array(
        'name' => __('Show search distances in miles or km', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Miles', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_dist_1',
        'std' => 'miles',
        'type' => 'radio',
        'value' => 'miles',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Show search distances in miles or km', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Kilometers', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_dist_1',
        'std' => 'miles',
        'type' => 'radio',
        'value' => 'km',
        'radiogroup' => 'end'
    ),

    array(
        'name' => __('If distance is less than 0.01 show distance in meters or feet', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Meters', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_dist_2',
        'std' => 'meters',
        'type' => 'radio',
        'value' => 'meters',
        'radiogroup' => 'start'
    ),

    array(
        'name' => __('If distance is less than 0.01 show distance in meters or feet', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('Feet', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_dist_2',
        'std' => 'meters',
        'type' => 'radio',
        'value' => 'feet',
        'radiogroup' => 'end'
    ),

    array(
        'name' => __('Add location specific text to (Near) search for Google', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('This is usefull if your directory is limted to one location such as: New York or Australia (this setting should be blank if using default country, regions etc with multilocation addon as it will automatically add them)', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_near_addition',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => ''
    ),
    array(
        'name' => __('Individual word search limit', GEODIRECTORY_TEXTDOMAIN),
        'desc' => __('With this option you can limit individual words being searched for, for example searching for `Jo Brown` would return results with words like `Jones`, you can exclude these types of small character words if you wish.', GEODIRECTORY_TEXTDOMAIN),
        'id' => 'geodir_search_word_limit',
        'css' => 'min-width:300px;',
        'std' => 'gridview_onehalf',
        'type' => 'select',
        'class' => 'chosen_select',
        'options' => array_unique(array(
            '0' => __('Disabled', GEODIRECTORY_TEXTDOMAIN),
            '1' => __('1 Character words excluded', GEODIRECTORY_TEXTDOMAIN),
            '2' => __('2 Character words and less excluded', GEODIRECTORY_TEXTDOMAIN),
            '3' => __('3 Character words and less excluded', GEODIRECTORY_TEXTDOMAIN),
        ))
    ),


    array('type' => 'sectionend', 'id' => 'search_settings'),

)); //search_settings End

/**
 * Filter GD Dummy data Settings array.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
$dummy_data_settings = apply_filters('geodir_dummy_data_settings', array(

    array('name' => __('Dummy Data', GEODIRECTORY_TEXTDOMAIN), 'type' => 'title', 'desc' => '', 'id' => 'dummy_data_settings'),

    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_dummy_data_installer',
        'type' => 'dummy_installer',
        'css' => 'min-width:300px;',
        'std' => '40' // Default value for the page title - changed in settings
    ),
    array('type' => 'sectionend', 'id' => 'geodir_dummy_data_settings'),

)); //dummy_data_settings End

/* // Moved under Import & Export.
$csv_upload_settings = apply_filters('geodir_csv_upload_settings', array(

    array('name' => __('CSV Upload', GEODIRECTORY_TEXTDOMAIN), 'type' => 'title', 'desc' => '', 'id' => 'csv_upload_settings'),

    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_csv_upload_installer',
        'type' => 'csv_installer',
        'css' => 'min-width:300px;',
        'std' => '40' // Default value for the page title - changed in settings
    ),
    array('type' => 'sectionend', 'id' => 'geodir_csv_upload_settings'),

));
*/


//echo 'update option= '.get_option('site_email_name');

$general_settings = array_merge($general_options, $google_analytic_settings, $search_settings, $dummy_data_settings);

/**
 * Filter GD General Settings array.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $general_settings General settings array.
 */
$geodir_settings['general_settings'] = apply_filters('geodir_general_settings', $general_settings);

//$_SESSION['geodir_settings']['general_settings'] = $geodir_settings['general_settings'] ;