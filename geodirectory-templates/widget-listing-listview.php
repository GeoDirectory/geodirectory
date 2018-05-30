<?php
/**
 * Template for the list of places
 *
 * This is used mostly by widgets that produce a list of listings and outputs the actual grid or list of listings.
 * See the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 * @global string $gridview_columns_widget The girdview style of the listings for widget.
 * @global object $gd_session GeoDirectory Session object.
 */

/** This action is documented in geodirectory-templates/listing-listview.php */
do_action('geodir_before_listing_listview');

global $gridview_columns_widget, $gd_session, $related_nearest, $related_parent_lat, $related_parent_lon;

/** This action is documented in geodirectory-templates/listing-listview.php */
$grid_view_class = apply_filters('geodir_grid_view_widget_columns', $gridview_columns_widget);
if ($gd_session->get('gd_listing_view') && !isset($before_widget)) {
    $grid_view_class = geodir_convert_listing_view_class($gd_session->get('gd_listing_view'));
}
?>
    <ul class="geodir_category_list_view clearfix <?php echo apply_filters('geodir_listing_listview_ul_extra_class', '', 'widget'); ?>">
        <?php
        if (!empty($widget_listings)) {
            /** This action is documented in geodirectory-templates/listing-listview.php */
            do_action('geodir_before_listing_post_listview');
            $all_postypes = geodir_get_posttypes();
            $geodir_days_new = (int)get_option('geodir_listing_new_days');
            foreach ($widget_listings as $widget_listing) {
                global $gd_widget_listing_type;
                $post = $widget_listing;

                $GLOBALS['post'] = $post;
                setup_postdata($post);

                $gd_widget_listing_type = $post->post_type;

                /** This action is documented in geodirectory-templates/listing-listview.php */
                $post_view_class = apply_filters('geodir_post_view_extra_class', '', $all_postypes);

                /** This action is documented in geodirectory-templates/listing-listview.php */
                $post_view_article_class = apply_filters('geodir_post_view_article_extra_class', '');
                ?>
                <li class="clearfix <?php if ($grid_view_class) {
                    echo 'geodir-gridview ' . $grid_view_class;
                } else {
                    echo ' geodir-listview ';
                } ?> <?php if ($post_view_class) {
                    echo $post_view_class;
                } ?>" <?php if (isset($listing_width) && $listing_width) {
                    echo "style='width:{$listing_width}%;'";
                }

                echo " data-post-id='$post->ID' ";
                /** This action is documented in geodirectory-templates/listing-listview.php */
                do_action('geodir_listview_inside_li', $post, 'widget');
                ?>>
                    <article class="geodir-category-listing <?php if ($post_view_article_class) {
                        echo $post_view_article_class;
                    } ?>">
                        <div class="geodir-post-img <?php echo apply_filters('geodir_listing_listview_thumb_extra_class', '', 'widget'); ?>">
                            <?php if ($fimage = geodir_show_featured_image($post->ID, 'list-thumb', true, false, $post->featured_image)) { ?>
                                <a href="<?php the_permalink(); ?>"><?php echo $fimage; ?></a>
                                <?php
                                /** This action is documented in geodirectory-templates/listing-listview.php */
                                do_action('geodir_before_badge_on_image', $post);
                                if ($post->is_featured) {
                                    echo geodir_show_badges_on_image('featured', $post, get_permalink());
                                }


                                if (round(abs(strtotime($post->post_date) - strtotime(date('Y-m-d'))) / 86400) < $geodir_days_new) {
                                    echo geodir_show_badges_on_image('new', $post, get_permalink());
                                }

                                /** This action is documented in geodirectory-templates/listing-listview.php */
                                do_action('geodir_after_badge_on_image', $post);
                            }
                            ?>
                        </div>
                        <div class="geodir-content <?php echo apply_filters('geodir_listing_listview_content_extra_class', '', 'widget'); ?>">
                            <?php
                            /** This action is documented in geodirectory-templates/listing-listview.php */
                            do_action('geodir_before_listing_post_title', 'listview', $post); ?>
                            <header class="geodir-entry-header">
                                <?php  $title_tag = !empty($title_tag) ? $title_tag : 'h3'; ?>
                                <<?php echo esc_attr($title_tag); ?> class="geodir-entry-title">
                                    <a href="<?php the_permalink(); ?>"
                                       title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                                </<?php echo esc_attr($title_tag); ?>>
                            </header>
                            <!-- .entry-header -->
                            <?php
                            /** This action is documented in geodirectory-templates/listing-listview.php */
                            do_action('geodir_after_listing_post_title', 'listview', $post); ?>
                            <?php /// Print Distance
                            if ( ( isset( $_REQUEST['sgeo_lat'] ) && $_REQUEST['sgeo_lat'] != '' ) || ( $related_nearest && $related_parent_lat ) ) {
                                if ( $related_nearest && $related_parent_lat ) {
                                    $startPoint = array('latitude' => $related_parent_lat, 'longitude' => $related_parent_lon);
                                } else {
                                    $startPoint = array('latitude' => $_REQUEST['sgeo_lat'], 'longitude' => $_REQUEST['sgeo_lon']);
                                }

                                $endLat = $post->post_latitude;
                                $endLon = $post->post_longitude;
                                $endPoint = array('latitude' => $endLat, 'longitude' => $endLon);
                                $uom = get_option('geodir_search_dist_1');
                                $distance = geodir_calculateDistanceFromLatLong($startPoint, $endPoint, $uom);
                                ?>
                                <h3>
                                    <?php
                                    if (round($distance, 2) == 0) {
                                        $uom = get_option('geodir_search_dist_2');
                                        $distance = geodir_calculateDistanceFromLatLong($startPoint, $endPoint, $uom);
                                        if ($uom == 'feet') {
                                            $uom = __('feet', 'geodirectory');
                                        } else {
                                            $uom = __('meters', 'geodirectory');
                                        }
                                        echo round($distance) . ' ' . __($uom, 'geodirectory') . '
			<br />
			';
                                    } else {
                                        if ($uom == 'miles') {
                                            $uom = __('miles', 'geodirectory');
                                        } else {
                                            $uom = __('km', 'geodirectory');
                                        }
                                        echo round($distance, 2) . ' ' . __($uom, 'geodirectory') . '
			<br />
			';
                                    }
                                    ?>
                                </h3>
                            <?php } ?>
                            <?php
                            /** This action is documented in geodirectory-templates/listing-listview.php */
                            do_action('geodir_before_listing_post_excerpt', $post); ?>
                            <?php echo geodir_show_listing_info('listing'); ?>
                            <?php if (isset($character_count) && $character_count == '0') {
                            } else { ?>
                                <div class="geodir-entry-content">
                                    <?php
                                    /**
                                     * Filter to hide the listing excerpt
                                     *
                                     * @since 1.5.3
                                     * @param bool $display Display the excerpt or not. Default true.
                                     * @param object $post The post object.
                                     */
                                    $show_listing_excerpt = apply_filters('geodir_show_listing_post_excerpt', true, 'widget', $post);
                                    if ($show_listing_excerpt) {
                                        if ( isset( $character_count ) && ( $character_count || $character_count == '0' ) ) {
                                            $content_out = geodir_max_excerpt( $character_count );
                                        } else {
                                            $content_out = get_the_excerpt();
                                        }
                                        if ( ! empty( $content_out ) ) {
                                            echo "<p>" . $content_out . "</p>";
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                            <?php
                            /** This action is documented in geodirectory-templates/listing-listview.php */
                            do_action('geodir_after_listing_post_excerpt', $post); ?>
                        </div>
                        <!-- gd-content ends here-->
                        <?php
                        /**
                         * Called after printing listing content.
                         *
                         * @since 1.5.3
                         * @param object $post The post object.
                         * @param string $view The view type, default 'widget'.
                         */
                        do_action( 'geodir_after_listing_content', $post, 'widget' ); ?>
                        <footer class="geodir-entry-meta <?php echo apply_filters('geodir_listing_listview_meta_extra_class', '', 'widget'); ?>">
                            <div class="geodir-addinfo clearfix <?php echo apply_filters('geodir_listing_listview_addinfo_extra_class', '', 'widget'); ?>">
                                <?php
                                /**
                                 * Called before printing review stars html.
                                 *
                                 * @since 1.5.3
                                 * @param object $post The post object.
                                 * @param string $view The view type, default 'widget'.
                                 */
                                do_action( 'geodir_before_review_html', $post, 'widget' );
                                $review_show = geodir_is_reviews_show('listview');
                                if ($review_show) {

                                    $post_avgratings = geodir_get_post_rating($post->ID);

                                    /** This action is documented in geodirectory-templates/listing-listview.php */
                                    do_action('geodir_before_review_rating_stars_on_listview', $post_avgratings, $post->ID);

                                    echo geodir_get_rating_stars($post_avgratings, $post->ID);

                                    /** This action is documented in geodirectory-templates/listing-listview.php */
                                    do_action('geodir_after_review_rating_stars_on_listview', $post_avgratings, $post->ID);
                                    ?><a href="<?php comments_link(); ?>" class="geodir-pcomments"><i
                                        class="fa fa-comments"></i> <?php geodir_comments_number($post->rating_count); ?>
                                    </a>
                                <?php
                                }


                                /**
                                 * Called after printing favorite html.
                                 *
                                 * @since 1.0.0
                                 */
                                do_action( 'geodir_after_favorite_html', $post->ID, 'widget' );

                                if ($post->post_author == get_current_user_id()) {
                                    $addplacelink = get_permalink(geodir_add_listing_page_id());
                                    $editlink = geodir_getlink($addplacelink, array('pid' => $post->ID), false);
                                    $upgradelink = geodir_getlink($editlink, array('upgrade' => '1'), false);

                                    $ajaxlink = geodir_get_ajax_url();
                                    $deletelink = geodir_getlink($ajaxlink, array('geodir_ajax' => 'add_listing', 'ajax_action' => 'delete', 'pid' => $post->ID), false);
                                    ?>
                                    <span class="geodir-authorlink clearfix">
                                <?php
                if (isset($_REQUEST['geodir_dashbord']) && $_REQUEST['geodir_dashbord']) {
                    /** This action is documented in geodirectory-templates/listing-listview.php */
                    do_action('geodir_before_edit_post_link_on_listing');
                    ?>
                    <a href="<?php echo esc_url($editlink); ?>" class="geodir-edit"
                       title="<?php _e('Edit Listing', 'geodirectory'); ?>">
                        <?php
                        $geodir_listing_edit_icon = apply_filters('geodir_listing_edit_icon', 'fa fa-edit');
                        echo '<i class="'. $geodir_listing_edit_icon .'"></i>';
                        ?>
                        <?php _e('Edit', 'geodirectory'); ?>
                    </a>
                    <a href="<?php echo esc_url($deletelink); ?>" class="geodir-delete"
                       title="<?php _e('Delete Listing', 'geodirectory'); ?>">
                        <?php
                        $geodir_listing_delete_icon = apply_filters('geodir_listing_delete_icon', 'fa fa-close');
                        echo '<i class="'. $geodir_listing_delete_icon .'"></i>';
                        ?>
                        <?php _e('Delete', 'geodirectory'); ?>
                    </a>
                    <?php
                    /** This action is documented in geodirectory-templates/listing-listview.php */
                    do_action('geodir_after_edit_post_link_on_listing');
                } ?>
					</span>
                                <?php } ?>
                            </div>
                            <!-- geodir-addinfo ends here-->
                        </footer>
                        <!-- .entry-meta -->
                    </article>
                </li>
                <?php
                unset($gd_widget_listing_type);
            }
            /** This action is documented in geodirectory-templates/listing-listview.php */
            do_action('geodir_after_listing_post_listview');
        } else {
			$favorite = isset($_REQUEST['list']) && $_REQUEST['list'] == 'favourite' ? true : false;
            
			/** This action is documented in geodirectory-templates/listing-listview.php */
            do_action('geodir_message_not_found_on_listing', 'widget-listing-listview', $favorite);
        }
        ?>
    </ul>  <!-- geodir_category_list_view ends here-->
    <div class="clear"></div>
<?php
/** This action is documented in geodirectory-templates/listing-listview.php */
do_action('geodir_after_listing_listview');
