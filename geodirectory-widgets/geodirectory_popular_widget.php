<?php

/**
 * Geodirectory popular posts category widget *
 **/
class geodir_popular_post_category extends WP_Widget {

	function geodir_popular_post_category() {
		//Constructor
		$widget_ops = array( 'classname'   => 'geodir_popular_post_category',
		                     'description' => __( 'GD > Popular Post Category', GEODIRECTORY_TEXTDOMAIN )
		);
		$this->WP_Widget( 'popular_post_category', __( 'GD > Popular Post Category', GEODIRECTORY_TEXTDOMAIN ), $widget_ops );


	}


	function widget( $args, $instance ) {
		global $wpdb, $plugin_prefix, $geodir_post_category_str;
		// prints the widget
		extract( $args, EXTR_SKIP );
		echo $before_widget;

		$title = empty( $instance['title'] ) ? __( 'Popular Categories', GEODIRECTORY_TEXTDOMAIN ) : apply_filters( 'widget_title', __( $instance['title'], GEODIRECTORY_TEXTDOMAIN ) );
		$list_sort = empty( $instance['list_sort'] ) ? 'count' : apply_filters( 'widget_list_sort', $instance['list_sort'] );
		$categ_limit = isset($instance['categ_limit']) && $instance['categ_limit'] > 0 ? (int)$instance['categ_limit'] : 15;

		$gd_post_type = geodir_get_current_posttype();
		$post_types = geodir_get_posttypes();

        $data = null;
        $data = apply_filters( 'geodir_get_term_count_array', $data );

        $output = '';
		$term_count = 0;

        $default_icon_url = geodir_plugin_url().'/geodirectory-functions/map-functions/icons/pin.png';

		foreach($post_types as $post_type) {

			if (is_single()) {
				if ($post_type != $gd_post_type) {
					continue;
				}
			}

			$taxonomy = geodir_get_taxonomies($post_type);
			$taxonomy = $taxonomy[0];

			$args = array(
				'order_by' => $list_sort,
				'order' => 'ASC',
				'pad_counts' => true,
                'hide_empty' => false,
			);

            if (is_tax()) {
                $taxonomy = get_query_var('taxonomy');
                $cur_term = get_query_var('term');
                $term_data = get_term_by('name', $cur_term, $taxonomy);
                $args['parent'] = $term_data->term_id;
                if ($post_type != $gd_post_type) {
                    continue;
                }
            }

            if (is_home()) {
                $args['parent'] = 0;
            }

			$terms = get_terms($taxonomy, $args);

			if (!empty($terms)) {
				$geodir_post_category_str = array();

				$sorted_terms = array();
				foreach ($terms as $term) {
					$term_count++;

					$taxonomy_obj = get_taxonomy($term->taxonomy);
					$post_type = $taxonomy_obj->object_type[0];

					$geodir_post_category_str[] = array('posttype' => $post_type, 'termid' => $term->term_id);

					$class_row = $term_count > $categ_limit ? 'geodir-pcat-hide geodir-hide' : 'geodir-pcat-show';
					$term_icon = get_tax_meta($term->term_id, 'ct_cat_icon', false, $post_type);
                    if($term_icon) {
                        $term_icon_url = $term_icon["src"];
                    } else {
                        $term_icon_url = $default_icon_url;
                    }
					$total_post = geodir_count_posts_by_term($data, $term);

					$html = '<li class="' . $class_row . '">';
					$html .= '<a href="' . get_term_link($term, $term->taxonomy) . '">';
                    $html .= '<img alt="term icon" class="" style="height:20px;vertical-align:middle;" src="' . $term_icon_url . '"/> ';
					$html .= ucwords($term->name) . ' (<span>' . $total_post . '</span>) ';
					$html .= '</a>';
                    $html .= '</li>';


					//this is needed since we are filtering by location
					if ($list_sort == 'count') {
						$sorted_terms[$term_count]['count'] = $total_post;
						$sorted_terms[$term_count]['html'] = $html;
					} else {
						$output .= $html;
					}

				}
				//this is needed since we are filtering by location
				if ($list_sort == 'count' && $sorted_terms) {
					usort($sorted_terms, 'geodir_sort_by_count');
					$sorted_terms = array_reverse($sorted_terms);
					foreach ($sorted_terms as $t) {
						$output .= $t['html'];
					}
				}
			}
		} ?>
		<?php if ($output) { ?>
			<div class="geodir-category-list-in clearfix">
				<div class="geodir-cat-list clearfix">
					<?php
					//$identifier = 'geodir-' . substr( md5( microtime() ), 0, 6 );
					echo $before_title . __( $title ) . $after_title;
					echo '<ul class="geodir-popular-cat-list">';
					echo $output;
					echo '</ul>';
					?>
				</div>
				<?php if ( $term_count > $categ_limit ) {
					echo '<a href="javascript:void(0)" class="geodir-morecat geodir-showcat">' . __( 'More Categories', GEODIRECTORY_TEXTDOMAIN ) . '</a>';
					echo '<a href="javascript:void(0)" class="geodir-morecat geodir-hidecat geodir-hide">' . __( 'Less Categories', GEODIRECTORY_TEXTDOMAIN ) . '</a>';
					/* add scripts */
					add_action( 'wp_footer', array( $this, 'geodir_popular_category_add_scripts' ), 100 );
				} ?>
			</div>
		<?php } ?>
		<?php
		echo $after_widget;
	}

