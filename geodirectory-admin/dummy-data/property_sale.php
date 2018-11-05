<?php
/**
 * GD Dummy data for Properties for sale.
 *
 * @since 1.6.11
 * @package GeoDirectory
 */

function geodir_property_sale_custom_fields($post_type='gd_place',$package_id=''){
    $fields = array();
    $package = ($package_id=='') ? '' : array($package_id);

    // price
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'text',
                      'data_type'           =>  'FLOAT',
                      'decimal_point'       =>  '2',
                      'admin_title'         =>  __('Price', 'geodirectory'),
                      'site_title'          =>  __('Price', 'geodirectory'),
                      'admin_desc'          =>  __('Enter the price in $ (no currency symbol)', 'geodirectory'),
                      'htmlvar_name'        =>  'price',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  addslashes_gpc('\d+(\.\d{2})?'), // add slashes required
                      'validation_msg'      =>  'Please enter number and decimal only ie: 100.50',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-dollar-sign',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true,
                      'extra'        =>  array(
                          'is_price'                  =>  1,
                          'thousand_separator'        =>  'comma',
                          'decimal_separator'         =>  'period',
                          'decimal_display'           =>  'if',
                          'currency_symbol'           =>  '$',
                          'currency_symbol_placement' =>  'left'
                      )
    );

    // property status
    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'select',
                      'field_type_key' => 'property_status',
                      'is_active' => 1,
                      'for_admin_use' => 0,
                      'is_default' => 0,
                      'admin_title' => __('Property Status', 'geodirectory'),
                      'admin_desc' => __('Enter the status of the property.', 'geodirectory'),
                      'site_title' => __('Property Status', 'geodirectory'),
                      'htmlvar_name' => 'property_status',
                      'default_value' => '',
                      'is_required' => '1',
                      'required_msg' => '',
                      'show_in'   =>  '[detail],[listing]',
                      'show_on_pkg' => $package,
                      'option_values' => 'Select Status/,For Sale,Sold,Under Offer',
                      'field_icon' => 'fas fa-home',
                      'css_class' => '',
                      'cat_sort' => 1,
                      'cat_filter' => 1,
    );

    // property furnishing
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Furnishing', 'geodirectory'),
                      'site_title'          =>  __('Furnishing', 'geodirectory'),
                      'admin_desc'          =>  __('Enter the furnishing status of the property.', 'geodirectory'),
                      'htmlvar_name'        =>  'property_furnishing',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  true,
                      'option_values'       =>  __('Select Status/,Unfurnished,Furnished,Partially furnished,Optional','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-th-large',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // property type
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Property Type', 'geodirectory'),
                      'site_title'          =>  __('Property Type', 'geodirectory'),
                      'admin_desc'          =>  __('Select the property type.', 'geodirectory'),
                      'htmlvar_name'        =>  'property_type',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  true,
                      'option_values'       =>  __('Select Type/,Detached house,Semi-detached house,Apartment,Bungalow,Semi-detached bungalow,Chalet,Town House,End-terrace house,Terrace house,Cottage,Hotel,Land','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-home',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // property bedrooms
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Property Bedrooms', 'geodirectory'),
                      'site_title'          =>  __('Bedrooms', 'geodirectory'),
                      'admin_desc'          =>  __('Select the number of bedrooms', 'geodirectory'),
                      'htmlvar_name'        =>  'property_bedrooms',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  true,
                      'option_values'       =>  __('Select Bedrooms/,1,2,3,4,5,6,7,8,9,10','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-bed',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // property bathrooms
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Property Bathrooms', 'geodirectory'),
                      'site_title'          =>  __('Bathrooms', 'geodirectory'),
                      'admin_desc'          =>  __('Select the number of bathrooms', 'geodirectory'),
                      'htmlvar_name'        =>  'property_bathrooms',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  true,
                      'option_values'       =>  __('Select Bathrooms/,1,2,3,4,5,6,7,8,9,10','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-bold',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // property area
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'text',
                      'data_type'           =>  'INT',
                      'admin_title'         =>  __('Property Area', 'geodirectory'),
                      'site_title'          =>  __('Area (Sq Ft)', 'geodirectory'),
                      'admin_desc'          =>  __('Enter the Sq Ft value for the property', 'geodirectory'),
                      'htmlvar_name'        =>  'property_area',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  addslashes_gpc('\d+(\.\d{2})?'), // add slashes required
                      'validation_msg'      =>  'Please enter the property area in numbers only: 1500',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-chart-area',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // property features
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'multiselect',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Property Features', 'geodirectory'),
                      'site_title'          =>  __('Features', 'geodirectory'),
                      'admin_desc'          =>  __('Select the property features.', 'geodirectory'),
                      'htmlvar_name'        =>  'property_features',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  false,
                      'option_values'       =>  __('Gas Central Heating,Oil Central Heating,Double Glazing,Triple Glazing,Front Garden,Garage,Private driveway,Off Road Parking,Fireplace','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-plus-square',
                      'css_class'           =>  'gd-comma-list',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );



    /**
     * Filter the array of default custom fields DB table data.
     *
     * @since 1.6.6
     * @param string $fields The default custom fields as an array.
     */
    $fields = apply_filters('geodir_property_sale_custom_fields', $fields);

    return  $fields;
}

function geodir_property_sale_custom_fields_sort($post_type='gd_place') {


    $fields = array();

    // price sort
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'text',
        'data_type'               => '',
        'htmlvar_name'            => 'geodir_price',
        'site_title'              => __('Price','geodirectory'),
        'asc'                     => 1,
        'asc_title'               => __('Price (lowest first)','geodirectory'),
        'desc'                    => 1,
        'desc_title'              => __('Price (highest first)','geodirectory'),
        'is_active'               => 1
    );

    // area sort
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'text',
        'data_type'               => '',
        'htmlvar_name'            => 'geodir_property_area',
        'site_title'              => __('Area (Sq Ft)','geodirectory'),
        'asc'                     => 1,
        'asc_title'               => __('Area (smallest first)','geodirectory'),
        'desc'                    => 1,
        'desc_title'              => __('Area (largest first)','geodirectory'),
        'is_active'               => 1
    );

    // bedrooms sort
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'select',
        'data_type'               => '',
        'htmlvar_name'            => 'geodir_property_bedrooms',
        'site_title'              => __('Area (Sq Ft)','geodirectory'),
        'asc'                     => 1,
        'asc_title'               => __('Bedrooms (least)','geodirectory'),
        'desc'                    => 1,
        'desc_title'              => __('Bedrooms (most)','geodirectory'),
        'is_active'               => 1
    );


    /**
     * Filter the array of advanced search fields DB table data.
     *
     * @since 1.6.6
     * @param string $fields The default custom fields as an array.
     */
    $fields = apply_filters('geodir_property_sale_custom_fields_sort', $fields);

    return $fields;

}

