<?php
/**
 * GeoDirectory cpt categories widget.
 *
 * @package GeoDirectory
 * @since 1.5.4
 */

/**
 * GeoDirectory CPT categories widget class.
 *
 * @since 1.5.4
 */
class geodir_cpt_categories_widget extends WP_Widget {

    /**
     * Register the cpt categories with WordPress.
     *
     * @since 1.5.4
     */
    public function __construct() {
        $widget_ops = array('classname' => 'geodir_cpt_categories_widget', 'description' => __('A list of GeoDirectory CPT categories.', 'geodirectory'));
        parent::__construct('geodir_cpt_categories_widget', __('GD > CPT Categories', 'geodirectory'), $widget_ops);
    }

    /**
     * Front-end display content for cpt categories widget.
     *
     * @since 1.5.4
     * @since 1.6.6 New parameters $no_cpt_filter &no_cat_filter added.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $params = array();
        /**
         * Filter the widget title.
         *
         * @since 1.5.4
         *
         * @param string $title The widget title. Default empty.
         * @param array  $instance An array of the widget's settings.
         * @param mixed  $id_base The widget ID.
         */
        $params['title'] = apply_filters('geodir_cpt_categories_widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        /**
         * Filter the widget setting post type.
         *
         * @since 1.5.4
         *
         * @param array $post_type The post types to display categories.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['post_type'] = apply_filters('geodir_cpt_categories_widget_post_type', empty($instance['post_type']) ? array() : $instance['post_type'], $instance, $this->id_base);

        /**
         * Filter the widget setting to hide empty categories.
         *
         * @since 1.5.4
         *
         * @param bool  $hide_empty If true then empty category will be not displayed.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['hide_empty'] = apply_filters('geodir_cpt_categories_widget_hide_empty', empty($instance['hide_empty']) ? 0 : 1, $instance, $this->id_base);

        /**
         * Filter the widget setting to show/hide category count.
         *
         * @since 1.5.4
         *
         * @param bool  $show_count If true then category count will be displayed.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['show_count'] = apply_filters('geodir_cpt_categories_widget_show_count', empty($instance['show_count']) ? 0 : 1, $instance, $this->id_base);

        /**
         * Filter the widget setting to show/hide category icon.
         *
         * @since 1.5.4
         *
         * @param bool  $hide_icon If true then category icon will be not displayed.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['hide_icon'] = apply_filters('geodir_cpt_categories_widget_hide_icon', empty($instance['hide_icon']) ? 0 : 1, $instance, $this->id_base);

        /**
         * Filter the widget setting to show CPT inline or not.
         *
         * @since 1.5.4
         *
         * @param bool  $cpt_left If true then CPT will be displayed inline.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['cpt_left'] = apply_filters('geodir_cpt_categories_widget_cpt_left', empty($instance['cpt_left']) ? 0 : 1, $instance, $this->id_base);

        /**
         * Filter the widget categories sorting order settings.
         *
         * @since 1.5.4
         *
         * @param string $max_count Widget max no of sub-categories count. Default 'count'.
         * @param array       $instance An array of the widget's settings.
         * @param mixed       $id_base The widget ID.
         */
        $params['sort_by'] = apply_filters('geodir_cpt_categories_widget_sort_by', isset($instance['sort_by']) && in_array($instance['sort_by'], array('az', 'count')) ? $instance['sort_by'] : 'count', $instance, $this->id_base);

        /**
         * Filter the widget max no of sub-categories count.
         *
         * @since 1.5.4
         *
         * @param bool|string $max_count Widget max no of sub-categories count.
         * @param array       $instance An array of the widget's settings.
         * @param mixed       $id_base The widget ID.
         */
        $params['max_count'] = apply_filters('geodir_cpt_categories_widget_max_count', !isset($instance['max_count']) ? 'all' : strip_tags($instance['max_count']), $instance, $this->id_base);

        /**
         * Filter the widget max sub-categories depth.
         *
         * @since 1.5.4
         *
         * @param bool|string $max_level Widget max sub-categories depth.
         * @param array       $instance An array of the widget's settings.
         * @param mixed       $id_base The widget ID.
         */
        $params['max_level'] = apply_filters('geodir_cpt_categories_widget_max_level', !isset($instance['max_level']) ? 'all' : strip_tags($instance['max_level']), $instance, $this->id_base);
        
        /**
         * Filter the widget setting to disable filter current viewing post type.
         *
         * @since 1.6.6
         *
         * @param bool  $no_cpt_filter If true then it doesn't filter current viewing post type.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['no_cpt_filter'] = apply_filters('geodir_cpt_categories_widget_no_cpt_filter', empty($instance['no_cpt_filter']) ? 0 : 1, $instance, $this->id_base);
        
        /**
         * Filter the widget setting to disable current viewing category.
         *
         * @since 1.6.6
         *
         * @param bool  $no_cat_filter If true then it doesn't filter current viewing category.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params['no_cat_filter'] = apply_filters('geodir_cpt_categories_widget_no_cat_filter', empty($instance['no_cat_filter']) ? 0 : 1, $instance, $this->id_base);

        /**
         * Filter the widget parameters.
         *
         * @since 1.5.4
         *
         * @param array $params The widget parameters.
         * @param array $instance An array of the widget's settings.
         * @param mixed $id_base The widget ID.
         */
        $params = apply_filters('geodir_cpt_categories_widget_params', $params, $instance, $this->id_base);

        $output = geodir_cpt_categories_output($params);

        echo $args['before_widget'];
        if ( $params['title'] ) {
            echo '<div class="geodir_list_heading clearfix">';
            echo $args['before_title'] . $params['title'] . $args['after_title'];
            echo '</div>';
        }
        echo '<div class="gd-cptcats-widget">';
        echo $output;
        echo '</div>';
        echo $args['after_widget'];
    }