	function geodir_popular_category_add_scripts() {
		?>
		<style>.geodir-hide {
				display: none
			}</style>
		<script type="text/javascript">
			jQuery(function ($) {
				$('.geodir-showcat').click(function () {
					var objCat = $(this).closest('.geodir-category-list-in');
					$(objCat).find('li.geodir-pcat-hide').removeClass('geodir-hide');
					$(objCat).find('a.geodir-showcat').addClass('geodir-hide');
					$(objCat).find('a.geodir-hidecat').removeClass('geodir-hide');
				});
				$('.geodir-hidecat').click(function () {
					var objCat = $(this).closest('.geodir-category-list-in');
					$(objCat).find('li.geodir-pcat-hide').addClass('geodir-hide');
					$(objCat).find('a.geodir-hidecat').addClass('geodir-hide');
					$(objCat).find('a.geodir-showcat').removeClass('geodir-hide');
				});
			});
		</script>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		//save the widget
		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['list_sort']       = strip_tags( $new_instance['list_sort'] );
		$categ_limit             = (int) $new_instance['categ_limit'];
		$instance['categ_limit'] = $categ_limit > 0 ? $categ_limit : 15;

		return $instance;
	}

	function form( $instance ) {
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'list_sort' => 'count', 'categ_limit' => 15 ) );

		$title       = strip_tags( $instance['title'] );
		$categ_limit = (int) $instance['categ_limit'];
		$categ_limit = $categ_limit > 0 ? $categ_limit : 15;
		$list_sort = strip_tags( $instance['list_sort'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', GEODIRECTORY_TEXTDOMAIN ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>"/>
			</label>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'list_sort' ); ?>"><?php _e( 'Sort by:', GEODIRECTORY_TEXTDOMAIN ); ?>

				<select class="widefat" id="<?php echo $this->get_field_id( 'list_sort' ); ?>"
				        name="<?php echo $this->get_field_name( 'list_sort' ); ?>">

					<option <?php if ( $list_sort == 'count' ) {
						echo 'selected="selected"';
					} ?> value="count"><?php _e( 'Most Listings', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'name' ) {
						echo 'selected="selected"';
					} ?> value="name"><?php _e( 'A-Z', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'none' ) {
						echo 'selected="selected"';
					} ?> value="id"><?php _e( 'ID', GEODIRECTORY_TEXTDOMAIN ); ?></option>

				</select>
			</label>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'categ_limit' ); ?>"><?php _e( 'Customize categories count to appear by default:', GEODIRECTORY_TEXTDOMAIN ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'categ_limit' ); ?>"
				       name="<?php echo $this->get_field_name( 'categ_limit' ); ?>" type="text"
				       value="<?php echo (int) esc_attr( $categ_limit ); ?>"/>

				<p class="description"
				   style="padding:0"><?php _e( 'After categories count reaches this limit option More Categories / Less Categoris will be displayed to show/hide categories. Default: 15', GEODIRECTORY_TEXTDOMAIN ); ?></p>
			</label>
		</p>
	<?php
	}
}

register_widget( 'geodir_popular_post_category' );


/**
 * Geodirectory popular posts widget *
 **/
class geodir_popular_postview extends WP_Widget {

