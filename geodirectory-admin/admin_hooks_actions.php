<?php
/**
 * GeoDirectory Admin.
 *
 * Main admin file which loads all settings panels and sets up admin menus.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

add_action('admin_init', 'geodir_admin_init');
if (!function_exists('geodir_admin_init')) {
    /**
     * Adds GD setting pages in admin.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global string $current_tab The current settings tab name.
     */
    function geodir_admin_init()
    {

        if (is_admin()):
            global $current_tab;
            geodir_redirect_to_admin_panel_on_installed();
            $current_tab = (isset($_GET['tab']) && $_GET['tab'] != '') ? $_GET['tab'] : 'general_settings';
            if (!(isset($_REQUEST['action']))) // this will avoid Ajax requests
                geodir_handle_option_form_submit($current_tab); // located in admin function.php
            /**
             * Called on the WordPress 'admin_init' hook this hookis used to call everything for the GD settings pages in the admin area.
             *
             * @since 1.0.0
             */
            do_action('admin_panel_init');
            add_action('geodir_admin_option_form', 'geodir_get_admin_option_form', 1);


        endif;
    }
}

/**
 * Redirects to admin page after plugin activation.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_redirect_to_admin_panel_on_installed()
{
    if (get_option('geodir_installation_redirect', false)) {
        delete_option('geodir_installation_redirect');
        wp_redirect(admin_url('admin.php?page=geodirectory&installed=yes'));
    }
}

/**
 * Displays setting form for the given tab.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $current_tab The current settings tab name.
 */
function geodir_get_admin_option_form($current_tab)
{
    geodir_admin_option_form($current_tab);// defined in admin template tags.php
}


/* Is used to show success or error message at the top of admin option panel */
add_action('geodir_update_options_compatibility_settings', 'geodir_update_options_compatibility_settings');
add_action('geodir_update_options_default_location_settings', 'geodir_location_form_submit');
add_action('geodir_before_admin_panel', 'geodir_before_admin_panel'); // this function is in admin_functions.php
add_action('geodir_before_update_options', 'geodir_before_update_options',10,2);

//add_action('geodir_before_admin_panel', 'geodir_autoinstall_admin_header');

/**
 * Admin scripts loader.
 *
 * @since 1.0.0
 * @since 1.6.0 Changes to work category icon and default image uploader for WP 4.5.
 * @since 1.6.3 Modified to fix jQuery chosen js conflicts.
 * @since 1.6.16 Fixed VC editor conflicts for GD post types.
 * @package GeoDirectory
 * @global string $pagenow The current screen.
 */
function geodir_conditional_admin_script_load()
{
    global $pagenow;
	
	// Get the current post type
	$post_type = geodir_admin_current_post_type();
	$geodir_post_types = geodir_get_posttypes();
    
	if ((isset($_REQUEST['page']) && $_REQUEST['page'] == 'geodirectory') || (($pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'edit.php') && $post_type && in_array($post_type, $geodir_post_types)) || ($pagenow == 'edit-tags.php' || $pagenow == 'term.php' || $pagenow == 'edit-comments.php' || $pagenow == 'comment.php')) {
        add_action('admin_enqueue_scripts', 'geodir_admin_scripts');
        add_action('admin_enqueue_scripts', 'geodir_admin_styles');
        add_action('admin_enqueue_scripts', 'geodir_admin_dequeue_scripts', 100);
        
        // Disable VC editor for GD post types.
        if (class_exists('Vc_Role_Access_Controller')) {
            add_filter( 'vc_role_access_with_post_types_can', '__return_false', 100 );
        }
    }

    add_action('admin_enqueue_scripts', 'geodir_admin_styles_req');

}

add_action('init', 'geodir_conditional_admin_script_load');


/**
 * Admin Menus
 */
add_action('admin_menu', 'geodir_admin_menu');

/**
 * Order admin menus
 */
add_action('menu_order', 'geodir_admin_menu_order');

add_action('admin_panel_init', 'geodir_location_form_submit'); // in location_function.php 

add_action('admin_panel_init', 'create_default_admin_main_nav', 1);
add_action('admin_panel_init', 'geodir_admin_list_columns', 2);

/* --- insert dummy post action ---*/
add_action('geodir_insert_dummy_posts', 'geodir_insert_dummy_posts', 1,3);
add_action('geodir_delete_dummy_posts', 'geodir_delete_dummy_posts', 1,3);


/**
 * Creates default admin navigation.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function create_default_admin_main_nav()
{
    add_filter('geodir_settings_tabs_array', 'geodir_default_admin_main_tabs', 1);
    add_filter('geodir_settings_tabs_array', 'places_custom_fields_tab', 2);
    add_filter('geodir_settings_tabs_array', 'geodir_compatibility_setting_tab', 90);
    add_filter('geodir_settings_tabs_array', 'geodir_tools_setting_tab', 95);
    add_filter('geodir_settings_tabs_array', 'geodir_extend_geodirectory_setting_tab', 100);
    //add_filter('geodir_settings_tabs_array', 'geodir_hide_set_location_default',3);

}


/**
 * Adds custom columns on geodirectory post types.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_admin_list_columns()
{
    if ($post_types = geodir_get_posttypes()) {

        foreach ($post_types as $post_type):
            add_filter("manage_edit-{$post_type}_columns", 'geodir_edit_post_columns', 100);
            //Filter-Payment-Manager to show Package
            add_action("manage_{$post_type}_posts_custom_column", 'geodir_manage_post_columns', 10, 2);

            add_filter("manage_edit-{$post_type}_sortable_columns", 'geodir_post_sortable_columns');
        endforeach;
    }
}

/**
 * Returns an array of main settings tabs.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $tabs Tabs array.
 * @return array Tabs array.
 */
function geodir_default_admin_main_tabs($tabs)
{
    return $tabs = array(
        'general_settings' => array('label' => __('General', 'geodirectory')),
        'design_settings' => array('label' => __('Design', 'geodirectory')),
        'permalink_settings' => array('label' => __('Permalinks', 'geodirectory')),
        'title_meta_settings' => array('label' => __('Titles & Metas', 'geodirectory')),
        'notifications_settings' => array('label' => __('Notifications', 'geodirectory')),
        'default_location_settings' => array('label' => __('Set Default Location', 'geodirectory')),

    );
}

add_action('do_meta_boxes', 'geodir_remove_image_box');
/**
 * Removes default thumbnail metabox on GD post types.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post WordPress Post object.
 */
function geodir_remove_image_box()
{
    global $post;

    $geodir_posttypes = geodir_get_posttypes();

    if (isset($post) && in_array($post->post_type, $geodir_posttypes)):

        remove_meta_box('postimagediv', $post->post_type, 'side');
        remove_meta_box('revisionsdiv', $post->post_type, 'normal');

    endif;

}


add_action('add_meta_boxes', 'geodir_meta_box_add');
/**
 * Adds meta boxes to the GD post types.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $post WordPress Post object.
 */
function geodir_meta_box_add()
{
    global $post;

    $geodir_post_types = geodir_get_posttypes('array');
    $geodir_posttypes = array_keys($geodir_post_types);

    if (isset($post->post_type) && in_array($post->post_type, $geodir_posttypes)):

        $geodir_posttype = $post->post_type;
        $post_typename = __($geodir_post_types[$geodir_posttype]['labels']['singular_name'], 'geodirectory');
        $post_typename = geodir_ucwords($post_typename);

        // Filter-Payment-Manager

        add_meta_box('geodir_post_images', $post_typename . ' ' . __('Attachments', 'geodirectory'), 'geodir_post_attachments', $geodir_posttype, 'side');

        add_meta_box('geodir_post_info', $post_typename . ' ' . __('Information', 'geodirectory'), 'geodir_post_info_setting', $geodir_posttype, 'normal', 'high');

        // no need of this box as all fields moved to main information box
        //add_meta_box( 'geodir_post_addinfo', $post_typename. ' ' .__('Additional Information' , 'geodirectory'), 'geodir_post_addinfo_setting', $geodir_posttype,'normal', 'high' );

    endif;

}

add_action('save_post', 'geodir_post_information_save',10,2);




//add_filter('geodir_design_settings' , 'geodir_show_hide_location_switcher_nav' ) ;


add_action('admin_menu', 'geodir_hide_post_taxonomy_meta_boxes');
/**
 * Removes taxonomy meta boxes.
 *
 * GeoDirectory hide categories post meta.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_hide_post_taxonomy_meta_boxes()
{

    $geodir_post_types = get_option('geodir_post_types');

    if (!empty($geodir_post_types)) {
        foreach ($geodir_post_types as $geodir_post_type => $geodir_posttype_info) {

            $gd_taxonomy = geodir_get_taxonomies($geodir_post_type);

            if(!empty($gd_taxonomy)) {
                foreach ($gd_taxonomy as $tax) {

                    remove_meta_box($tax . 'div', $geodir_post_type, 'normal');

                }
            }

        }
    }
}

add_filter('geodir_add_listing_map_restrict', 'geodir_add_listing_map_restrict');
/**
 * Checks whether to restrict the map for specific address only.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param bool $map_restirct The value before filter.
 * @return bool The value after filter.
 */
function geodir_add_listing_map_restrict($map_restirct)
{
    if (is_admin()) {
        if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'default_location_settings') {
            $map_restirct = false;
        }
    }
    return $map_restirct;
}


add_filter('geodir_notifications_settings', 'geodir_enable_editor_on_notifications', 1);

/**
 * Converts textarea field to WYSIWYG editor on Notification settings.
 *
 * WP Admin -> Geodirectory -> Notifications.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $notification The notification settings array.
 * @return array Modified notification settings array.
 */
function geodir_enable_editor_on_notifications($notification)
{

    if (!empty($notification) && get_option('geodir_tiny_editor') == '1') {

        foreach ($notification as $key => $value) {
            if ($value['type'] == 'textarea')
                $notification[$key]['type'] = 'editor';
        }

    }

    return $notification;
}


add_filter('geodir_design_settings', 'geodir_enable_editor_on_design_settings', 1);

/**
 * Converts textarea field to WYSIWYG editor on Design settings.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $design_setting The design settings array.
 * @return array Modified design settings array.
 */
