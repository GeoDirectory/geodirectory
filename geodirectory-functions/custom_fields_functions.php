<?php
/**
 * Custom fields functions
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $table_prefix WordPress Database Table prefix.
 */
global $wpdb, $table_prefix;

if (!function_exists('geodir_column_exist')) {
    /**
     * Check table column exist or not.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @param string $db The table name.
     * @param string $column The column name.
     * @return bool If column exists returns true. Otherwise false.
     */
    function geodir_column_exist($db, $column)
    {
        global $wpdb;
        $exists = false;
        $columns = $wpdb->get_col("show columns from $db");
        foreach ($columns as $c) {
            if ($c == $column) {
                $exists = true;
                break;
            }
        }
        return $exists;
    }
}

if (!function_exists('geodir_add_column_if_not_exist')) {
    /**
     * Add column if table column not exist.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @param string $db The table name.
     * @param string $column The column name.
     * @param string $column_attr The column attributes.
     */
    function geodir_add_column_if_not_exist($db, $column, $column_attr = "VARCHAR( 255 ) NOT NULL")
    {
        global $wpdb;
        $result = 0;// no rows affected
        if (!geodir_column_exist($db, $column)) {
            if (!empty($db) && !empty($column))
                $result = $wpdb->query("ALTER TABLE `$db` ADD `$column`  $column_attr");
        }
        return $result;
    }
}

/**
 * Returns custom fields based on page type. (detail page, listing page).
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param int|string $package_id The package ID.
 * @param string $default Optional. When set to "default" it will display only default fields.
 * @param string $post_type Optional. The wordpress post type.
 * @param string $fields_location Optional. Where exactly are you going to place this custom fields?.
 * @return array|mixed|void Returns custom fields.
 */
function geodir_post_custom_fields($package_id = '', $default = 'all', $post_type = 'gd_place', $fields_location = 'none')
{
    global $wpdb, $geodir_post_custom_fields_cache;

    $cache_stored = $post_type . '_' . $package_id . '_' . $default . '_' . $fields_location;

    if (array_key_exists($cache_stored, $geodir_post_custom_fields_cache)) {
        return $geodir_post_custom_fields_cache[$cache_stored];
    }

    $default_query = '';

    if ($default == 'default')
        $default_query .= " and is_admin IN ('1') ";
    elseif ($default == 'custom')
        $default_query .= " and is_admin = '0' ";

    if ($fields_location == 'none') {
    } else{
        $fields_location = esc_sql( $fields_location );
        $default_query .= " and show_in LIKE '%%[$fields_location]%%' ";
    }

    $post_meta_info = $wpdb->get_results(
        $wpdb->prepare(
            "select * from " . GEODIR_CUSTOM_FIELDS_TABLE . " where is_active = '1' and post_type = %s {$default_query} order by sort_order asc,admin_title asc",
            array($post_type)
        )
    );


    $return_arr = array();
    if ($post_meta_info) {

        foreach ($post_meta_info as $post_meta_info_obj) {

            $custom_fields = array(
                "name" => $post_meta_info_obj->htmlvar_name,
                "label" => $post_meta_info_obj->clabels,
                "default" => $post_meta_info_obj->default_value,
                "type" => $post_meta_info_obj->field_type,
                "desc" => $post_meta_info_obj->admin_desc);

            if ($post_meta_info_obj->field_type) {
                $options = explode(',', $post_meta_info_obj->option_values);
                $custom_fields["options"] = $options;
            }

            foreach ($post_meta_info_obj as $key => $val) {
                $custom_fields[$key] = $val;
            }

            $pricearr = array();
            $pricearr = explode(',', $post_meta_info_obj->packages);

            if ($package_id != '' && in_array($package_id, $pricearr)) {
                $return_arr[$post_meta_info_obj->sort_order] = $custom_fields;
            } elseif ($package_id == '') {
                $return_arr[$post_meta_info_obj->sort_order] = $custom_fields;
            }
        }
    }
    $geodir_post_custom_fields_cache[$cache_stored] = $return_arr;

    if (has_filter('geodir_filter_geodir_post_custom_fields')) {
        /**
         * Filter the post custom fields array.
         *
         * @since 1.0.0
         *
         * @param array $return_arr Post custom fields array.
         * @param int|string $package_id The package ID.
         * @param string $post_type Optional. The wordpress post type.
         * @param string $fields_location Optional. Where exactly are you going to place this custom fields?.
         */
        $return_arr = apply_filters('geodir_filter_geodir_post_custom_fields', $return_arr, $package_id, $post_type, $fields_location);
    }

    return $return_arr;
}

    /**
     * Adds admin html for custom fields.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @param string $field_type The form field type.
     * @param object|int $result_str The custom field results object or row id.
     * @param string $field_ins_upd When set to "submit" displays form.
     * @param string $field_type_key The key of the custom field.
     */
    function geodir_custom_field_adminhtml($field_type, $result_str, $field_ins_upd = '', $field_type_key ='')
    {
        global $wpdb;
        $cf = $result_str;
        if (!is_object($cf)) {

            $field_info = $wpdb->get_row($wpdb->prepare("select * from " . GEODIR_CUSTOM_FIELDS_TABLE . " where id= %d", array($cf)));

        } else {
            $field_info = $cf;
            $result_str = $cf->id;
        }
        /**
         * Contains custom field html.
         *
         * @since 1.0.0
         */
        include('custom_field_html.php');

    }


if (!function_exists('geodir_custom_field_delete')) {
    /**
     * Delete custom field using field id.
     *
     * @since 1.0.0
     * @since 1.5.7 Delete field from sorting fields table when custom field deleted.
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param string $field_id The custom field ID.
     * @return int|string If field deleted successfully, returns field id. Otherwise returns 0.
     */
    function geodir_custom_field_delete($field_id = '') {
        global $wpdb, $plugin_prefix;

        if ($field_id != '') {
            $cf = trim($field_id, '_');

            if ($field = $wpdb->get_row($wpdb->prepare("select htmlvar_name,post_type,field_type from " . GEODIR_CUSTOM_FIELDS_TABLE . " where id= %d", array($cf)))) {
                $wpdb->query($wpdb->prepare("delete from " . GEODIR_CUSTOM_FIELDS_TABLE . " where id= %d ", array($cf)));

                $post_type = $field->post_type;
                $htmlvar_name = $field->htmlvar_name;

                if ($post_type != '' && $htmlvar_name != '') {
                    $wpdb->query($wpdb->prepare("DELETE FROM " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " WHERE htmlvar_name=%s AND post_type=%s LIMIT 1", array($htmlvar_name, $post_type)));
                }

                /**
                 * Called after a custom field is deleted.
                 *
                 * @since 1.0.0
                 * @param string $cf The fields ID.
                 * @param string $field->htmlvar_name The html variable name for the field.
                 * @param string $post_type The post type the field belongs to.
                 */
                do_action('geodir_after_custom_field_deleted', $cf, $field->htmlvar_name, $post_type);

                if ($field->field_type == 'address') {
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_address`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_city`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_region`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_country`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_zip`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_latitude`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_longitude`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_mapview`");
                    $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "_mapzoom`");
                } else {
                    if ($field->field_type != 'fieldset') {
                        $wpdb->query("ALTER TABLE " . $plugin_prefix . $post_type . "_detail DROP `" . $field->htmlvar_name . "`");
                    }
                }

                return $field_id;
            } else
                return 0;
        } else
            return 0;
    }
}