	function geodir_popular_postview() {
		//Constructor
		$widget_ops = array( 'classname'   => 'geodir_popular_post_view',
		                     'description' => __( 'GD > Popular Post View', GEODIRECTORY_TEXTDOMAIN )
		);
		$this->WP_Widget( 'popular_post_view', __( 'GD > Popular Post View', GEODIRECTORY_TEXTDOMAIN ), $widget_ops );
	}

	function widget( $args, $instance ) {
		// prints the widget
		extract( $args, EXTR_SKIP );

		echo $before_widget;

		$title                 = empty( $instance['title'] ) ? ucwords( $instance['category_title'] ) : apply_filters( 'widget_title', __( $instance['title'], GEODIRECTORY_TEXTDOMAIN ) );
		$post_type             = empty( $instance['post_type'] ) ? 'gd_place' : apply_filters( 'widget_post_type', $instance['post_type'] );
		$category              = empty( $instance['category'] ) ? '0' : apply_filters( 'widget_category', $instance['category'] );
		$post_number           = empty( $instance['post_number'] ) ? '5' : apply_filters( 'widget_post_number', $instance['post_number'] );
		$layout                = empty( $instance['layout'] ) ? 'gridview_onehalf' : apply_filters( 'widget_layout', $instance['layout'] );
		$add_location_filter   = empty( $instance['add_location_filter'] ) ? '0' : apply_filters( 'widget_layout', $instance['add_location_filter'] );
		$listing_width         = empty( $instance['listing_width'] ) ? '' : apply_filters( 'widget_listing_width', $instance['listing_width'] );
		$list_sort             = empty( $instance['list_sort'] ) ? 'latest' : apply_filters( 'widget_list_sort', $instance['list_sort'] );
		$use_viewing_post_type = ! empty( $instance['use_viewing_post_type'] ) ? true : false;

		// set post type to current viewing post type
		if ( $use_viewing_post_type ) {
			$current_post_type = geodir_get_current_posttype();
			if ( $current_post_type != '' && $current_post_type != $post_type ) {
				$post_type = $current_post_type;
				$category  = array(); // old post type category will not work for current changed post type
			}
		}
		// replace widget title dynamically
		$posttype_plural_label   = __( get_post_type_plural_label( $post_type ), GEODIRECTORY_TEXTDOMAIN );
		$posttype_singular_label = __( get_post_type_singular_label( $post_type ), GEODIRECTORY_TEXTDOMAIN );

		$title = str_replace( "%posttype_plural_label%", $posttype_plural_label, $title );
		$title = str_replace( "%posttype_singular_label%", $posttype_singular_label, $title );

		if ( isset( $instance['character_count'] ) ) {
			$character_count = apply_filters( 'widget_list_character_count', $instance['character_count'] );
		} else {
			$character_count = '';
		}

		if ( empty( $title ) || $title == 'All' ) {
			$title .= ' ' . __( get_post_type_plural_label( $post_type ), GEODIRECTORY_TEXTDOMAIN );
		}

		$location_url = array();
		$city         = get_query_var( 'gd_city' );
		if ( ! empty( $city ) ) {
			if ( get_option( 'geodir_show_location_url' ) == 'all' ) {
				$country = get_query_var( 'gd_country' );
				$region  = get_query_var( 'gd_region' );

				if ( ! empty( $country ) ) {
					$location_url[] = $country;
				}

				if ( ! empty( $region ) ) {
					$location_url[] = $region;
				}
			}

			$location_url[] = $city;
		}

		$location_url = implode( "/", $location_url );

		if ( get_option( 'permalink_structure' ) ) {
			$viewall_url = get_post_type_archive_link( $post_type );
		} else {
			$viewall_url = get_post_type_archive_link( $post_type );
		}

		if ( ! empty( $category ) && $category[0] != '0' ) {
			global $geodir_add_location_url;

			$geodir_add_location_url = '0';

			if ( $add_location_filter != '0' ) {
				$geodir_add_location_url = '1';
			}

			$viewall_url = get_term_link( (int) $category[0], $post_type . 'category' );

			$geodir_add_location_url = null;
		}
		$query_args = array(
			'posts_per_page' => $post_number,
			'is_geodir_loop' => true,
			'gd_location'    => $add_location_filter ? true : false,
			'post_type'      => $post_type,
			'order_by'       => $list_sort
		);

		if ( $character_count ) {
			$query_args['excerpt_length'] = $character_count;
		}

		if ( ! empty( $instance['show_featured_only'] ) ) {
			$query_args['show_featured_only'] = 1;
		}

		if ( ! empty( $instance['show_special_only'] ) ) {
			$query_args['show_special_only'] = 1;
		}

		if ( ! empty( $instance['with_pics_only'] ) ) {
			$query_args['with_pics_only'] = 1;
		}

		if ( ! empty( $instance['with_videos_only'] ) ) {
			$query_args['with_videos_only'] = 1;
		}
		$with_no_results = ! empty( $instance['without_no_results'] ) ? false : true;

		if ( ! empty( $category ) && $category[0] != '0' ) {
			$category_taxonomy = geodir_get_taxonomies( $post_type );

			######### WPML #########
			if ( function_exists( 'icl_object_id' ) ) {
				$category = gd_lang_object_ids( $category, $category_taxonomy[0] );
			}
			######### WPML #########

			$tax_query = array(
				'taxonomy' => $category_taxonomy[0],
				'field'    => 'id',
				'terms'    => $category
			);

			$query_args['tax_query'] = array( $tax_query );
		}

		global $gridview_columns_widget, $geodir_is_widget_listing;

		$widget_listings = geodir_get_widget_listings( $query_args );

		if ( ! empty( $widget_listings ) || $with_no_results ) {
			?>
			<div class="geodir_locations geodir_location_listing">
				<?php do_action( 'geodir_before_view_all_link_in_widget' ); ?>
				<div class="geodir_list_heading clearfix">
					<?php echo $before_title . $title . $after_title; ?>
					<a href="<?php echo $viewall_url; ?>"
					   class="geodir-viewall"><?php _e( 'View all', GEODIRECTORY_TEXTDOMAIN ); ?></a>
				</div>
				<?php do_action( 'geodir_after_view_all_link_in_widget' ); ?>
				<?php
				if ( strstr( $layout, 'gridview' ) ) {
					$listing_view_exp        = explode( '_', $layout );
					$gridview_columns_widget = $layout;
					$layout                  = $listing_view_exp[0];
				} else {
					$gridview_columns_widget = '';
				}

				$template = apply_filters( "geodir_template_part-widget-listing-listview", geodir_plugin_path() . '/geodirectory-templates/widget-listing-listview.php' );
				if ( ! isset( $character_count ) ) {
					$character_count = $character_count == '' ? 50 : apply_filters( 'widget_character_count', $character_count );
				}

				global $post, $map_jason, $map_canvas_arr;

				$current_post             = $post;
				$current_map_jason        = $map_jason;
				$current_map_canvas_arr   = $map_canvas_arr;
				$geodir_is_widget_listing = true;

				include( $template );

				$geodir_is_widget_listing = false;

				$GLOBALS['post'] = $current_post;
				setup_postdata( $current_post );
				$map_jason      = $current_map_jason;
				$map_canvas_arr = $current_map_canvas_arr;
				?>
			</div>
		<?php
		}
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		//save the widget
		$instance = $old_instance;

		if ( $new_instance['title'] == '' ) {
			$title = ucwords( strip_tags( $new_instance['category_title'] ) );
			//$instance['title'] = $title;
		}
		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		//$instance['category'] = strip_tags($new_instance['category']);
		$instance['category']        = isset( $new_instance['category'] ) ? $new_instance['category'] : '';
		$instance['category_title']  = strip_tags( $new_instance['category_title'] );
		$instance['post_number']     = strip_tags( $new_instance['post_number'] );
		$instance['layout']          = strip_tags( $new_instance['layout'] );
		$instance['listing_width']   = strip_tags( $new_instance['listing_width'] );
		$instance['list_sort']       = strip_tags( $new_instance['list_sort'] );
		$instance['character_count'] = $new_instance['character_count'];
		if ( isset( $new_instance['add_location_filter'] ) && $new_instance['add_location_filter'] != '' ) {
			$instance['add_location_filter'] = strip_tags( $new_instance['add_location_filter'] );
		} else {
			$instance['add_location_filter'] = '0';
		}

		$instance['show_featured_only']    = isset( $new_instance['show_featured_only'] ) && $new_instance['show_featured_only'] ? 1 : 0;
		$instance['show_special_only']     = isset( $new_instance['show_special_only'] ) && $new_instance['show_special_only'] ? 1 : 0;
		$instance['with_pics_only']        = isset( $new_instance['with_pics_only'] ) && $new_instance['with_pics_only'] ? 1 : 0;
		$instance['with_videos_only']      = isset( $new_instance['with_videos_only'] ) && $new_instance['with_videos_only'] ? 1 : 0;
		$instance['use_viewing_post_type'] = isset( $new_instance['use_viewing_post_type'] ) && $new_instance['use_viewing_post_type'] ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance,
			array(
				'title'                 => '',
				'post_type'             => '',
				'category'              => array(),
				'category_title'        => '',
				'list_sort'             => '',
				'list_order'            => '',
				'post_number'           => '5',
				'layout'                => 'gridview_onehalf',
				'listing_width'         => '',
				'add_location_filter'   => '1',
				'character_count'       => '20',
				'show_featured_only'    => '',
				'show_special_only'     => '',
				'with_pics_only'        => '',
				'with_videos_only'      => '',
				'use_viewing_post_type' => ''
			)
		);