function geodir_enable_editor_on_design_settings($design_setting)
{

    if (!empty($design_setting) && get_option('geodir_tiny_editor') == '1') {

        foreach ($design_setting as $key => $value) {
            if ($value['type'] == 'textarea' && $value['id'] == 'geodir_term_condition_content')
                $design_setting[$key]['type'] = 'editor';
        }

    }

    return $design_setting;
}

/* ----------- START MANAGE CUSTOM FIELDS ---------------- */
add_action('geodir_manage_available_fields_predefined', 'geodir_manage_available_fields_predefined');
add_action('geodir_manage_available_fields_custom', 'geodir_manage_available_fields_custom');

function geodir_manage_available_fields_predefined($sub_tab){
    if($sub_tab=='custom_fields'){
        geodir_custom_available_fields('predefined');
    }
}

function geodir_manage_available_fields_custom($sub_tab){
    if($sub_tab=='custom_fields'){
        geodir_custom_available_fields('custom');
    }
}


add_action('geodir_manage_available_fields', 'geodir_manage_available_fields');

/**
 * Lists available fields for the given sub tab.
 *
 * WP Admin -> Geodirectory -> (post type) Settings -> Custom Fields -> Add new Place form field.
 * WP Admin -> Geodirectory -> (post type) Settings -> Sorting Options -> Available sorting options for Place listing and search results.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $sub_tab The sub tab slug.
 */
function geodir_manage_available_fields($sub_tab)
{

    switch ($sub_tab) {
        case 'custom_fields':
            geodir_custom_available_fields();
            break;

        case 'sorting_options':
            geodir_sorting_options_available_fields();
            break;

    }
}


add_action('geodir_manage_selected_fields', 'geodir_manage_selected_fields');

/**
 * Adds admin html for selected fields of the given sub tab.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $sub_tab The sub tab slug.
 */
function geodir_manage_selected_fields($sub_tab)
{

    switch ($sub_tab) {
        case 'custom_fields':
            geodir_custom_selected_fields();
            break;

        case 'sorting_options':
            geodir_sorting_options_selected_fields();
            break;

    }
}

/**
 * Adds admin html for sorting options available fields.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_sorting_options_available_fields()
{
    global $wpdb;
    $listing_type = ($_REQUEST['listing_type'] != '') ? sanitize_text_field($_REQUEST['listing_type']) : 'gd_place';
    ?>
    <input type="hidden" name="listing_type" id="new_post_type" value="<?php echo $listing_type;?>"/>
    <input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo sanitize_text_field($_REQUEST['subtab']); ?>"/>
    <ul>
    <?php
        $sort_options = geodir_get_custom_sort_options($listing_type);
        
        foreach ($sort_options as $key => $val) {
            $val = stripslashes_deep($val); // strip slashes

            $check_html_variable = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT htmlvar_name FROM " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " WHERE htmlvar_name = %s AND post_type = %s AND field_type=%s",
                    array($val['htmlvar_name'], $listing_type, $val['field_type'])
                )
            );
            
            $display = $check_html_variable ? ' style="display:none;"' : '';
           ?>

            <li   class="gd-cf-tooltip-wrap" <?php echo $display;?>>
                <?php
                if(isset($val['description']) && $val['description']){
                    echo '<div class="gdcf-tooltip">'.$val['description'].'</div>';
                }?>

                <a id="gd-<?php echo $val['field_type'];?>-_-<?php echo $val['htmlvar_name'];?>" data-field-type-key="<?php echo $val['htmlvar_name'];?>"  data-field-type="<?php echo $val['field_type'];?>"
                   title="<?php echo $val['site_title'];?>"
                   class="gd-draggable-form-items  gd-<?php echo $val['field_type'];?> geodir-sort-<?php echo $val['htmlvar_name'];?>" href="javascript:void(0);">
                    <?php if (isset($val['field_icon']) && strpos($val['field_icon'], 'fa fa-') !== false) {
                        echo '<i class="'.$val['field_icon'].'" aria-hidden="true"></i>';
                    }elseif(isset($val['field_icon']) && $val['field_icon'] ){
                        echo '<b style="background-image: url("'.$val['field_icon'].'")"></b>';
                    }else{
                        echo '<i class="fa fa-cog" aria-hidden="true"></i>';
                    }?>
                    <?php echo $val['site_title'];?>
                </a>
            </li>


            <?php
        }
    ?>
    </ul>
    <?php
}

/**
 * Adds admin html for sorting options selected fields.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_sorting_options_selected_fields()
{
    $listing_type = ($_REQUEST['listing_type'] != '') ? sanitize_text_field($_REQUEST['listing_type']) : 'gd_place';
    ?>
    <input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo sanitize_text_field($_REQUEST['subtab']); ?>"/>
    <ul class="core">
    <?php 
        global $wpdb;
        
        $fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " WHERE post_type = %s AND field_type != 'address' ORDER BY sort_order ASC", array($listing_type)));

        if (!empty($fields)) {
            foreach ($fields as $field) {
                //$result_str = $field->id;
                $result_str = $field;
                $field_type = $field->field_type;
                $field_ins_upd = 'display';

                $default = false;

                geodir_custom_sort_field_adminhtml($field_type, $result_str, $field_ins_upd, $default);
            }
        }
    ?>
    </ul>
    <?php
}

/**
 * Returns the array of custom fields that can be used.
 *
 * @since 1.6.9
 * @package GeoDirectory
 */
function geodir_custom_fields_custom($post_type=''){

    $custom_fields = array();

    /**
     * @see `geodir_custom_fields`
     */
    return apply_filters('geodir_custom_fields_custom',$custom_fields,$post_type);
}



/**
 * Returns the array of custom fields that can be used.
 *
 * @since 1.6.6
 * @package GeoDirectory
 */
function geodir_custom_fields($post_type=''){
    
    $custom_fields = array(
        'text' => array(
            'field_type'  =>  'text',
            'class' =>  'gd-text',
            'icon'  =>  'fa fa-minus',
            'name'  =>  __('Text', 'geodirectory'),
            'description' =>  __('Add any sort of text field, text or numbers', 'geodirectory')
        ),
        'datepicker' => array(
            'field_type'  =>  'datepicker',
            'class' =>  'gd-datepicker',
            'icon'  =>  'fa fa-calendar',
            'name'  =>  __('Date', 'geodirectory'),
            'description' =>  __('Adds a date picker.', 'geodirectory')
        ),
        'textarea' => array(
            'field_type'  =>  'textarea',
            'class' =>  'gd-textarea',
            'icon'  =>  'fa fa-bars',
            'name'  =>  __('Textarea', 'geodirectory'),
            'description' =>  __('Adds a textarea', 'geodirectory')
        ),
        'time' => array(
            'field_type'  =>  'time',
            'class' =>  'gd-time',
            'icon' =>  'fa fa-clock-o',
            'name'  =>  __('Time', 'geodirectory'),
            'description' =>  __('Adds a time picker', 'geodirectory')
        ),
        'checkbox' => array(
            'field_type'  =>  'checkbox',
            'class' =>  'gd-checkbox',
            'icon' =>  'fa fa-check-square-o',
            'name'  =>  __('Checkbox', 'geodirectory'),
            'description' =>  __('Adds a checkbox', 'geodirectory')
        ),
        'phone' => array(
            'field_type'  =>  'phone',
            'class' =>  'gd-phone',
            'icon' =>  'fa fa-phone',
            'name'  =>  __('Phone', 'geodirectory'),
            'description' =>  __('Adds a phone input', 'geodirectory')
        ),
        'radio' => array(
            'field_type'  =>  'radio',
            'class' =>  'gd-radio',
            'icon' =>  'fa fa-dot-circle-o',
            'name'  =>  __('Radio', 'geodirectory'),
            'description' =>  __('Adds a radio input', 'geodirectory')
        ),
        'email' => array(
            'field_type'  =>  'email',
            'class' =>  'gd-email',
            'icon' =>  'fa fa-envelope-o',
            'name'  =>  __('Email', 'geodirectory'),
            'description' =>  __('Adds a email input', 'geodirectory')
        ),
        'select' => array(
            'field_type'  =>  'select',
            'class' =>  'gd-select',
            'icon' =>  'fa fa-caret-square-o-down',
            'name'  =>  __('Select', 'geodirectory'),
            'description' =>  __('Adds a select input', 'geodirectory')
        ),
        'multiselect' => array(
            'field_type'  =>  'multiselect',
            'class' =>  'gd-multiselect',
            'icon' =>  'fa fa-caret-square-o-down',
            'name'  =>  __('Multi Select', 'geodirectory'),
            'description' =>  __('Adds a multiselect input', 'geodirectory')
        ),
        'url' => array(
            'field_type'  =>  'url',
            'class' =>  'gd-url',
            'icon' =>  'fa fa-link',
            'name'  =>  __('URL', 'geodirectory'),
            'description' =>  __('Adds a url input', 'geodirectory')
        ),
        'html' => array(
            'field_type'  =>  'html',
            'class' =>  'gd-html',
            'icon' =>  'fa fa-code',
            'name'  =>  __('HTML', 'geodirectory'),
            'description' =>  __('Adds a html input textarea', 'geodirectory')
        ),
        'file' => array(
            'field_type'  =>  'file',
            'class' =>  'gd-file',
            'icon' =>  'fa fa-file',
            'name'  =>  __('File Upload', 'geodirectory'),
            'description' =>  __('Adds a file input', 'geodirectory')
        )
    );

    /**
     * Filter the custom fields array to be able to add or remove items.
     * 
     * @since 1.6.6
     *
     * @param array $custom_fields {
     *     The custom fields array to be filtered.
     *
     *     @type string $field_type The type of field, eg: text, datepicker, textarea, time, checkbox, phone, radio, email, select, multiselect, url, html, file.
     *     @type string $class The class for the field in backend.
     *     @type string $icon Can be font-awesome class name or icon image url.
     *     @type string $name The name of the field.
     *     @type string $description A short description about the field.
     *     @type array $defaults {
     *                    Optional. Used to set the default value of the field.
     *
     *                    @type string data_type The SQL data type for the field. VARCHAR, TEXT, TIME, TINYINT, INT, FLOAT, DATE
     *                    @type int decimal_point limit if using FLOAT data_type
     *                    @type string admin_title The admin title for the field.
     *                    @type string site_title This will be the title for the field on the frontend.
     *                    @type string admin_desc This will be shown below the field on the add listing form.
     *                    @type string htmlvar_name This is a unique identifier used in the HTML, it MUST NOT contain spaces or special characters.
     *                    @type bool is_active If false the field will not be displayed anywhere.
     *                    @type bool for_admin_use If true then only site admin can see and edit this field.
     *                    @type string default_value The default value for the input on the add listing page.
     *                    @type string show_in The locations to show in. [detail],[moreinfo],[listing],[owntab],[mapbubble]
     *                    @type bool is_required If true the field will be required on the add listing page.
     *                    @type string option_values The option values for select and multiselect only
     *                    @type string validation_pattern HTML5 validation pattern (text input only by default).
     *                    @type string validation_msg HTML5 validation message (text input only by default).
     *                    @type string required_msg Required warning message.
     *                    @type string field_icon Icon url or font awesome class.
     *                    @type string css_class Field custom css class for field custom style.
     *                    @type bool cat_sort If true the field will appear in the category sort options, if false the field will be hidden, leave blank to show option.
     *                    @type bool cat_sort If true the field will appear in the advanced search sort options, if false the field will be hidden, leave blank to show option. (advanced search addon required)
     *     }
     * }
     * @param string $post_type The post type requested.
     */
    return apply_filters('geodir_custom_fields',$custom_fields,$post_type);
}