if (!function_exists('geodir_custom_field_save')) {
    /**
     * Save or Update custom fields into the database.
     *
     * @since 1.0.0
     * @since 1.5.6 Fix for saving multiselect custom field "Display Type" on first attempt.
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param array $request_field {
     *    Attributes of the request field array.
     *
     *    @type string $action Ajax Action name. Default "geodir_ajax_action".
     *    @type string $manage_field_type Field type Default "custom_fields".
     *    @type string $create_field Create field Default "true".
     *    @type string $field_ins_upd Field ins upd Default "submit".
     *    @type string $_wpnonce WP nonce value.
     *    @type string $listing_type Listing type Example "gd_place".
     *    @type string $field_type Field type Example "radio".
     *    @type string $field_id Field id Example "12".
     *    @type string $data_type Data type Example "VARCHAR".
     *    @type string $is_active Either "1" or "0". If "0" is used then the field will not be displayed anywhere.
     *    @type array $show_on_pkg Package list to display this field.
     *    @type string $admin_title Personal comment, it would not be displayed anywhere except in custom field settings.
     *    @type string $site_title Section title which you wish to display in frontend.
     *    @type string $admin_desc Section description which will appear in frontend.
     *    @type string $htmlvar_name Html variable name. This should be a unique name.
     *    @type string $clabels Section Title which will appear in backend.
     *    @type string $default_value The default value (for "link" this will be used as the link text).
     *    @type string $sort_order The display order of this field in backend. e.g. 5.
     *    @type string $is_default Either "1" or "0". If "0" is used then the field will be displayed as main form field or additional field.
     *    @type string $for_admin_use Either "1" or "0". If "0" is used then only site admin can edit this field.
     *    @type string $is_required Use "1" to set field as required.
     *    @type string $required_msg Enter text for error message if field required and have not full fill requirement.
     *    @type string $show_on_listing Want to show this on listing page?.
     *    @type string $show_in What locations to show the custom field in.
     *    @type string $show_on_detail Want to show this in More Info tab on detail page?.
     *    @type string $show_as_tab Want to display this as a tab on detail page? If "1" then "Show on detail page?" must be Yes.
     *    @type string $option_values Option Values should be separated by comma.
     *    @type string $field_icon Upload icon using media and enter its url path, or enter font awesome class.
     *    @type string $css_class Enter custom css class for field custom style.
     *    @type array $extra_fields An array of extra fields to store.
     *
     * }
     * @param bool $default Not yet implemented.
     * @return int|string If field is unique returns inserted row id. Otherwise returns error string.
     */
    function geodir_custom_field_save($request_field = array(), $default = false)
    {

        global $wpdb, $plugin_prefix;

        $old_html_variable = '';

        $data_type = trim($request_field['data_type']);

        $result_str = isset($request_field['field_id']) ? trim($request_field['field_id']) : '';

        // some servers fail if a POST value is VARCHAR so we change it.
        if(isset($request_field['data_type']) && $request_field['data_type']=='XVARCHAR'){
            $request_field['data_type'] = 'VARCHAR';
        }

        $cf = trim($result_str, '_');


        /*-------- check duplicate validation --------*/

        $cehhtmlvar_name = isset($request_field['htmlvar_name']) ? $request_field['htmlvar_name'] : '';
        $post_type = $request_field['listing_type'];

        if ($request_field['field_type'] != 'address' && $request_field['field_type'] != 'taxonomy' && $request_field['field_type'] != 'fieldset') {
            $cehhtmlvar_name = 'geodir_' . $cehhtmlvar_name;
        }

        $check_html_variable = $wpdb->get_var(
            $wpdb->prepare(
                "select htmlvar_name from " . GEODIR_CUSTOM_FIELDS_TABLE . " where id <> %d and htmlvar_name = %s and post_type = %s ",
                array($cf, $cehhtmlvar_name, $post_type)
            )
        );


        if (!$check_html_variable || $request_field['field_type'] == 'fieldset') {

            if ($cf != '') {

                $post_meta_info = $wpdb->get_row(
                    $wpdb->prepare(
                        "select * from " . GEODIR_CUSTOM_FIELDS_TABLE . " where id = %d",
                        array($cf)
                    )
                );

            }

            if (!empty($post_meta_info)) {
                $post_val = $post_meta_info;
                $old_html_variable = $post_val->htmlvar_name;

            }



            if ($post_type == '') $post_type = 'gd_place';


            $detail_table = $plugin_prefix . $post_type . '_detail';

            $admin_title = $request_field['admin_title'];
            $site_title = $request_field['site_title'];
            $data_type = $request_field['data_type'];
            $field_type = $request_field['field_type'];
            $field_type_key = isset($request_field['field_type_key']) ? $request_field['field_type_key'] : $field_type;
            $htmlvar_name = isset($request_field['htmlvar_name']) ? $request_field['htmlvar_name'] : '';
            $admin_desc = $request_field['admin_desc'];
            $clabels = isset($request_field['clabels']) ? $request_field['clabels'] : '';
            $default_value = isset($request_field['default_value']) ? $request_field['default_value'] : '';
            $sort_order = isset($request_field['sort_order']) ? $request_field['sort_order'] : '';
            $is_active = isset($request_field['is_active']) ? $request_field['is_active'] : '';
            $is_required = isset($request_field['is_required']) ? $request_field['is_required'] : '';
            $required_msg = isset($request_field['required_msg']) ? $request_field['required_msg'] : '';
            $css_class = isset($request_field['css_class']) ? $request_field['css_class'] : '';
            $field_icon = isset($request_field['field_icon']) ? $request_field['field_icon'] : '';
            $show_on_listing = isset($request_field['show_on_listing']) ? $request_field['show_on_listing'] : '';
            $show_in = isset($request_field['show_in']) ? $request_field['show_in'] : '';
            $show_on_detail = isset($request_field['show_on_detail']) ? $request_field['show_on_detail'] : '';
            $show_as_tab = isset($request_field['show_as_tab']) ? $request_field['show_as_tab'] : '';
            $decimal_point = isset($request_field['decimal_point']) ? trim($request_field['decimal_point']) : ''; // decimal point for DECIMAL data type
            $decimal_point = $decimal_point > 0 ? ($decimal_point > 10 ? 10 : $decimal_point) : '';
            $validation_pattern = isset($request_field['validation_pattern']) ? $request_field['validation_pattern'] : '';
            $validation_msg = isset($request_field['validation_msg']) ? $request_field['validation_msg'] : '';
            $for_admin_use = isset($request_field['for_admin_use']) ? $request_field['for_admin_use'] : '';

            
            if(is_array($show_in)){
                $show_in = implode(",", $request_field['show_in']);
            }
            
            if ($field_type != 'address' && $field_type != 'taxonomy' && $field_type != 'fieldset') {
                $htmlvar_name = 'geodir_' . $htmlvar_name;
            }

            $option_values = '';
            if (isset($request_field['option_values']))
                $option_values = $request_field['option_values'];

            $cat_sort = isset($request_field['cat_sort']) ? $request_field['cat_sort'] : '0';

            $cat_filter = isset($request_field['cat_filter']) ? $request_field['cat_filter'] : '0';

            if (isset($request_field['show_on_pkg']) && !empty($request_field['show_on_pkg']))
                $price_pkg = implode(",", $request_field['show_on_pkg']);
            else {
                $package_info = array();

                $package_info = geodir_post_package_info($package_info, '', $post_type);
                $price_pkg = !empty($package_info->pid) ? $package_info->pid : '';
            }


            if (isset($request_field['extra']) && !empty($request_field['extra']))
                $extra_fields = $request_field['extra'];

            if (isset($request_field['is_default']) && $request_field['is_default'] != '')
                $is_default = $request_field['is_default'];
            else
                $is_default = '0';

            if (isset($request_field['is_admin']) && $request_field['is_admin'] != '')
                $is_admin = $request_field['is_admin'];
            else
                $is_admin = '0';


            if ($is_active == '') $is_active = 1;
            if ($is_required == '') $is_required = 0;


            if ($sort_order == '') {

                $last_order = $wpdb->get_var("SELECT MAX(sort_order) as last_order FROM " . GEODIR_CUSTOM_FIELDS_TABLE);

                $sort_order = (int)$last_order + 1;
            }

            $default_value_add = '';


            if (!empty($post_meta_info)) {
                switch ($field_type):

                    case 'address':

                        if ($htmlvar_name != '') {
                            $prefix = $htmlvar_name . '_';
                        }
                        $old_prefix = $old_html_variable . '_';


                        $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "address` `" . $prefix . "address` VARCHAR( 254 ) NULL";

                        if ($default_value != '') {
                            $meta_field_add .= " DEFAULT '" . $default_value . "'";
                        }

                        $wpdb->query($meta_field_add);

                        if ($extra_fields != '') {

                            if (isset($extra_fields['show_city']) && $extra_fields['show_city']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "city'");
                                if ($is_column) {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "city` `" . $prefix . "city` VARCHAR( 50 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {

                                    $meta_field_add = "VARCHAR( 50 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }
                                    geodir_add_column_if_not_exist($detail_table, $prefix . "city", $meta_field_add);

                                }


                            }


                            if (isset($extra_fields['show_region']) && $extra_fields['show_region']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "region'");

                                if ($is_column) {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "region` `" . $prefix . "region` VARCHAR( 50 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {
                                    $meta_field_add = "VARCHAR( 50 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "region", $meta_field_add);
                                }

                            }
                            if (isset($extra_fields['show_country']) && $extra_fields['show_country']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "country'");

                                if ($is_column) {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "country` `" . $prefix . "country` VARCHAR( 50 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {

                                    $meta_field_add = "VARCHAR( 50 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "country", $meta_field_add);

                                }

                            }
                            if (isset($extra_fields['show_zip']) && $extra_fields['show_zip']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "zip'");

                                if ($is_column) {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "zip` `" . $prefix . "zip` VARCHAR( 50 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {

                                    $meta_field_add = "VARCHAR( 50 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "zip", $meta_field_add);

                                }

                            }
                            if (isset($extra_fields['show_map']) && $extra_fields['show_map']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "latitude'");
                                if ($is_column) {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "latitude` `" . $prefix . "latitude` VARCHAR( 20 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "latitude` VARCHAR( 20 ) NULL";
                                    $meta_field_add = "VARCHAR( 20 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "latitude", $meta_field_add);

                                }


                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "longitude'");

                                if ($is_column) {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "longitude` `" . $prefix . "longitude` VARCHAR( 20 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "longitude` VARCHAR( 20 ) NULL";
                                    $meta_field_add = "VARCHAR( 20 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "longitude", $meta_field_add);
                                }

                            }
                            if (isset($extra_fields['show_mapview']) && $extra_fields['show_mapview']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "mapview'");

                                if ($is_column) {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "mapview` `" . $prefix . "mapview` VARCHAR( 15 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);
                                } else {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "mapview` VARCHAR( 15 ) NULL";

                                    $meta_field_add = "VARCHAR( 15 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "mapview", $meta_field_add);
                                }


                            }
                            if (isset($extra_fields['show_mapzoom']) && $extra_fields['show_mapzoom']) {

                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "mapzoom'");
                                if ($is_column) {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "mapzoom` `" . $prefix . "mapzoom` VARCHAR( 3 ) NULL";

                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    $wpdb->query($meta_field_add);

                                } else {

                                    $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "mapzoom` VARCHAR( 3 ) NULL";

                                    $meta_field_add = "VARCHAR( 3 ) NULL";
                                    if ($default_value != '') {
                                        $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                    }

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "mapzoom", $meta_field_add);
                                }

                            }
                            // show lat lng
                            if (isset($extra_fields['show_latlng']) && $extra_fields['show_latlng']) {
                                $is_column = $wpdb->get_var("SHOW COLUMNS FROM " . $detail_table . " where field='" . $old_prefix . "latlng'");

                                if ($is_column) {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_prefix . "latlng` `" . $prefix . "latlng` VARCHAR( 3 ) NULL";
                                    $meta_field_add .= " DEFAULT '1'";

                                    $wpdb->query($meta_field_add);
                                } else {
                                    $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "latlng` VARCHAR( 3 ) NULL";

                                    $meta_field_add = "VARCHAR( 3 ) NULL";
                                    $meta_field_add .= " DEFAULT '1'";

                                    geodir_add_column_if_not_exist($detail_table, $prefix . "latlng", $meta_field_add);
                                }

                            }
                        }// end extra

                        break;

                    case 'checkbox':
                    case 'multiselect':
                    case 'select':
                    case 'taxonomy':

                        $op_size = '500';

                        // only make the field as big as it needs to be.
                        if(isset($option_values) && $option_values && $field_type=='select'){
                            $option_values_arr = explode(',',$option_values);
                            if(is_array($option_values_arr)){
                                $op_max = 0;
                                foreach($option_values_arr as $op_val){
                                    if(strlen($op_val) && strlen($op_val)>$op_max){$op_max = strlen($op_val);}
                                }
                                if($op_max){$op_size =$op_max; }
                            }
                        }elseif(isset($option_values) && $option_values && $field_type=='multiselect'){
                            if(strlen($option_values)){
                                $op_size =  strlen($option_values);
                            }
                        }

                        $meta_field_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_html_variable . "` `" . $htmlvar_name . "`VARCHAR( $op_size ) NULL";

                        if ($default_value != '') {
                            $meta_field_add .= " DEFAULT '" . $default_value . "'";
                        }

                        $alter_result = $wpdb->query($meta_field_add);
                        if($alter_result===false){
                            return __('Column change failed, you may have too many columns.','geodirectory');
                        }

                        if (isset($request_field['cat_display_type']))
                            $extra_fields = $request_field['cat_display_type'];

                        if (isset($request_field['multi_display_type']))
                            $extra_fields = $request_field['multi_display_type'];


                        break;

                    case 'textarea':
                    case 'html':
                    case 'url':
                    case 'file':

                        $alter_result = $wpdb->query("ALTER TABLE " . $detail_table . " CHANGE `" . $old_html_variable . "` `" . $htmlvar_name . "` TEXT NULL");
                        if($alter_result===false){
                            return __('Column change failed, you may have too many columns.','geodirectory');
                        }
                        if (isset($request_field['advanced_editor']))
                            $extra_fields = $request_field['advanced_editor'];

                        break;

                    case 'fieldset':
                        // Nothing happened for fieldset
                        break;

                    default:
                        if ($data_type != 'VARCHAR' && $data_type != '') {
                            if ($data_type == 'FLOAT' && $decimal_point > 0) {
                                $default_value_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_html_variable . "` `" . $htmlvar_name . "` DECIMAL(11, " . (int)$decimal_point . ") NULL";
                            } else {
                                $default_value_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_html_variable . "` `" . $htmlvar_name . "` " . $data_type . " NULL";
                            }

                            if (is_numeric($default_value) && $default_value != '') {
                                $default_value_add .= " DEFAULT '" . $default_value . "'";
                            }
                        } else {
                            $default_value_add = "ALTER TABLE " . $detail_table . " CHANGE `" . $old_html_variable . "` `" . $htmlvar_name . "` VARCHAR( 254 ) NULL";
                            if ($default_value != '') {
                                $default_value_add .= " DEFAULT '" . $default_value . "'";
                            }
                        }

                        $alter_result = $wpdb->query($default_value_add);
                        if($alter_result===false){
                            return __('Column change failed, you may have too many columns.','geodirectory');
                        }
                        break;
                endswitch;

                $extra_field_query = '';
                if (!empty($extra_fields)) {
                    $extra_field_query = serialize($extra_fields);
                }

                $decimal_point = $field_type == 'text' && $data_type == 'FLOAT' ? $decimal_point : '';

                $wpdb->query(

                    $wpdb->prepare(

                        "update " . GEODIR_CUSTOM_FIELDS_TABLE . " set 
					post_type = %s,
					admin_title = %s,
					site_title = %s,
					field_type = %s,
					field_type_key = %s,
					htmlvar_name = %s,
					admin_desc = %s,
					clabels = %s,
					default_value = %s,
					sort_order = %s,
					is_active = %s,
					is_default  = %s,
					is_required = %s,
					required_msg = %s,
					css_class = %s,
					field_icon = %s,
					field_icon = %s,
					show_on_listing = %s,
					show_in = %s,
					show_on_detail = %s, 
					show_as_tab = %s, 
					option_values = %s, 
					packages = %s, 
					cat_sort = %d, 
					cat_filter = %s, 
					data_type = %s,
					extra_fields = %s,
					decimal_point = %s,
					validation_pattern = %s,
					validation_msg = %s,
					for_admin_use = %s
					where id = %d",

                        array($post_type, $admin_title, $site_title, $field_type, $field_type_key, $htmlvar_name, $admin_desc, $clabels, $default_value, $sort_order, $is_active, $is_default, $is_required, $required_msg, $css_class, $field_icon, $field_icon, $show_on_listing, $show_in, $show_on_detail, $show_as_tab, $option_values, $price_pkg, $cat_sort, $cat_filter, $data_type, $extra_field_query, $decimal_point,$validation_pattern,$validation_msg, $for_admin_use, $cf)
                    )

                );

                $lastid = trim($cf);


                $wpdb->query(
                    $wpdb->prepare(
                        "update " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " set 
					 	site_title=%s
					where post_type = %s and htmlvar_name = %s",
                        array($site_title, $post_type, $htmlvar_name)
                    )
                );


                if ($cat_sort == '')
                    $wpdb->query($wpdb->prepare("delete from " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " where post_type = %s and htmlvar_name = %s", array($post_type, $htmlvar_name)));


                /**
                 * Called after all custom fields are saved for a post.
                 *
                 * @since 1.0.0
                 * @param int $lastid The post ID.
                 */
                do_action('geodir_after_custom_fields_updated', $lastid);

            } else {

                switch ($field_type):

                    case 'address':

                        $data_type = '';

                        if ($htmlvar_name != '') {
                            $prefix = $htmlvar_name . '_';
                        }
                        $old_prefix = $old_html_variable;

                        //$meta_field_add = "ALTER TABLE ".$detail_table." ADD `".$prefix."address` VARCHAR( 254 ) NULL";

                        $meta_field_add = "VARCHAR( 254 ) NULL";
                        if ($default_value != '') {
                            $meta_field_add .= " DEFAULT '" . $default_value . "'";
                        }

                        geodir_add_column_if_not_exist($detail_table, $prefix . "address", $meta_field_add);
                        //$wpdb->query($meta_field_add);


                        if (!empty($extra_fields)) {

                            if (isset($extra_fields['show_city']) && $extra_fields['show_city']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "city` VARCHAR( 30 ) NULL";
                                $meta_field_add = "VARCHAR( 50 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "city", $meta_field_add);
                                //$wpdb->query($meta_field_add);
                            }
                            if (isset($extra_fields['show_region']) && $extra_fields['show_region']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "region` VARCHAR( 30 ) NULL";
                                $meta_field_add = "VARCHAR( 50 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "region", $meta_field_add);
                                //$wpdb->query($meta_field_add);
                            }
                            if (isset($extra_fields['show_country']) && $extra_fields['show_country']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "country` VARCHAR( 30 ) NULL";

                                $meta_field_add = "VARCHAR( 30 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "country", $meta_field_add);
                                //$wpdb->query($meta_field_add);
                            }
                            if (isset($extra_fields['show_zip']) && $extra_fields['show_zip']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "zip` VARCHAR( 15 ) NULL";
                                $meta_field_add = "VARCHAR( 15 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "zip", $meta_field_add);
                                //$wpdb->query($meta_field_add);
                            }
                            if (isset($extra_fields['show_map']) && $extra_fields['show_map']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "latitude` VARCHAR( 20 ) NULL";
                                $meta_field_add = "VARCHAR( 20 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "latitude", $meta_field_add);
                                //$wpdb->query($meta_field_add);

                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "longitude` VARCHAR( 20 ) NULL";

                                $meta_field_add = "VARCHAR( 20 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "longitude", $meta_field_add);

                                //$wpdb->query($meta_field_add);
                            }
                            if (isset($extra_fields['show_mapview']) && $extra_fields['show_mapview']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "mapview` VARCHAR( 15 ) NULL";

                                $meta_field_add = "VARCHAR( 15 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "mapview", $meta_field_add);

                                //$wpdb->query($meta_field_add);
                            }
                            if (isset($extra_fields['show_mapzoom']) && $extra_fields['show_mapzoom']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "mapzoom` VARCHAR( 3 ) NULL";

                                $meta_field_add = "VARCHAR( 3 ) NULL";
                                if ($default_value != '') {
                                    $meta_field_add .= " DEFAULT '" . $default_value . "'";
                                }

                                geodir_add_column_if_not_exist($detail_table, $prefix . "mapzoom", $meta_field_add);

                                //$wpdb->query($meta_field_add);
                            }
                            // show lat lng
                            if (isset($extra_fields['show_latlng']) && $extra_fields['show_latlng']) {
                                $meta_field_add = "ALTER TABLE " . $detail_table . " ADD `" . $prefix . "latlng` VARCHAR( 3 ) NULL";

                                $meta_field_add = "VARCHAR( 3 ) NULL";
                                $meta_field_add .= " DEFAULT '1'";

                                geodir_add_column_if_not_exist($detail_table, $prefix . "latlng", $meta_field_add);
                                //$wpdb->query($meta_field_add);
                            }
                        }

                        break;

                    case 'checkbox':
                        $data_type = 'TINYINT';

                        $meta_field_add = $data_type . "( 1 ) NOT NULL ";
                        if ((int)$default_value === 1) {
                            $meta_field_add .= " DEFAULT '1'";
                        }

                        $add_result = geodir_add_column_if_not_exist($detail_table, $htmlvar_name, $meta_field_add);
                        if ($add_result === false) {
                            return __('Column creation failed, you may have too many columns or the default value does not match with field data type.', 'geodirectory');
                        }
                        break;
                    case 'multiselect':
                    case 'select':
                        $data_type = 'VARCHAR';
                        $op_size = '500';

                        // only make the field as big as it needs to be.
                        if (isset($option_values) && $option_values && $field_type == 'select') {
                            $option_values_arr = explode(',', $option_values);

                            if (is_array($option_values_arr)) {
                                $op_max = 0;

                                foreach ($option_values_arr as $op_val) {
                                    if (strlen($op_val) && strlen($op_val) > $op_max) {
                                        $op_max = strlen($op_val);
                                    }
                                }

                                if ($op_max) {
                                    $op_size = $op_max;
                                }
                            }
                        } elseif (isset($option_values) && $option_values && $field_type == 'multiselect') {
                            if (strlen($option_values)) {
                                $op_size =  strlen($option_values);
                            }

                            if (isset($request_field['multi_display_type'])) {
                                $extra_fields = $request_field['multi_display_type'];
                            }
                        }

                        $meta_field_add = $data_type . "( $op_size ) NULL ";
                        if ($default_value != '') {
                            $meta_field_add .= " DEFAULT '" . $default_value . "'";
                        }

                        $add_result = geodir_add_column_if_not_exist($detail_table, $htmlvar_name, $meta_field_add);
                        if ($add_result === false) {
                            return __('Column creation failed, you may have too many columns or the default value does not match with field data type.', 'geodirectory');
                        }
                        break;
                    case 'textarea':
                    case 'html':
                    case 'url':
                    case 'file':

                        $data_type = 'TEXT';

                        $default_value_add = " `" . $htmlvar_name . "` " . $data_type . " NULL ";

                        $meta_field_add = $data_type . " NULL ";
                        /*if($default_value != '')
					{ $meta_field_add .= " DEFAULT '".$default_value."'"; }*/

                        $add_result = geodir_add_column_if_not_exist($detail_table, $htmlvar_name, $meta_field_add);
                        if ($add_result === false) {
                            return __('Column creation failed, you may have too many columns or the default value does not match with field data type.', 'geodirectory');
                        }

                        break;

                    case 'datepicker':

                        $data_type = 'DATE';

                        $default_value_add = " `" . $htmlvar_name . "` " . $data_type . " NULL ";

                        $meta_field_add = $data_type . " NULL ";

                        $add_result = geodir_add_column_if_not_exist($detail_table, $htmlvar_name, $meta_field_add);
                        if ($add_result === false) {
                            return __('Column creation failed, you may have too many columns or the default value might have a invalid date format.', 'geodirectory');
                        }

                        break;

                    case 'time':

                        $data_type = 'TIME';

                        $default_value_add = " `" . $htmlvar_name . "` " . $data_type . " NULL ";

                        $meta_field_add = $data_type . " NULL ";

                        $add_result = geodir_add_column_if_not_exist($detail_table, $htmlvar_name, $meta_field_add);
                        if ($add_result === false) {
                            return __('Column creation failed, you may have too many columns or the default value might have a invalid time format.', 'geodirectory');
                        }

                        break;

                    default:

                        if ($data_type != 'VARCHAR' && $data_type != '') {
                            $meta_field_add = $data_type . " NULL ";

                            if ($data_type == 'FLOAT' && $decimal_point > 0) {
                                $meta_field_add = "DECIMAL(11, " . (int)$decimal_point . ") NULL ";
                            }

                            if (is_numeric($default_value) && $default_value != '') {
                                $default_value_add .= " DEFAULT '" . $default_value . "'";
                                $meta_field_add .= " DEFAULT '" . $default_value . "'";
                            }
                        } else {
                            $meta_field_add = " VARCHAR( 254 ) NULL ";

                            if ($default_value != '') {
                                $default_value_add .= " DEFAULT '" . $default_value . "'";
                                $meta_field_add .= " DEFAULT '" . $default_value . "'";
                            }
                        }

                        $add_result = geodir_add_column_if_not_exist($detail_table, $htmlvar_name, $meta_field_add);
                        if ($add_result === false) {
                            return __('Column creation failed, you may have too many columns or the default value does not match with field data type.', 'geodirectory');
                        }
                        break;
                endswitch;

                $extra_field_query = '';
                if (!empty($extra_fields)) {
                    $extra_field_query = serialize($extra_fields);
                }

                $decimal_point = $field_type == 'text' && $data_type == 'FLOAT' ? $decimal_point : '';

                $wpdb->query(

                    $wpdb->prepare(

                        "insert into " . GEODIR_CUSTOM_FIELDS_TABLE . " set 
					post_type = %s,
					admin_title = %s,
					site_title = %s,
					field_type = %s,
					field_type_key = %s,
					htmlvar_name = %s,
					admin_desc = %s,
					clabels = %s,
					default_value = %s,
					sort_order = %d,
					is_active = %s,
					is_default  = %s,
					is_admin = %s,
					is_required = %s,
					required_msg = %s,
					css_class = %s,
					field_icon = %s,
					show_on_listing = %s,
					show_in = %s,
					show_on_detail = %s, 
					show_as_tab = %s, 
					option_values = %s, 
					packages = %s, 
					cat_sort = %s, 
					cat_filter = %s, 
					data_type = %s,
					extra_fields = %s,
					decimal_point = %s,
					validation_pattern = %s,
					validation_msg = %s,
					for_admin_use = %s ",

                        array($post_type, $admin_title, $site_title, $field_type, $field_type_key, $htmlvar_name, $admin_desc, $clabels, $default_value, $sort_order, $is_active, $is_default, $is_admin, $is_required, $required_msg, $css_class, $field_icon, $show_on_listing,$show_in, $show_on_detail, $show_as_tab, $option_values, $price_pkg, $cat_sort, $cat_filter, $data_type, $extra_field_query, $decimal_point,$validation_pattern,$validation_msg, $for_admin_use)

                    )

                );

                $lastid = $wpdb->insert_id;

                $lastid = trim($lastid);

            }

            return (int)$lastid;


        } else {
            return 'HTML Variable Name should be a unique name';
        }

    }
}

