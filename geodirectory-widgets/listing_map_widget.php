<?php
/**
 * GeoDirectory GMap - Listing page Widget
 *
 * This will display Google map on listing page with use of Google Map Api V3.
 *
 * @since 1.0.0
 *
 * @package GeoDirectory
 */

/**
 * Enque listing map script.
 *
 * @since 1.0.0
 *
 * @global array $list_map_json Empty array.
 */
function init_listing_map_script()
{
    global $list_map_json;

    $list_map_json = array();

}

/**
 * Create listing json for map script.
 *
 * @since 1.0.0
 *
 * @global object $wpdb WordPress Database object.
 * @global array $list_map_json Listing map data in json format.
 * @global bool $add_post_in_marker_array Displays posts in marker array when the value is true.
 */
function create_list_jsondata($post)
{
    global $wpdb, $list_map_json, $add_post_in_marker_array;

    if ((is_main_query() || $add_post_in_marker_array) && isset($post->marker_json) && $post->marker_json != '') {
        /**
         * Filter the json data for search listing map.
         *
         * @since 1.5.7
         * @param string $post->marker_json JSON representation of the post marker info.
         * @param object $post The post object.
         */
        $list_map_json[] = apply_filters('geodir_create_list_jsondata',$post->marker_json,$post);
    }

}

/**
 * Send json data to script and show listing map.
 *
 * @since 1.0.0
 *
 * @global array $list_map_json Listing map data in json format.
 */
function show_listing_widget_map()
{
    global $list_map_json;

    if (!empty($list_map_json)) {
        $list_map_json = array_unique($list_map_json);
        $cat_content_info[] = implode(',', $list_map_json);
    }

    $totalcount = count(array_unique($list_map_json));


    if (!empty($cat_content_info)) {
        $json_content = substr(implode(',', $cat_content_info), 1);
        $json_content = htmlentities($json_content, ENT_QUOTES, get_option('blog_charset')); // Quotes in csv title import break maps - FIXED by kiran on 2nd March, 2016
        $json_content = wp_specialchars_decode($json_content); // Fixed #post-320722 on 2016-12-08
        $list_json = '[{"totalcount":"' . $totalcount . '",' . $json_content . ']';
    } else {
        $list_json = '[{"totalcount":"0"}]';
    }

    $listing_map_args = array('list_json' => $list_json);

    // Pass the json data in listing map script
    wp_localize_script('geodir-listing-map-widget', 'listing_map_args', $listing_map_args);

}

/**
 * GeoDirectory listing page map widget class.
 *
 * @since 1.0.0
 */
class geodir_map_listingpage extends WP_Widget
{

    /**
	 * Register the listing page map widget.
	 *
	 * @since 1.0.0
     * @since 1.5.1 Changed from PHP4 style constructors to PHP5 __construct.
	 */
    public function __construct() {
        $widget_ops = array('classname' => 'widget geodir-map-listing-page', 'description' => __('Google Map for Listing page. It will show you google map V3 for Listing page.', 'geodirectory'));
        parent::__construct(
            'geodir_map_v3_listing_map', // Base ID
            __('GD > GMap - Listing page', 'geodirectory'), // Name
            $widget_ops// Args
        );

        add_action('wp_head', 'init_listing_map_script'); // Initialize the map object and marker array

        add_action('the_post', 'create_list_jsondata'); // Add marker in json array

        add_action('wp_footer', 'show_listing_widget_map'); // Show map for listings with markers
    }

	/**
	 * Front-end display content for listing page map widget.
	 *
	 * @since 1.0.0
     * @since 1.5.1 Declare function public.
	 *
     * @global object $post The current post object.
     *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
    public function widget($args, $instance)
    {

        if (geodir_is_page('listing') || geodir_is_page('author') || geodir_is_page('search')
            || geodir_is_page('detail')
        ) :

            extract($args, EXTR_SKIP);
            /** This action is documented in geodirectory_shortcodes.php */
            $width = empty($instance['width']) ? '294' : apply_filters('widget_width', $instance['width']);
            /** This action is documented in geodirectory_shortcodes.php */
            $height = empty($instance['heigh']) ? '370' : apply_filters('widget_heigh', $instance['heigh']);
            /** This action is documented in geodirectory_shortcodes.php */
            $maptype = empty($instance['maptype']) ? 'ROADMAP' : apply_filters('widget_maptype', $instance['maptype']);
            /** This action is documented in geodirectory_shortcodes.php */
            $zoom = empty($instance['zoom']) ? '13' : apply_filters('widget_zoom', $instance['zoom']);
            /** This action is documented in geodirectory_shortcodes.php */
            $autozoom = empty($instance['autozoom']) ? '' : apply_filters('widget_autozoom', $instance['autozoom']);
            /**
             * Filter the listing map value widget_sticky, to set if the map should be sticky or not (scroll with page).
             *
             * @since 1.0.0
             * @param bool $sticky True if should be sticky, false if not
             */
            $sticky = empty($instance['sticky']) ? '' : apply_filters('widget_sticky', $instance['sticky']);
            /** This action is documented in geodirectory_shortcodes.php */
            $scrollwheel = empty($instance['scrollwheel']) ? '0' : apply_filters('widget_scrollwheel', $instance['scrollwheel']);
            $showall = empty($instance['showall']) ? '0' : apply_filters('widget_showall', $instance['showall']);
			
