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

    // Salary
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'text',
                      'data_type'           =>  'FLOAT',
                      'decimal_point'       =>  '2',
                      'admin_title'         =>  __('Salary', 'geodirectory'),
                      'site_title'          =>  __('Salary', 'geodirectory'),
                      'admin_desc'          =>  __('Enter the Salary in $ (no currency symbol) ie: 25000', 'geodirectory'),
                      'htmlvar_name'        =>  'salary',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  '\d+(\.\d{2})?',
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



    // Job Type
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Job Type', 'geodirectory'),
                      'site_title'          =>  __('Job Type','geodirectory'),
                      'admin_desc'          =>  __('Select the type of job.','geodirectory'),
                      'htmlvar_name'        =>  'job_type',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  true,
                      'option_values'       =>  __('Select Type/,Freelance,Full Time,Internship,Part Time,Temporary,Other','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-briefcase',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // Job Sector
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
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
    );

    // Required Experience
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'select',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Required Experience', 'geodirectory'),
                      'site_title'          =>  __('Required Experience', 'geodirectory'),
                      'admin_desc'          =>  __('Select the number of years required experience', 'geodirectory'),
                      'htmlvar_name'        =>  'job_experience',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  true,
                      'option_values'       =>  __('Select Experience/,No Experience Required,1 Year,2 Years,3 Years,4 Years,5 Years,6 Years,7 Years,8 Years,9 Years,10+ Years','geodirectory'),
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'far fa-life-ring',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );

    // Required Skills
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'textarea',
                      'data_type'           =>  'TEXT',
                      'admin_title'         =>  __('Required Skills', 'geodirectory'),
                      'site_title'          =>  __('Required Skills', 'geodirectory'),
                      'admin_desc'          =>  __('Enter the required skills for the job', 'geodirectory'),
                      'htmlvar_name'        =>  'property_area',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[detail],[listing]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-chart-area',
                      'css_class'           =>  '',
                      'cat_sort'            =>  true,
                      'cat_filter'	        =>  true
    );



    // Company details fieldset
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'fieldset',
                      'data_type'           =>  '',
                      'admin_title'         =>  __('Company Details', 'geodirectory'),
                      'site_title'          =>  __('Company Details', 'geodirectory'),
                      'admin_desc'          =>  __('Enter your company details here', 'geodirectory'),
                      'htmlvar_name'        =>  'job_company_details',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'show_in' 	        =>  '[owntab]'

    );

    // Company Name
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'text',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Company Name', 'geodirectory'),
                      'site_title'          =>  __('Company Name', 'geodirectory'),
                      'admin_desc'          =>  __('Enter your company name', 'geodirectory'),
                      'htmlvar_name'        =>  'job_company_name',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[owntab]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-arrow-circle-right',
                      'css_class'           =>  '',
                      'cat_sort'            =>  false,
                      'cat_filter'	        =>  false
    );

    // Company Logo
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'file',
                      'data_type'           =>  '',
                      'admin_title'         =>  __('Company Logo', 'geodirectory'),
                      'site_title'          =>  __('Company Logo', 'geodirectory'),
                      'admin_desc'          =>  __('Enter your company Logo', 'geodirectory'),
                      'htmlvar_name'        =>  'job_company_logo',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[owntab]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-arrow-circle-right',
                      'css_class'           =>  '',
                      'cat_sort'            =>  false,
                      'cat_filter'	        =>  false,
                      'extra'               =>  array(
                          'gd_file_types'   =>  'jpg',
                          'gd_file_types'   =>  'jpeg',
                          'gd_file_types'   =>  'gif',
                          'gd_file_types'   =>  'png',
                      )
    );

    // Company Url
    $fields[] = array('listing_type' => $post_type,
                      'field_type'          =>  'url',
                      'data_type'           =>  'VARCHAR',
                      'admin_title'         =>  __('Company Url', 'geodirectory'),
                      'site_title'          =>  __('Company Url', 'geodirectory'),
                      'admin_desc'          =>  __('Enter your company Url', 'geodirectory'),
                      'htmlvar_name'        =>  'job_company_url',
                      'is_active'           =>  true,
                      'for_admin_use'       =>  false,
                      'default_value'       =>  '',
                      'show_in' 	        =>  '[owntab]',
                      'is_required'         =>  false,
                      'validation_pattern'  =>  '',
                      'validation_msg'      =>  '',
                      'required_msg'        =>  '',
                      'field_icon'          =>  'fas fa-arrow-circle-right',
                      'css_class'           =>  '',
                      'cat_sort'            =>  false,
                      'cat_filter'	        =>  false
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
    update_option($post_type.'_dummy_data_type','property_sale');
}

if (geodir_dummy_folder_exists())
    $dummy_image_url = geodir_plugin_url() . "/geodirectory-admin/dummy";
else
    $dummy_image_url = 'http://wpgeodirectory.com/dummy';

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