/**
 * Set custom field order
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $field_ids List of field ids.
 * @return array|bool Returns field ids when success, else returns false.
 */
function godir_set_field_order($field_ids = array())
{

    global $wpdb;

    $count = 0;
    if (!empty($field_ids)):
        $post_meta_info = false;
        foreach ($field_ids as $id) {

            $cf = trim($id, '_');

            $post_meta_info = $wpdb->query(
                $wpdb->prepare(
                    "update " . GEODIR_CUSTOM_FIELDS_TABLE . " set 
															sort_order=%d 
															where id= %d",
                    array($count, $cf)
                )
            );
            $count++;
        }

        return $post_meta_info;
    else:
        return false;
    endif;
}

function geodir_get_cf_value($cf) {
    global $gd_session;
    $value = '';
    if (is_admin()) {
        global $post;

        if (isset($_REQUEST['post'])) {
            $_REQUEST['pid'] = (int)$_REQUEST['post'];
        }
    }

    if (isset($_REQUEST['backandedit']) && $_REQUEST['backandedit'] && $gd_ses_listing = $gd_session->get('listing')) {
        $post = $gd_ses_listing;
        $value = isset($post[$cf['name']]) ? $post[$cf['name']] : '';
    } elseif (isset($_REQUEST['pid']) && $_REQUEST['pid'] != '') {
        $value = geodir_get_post_meta($_REQUEST['pid'], $cf['name'], true);
    } else {
        if ($value == '') {
            $value = $cf['default'];
        }
    }
    
    /**
     * Filter the custom field value.
     *
     * @since 1.6.20
     * 
     * @param mixed $value Custom field value.
     * @param array $cf Custom field info.
     */
    return apply_filters( 'geodir_get_cf_value', $value, $cf );
}

/**
 * Displays custom fields html.
 *
 * @since 1.0.0
 * @since 1.5.2 Added TERRAIN map type.
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $post The current post object.
 * @global array $geodir_addon_list List of active GeoDirectory extensions.
 * @global object $gd_session GeoDirectory Session object.
 *
 * @param int|string $package_id The package ID.
 * @param string $default Optional. When set to "default" it will display only default fields.
 * @param string $post_type Optional. The wordpress post type.
 */
function geodir_get_custom_fields_html($package_id = '', $default = 'custom', $post_type = 'gd_place') {
    global $is_default, $mapzoom, $gd_session;

    $listing_type = $post_type;

    $custom_fields = geodir_post_custom_fields($package_id, $default, $post_type);

    foreach ($custom_fields as $key => $val) {
        if(isset($val['extra_fields'])){$extra_fields = $val['extra_fields'];}
        $val = stripslashes_deep($val); // strip slashes from labels
        if(isset($val['extra_fields'])){$val['extra_fields'] = $extra_fields;}

        $name = $val['name'];
        $type = $val['type'];
        $is_default = $val['is_default'];

        /* field available to site admin only for edit */
        $for_admin_use = isset($val['for_admin_use']) && (int)$val['for_admin_use'] == 1 ? true : false;
        if ($for_admin_use && !is_super_admin()) {
            continue;
        }

        if (is_admin()) {
            global $post;

            if (isset($_REQUEST['post']))
                $_REQUEST['pid'] = $_REQUEST['post'];
        }

        

        /**
         * Called before the custom fields info is output for submitting a post.
         *
         * Used dynamic hook type geodir_before_custom_form_field_$name.
         *
         * @since 1.0.0
         * @param string $listing_type The post post type.
         * @param int $package_id The price package ID for the post.
         * @param array $val The settings array for the field. {@see geodir_custom_field_save()}.
         * @see 'geodir_after_custom_form_field_$name'
         */
        do_action('geodir_before_custom_form_field_' . $name, $listing_type, $package_id, $val);


        $custom_field = $val;
        $html ='';
        /**
         * Filter the output for custom fields.
         *
         * Here we can remove or add new functions depending on the field type.
         *
         * @param string $html The html to be filtered (blank).
         * @param array $custom_field The custom field array values.
         */
        echo apply_filters("geodir_custom_field_input_{$type}",$html,$custom_field);



        /**
         * Called after the custom fields info is output for submitting a post.
         *
         * Used dynamic hook type geodir_after_custom_form_field_$name.
         *
         * @since 1.0.0
         * @param string $listing_type The post post type.
         * @param int $package_id The price package ID for the post.
         * @param array $val The settings array for the field. {@see geodir_custom_field_save()}.
         * @see 'geodir_before_custom_form_field_$name'
         */
        do_action('geodir_after_custom_form_field_' . $name, $listing_type, $package_id, $val);

    }

}


if (!function_exists('geodir_get_field_infoby')) {
    /**
     * Get custom field using key and value.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @param string $key The key you want to look for.
     * @param string $value The value of the key you want to look for.
     * @param string $geodir_post_type The post type.
     * @return bool|mixed Returns field info when available. otherwise returns false.
     */
    function geodir_get_field_infoby($key = '', $value = '', $geodir_post_type = '')
    {

        global $wpdb;

        $filter = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM " . GEODIR_CUSTOM_FIELDS_TABLE . " WHERE post_type=%s AND " . $key . "='" . $value . "'",
                array($geodir_post_type)
            )
        );

        if ($filter) {
            return $filter;
        } else {
            return false;
        }

    }
}


function geodir_field_icon_proccess($cf){


    if (strpos($cf['field_icon'], 'http') !== false) {
        $field_icon = ' background: url(' . $cf['field_icon'] . ') no-repeat left center;background-size:18px 18px;padding-left: 21px;';
    } elseif (strpos($cf['field_icon'], 'fa fa-') !== false) {
        $field_icon = '<i class="' . $cf['field_icon'] . '"></i>';
    }else{
        $field_icon = $cf['field_icon'];
    }

    return $field_icon;
}

if (!function_exists('geodir_show_listing_info')) {
    /**
     * Show listing info depending on field location.
     *
     * @since 1.0.0
     * @since 1.5.7 Custom fields option values added to db translation.
     *              Changes to display url fields title.
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global object $post The current post object.
     * @global bool $send_to_friend True if send to friend link already rendered. Otherwise false.
     *
     * @param string $fields_location In which page you are going to place this custom fields?. Ex: listing, detail etc.
     * @return string Returns listing info html.
     */
    function geodir_show_listing_info($fields_location = '') {
        global $post, $preview, $wpdb, $send_to_friend;

        $package_info = array();

        $package_info = geodir_post_package_info($package_info, $post);
        $post_package_id = !empty($package_info->pid) ? $package_info->pid : '';
        $p_type = !empty($post->post_type) ? $post->post_type : geodir_get_current_posttype();
        $send_to_friend = false;

        ob_start();
        $fields_info = geodir_post_custom_fields($post_package_id, 'all', $p_type, $fields_location);

        if (!empty($fields_info)) {
            $post = stripslashes_deep($post); // strip slashes
            
            //echo '<div class="geodir-company_info field-group">';
            global $field_set_start;
            $field_set_start = 0;



            foreach ($fields_info as $type) {
                if(isset($type['extra_fields'])){$extra_fields= $type['extra_fields'];}
                $type = stripslashes_deep($type); // strip slashes
                if(isset($type['extra_fields'])){$type['extra_fields'] = $extra_fields;}
                $html = '';
                $field_icon = geodir_field_icon_proccess($type);
                $filed_type = $type['type'];
                $html_var = isset($type['htmlvar_name']) ? $type['htmlvar_name'] : '';
                if($html_var=='post'){$html_var='post_address';}

                /**
                 * Filter the output for custom fields.
                 *
                 * Here we can remove or add new functions depending on the field type.
                 *
                 * @param string $html The html to be filtered (blank).
                 * @param string $fields_location The location the field is to be show.
                 * @param array $type The array of field values.
                 */
                $html = apply_filters("geodir_custom_field_output_{$filed_type}",$html,$fields_location,$type);

                $variables_array = array();


                if ($type['type'] != 'fieldset'):
                    $variables_array['post_id'] = !empty($post->ID) ? $post->ID : (!empty($post->pid) ? $post->pid : NULL);
                    $variables_array['label'] = __($type['site_title'], 'geodirectory');
                    $variables_array['value'] = '';
                    if (isset($post->{$type['htmlvar_name']}))
                        $variables_array['value'] = $post->{$type['htmlvar_name']};
                endif;


                if ($html):

                    /**
                     * Called before a custom fields is output on the frontend.
                     *
                     * @since 1.0.0
                     * @param string $html_var The HTML variable name for the field.
                     */
                    do_action("geodir_before_show_{$html_var}");
                    /**
                     * Filter custom field output.
                     *
                     * @since 1.0.0
                     *
                     * @param string $html_var The HTML variable name for the field.
                     * @param string $html Custom field unfiltered HTML.
                     * @param array $variables_array Custom field variables array.
                     */
                    if ($html) echo apply_filters("geodir_show_{$html_var}", $html, $variables_array);

                    /**
                     * Called after a custom fields is output on the frontend.
                     *
                     * @since 1.0.0
                     * @param string $html_var The HTML variable name for the field.
                     */
                    do_action("geodir_after_show_{$html_var}");

                endif;

            }

            //echo '</div>';

        }


        $html = ob_get_clean();

        /**
         * Filter the custom fields over all output.
         *
         * @param string $html The html of the custom fields.
         * @param string $fields_location The location the fields are being output.
         * @since 1.6.9
         */
        return apply_filters('geodir_show_listing_info',$html,$fields_location);

    }
}