			/**
             * Filter the listing map should to be displayed or not.
             *
             * @since 1.4.6
			 *
             * @param bool $display true if map should be displayed, false if not.
             */
			$show_map = apply_filters( 'geodir_show_map_listing', $display = true );
			if ( !$show_map ) {
				return;
			}

            $map_args = array();
            $map_args['map_canvas_name'] = str_replace('-', '_', $args['widget_id']);
            $map_args['width'] = $width;
            $map_args['height'] = $height;

            $map_args['scrollwheel'] = $scrollwheel;
            $map_args['showall'] = $showall;
            $map_args['child_collapse'] = '0';
            $map_args['sticky'] = $sticky;
            $map_args['enable_cat_filters'] = false;
            $map_args['enable_text_search'] = false;
            $map_args['enable_post_type_filters'] = false;
            $map_args['enable_location_filters'] = false;
            $map_args['enable_jason_on_load'] = true;
			
            if (is_single()) {

                global $post;
                $map_default_lat = $address_latitude = $post->post_latitude;
                $map_default_lng = $address_longitude = $post->post_longitude;
                $mapview = $post->post_mapview;
                $mapzoom = $post->post_mapzoom;
                $map_args['map_class_name'] = 'geodir-map-listing-page-single';

            } else {
                $default_location = geodir_get_default_location();

                $map_default_lat = isset($default_location->city_latitude) ? $default_location->city_latitude : '';
                $map_default_lng = isset($default_location->city_longitude) ? $default_location->city_longitude : '';
                $map_args['map_class_name'] = 'geodir-map-listing-page';
                $mapview = $maptype;
            }

            if (empty($mapzoom)) $mapzoom = $zoom;

            // Set default map options
            $map_args['ajax_url'] = geodir_get_ajax_url();
            $map_args['latitude'] = $map_default_lat;
            $map_args['longitude'] = $map_default_lng;
            $map_args['zoom'] = $zoom;
            //$map_args['scrollwheel'] = true;
            $map_args['scrollwheel'] = $scrollwheel;
            $map_args['showall'] = $showall;
            $map_args['streetViewControl'] = true;
            $map_args['maptype'] = $maptype;
            $map_args['showPreview'] = '0';
            $map_args['maxZoom'] = 21;
            $map_args['autozoom'] = $autozoom;
            $map_args['bubble_size'] = 'small';
            
            $map_args['enable_marker_cluster'] = defined('GDCLUSTER_VERSION') && !empty($instance['marker_cluster']) ? true : false;

            // on near search change default location to searched place
            if(isset($_REQUEST['geodir_search']) && $_REQUEST['geodir_search']=='1'){
                if(isset($_REQUEST['sgeo_lat']) && isset($_REQUEST['sgeo_lon']) && is_float(floatval($_REQUEST['sgeo_lat'])) && is_float(floatval($_REQUEST['sgeo_lon'])) ){
                    $map_args['latitude'] = sanitize_text_field(floatval($_REQUEST['sgeo_lat']));
                    $map_args['longitude'] = sanitize_text_field(floatval($_REQUEST['sgeo_lon']));
                }
            }


            echo $before_widget;
            geodir_draw_map($map_args);
            echo $after_widget;

