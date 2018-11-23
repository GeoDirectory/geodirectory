<?php
/**
 * Custom fields predefined
 *
 * @since 1.6.9
 * @package GeoDirectory
 */


/**
 * Returns the array of custom fields that can be used.
 *
 * @param string $post_type The post type being added.
 * @since 1.6.9
 * @package GeoDirectory
 * @see `geodir_custom_field_save` for array details.
 */
function geodir_custom_fields_predefined($post_type=''){

    $custom_fields = array();


    // price
    $custom_fields['price'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'text',
        'class'       =>  'gd-price',
        'icon'        =>  'fas fa-dollar-sign',
        'name'        =>  __('Price', 'geodirectory'),
        'description' =>  __('Adds a input for a price field. This will let you filter and sort by price.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'FLOAT',
            'decimal_point'       =>  '2',
            'admin_title'         =>  'Price',
            'site_title'          =>  'Price',
            'admin_desc'          =>  'Enter the price in $ (no currency symbol)',
            'htmlvar_name'        =>  'price',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	      =>  '[detail],[listing]',
            'is_required'         =>  false,
            'validation_pattern'  =>  '\d+(\.\d{2})?',
            'validation_msg'      =>  'Please enter number and decimal only ie: 100.50',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-dollar-sign',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true,
            'extra_fields'        =>  array(
                'is_price'                  =>  1,
                'thousand_separator'        =>  'comma',
                'decimal_separator'         =>  'period',
                'decimal_display'           =>  'if',
                'currency_symbol'           =>  '$',
                'currency_symbol_placement' =>  'left'
            )
        )
    );

    // property status
    $custom_fields['property_status'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-property-status',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Status', 'geodirectory'),
        'description' =>  __('Adds a select input to be able to set the status of a property ie: For Sale, For Rent', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Property Status',
            'site_title'          =>  'Property Status',
            'admin_desc'          =>  'Enter the status of the property.',
            'htmlvar_name'        =>  'property_status',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Status/,For Sale,For Rent,Sold,Let','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-home',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // property furnishing
    $custom_fields['property_furnishing'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-property-furnishing',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Furnishing', 'geodirectory'),
        'description' =>  __('Adds a select input to be able to set the furnishing status of a property ie: Unfurnished, Furnished', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Furnishing',
            'site_title'          =>  'Furnishing',
            'admin_desc'          =>  'Enter the furnishing status of the property.',
            'htmlvar_name'        =>  'property_furnishing',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Status/,Unfurnished,Furnished,Partially furnished,Optional','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-th-large',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // property type
    $custom_fields['property_type'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-property-type',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Type', 'geodirectory'),
        'description' =>  __('Adds a select input for the property type ie: Detached house, Apartment', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Property Type',
            'site_title'          =>  'Property Type',
            'admin_desc'          =>  'Select the property type.',
            'htmlvar_name'        =>  'property_type',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Type/,Detached house,Semi-detached house,Apartment,Bungalow,Semi-detached bungalow,Chalet,Town House,End-terrace house,Terrace house,Cottage','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-home',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // property bedrooms
    $custom_fields['property_bedrooms'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-property-bedrooms',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Bedrooms', 'geodirectory'),
        'description' =>  __('Adds a select input for the number of bedrooms.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Property Bedrooms',
            'site_title'          =>  'Bedrooms',
            'admin_desc'          =>  'Select the number of bedrooms',
            'htmlvar_name'        =>  'property_bedrooms',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Bedrooms/,1,2,3,4,5,6,7,8,9,10','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-bed',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // property bathrooms
    $custom_fields['property_bathrooms'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-property-bathrooms',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Bathrooms', 'geodirectory'),
        'description' =>  __('Adds a select input for the number of bathrooms.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Property Bathrooms',
            'site_title'          =>  'Bathrooms',
            'admin_desc'          =>  'Select the number of bathrooms',
            'htmlvar_name'        =>  'property_bathrooms',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Bathrooms/,1,2,3,4,5,6,7,8,9,10','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-bold',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // property area
    $custom_fields['property_area'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'text',
        'class'       =>  'gd-area',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Area', 'geodirectory'),
        'description' =>  __('Adds a input for the property area.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'FLOAT',
            'admin_title'         =>  'Property Area',
            'site_title'          =>  'Area (Sq Ft)',
            'admin_desc'          =>  'Enter the Sq Ft value for the property',
            'htmlvar_name'        =>  'property_area',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	      =>  '[detail],[listing]',
            'is_required'         =>  false,
            'validation_pattern'  =>  '\d+(\.\d{2})?',
            'validation_msg'      =>  'Please enter the property area in numbers only: 1500',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-chart-area',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // property features
    $custom_fields['property_features'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'multiselect',
        'class'       =>  'gd-property-features',
        'icon'        =>  'fas fa-home',
        'name'        =>  __('Property Features', 'geodirectory'),
        'description' =>  __('Adds a select input for the property features.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Property Features',
            'site_title'          =>  'Features',
            'admin_desc'          =>  'Select the property features.',
            'htmlvar_name'        =>  'property_features',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Features/,Gas Central Heating,Oil Central Heating,Double Glazing,Triple Glazing,Front Garden,Garage,Private driveway,Off Road Parking,Fireplace','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-plus-square',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // Twitter feed
    $custom_fields['twitter_feed'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'text',
        'class'       =>  'gd-twitter',
        'icon'        =>  'fab fa-twitter',
        'name'        =>  __('Twitter feed', 'geodirectory'),
        'description' =>  __('Adds a input for twitter username and outputs feed.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Twitter',
            'site_title'          =>  'Twitter',
            'admin_desc'          =>  'Enter your Twitter username',
            'htmlvar_name'        =>  'twitterusername',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	      =>  '[detail],[owntab]',
            'is_required'         =>  false,
            'validation_pattern'  =>  '^[A-Za-z0-9_]{1,32}$',
            'validation_msg'      =>  'Please enter a valid twitter username.',
            'required_msg'        =>  '',
            'field_icon'          =>  'fab fa-twitter',
            'css_class'           =>  '',
            'cat_sort'            =>  false,
            'cat_filter'	      =>  false
        )
    );

    // Get directions link
    $custom_fields['get_directions'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'text',
        'class'       =>  'gd-get-directions',
        'icon'        =>  'fas fa-location-arrow',
        'name'        =>  __('Get Directions Link', 'geodirectory'),
        'description' =>  __('Adds a input for twitter username and outputs feed.', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  'Get Directions',
            'site_title'          =>  'Get Directions',
            'admin_desc'          =>  '',
            'htmlvar_name'        =>  'get_directions',
            'is_active'           =>  true,
            'for_admin_use'       =>  true,
            'default_value'       =>  'Get Directions',
            'show_in' 	      =>  '[detail],[listing]',
            'is_required'         =>  false,
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-location-arrow',
            'css_class'           =>  '',
            'cat_sort'            =>  false,
            'cat_filter'	      =>  false
        )
    );


    // JOB TYPE CF

    // job type
    $custom_fields['job_type'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-job-type',
        'icon'        =>  'fas fa-briefcase',
        'name'        =>  __('Job Type', 'geodirectory'),
        'description' =>  __('Adds a select input to be able to set the type of a job ie: Full Time, Part Time', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  __('Job Type', 'geodirectory'),
            'site_title'          =>  __('Job Type','geodirectory'),
            'admin_desc'          =>  __('Select the type of job.','geodirectory'),
            'htmlvar_name'        =>  'job_type',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail],[listing]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Type/,Freelance,Full Time,Internship,Part Time,Temporary,Other','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-briefcase',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );

    // job sector
    $custom_fields['job_sector'] = array( // The key value should be unique and not contain any spaces.
        'field_type'  =>  'select',
        'class'       =>  'gd-job-type',
        'icon'        =>  'fas fa-briefcase',
        'name'        =>  __('Job Sector', 'geodirectory'),
        'description' =>  __('Adds a select input to be able to set the type of a job Sector ie: Private Sector,Public Sector', 'geodirectory'),
        'defaults'    => array(
            'data_type'           =>  'VARCHAR',
            'admin_title'         =>  __('Job Sector','geodirectory'),
            'site_title'          =>  __('Job Sector','geodirectory'),
            'admin_desc'          =>  __('Select the job sector.','geodirectory'),
            'htmlvar_name'        =>  'job_sector',
            'is_active'           =>  true,
            'for_admin_use'       =>  false,
            'default_value'       =>  '',
            'show_in' 	          =>  '[detail]',
            'is_required'         =>  true,
            'option_values'       =>  __('Select Sector/,Private Sector,Public Sector,Agencies','geodirectory'),
            'validation_pattern'  =>  '',
            'validation_msg'      =>  '',
            'required_msg'        =>  '',
            'field_icon'          =>  'fas fa-briefcase',
            'css_class'           =>  '',
            'cat_sort'            =>  true,
            'cat_filter'	      =>  true
        )
    );


    /**
     * @see `geodir_custom_fields`
     */
    return apply_filters('geodir_custom_fields_predefined',$custom_fields,$post_type);
}


/**
 * Filter the custom field output.
 *
 * @param string $html The html to be output.
 * @param string $location The location name of the output location.
 * @param object $cf The custom field object info.
 *
 * @since 1.6.9
 * @return string The html to output.
 */
function geodir_predefined_custom_field_output_twitter_feed($html,$location,$cf){
    global $post;


    if (isset($post->{$cf['htmlvar_name']}) && $post->{$cf['htmlvar_name']} != '' ):

        $class = ($cf['htmlvar_name'] == 'geodir_timing') ? "geodir-i-time" : "geodir-i-text";

        $field_icon = geodir_field_icon_proccess($cf);
        if (strpos($field_icon, 'http') !== false) {
            $field_icon_af = '';
        } elseif ($field_icon == '') {
            $field_icon_af = ($cf['htmlvar_name'] == 'geodir_timing') ? '<i class="far fa-clock"></i>' : "";
        } else {
            $field_icon_af = $field_icon;
            $field_icon = '';
        }


        $html = '<div class="geodir_more_info ' . $cf['css_class'] . ' ' . $cf['htmlvar_name'] . '" style="clear:both;">';

        $html .= '<a class="twitter-timeline" data-height="600" data-dnt="true" href="https://twitter.com/'.$post->{$cf['htmlvar_name']}.'">Tweets by '.$post->{$cf['htmlvar_name']}.'</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
        $html .= '</div>';

    endif;

    return $html;
}
add_filter('geodir_custom_field_output_text_key_twitter_feed','geodir_predefined_custom_field_output_twitter_feed',10,3);

/**
 * Filter the get_directions custom field output to show a link.
 *
 * @param string $html The html to be output.
 * @param string $location The location name of the output location.
 * @param object $cf The custom field object info.
 *
 * @since 1.6.9
 * @return string The html to output.
 */
function geodir_predefined_custom_field_output_get_directions($html,$location,$cf) {
    global $post;


    if ( isset( $post->{$cf['htmlvar_name']} ) && $post->{$cf['htmlvar_name']} != '' && isset( $post->post_latitude ) && $post->post_latitude ){

        $field_icon = geodir_field_icon_proccess( $cf );
        if ( strpos( $field_icon, 'http' ) !== false ) {
            $field_icon_af = '';
        } elseif ( $field_icon == '' ) {
            $field_icon_af = '<i class="fas fa-location-arrow"></i>';
        } else {
            $field_icon_af = $field_icon;
            $field_icon    = '';
        }

        $link_text = !empty( $cf['default_value']) ? $cf['default_value'] : __( 'Get Directions', 'geodirectory' );

        $html = '<div class="geodir_more_info ' . $cf['css_class'] . ' ' . $cf['htmlvar_name'] . '" style="clear:both;">';

        if(isset( $cf['field_icon'] ) && $cf['field_icon']){
            $html .= $field_icon_af;
        }

        // We use maps.apple.com here because it will handle redirects nicely in most cases
        $html .= '<a href="https://maps.apple.com/?daddr=' . $post->post_latitude . ',' . $post->post_longitude . '" target="_blank" >' . $link_text . '</a>';
        $html .= '</div>';

    }else{
        $html ='';
    }

    return $html;
}
add_filter('geodir_custom_field_output_text_key_get_directions','geodir_predefined_custom_field_output_get_directions',10,3);