if (!function_exists('geodir_default_date_format')) {
    /**
     * Returns default date format.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @return mixed|string|void Returns default date format.
     */
    function geodir_default_date_format()
    {
        if ($format = get_option('date_format'))
            return $format;
        else
            return 'dd-mm-yy';
    }
}

if (!function_exists('geodir_get_formated_date')) {
    /**
     * Returns formatted date.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param string $date Date string to convert.
     * @return bool|int|string Returns formatted date.
     */
    function geodir_get_formated_date($date)
    {
        return mysql2date(get_option('date_format'), $date);
    }
}

if (!function_exists('geodir_get_formated_time')) {
    /**
     * Returns formatted time.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param string $time Time string to convert.
     * @return bool|int|string Returns formatted time.
     */
    function geodir_get_formated_time($time)
    {
        return mysql2date(get_option('time_format'), $time, $translate = true);
    }
}


if (!function_exists('geodir_save_post_file_fields')) {
    /**
     * Save post file fields
     *
     * @since 1.0.0
     * @since 1.4.7 Added `$extra_fields` parameter.
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @global object $current_user Current user object.
     * @param int $post_id
     * @param string $field_id
     * @param array $post_image
     * @param array $extra_fields Array of extra fields.
     */
    function geodir_save_post_file_fields($post_id = 0, $field_id = '', $post_image = array(), $extra_fields = array())
    {

        global $wpdb, $plugin_prefix, $current_user;

        $post_type = get_post_type($post_id);
        //echo $field_id; exit;
        $table = $plugin_prefix . $post_type . '_detail';

        $postcurr_images = array();
        $postcurr_images = geodir_get_post_meta($post_id, $field_id, true);
        $file_urls = '';

        if (!empty($post_image)) {

            $invalid_files = array();

            //Get and remove all old images of post from database to set by new order
            $geodir_uploaddir = '';
            $uploads = wp_upload_dir();
            $uploads_dir = $uploads['path'];

            $geodir_uploadpath = $uploads['path'];
            $geodir_uploadurl = $uploads['url'];
            $sub_dir = $uploads['subdir'];

            $allowed_file_types = !empty($extra_fields['gd_file_types']) && is_array($extra_fields['gd_file_types']) && !in_array("*", $extra_fields['gd_file_types'] ) ? $extra_fields['gd_file_types'] : '';

            for ($m = 0; $m < count($post_image); $m++) {

                /* --------- start ------- */

                if (!$find_image = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM " . $table . " WHERE $field_id = %s AND post_id = %d", array($post_image[$m], $post_id)))) {


                    $curr_img_url = $post_image[$m];
                    $image_name_arr = explode('/', $curr_img_url);
                    $curr_img_dir = $image_name_arr[count($image_name_arr) - 2];
                    $filename = end($image_name_arr);
                    $img_name_arr = explode('.', $filename);

                    $arr_file_type = wp_check_filetype($filename);

                    if (empty($arr_file_type['ext']) || empty($arr_file_type['type'])) {
                        continue;
                    }

                    $uploaded_file_type = $arr_file_type['type'];
                    $uploaded_file_ext = $arr_file_type['ext'];

                    if (!empty($allowed_file_types) && !in_array($uploaded_file_ext, $allowed_file_types)) {
                        continue; // Invalid file type.
                    }

                    // Set an array containing a list of acceptable formats
                    //$allowed_file_types = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/octet-stream', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv', 'text/plain');

                    if (!function_exists('wp_handle_upload'))
                        require_once(ABSPATH . 'wp-admin/includes/file.php');

                    if (!is_dir($geodir_uploadpath))
                        mkdir($geodir_uploadpath);

                    $new_name = $post_id . '_' . $field_id . '_' . $img_name_arr[0] . '.' . $img_name_arr[1];
                    $explode_sub_dir = explode("/", $sub_dir);
                    if ($curr_img_dir == end($explode_sub_dir)) {
                        $img_path = $geodir_uploadpath . '/' . $filename;
                        $img_url = $geodir_uploadurl . '/' . $filename;
                    } else {
                        $img_path = $uploads_dir . '/temp_' . $current_user->data->ID . '/' . $filename;
                        $img_url = $uploads['url'] . '/temp_' . $current_user->data->ID . '/' . $filename;
                    }

                    $uploaded_file = '';
                    if (file_exists($img_path))
                        $uploaded_file = copy($img_path, $geodir_uploadpath . '/' . $new_name);

                    if ($curr_img_dir != $geodir_uploaddir) {
                        if (file_exists($img_path))
                            unlink($img_path);
                    }

                    if (!empty($uploaded_file))
                        $file_urls = $geodir_uploadurl . '/' . $new_name;

                } else {
                    $file_urls = $post_image[$m];
                }
            }


        }

        //Remove all old attachments and temp images
        if (!empty($postcurr_images)) {

            if ($file_urls != $postcurr_images) {
                $invalid_files[] = (object)array('src' => $postcurr_images);
                $invalid_files = (object)$invalid_files;
            }
        }

        geodir_save_post_meta($post_id, $field_id, $file_urls);

        if (!empty($invalid_files))
            geodir_remove_attachments($invalid_files);

    }
}


add_filter('upload_mimes', 'geodir_custom_upload_mimes');
/**
 * Returns list of supported mime types for upload handling.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $existing_mimes List of existing mime types.
 * @return array Returns list of supported mime types.
 */