        endif;
    }

	/**
	 * Sanitize listing page map widget form values as they are saved.
	 *
	 * @since 1.0.0
     * @since 1.5.1 Declare function public.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
    public function update($new_instance, $old_instance) {
        //save the widget
        $instance = $old_instance;
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['heigh'] = ($new_instance['heigh']);
        $instance['maptype'] = ($new_instance['maptype']);
        $instance['zoom'] = ($new_instance['zoom']);
        $instance['autozoom'] = isset($new_instance['autozoom']) ? $new_instance['autozoom'] : '';
        $instance['sticky'] = isset($new_instance['sticky']) ? $new_instance['sticky'] : '';
        $instance['scrollwheel'] = isset($new_instance['scrollwheel']) ? ($new_instance['scrollwheel']) : '';
        $instance['showall'] = isset($new_instance['showall']) ? ($new_instance['showall']) : '';
        $instance['marker_cluster'] = defined('GDCLUSTER_VERSION') && !empty($new_instance['marker_cluster']) ? 1 : '';

        return $instance;
    }

	/**
	 * Back-end listing page map widget settings form.
	 *
	 * @since 1.0.0
     * @since 1.5.1 Declare function public.
	 *
	 * @param array $instance Previously saved values from database.
	 */
    public function form($instance) {
        // widget form in backend
        $instance = wp_parse_args((array)$instance, array('width' => '', 'heigh' => '', 'maptype' => '', 'zoom' => '', 'autozoom' => '', 'sticky' => '', 'scrollwheel' => '0', 'showall' => '0', 'marker_cluster' => '0'));
        
        $width = strip_tags($instance['width']);
        $heigh = strip_tags($instance['heigh']);
        $maptype = strip_tags($instance['maptype']);
        $zoom = strip_tags($instance['zoom']);
        $autozoom = strip_tags($instance['autozoom']);
        $sticky = strip_tags($instance['sticky']);
        $scrollwheel = strip_tags($instance['scrollwheel']);
        $showall = strip_tags($instance['showall']);
        $marker_cluster = (int)$instance['marker_cluster'];
        ?>
        <p>
            <label
                for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Map Width <small>(Default is : 294) you can use px or % here</small>', 'geodirectory'); ?>
                :
                <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>"
                       name="<?php echo $this->get_field_name('width'); ?>" type="text"
                       value="<?php echo esc_attr($width); ?>"/>
            </label>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('heigh'); ?>"><?php _e('Map Height <small>(Default is : 370) you can use px or vh here</small>', 'geodirectory'); ?>
                :
                <input class="widefat" id="<?php echo $this->get_field_id('heigh'); ?>"
                       name="<?php echo $this->get_field_name('heigh'); ?>" type="text"
                       value="<?php echo esc_attr($heigh); ?>"/>
            </label>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('maptype'); ?>"><?php _e(' Select Map View', 'geodirectory'); ?>
                :
                <select class="widefat" id="<?php echo $this->get_field_id('maptype'); ?>"
                        name="<?php echo $this->get_field_name('maptype'); ?>">

                    <option <?php if (isset($maptype) && $maptype == 'ROADMAP') {
                        echo 'selected="selected"';
                    } ?> value="ROADMAP"><?php _e('Road Map', 'geodirectory'); ?></option>
                    <option <?php if (isset($maptype) && $maptype == 'SATELLITE') {
                        echo 'selected="selected"';
                    } ?> value="SATELLITE"><?php _e('Satellite Map', 'geodirectory'); ?></option>
                    <option <?php if (isset($maptype) && $maptype == 'HYBRID') {
                        echo 'selected="selected"';
                    } ?> value="HYBRID"><?php _e('Hybrid Map', 'geodirectory'); ?></option>
					<option <?php selected($maptype, 'TERRAIN');?> 
							value="TERRAIN"><?php _e('Terrain Map', 'geodirectory'); ?></option>
                </select>
            </label>
        </p>

        <?php
        $map_zoom_level = geodir_map_zoom_level();
        ?>

        <p>
            <label
                for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Map Zoom level', 'geodirectory'); ?>
                :

                <select class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>"
                        name="<?php echo $this->get_field_name('zoom'); ?>"> <?php

                    foreach ($map_zoom_level as $level) {
                        $selected = '';
                        if ($level == $zoom)
                            $selected = 'selected="selected"';

                        echo '<option ' . $selected . ' value="' . $level . '">' . $level . '</option>';

                    } ?>

                </select>

            </label>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('autozoom'); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('autozoom'); ?>"
                       name="<?php echo $this->get_field_name('autozoom'); ?>"<?php if ($autozoom) {
                    echo 'checked="checked"';
                } ?> /> <?php _e('Map Auto Zoom ?', 'geodirectory'); ?></label>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('sticky'); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('sticky'); ?>"
                       name="<?php echo $this->get_field_name('sticky'); ?>"<?php if ($sticky) {
                    echo 'checked="checked"';
                } ?> /> <?php _e('Map Sticky(should stick to the right of screen) ?', 'geodirectory'); ?>
            </label>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('scrollwheel'); ?>">
                <input id="<?php echo $this->get_field_id('scrollwheel'); ?>"
                       name="<?php echo $this->get_field_name('scrollwheel'); ?>" type="checkbox" value="1"
                       <?php if ($scrollwheel){ ?>checked="checked" <?php } ?> /> <?php _e('Enable mouse scroll zoom ?', 'geodirectory'); ?>
            </label>
        </p>
        <?php if (defined('GDCLUSTER_VERSION')) { ?>
        <p>
            <label for="<?php echo $this->get_field_id('marker_cluster'); ?>"><input id="<?php echo $this->get_field_id('marker_cluster'); ?>" name="<?php echo $this->get_field_name('marker_cluster'); ?>" type="checkbox" value="1" <?php checked($marker_cluster, 1); ?> /> <?php _e('Enable marker cluster?', 'geodirectory'); ?>
            </label>
        </p>
        <?php } ?>

        <!-- <p>
      <label for="<?php echo $this->get_field_id('showall'); ?>"><?php _e('Show all listings on map? (not just page list)', 'geodirectory'); ?>:
      <input id="<?php echo $this->get_field_id('showall'); ?>" name="<?php echo $this->get_field_name('showall'); ?>" type="checkbox"  value="1"  <?php if ($showall) { ?>checked="checked" <?php } ?> />
      </label>
    </p> -->

    <?php
    }
} // class geodir_map_listingpage

register_widget('geodir_map_listingpage');