function geodir_property_sale_custom_fields_advanced_search($post_type='gd_place') {


    $fields = array();

    // price range
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'text',
        'data_type'               => 'RANGE',
        'is_active'               => 1,
        'site_field_title'        => 'Price',
        'field_data_type'         => 'FLOAT',
        'main_search'             => 1,
        'main_search_priority'    => 15,
        'data_type_change'        => 'SELECT',
        'search_condition_select' => 'SINGLE',
        'search_min_value'        => '50000',
        'search_max_value'        => '1000000',
        'search_diff_value'       => '100000',
        'first_search_value'      => '0',
        'first_search_text'       => '',
        'last_search_text'        => '',
        'search_condition'        => 'SELECT',
        'site_htmlvar_name'       => 'geodir_price',
        'htmlvar_name'            => 'geodir_price',
        'field_title'             => 'geodir_price',
        'expand_custom_value'     => '',
        'front_search_title'      => 'Price Range',
        'field_desc'              => ''
    );

    // bedrooms
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'select',
        'data_type'               => 'CHECK',
        'is_active'               => 1,
        'site_field_title'        => 'Bedrooms',
        'field_data_type'         => 'VARCHAR',
        'main_search'             => 1,
        'main_search_priority'    => 16,
        'search_condition'        => 'SINGLE',
        'site_htmlvar_name'       => 'geodir_property_bedrooms',
        'htmlvar_name'            => 'geodir_property_bedrooms',
        'field_title'             => 'geodir_property_bedrooms',
        'front_search_title'      => 'Bedrooms',
        'field_desc'              => '',
        'expand_custom_value'     => 5,
        'expand_search'           => 1,
        'search_operator'         => 'OR'
    );

    // Property type
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'select',
        'data_type'               => 'CHECK',
        'is_active'               => 1,
        'site_field_title'        => 'Property Type',
        'field_data_type'         => 'VARCHAR',
        'main_search'             => 0,
        //'main_search_priority'    => 16,
        'search_condition'        => 'SINGLE',
        'site_htmlvar_name'       => 'geodir_property_type',
        'htmlvar_name'            => 'geodir_property_type',
        'field_title'             => 'geodir_property_type',
        'front_search_title'      => 'Property Type',
        'field_desc'              => '',
        'expand_custom_value'     => 5,
        'expand_search'           => 1,
        'search_operator'         => 'OR'
    );

    // Property Features
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'multiselect',
        'data_type'               => 'CHECK',
        'is_active'               => 1,
        'site_field_title'        => 'Features',
        'field_data_type'         => 'VARCHAR',
        'main_search'             => 0,
        //'main_search_priority'    => 16,
        'search_condition'        => 'SINGLE',
        'site_htmlvar_name'       => 'geodir_property_features',
        'htmlvar_name'            => 'geodir_property_features',
        'field_title'             => 'geodir_property_features',
        'front_search_title'      => 'Property Features',
        'field_desc'              => '',
        'expand_custom_value'     => 5,
        'expand_search'           => 1,
        'search_operator'         => 'AND'
    );

    // Property Bathrooms
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'select',
        'data_type'               => 'CHECK',
        'is_active'               => 1,
        'site_field_title'        => 'Bathrooms',
        'field_data_type'         => 'VARCHAR',
        'main_search'             => 0,
        //'main_search_priority'    => 16,
        'search_condition'        => 'SINGLE',
        'site_htmlvar_name'       => 'geodir_property_bathrooms',
        'htmlvar_name'            => 'geodir_property_bathrooms',
        'field_title'             => 'geodir_property_bathrooms',
        'front_search_title'      => 'Bathrooms',
        'field_desc'              => '',
        'expand_custom_value'     => 5,
        'expand_search'           => 1,
        'search_operator'         => 'OR'
    );

    // Property Furnishing
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'select',
        'data_type'               => 'CHECK',
        'is_active'               => 1,
        'site_field_title'        => 'Furnishing',
        'field_data_type'         => 'VARCHAR',
        'main_search'             => 0,
        //'main_search_priority'    => 16,
        'search_condition'        => 'SINGLE',
        'site_htmlvar_name'       => 'geodir_property_furnishing',
        'htmlvar_name'            => 'geodir_property_furnishing',
        'field_title'             => 'geodir_property_furnishing',
        'front_search_title'      => 'Furnishing',
        'field_desc'              => '',
        'expand_custom_value'     => 5,
        'expand_search'           => 1,
        'search_operator'         => 'OR'
    );

    // Property Status
    $fields[] = array(
        'create_field'            => true,
        'listing_type'            => $post_type,
        'field_type'              => 'select',
        'data_type'               => 'CHECK',
        'is_active'               => 1,
        'site_field_title'        => 'Property Status',
        'field_data_type'         => 'VARCHAR',
        'main_search'             => 0,
        //'main_search_priority'    => 16,
        'search_condition'        => 'SINGLE',
        'site_htmlvar_name'       => 'geodir_property_status',
        'htmlvar_name'            => 'geodir_property_status',
        'field_title'             => 'geodir_property_status',
        'front_search_title'      => 'Property Status',
        'field_desc'              => '',
        'expand_custom_value'     => 5,
        'expand_search'           => 1,
        'search_operator'         => 'OR'
    );



    /**
     * Filter the array of advanced search fields DB table data.
     *
     * @since 1.6.6
     * @param string $fields The default custom fields as an array.
     */
    $fields = apply_filters('geodir_property_sale_custom_fields_advanced_search', $fields);

    return $fields;
}