/**
 * Adds admin html for custom fields available fields.
 *
 * @since 1.0.0
 * @since 1.6.9 Added
 * @param string $type The custom field type, predefined, custom or blank for default
 * @package GeoDirectory
 */
function geodir_custom_available_fields($type='')
{
    $listing_type = ($_REQUEST['listing_type'] != '') ? sanitize_text_field($_REQUEST['listing_type']) : 'gd_place';
    ?>
    <input type="hidden" name="listing_type" id="new_post_type" value="<?php echo $listing_type;?>"/>
    <input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo sanitize_text_field($_REQUEST['subtab']); ?>" />

        <?php
        if($type=='predefined'){
            $cfs = geodir_custom_fields_predefined($listing_type);
        }elseif($type=='custom'){
            $cfs = geodir_custom_fields_custom($listing_type);
        }else{
            $cfs = geodir_custom_fields($listing_type);
            ?>
            <ul class="full gd-cf-tooltip-wrap">
                <li>
                    <div class="gdcf-tooltip">
                        <?php _e('This adds a section separator with a title.', 'geodirectory');?>
                    </div>
                    <a id="gt-fieldset"
                       class="gd-draggable-form-items gt-fieldset"
                       href="javascript:void(0);"
                       data-field-custom-type=""
                       data-field-type="fieldset"
                       data-field-type-key="fieldset">

                        <i class="fa fa-long-arrow-left " aria-hidden="true"></i>
                        <i class="fa fa-long-arrow-right " aria-hidden="true"></i>
                        <?php _e('Fieldset (section separator)', 'geodirectory');?>
                    </a>
                </li>
            </ul>

            <?php
        }

    if(!empty($cfs)) {
        echo '<ul>';
        foreach ( $cfs as $id => $cf ) {
            ?>

            <li class="gd-cf-tooltip-wrap">
                <?php
                if ( isset( $cf['description'] ) && $cf['description'] ) {
                    echo '<div class="gdcf-tooltip">' . $cf['description'] . '</div>';
                } ?>

                <a id="gd-<?php echo $id; ?>"
                   data-field-custom-type="<?php echo $type; ?>"
                   data-field-type-key="<?php echo $id; ?>"
                   data-field-type="<?php echo $cf['field_type']; ?>"
                   class="gd-draggable-form-items <?php echo $cf['class']; ?>"
                   href="javascript:void(0);">

                    <?php if ( isset( $cf['icon'] ) && strpos( $cf['icon'], 'fa fa-' ) !== false ) {
                        echo '<i class="' . $cf['icon'] . '" aria-hidden="true"></i>';
                    } elseif ( isset( $cf['icon'] ) && $cf['icon'] ) {
                        echo '<b style="background-image: url("' . $cf['icon'] . '")"></b>';
                    } else {
                        echo '<i class="fa fa-cog" aria-hidden="true"></i>';
                    } ?>
                    <?php echo $cf['name']; ?>
                </a>
            </li>
            <?php
        }
    }else{
        _e('There are no custom fields here yet.', 'geodirectory');
    }
        ?>


    </ul>

<?php

}


/**
 * Adds admin html for custom fields selected fields.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_custom_selected_fields()
{
    $listing_type = ($_REQUEST['listing_type'] != '') ? sanitize_text_field($_REQUEST['listing_type']) : 'gd_place';
    ?>
    <input type="hidden" name="manage_field_type" class="manage_field_type" value="<?php echo sanitize_text_field($_REQUEST['subtab']); ?>"/>
    <ul class="core">
    <?php 
        global $wpdb;
        $fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . GEODIR_CUSTOM_FIELDS_TABLE . " WHERE post_type = %s ORDER BY sort_order ASC", array($listing_type)));

        if (!empty($fields)) {
            foreach ($fields as $field) {
                //$result_str = $field->id;
                $result_str = $field;
                $field_type = $field->field_type;
                $field_type_key = $field->field_type_key;
                $field_ins_upd = 'display';

                geodir_custom_field_adminhtml($field_type, $result_str, $field_ins_upd,$field_type_key);
            }
        }
        ?></ul>
<?php

}

add_filter('geodir_custom_fields_panel_head', 'geodir_custom_fields_panel_head', 1, 3);
/**
 * Returns heading for given sub tab.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $heading The page heading.
 * @param string $sub_tab The sub tab slug.
 * @param string $listing_type The post type.
 * @return string The page heading.
 */