    /**
     * Sanitize cpt categories widget values as they are saved.
     *
     * @since 1.5.4
     * @since 1.6.6 New parameters $no_cpt_filter &no_cat_filter added.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $new_instance['post_type'] = is_array($new_instance['post_type']) && in_array('0', $new_instance['post_type']) ? array('0') : $new_instance['post_type'];
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['post_type'] = isset($new_instance['post_type']) ? $new_instance['post_type'] : array('0');
        $instance['hide_empty'] = !empty($new_instance['hide_empty']) ? 1 : 0;
        $instance['show_count'] = !empty($new_instance['show_count']) ? 1 : 0;
        $instance['hide_icon'] = !empty($new_instance['hide_icon']) ? 1 : 0;
        $instance['cpt_left'] = !empty($new_instance['cpt_left']) ? 1 : 0;
        $instance['sort_by'] = isset($new_instance['sort_by']) && in_array($new_instance['sort_by'], array('az', 'count')) ? $new_instance['sort_by'] : 'count';
        $instance['max_count'] = strip_tags($new_instance['max_count']);
        $instance['max_level'] = strip_tags($new_instance['max_level']);
        $instance['no_cpt_filter'] = !empty($new_instance['no_cpt_filter']) ? 1 : 0;
        $instance['no_cat_filter'] = !empty($new_instance['no_cat_filter']) ? 1 : 0;

        return $instance;
    }

    /**
     * Back-end cpt categories settings form.
     *
     * @since 1.5.4
     * @since 1.6.6 New parameters $no_cpt_filter &no_cat_filter added.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $instance = wp_parse_args( (array)$instance,
            array(
                'title' => '',
                'post_type' => array(), // NULL for all
                'hide_empty' => '',
                'show_count' => '',
                'hide_icon' => '',
                'cpt_left' => '',
                'sort_by' => 'count',
                'max_count' => 'all',
                'max_level' => '1',
                'no_cpt_filter' => '',
                'no_cat_filter' => '',
            )
        );

        $title = strip_tags($instance['title']);
        $post_type = $instance['post_type'];
        $hide_empty = !empty($instance['hide_empty']) ? true : false;
        $show_count = !empty($instance['show_count']) ? true : false;
        $hide_icon = !empty($instance['hide_icon']) ? true : false;
        $cpt_left = !empty($instance['cpt_left']) ? true : false;
        $max_count = strip_tags($instance['max_count']);
        $max_level = strip_tags($instance['max_level']);
        $sort_by = isset($instance['sort_by']) && in_array($instance['sort_by'], array('az', 'count')) ? $instance['sort_by'] : 'count';
        $no_cpt_filter = !empty($instance['no_cpt_filter']) ? true : false;
        $no_cat_filter = !empty($instance['no_cat_filter']) ? true : false;

        $post_type_options = geodir_get_posttypes('options');
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'geodirectory'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Select CPT:', 'geodirectory'); ?></label>
            <select name="<?php echo $this->get_field_name('post_type'); ?>[]" id="<?php echo $this->get_field_id('post_type'); ?>" class="widefat" multiple="multiple">
                <option value="0" <?php selected( (empty($post_type) || (is_array($post_type) && in_array('0', $post_type))), true ); ?>><?php _e('All', 'geodirectory'); ?></option>
                <?php foreach ($post_type_options as $name => $title) { ?>
                    <option value="<?php echo $name;?>" <?php selected( is_array($post_type) && in_array($name, $post_type), true ); ?>><?php echo $title; ?></option>
                <?php } ?>
            </select>
        </p>
        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>"<?php checked( $hide_empty ); ?> value="1" />
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide empty categories', 'geodirectory' ); ?></label><br />
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>"<?php checked( $show_count ); ?> value="1" />
            <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e( 'Show category count' ); ?></label> <small><?php _e( '( Enabling will slow down page loading for big directories. )', 'geodirectory' ); ?></small><br />
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_icon'); ?>" name="<?php echo $this->get_field_name('hide_icon'); ?>"<?php checked( $hide_icon ); ?> value="1" />
            <label for="<?php echo $this->get_field_id('hide_icon'); ?>"><?php _e( 'Hide category icon', 'geodirectory' ); ?></label><br />
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('cpt_left'); ?>" name="<?php echo $this->get_field_name('cpt_left'); ?>"<?php checked( $cpt_left ); ?> value="1" />
            <label for="<?php echo $this->get_field_id('cpt_left'); ?>"><?php _e( 'Show CPT on same line', 'geodirectory' ); ?></label>
        <p>
            <label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e('Sort by:', 'geodirectory'); ?></label>
            <select name="<?php echo $this->get_field_name('sort_by'); ?>" id="<?php echo $this->get_field_id('sort_by'); ?>" class="widefat">
                <option value="az" <?php selected( $sort_by, 'az' ); ?>><?php _e('A-Z', 'geodirectory'); ?></option>
                <option value="count" <?php selected( $sort_by, 'count' ); ?>><?php _e('Count', 'geodirectory'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('max_count'); ?>"><?php _e('Max no of sub-categories:', 'geodirectory'); ?></label>
            <select name="<?php echo $this->get_field_name('max_count'); ?>" id="<?php echo $this->get_field_id('max_count'); ?>" class="widefat">
                <option value="all" <?php selected( $max_count, 'all' ); ?>><?php _e('All', 'geodirectory'); ?></option>
                <?php for ($n = 10; $n >= 0; $n--) { ?>
                    <option value="<?php echo $n;?>" <?php selected( $max_count, $n ); ?>><?php echo $n; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('max_level'); ?>"><?php _e('Show max sub-categories depth:', 'geodirectory'); ?></label>
            <select name="<?php echo $this->get_field_name('max_level'); ?>" id="<?php echo $this->get_field_id('max_level'); ?>" class="widefat">
                <option value="all" <?php selected( $max_level, 'all' ); ?>><?php _e('All', 'geodirectory'); ?></option>
                <?php for ($n = 0; $n <= 10; $n++) { ?>
                    <option value="<?php echo $n;?>" <?php selected( $max_level, $n ); ?>><?php echo $n; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('no_cpt_filter'); ?>" name="<?php echo $this->get_field_name('no_cpt_filter'); ?>"<?php checked( $no_cpt_filter ); ?> value="1" />
            <label for="<?php echo $this->get_field_id('no_cpt_filter'); ?>"><?php _e( 'Don\'t filter for current viewing post type', 'geodirectory' ); ?></label>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('no_cat_filter'); ?>" name="<?php echo $this->get_field_name('no_cat_filter'); ?>"<?php checked( $no_cat_filter ); ?> value="1" />
            <label for="<?php echo $this->get_field_id('no_cat_filter'); ?>"><?php _e( 'Don\'t filter for current viewing category', 'geodirectory' ); ?></label>
        </p>
    <?php
    }
} // class geodir_cpt_categories_widget

register_widget('geodir_cpt_categories_widget');

/**
 * Get the cpt categories content.
 *
 * @since 1.5.4
 * @since 1.6.6 New parameters $no_cpt_filter &no_cat_filter added.
 *
 * @global object $post The post object.
 * @global bool $gd_use_query_vars If true then use query vars to get current location terms.
 *
 * @param array $params An array of cpt categories parameters.
 * @return string CPT categories content.
 */
function geodir_cpt_categories_output($params) {
    global $post, $gd_use_query_vars;
    
    $old_gd_use_query_vars = $gd_use_query_vars;
    
    $gd_use_query_vars = geodir_is_page('detail') ? true : false;
    
    $args = wp_parse_args((array)$params,
        array(
            'title' => '',
            'post_type' => array(), // NULL for all
            'hide_empty' => '',
            'show_count' => '',
            'hide_icon' => '',
            'cpt_left' => '',
            'sort_by' => 'count',
            'max_count' => 'all',
            'max_level' => '1',
            'no_cpt_filter' => '',
            'no_cat_filter' => '',
        )
    );

    $sort_by = isset($args['sort_by']) && in_array($args['sort_by'], array('az', 'count')) ? $args['sort_by'] : 'count';
    $cpt_filter = empty($args['no_cpt_filter']) ? true : false;
    $cat_filter = empty($args['no_cat_filter']) ? true : false;

    $gd_post_types = geodir_get_posttypes('array');

    $post_type_arr = !is_array($args['post_type']) ? explode(',', $args['post_type']) : $args['post_type'];
    $current_posttype = geodir_get_current_posttype();

    $is_listing = false;
    $is_detail = false;
    $is_category = false;
    $post_ID = 0;
    $is_listing_page = geodir_is_page('listing');
    $is_detail_page = geodir_is_page('detail');
    if ($is_listing_page || $is_detail_page) {
        $current_posttype = geodir_get_current_posttype();

        if ($current_posttype != '' && isset($gd_post_types[$current_posttype])) {
            if ($is_detail_page) {
                $is_detail = true;
                $post_ID = is_object($post) && !empty($post->ID) ? (int)$post->ID : 0;
            } else {
                $is_listing = true;
                if (is_tax()) { // category page
                    $current_term_id = get_queried_object_id();
                    $current_taxonomy = get_query_var('taxonomy');
                    $current_posttype = geodir_get_current_posttype();

                    if ($current_term_id && $current_posttype && get_query_var('taxonomy') == $current_posttype . 'category') {
                        $is_category = true;
                    }
                }
            }
        }
    }

    $parent_category = 0;
    if (($is_listing || $is_detail) && $cpt_filter) {
        $post_type_arr = array($current_posttype);
    }

    $post_types = array();
    if (!empty($post_type_arr)) {
        if (in_array('0', $post_type_arr)) {
            $post_types = $gd_post_types;
        } else {
            foreach ($post_type_arr as $cpt) {
                if (isset($gd_post_types[$cpt])) {
                    $post_types[$cpt] = $gd_post_types[$cpt];
                }
            }
        }
    }

    if (empty($post_type_arr)) {
        $post_types = $gd_post_types;
    }

    $hide_empty = !empty($args['hide_empty']) ? true : false;
    $max_count = strip_tags($args['max_count']);
    $all_childs = $max_count == 'all' ? true : false;
    $max_count = $max_count > 0 ? (int)$max_count : 0;
    $max_level = strip_tags($args['max_level']);
    $show_count = !empty($args['show_count']) ? true : false;
    $hide_icon = !empty($args['hide_icon']) ? true : false;
    $cpt_left = !empty($args['cpt_left']) ? true : false;

    if(!$cpt_left){
        $cpt_left = "gd-cpt-flat";
    }else{
        $cpt_left = '';
    }

    $orderby = 'count';
    $order = 'DESC';
    if ($sort_by == 'az') {
        $orderby = 'name';
        $order = 'ASC';
    }

    $output = '';
    if (!empty($post_types)) {
        foreach ($post_types as $cpt => $cpt_info) {
            $parent_category = ($is_category && $cat_filter && $cpt == $current_posttype) ? $current_term_id : 0;
            $cat_taxonomy = $cpt . 'category';
            $skip_childs = false;
            if ($cat_filter && $cpt == $current_posttype && $is_detail && $post_ID) {
                $skip_childs = true;
                $categories = get_terms($cat_taxonomy, array('orderby' => $orderby, 'order' => $order, 'hide_empty' => $hide_empty, 'object_ids' => $post_ID));
            } else {
                $categories = get_terms($cat_taxonomy, array('orderby' => $orderby, 'order' => $order, 'hide_empty' => $hide_empty, 'parent' => $parent_category));
            }

            if ($hide_empty) {
                $categories = geodir_filter_empty_terms($categories);
            }
            if ($sort_by == 'count') {
                $categories = geodir_sort_terms($categories, 'count');
            }
            
            $categories = apply_filters('geodir_custom_sort_cpt_terms', $categories);

            if (!empty($categories)) {
                $term_icons = !$hide_icon ? geodir_get_term_icon() : array();
                $row_class = '';

                if ($is_listing) {
                    $row_class = $is_category ? ' gd-cptcat-categ' : ' gd-cptcat-listing';
                }
                $cpt_row = '<div class="gd-cptcat-row gd-cptcat-' . $cpt . $row_class . ' '.$cpt_left.'">';

                if ($is_category && $cat_filter && $cpt == $current_posttype) {
                    $term_info = get_term($current_term_id, $cat_taxonomy);

                    $term_icon_url = !empty($term_icons) && isset($term_icons[$term_info->term_id]) ? $term_icons[$term_info->term_id] : '';
                    $term_icon_url = $term_icon_url != '' ? '<img alt="' . esc_attr($term_info->name) . ' icon" src="' . $term_icon_url . '" /> ' : '';

                    $count = $show_count ? ' <span class="gd-cptcat-count">(' . $term_info->count . ')</span>' : '';
                    $cpt_row .= '<h2 class="gd-cptcat-title">' . $term_icon_url . $term_info->name . $count . '</h2>';
                } else {
                    $cpt_row .= '<h2 class="gd-cptcat-title">' . __($cpt_info['labels']['name'], 'geodirectory') . '</h2>';
                }
                foreach ($categories as $category) {
                    $term_icon_url = !empty($term_icons) && isset($term_icons[$category->term_id]) ? $term_icons[$category->term_id] : '';
                    $term_icon_url = $term_icon_url != '' ? '<img alt="' . esc_attr($category->name) . ' icon" src="' . $term_icon_url . '" /> ' : '';

                    $term_link = get_term_link( $category, $category->taxonomy );
                    /** Filter documented in geodirectory-functions/general_functions.php **/
                    $term_link = apply_filters( 'geodir_category_term_link', $term_link, $category->term_id, $cpt );

                    $cpt_row .= '<ul class="gd-cptcat-ul gd-cptcat-parent  '.$cpt_left.'">';
                    $cpt_row .= '<li class="gd-cptcat-li gd-cptcat-li-main">';
                    $count = $show_count ? ' <span class="gd-cptcat-count">(' . $category->count . ')</span>' : '';
                    $cpt_row .= '<h3 class="gd-cptcat-cat"><a href="' . esc_url($term_link) . '" title="' . esc_attr($category->name) . '">'  .$term_icon_url . $category->name . $count . '</a></h3>';
                    if (!$skip_childs && ($all_childs || $max_count > 0) && ($max_level == 'all' || (int)$max_level > 0)) {
                        $cpt_row .= geodir_cpt_categories_child_cats($category->term_id, $cpt, $hide_empty, $show_count, $sort_by, $max_count, $max_level, $term_icons);
                    }
                    $cpt_row .= '</li>';
                    $cpt_row .= '</ul>';
                }
                $cpt_row .= '</div>';

                $output .= $cpt_row;
            }
        }
    }
        
    $gd_use_query_vars = $old_gd_use_query_vars;
    
    return $output;
}

/**
 * Get the child categories content.
 *
 * @since 1.5.4
 *
 * @param int $parent_id Parent category id.
 * @param string $cpt The post type.
 * @param bool $hide_empty If true then filter the empty categories.
 * @param bool $show_count If true then category count will be displayed.
 * @param string $sort_by Sorting order for categories.
 * @param bool|string $max_count Max no of sub-categories count to display.
 * @param bool|string $max_level Max depth level sub-categories to display.
 * @param array $term_icons Array of terms icons url.
 * @param int $depth Category depth level. Default 1.
 * @return string Html content.
 */
function geodir_cpt_categories_child_cats($parent_id, $cpt, $hide_empty, $show_count, $sort_by, $max_count, $max_level, $term_icons, $depth = 1) {
    $cat_taxonomy = $cpt . 'category';

    $orderby = 'count';
    $order = 'DESC';
    if ($sort_by == 'az') {
        $orderby = 'name';
        $order = 'ASC';
    }

    if ($max_level != 'all' && $depth > (int)$max_level ) {
        return '';
    }

    $child_cats = get_terms($cat_taxonomy, array('orderby' => $orderby, 'order' => $order, 'hide_empty' => $hide_empty, 'parent' => $parent_id, 'number' => $max_count));
    if ($hide_empty) {
        $child_cats = geodir_filter_empty_terms($child_cats);
    }

    if (empty($child_cats)) {
        return '';
    }

    if ($sort_by == 'count') {
        $child_cats = geodir_sort_terms($child_cats, 'count');
    }

    $content = '<li class="gd-cptcat-li gd-cptcat-li-sub"><ul class="gd-cptcat-ul gd-cptcat-sub gd-cptcat-sub-' . $depth . '">';
    $depth++;
    foreach ($child_cats as $category) {
        $term_icon_url = !empty($term_icons) && isset($term_icons[$category->term_id]) ? $term_icons[$category->term_id] : '';
        $term_icon_url = $term_icon_url != '' ? '<img alt="' . esc_attr($category->name) . ' icon" src="' . $term_icon_url . '" /> ' : '';
        $term_link = get_term_link( $category, $category->taxonomy );
        /** Filter documented in geodirectory-functions/general_functions.php **/
        $term_link = apply_filters( 'geodir_category_term_link', $term_link, $category->term_id, $cpt );
        $count = $show_count ? ' <span class="gd-cptcat-count">(' . $category->count . ')</span>' : '';

        $content .= '<li class="gd-cptcat-li gd-cptcat-li-sub">';
        $content .= '<a href="' . esc_url($term_link) . '" title="' . esc_attr($category->name) . '">' . $term_icon_url . $category->name . $count . '</a></li>';
        $content .= geodir_cpt_categories_child_cats($category->term_id, $cpt, $hide_empty, $show_count, $sort_by, $max_count, $max_level, $term_icons, $depth);
    }
    $content .= '</li></ul>';

    return $content;
}
?>