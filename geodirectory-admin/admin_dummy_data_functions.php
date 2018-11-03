<?php
/**
 * GeoDirectory dummy data related functions.
 *
 * Functions for adding and removing dummy data.
 *
 * @since 1.6.11
 * @package GeoDirectory
 */

/**
 * Default taxonomies
 *
 * Adds the default terms for taxonomies - placecategory. Modify at your own risk.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $dummy_image_path The dummy image path.
 */
function geodir_dummy_data_taxonomies($post_type,$category_array) {
    global $wpdb, $dummy_image_path;



    $last_catid = '';

    $uploads = wp_upload_dir(); // Array of key => value pairs

    for ($i = 0; $i < count($category_array); $i++) {
        $parent_catid = 0;
        if (is_array($category_array[$i])) {
            $cat_name_arr = $category_array[$i];
            for ($j = 0; $j < count($cat_name_arr); $j++) {
                $catname = $cat_name_arr[$j];

                if (!term_exists($catname, $post_type.'category')) {
                    $last_catid = wp_insert_term($catname, $post_type.'category', $args = array('parent' => $parent_catid));

                    if ($j == 0) {
                        $parent_catid = $last_catid;
                    }


                    if (geodir_dummy_folder_exists())
                        $dummy_image_url = geodir_plugin_url() . "/geodirectory-admin/dummy/cat_icon";
                    else
                        $dummy_image_url = 'http://wpgeodirectory.com/dummy/cat_icon';

                    $dummy_image_url = apply_filters('place_dummy_cat_image_url', $dummy_image_url);

                    $catname = str_replace(' ', '_', $catname);
                    $uploaded = (array)fetch_remote_file("$dummy_image_url/" . $catname . ".png");

                    if (empty($uploaded['error'])) {
                        $new_path = $uploaded['file'];
                        $new_url = $uploaded['url'];
                    }

                    $wp_filetype = wp_check_filetype(basename($new_path), null);

                    $attachment = array(
                        'guid' => $uploads['baseurl'] . '/' . basename($new_path),
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($new_path)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $attach_id = wp_insert_attachment($attachment, $new_path);

                    // you must first include the image.php file
                    // for the function wp_generate_attachment_metadata() to work
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata($attach_id, $new_path);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    if (!geodir_get_tax_meta($last_catid['term_id'], 'ct_cat_icon', false, $post_type)) {
                        geodir_update_tax_meta($last_catid['term_id'], 'ct_cat_icon', array('id' => 'icon', 'src' => $new_url), $post_type);
                    }
                }
            }

        } else {
            $catname = $category_array[$i];

            if (!term_exists($catname, $post_type.'category')) {
                $last_catid = wp_insert_term($catname, $post_type.'category');

                if (geodir_dummy_folder_exists())
                    $dummy_image_url = geodir_plugin_url() . "/geodirectory-admin/dummy/cat_icon";
                else
                    $dummy_image_url = 'http://wpgeodirectory.com/dummy/cat_icon';

                $dummy_image_url = apply_filters('place_dummy_cat_image_url', $dummy_image_url);

                $catname = str_replace(' ', '_', $catname);
                $uploaded = (array)fetch_remote_file("$dummy_image_url/" . $catname . ".png");

                if (empty($uploaded['error'])) {
                    $new_path = $uploaded['file'];
                    $new_url = $uploaded['url'];
                }

                $wp_filetype = wp_check_filetype(basename($new_path), null);

                $attachment = array(
                    'guid' => $uploads['baseurl'] . '/' . basename($new_path),
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($new_path)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment, $new_path);


                // you must first include the image.php file
                // for the function wp_generate_attachment_metadata() to work
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $new_path);
                wp_update_attachment_metadata($attach_id, $attach_data);

                if (!geodir_get_tax_meta($last_catid['term_id'], 'ct_cat_icon', false, $post_type)) {
                    geodir_update_tax_meta($last_catid['term_id'], 'ct_cat_icon', array('id' => $attach_id, 'src' => $new_url), $post_type);
                }
            }
        }

    }
}