		$title = strip_tags( $instance['title'] );

		$post_type = strip_tags( $instance['post_type'] );

		$category = $instance['category'];

		$category_title = strip_tags( $instance['category_title'] );

		$list_sort = strip_tags( $instance['list_sort'] );

		$list_order = strip_tags( $instance['list_order'] );

		$post_number = strip_tags( $instance['post_number'] );

		$layout = strip_tags( $instance['layout'] );

		$listing_width = strip_tags( $instance['listing_width'] );

		$add_location_filter = strip_tags( $instance['add_location_filter'] );

		$character_count = $instance['character_count'];

		$show_featured_only    = isset( $instance['show_featured_only'] ) && $instance['show_featured_only'] ? true : false;
		$show_special_only     = isset( $instance['show_special_only'] ) && $instance['show_special_only'] ? true : false;
		$with_pics_only        = isset( $instance['with_pics_only'] ) && $instance['with_pics_only'] ? true : false;
		$with_videos_only      = isset( $instance['with_videos_only'] ) && $instance['with_videos_only'] ? true : false;
		$use_viewing_post_type = isset( $instance['use_viewing_post_type'] ) && $instance['use_viewing_post_type'] ? true : false;

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', GEODIRECTORY_TEXTDOMAIN ); ?>
				<small>(%posttype_singular_label% ,
					%posttype_plural_label% <?php _e( 'can be used', GEODIRECTORY_TEXTDOMAIN ); ?>)
				</small>

				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>"/>
			</label>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Type:', GEODIRECTORY_TEXTDOMAIN ); ?>