function geodir_custom_upload_mimes($existing_mimes = array())
{
    $existing_mimes['wif'] = 'text/plain';
    $existing_mimes['jpg|jpeg'] = 'image/jpeg';
    $existing_mimes['gif'] = 'image/gif';
    $existing_mimes['png'] = 'image/png';
    $existing_mimes['pdf'] = 'application/pdf';
    $existing_mimes['txt'] = 'text/text';
    $existing_mimes['csv'] = 'application/octet-stream';
    $existing_mimes['doc'] = 'application/msword';
    $existing_mimes['xla|xls|xlt|xlw'] = 'application/vnd.ms-excel';
    $existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    $existing_mimes['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    return $existing_mimes;
}

if (!function_exists('geodir_plupload_action')) {

    /**
     * Get upload directory path details
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $current_user Current user object.
     * @param array $upload Array of upload directory data with keys of 'path','url', 'subdir, 'basedir', and 'error'.
     * @return mixed Returns upload directory details as an array.
     */
    function geodir_upload_dir($upload)
    {
        global $current_user;
        $upload['subdir'] = $upload['subdir'] . '/temp_' . $current_user->data->ID;
        $upload['path'] = $upload['basedir'] . $upload['subdir'];
        $upload['url'] = $upload['baseurl'] . $upload['subdir'];
        return $upload;
    }

    /**
     * Handles place file and image upload.
     *
     * @since 1.0.0
     * @package GeoDirectory
     */
    function geodir_plupload_action()
    {
        // check ajax nonce
        $imgid = $_POST["imgid"];

        check_ajax_referer($imgid . 'pluploadan');

        // handle custom file uploaddir
        add_filter('upload_dir', 'geodir_upload_dir');

        // change file orientation if needed
        $fixed_file = geodir_exif($_FILES[$imgid . 'async-upload']);

        // handle file upload
        $status = wp_handle_upload($fixed_file, array('test_form' => true, 'action' => 'plupload_action'));
        // remove handle custom file uploaddir
        remove_filter('upload_dir', 'geodir_upload_dir');

        if(!isset($status['url']) && isset($status['error'])){
            print_r($status);
        }

        // send the uploaded file url in response
        if (isset($status['url'])) {
            echo $status['url'];
        } else {
            echo 'x';
        }
        exit;
    }
}

/**
 * Get video using post ID.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int $post_id The post ID.
 * @return mixed Returns video.
 */
function geodir_get_video($post_id)
{
    global $wpdb, $plugin_prefix;

    $post_type = get_post_type($post_id);

    $table = $plugin_prefix . $post_type . '_detail';

    $results = $wpdb->get_results($wpdb->prepare("SELECT geodir_video FROM " . $table . " WHERE post_id=%d", array($post_id)));

    if ($results) {
        return $results[0]->geodir_video;
    }

}

/**
 * Get special offers using post ID.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int $post_id The post ID.
 * @return mixed Returns special offers.
 */
function geodir_get_special_offers($post_id)
{
    global $wpdb, $plugin_prefix;

    $post_type = get_post_type($post_id);

    $table = $plugin_prefix . $post_type . '_detail';

    $results = $wpdb->get_results($wpdb->prepare("SELECT geodir_special_offers FROM " . $table . " WHERE post_id=%d", array($post_id)));

    if ($results) {
        return $results[0]->geodir_special_offers;
    }

}

if (!function_exists('geodir_max_upload_size')) {
    /**
     * Get max upload file size
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @return mixed|void Returns max upload file size.
     */
    function geodir_max_upload_size()
    {
        $max_filesize = (float)get_option('geodir_upload_max_filesize', 2);

        if ($max_filesize > 0 && $max_filesize < 1) {
            $max_filesize = (int)($max_filesize * 1024) . 'kb';
        } else {
            $max_filesize = $max_filesize > 0 ? $max_filesize . 'mb' : '2mb';
        }
        /** Filter documented in geodirectory-functions/general_functions.php **/
        return apply_filters('geodir_default_image_upload_size_limit', $max_filesize);
    }
}


add_filter('geodir_add_custom_sort_options', 'geodir_add_custom_sort_options', 0, 2);

/**
 * Add custom sort options to the existing fields.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $fields The sorting fields array
 * @param string $post_type The post type.
 * @return array Returns the fields.
 */
function geodir_add_custom_sort_options($fields, $post_type)
{
    global $wpdb;

    if ($post_type != '') {

        $all_postypes = geodir_get_posttypes();

        if (in_array($post_type, $all_postypes)) {

            $custom_fields = $wpdb->get_results(
                $wpdb->prepare(
                    "select post_type,data_type,field_type,site_title,htmlvar_name,field_icon from " . GEODIR_CUSTOM_FIELDS_TABLE . " where post_type = %s and is_active='1' and cat_sort='1' AND field_type != 'address' order by sort_order asc",
                    array($post_type)
                ), 'ARRAY_A'
            );

            if (!empty($custom_fields)) {

                foreach ($custom_fields as $val) {
                    $fields[] = $val;
                }
            }

        }

    }

    return $fields;
}


/**
 * Get sort options based on post type.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param string $post_type The post type.
 * @return bool|mixed|void Returns sort options when post type available. Otherwise returns false.
 */
function geodir_get_custom_sort_options($post_type = '')
{

    global $wpdb;

    if ($post_type != '') {

        $all_postypes = geodir_get_posttypes();

        if (!in_array($post_type, $all_postypes))
            return false;

        $fields = array();

        $fields[] = array(
            'post_type' => $post_type,
            'data_type' => '',
            'field_type' => 'random',
            'site_title' => 'Random',
            'htmlvar_name' => 'post_title',
            'field_icon' =>  'fa fa-random',
            'description' =>  __('Random sort (not recommended for large sites)', 'geodirectory')
        );

        $fields[] = array(
            'post_type' => $post_type,
            'data_type' => '',
            'field_type' => 'datetime',
            'site_title' => __('Add date', 'geodirectory'),
            'htmlvar_name' => 'post_date',
            'field_icon' =>  'fa fa-calendar',
            'description' =>  __('Sort by date added', 'geodirectory')
        );
        $fields[] = array(
            'post_type' => $post_type,
            'data_type' => '',
            'field_type' => 'bigint',
            'site_title' => __('Review', 'geodirectory'),
            'htmlvar_name' => 'comment_count',
            'field_icon' =>  'fa fa-commenting-o',
            'description' =>  __('Sort by the number of reviews', 'geodirectory')
        );
        $fields[] = array(
            'post_type' => $post_type,
            'data_type' => '',
            'field_type' => 'float',
            'site_title' => __('Rating', 'geodirectory'),
            'htmlvar_name' => 'overall_rating',
            'field_icon' =>  'fa fa-star-o',
            'description' =>  __('Sort by the overall rating value', 'geodirectory')
        );
        $fields[] = array(
            'post_type' => $post_type,
            'data_type' => '',
            'field_type' => 'text',
            'site_title' => __('Title', 'geodirectory'),
            'htmlvar_name' => 'post_title',
            'field_icon' =>  'fa fa-sort-alpha-desc',
            'description' =>  __('Sort alphabetically by title', 'geodirectory')
        );

        /**
         * Hook to add custom sort options.
         *
         * @since 1.0.0
         * @param array $fields Unmodified sort options array.
         * @param string $post_type Post type.
         */
        return $fields = apply_filters('geodir_add_custom_sort_options', $fields, $post_type);

    }

    return false;
}


/**
 * Set custom sort field order.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @param array $field_ids List of field ids.
 * @return array|bool Returns field ids. If no field id, returns false.
 */
function godir_set_sort_field_order($field_ids = array())
{

    global $wpdb;

    $count = 0;
    if (!empty($field_ids)):
        foreach ($field_ids as $id) {

            $cf = trim($id, '_');

            $post_meta_info = $wpdb->query(
                $wpdb->prepare(
                    "update " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " set 
															sort_order=%d 
															where id= %d",
                    array($count, $cf)
                )
            );
            $count++;
        }

        return $field_ids;
    else:
        return false;
    endif;
}


if (!function_exists('geodir_custom_sort_field_save')) {
    /**
     * Save or Update custom sort fields into the database.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param array $request_field {
     *    Attributes of the Request field.
     *
     *    @type string $action Ajax action name.
     *    @type string $manage_field_type Manage field type Default "sorting_options".
     *    @type string $create_field Do you want to create this field?.
     *    @type string $field_ins_upd Field created or updated?.
     *    @type string $_wpnonce Nonce value.
     *    @type string $listing_type The Post type.
     *    @type string $field_type Field Type.
     *    @type string $field_id Field ID.
     *    @type string $data_type Data Type.
     *    @type string $htmlvar_name HTML variable name.
     *    @type string $site_title Section title which you wish to display in frontend.
     *    @type string $is_default Is this default sorting?.
     *    @type string $is_active If not active then the field will not be displayed anywhere.
     *    @type string $sort_order Sort Order.
     *
     * }
     * @param bool $default Not yet implemented.
     * @return int Returns the last affected db table row id.
     */
    function geodir_custom_sort_field_save($request_field = array(), $default = false)
    {

        global $wpdb, $plugin_prefix;

        $result_str = isset($request_field['field_id']) ? trim($request_field['field_id']) : '';

        $cf = trim($result_str, '_');

        /*-------- check duplicate validation --------*/

        $field_type = isset($request_field['field_type']) ? $request_field['field_type'] : '';
        $cehhtmlvar_name = isset($request_field['htmlvar_name']) ? $request_field['htmlvar_name'] : '';

        $post_type = $request_field['listing_type'];
        $data_type = isset($request_field['data_type']) ? $request_field['data_type'] : '';
        $field_type = isset($request_field['field_type']) ? $request_field['field_type'] : '';
        $site_title = isset($request_field['site_title']) ? $request_field['site_title'] : '';
        $htmlvar_name = isset($request_field['htmlvar_name']) ? $request_field['htmlvar_name'] : '';
        $sort_order = isset($request_field['sort_order']) ? $request_field['sort_order'] : 0;
        $is_active = isset($request_field['is_active']) ? $request_field['is_active'] : 0;
        $is_default = isset($request_field['is_default']) ? $request_field['is_default'] : '';
        $asc = isset($request_field['asc']) ? $request_field['asc'] : 0;
        $desc = isset($request_field['desc']) ? $request_field['desc'] : 0;
        $asc_title = isset($request_field['asc_title']) ? $request_field['asc_title'] : '';
        $desc_title = isset($request_field['desc_title']) ? $request_field['desc_title'] : '';

        $default_order = '';
        if ($is_default != '') {
            $default_order = $is_default;
            $is_default = '1';
        }


        $check_html_variable = $wpdb->get_var(
            $wpdb->prepare(
                "select htmlvar_name from " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " where htmlvar_name = %s and post_type = %s and field_type=%s ",
                array($cehhtmlvar_name, $post_type, $field_type)
            )
        );

        if ($is_default == 1) {

            $wpdb->query($wpdb->prepare("update " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " set is_default='0', default_order='' where post_type = %s", array($post_type)));

        }


        if (!$check_html_variable) {

            $wpdb->query(

                $wpdb->prepare(

                    "insert into " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " set 
				post_type = %s,
				data_type = %s,
				field_type = %s,
				site_title = %s,
				htmlvar_name = %s,
				sort_order = %d,
				is_active = %d,
				is_default = %d,
				default_order = %s,
				sort_asc = %d,
				sort_desc = %d,
				asc_title = %s,
				desc_title = %s",

                    array($post_type, $data_type, $field_type, $site_title, $htmlvar_name, $sort_order, $is_active, $is_default, $default_order, $asc, $desc, $asc_title, $desc_title)
                )

            );


            $lastid = $wpdb->insert_id;

            $lastid = trim($lastid);

        } else {

            $wpdb->query(

                $wpdb->prepare(

                    "update " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " set 
				post_type = %s,
				data_type = %s,
				field_type = %s,
				site_title = %s,
				htmlvar_name = %s,
				sort_order = %d,
				is_active = %d,
				is_default = %d,
				default_order = %s,
				sort_asc = %d,
				sort_desc = %d,
				asc_title = %s,
				desc_title = %s
				where id = %d",

                    array($post_type, $data_type, $field_type, $site_title, $htmlvar_name, $sort_order, $is_active, $is_default, $default_order, $asc, $desc, $asc_title, $desc_title, $cf)
                )

            );

            $lastid = trim($cf);

        }


        return (int)$lastid;

    }
}


if (!function_exists('geodir_custom_sort_field_delete')) {
    /**
     * Delete a custom sort field using field id.
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param string $field_id The field ID.
     * @return int|string Returns field id when successful deletion, else returns 0.
     */
    function geodir_custom_sort_field_delete($field_id = '')
    {

        global $wpdb, $plugin_prefix;
        if ($field_id != '') {
            $cf = trim($field_id, '_');

            $wpdb->query($wpdb->prepare("delete from " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " where id= %d ", array($cf)));

            return $field_id;

        } else
            return 0;

    }
}


if (!function_exists('geodir_custom_sort_field_adminhtml')) {
    /**
     * Custom sort field admin html.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @param string $field_type The form field type.
     * @param object|int $result_str The custom field results object or row id.
     * @param string $field_ins_upd When set to "submit" displays form.
     * @param bool $default when set to true field will be for admin use only.
     */
    function geodir_custom_sort_field_adminhtml($field_type, $result_str, $field_ins_upd = '', $field_type_key='')
    {
        global $wpdb;
        $cf = $result_str;
        if (!is_object($cf)) {
            $field_info = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . GEODIR_CUSTOM_SORT_FIELDS_TABLE . " WHERE id = %d", array($cf)));
        } else {
            $field_info = $cf;
            $result_str = $cf->id;
        }

        $field_info = stripslashes_deep($field_info); // strip slashes

        if (!isset($field_info->post_type)) {
            $post_type = sanitize_text_field($_REQUEST['listing_type']);
        } else {
            $post_type = $field_info->post_type;
        }


        $htmlvar_name = isset($field_type_key) ? $field_type_key : '';

        $site_title = '';
        if ($site_title == '')
            $site_title = isset($field_info->site_title) ? $field_info->site_title : '';

        if ($site_title == '') {
            $fields = geodir_get_custom_sort_options($post_type);

            foreach ($fields as $val) {
                $val = stripslashes_deep($val); // strip slashes

                if ($val['field_type'] == $field_type && $val['htmlvar_name'] == $htmlvar_name) {
                    $site_title = isset($val['site_title']) ? $val['site_title'] : '';
                }
            }
        }

        if ($htmlvar_name == '')
            $htmlvar_name = isset($field_info->htmlvar_name) ? $field_info->htmlvar_name : '';

        $nonce = wp_create_nonce('custom_fields_' . $result_str);

        $field_icon = '<i class="fa fa-cog" aria-hidden="true"></i>';
        $cso_arr = geodir_get_custom_sort_options($post_type);

        $cur_field_type = (isset($cf->field_type)) ? $cf->field_type : esc_html($_REQUEST['field_type']);
        foreach($cso_arr as $cso){
            if($cur_field_type==$cso['field_type']){

                if (isset($cso['field_icon']) && strpos($cso['field_icon'], 'fa fa-') !== false) {
                    $field_icon = '<i class="'.$cso['field_icon'].'" aria-hidden="true"></i>';
                }elseif(isset($cso['field_icon']) && $cso['field_icon']){
                    $field_icon = '<b style="background-image: url("'.$cso['field_icon'].'")"></b>';
                }

            }
        }

        $radio_id = (isset($field_info->htmlvar_name)) ? $field_info->htmlvar_name.$field_type : rand(5, 500);
        ?>

        <li class="text" id="licontainer_<?php echo $result_str;?>">
            <form><!-- we need to wrap in a form so we can use radio buttons with same name -->
            <div class="title title<?php echo $result_str;?> gt-fieldset"
                 title="<?php _e('Double Click to toggle and drag-drop to sort', 'geodirectory');?>"
                 ondblclick="show_hide('field_frm<?php echo $result_str;?>')">
                <?php

                ?>

                <div title="<?php _e('Click to remove field', 'geodirectory');?>"
                     onclick="delete_sort_field('<?php echo $result_str;?>', '<?php echo $nonce;?>', this)"
                     class="handlediv close"><i class="fa fa-times" aria-hidden="true"></i></div>


                <?php echo $field_icon;?>
                <b style="cursor:pointer;"
                   onclick="show_hide('field_frm<?php echo $result_str;?>')"><?php echo geodir_ucwords(__('Field:', 'geodirectory') . ' (' . $site_title . ')');?></b>

            </div>

            <div id="field_frm<?php echo $result_str;?>" class="field_frm"
                 style="display:<?php if ($field_ins_upd == 'submit') {
                     echo 'block;';
                 } else {
                     echo 'none;';
                 } ?>">
                <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>"/>
                <input type="hidden" name="listing_type" id="listing_type" value="<?php echo $post_type;?>"/>
                <input type="hidden" name="field_type" id="field_type" value="<?php echo $field_type;?>"/>
                <input type="hidden" name="field_id" id="field_id" value="<?php echo $result_str;?>"/>
                <input type="hidden" name="data_type" id="data_type" value="<?php if (isset($field_info->data_type)) {
                    echo $field_info->data_type;
                }?>"/>
                <input type="hidden" name="htmlvar_name" id="htmlvar_name" value="<?php echo $htmlvar_name;?>"/>


                <ul class="widefat post fixed" border="0" style="width:100%;">

                    <?php if ($field_type != 'random') { ?>

                        <input type="hidden" name="site_title" id="site_title" value="<?php echo esc_attr($site_title); ?>"/>

                        <li>
                            <?php $value = (isset($field_info->sort_asc) && $field_info->sort_asc) ? $field_info->sort_asc : 0;?>

                            <label for="asc" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Show Ascending Sort (low to high)', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('Select if you want to show this option in the sort options. (A-Z,0-100 or OFF)', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap gd-switch">

                                <input type="radio" id="asc_yes<?php echo $radio_id;?>" name="asc" class="gdri-enabled"  value="1"
                                    <?php if ($value == '1') {
                                        echo 'checked';
                                    } ?>/>
                                <label onclick="show_hide_radio(this,'show','cfs-asc-title');" for="asc_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                                <input type="radio" id="asc_no<?php echo $radio_id;?>" name="asc" class="gdri-disabled" value="0"
                                    <?php if ($value == '0' || !$value) {
                                        echo 'checked';
                                    } ?>/>
                                <label onclick="show_hide_radio(this,'hide','cfs-asc-title');" for="asc_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                            </div>

                        </li>

                        <li class="cfs-asc-title" <?php if ((isset($field_info->sort_asc) && $field_info->sort_asc == '0') || !isset($field_info->sort_asc)) {echo "style='display:none;'";}?>>
                            <?php $value = (isset($field_info->asc_title) && $field_info->asc_title) ? esc_attr($field_info->asc_title) : '';?>

                            <label for="asc_title" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Ascending title', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('This is the text used for the sort option.', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap">

                                <input type="text" name="asc_title" id="asc_title" value="<?php echo $value;?>" />
                            </div>


                        </li>


                        <li class="cfs-asc-title" <?php if ((isset($field_info->sort_asc) && $field_info->sort_asc == '0') || !isset($field_info->sort_asc)) {echo "style='display:none;'";}?>>

                            <label for="is_default" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Default sort?', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('This sets the option as the overall default sort value, there can be only one.', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap">

                                <input type="radio" name="is_default"
                                       value="<?php echo $htmlvar_name; ?>_asc" <?php if (isset($field_info->default_order) && $field_info->default_order == $htmlvar_name . '_asc') {
                                    echo 'checked="checked"';
                                } ?>/>
                            </div>

                        </li>



                        <li>
                            <?php $value = (isset($field_info->sort_desc) && $field_info->sort_desc) ? $field_info->sort_desc : 0;?>

                            <label for="desc" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Show Descending Sort (high to low)', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('Select if you want to show this option in the sort options. (Z-A,100-0 or ON)', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap gd-switch">

                                <input type="radio" id="desc_yes<?php echo $radio_id;?>" name="desc" class="gdri-enabled"  value="1"
                                    <?php if ($value == '1') {
                                        echo 'checked';
                                    } ?>/>
                                <label onclick="show_hide_radio(this,'show','cfs-desc-title');" for="desc_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                                <input type="radio" id="desc_no<?php echo $radio_id;?>" name="desc" class="gdri-disabled" value="0"
                                    <?php if ($value == '0' || !$value) {
                                        echo 'checked';
                                    } ?>/>
                                <label onclick="show_hide_radio(this,'hide','cfs-desc-title');" for="desc_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                            </div>

                        </li>

                        <li class="cfs-desc-title" <?php if ((isset($field_info->sort_desc) && $field_info->sort_desc == '0') || !isset($field_info->sort_desc)) {echo "style='display:none;'";}?>>
                            <?php $value = (isset($field_info->desc_title) && $field_info->desc_title) ? esc_attr($field_info->desc_title) : '';?>

                            <label for="desc_title" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Descending title', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('This is the text used for the sort option.', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap">

                                <input type="text" name="desc_title" id="desc_title" value="<?php echo $value;?>" />
                            </div>


                        </li>

                        <li class="cfs-desc-title" <?php if ((isset($field_info->sort_desc) && $field_info->sort_desc == '0') || !isset($field_info->sort_desc)) {echo "style='display:none;'";}?>>

                            <label for="is_default" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Default sort?', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('This sets the option as the overall default sort value, there can be only one.', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap">

                                <input type="radio" name="is_default"
                                       value="<?php echo $htmlvar_name; ?>_desc" <?php if (isset($field_info->default_order) && $field_info->default_order == $htmlvar_name . '_desc') {
                                    echo 'checked="checked"';
                                } ?>/>
                            </div>

                        </li>


                    <?php } else { ?>





                        <li>
                            <?php $value = esc_attr($site_title)?>

                            <label for="site_title" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Frontend title', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('This is the text used for the sort option.', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap">

                                <input type="text" name="site_title" id="site_title" value="<?php echo $value;?>" />
                            </div>


                        </li>

                        <li>
                            <?php $value = (isset($field_info->is_default) && $field_info->is_default) ? esc_attr($field_info->is_default) : '';?>

                            <label for="is_default" class="gd-cf-tooltip-wrap">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Default sort?', 'geodirectory'); ?>
                                <div class="gdcf-tooltip">
                                    <?php _e('This sets the option as the overall default sort value, there can be only one.', 'geodirectory'); ?>
                                </div>
                            </label>
                            <div class="gd-cf-input-wrap">

                                <input type="checkbox" name="is_default"
                                       value="<?php echo $field_type; ?>"  <?php if (isset($value) && $value == '1') {
                                    echo 'checked="checked"';
                                } ?>/>
                            </div>


                        </li>
                        

                    <?php } ?>


                    <li>
                        <?php $value = (isset($field_info->is_active) && $field_info->is_active) ? $field_info->is_active: 0;?>

                        <label for="is_active" class="gd-cf-tooltip-wrap">
                            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Is active', 'geodirectory'); ?>
                            <div class="gdcf-tooltip">
                                <?php _e('Set if this sort option is active or not, if not it will not be shown to users.', 'geodirectory'); ?>
                            </div>
                        </label>
                        <div class="gd-cf-input-wrap gd-switch">

                            <input type="radio" id="is_active_yes<?php echo $radio_id;?>" name="is_active" class="gdri-enabled"  value="1"
                                <?php if ($value == '1') {
                                    echo 'checked';
                                } ?>/>
                            <label for="is_active_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

                            <input type="radio" id="is_active_no<?php echo $radio_id;?>" name="is_active" class="gdri-disabled" value="0"
                                <?php if ($value == '0' || !$value) {
                                    echo 'checked';
                                } ?>/>
                            <label for="is_active_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

                        </div>

                    </li>


                    <input type="hidden" readonly="readonly" name="sort_order" id="sort_order"
                                                value="<?php if (isset($field_info->sort_order)) {
                                                    echo esc_attr($field_info->sort_order);
                                                }?>" size="50"/>




                    <li>

                        <label for="save" class="gd-cf-tooltip-wrap">
                            <h3></h3>
                        </label>
                        <div class="gd-cf-input-wrap">
                            <input type="button" class="button button-primary" name="save" id="save" value="<?php echo esc_attr(__('Save','geodirectory'));?>"
                                   onclick="save_sort_field('<?php echo esc_attr($result_str); ?>')"/>
                                <a href="javascript:void(0)"><input type="button" name="delete" value="<?php echo esc_attr(__('Delete','geodirectory'));?>"
                                                                    onclick="delete_sort_field('<?php echo $result_str;?>', '<?php echo $nonce;?>', this)"
                                                                    class="button"/></a>
                        </div>
                    </li>
                </ul>

            </div>
            </form>
        </li> <?php

    }
}