function geodir_dummy_data_types(){
    $data =  array(
        'standard_places' => array(
            'name'=>__('Default','geodirectory'),
            'count'=> 30
        ),
        'property_sale' => array(
            'name'=>__('Property for sale','geodirectory'),
            'count'=> 10
        ),
        'property_rent' => array(
            'name'=>__('Property for rent','geodirectory'),
            'count'=> 10
        )
    );

    return apply_filters('geodir_dummy_data_types',$data );
}


function geodir_create_dummy_fields($fields)
{
    
    /**
     * Filter the array of default custom fields DB table data.
     *
     * @since 1.0.0
     * @param string $fields The default custom fields as an array.
     */
    $fields = apply_filters('geodir_before_dummy_custom_fields_saved', $fields);
    foreach ($fields as $field_index => $field) {
        geodir_custom_field_save($field);

    }
}

/**
 * Deletes GeoDirectory dummy data.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 */
function geodir_delete_dummy_posts($post_type,$data_type)
{
    global $wpdb, $plugin_prefix;


    $post_ids = $wpdb->get_results("SELECT post_id FROM " . $plugin_prefix . $post_type."_detail WHERE post_dummy='1'");


    foreach ($post_ids as $post_ids_obj) {
        wp_delete_post($post_ids_obj->post_id);
    }

    //double check posts are deleted
    $wpdb->get_results("DELETE FROM " . $plugin_prefix . $post_type. "_detail WHERE post_dummy='1'");

    update_option($post_type.'_dummy_data_type','');
}

/**
 * Inserts GeoDirectory dummy posts.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global object $current_user Current user object.
 */
function geodir_insert_dummy_posts($post_type,$data_type,$item_index)
{

    @ini_set('max_execution_time', 999999); //300 seconds = 5 minutes
    $data_types = geodir_dummy_data_types();

    $total_count = 0;
    global $dummy_post_index;
    $dummy_post_index = $item_index;
    foreach( $data_types as $key=>$val){
        if($key==$data_type){
            $total_count = $val['count'];
            if($key=='standard_places'){
                /**
                 * Contains dummy post content.
                 *
                 * @since 1.0.0
                 * @package GeoDirectory
                 */
                include_once( 'dummy-data/standard_places.php' );
            }elseif($key=='property_sale'){
                /**
                 * Contains dummy property for sale post content.
                 *
                 * @since 1.6.11
                 * @package GeoDirectory
                 */
                include_once( 'dummy-data/property_sale.php' );
            }elseif($key=='property_rent'){
                /**
                 * Contains dummy property for sale post content.
                 *
                 * @since 1.6.11
                 * @package GeoDirectory
                 */
                include_once( 'dummy-data/property_rent.php' );
            }

        }

        do_action('geodir_insert_dummy_data_loop',$post_type,$data_type,$item_index);
    }



    // delete image cache on last entry
    if($total_count == $item_index){
        delete_transient( 'cached_dummy_images' );
        flush_rewrite_rules();
    }


}