				<?php $postypes = geodir_get_posttypes();
				$postypes       = apply_filters( 'geodir_post_type_list_in_p_widget', $postypes ); ?>

				<select class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>"
				        name="<?php echo $this->get_field_name( 'post_type' ); ?>"
				        onchange="geodir_change_category_list(this)">

					<?php foreach ( $postypes as $postypes_obj ) { ?>

						<option <?php if ( $post_type == $postypes_obj ) {
							echo 'selected="selected"';
						} ?> value="<?php echo $postypes_obj; ?>"><?php $extvalue = explode( '_', $postypes_obj );
							echo ucfirst( $extvalue[1] ); ?></option>

					<?php } ?>

				</select>
			</label>
		</p>


		<p id="post_type_cats">
			<label
				for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Post Category:', GEODIRECTORY_TEXTDOMAIN ); ?>

				<?php

				$post_type = ( $post_type != '' ) ? $post_type : 'gd_place';

				$all_postypes = geodir_get_posttypes();

				if ( ! in_array( $post_type, $all_postypes ) ) {
					$post_type = 'gd_place';
				}

				$category_taxonomy = geodir_get_taxonomies( $post_type );
				$categories        = get_terms( $category_taxonomy, array( 'orderby' => 'count', 'order' => 'DESC' ) );

				?>

				<select multiple="multiple" class="widefat" name="<?php echo $this->get_field_name( 'category' ); ?>[]"
				        onchange="geodir_popular_widget_cat_title(this)">