if (!function_exists('check_field_visibility')) {
    /**
     * Check field visibility as per price package.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global array $geodir_addon_list List of active GeoDirectory extensions.
     * @param int|string $package_id The package ID.
     * @param string $field_name The field name.
     * @param string $post_type Optional. The wordpress post type.
     * @return bool Returns true when field visible, otherwise false.
     */
    function check_field_visibility($package_id, $field_name, $post_type)
    {
        global $wpdb, $geodir_addon_list;
        if (!(isset($geodir_addon_list['geodir_payment_manager']) && $geodir_addon_list['geodir_payment_manager'] == 'yes')) {
            return true;
        }
        if (!$package_id || !$field_name || !$post_type) {
            return true;
        }
        $sql = $wpdb->prepare("SELECT id FROM " . GEODIR_CUSTOM_FIELDS_TABLE . " WHERE is_active='1' AND htmlvar_name=%s AND post_type=%s AND FIND_IN_SET(%s, packages)", array($field_name, $post_type, (int)$package_id));

        if ($wpdb->get_var($sql)) {
            return true;
        }
        return false;
    }
}

/**
 * Parse label & values from string.
 *
 * @since 1.0.0
 * @since 1.5.7 New parameter $translated added.
 * @since 1.6.11 Input option value should not be translated.
 * @package GeoDirectory
 * @param string $input The string input.
 * @param bool $translated True if label needs to be translated.
 * @return array Returns option array.
 */
function geodir_string_to_options($input = '', $translated = false)
{
    $return = array();
    if ($input != '') {
        $input = trim($input);
        $input = rtrim($input, ",");
        $input = ltrim($input, ",");
        $input = trim($input);
    }

    $input_arr = explode(',', $input);

    if (!empty($input_arr)) {
        foreach ($input_arr as $input_str) {
            $input_str = trim($input_str);

            if (strpos($input_str, "/") !== false) {
                $input_str = explode("/", $input_str, 2);
                $label = trim($input_str[0]);
                if ($translated && $label != '') {
                    $label = __($label, 'geodirectory');
                }
                $label = geodir_utf8_ucfirst($label);
                $value = trim($input_str[1]);
            } else {
                $value = $input_str;
                if ($translated && $input_str != '') {
                    $input_str = __($input_str, 'geodirectory');
                }
                $label = geodir_utf8_ucfirst($input_str);
            }

            if ($label != '') {
                $return[] = array('label' => $label, 'value' => $value, 'optgroup' => NULL);
            }
        }
    }

    return $return;
}

/**
 * Parse option values string to array.
 *
 * @since 1.0.0
 * @since 1.5.7 New parameter $translated added.
 * @package GeoDirectory
 * @param string $option_values The option values.
 * @param bool $translated True if label needs to be translated.
 * @return array Returns option array.
 */
function geodir_string_values_to_options($option_values = '', $translated = false)
{
    $options = array();
    if ($option_values == '') {
        return NULL;
    }

    if (strpos($option_values, "{/optgroup}") !== false) {
        $option_values_arr = explode("{/optgroup}", $option_values);

        foreach ($option_values_arr as $optgroup) {
            if (strpos($optgroup, "{optgroup}") !== false) {
                $optgroup_arr = explode("{optgroup}", $optgroup);

                $count = 0;
                foreach ($optgroup_arr as $optgroup_str) {
                    $count++;
                    $optgroup_str = trim($optgroup_str);

                    $optgroup_label = '';
                    if (strpos($optgroup_str, "|") !== false) {
                        $optgroup_str_arr = explode("|", $optgroup_str, 2);
                        $optgroup_label = trim($optgroup_str_arr[0]);
                        if ($translated && $optgroup_label != '') {
                            $optgroup_label = __($optgroup_label, 'geodirectory');
                        }
                        $optgroup_label = geodir_utf8_ucfirst($optgroup_label);
                        $optgroup_str = $optgroup_str_arr[1];
                    }

                    $optgroup3 = geodir_string_to_options($optgroup_str, $translated);

                    if ($count > 1 && $optgroup_label != '' && !empty($optgroup3)) {
                        $optgroup_start = array(array('label' => $optgroup_label, 'value' => NULL, 'optgroup' => 'start'));
                        $optgroup_end = array(array('label' => $optgroup_label, 'value' => NULL, 'optgroup' => 'end'));
                        $optgroup3 = array_merge($optgroup_start, $optgroup3, $optgroup_end);
                    }
                    $options = array_merge($options, $optgroup3);
                }
            } else {
                $optgroup1 = geodir_string_to_options($optgroup, $translated);
                $options = array_merge($options, $optgroup1);
            }
        }
    } else {
        $options = geodir_string_to_options($option_values, $translated);
    }

    return $options;
}


function geodir_cfa_data_type_text($output,$result_str,$cf,$field_info){
    ob_start();

    $dt_value = '';
    if (isset($field_info->data_type)) {
        $dt_value  = esc_attr($field_info->data_type);
    }elseif(isset($cf['defaults']['data_type']) && $cf['defaults']['data_type']){
        $dt_value  = $cf['defaults']['data_type'];
    }
    ?>
    <li>
        <label for="data_type"><?php _e('Field Data Type ? :', 'geodirectory'); ?></label>
        <div class="gd-cf-input-wrap">

            <select name="data_type" id="data_type"
                    onchange="javascript:gd_data_type_changed(this, '<?php echo $result_str; ?>');">
                <option
                    value="XVARCHAR" <?php if ($dt_value  == 'VARCHAR') {
                    echo 'selected="selected"';
                } ?>><?php _e('CHARACTER', 'geodirectory'); ?></option>
                <option
                    value="INT" <?php if ($dt_value   == 'INT') {
                    echo 'selected="selected"';
                } ?>><?php _e('NUMBER', 'geodirectory'); ?></option>
                <option
                    value="FLOAT" <?php if ($dt_value   == 'FLOAT') {
                    echo 'selected="selected"';
                } ?>><?php _e('DECIMAL', 'geodirectory'); ?></option>
            </select>
            <br/> <span><?php _e('Select Custom Field type', 'geodirectory'); ?></span>

        </div>
    </li>

    <?php
    $value = '';
    if (isset($field_info->decimal_point)) {
        $value = esc_attr($field_info->decimal_point);
    }elseif(isset($cf['defaults']['decimal_point']) && $cf['defaults']['decimal_point']){
        $value = $cf['defaults']['decimal_point'];
    }
    ?>

    <li class="decimal-point-wrapper"
        style="<?php echo ($dt_value  == 'FLOAT') ? '' : 'display:none' ?>">
        <label for="decimal_point"><?php _e('Select decimal point :', 'geodirectory'); ?></label>
        <div class="gd-cf-input-wrap">
            <select name="decimal_point" id="decimal_point">
                <option value=""><?php echo _e('Select', 'geodirectory'); ?></option>
                <?php for ($i = 1; $i <= 10; $i++) {
                    $selected = $i == $value ? 'selected="selected"' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
            <br/> <span><?php _e('Decimal point to display after point', 'geodirectory'); ?></span>
        </div>
    </li>
<?php

    $output = ob_get_clean();
    return $output;
}
add_filter('geodir_cfa_data_type_text','geodir_cfa_data_type_text',10,4);

// htmlvar not needed for fieldset and taxonomy
add_filter('geodir_cfa_htmlvar_name_fieldset','__return_empty_string',10,4);
add_filter('geodir_cfa_htmlvar_name_taxonomy','__return_empty_string',10,4);


// default_value not needed for textarea, html, file, fieldset, taxonomy, address
add_filter('geodir_cfa_default_value_file','__return_empty_string',10,4);
add_filter('geodir_cfa_default_value_taxonomy','__return_empty_string',10,4);
add_filter('geodir_cfa_default_value_address','__return_empty_string',10,4);
add_filter('geodir_cfa_default_value_fieldset','__return_empty_string',10,4);

// is_required not needed for fieldset
add_filter('geodir_cfa_is_required_fieldset','__return_empty_string',10,4);
add_filter('geodir_cfa_required_msg_fieldset','__return_empty_string',10,4);

// field_icon not needed for fieldset
add_filter('geodir_cfa_field_icon_fieldset','__return_empty_string',10,4);
add_filter('geodir_cfa_css_class_fieldset','__return_empty_string',10,4);

// cat_sort not needed for some fields
add_filter('geodir_cfa_cat_sort_html','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_file','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_url','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_fieldset','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_multiselect','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_textarea','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_taxonomy','__return_empty_string',10,4);
add_filter('geodir_cfa_cat_sort_address','__return_empty_string',10,4);



function geodir_cfa_advanced_editor_geodir_special_offers($output,$result_str,$cf,$field_info){
    if($field_info->htmlvar_name != 'geodir_special_offers'){return '';}
    ob_start();
    ?>
    <li>
        <label for="advanced_editor" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Show advanced editor :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Select if you want to show the advanced editor on add listing page.', 'geodirectory'); ?>
            </div>
        </label>

        <div class="gd-cf-input-wrap">

            <?php
            $selected = '';
            if (isset($field_info->extra_fields))
                $advanced_editor = unserialize($field_info->extra_fields);

            if (!empty($advanced_editor) && is_array($advanced_editor) && in_array('1', $advanced_editor))
                $selected = 'checked="checked"';
            ?>

            <input type="checkbox" name="advanced_editor[]" id="advanced_editor"
                   value="1" <?php echo $selected; ?>/>
        </div>

    </li>
    <?php

    $output = ob_get_clean();
    return $output;
}
add_filter('geodir_cfa_advanced_editor_textarea','geodir_cfa_advanced_editor_geodir_special_offers',10,4);


function geodir_cfa_validation_pattern_text($output,$result_str,$cf,$field_info){
    ob_start();

    $value = '';
    if (isset($field_info->validation_pattern)) {
        $value = esc_attr($field_info->validation_pattern);
    }elseif(isset($cf['defaults']['validation_pattern']) && $cf['defaults']['validation_pattern']){
        $value = esc_attr($cf['defaults']['validation_pattern']);
    }
    ?>
    <li>
        <label for="validation_pattern" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Validation Pattern:', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Enter regex expression for HTML5 pattern validation.', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="validation_pattern" id="validation_pattern"
                   value="<?php echo $value; ?>"/>
        </div>
    </li>
    <?php
    $value = '';
    if (isset($field_info->validation_msg)) {
        $value = esc_attr($field_info->validation_msg);
    }elseif(isset($cf['defaults']['validation_msg']) && $cf['defaults']['validation_msg']){
        $value = esc_attr($cf['defaults']['validation_msg']);
    }
    ?>
    <li>
        <label for="validation_msg" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Validation Message:', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Enter a extra validation message to show to the user if validation fails.', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="validation_msg" id="validation_msg"
                   value="<?php echo $value; ?>"/>
        </div>
    </li>
    <?php

    $output = ob_get_clean();
    return $output;
}
add_filter('geodir_cfa_validation_pattern_text','geodir_cfa_validation_pattern_text',10,4);


function geodir_cfa_htmlvar_name_taxonomy($output,$result_str,$cf,$field_info){
    ob_start();
    global $post_type;

    if (!isset($field_info->post_type)) {
        $post_type = sanitize_text_field($_REQUEST['listing_type']);
    } else
        $post_type = $field_info->post_type;
    ?>
    <li style="display: none;">
        <label for="htmlvar_name" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Select taxonomy:', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Selected taxonomy name use as field name index. ex:-( post_category[gd_placecategory] )', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <select name="htmlvar_name" id="htmlvar_name">
                <?php
                $gd_taxonomy = geodir_get_taxonomies($post_type);

                foreach ($gd_taxonomy as $gd_tax) {
                    ?>
                    <option <?php if (isset($field_info->htmlvar_name) && $field_info->htmlvar_name == $gd_tax) {
                        echo 'selected="selected"';
                    }?> id="<?php echo $gd_tax;?>"><?php echo $gd_tax;?></option><?php
                }
                ?>
            </select>
        </div>
    </li>

    <li>
        <label for="cat_display_type" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Category display type :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Show categories list as select, multiselect, checkbox or radio', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">

            <select name="cat_display_type" id="cat_display_type">
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'ajax_chained') {
                    echo 'selected="selected"';
                }?> value="ajax_chained"><?php _e('Ajax Chained', 'geodirectory');?></option>
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'select') {
                    echo 'selected="selected"';
                }?> value="select"><?php _e('Select', 'geodirectory');?></option>
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'multiselect') {
                    echo 'selected="selected"';
                }?> value="multiselect"><?php _e('Multiselect', 'geodirectory');?></option>
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'checkbox') {
                    echo 'selected="selected"';
                }?> value="checkbox"><?php _e('Checkbox', 'geodirectory');?></option>
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'radio') {
                    echo 'selected="selected"';
                }?> value="radio"><?php _e('Radio', 'geodirectory');?></option>
            </select>
        </div>
    </li>
    <?php

    $output = ob_get_clean();
    return $output;
}
add_filter('geodir_cfa_htmlvar_name_taxonomy','geodir_cfa_htmlvar_name_taxonomy',10,4);