if (!function_exists('geodir_autoinstall_admin_header') && (get_option('geodir_installed') || defined( 'GD_TESTING_MODE' ))) {
    /**
     * GeoDirectory dummy data installation.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $wpdb WordPress Database object.
     * @global string $plugin_prefix Geodirectory plugin table prefix.
     * @param string $post_type The post type.
     */
    function geodir_autoinstall_admin_header($post_type = 'gd_place')
    {

        global $wpdb, $plugin_prefix;

        if (!geodir_is_default_location_set()) {
            echo '<div class="updated fade"><p><strong>' . sprintf(__('Please %sclick here%s to set a default location, this will help to set location of all dummy data.', 'geodirectory'), '<a href=\'' . admin_url('admin.php?page=geodirectory&tab=default_location_settings') . '\'>', '</a>') . '</strong></p></div>';
        } else {

            ?>
            <span class="gd-dummy-hint"><small><?php _e('*Hint*: Installing our Advanced Search addon FIRST will add extra search fields to non-default data types.','geodirectory');?></small></span>
            <table class="form-table gd-dummy-table">
                <tbody>
                <tr>
                    <td><strong><?php _e('CPT','geodirectory');?></strong></td>
                    <td><strong><?php _e('Data Type','geodirectory');?></strong></td>
                    <td><strong><?php _e('Action','geodirectory');?></strong></td>
                </tr>

                <?php

                $cpts = geodir_get_posttypes('array');

                $data_types = geodir_dummy_data_types();

                $nonce = wp_create_nonce('geodir_dummy_posts_insert_noncename');

                foreach($cpts as $post_type=>$cpt){

                    $data_types_for = apply_filters('geodir_dummy_date_types_for',$data_types,$post_type);


                    $set_dt = get_option($post_type.'_dummy_data_type');

                    $count = 30;

                    geodir_add_column_if_not_exist($plugin_prefix . $post_type. "_detail", 'post_dummy', "enum( '1', '0' ) NULL DEFAULT '0'");

                    $post_counts = $wpdb->get_var("SELECT count(post_id) FROM " . $plugin_prefix . $post_type . "_detail WHERE post_dummy='1'");

                    echo "<tr>";
                    echo "<td><strong>".$cpt['labels']['name']."</strong></td>";


                    $select_disabled = $post_counts > 0 ? 'disabled' : '';
                    echo "<td>";
                    echo "<select id='".$post_type."_data_type' onchange='geodir_dummy_set_count(this,\"$post_type\");' $select_disabled>";

                    foreach($data_types_for as $key=>$val){
                        $selected = ($key==$set_dt) ? "selected='selected'" : '';
                        if($selected || count($data_types_for)==1){$count = $val['count'];}
                        echo "<option $selected value='$key' data-count='".$val['count']."'>".$val['name']."</option>";
                    }
                    echo "</select>";

                    $select_display = $post_counts > 0 ? 'display:none;' : '';
                    echo "<select id='".$post_type."_data_type_count' style='$select_display' >";
                    $x = 1;
                    while($x <= $count){
                        $selected = ($x==$count) ? "selected='selected'" : '';
                        echo "<option $selected value='$x'>".$x."</option>";
                        $x++;
                    }
                    echo "</select>";
                    echo "</td>";





                    if($post_counts > 0){
                        echo '<td><input type="button" value="'.__('Remove data','geodirectory').'" class="button-primary geodir_dummy_button gd-remove-data" onclick="gdInstallDummyData(this,\'' . $nonce . '\',\'' . $post_type . '\'); return false;" ></td>';
                    }else{
                        echo '<td><input type="button" value="'.__('Insert data','geodirectory').'" class="button-primary geodir_dummy_button" onclick="gdInstallDummyData(this,\'' . $nonce . '\',\'' . $post_type . '\'); return false;" ></td>';
                    }

                    echo "</tr>";
                    //print_r($cpt);
                }

                ?>
                </tbody>
            </table>
            <?php


            $default_location = geodir_get_default_location();
            $city = isset($default_location->city) ? $default_location->city : '';
            $region = isset($default_location->region) ? $default_location->region : '';
            $country = isset($default_location->country) ? $default_location->country : '';
            $city_latitude = isset($default_location->city_latitude) ? $default_location->city_latitude : '';
            $city_longitude = isset($default_location->city_longitude) ? $default_location->city_longitude : '';
            ?>
            <script type="text/javascript">

                function geodir_dummy_set_count(data,cpt){

                    var dateTypeCount = jQuery(data).find(':selected').data('count');

                    var optionsAsString = "";
                    for(var i = 0; i < dateTypeCount; i++) {
                        var v = i+1;
                        var selected = v==dateTypeCount ? 'selected' : '';
                        optionsAsString += "<option value='" + v + "' "+selected +">" + v + "</option>";
                    }
                    jQuery( '#'+cpt+'_data_type_count' ).empty().append( optionsAsString );

                }

                var CITY_ADDRESS = '<?php echo addslashes( $city . ',' . $region . ',' . $country );?>';
                var bound_lat_lng;
                var latlng = ['<?php echo $city_latitude; ?>', <?php echo $city_longitude; ?>];
                var lat = <?php echo $city_latitude; ?>;
                var lng = <?php echo $city_longitude; ?>;

                jQuery( document ).ready(function() {
                    var geocoder = window.gdMaps == 'google' ? new google.maps.Geocoder() : null;
                    if (window.gdMaps == 'google') {
                        console.log('gmaps');
                        latlng = new google.maps.LatLng(lat, lng);

                        geocoder.geocode({'address': CITY_ADDRESS},
                            function (results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    // Bounds for North America
                                    if (results[0].geometry.bounds == null) {
                                        bound_lat_lng1 = String(results[0].geometry.viewport.getSouthWest());
                                        bound_lat_lng1 = bound_lat_lng1.replace(/[()]/g, "");
                                        bound_lat_lng2 = String(results[0].geometry.viewport.getNorthEast());
                                        bound_lat_lng2 = bound_lat_lng2.replace(/[()]/g, "");
                                        bound_lat_lng2 = bound_lat_lng1 + "," + bound_lat_lng2;
                                        bound_lat_lng = bound_lat_lng2.split(',');
                                    } else {
                                        bound_lat_lng = String(results[0].geometry.bounds);
                                        bound_lat_lng = bound_lat_lng.replace(/[()]/g, "");
                                        bound_lat_lng = bound_lat_lng.split(',');
                                    }

                                    bound_lat_lng = bound_lat_lng.map(function (x) {
                                        return x.replace(" ", '');
                                    }); // remove spaces from lat/lon
                                } else {
                                    alert("<?php _e( 'Geocode was not successful for the following reason:', 'geodirectory' );?> " + status);
                                }
                            });
                    } else if (window.gdMaps == 'osm') {
                        console.log('osm');
                        latlng = L.latLng(lat, lng);

                        geocodePositionOSM(false, CITY_ADDRESS, false, false, function (geodata) {
                            if (typeof geodata == 'object' && geodata.boundingbox) {
                                bound_lat_lng = [geodata.boundingbox[0], geodata.boundingbox[2], geodata.boundingbox[1], geodata.boundingbox[3]];
                            } else {
                                geocodePositionOSM(latlng, false, false, false, function (geodata) {
                                    if (typeof geodata == 'object' && geodata.boundingbox) {
                                        bound_lat_lng = [geodata.boundingbox[0], geodata.boundingbox[2], geodata.boundingbox[1], geodata.boundingbox[3]];
                                    }
                                });
                            }
                        });
                    }
                });

                var dummy_post_index = 1;

                function gdRemoveDummyData(obj, nonce, posttype){
                    if (confirm('<?php _e('Are you sure you want to delete dummy data?' , 'geodirectory'); ?>')) {
                        jQuery(obj).prop('disabled', true);
                        jQuery('.gd-dummy-data-results-' + posttype).remove();
                        jQuery('<tr class="gd-dummy-data-results gd-dummy-data-results-' + posttype + '" >'+
                            '<td colspan="3">' +
                            '<div class="gd_progressbar_container_'+posttype+'">' +
                            '<div id="gd_progressbar" class="gd_progressbar_'+posttype+'">' +
                            '<div class="gd-progress-label"></div>' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '</tr>').insertAfter(jQuery(obj).parents('tr'));

                        jQuery('.gd_progressbar_'+posttype).progressbar({value: 0});

                        gd_progressbar('.gd_progressbar_container_'+posttype, 0, '<i class="fas fa-sync fa-spin"></i><?php echo esc_attr(__('Removing data...', 'geodirlocation'));?>');


                        jQuery.post('<?php echo geodir_get_ajax_url(); ?>&geodir_autofill=geodir_dummy_delete&posttype=' + posttype + '&_wpnonce=' + nonce,
                            function (data) {
                                gd_progressbar('.gd_progressbar_container_'+posttype, 100, '<i class="fas fa-check"></i><?php echo esc_attr(__('Complete!', 'geodirlocation'));?>');
                                jQuery(obj).removeClass('gd-remove-data');
                                jQuery(obj).val('<?php _e('Insert data','geodirectory');?>');
                                jQuery(obj).prop('disabled', false);
                                jQuery('#'+posttype+'_data_type_count').show();
                                jQuery('#'+posttype+'_data_type').prop('disabled', false);
                                geodir_dummy_set_count(jQuery('#'+posttype+'_data_type'),posttype);
                            });
                        return true;
                    }
                }


                function gdInstallDummyData(obj, nonce, posttype,insertedCount){

                    if(jQuery(obj).hasClass('gd-remove-data')){
                        gdRemoveDummyData(obj, nonce, posttype);
                        return;
                    }

                    jQuery(obj).prop('disabled', true);
                    jQuery('#'+posttype+'_data_type').prop('disabled', true);
                    jQuery('#'+posttype+'_data_type_count').hide();

                    if(!insertedCount){insertedCount = 0; jQuery('.gd-dummy-data-results-' + posttype).remove();}
                    var active_tab = jQuery(obj).closest('form').find('dl dd.gd-tab-active').attr('id');
                    var dateType = jQuery('#'+posttype+'_data_type').val();
                    //var dateTypeCount = jQuery('#'+posttype+'_data_type').find(':selected').data('count');
                    var dateTypeCount = jQuery('#'+posttype+'_data_type_count').val();

                    var result_container = jQuery('.gd-dummy-data-results-' + posttype);
                    if (!result_container.length) {

                        jQuery('<tr class="gd-dummy-data-results gd-dummy-data-results-' + posttype + '" >'+
                            '<td colspan="3">' +
                            '<div class="gd_progressbar_container_'+posttype+'">' +
                            '<div id="gd_progressbar" class="gd_progressbar_'+posttype+'">' +
                            '<div class="gd-progress-label"></div>' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '</tr>').insertAfter(jQuery(obj).parents('tr'));

                        jQuery('.gd_progressbar_'+posttype).progressbar({value: 0});

                        gd_progressbar('.gd_progressbar_container_'+posttype, 0, '0% (0 / ' + dateTypeCount + ') <i class="fas fa-sync fa-spin"></i><?php echo esc_attr(__('Creating categories and custom fields...', 'geodirlocation'));?>');
                    }

                    if (!(typeof bound_lat_lng == 'object' && bound_lat_lng.length == 4)) {
                        bound_lat_lng = ['<?php echo $city_latitude; ?>', <?php echo $city_longitude; ?>, '<?php echo $city_latitude; ?>', <?php echo $city_longitude; ?>];
                    }
               
                    var dummy_post_index = insertedCount;
                    dummy_post_index++;
                    var post_url = '<?php echo geodir_get_ajax_url(); ?>&geodir_autofill=geodir_dummy_insert&datatype='+dateType+'&posttype=' + posttype + '&insert_dummy_post_index=' + dummy_post_index + '&city_bound_lat1=' + bound_lat_lng[0] + '&city_bound_lng1=' + bound_lat_lng[1] + '&city_bound_lat2=' + bound_lat_lng[2] + '&city_bound_lng2=' + bound_lat_lng[3] + '&_wpnonce=' + nonce;

                    jQuery.post( post_url, function (data) {
                        var percentage = 0;

                        if (insertedCount < dateTypeCount){
                            insertedCount++;
                            var percentage = Math.round((insertedCount / dateTypeCount ) * 100);
                            percentage = percentage > 100 ? 100 : percentage;


                            gd_progressbar('.gd_progressbar_container_'+posttype, percentage, percentage + '% ('+insertedCount+' / ' + dateTypeCount + ') <i class="fas fa-sync fa-spin"></i><?php echo esc_attr(__('Inserting data...', 'geodirlocation'));?>');

                            gdInstallDummyData(obj, nonce, posttype,insertedCount);
                        }
                        else {
                            percentage = 100;
                            gd_progressbar('.gd_progressbar_container_'+posttype, percentage, percentage + '% ('+insertedCount+' / ' + dateTypeCount + ') <i class="fas fa-check"></i><?php echo esc_attr(__('Complete!', 'geodirlocation'));?>');
                            jQuery(obj).addClass('gd-remove-data');
                            jQuery(obj).val('<?php _e('Remove data','geodirectory');?>');
                            jQuery(obj).prop('disabled', false);

                        }
                    });

                }
            </script>
            <?php
        }
    }
}