					<option <?php if ( is_array( $category ) && in_array( '0', $category ) ) {
						echo 'selected="selected"';
					} ?> value="0"><?php _e( 'All', GEODIRECTORY_TEXTDOMAIN ); ?></option>
					<?php foreach ( $categories as $category_obj ) {
						$selected = '';
						if ( is_array( $category ) && in_array( $category_obj->term_id, $category ) ) {
							echo $selected = 'selected="selected"';
						}

						?>

						<option <?php echo $selected; ?>
							value="<?php echo $category_obj->term_id; ?>"><?php echo ucfirst( $category_obj->name ); ?></option>

					<?php } ?>

				</select>


				<input type="hidden" name="<?php echo $this->get_field_name( 'category_title' ); ?>"
				       id="<?php echo $this->get_field_id( 'category_title' ); ?>"
				       value="<?php if ( $category_title != '' ) {
					       echo $category_title;
				       } else {
					       echo __( 'All', GEODIRECTORY_TEXTDOMAIN );
				       } ?>"/>

			</label>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'list_sort' ); ?>"><?php _e( 'Sort by:', GEODIRECTORY_TEXTDOMAIN ); ?>

				<select class="widefat" id="<?php echo $this->get_field_id( 'list_sort' ); ?>"
				        name="<?php echo $this->get_field_name( 'list_sort' ); ?>">

					<option <?php if ( $list_sort == 'az' ) {
						echo 'selected="selected"';
					} ?> value="az"><?php _e( 'A-Z', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'latest' ) {
						echo 'selected="selected"';
					} ?> value="latest"><?php _e( 'Latest', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'featured' ) {
						echo 'selected="selected"';
					} ?> value="featured"><?php _e( 'Featured', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'high_review' ) {
						echo 'selected="selected"';
					} ?> value="high_review"><?php _e( 'Review', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'high_rating' ) {
						echo 'selected="selected"';
					} ?> value="high_rating"><?php _e( 'Rating', GEODIRECTORY_TEXTDOMAIN ); ?></option>

					<option <?php if ( $list_sort == 'random' ) {
						echo 'selected="selected"';
					} ?> value="random"><?php _e( 'Random', GEODIRECTORY_TEXTDOMAIN ); ?></option>

				</select>
			</label>
		</p>

		<p>

			<label
				for="<?php echo $this->get_field_id( 'post_number' ); ?>"><?php _e( 'Number of posts:', GEODIRECTORY_TEXTDOMAIN ); ?>

				<input class="widefat" id="<?php echo $this->get_field_id( 'post_number' ); ?>"
				       name="<?php echo $this->get_field_name( 'post_number' ); ?>" type="text"
				       value="<?php echo esc_attr( $post_number ); ?>"/>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'layout' ); ?>">
				<?php _e( 'Layout:', GEODIRECTORY_TEXTDOMAIN ); ?>
				<select class="widefat" id="<?php echo $this->get_field_id( 'layout' ); ?>"
				        name="<?php echo $this->get_field_name( 'layout' ); ?>">
					<option <?php if ( $layout == 'gridview_onehalf' ) {
						echo 'selected="selected"';
					} ?>
						value="gridview_onehalf"><?php _e( 'Grid View (Two Columns)', GEODIRECTORY_TEXTDOMAIN ); ?></option>
					<option <?php if ( $layout == 'gridview_onethird' ) {
						echo 'selected="selected"';
					} ?>
						value="gridview_onethird"><?php _e( 'Grid View (Three Columns)', GEODIRECTORY_TEXTDOMAIN ); ?></option>
					<option <?php if ( $layout == 'gridview_onefourth' ) {
						echo 'selected="selected"';
					} ?>
						value="gridview_onefourth"><?php _e( 'Grid View (Four Columns)', GEODIRECTORY_TEXTDOMAIN ); ?></option>
					<option <?php if ( $layout == 'gridview_onefifth' ) {
						echo 'selected="selected"';
					} ?>
						value="gridview_onefifth"><?php _e( 'Grid View (Five Columns)', GEODIRECTORY_TEXTDOMAIN ); ?></option>
					<option <?php if ( $layout == 'list' ) {
						echo 'selected="selected"';
					} ?> value="list"><?php _e( 'List view', GEODIRECTORY_TEXTDOMAIN ); ?></option>

				</select>
			</label>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'listing_width' ); ?>"><?php _e( 'Listing width:', GEODIRECTORY_TEXTDOMAIN ); ?>

				<input class="widefat" id="<?php echo $this->get_field_id( 'listing_width' ); ?>"
				       name="<?php echo $this->get_field_name( 'listing_width' ); ?>" type="text"
				       value="<?php echo esc_attr( $listing_width ); ?>"/>
			</label>
		</p>

		<p>
			<label
				for="<?php echo $this->get_field_id( 'character_count' ); ?>"><?php _e( 'Post Content excerpt character count :', GEODIRECTORY_TEXTDOMAIN ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'character_count' ); ?>"
				       name="<?php echo $this->get_field_name( 'character_count' ); ?>" type="text"
				       value="<?php echo esc_attr( $character_count ); ?>"/>
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'add_location_filter' ); ?>">
				<?php _e( 'Enable Location Filter:', GEODIRECTORY_TEXTDOMAIN ); ?>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'add_location_filter' ); ?>"
				       name="<?php echo $this->get_field_name( 'add_location_filter' ); ?>" <?php if ( $add_location_filter ) {
					echo 'checked="checked"';
				} ?>  value="1"/>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_featured_only' ); ?>">
				<?php _e( 'Show only featured listings:', GEODIRECTORY_TEXTDOMAIN ); ?> <input type="checkbox"
				                                                                               id="<?php echo $this->get_field_id( 'show_featured_only' ); ?>"
				                                                                               name="<?php echo $this->get_field_name( 'show_featured_only' ); ?>" <?php if ( $show_featured_only ) {
					echo 'checked="checked"';
				} ?>  value="1"/>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_special_only' ); ?>">
				<?php _e( 'Show only listings with special offers:', GEODIRECTORY_TEXTDOMAIN ); ?> <input
					type="checkbox" id="<?php echo $this->get_field_id( 'show_special_only' ); ?>"
					name="<?php echo $this->get_field_name( 'show_special_only' ); ?>" <?php if ( $show_special_only ) {
					echo 'checked="checked"';
				} ?>  value="1"/>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'with_pics_only' ); ?>">
				<?php _e( 'Show only listings with pics:', GEODIRECTORY_TEXTDOMAIN ); ?> <input type="checkbox"
				                                                                                id="<?php echo $this->get_field_id( 'with_pics_only' ); ?>"
				                                                                                name="<?php echo $this->get_field_name( 'with_pics_only' ); ?>" <?php if ( $with_pics_only ) {
					echo 'checked="checked"';
				} ?>  value="1"/>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'with_videos_only' ); ?>">
				<?php _e( 'Show only listings with videos:', GEODIRECTORY_TEXTDOMAIN ); ?> <input type="checkbox"
				                                                                                  id="<?php echo $this->get_field_id( 'with_videos_only' ); ?>"
				                                                                                  name="<?php echo $this->get_field_name( 'with_videos_only' ); ?>" <?php if ( $with_videos_only ) {
					echo 'checked="checked"';
				} ?>  value="1"/>
			</label>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'use_viewing_post_type' ); ?>"><?php _e( 'Use current viewing post type:', GEODIRECTORY_TEXTDOMAIN ); ?>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'use_viewing_post_type' ); ?>"
				       name="<?php echo $this->get_field_name( 'use_viewing_post_type' ); ?>" <?php if ( $use_viewing_post_type ) {
					echo 'checked="checked"';
				} ?>  value="1"/>
			</label>
		</p>


		<script type="text/javascript">

			function geodir_popular_widget_cat_title(val) {

				jQuery(val).find("option:selected").each(function (i) {
					if (i == 0)
						jQuery(val).closest('form').find('#post_type_cats input').val(jQuery(this).html());

				});

			}

			function geodir_change_category_list(obj, selected) {
				var post_type = obj.value;

				var ajax_url = '<?php echo geodir_get_ajax_url(); ?>';

				var myurl = ajax_url + "&geodir_ajax=admin_ajax&ajax_action=get_cat_dl&post_type=" + post_type + "&selected=" + selected;

				jQuery.ajax({
					type: "GET",
					url: myurl,
					success: function (data) {

						jQuery(obj).closest('form').find('#post_type_cats select').html(data);

					}
				});

			}

			<?php if(is_active_widget( false, false, $this->id_base, true )){ ?>
			var post_type = jQuery('#<?php echo $this->get_field_id('post_type'); ?>').val();

			<?php } ?>

		</script>

	<?php
	}
}

register_widget( 'geodir_popular_postview' );