function geodir_cfa_extra_fields_address($output,$result_str,$cf,$field_info){

    ob_start();
    if (isset($field_info->extra_fields) && $field_info->extra_fields != '') {
        $address = stripslashes_deep(unserialize($field_info->extra_fields));
    }

    $radio_id = (isset($field_info->htmlvar_name)) ? $field_info->htmlvar_name : rand(5, 500);
    ?>
    <?php
    /**
     * Called on the add custom fields settings page before the address field is output.
     *
     * @since 1.0.0
     * @param array $address The address settings array.
     * @param object $field_info Extra fields info.
     */
    do_action('geodir_address_extra_admin_fields', $address, $field_info); ?>

    <li>
        <label for="show_zip" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Display zip/post code :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Select if you want to show zip/post code field in address section.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap gd-switch">

            <input type="radio" id="show_zip_yes<?php echo $radio_id;?>" name="extra[show_zip]" class="gdri-enabled"  value="1"
                <?php if (isset($address['show_zip']) && $address['show_zip'] == '1') {
                    echo 'checked';
                } ?>/>
            <label onclick="show_hide_radio(this,'show','cf-zip-lable');" for="show_zip_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

            <input type="radio" id="show_zip_no<?php echo $radio_id;?>" name="extra[show_zip]" class="gdri-disabled" value="0"
                <?php if ((isset($address['show_zip']) && !$address['show_zip']) || !isset($address['show_zip'])) {
                    echo 'checked';
                } ?>/>
            <label onclick="show_hide_radio(this,'hide','cf-zip-lable');" for="show_zip_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>


        </div>
    </li>

    <li class="cf-zip-lable"  <?php if ((isset($address['show_zip']) && !$address['show_zip']) || !isset($address['show_zip'])) {echo "style='display:none;'";}?> >
        <label for="zip_lable" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Zip/Post code label :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Enter zip/post code field label in address section.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="extra[zip_lable]" id="zip_lable"
                   value="<?php if (isset($address['zip_lable'])) {
                       echo esc_attr($address['zip_lable']);
                   }?>"/>
        </div>
    </li>

    <input type="hidden" name="extra[show_map]" value="1" />


    <li>
        <label for="map_lable" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Map button label :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Enter text for `set address on map` button in address section.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="extra[map_lable]" id="map_lable"
                   value="<?php if (isset($address['map_lable'])) {
                       echo esc_attr($address['map_lable']);
                   }?>"/>
        </div>
    </li>

    <li>
        <label for="show_mapzoom" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Use user zoom level:', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Do you want to use the user defined map zoom level from the add listing page?', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap gd-switch">

            <input type="radio" id="show_mapzoom_yes<?php echo $radio_id;?>" name="extra[show_mapzoom]" class="gdri-enabled"  value="1"
                <?php if (isset($address['show_mapzoom']) && $address['show_mapzoom'] == '1') {
                    echo 'checked';
                } ?>/>
            <label for="show_mapzoom_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

            <input type="radio" id="show_mapzoom_no<?php echo $radio_id;?>" name="extra[show_mapzoom]" class="gdri-disabled" value="0"
                <?php if ((isset($address['show_mapzoom']) && !$address['show_mapzoom']) || !isset($address['show_mapzoom'])) {
                    echo 'checked';
                } ?>/>
            <label for="show_mapzoom_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

        </div>
    </li>

    <li>
        <label for="show_mapview" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Display map view:', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Select if you want to `set default map` options in address section. ( Satellite Map, Hybrid Map, Terrain Map)', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap gd-switch">

            <input type="radio" id="show_mapview_yes<?php echo $radio_id;?>" name="extra[show_mapview]" class="gdri-enabled"  value="1"
                <?php if (isset($address['show_mapview']) && $address['show_mapview'] == '1') {
                    echo 'checked';
                } ?>/>
            <label for="show_mapview_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

            <input type="radio" id="show_mapview_no<?php echo $radio_id;?>" name="extra[show_mapview]" class="gdri-disabled" value="0"
                <?php if ((isset($address['show_mapview']) && !$address['show_mapview']) || !isset($address['show_mapview'])) {
                    echo 'checked';
                } ?>/>
            <label for="show_mapview_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

        </div>
    </li>


    <li>
        <label for="mapview_lable" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Map view label:', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Enter mapview field label in address section.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="extra[mapview_lable]" id="mapview_lable"
                   value="<?php if (isset($address['mapview_lable'])) {
                       echo esc_attr($address['mapview_lable']);
                   }?>"/>
        </div>
    </li>
    <li>
        <label for="show_latlng" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Show latitude and longitude', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('This will show/hide the longitude fields in the address section add listing form.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap gd-switch">

            <input type="radio" id="show_latlng_yes<?php echo $radio_id;?>" name="extra[show_latlng]" class="gdri-enabled"  value="1"
                <?php if (isset($address['show_latlng']) && $address['show_latlng'] == '1') {
                    echo 'checked';
                } ?>/>
            <label for="show_latlng_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

            <input type="radio" id="show_latlng_no<?php echo $radio_id;?>" name="extra[show_latlng]" class="gdri-disabled" value="0"
                <?php if ((isset($address['show_latlng']) && !$address['show_latlng']) || !isset($address['show_latlng'])) {
                    echo 'checked';
                } ?>/>
            <label for="show_latlng_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

        </div>
    </li>
    <?php

    $html = ob_get_clean();
    return $output.$html;
}
add_filter('geodir_cfa_extra_fields_address','geodir_cfa_extra_fields_address',10,4);


function geodir_cfa_extra_fields_multiselect($output,$result_str,$cf,$field_info){
    ob_start();
    ?>
    <li>
        <label for="multi_display_type" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Multiselect display type :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Show multiselect list as multiselect,checkbox or radio.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">

            <select name="multi_display_type" id="multi_display_type">
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'select') {
                    echo 'selected="selected"';
                }?> value="select"><?php _e('Select', 'geodirectory');?></option>
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'checkbox') {
                    echo 'selected="selected"';
                }?> value="checkbox"><?php _e('Checkbox', 'geodirectory');?></option>
                <option <?php if (isset($field_info->extra_fields) && unserialize($field_info->extra_fields) == 'radio') {
                    echo 'selected="selected"';
                }?> value="radio"><?php _e('Radio', 'geodirectory');?></option>
            </select>

            <br/>
        </div>
    </li>
    <?php

    $html = ob_get_clean();
    return $output.$html;
}
add_filter('geodir_cfa_extra_fields_multiselect','geodir_cfa_extra_fields_multiselect',10,4);


function geodir_cfa_extra_fields_smr($output,$result_str,$cf,$field_info){

    ob_start();

    $value = '';
    if (isset($field_info->option_values)) {
        $value = esc_attr($field_info->option_values);
    }elseif(isset($cf['defaults']['option_values']) && $cf['defaults']['option_values']){
        $value = esc_attr($cf['defaults']['option_values']);
    }

    $field_type = isset($field_info->field_type) ? $field_info->field_type : '';
    ?>
    <li>
        <label for="option_values" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Option Values :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <span><?php _e('Option Values should be separated by comma.', 'geodirectory');?></span>
                <br/>
                <small><span><?php _e('If using for a "tick filter" place a / and then either a 1 for true or 0 for false', 'geodirectory');?></span>
                    <br/>
                    <span><?php _e('eg: "No Dogs Allowed/0,Dogs Allowed/1" (Select only, not multiselect)', 'geodirectory');?></span>
                    <?php if ($field_type == 'multiselect' || $field_type == 'select') { ?>
                        <br/>
                        <span><?php _e('- If using OPTGROUP tag to grouping options, use "{optgroup}OPTGROUP-LABEL|OPTION-1,OPTION-2{/optgroup}"', 'geodirectory'); ?></span>
                        <br/>
                        <span><?php _e('eg: "{optgroup}Pets Allowed|No Dogs Allowed/0,Dogs Allowed/1{/optgroup},{optgroup}Sports|Cricket/Cricket,Football/Football,Hockey{/optgroup}"', 'geodirectory'); ?></span>
                    <?php } ?></small>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="option_values" id="option_values"
                   value="<?php echo $value;?>"/>
            <br/>

        </div>
    </li>
    <?php

    $html = ob_get_clean();
    return $output.$html;
}
add_filter('geodir_cfa_extra_fields_multiselect','geodir_cfa_extra_fields_smr',10,4);
add_filter('geodir_cfa_extra_fields_select','geodir_cfa_extra_fields_smr',10,4);
add_filter('geodir_cfa_extra_fields_radio','geodir_cfa_extra_fields_smr',10,4);


function geodir_cfa_extra_fields_datepicker($output,$result_str,$cf,$field_info){
    ob_start();
    $extra = array();
    if (isset($field_info->extra_fields) && $field_info->extra_fields != '') {
        $extra = unserialize($field_info->extra_fields);
    }
    ?>
    <li>
        <label for="date_format" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Date Format :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Select the date format.', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap" style="overflow:inherit;">
            <?php
            $date_formats = array(
                'm/d/Y',
                'd/m/Y',
                'Y/m/d',
                'm-d-Y',
                'd-m-Y',
                'Y-m-d',
                'F j, Y',
            );
            /**
             * Filter the custom field date format options.
             *
             * @since 1.6.5
             * @param array $date_formats The PHP date format array.
             */
            $date_formats = apply_filters('geodir_date_formats',$date_formats);
            ?>
            <select name="extra[date_format]" id="date_format">
                <?php
                foreach($date_formats as $format){
                    $selected = '';
                    if(!empty($extra) && esc_attr($extra['date_format'])==$format){
                        $selected = "selected='selected'";
                    }
                    echo "<option $selected value='$format'>$format       (".date_i18n( $format, time()).")</option>";
                }
                ?>
            </select>

        </div>
    </li>
    <?php

    $html = ob_get_clean();
    return $output.$html;
}
add_filter('geodir_cfa_extra_fields_datepicker','geodir_cfa_extra_fields_datepicker',10,4);


function geodir_cfa_extra_fields_file($output,$result_str,$cf,$field_info){
    ob_start();
    $allowed_file_types = geodir_allowed_mime_types();

    $extra_fields = isset($field_info->extra_fields) && $field_info->extra_fields != '' ? maybe_unserialize($field_info->extra_fields) : '';
    $gd_file_types = !empty($extra_fields) && !empty($extra_fields['gd_file_types']) ? $extra_fields['gd_file_types'] : array('*');
    ?>
    <li>
        <label for="gd_file_types" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Allowed file types :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Select file types to allowed for file uploading. (Select multiple file types by holding down "Ctrl" key.)', 'geodirectory');?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <select name="extra[gd_file_types][]" id="gd_file_types" multiple="multiple" style="height:100px;width:90%;">
                <option value="*" <?php selected(true, in_array('*', $gd_file_types));?>><?php _e('All types', 'geodirectory') ;?></option>
                <?php foreach ( $allowed_file_types as $format => $types ) { ?>
                    <optgroup label="<?php echo esc_attr( wp_sprintf(__('%s formats', 'geodirectory'), __($format, 'geodirectory') ) ) ;?>">
                        <?php foreach ( $types as $ext => $type ) { ?>
                            <option value="<?php echo esc_attr($ext) ;?>" <?php selected(true, in_array($ext, $gd_file_types));?>><?php echo '.' . $ext ;?></option>
                        <?php } ?>
                    </optgroup>
                <?php } ?>
            </select>
        </div>
    </li>
    <?php

    $html = ob_get_clean();
    return $output.$html;
}
add_filter('geodir_cfa_extra_fields_file','geodir_cfa_extra_fields_file',10,4);