global $city_bound_lat1, $city_bound_lng1, $city_bound_lat2, $city_bound_lng2,$wpdb, $current_user,$dummy_post_index;
$post_info = array();
$image_array = array();
$post_meta = array();
$category_array = array('Apartments', 'Houses', 'Commercial', 'Land');

if($dummy_post_index==1){
    // add the dummy categories
    geodir_dummy_data_taxonomies($post_type,$category_array );

    // add the dummy custom fields
    $fields = geodir_property_sale_custom_fields($post_type);
    geodir_create_dummy_fields($fields);

    // add sort order items
    $sort_fields = geodir_property_sale_custom_fields_sort($post_type);
    foreach($sort_fields as $sort){
        geodir_custom_sort_field_save($sort);
    }

    // update the type currently installed
    update_option($post_type.'_dummy_data_type','property_sale');

    // add the advanced search fields
    if (defined('GEODIRADVANCESEARCH_VERSION')){
        $search_fields = geodir_property_sale_custom_fields_advanced_search($post_type);
        foreach($search_fields as $sfield){
            geodir_custom_advance_search_field_save( $sfield );
        }
    }
}

if (geodir_dummy_folder_exists())
    $dummy_image_url = geodir_plugin_url() . "/geodirectory-admin/dummy";
else
    $dummy_image_url = 'https://wpgeodirectory.com/dummy';