function geodir_custom_fields_panel_head($heading, $sub_tab, $listing_type)
{

    switch ($sub_tab) {
        case 'custom_fields':
            $heading = sprintf(__('Manage %s Custom Fields', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;

        case 'sorting_options':
            $heading = sprintf(__('Manage %s Listing Sorting Options Fields', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;
    }
    return $heading;
}


add_filter('geodir_cf_panel_available_fields_head', 'geodir_cf_panel_available_fields_head', 1, 3);
/**
 * Returns heading for given sub tab available fields box.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $heading The page heading.
 * @param string $sub_tab The sub tab slug.
 * @param string $listing_type The post type.
 * @return string The page heading.
 */
function geodir_cf_panel_available_fields_head($heading, $sub_tab, $listing_type)
{

    switch ($sub_tab) {
        case 'custom_fields':
            $heading = sprintf(__('Add new %s form field', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;

        case 'sorting_options':
            $heading = sprintf(__('Available sorting options for %s listing and search results', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;
    }
    return $heading;
}


add_filter('geodir_cf_panel_available_fields_note', 'geodir_cf_panel_available_fields_note', 1, 3);
/**
 * Returns description for given sub tab - available fields box.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $note The box description.
 * @param string $sub_tab The sub tab slug.
 * @param string $listing_type The post type.
 * @return string The box description.
 */
function geodir_cf_panel_available_fields_note($note, $sub_tab, $listing_type)
{

    switch ($sub_tab) {
        case 'custom_fields':
            $note = sprintf(__('Click on any box below to add a field of that type to the add %s listing form. You can use a fieldset to group your fields.', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;

        case 'sorting_options':
            $note = sprintf(__('Click on any box below to make it appear in the sorting option dropdown on %s listing and search results.<br />To make a field available here, go to custom fields tab and expand any field from selected fields panel and tick the checkbox saying \'Include this field in sort option\'.', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;
    }
    return $note;
}


add_filter('geodir_cf_panel_selected_fields_head', 'geodir_cf_panel_selected_fields_head', 1, 3);
/**
 * Returns heading for given sub tab selected fields box.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $heading The page heading.
 * @param string $sub_tab The sub tab slug.
 * @param string $listing_type The post type.
 * @return string The page heading.
 */
function geodir_cf_panel_selected_fields_head($heading, $sub_tab, $listing_type)
{

    switch ($sub_tab) {
        case 'custom_fields':
            $heading = sprintf(__('List of fields that will appear on add new %s listing form', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;

        case 'sorting_options':
            $heading = sprintf(__('List of fields that will appear in %s listing and search results sorting option dropdown box.', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;
    }
    return $heading;
}


add_filter('geodir_cf_panel_selected_fields_note', 'geodir_cf_panel_selected_fields_note', 1, 3);
/**
 * Returns description for given sub tab - selected fields box.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $note The box description.
 * @param string $sub_tab The sub tab slug.
 * @param string $listing_type The post type.
 * @return string The box description.
 */
function geodir_cf_panel_selected_fields_note($note, $sub_tab, $listing_type)
{

    switch ($sub_tab) {
        case 'custom_fields':
            $note = sprintf(__('Click to expand and view field related settings. You may drag and drop to arrange fields order on add %s listing form too.', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;

        case 'sorting_options':
            $note = sprintf(__('Click to expand and view field related settings. You may drag and drop to arrange fields order in sorting option dropdown box on %s listing and search results page.', 'geodirectory'), get_post_type_singular_label($listing_type, false, true));
            break;
    }
    return $note;
}


add_action('admin_init', 'geodir_remove_unnecessary_fields');

/**
 * Removes unnecessary table columns from the database.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_remove_unnecessary_fields()
{
    global $wpdb, $plugin_prefix;

    if (!get_option('geodir_remove_unnecessary_fields')) {

        if ($wpdb->get_var("SHOW COLUMNS FROM " . $plugin_prefix . "gd_place_detail WHERE field = 'categories'"))
            $wpdb->query("ALTER TABLE `" . $plugin_prefix . "gd_place_detail` DROP `categories`");

        update_option('geodir_remove_unnecessary_fields', '1');

    }

}


/* ----------- END MANAGE CUSTOM FIELDS ---------------- */

/* Ajax Handler Start */
add_action('wp_ajax_geodir_admin_ajax', "geodir_admin_ajax_handler");

/**
 * Handles admin ajax.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function geodir_admin_ajax_handler()
{
    if (isset($_REQUEST['geodir_admin_ajax_action']) && $_REQUEST['geodir_admin_ajax_action'] != '') {
        $geodir_admin_ajax_action = $_REQUEST['geodir_admin_ajax_action'];
        $diagnose_this = "";
        switch ($geodir_admin_ajax_action) {
            case 'diagnosis' :
                if (isset($_REQUEST['diagnose_this']) && $_REQUEST['diagnose_this'] != '') {
                    $diagnose_this = sanitize_text_field($_REQUEST['diagnose_this']);
                    call_user_func('geodir_diagnose_' . $diagnose_this);

                }
                exit();
                break;

            case 'diagnosis-fix' :
                if (isset($_REQUEST['diagnose_this']) && $_REQUEST['diagnose_this'] != '')
                    $diagnose_this = sanitize_text_field($_REQUEST['diagnose_this']);
                call_user_func('geodir_diagnose_' . $diagnose_this);
                exit();
                break;
        }
    }
    exit();
}


/**
 * Diagnose multisite related tables.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $filter_arr The diagnose messages array.
 * @param string $table The table name. Ex: geodir_countries.
 * @param string $tabel_name Human readable table name. Ex: Geodir Countries.
 * @param bool $fix If error during diagnose do you want to fix it? Default: false.
 * @return array The diagnose messages array.
 */
function geodir_diagnose_multisite_table($filter_arr, $table, $tabel_name, $fix)
{
    global $wpdb;
    //$filter_arr['output_str'] .='###'.$table.'###';
    if ($wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak2'") > 0 && $wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak'") > 0) {
        $filter_arr['output_str'] .= "<li>" . __('ERROR: You did not follow instructions! Now you will need to contact support to manually fix things.', 'geodirectory') . "</li>";
        $filter_arr['is_error_during_diagnose'] = true;

    } elseif ($wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak'") > 0 && $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "$table'") > 0) {
        $filter_arr['output_str'] .= "<li>" . sprintf(__('ERROR: %s_ms_bak table found', 'geodirectory'), $tabel_name) . "</li>";
        $filter_arr['is_error_during_diagnose'] = true;
        $filter_arr['output_str'] .= "<li>" . __('IMPORTANT: This can be caused by out of date core or addons, please update core + addons before trying the fix OR YOU WILL HAVE A BAD TIME!', 'geodirectory') . "</li>";
        $filter_arr['is_error_during_diagnose'] = true;

        if ($fix) {
            $ms_bak_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $table . "_ms_bak");// get backup table count
            $new_table_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "$table");// get new table count

            if ($ms_bak_count == $new_table_count) {// if they are the same count rename to bak2
                //$filter_arr['output_str'] .= "<li>".sprintf( __('-->PROBLEM: %s table count is the same as new table, contact support' , 'geodirectory'), $table )."</li>" ;

                $wpdb->query("RENAME TABLE " . $table . "_ms_bak TO " . $table . "_ms_bak2");// rename bak table to new table

                if ($wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak2'") && $wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak'") == 0) {
                    $filter_arr['output_str'] .= "<li>" . __('-->FIXED: Renamed and backed up the tables', 'geodirectory') . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . __('-->PROBLEM: Failed to rename tables, please contact support.', 'geodirectory') . "</li>";
                }

            } elseif ($ms_bak_count > $new_table_count) {//if backup is greater then restore it

                $wpdb->query("RENAME TABLE " . $wpdb->prefix . "$table TO " . $table . "_ms_bak2");// rename new table to bak2
                $wpdb->query("RENAME TABLE " . $table . "_ms_bak TO " . $wpdb->prefix . "$table");// rename bak table to new table

                if ($wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak2'") && $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "$table'") && $wpdb->query("SHOW TABLES LIKE '$table'") == 0) {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: restored largest table %s', 'geodirectory'), $table) . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . __('-->PROBLEM: Failed to rename tables, please contact support.', 'geodirectory') . "</li>";
                }

            } elseif ($new_table_count > $ms_bak_count) {// we cant do much so rename the table to stop errors

                $wpdb->query("RENAME TABLE " . $table . "_ms_bak TO " . $table . "_ms_bak2");// rename ms_bak table to ms_bak2

                if ($wpdb->query("SHOW TABLES LIKE '" . $table . "_ms_bak'") == 0) {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: table %s_ms_bak renamed and backed up', 'geodirectory'), $table) . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . __('-->PROBLEM: Failed to rename tables, please contact support.', 'geodirectory') . "</li>";
                }

            }

        }


    } elseif ($wpdb->query("SHOW TABLES LIKE '$table'") > 0 && $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "$table'") > 0) {
        $filter_arr['output_str'] .= "<li>" . sprintf(__('ERROR: Two %s tables found', 'geodirectory'), $tabel_name) . "</li>";
        $filter_arr['is_error_during_diagnose'] = true;

        if ($fix) {
            if ($wpdb->get_var("SELECT COUNT(*) FROM $table") == 0) {// if first table is empty just delete it
                if ($wpdb->query("DROP TABLE IF EXISTS $table")) {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: Deleted table %s', 'geodirectory'), $table) . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->PROBLEM: Delete table %s failed, please try manual delete from DB', 'geodirectory'), $table) . "</li>";
                }

            } elseif ($wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "$table") == 0) {// if main table is empty but original is not, delete main and rename original
                if ($wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "$table")) {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: Deleted table %s', 'geodirectory'), $wpdb->prefix . $table) . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->PROBLEM: Delete table %s failed, please try manual delete from DB', 'geodirectory'), $wpdb->prefix . $table) . "</li>";
                }
                if ($wpdb->query("RENAME TABLE $table TO " . $wpdb->prefix . "$table") || $wpdb->query("SHOW TABLES LIKE '$table'") == 0) {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: Table %s renamed to %s', 'geodirectory'), $table, $wpdb->prefix . $table) . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->PROBLEM: Failed to rename table %s to %s, please try manually from DB', 'geodirectory'), $table, $wpdb->prefix . $table) . "</li>";
                }
            } else {// else rename the original table to _ms_bak
                if ($wpdb->query("RENAME TABLE $table TO " . $table . "_ms_bak") || $wpdb->query("SHOW TABLES LIKE '$table'") == 0) {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: Table contained info so we renamed %s to %s incase it is needed in future', 'geodirectory'), $table, $table . "_ms_bak") . "</li>";
                } else {
                    $filter_arr['output_str'] .= "<li>" . sprintf(__('-->PROBLEM: Table %s could not be renamed to %s, this table has info so may need to be reviewed manually in the DB', 'geodirectory'), $table, $table . "_ms_bak") . "</li>";
                }
            }
        }

    } elseif ($wpdb->query("SHOW TABLES LIKE '$table'") > 0 && $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "$table'") == 0) {
        $filter_arr['output_str'] .= "<li>" . sprintf(__('ERROR: %s table not converted', 'geodirectory'), $tabel_name) . "</li>";
        $filter_arr['is_error_during_diagnose'] = true;

        if ($fix) {
            // if original table exists but new does not, rename
            if ($wpdb->query("RENAME TABLE $table TO " . $wpdb->prefix . "$table") || $wpdb->query("SHOW TABLES LIKE '$table'") == 0) {
                $filter_arr['output_str'] .= "<li>" . sprintf(__('-->FIXED: Table %s renamed to %s', 'geodirectory'), $table, $wpdb->prefix . $table) . "</li>";
            } else {
                $filter_arr['output_str'] .= "<li>" . sprintf(__('-->PROBLEM: Failed to rename table %s to %s, please try manually from DB', 'geodirectory'), $table, $wpdb->prefix . $table) . "</li>";
            }

        }

    } elseif ($wpdb->query("SHOW TABLES LIKE '$table'") == 0 && $wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "$table'") == 0) {
        $filter_arr['output_str'] .= "<li>" . sprintf(__('ERROR: %s table does not exist', 'geodirectory'), $tabel_name) . "</li>";
        $filter_arr['is_error_during_diagnose'] = true;

        if ($fix) {
            // if original table does not exist try deleting db_vers of all addons so the initial db_install scripts run;
            delete_option('geodirlocation_db_version');
            delete_option('geodirevents_db_version');
            delete_option('geodir_reviewrating_db_version');
            delete_option('gdevents_db_version');
            delete_option('geodirectory_db_version');
            delete_option('geodirclaim_db_version');
            delete_option('geodir_custom_posts_db_version');
            delete_option('geodir_reviewratings_db_version');
            delete_option('geodiradvancesearch_db_version');
            $filter_arr['output_str'] .= "<li>" . __('-->TRY: Please refresh page to run table install functions', 'geodirectory') . "</li>";
        }

    } else {
        $filter_arr['output_str'] .= "<li>" . sprintf(__('%s table converted correctly', 'geodirectory'), $tabel_name) . "</li>";
    }
    return $filter_arr;
}


/**
 * Syncs when tags are showing in the backend but missing from the front end.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_diagnose_tags_sync()
{
    global $wpdb, $plugin_prefix;
    $fix = isset($_POST['fix']) ? true : false;
    $step = isset($_POST['step']) ? strip_tags(esc_sql($_POST['step'])) : 0;
    $step_max_items = geodir_get_diagnose_step_max_items();
    $offset = (int) $step * $step_max_items;
    $ptype = isset($_POST['ptype']) ? strip_tags(esc_sql($_POST['ptype'])) : false;

    $total_listings = geodir_total_listings_count();
    $total_ptype_listings = 0;
    if ($ptype) {
        $total_ptype_listings = geodir_total_listings_count($ptype);
    }
    $max_step = ceil($total_ptype_listings / $step_max_items) - 1;
    
    //if($fix){echo 'true';}else{echo 'false';}
    $is_error_during_diagnose = false;
    $output_str = '';
    
    if ($ptype && !empty($ptype) && $total_listings > $step_max_items) {
        $stepped_process = true;
    } else {
        $stepped_process = false;
    }

    if ($stepped_process) {
        $sql = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "geodir_" . $ptype . "_detail LIMIT %d OFFSET %d", $step_max_items, $offset );
        $posts = $wpdb->get_results( $sql );

        if (!empty($posts)) {

            foreach ($posts as $p) {
                $p->post_type = $ptype;
                $raw_tags = wp_get_object_terms($p->post_id, $p->post_type . '_tags', array('fields' => 'names'));
                if (empty($raw_tags)) {
                    $post_tags = '';
                } else {
                    $post_tags = implode(",", $raw_tags);
                }
                $tablename = $plugin_prefix . $p->post_type . '_detail';
                $wpdb->query($wpdb->prepare("UPDATE " . $tablename . " SET post_tags=%s WHERE post_id =%d", $post_tags, $p->post_id));

            }
            if ($step >= $max_step) {
                $output_str = "done";    
            } else {
                $output_str = $step + 1;
            }
        }

    } else {
        $all_postypes = geodir_get_posttypes();

        if (!empty($all_postypes)) {
            foreach ($all_postypes as $key) {
                // update each GD CPT
                $posts = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "geodir_" . $key . "_detail");

                if (!empty($posts)) {

                    foreach ($posts as $p) {
                        $p->post_type = $key;
                        $raw_tags = wp_get_object_terms($p->post_id, $p->post_type . '_tags', array('fields' => 'names'));
                        if (empty($raw_tags)) {
                            $post_tags = '';
                        } else {
                            $post_tags = implode(",", $raw_tags);
                        }
                        $tablename = $plugin_prefix . $p->post_type . '_detail';
                        $wpdb->query($wpdb->prepare("UPDATE " . $tablename . " SET post_tags=%s WHERE post_id =%d", $post_tags, $p->post_id));

                    }
                    $output_str .= "<li>" . $key . __(': Done', 'geodirectory') . "</li>";
                }

            }

        }
    }


    if ($is_error_during_diagnose) {
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "<input type='button' value='" . __('Fix', 'geodirectory') . "' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='ratings' />";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }

    if ($stepped_process) {
        $percent = ($step/$max_step) * 100;
        if ($output_str == 'done') {
            echo $output_str;
        } else {
            $output = array(
                'step' => $output_str,
                'percent' => $percent
            );
            echo json_encode($output);
        }
    } else {
        echo "<ul class='$info_div_class'>";
        echo $output_str;
        echo $fix_button_txt;
        echo "</ul>";
    }
}

/**
 * Syncs when categories are missing from the details table but showing in other places in the backend.
 *
 * Only checks posts with missing category info in details table.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_diagnose_cats_sync()
{
    global $wpdb, $plugin_prefix;
    $fix = isset($_POST['fix']) ? true : false;

    //if($fix){echo 'true';}else{echo 'false';}
    $is_error_during_diagnose = false;
    $output_str = '';


    $all_postypes = geodir_get_posttypes();

    if (!empty($all_postypes)) {
        foreach ($all_postypes as $key) {
            // update each GD CTP
            $posts = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "geodir_" . $key . "_detail d WHERE d." . $key . "category='' ");

            if (!empty($posts)) {

                foreach ($posts as $p) {
                    $p->post_type = $key;
                    $raw_cats = wp_get_object_terms($p->post_id, $p->post_type . 'category', array('fields' => 'ids'));

                    if (empty($raw_cats)) {
                        $post_categories = get_post_meta($p->post_id, 'post_categories', true);

                        if (!empty($post_categories) && !empty($post_categories[$p->post_type . 'category'])) {
                            $post_categories[$p->post_type . 'category'] = str_replace("d:", "", $post_categories[$p->post_type . 'category']);
                            foreach (explode(",", $post_categories[$p->post_type . 'category']) as $cat_part) {
                                if (is_numeric($cat_part)) {
                                    $raw_cats[] = (int)$cat_part;
                                }
                            }

                        }

                        if (!empty($raw_cats)) {
                            $term_taxonomy_ids = wp_set_object_terms($p->post_id, $raw_cats, $p->post_type . 'category');

                        }

                    }


                    if (empty($raw_cats)) {
                        $post_cats = '';
                    } else {
                        $post_cats = ',' . implode(",", $raw_cats) . ',';
                    }
                    $tablename = $plugin_prefix . $p->post_type . '_detail';
                    $wpdb->query($wpdb->prepare("UPDATE " . $tablename . " SET " . $p->post_type . "category=%s WHERE post_id =%d", $post_cats, $p->post_id));
                }

            }
            $output_str .= "<li>" . $key . __(': Done', 'geodirectory') . "</li>";

        }

    }

    if ($is_error_during_diagnose) {
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "<input type='button' value='" . __('Fix', 'geodirectory') . "' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='ratings' />";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";

}

/**
 * Clears all GD version numbers so any upgrade functions will run again.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_diagnose_version_clear()
{
    global $wpdb, $plugin_prefix;
    $fix = isset($_POST['fix']) ? true : false;

    //if($fix){echo 'true';}else{echo 'false';}
    $is_error_during_diagnose = false;
    $output_str = '';


    $gd_arr = array('GeoDirectory' => 'geodirectory_db_version',
        'Payment Manager' => 'geodir_payments_db_version',
        'GeoDirectory Framework' => 'gdf_db_version',
        'Advanced Search' => 'geodiradvancesearch_db_version',
        'Review Rating Manager' => 'geodir_reviewratings_db_version',
        'Claim Manager' => 'geodirclaim_db_version',
        'CPT Manager' => 'geodir_custom_posts_db_version',
        'Location Manager' => 'geodirlocation_db_version',
        'Payment Manager' => 'geodir_payments_db_version',
        'Events Manager' => 'geodirevents_db_version',
    );

    /**
     * Filter the array of plugins to clear the version numbers for in the GD >Tools : clear all version numbers.
     *
     * @since 1.0.0
     * @param array $gd_arr The array or addons to clear, array('GeoDirectory' => 'geodirectory_db_version',...
     */
    $ver_arr = apply_filters('geodir_db_version_name', $gd_arr);

    if (!empty($ver_arr)) {
        foreach ($ver_arr as $key => $val) {
            if (delete_option($val)) {
                $output_str .= "<li>" . $key . __(' Version: Deleted', 'geodirectory') . "</li>";
            } else {
                $output_str .= "<li>" . $key . __(' Version: Not Found', 'geodirectory') . "</li>";
            }

        }

        if ($output_str) {
            $output_str .= "<li><strong>" . __(' Upgrade/install scripts will run on next page reload.', 'geodirectory') . "</strong></li>";
        }

    }

    if ($is_error_during_diagnose) {
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";

}


/**
 * Checks ratings for correct location and content settings.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_diagnose_ratings()
{
    global $wpdb;
    $fix = isset($_POST['fix']) ? true : false;

    //if($fix){echo 'true';}else{echo 'false';}
    $is_error_during_diagnose = false;
    $output_str = '';

    // check review locations
    if ($wpdb->get_results("SELECT * FROM " . GEODIR_REVIEW_TABLE . " WHERE post_city='' OR post_city IS NULL OR post_latitude='' OR post_latitude IS NULL")) {
        $output_str .= "<li>" . __('Review locations missing or broken', 'geodirectory') . "</li>";
        $is_error_during_diagnose = true;

        if ($fix) {
            if (geodir_fix_review_location()) {
                $output_str .= "<li><strong>" . __('-->FIXED: Review locations fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Review locations fix failed', 'geodirectory') . "</strong></li>";
            }
        }

    } else {
        $output_str .= "<li>" . __('Review locations ok', 'geodirectory') . "</li>";
    }

    // check review content
    if ($wpdb->get_results("SELECT * FROM " . GEODIR_REVIEW_TABLE . " WHERE comment_content IS NULL")) {
        $output_str .= "<li>" . __('Review content missing or broken', 'geodirectory') . "</li>";
        $is_error_during_diagnose = true;

        if ($fix) {
            if (geodir_fix_review_content()) {
                $output_str .= "<li><strong>" . __('-->FIXED: Review content fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Review content fix failed', 'geodirectory') . "</strong></li>";
            }
        }

    } else {
        $output_str .= "<li>" . __('Review content ok', 'geodirectory') . "</li>";
    }


    if ($is_error_during_diagnose) {
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "<input type='button' value='" . __('Fix', 'geodirectory') . "' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='ratings' />";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";

}


/**
 * Checks if the GD database tables have been converted to use multisite correctly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_diagnose_multisite_conversion()
{
    global $wpdb;
    $fix = isset($_POST['fix']) ? true : false;
    //if($fix){echo 'true';}else{echo 'false';}
    $is_error_during_diagnose = false;
    $output_str = '';

    $filter_arr = array();
    $filter_arr['output_str'] = $output_str;
    $filter_arr['is_error_during_diagnose'] = $is_error_during_diagnose;
    $table_arr = array('geodir_countries' => __('Countries', 'geodirectory'),
        'geodir_custom_fields' => __('Custom fields', 'geodirectory'),
        'geodir_post_icon' => __('Post icon', 'geodirectory'),
        'geodir_attachments' => __('Attachments', 'geodirectory'),
        'geodir_post_review' => __('Reviews', 'geodirectory'),
        'geodir_custom_sort_fields' => __('Custom sort fields', 'geodirectory'),
        'geodir_gd_place_detail' => __('Place detail', 'geodirectory')
    );

    // allow other addons to hook in and add their checks

    /**
     * Filter the array of tables.
     *
     * Filter the array of tables to check during the GD>Tools multisite DB conversion tool check, this allows addons to add their DB tables to the checks.
     *
     * @since 1.0.0
     * @param array $table_arr The array of tables to check, array('geodir_countries' => __('Countries', 'geodirectory'),...
     */
    $table_arr = apply_filters('geodir_diagnose_multisite_conversion', $table_arr);

    foreach ($table_arr as $table => $table_name) {
        // Diagnose table
        $filter_arr = geodir_diagnose_multisite_table($filter_arr, $table, $table_name, $fix);
    }


    $output_str = $filter_arr['output_str'];
    $is_error_during_diagnose = $filter_arr['is_error_during_diagnose'];


    if ($is_error_during_diagnose) {
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "<input type='button' value='" . __('Fix', 'geodirectory') . "' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='multisite_conversion' />";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";
}

/**
 * Fixes if the GD pages are not installed correctly.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $current_user Current user object.
 * @param string $slug The page slug.
 * @param string $page_title The page title.
 * @param int $old_id Old post ID.
 * @param string $option Option meta key.
 * @return bool Returns true when success. false when failure.
 */
function geodir_fix_virtual_page($slug, $page_title, $old_id, $option)
{
    global $wpdb, $current_user;

    if (!empty($old_id)) {
        wp_delete_post($old_id, true);
    }//delete post if already there
    else {
        $page_found = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;",
                array($slug)
            )
        );
        wp_delete_post($page_found, true);

    }

    $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => $current_user->ID,
        'post_name' => $slug,
        'post_title' => $page_title,
        'post_content' => '',
        'post_parent' => 0,
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option($option, $page_id);
    if ($page_id) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if the GD pages are installed correctly or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_diagnose_default_pages()
{
    global $wpdb;
    $is_error_during_diagnose = false;
    $output_str = '';
    $fix = isset($_POST['fix']) ? true : false;

    //////////////////////////////////
    /* Diagnose GD Home Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_home_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('GD Home page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('GD Home page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('gd-home', __('GD Home page', 'geodirectory'), $page_found, 'geodir_home_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: GD Home page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: GD Home page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose GD Home Page Ends */
    ////////////////////////////////

    //////////////////////////////////
    /* Diagnose Add Listing Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_add_listing_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('Add Listing page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('Add Listing page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('add-listing', __('Add Listing', 'geodirectory'), $page_found, 'geodir_add_listing_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: Add Listing page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Add Listing page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose Add Listing Page Ends */
    ////////////////////////////////


    //////////////////////////////////
    /* Diagnose Listing Preview Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_preview_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('Listing Preview page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('Listing Preview page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('listing-preview', __('Listing Preview', 'geodirectory'), $page_found, 'geodir_preview_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: Listing Preview page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Listing Preview page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose Listing Preview Page Ends */
    ////////////////////////////////

    //////////////////////////////////
    /* Diagnose Listing Success Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_success_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('Listing Success page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('Listing Success page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('listing-success', __('Listing Success', 'geodirectory'), $page_found, 'geodir_success_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: Listing Success page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Listing Success page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose Listing Sucess Page Ends */
    ////////////////////////////////

    //////////////////////////////////
    /* Diagnose Info Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_info_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('Info page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('Info page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('gd-info', __('Info', 'geodirectory'), $page_found, 'geodir_info_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: Info page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Info page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose Info Page Ends */
    ////////////////////////////////

    //////////////////////////////////
    /* Diagnose Login Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_login_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('Login page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('Login page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('gd-login', __('Login', 'geodirectory'), $page_found, 'geodir_login_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: Login page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Login page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose Info Page Ends */
    ////////////////////////////////

    //////////////////////////////////
    /* Diagnose Location Page Starts */
    //////////////////////////////////
    $option_value = get_option('geodir_location_page');
    $page = get_post($option_value);
    if(!empty($page)){$page_found = $page->ID;}else{$page_found = '';}

    if(!empty($option_value) && !empty($page_found) && $option_value == $page_found && $page->post_status=='publish')
        $output_str .= "<li>" . __('Location page exists with proper setting.', 'geodirectory') . "</li>";
    else {
        $is_error_during_diagnose = true;
        $output_str .= "<li><strong>" . __('Location page is missing.', 'geodirectory') . "</strong></li>";
        if ($fix) {
            if (geodir_fix_virtual_page('location', __('Location', 'geodirectory'), $page_found, 'geodir_location_page')) {
                $output_str .= "<li><strong>" . __('-->FIXED: Location page fixed', 'geodirectory') . "</strong></li>";
            } else {
                $output_str .= "<li><strong>" . __('-->FAILED: Location page fix failed', 'geodirectory') . "</strong></li>";
            }
        }
    }

    ////////////////////////////////
    /* Diagnose Location Page Ends */
    ////////////////////////////////

    $page_chk_arr = array('output_str'=>$output_str,'is_error_during_diagnose'=>$is_error_during_diagnose );
    /**
     * This action is called at the end of the GD Tools page check function.
     *
     * @since 1.5.2
     */
    $page_chk_arr = apply_filters('geodir_diagnose_default_pages',$page_chk_arr);

    $output_str = $page_chk_arr['output_str'];
    $is_error_during_diagnose = $page_chk_arr['is_error_during_diagnose'];

    if ($is_error_during_diagnose) {
        if ($fix) {
            flush_rewrite_rules();
        }
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "<input type='button' value='" . __('Fix', 'geodirectory') . "' class='button-primary geodir_fix_diagnostic_issue' data-diagnostic-issue='default_pages' />";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";

}

/**
 * Loads custom fields in to file for translation.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
function geodir_diagnose_load_db_language() {
    global $wpdb;
	
	$is_error_during_diagnose = geodirectory_load_db_language();

    $output_str = '';
    $fix_button_txt = '';

    if ($is_error_during_diagnose) {
        $output_str .= "<li>" . __('Fail to load custom fields in to file for translation, please check file permission:', 'geodirectory') . ' ' . geodir_plugin_path() . '/db-language.php' . "</li>";
		$info_div_class = "geodir_problem_info";
    } else {
        $output_str .= "<li>" . __('Load custom fields in to file for translation: ok', 'geodirectory') . "</li>";
		$info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    
	echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";

}

/* Ajax Handler Ends*/

add_filter('posts_clauses_request', 'geodir_posts_clauses_request');
/**
 * Adds sorting type - sort by expire.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $wp_query WordPress Query object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param array $clauses {
 *    Attributes of the clause array.
 *
 *    @type string $where Where clause.
 *    @type string $groupby Groupby clause.
 *    @type string $join Join clause.
 *    @type string $orderby Orderby clause.
 *    @type string $distinct Distinct clause.
 *    @type string $fields Fields clause.
 *    @type string $limits Limits clause.
 *
 * }
 * @return array Altered clause array.
 */
function geodir_posts_clauses_request($clauses)
{
    global $wpdb, $wp_query, $plugin_prefix;

    if (is_admin() && !empty($wp_query->query_vars) && !empty($wp_query->query_vars['is_geodir_loop']) && !empty($wp_query->query_vars['orderby']) && $wp_query->query_vars['orderby'] == 'expire' && !empty($wp_query->query_vars['post_type']) && in_array($wp_query->query_vars['post_type'], geodir_get_posttypes()) && !empty($wp_query->query_vars['orderby']) && isset($clauses['join']) && isset($clauses['orderby']) && isset($clauses['fields'])) {
        $table = $plugin_prefix . $wp_query->query_vars['post_type'] . '_detail';

        $join = $clauses['join'] . ' INNER JOIN ' . $table . ' AS gd_posts ON (gd_posts.post_id = ' . $wpdb->posts . '.ID)';
        $clauses['join'] = $join;

        $fields = $clauses['fields'] != '' ? $clauses['fields'] . ', ' : '';
        $fields .= 'IF(UNIX_TIMESTAMP(DATE_FORMAT(gd_posts.expire_date, "%Y-%m-%d")), UNIX_TIMESTAMP(DATE_FORMAT(gd_posts.expire_date, "%Y-%m-%d")), 253402300799) AS gd_expire';
        $clauses['fields'] = $fields;

        $order = !empty($wp_query->query_vars['order']) ? $wp_query->query_vars['order'] : 'ASC';
        $orderby = 'gd_expire ' . $order;
        $clauses['orderby'] = $orderby;
    }
    return $clauses;
}


/* hook action for post updated */
add_action('post_updated', 'geodir_action_post_updated', 15, 3);

/*
 * hook to add option in bcc options
 */
add_filter('geodir_notifications_settings', 'geodir_notification_add_bcc_option', 1);

add_action('after_switch_theme', 'gd_theme_switch_compat_check');
/**
 * check if there is a compatibility pack when switching theme.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
function gd_theme_switch_compat_check()
{
    gd_set_theme_compat();
}

/**
 * Read string as csv array.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $current_user Current user object.
 * @return array Returns parsed data as array.
 */
function geodir_str_getcsv($input, $delimiter = ",", $enclosure = '"', $escape = "\\")
{
    if (function_exists('str_getcsv')) {
        $fgetcsv = str_getcsv($input, $delimiter, $enclosure, $escape);
    } else {
        global $current_user;
        $upload_dir = wp_upload_dir();

        $file = $upload_dir['path'] . '/temp_' . $current_user->data->ID . '/geodir_tmp.csv';
        $handle = fopen($file, 'w');

        fwrite($handle, $input);
        fclose($handle);

        $handle = fopen($file, 'rt');
        if (PHP_VERSION >= '5.3.0') {
            $fgetcsv = fgetcsv($handle, 0, $delimiter, $enclosure, $escape);
        } else {
            $fgetcsv = fgetcsv($handle, 0, $delimiter, $enclosure);
        }
        fclose($handle);
    }
    return $fgetcsv;
}

add_action('wp_ajax_gdImportCsv', 'geodir_ajax_import_csv');
/**
 * Imports data from CSV file using ajax.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global object $current_user Current user object.
 */
function geodir_ajax_import_csv()
{
    error_reporting(0); // hide error to get clean json response

    global $wpdb, $plugin_prefix, $current_user;
    $uploads = wp_upload_dir();
    ini_set('auto_detect_line_endings', true);
	
	$wp_post_statuses = get_post_statuses(); // All of the WordPress supported post statuses.

    $task = isset($_POST['task']) ? $_POST['task'] : '';
    $uploadedFile = isset($_POST['gddata']['uploadedFile']) ? $_POST['gddata']['uploadedFile'] : NULL;
    $filename = $uploadedFile;

    $uploads = wp_upload_dir();
    $uploads_dir = $uploads['path'];
    $image_name_arr = explode('/', $filename);
    $filename = end($image_name_arr);
    $target_path = $uploads_dir . '/temp_' . $current_user->data->ID . '/' . $filename;
    $return = array();
    $return['file'] = $uploadedFile;
    $return['error'] = __('The uploaded file is not a valid csv file. Please try again.', 'geodirectory');

    if (is_file($target_path) && file_exists($target_path) && $uploadedFile) {
        $wp_filetype = wp_check_filetype_and_ext($target_path, $filename);

        if (!empty($wp_filetype) && isset($wp_filetype['ext']) && geodir_strtolower($wp_filetype['ext']) == 'csv') {
            $return['error'] = NULL;

            $return['rows'] = 0;



                if (($handle = fopen($target_path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if(is_array($data) && !empty($data)) {
                            $file[] = '"' . implode('","', $data) . '"';
                        }
                    }
                    fclose($handle);
                    $file = $file;
                }



                $return['rows'] = (!empty($file) && count($file) > 1) ? count($file) - 1 : 0;


            if (!$return['rows'] > 0) {
                $return['error'] = __('No data found in csv file.', 'geodirectory');
            }
        }
    }
    if ($task == 'prepare' || !empty($return['error'])) {
        echo json_encode($return);
        exit;
    }

    $totRecords = isset($_POST['gddata']['totRecords']) ? $_POST['gddata']['totRecords'] : NULL;
    $importlimit = isset($_POST['gddata']['importlimit']) ? $_POST['gddata']['importlimit'] : 1;
    $count = $importlimit;
    $requested_limit = $importlimit;
    $tmpCnt = isset($_POST['gddata']['tmpcount']) ? $_POST['gddata']['tmpcount'] : 0;

    if ($count < $totRecords) {
        $count = $tmpCnt + $count;
        if ($count > $totRecords) {
            $count = $totRecords;
        }
    } else {
        $count = $totRecords;
    }

    $total_records = 0;
    $rowcount = 0;
    $address_invalid = 0;
    $blank_address = 0;
    $upload_files = 0;
    $invalid_post_type = 0;
    $invalid_title = 0;
    $customKeyarray = array();
    $gd_post_info = array();
    $post_location = array();
    $countpost = 0;

    if (!empty($file)) {
        $columns = isset($file[0]) ? geodir_str_getcsv($file[0]) : NULL;
        $customKeyarray = $columns;

        if (empty($columns) || (!empty($columns) && $columns[0] == '')) {
            $return['error'] = CSV_INVAILD_FILE;
            echo json_encode($return);
            exit;
        }

        for ($i = 1; $i <= $importlimit; $i++) {
            $current_index = $tmpCnt + $i;
            if (isset($file[$current_index])) {
                $total_records++;

                $buffer = geodir_str_getcsv($file[$current_index]);
                $post_title = addslashes($buffer[0]);
                $current_post_author = $buffer[1];
                $post_desc = addslashes($buffer[2]);
                $post_cat = array();
                $catids_arr = array();
                $post_cat = trim($buffer[3]); // comma seperated category name

                if ($post_cat) {
                    $post_cat_arr = explode(',', $post_cat);

                    for ($c = 0; $c < count($post_cat_arr); $c++) {
                        $catid = wp_kses_normalize_entities(trim($post_cat_arr[$c]));

                        if (!empty($buffer[5])) {
                            if (in_array($buffer[5], geodir_get_posttypes())) {

                                $p_taxonomy = geodir_get_taxonomies(addslashes($buffer[5]));

                                if (get_term_by('name', $catid, $p_taxonomy[0])) {
                                    $cat = get_term_by('name', $catid, $p_taxonomy[0]);
                                    $catids_arr[] = $cat->slug;
                                } else if (get_term_by('slug', $catid, $p_taxonomy[0])) {
                                    $cat = get_term_by('slug', $catid, $p_taxonomy[0]);
                                    $catids_arr[] = $cat->slug;
                                } else {
                                    $ret = wp_insert_term($catid, $p_taxonomy[0]);
                                    if ($ret && !is_wp_error($ret)) {
                                        if (get_term_by('name', $catid, $p_taxonomy[0])) {
                                            $cat = get_term_by('name', $catid, $p_taxonomy[0]);
                                            $catids_arr[] = $cat->slug;
                                        } elseif (get_term_by('slug', $catid, $p_taxonomy[0])) {
                                            $cat = get_term_by('slug', $catid, $p_taxonomy[0]);
                                            $catids_arr[] = $cat->slug;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (!$catids_arr) {
                    $catids_arr[] = 1;
                }

                $post_tags = trim($buffer[4]); // comma seperated tags

                $tag_arr = '';
                if ($post_tags) {
                    $tag_arr = explode(',', $post_tags);
                }

                $table = $plugin_prefix . $buffer[5] . '_detail'; // check table in database

                $error = '';
                if ($wpdb->get_var("SHOW TABLES LIKE '" . $table . "'") != $table) {
                    $invalid_post_type++;
                    continue;
                }

                if ($post_title != '') {
                    $menu_order = 0;
                    $image_folder_name = 'uplaod/';

                    $image_names = array();

                    for ($c = 5; $c < count($customKeyarray); $c++) {
                        $gd_post_info[$customKeyarray[$c]] = addslashes($buffer[$c]);

                        if ($customKeyarray[$c] == 'IMAGE') {
                            $buffer[$c] = trim($buffer[$c]);

                            if (!empty($buffer[$c])) {
                                $image_names[] = $buffer[$c];
                            }
                        }

                        if ($customKeyarray[$c] == 'alive_days') {
                            if ($buffer[$c] != '0' && $buffer[$c] != '') {
                                $submitdata = date('Y-m-d');

                                $gd_post_info['expire_date'] = date('Y-m-d', strtotime($submitdata . "+" . addslashes($buffer[$c]) . " days"));
                            } else {
                                $gd_post_info['expire_date'] = 'Never';
                            }
                        }

                        if ($customKeyarray[$c] == 'post_city') {
                            $post_city = addslashes($buffer[$c]);
                        }

                        if ($customKeyarray[$c] == 'post_region') {
                            $post_region = addslashes($buffer[$c]);
                        }

                        if ($customKeyarray[$c] == 'post_country') {
                            $post_country = addslashes($buffer[$c]);
                        }

                        if ($customKeyarray[$c] == 'post_latitude') {
                            $post_latitude = addslashes($buffer[$c]);
                        }

                        if ($customKeyarray[$c] == 'post_longitude') {
                            $post_longitude = addslashes($buffer[$c]);
                        }
						
						// Post status
						if ($customKeyarray[$c] == 'post_status') {
                            $post_status = sanitize_key( $buffer[$c] );
                        }
                    }

                    /* ================ before array create ============== */
                    $location_result = geodir_get_default_location();
                    if ((!isset($gd_post_info['post_city']) || $gd_post_info['post_city'] == '') || (!isset($gd_post_info['post_region']) || $gd_post_info['post_region'] == '') || (!isset($gd_post_info['post_country']) || $gd_post_info['post_country'] == '') || (!isset($gd_post_info['post_address']) || $gd_post_info['post_address'] == '') || (!isset($gd_post_info['post_latitude']) || $gd_post_info['post_latitude'] == '') || (!isset($gd_post_info['post_longitude']) || $gd_post_info['post_longitude'] == '')) {
                        $blank_address++;
                        continue;
                    } else if ($location_result->location_id == 0) {
                        if ((geodir_strtolower($gd_post_info['post_city']) != geodir_strtolower($location_result->city)) || (geodir_strtolower($gd_post_info['post_region']) != geodir_strtolower($location_result->region)) || (geodir_strtolower($gd_post_info['post_country']) != geodir_strtolower($location_result->country))) {
                            $address_invalid++;
                            continue;
                        }
                    }
					
					// Default post status
					$default_status = 'publish';
					$post_status = !empty( $post_status ) ? sanitize_key( $post_status ) : $default_status;
					$post_status = !empty( $wp_post_statuses ) && !isset( $wp_post_statuses[$post_status] ) ? $default_status : $post_status;

                    $my_post['post_title'] = $post_title;
                    $my_post['post_content'] = $post_desc;
                    $my_post['post_type'] = addslashes($buffer[5]);
                    $my_post['post_author'] = $current_post_author;
                    $my_post['post_status'] = $post_status;
                    $my_post['post_category'] = $catids_arr;
                    $my_post['post_tags'] = $tag_arr;

                    $gd_post_info['post_tags'] = $tag_arr;
                    $gd_post_info['post_title'] = $post_title;
                    $gd_post_info['post_status'] = $post_status;
                    $gd_post_info['submit_time'] = time();
                    $gd_post_info['submit_ip'] = $_SERVER['REMOTE_ADDR'];

                    $last_postid = wp_insert_post($my_post);
                    $countpost++;

                    // Check if we need to save post location as new location
                    if ($location_result->location_id > 0) {
                        if (isset($post_city) && isset($post_region)) {
                            $request_info['post_location'] = array(
                                'city' => $post_city,
                                'region' => $post_region,
                                'country' => $post_country,
                                'geo_lat' => $post_latitude,
                                'geo_lng' => $post_longitude
                            );

                            $post_location_info = $request_info['post_location'];
                            if ($location_id = geodir_add_new_location($post_location_info))
                                $post_location_id = $location_id;
                        } else {
                            $post_location_id = 0;
                        }
                    } else {
                        $post_location_id = 0;
                    }

                    /* ------- get default package info ----- */
                    $payment_info = array();
                    $package_info = array();

                    $package_info = (array)geodir_post_package_info($package_info, '', $buffer[5]);
                    $package_id = '';
                    if (isset($gd_post_info['package_id']) && $gd_post_info['package_id'] != '') {
                        $package_id = $gd_post_info['package_id'];
                    }

                    if (!empty($package_info)) {
                        $payment_info['package_id'] = $package_info['pid'];

                        if (isset($package_info['alive_days']) && $package_info['alive_days'] != 0) {
                            $payment_info['expire_date'] = date('Y-m-d', strtotime("+" . $package_info['alive_days'] . " days"));
                        } else {
                            $payment_info['expire_date'] = 'Never';
                        }

                        $gd_post_info = array_merge($gd_post_info, $payment_info);
                    }

                    $gd_post_info['post_location_id'] = $post_location_id;

                    $post_type = get_post_type($last_postid);

                    $table = $plugin_prefix . $post_type . '_detail';

                    geodir_save_post_info($last_postid, $gd_post_info);

                    if (!empty($image_names)) {
                        $upload_files++;
                        $menu_order = 1;

                        foreach ($image_names as $image_name) {
                            $img_name_arr = explode('.', $image_name);

                            $uploads = wp_upload_dir();
                            $sub_dir = $uploads['subdir'];

                            $arr_file_type = wp_check_filetype($image_name);
                            $uploaded_file_type = $arr_file_type['type'];

                            $attachment = array();
                            $attachment['post_id'] = $last_postid;
                            $attachment['title'] = $img_name_arr[0];
                            $attachment['content'] = '';
                            $attachment['file'] = $sub_dir . '/' . $image_name;
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

                            if ($menu_order == 1) {
                                $post_type = get_post_type($last_postid);
                                $wpdb->query($wpdb->prepare("UPDATE " . $table . " SET featured_image = %s where post_id =%d", array($sub_dir . '/' . $image_name, $last_postid)));
                            }
                            $menu_order++;
                        }
                    }

                    $gd_post_info['package_id'] = $package_id;

                    /** This action is documented in geodirectory-functions/post-functions.php */
                    do_action('geodir_after_save_listing', $last_postid, $gd_post_info);

                    if (!empty($buffer[5])) {
                        if (in_array($buffer[5], geodir_get_posttypes())) {
                            $taxonomies = geodir_get_posttype_info(addslashes($buffer[5]));
                            wp_set_object_terms($last_postid, $my_post['post_tags'], $taxonomy = $taxonomies['taxonomies'][1]);
                            wp_set_object_terms($last_postid, $my_post['post_category'], $taxonomy = $taxonomies['taxonomies'][0]);

                            $post_default_category = isset($my_post['post_default_category']) ? $my_post['post_default_category'] : '';
                            $post_category_str = isset($my_post['post_category_str']) ? $my_post['post_category_str'] : '';
                            geodir_set_postcat_structure($last_postid, $taxonomy, $post_default_category, $post_category_str);
                        }
                    }
                } else {
                    $invalid_title++;
                }
            }
        }
    }
    $return['rowcount'] = $countpost;
    $return['invalidcount'] = $address_invalid;
    $return['blank_address'] = $blank_address;
    $return['upload_files'] = $upload_files;
    $return['invalid_post_type'] = $invalid_post_type;
    $return['invalid_title'] = $invalid_title;
    $return['total_records'] = $total_records;

    echo json_encode($return);
    exit;
}

// Add the tab in left sidebar menu fro import & export page.
add_filter( 'geodir_settings_tabs_array', 'geodir_import_export_tab', 94 );

// Handle ajax request for import/export.
add_action( 'wp_ajax_geodir_import_export', 'geodir_ajax_import_export' );
add_action( 'wp_ajax_nopriv_geodir_import_exportn', 'geodir_ajax_import_export' );


/**
 * Updates the location page prefix when location page is saved
 *
 * @package GeoDirectory
 * @since 1.4.6
 * @param $post_id int $post_id The post ID of the post being saved.
 * @param $post object $post The post object of the post being saved.
 */
function geodir_update_location_prefix($post_id,$post){
    if($post->post_type=='page' && $post->post_name && $post_id==get_option('geodir_location_page')){
        update_option('geodir_location_prefix',$post->post_name);
    }

}

add_action('save_post', 'geodir_update_location_prefix',10,2);

add_action( 'wp_ajax_geodir_ga_callback', 'geodir_ga_callback' );

function geodir_ga_callback(){

if(isset($_REQUEST['code']) && $_REQUEST['code']) {
    $oAuthURL = "https://www.googleapis.com/oauth2/v3/token?";
    $code = "code=".$_REQUEST['code'];
    $grant_type = "&grant_type=authorization_code";
    $redirect_uri = "&redirect_uri=" . admin_url('admin-ajax.php') . "?action=geodir_ga_callback";
    $client_id = "&client_id=".get_option('geodir_ga_client_id');
    $client_secret = "&client_secret=".get_option('geodir_ga_client_secret');

    $auth_url = $oAuthURL . $code . $redirect_uri .  $grant_type . $client_id .$client_secret;

    $response = wp_remote_post($auth_url, array('timeout' => 15));

    //print_r($response);

    $error_msg =  __('Something went wrong','geodirectory');
    if(!empty($response['response']['code']) && $response['response']['code']==200){

        $parts = json_decode($response['body']);
        //print_r($parts);
        if(!isset($parts->access_token)){echo $error_msg." - #1";exit;}
        else{

            update_option('gd_ga_access_token', $parts->access_token);
            update_option('gd_ga_refresh_token', $parts->refresh_token);
            ?><script>window.close();</script><?php
        }


    }
    elseif(!empty($response['response']['code'])) {
        $parts = json_decode($response['body']);

        if(isset($parts->error)){
            echo $parts->error.": ".$parts->error_description;exit;
        }else{
            echo $error_msg." - #2";exit;
        }

    }else{

        echo $error_msg." - #3";exit;

    }
}
    exit;
}

if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'permalink_settings') {
	add_action('geodir_before_admin_panel', 'geodir_wpml_permalink_setting_notice');
}

/**
 * Add uninstall settings for GeoDirectory plugins.
 *
 * @since 1.6.9
 *
 * @param array $settings Array of GeoDirectory general settings.
 * @return array Array of settings.
 */
function geodir_uninstall_settings($general_settings) {
    $settings   = array();
    $settings[] = array('type' => 'title', 'id' => 'uninstall_settings', 'name' => __('Uninstall Settings', 'geodirectory'));
    $settings[] = array('type' => 'sectionstart', 'id' => 'uninstall_settings_main', 'name' => __('Remove Data on Uninstall?', 'geodirectory' ));
    
    $plugins    = get_plugins();
    $un_plugins = apply_filters('geodir_plugins_uninstall_settings', array());
    
    if (!empty($plugins) && !empty($un_plugins)) {
        foreach ($plugins as $plugin => $data) {
            $plugin_name = plugin_basename(dirname($plugin));
            
            if (in_array($plugin_name, $un_plugins)) {
                $settings[] = array(
                    'type' => 'checkbox',
                    'id' => 'geodir_un_' . $plugin_name,
                    'name' => $data['Name'],
                    'desc' => __('Remove all data when deleted?', 'geodirectory'),
                    'std' => '0'
                );
            }
        }
    }
        
    $settings[] = array('type' => 'sectionend', 'id' => 'uninstall_settings_main');
    
    /**
     * Filter the uninstall settings array.
     *
     * @since 1.6.9
     *
     * @param array $settings The settings array.
     */
    $settings = apply_filters('geodir_uninstall_settings', $settings);
    
    if (!empty($settings) && count($settings) > 3) {
        return array_merge($general_settings, $settings);
    }
    
    return $general_settings;
}
add_filter('geodir_general_settings', 'geodir_uninstall_settings', 100, 1);

/**
 * Show the description in uninstall settings section.
 *
 * @since 1.6.9
 */
function geodir_uninstall_settings_desc() {
    echo '<p class="gd-un-settings-desc">' . __('Select the plugin(s) for which all data should be completely removed when the plugin is deleted.', 'geodirectory') . '</p>';
}
add_action('geodir_settings_uninstall_settings_main_start', 'geodir_uninstall_settings_desc');

/**
 * Handle the plugin settings for plugin deactivate to activate.
 *
 * It manages the the settings without loosing previous settings saved when plugin
 * status changed from deactivate to activate.
 *
 * @since 1.6.9
 *
 * @param array $settings The option settings array.
 * @return array The settings array.
 */
function geodir_resave_settings($settings = array()) {
    if (!empty($settings) && is_array($settings)) {
        $c = 0;
        
        foreach ($settings as $setting) {
            if (!empty($setting['id']) && false !== ($value = get_option($setting['id']))) {
                $settings[$c]['std'] = $value;
            }
            $c++;
        }
    }

    return $settings;
}

/**
 * Add the plugin to uninstall settings.
 *
 * @since 1.6.9
 *
 * @return array $settings the settings array.
 * @return array The modified settings.
 */
function geodir_core_uninstall_settings($settings) {
    $settings[] = plugin_basename(dirname(dirname(__FILE__)));
    
    return $settings;
}
add_filter('geodir_plugins_uninstall_settings', 'geodir_core_uninstall_settings', 10, 1);

/**
 * Truncate the countries table and clear version numbers so it will be installed on refresh..
 *
 * @since 1.6.16
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_diagnose_reload_db_countries()
{
    global $wpdb, $plugin_prefix;

    $is_error_during_diagnose = false;
    $output_str = '';

    $delete = $wpdb->query("TRUNCATE TABLE ".GEODIR_COUNTRIES_TABLE);


    if ($delete) {
            $output_str .= "<li><strong>" . __('Table dropped, refresh page to reinstall.', 'geodirectory') . "</strong></li>";
        ob_start();
        geodir_diagnose_version_clear();
        ob_end_clean();
    }else{
        $output_str .= "<li><strong>" . __('Something went wrong.', 'geodirectory') . "</strong></li>";
    }

    if ($is_error_during_diagnose) {
        $info_div_class = "geodir_problem_info";
        $fix_button_txt = "";
    } else {
        $info_div_class = "geodir_noproblem_info";
        $fix_button_txt = '';
    }
    echo "<ul class='$info_div_class'>";
    echo $output_str;
    echo $fix_button_txt;
    echo "</ul>";
}

/**
 * Disable quick edit links for GD post types.
 *
 * @since 1.6.22
 * @package GeoDirectory
 * @param array $actions An array of row action links.
 * @param object $row The post|taxonomy object.
 * @return array Filtered actions.
 */
function geodir_disable_quick_edit( $actions = array(), $row = null ) {
    if ( isset( $actions['inline hide-if-no-js'] ) ) {
        unset( $actions['inline hide-if-no-js'] );
    }

    return $actions;
}

/**
 * Set hooks to disable quick edit links for GD post types.
 *
 * @since 1.6.22
 * @package GeoDirectory
 *
 * @global string $pagenow The current screen.
 * @global object $current_screen The current screen object.
 * @global bool $gd_cpt_screen True if current scrrrn has GD post type.
 */
function geodir_check_quick_edit() {
    global $pagenow, $current_screen, $gd_cpt_screen;

    if ( ( $pagenow == 'edit.php' || $pagenow == 'edit-tags.php' ) && !empty( $current_screen->post_type ) ) {
        if ( empty( $gd_cpt_screen ) ) {
            if ( in_array( $current_screen->post_type, geodir_get_posttypes() ) ) {
                $gd_cpt_screen = 'y';
            } else {
                $gd_cpt_screen = 'n';
            }
        }

        if ( $gd_cpt_screen == 'y' ) {
            if ( $pagenow == 'edit.php' ) {
                add_filter( 'post_row_actions', 'geodir_disable_quick_edit', 10, 2 );
                add_filter( 'page_row_actions', 'geodir_disable_quick_edit', 10, 2 );
            } elseif ( $pagenow == 'edit-tags.php' && !empty( $current_screen->taxonomy ) ) {
                add_filter( $current_screen->taxonomy . '_row_actions', 'geodir_disable_quick_edit', 10, 2 );
            }
        }
    }
}
add_action( 'admin_head', 'geodir_check_quick_edit', 10 );