function geodir_cfa_extra_fields_text($output,$result_str,$cf,$field_info){
    ob_start();

    $extra_fields = isset($field_info->extra_fields) && $field_info->extra_fields != '' ? maybe_unserialize($field_info->extra_fields) : '';
   // print_r($cf);echo '###';



    $radio_id = (isset($field_info->htmlvar_name)) ? $field_info->htmlvar_name : rand(5, 500);


    $value = '';
    if ($extra_fields && isset($extra_fields['is_price'])) {
    $value = esc_attr($extra_fields['is_price']);
    }elseif(isset($cf['defaults']['extra_fields']['is_price']) && $cf['defaults']['extra_fields']['is_price']){
    $value = esc_attr($cf['defaults']['extra_fields']['is_price']);
    }

    $show_price_extra = ($value==1) ? 1 : 0;

    $show_price = (isset($field_info->data_type) && ($field_info->data_type=='INT' && $field_info->data_type=='FLOAT')) ? 1 : 0;
    ?>
    <li class="gdcf-price-extra-set" <?php if(!$show_price){ echo "style='display:none;'";}?>>
        <label for="is_price" class="gd-cf-tooltip-wrap">
            <i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Display as price? :', 'geodirectory'); ?>
            <div class="gdcf-tooltip">
                <?php _e('Select if this field should be displayed as a price value. ', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap gd-switch">

            <input type="radio" id="is_price_yes<?php echo $radio_id;?>" name="extra[is_price]" class="gdri-enabled"  value="1"
                <?php if ($value == '1') {
                    echo 'checked';
                } ?>/>
            <label onclick="show_hide_radio(this,'show','gdcf-price-extra');" for="is_price_yes<?php echo $radio_id;?>" class="gdcb-enable"><span><?php _e('Yes', 'geodirectory'); ?></span></label>

            <input type="radio" id="is_price_no<?php echo $radio_id;?>" name="extra[is_price]" class="gdri-disabled" value="0"
                <?php if ($value == '0' || !$value) {
                    echo 'checked';
                } ?>/>
            <label onclick="show_hide_radio(this,'hide','gdcf-price-extra');" for="is_price_no<?php echo $radio_id;?>" class="gdcb-disable"><span><?php _e('No', 'geodirectory'); ?></span></label>

        </div>
    </li>

    <?php

    $value = '';
    if ($extra_fields && isset($extra_fields['thousand_separator'])) {
        $value = esc_attr($extra_fields['thousand_separator']);
    }elseif(isset($cf['defaults']['extra_fields']['thousand_separator']) && $cf['defaults']['extra_fields']['thousand_separator']){
        $value = esc_attr($cf['defaults']['extra_fields']['thousand_separator']);
    }
    ?>
    <li class="gdcf-price-extra" <?php if(!$show_price_extra){ echo "style='display:none;'";}?>>
        <label for="thousand_separator" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Thousand separator :', 'geodirectory');?>
            <div class="gdcf-tooltip">
                <?php _e('Select the thousand separator.', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
                <select name="extra[thousand_separator]" id="thousand_separator">
                    <option value="comma" <?php selected(true, $value == 'comma');?>><?php _e(', (comma)', 'geodirectory'); ?></option>
                    <option value="slash" <?php selected(true, $value == "slash");?>><?php _e('\ (slash)', 'geodirectory'); ?></option>
                    <option value="period" <?php selected(true, $value == 'period');?>><?php _e('. (period)', 'geodirectory'); ?></option>
                    <option value="space" <?php selected(true, $value == 'space');?>><?php _e(' (space)', 'geodirectory'); ?></option>
                    <option value="none" <?php selected(true, $value == 'none');?>><?php _e('(none)', 'geodirectory'); ?></option>
                </select>
        </div>
    </li>


    <?php

    $value = '';
    if ($extra_fields && isset($extra_fields['decimal_separator'])) {
        $value = esc_attr($extra_fields['decimal_separator']);
    }elseif(isset($cf['defaults']['extra_fields']['decimal_separator']) && $cf['defaults']['extra_fields']['decimal_separator']){
        $value = esc_attr($cf['defaults']['extra_fields']['decimal_separator']);
    }
    ?>
    <li class="gdcf-price-extra" <?php if(!$show_price_extra){ echo "style='display:none;'";}?>>
        <label for="decimal_separator" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Decimal separator :', 'geodirectory');?>
            <div class="gdcf-tooltip">
                <?php _e('Select the decimal separator.', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <select name="extra[decimal_separator]" id="decimal_separator">
                <option value="period" <?php selected(true, $value == 'period');?>><?php _e('. (period)', 'geodirectory'); ?></option>
                <option value="comma" <?php selected(true, $value == "comma");?>><?php _e(', (comma)', 'geodirectory'); ?></option>
            </select>
        </div>
    </li>

    <?php

    $value = '';
    if ($extra_fields && isset($extra_fields['decimal_display'])) {
        $value = esc_attr($extra_fields['decimal_display']);
    }elseif(isset($cf['defaults']['extra_fields']['decimal_display']) && $cf['defaults']['extra_fields']['decimal_display']){
        $value = esc_attr($cf['defaults']['extra_fields']['decimal_display']);
    }
    ?>
    <li class="gdcf-price-extra" <?php if(!$show_price_extra){ echo "style='display:none;'";}?>>
        <label for="decimal_display" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Decimal display :', 'geodirectory');?>
            <div class="gdcf-tooltip">
                <?php _e('Select how the decimal is displayed', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <select name="extra[decimal_display]" id="decimal_display">
                <option value="if" <?php selected(true, $value == 'if');?>><?php _e('If used (not .00)', 'geodirectory'); ?></option>
                <option value="allways" <?php selected(true, $value == "allways");?>><?php _e('Always (.00)', 'geodirectory'); ?></option>
            </select>
        </div>
    </li>

    <?php

    $value = '';
    if ($extra_fields && isset($extra_fields['currency_symbol'])) {
        $value = esc_attr($extra_fields['currency_symbol']);
    }elseif(isset($cf['defaults']['extra_fields']['currency_symbol']) && $cf['defaults']['extra_fields']['currency_symbol']){
        $value = esc_attr($cf['defaults']['extra_fields']['currency_symbol']);
    }
    ?>
    <li class="gdcf-price-extra" <?php if(!$show_price_extra){ echo "style='display:none;'";}?>>
        <label for="currency_symbol" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Currency symbol :', 'geodirectory');?>
            <div class="gdcf-tooltip">
                <?php _e('Select the currency symbol.', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <input type="text" name="extra[currency_symbol]" id="currency_symbol"
                   value="<?php echo esc_attr($value); ?>"/>
        </div>
    </li>

    <?php

    $value = '';
    if ($extra_fields && isset($extra_fields['currency_symbol_placement'])) {
        $value = esc_attr($extra_fields['currency_symbol_placement']);
    }elseif(isset($cf['defaults']['extra_fields']['currency_symbol_placement']) && $cf['defaults']['extra_fields']['currency_symbol_placement']){
        $value = esc_attr($cf['defaults']['extra_fields']['currency_symbol_placement']);
    }
    ?>
    <li class="gdcf-price-extra" <?php if(!$show_price_extra){ echo "style='display:none;'";}?>>
        <label for="currency_symbol_placement" class="gd-cf-tooltip-wrap"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php _e('Currency symbol placement :', 'geodirectory');?>
            <div class="gdcf-tooltip">
                <?php _e('Select the currency symbol placement.', 'geodirectory'); ?>
            </div>
        </label>
        <div class="gd-cf-input-wrap">
            <select name="extra[currency_symbol_placement]" id="currency_symbol_placement">
                <option value="left" <?php selected(true, $value == 'left');?>><?php _e('Left', 'geodirectory'); ?></option>
                <option value="right" <?php selected(true, $value == "right");?>><?php _e('Right', 'geodirectory'); ?></option>
            </select>
        </div>
    </li>


    <?php

    $html = ob_get_clean();
    return $output.$html;
}
add_filter('geodir_cfa_extra_fields_text','geodir_cfa_extra_fields_text',10,4);

function geodir_default_custom_fields($post_type='gd_place',$package_id=''){
    $fields = array();
    $package = ($package_id=='') ? '' : array($package_id);

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'taxonomy',
                      'admin_title' => __('Category', 'geodirectory'),
                      'admin_desc' => __('SELECT listing category FROM here. SELECT at least one CATEGORY', 'geodirectory'),
                      'site_title' => __('Category', 'geodirectory'),
                      'htmlvar_name' => $post_type.'category',
                      'default_value' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'is_required' => '1',
                      'show_in'   =>  '[detail]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Category', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'address',
                      'admin_title' => __('Address', 'geodirectory'),
                      'admin_desc' => ADDRESS_MSG,
                      'site_title' => __('Address', 'geodirectory'),
                      'htmlvar_name' => 'post',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'is_required' => '1',
                      'show_in'   =>  '[detail],[mapbubble]',
                      'show_on_pkg' => $package,
                      'required_msg' => __('Address fields are required', 'geodirectory'),
                      'clabels' => __('Address', 'geodirectory'),
                      'extra' => array('show_city' => 1, 'city_lable' => __('City', 'geodirectory'),
                                       'show_region' => 1, 'region_lable' => __('Region', 'geodirectory'),
                                       'show_country' => 1, 'country_lable' => __('Country', 'geodirectory'),
                                       'show_zip' => 1, 'zip_lable' => __('Zip/Post Code', 'geodirectory'),
                                       'show_map' => 1, 'map_lable' => __('Set Address On Map', 'geodirectory'),
                                       'show_mapview' => 1, 'mapview_lable' => __('Select Map View', 'geodirectory'),
                                       'show_mapzoom' => 1, 'mapzoom_lable' => 'hidden',
                                       'show_latlng' => 1));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'text',
                      'admin_title' => __('Time', 'geodirectory'),
                      'admin_desc' => __('Enter Business or Listing Timing Information.<br/>eg. : 10.00 am to 6 pm every day', 'geodirectory'),
                      'site_title' => __('Time', 'geodirectory'),
                      'htmlvar_name' => 'timing',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'show_in' =>  '[detail],[mapbubble]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Time', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'phone',
                      'admin_title' => __('Phone', 'geodirectory'),
                      'admin_desc' => __('You can enter phone number,cell phone number etc.', 'geodirectory'),
                      'site_title' => __('Phone', 'geodirectory'),
                      'htmlvar_name' => 'contact',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'show_in' =>  '[detail],[mapbubble]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Phone', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'email',
                      'admin_title' => __('Email', 'geodirectory'),
                      'admin_desc' => __('You can enter your business or listing email.', 'geodirectory'),
                      'site_title' => __('Email', 'geodirectory'),
                      'htmlvar_name' => 'email',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'show_in' => '[detail]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Email', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'url',
                      'admin_title' => __('Website', 'geodirectory'),
                      'admin_desc' => __('You can enter your business or listing website.', 'geodirectory'),
                      'site_title' => __('Website', 'geodirectory'),
                      'htmlvar_name' => 'website',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'show_in' => '[detail]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Website', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'url',
                      'admin_title' => __('Twitter', 'geodirectory'),
                      'admin_desc' => __('You can enter your business or listing twitter url.', 'geodirectory'),
                      'site_title' => __('Twitter', 'geodirectory'),
                      'htmlvar_name' => 'twitter',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'show_in' => '[detail]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Twitter', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'VARCHAR',
                      'field_type' => 'url',
                      'admin_title' => __('Facebook', 'geodirectory'),
                      'admin_desc' => __('You can enter your business or listing facebook url.', 'geodirectory'),
                      'site_title' => __('Facebook', 'geodirectory'),
                      'htmlvar_name' => 'facebook',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '1',
                      'is_admin' => '1',
                      'show_in' => '[detail]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Facebook', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'TEXT',
                      'field_type' => 'textarea',
                      'admin_title' => __('Video', 'geodirectory'),
                      'admin_desc' => __('Add video code here, YouTube etc.', 'geodirectory'),
                      'site_title' => __('Video', 'geodirectory'),
                      'htmlvar_name' => 'video',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '0',
                      'is_admin' => '1',
                      'show_in' => '[owntab]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Video', 'geodirectory'));

    $fields[] = array('listing_type' => $post_type,
                      'data_type' => 'TEXT',
                      'field_type' => 'textarea',
                      'admin_title' => __('Special Offers', 'geodirectory'),
                      'admin_desc' => __('Note: List out any special offers (optional)', 'geodirectory'),
                      'site_title' => __('Special Offers', 'geodirectory'),
                      'htmlvar_name' => 'special_offers',
                      'default_value' => '',
                      'option_values' => '',
                      'is_default' => '0',
                      'is_admin' => '1',
                      'show_in' => '[owntab]',
                      'show_on_pkg' => $package,
                      'clabels' => __('Special Offers', 'geodirectory'));

    /**
     * Filter the array of default custom fields DB table data.
     *
     * @since 1.6.6
     * @param string $fields The default custom fields as an array.
     */
    $fields = apply_filters('geodir_default_custom_fields', $fields);

    return  $fields;
}

function geodir_currency_format_number($number='',$cf=''){

    $cs = isset($cf['extra_fields']) ? maybe_unserialize($cf['extra_fields']) : '';

    $symbol = isset($cs['currency_symbol']) ? $cs['currency_symbol'] : '$';
    $decimals = isset($cf['decimal_point']) && $cf['decimal_point'] ? $cf['decimal_point'] : 2;
    $decimal_display = !empty($cf['decimal_display']) ? $cf['decimal_display'] : (!empty($cs['decimal_display']) ? $cs['decimal_display'] : 'if');
    $decimalpoint = '.';

    if(isset($cs['decimal_separator']) && $cs['decimal_separator']=='comma'){
        $decimalpoint = ',';
    }

    $separator = ',';

    if(isset($cs['thousand_separator'])){
        if($cs['thousand_separator']=='comma'){$separator = ',';}
        if($cs['thousand_separator']=='slash'){$separator = '\\';}
        if($cs['thousand_separator']=='period'){$separator = '.';}
        if($cs['thousand_separator']=='space'){$separator = ' ';}
        if($cs['thousand_separator']=='none'){$separator = '';}
    }

    $currency_symbol_placement = isset($cs['currency_symbol_placement']) ? $cs['currency_symbol_placement'] : 'left';

    if($decimals>0 && $decimal_display=='if'){
        if(is_int($number) || floor( $number ) == $number)
            $decimals = 0;
    }

    $number = number_format($number,$decimals,$decimalpoint,$separator);



    if($currency_symbol_placement=='left'){
        $number = $symbol . $number;
    }else{
        $number = $number . $symbol;
    }


   return $number;
}