$dummy_image_url = apply_filters('place_dummy_image_url', $dummy_image_url);

switch ($dummy_post_index) {

    case(1):
        $image_array[] = "$dummy_image_url/ps/psf1.jpg";
        $image_array[] = "$dummy_image_url/ps/psl1.jpg";
        $image_array[] = "$dummy_image_url/ps/psb1.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";


        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Eastern Lodge',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non augue ultrices, vulputate nulla at, consectetur ante. Quisque neque mi, vulputate quis nulla a, sollicitudin fringilla leo. Nam dictum id neque eu imperdiet. Curabitur ligula turpis, malesuada at lobortis commodo, vulputate volutpat arcu. Duis bibendum blandit aliquam. In ipsum diam, tristique ut bibendum vel, lobortis non tellus. Nulla ultricies, ante vitae placerat auctor, nisi quam blandit enim, sit amet aliquam est diam id urna. Suspendisse eget nibh volutpat, malesuada enim sed, egestas massa.

Aliquam ut odio ullamcorper, posuere enim sed, venenatis tortor. Donec justo elit, aliquam sed cursus sed, semper eget libero. Mauris consequat lorem sed fringilla tincidunt. Phasellus suscipit velit et elit tristique, ac commodo metus scelerisque. Vivamus finibus ipsum placerat pulvinar aliquet. Maecenas augue orci, blandit at nibh pharetra, condimentum congue ligula. Duis non ante sagittis odio convallis lacinia in quis sapien.

Curabitur molestie vel ipsum non eleifend. Pellentesque eu nulla sed magna condimentum finibus. Aliquam vel ullamcorper eros, eget lacinia eros. Nam tempor auctor tortor, eget tempor dui rhoncus in. Donec posuere sit amet odio eget pharetra. Duis nec tortor id urna dignissim bibendum. Phasellus eu leo consectetur, tincidunt ipsum sed, aliquet felis. Praesent eu consequat mauris, ac pulvinar velit. Curabitur vel purus in mauris elementum bibendum sit amet a erat. Suspendisse suscipit nec libero at pellentesque.

Vestibulum tristique quam eget bibendum pulvinar. Mauris sit amet magna ut arcu rutrum pellentesque feugiat et ipsum. Proin porta quam sed risus accumsan pharetra. Nulla quis semper nisl. Nulla facilisi. Nulla facilisi. Pellentesque euismod sollicitudin lacus vel ultricies. Vestibulum ut sem ut nulla ultricies convallis in at mi. Nunc vitae nibh arcu. Maecenas nunc enim, tempus a rhoncus eget, pellentesque ut erat.

Suspendisse interdum accumsan magna et tempor. Suspendisse scelerisque at lorem sit amet faucibus. Aenean quis consectetur enim. Duis aliquet tristique tempus. Suspendisse id ullamcorper mauris. Aliquam in libero eu justo porttitor pulvinar. Nulla semper placerat lectus. Nulla mollis suscipit lacus, a blandit purus cursus non. Maecenas id tellus mi. Pellentesque sollicitudin nibh eget magna scelerisque consequat. Aliquam convallis orci arcu, et euismod dui cursus et. Donec nec pellentesque nulla, ac pretium massa. In gravida bibendum ornare.',
            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[1])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(111) 677-4444',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '350000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => 'Furnished',
            'geodir_property_type' => 'Detached house',
            'geodir_property_bedrooms' => '3',
            'geodir_property_bathrooms' => '2',
            'geodir_property_area' => '1850',
            'geodir_property_features' => 'Gas Central Heating,Triple Glazing,Front Garden,Private driveway,Fireplace',
            "post_dummy" => '1'
        );


        break;
    case 2:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf2.jpg";
        $image_array[] = "$dummy_image_url/ps/psl2.jpg";
        $image_array[] = "$dummy_image_url/ps/psb2.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Daisy Street',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[1])),
            "post_tags" => array('Garage'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '230000',
            "geodir_property_status" => 'Sold',
            'geodir_property_furnishing' => 'Unfurnished',
            'geodir_property_type' => 'Detached house',
            'geodir_property_bedrooms' => '5',
            'geodir_property_bathrooms' => '3',
            'geodir_property_area' => '2650',
            'geodir_property_features' => 'Select Features/,Oil Central Heating,Front Garden,Garage,Private driveway,Fireplace',
            "post_dummy" => '1'
        );

        break;

    case 3:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf3.jpg";
        $image_array[] = "$dummy_image_url/ps/psl3.jpg";
        $image_array[] = "$dummy_image_url/ps/psb3.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Northbay House',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[1])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '260000',
            "geodir_property_status" => 'Under Offer',
            'geodir_property_furnishing' => 'Unfurnished',
            'geodir_property_type' => 'Detached house',
            'geodir_property_bedrooms' => '6',
            'geodir_property_bathrooms' => '6',
            'geodir_property_area' => '1650',
            'geodir_property_features' => 'Select Features/,Gas Central Heating,Triple Glazing,Off Road Parking,Fireplace',
            "post_dummy" => '1'
        );

        break;


    case 4:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf4.jpg";
        $image_array[] = "$dummy_image_url/ps/psl4.jpg";
        $image_array[] = "$dummy_image_url/ps/psb4.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Jesmond Mansion',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[1])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '2300000',
            "geodir_property_status" => 'Under Offer',
            'geodir_property_furnishing' => 'Partially furnished',
            'geodir_property_type' => 'Detached house',
            'geodir_property_bedrooms' => '10',
            'geodir_property_bathrooms' => '7',
            'geodir_property_area' => '6600',
            'geodir_property_features' => 'Select Features/,Oil Central Heating,Double Glazing,Front Garden,Garage,Private driveway,Fireplace',
            "post_dummy" => '1'
        );

        break;

    case 5:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf5.jpg";
        $image_array[] = "$dummy_image_url/ps/psl5.jpg";
        $image_array[] = "$dummy_image_url/ps/psb5.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Springfield Lodge',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[1])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '330000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => 'Optional',
            'geodir_property_type' => 'Detached house',
            'geodir_property_bedrooms' => '4',
            'geodir_property_bathrooms' => '3',
            'geodir_property_area' => '3700',
            'geodir_property_features' => 'Select Features/,Oil Central Heating,Double Glazing,Front Garden',
            "post_dummy" => '1'
        );

        break;

    case 6:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf6.jpg";
        $image_array[] = "$dummy_image_url/ps/psl6.jpg";
        $image_array[] = "$dummy_image_url/ps/psb5.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Forrest Park',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[1])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '530000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => 'Unfurnished',
            'geodir_property_type' => 'Detached house',
            'geodir_property_bedrooms' => '5',
            'geodir_property_bathrooms' => '4',
            'geodir_property_area' => '2250',
            'geodir_property_features' => 'Select Features/,Gas Central Heating,Double Glazing,Front Garden,Private driveway',
            "post_dummy" => '1'
        );

        break;

    case 7:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf7.jpg";
        $image_array[] = "$dummy_image_url/ps/psl4.jpg";
        $image_array[] = "$dummy_image_url/ps/psb4.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Fraser Suites',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[0])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '245000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => 'Unfurnished',
            'geodir_property_type' => 'Apartment',
            'geodir_property_bedrooms' => '3',
            'geodir_property_bathrooms' => '2',
            'geodir_property_area' => '1250',
            'geodir_property_features' => 'Select Features/,Gas Central Heating,Double Glazing',
            "post_dummy" => '1'
        );

        break;

    case 8:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf8.jpg";
        $image_array[] = "$dummy_image_url/ps/psl2.jpg";
        $image_array[] = "$dummy_image_url/ps/psb2.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Richmore Apartments',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[0])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '395000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => 'Unfurnished',
            'geodir_property_type' => 'Apartment',
            'geodir_property_bedrooms' => '2',
            'geodir_property_bathrooms' => '2',
            'geodir_property_area' => '1750',
            'geodir_property_features' => 'Select Features/,Gas Central Heating,Double Glazing,Garage',
            "post_dummy" => '1'
        );

        break;


    case 9:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf9.jpg";
        $image_array[] = "$dummy_image_url/ps/psc9.jpg";
        $image_array[] = "$dummy_image_url/ps/psb2.jpg";
        $image_array[] = "$dummy_image_url/ps/psk.jpg";
        $image_array[] = "$dummy_image_url/ps/psbr.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Hotel Alpina',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[2])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '12500000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => 'Furnished',
            'geodir_property_type' => 'Hotel',
            'geodir_property_bedrooms' => '120',
            'geodir_property_bathrooms' => '133',
            'geodir_property_area' => '35000',
            'geodir_property_features' => 'Select Features/,Gas Central Heating,Double Glazing,Garage',
            "post_dummy" => '1'
        );

        break;

    case 10:
        $image_array = array();
        $post_meta = array();
        $image_array[] = "$dummy_image_url/ps/psf10.jpg";
        $image_array[] = "$dummy_image_url/ps/psf102.jpg";

        $post_info[] = array(
            "listing_type" => $post_type,
            "post_title" => 'Development Land',
            "post_desc" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut fringilla ipsum congue enim elementum ornare. Vestibulum id ipsum ac massa malesuada rutrum. Curabitur id erat nec mauris hendrerit pretium. Aliquam pretium sollicitudin enim ac hendrerit. Phasellus et enim elit. Mauris ac maximus enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ut cursus leo. Aenean lacinia risus ut ex sodales, a dictum eros vulputate. Sed ornare ex eget velit fringilla luctus. Etiam a purus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam non felis ultrices, dignissim metus mattis, interdum urna.

Vivamus at ipsum consectetur, pellentesque lectus vitae, vulputate leo. Cras tincidunt suscipit vulputate. Aenean pretium diam dui, efficitur porttitor lorem cursus in. Aenean convallis, mauris quis fermentum vehicula, purus libero fringilla lorem, placerat ultricies magna velit sit amet neque. Aenean tempor ut eros et volutpat. Proin ac lacus et odio volutpat aliquet. Proin at erat enim. Vivamus venenatis dictum magna, id dignissim lacus molestie non. Nullam ornare placerat metus, quis aliquam orci tincidunt at. Sed semper imperdiet arcu, eu convallis eros fringilla vel.

Nullam eget gravida ex, et tincidunt nibh. Fusce sed turpis at tellus porta sodales. Nulla eget mattis lorem, sit amet pulvinar diam. Nulla odio justo, feugiat id odio non, convallis fermentum quam. Nullam risus ligula, rhoncus sed tortor vulputate, gravida luctus quam. Pellentesque sollicitudin in sapien sit amet dictum. Sed aliquam felis ac sapien aliquet faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla vel mollis libero. Sed lectus leo, blandit vitae quam venenatis, gravida laoreet sapien. Nullam quis dolor vel eros dictum lobortis. Donec eget sem ac dolor mattis lacinia. Phasellus accumsan condimentum magna, eu dignissim ipsum.

Quisque consequat sagittis purus. Vivamus aliquam eros quis metus dignissim, eu blandit massa dictum. Curabitur vel fringilla elit. Fusce orci augue, vehicula id finibus eu, viverra sed nulla. Quisque pretium augue augue, eget mattis orci tincidunt tincidunt. Donec fermentum odio placerat vestibulum fermentum. Vivamus eu posuere sapien.

Mauris ac elit vitae massa dignissim posuere. Sed blandit nibh ut elementum ullamcorper. Nunc facilisis elit eget lorem bibendum, eu fermentum neque ultrices. Etiam vestibulum gravida sollicitudin. Nullam velit quam, luctus vel suscipit id, ullamcorper sit amet ipsum. Donec a elit ac lorem porttitor gravida. Sed non dui sed lacus vulputate varius. Nullam in tincidunt odio, ac pharetra mauris. Integer ac volutpat quam. Mauris fermentum facilisis porttitor. Nunc ornare vel erat volutpat consectetur. Phasellus ut lacinia ante. Vestibulum massa orci, tincidunt sit amet urna in, maximus mollis ligula.',

            "post_images" => $image_array,
            "post_category" => array($post_type.'category' => array($category_array[3])),
            "post_tags" => array('Tags', 'Sample Tags'),
            "geodir_video" => '',
            "geodir_timing" => 'Viewing Sunday 10 am to 9 pm',
            "geodir_contact" => '(222) 777-1111',
            "geodir_email" => 'info@example.com',
            "geodir_website" => 'http://example.com/',
            "geodir_twitter" => 'http://example.com/',
            "geodir_facebook" => 'http://example.com/',
            "geodir_price" => '80000',
            "geodir_property_status" => 'For Sale',
            'geodir_property_furnishing' => '',
            'geodir_property_type' => 'Land',
            'geodir_property_bedrooms' => '',
            'geodir_property_bathrooms' => '',
            'geodir_property_area' => '250000',
            'geodir_property_features' => '',
            "post_dummy" => '1'
        );

        break;

} // end of switch

foreach ($post_info as $post_info) {
    $default_location = geodir_get_default_location();
    if ($city_bound_lat1 > $city_bound_lat2)
        $dummy_post_latitude = geodir_random_float(geodir_random_float($city_bound_lat1, $city_bound_lat2), geodir_random_float($city_bound_lat2, $city_bound_lat1));
    else
        $dummy_post_latitude = geodir_random_float(geodir_random_float($city_bound_lat2, $city_bound_lat1), geodir_random_float($city_bound_lat1, $city_bound_lat2));


    if ($city_bound_lng1 > $city_bound_lng2)
        $dummy_post_longitude = geodir_random_float(geodir_random_float($city_bound_lng1, $city_bound_lng2), geodir_random_float($city_bound_lng2, $city_bound_lng1));
    else
        $dummy_post_longitude = geodir_random_float(geodir_random_float($city_bound_lng2, $city_bound_lng1), geodir_random_float($city_bound_lng1, $city_bound_lng2));

    $load_map = get_option('geodir_load_map');
    
    if ($load_map == 'osm') {
        $post_address = geodir_get_osm_address_by_lat_lan($dummy_post_latitude, $dummy_post_longitude);
    } else {
        $post_address = geodir_get_address_by_lat_lan($dummy_post_latitude, $dummy_post_longitude);
    }

    $postal_code = '';
    if (!empty($post_address)) {
        if ($load_map == 'osm') {
            $address = !empty($post_address->formatted_address) ? $post_address->formatted_address : '';
            $postal_code = !empty($post_address->address->postcode) ? $post_address->address->postcode : '';
        } else {
            $addresses = array();
            $addresses_default = array();
            
            foreach ($post_address as $add_key => $add_value) {
                if ($add_key < 2 && !empty($add_value->long_name)) {
                    $addresses_default[] = $add_value->long_name;
                }
                if ($add_value->types[0] == 'postal_code') {
                    $postal_code = $add_value->long_name;
                }
                if ($add_value->types[0] == 'street_number') {
                    $addresses[] = $add_value->long_name;
                }
                if ($add_value->types[0] == 'route') {
                    $addresses[] = $add_value->long_name;
                }
                if ($add_value->types[0] == 'neighborhood') {
                    $addresses[] = $add_value->long_name;
                }
                if ($add_value->types[0] == 'sublocality') {
                    $addresses[] = $add_value->long_name;
                }
            }
            $address = !empty($addresses) ? implode(', ', $addresses) : (!empty($addresses_default) ? implode(', ', $addresses_default) : '');
        }

        $post_info['post_address'] = !empty($address) ? $address : $default_location->city;
        $post_info['post_city'] = $default_location->city;
        $post_info['post_region'] = $default_location->region;
        $post_info['post_country'] = $default_location->country;
        $post_info['post_zip'] = $postal_code;
        $post_info['post_latitude'] = $dummy_post_latitude;
        $post_info['post_longitude'] = $dummy_post_longitude;
    }
    
    geodir_save_listing($post_info, true);
    echo 1;
}
