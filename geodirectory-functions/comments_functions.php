<?php
/**
 * Comment related functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

add_filter('comment_row_actions', 'geodir_comment_meta_row_action', 11, 1);
/**
 * Add the comment meta fields to the comments admin page.
 *
 * Adds rating stars below each comment of the WP Admin Dashboard -> Comments page.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param array $a {
 *    List of comment row actions.
 *
 *    @type string $approve Current comment approve HTML link string.
 *    @type string $unapprove Current comment unapprove HTML link string.
 *    @type string $reply Current comment reply HTML link string.
 *    @type string $quickedit Current comment Quick Edit HTML link string.
 *    @type string $edit Current comment Edit HTML link string.
 *    @type string $spam Current comment Spam HTML link string.
 *    @type string $trash Current comment Trash HTML link string.
 *
 * }
 * @global object $comment The comment object.
 * @return mixed Comment row actions.
 */
function geodir_comment_meta_row_action($a)
{
    global $comment;

    $rating = geodir_get_commentoverall($comment->comment_ID);
    if ($rating != 0) {
        echo geodir_get_rating_stars($rating, $comment->comment_ID);
    }
    return $a;
}

add_action('add_meta_boxes_comment', 'geodir_comment_add_meta_box');
/**
 * Adds comment rating meta box.
 *
 * Adds meta box to Comments -> Edit page using hook {@see 'add_meta_boxes_comment'}.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param object $comment The comment object.
 */
function geodir_comment_add_meta_box($comment)
{
    add_meta_box('gd-comment-rating', __('Comment Rating', 'geodirectory'), 'geodir_comment_rating_meta', 'comment', 'normal', 'high');
}

/**
 * Add comment rating meta box form fields.
 *
 * Adds form fields to the function {@see 'geodir_comment_add_meta_box'}.
 *
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @package GeoDirectory
 * @param object $comment The comment object.
 */
function geodir_comment_rating_meta($comment) {
    $post_type = get_post_type($comment->comment_post_ID);
	if (in_array($post_type, (array)geodir_get_posttypes()) && (int)$comment->comment_parent == 0 && !(!empty($post_type) && geodir_cpt_has_rating_disabled($post_type))) {
		$rating = geodir_get_commentoverall($comment->comment_ID);
		
		if ((int)get_option('geodir_reviewrating_enable_font_awesome') == 1) {
			$star_texts = array();
			$star_texts[] = __('Terrible', 'geodirectory');
			$star_texts[] = __('Poor', 'geodirectory');
			$star_texts[] = __('Average', 'geodirectory');
			$star_texts[] = __('Very Good', 'geodirectory');
			$star_texts[] = __('Excellent', 'geodirectory');
			
			echo geodir_font_awesome_rating_form_html('', $star_texts, $rating);
		} else {			
			if ($rating) {
				echo '<div class="gd_rating" data-average="' . $rating . '" data-id="5">';

			} else {
				echo '<div class="gd_rating" data-average="0" data-id="5"></div>';
			}
		}
		echo '<input type="hidden" id="geodir_overallrating" name="geodir_overallrating" value="' . $rating . '"  />';
	}
}


add_action('comment_form_logged_in_after', 'geodir_comment_rating_fields');
add_action('comment_form_before_fields', 'geodir_comment_rating_fields');

/**
 * Add rating fields in comment form.
 *
 * Adds a rating input field in comment form.
 *
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @package GeoDirectory
 * @global object $post The post object.
 */
function geodir_comment_rating_fields() {
    global $post;

    $post_types = geodir_get_posttypes();

    if (!empty($post->post_type) && in_array($post->post_type, $post_types) && !(!empty($post->post_type) && geodir_cpt_has_rating_disabled($post->post_type))) {
        $star_texts = array();
        $star_texts[] = __('Terrible', 'geodirectory');
        $star_texts[] = __('Poor', 'geodirectory');
        $star_texts[] = __('Average', 'geodirectory');
        $star_texts[] = __('Very Good', 'geodirectory');
        $star_texts[] = __('Excellent', 'geodirectory');
        
        $gd_rating_html = apply_filters('gd_rating_form_html', '<div class="gd_rating" data-average="0" data-id="5"></div>', $star_texts);
        echo $gd_rating_html;
        ?>
        <input type="hidden" id="geodir_overallrating" name="geodir_overallrating" value="0"/>
        <?php
    }
}

add_filter('comment_reply_link', 'geodir_comment_replaylink');
/**
 * Wrap comment reply link with custom div.
 *
 * @since 1.0.0
 * @since 1.6.18 Replace standard login link on login to reply link.
 * @package GeoDirectory
 * @param string $link The HTML link.
 * @return string The HTML link.
 */
function geodir_comment_replaylink($link)
{

    if (strpos($link, 'wp-login.php?') !== false) {
        $link = str_replace(wp_login_url(),geodir_login_url(),$link);
    }
    $link = '<div class="gd_comment_replaylink">' . $link . '</div>';

    return $link;
}

add_filter('cancel_comment_reply_link', 'geodir_cancle_replaylink');
/**
 * Wrap comment cancel reply link with custom div.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $link The HTML link.
 * @return string The HTML link.
 */
function geodir_cancle_replaylink($link)
{

    $link = '<span class="gd-cancel-replaylink">' . $link . '</span>';

    return $link;
}

add_action('comment_post', 'geodir_save_rating');
/**
 * Save rating details for a comment.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $comment The comment ID.
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global int $user_ID The current user ID.
 */
function geodir_save_rating($comment = 0)
{
    global $wpdb, $user_ID, $plugin_prefix;

    $comment_info = get_comment($comment);

    $post_id = $comment_info->comment_post_ID;
    $status = $comment_info->comment_approved;
    $rating_ip = getenv("REMOTE_ADDR");
	
    $post = geodir_get_post_info($post_id);
    if (empty($post)) {
        return;
    }

    if ($post->post_status == 'publish') {
        $post_status = '1';
    } else {
        $post_status = '0';
    }
	
    if (isset($_REQUEST['geodir_overallrating'])) {
        $overall_rating = $_REQUEST['geodir_overallrating'];
        
		if (isset($comment_info->comment_parent) && (int)$comment_info->comment_parent == 0) {
            $overall_rating = $overall_rating > 0 ? $overall_rating : '0';

            $sqlqry = $wpdb->prepare("INSERT INTO " . GEODIR_REVIEW_TABLE . " SET
					post_id		= %d,
					post_type = %s,
					post_title	= %s,
					user_id		= %d,
					comment_id	= %d,
					rating_ip	= %s,
					overall_rating = %f,
					status		= %s,
					post_status		= %s, 
					post_date		= %s, 
					post_city		= %s, 
					post_region		= %s, 
					post_country	= %s,
					post_longitude	= %s,
					post_latitude	= %s,
					comment_content	= %s 
					",
                array($post_id, $post->post_type, $post->post_title, $user_ID, $comment, $rating_ip, $overall_rating, $status, $post_status, date_i18n('Y-m-d H:i:s', current_time('timestamp')), $post->post_city, $post->post_region, $post->post_country, $post->post_latitude, $post->post_longitude, $comment_info->comment_content)
            );

            $wpdb->query($sqlqry);

            /**
             * Called after saving the comment.
             *
             * @since 1.0.0
             * @package GeoDirectory
             * @param array $_REQUEST {
             *    Attributes of the $_REQUEST variable.
             *
             *    @type string $geodir_overallrating Overall rating.
             *    @type string $comment Comment text.
             *    @type string $submit Submit button text.
             *    @type string $comment_post_ID Comment post ID.
             *    @type string $comment_parent Comment Parent ID.
             *    @type string $_wp_unfiltered_html_comment Unfiltered html comment string.
             *
             * }
             */
            do_action('geodir_after_save_comment', $_REQUEST, 'Comment Your Post');

            if ($status) {
                geodir_update_postrating($post_id);
            }
        }
    }
}


add_action('wp_set_comment_status', 'geodir_update_rating_status_change', 10, 2);
/**
 * Update comment status when changing the rating.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $comment_id The comment ID.
 * @param int|string $status The comment status.
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global int $user_ID The current user ID.
 */
function geodir_update_rating_status_change($comment_id, $status)
{
    if ($status == 'delete') {
        return;
    }
    global $wpdb, $plugin_prefix, $user_ID;

    $comment_info = get_comment($comment_id);

    $post_id = isset($comment_info->comment_post_ID) ? $comment_info->comment_post_ID : '';

    if (!empty($comment_info))
        $status = $comment_info->comment_approved;

    if ($status == 'approve' || $status == 1) {
        $status = 1;
    } else {
        $status = 0;
    }

    $comment_info_ID = isset($comment_info->comment_ID) ? $comment_info->comment_ID : '';
    $old_rating = geodir_get_commentoverall($comment_info_ID);

    $post_type = get_post_type($post_id);

    $detail_table = $plugin_prefix . $post_type . '_detail';

    if ($comment_id) {

        $overall_rating = $old_rating;

        if (isset($old_rating)) {

            $sqlqry = $wpdb->prepare("UPDATE " . GEODIR_REVIEW_TABLE . " SET
						overall_rating = %f,
						status		= %s,
						comment_content = %s 
						WHERE comment_id = %d ", array($overall_rating, $status, $comment_info->comment_content, $comment_id));

            $wpdb->query($sqlqry);

            //update rating
            geodir_update_postrating($post_id, $post_type);

        }

    }

}


add_action('edit_comment', 'geodir_update_rating');
/**
 * Update comment rating.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $comment_id The comment ID.
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @global int $user_ID The current user ID.
 */
function geodir_update_rating($comment_id = 0)
{

    global $wpdb, $plugin_prefix, $user_ID;

    $comment_info = get_comment($comment_id);

    $post_id = $comment_info->comment_post_ID;
    $status = $comment_info->comment_approved;
    $old_rating = geodir_get_commentoverall($comment_info->comment_ID);

    $post_type = get_post_type($post_id);

    $detail_table = $plugin_prefix . $post_type . '_detail';

    if (isset($_REQUEST['geodir_overallrating'])) {

        $overall_rating = $_REQUEST['geodir_overallrating'];

        if (isset($comment_info->comment_parent) && (int)$comment_info->comment_parent == 0) {
            $overall_rating = $overall_rating > 0 ? $overall_rating : '0';

            if (isset($old_rating)) {

                $sqlqry = $wpdb->prepare("UPDATE " . GEODIR_REVIEW_TABLE . " SET
						overall_rating = %f,
						status		= %s,
						comment_content	= %s 
						WHERE comment_id = %d ", array($overall_rating, $status, $comment_info->comment_content, $comment_id));

                $wpdb->query($sqlqry);

                //update rating
                geodir_update_postrating($post_id, $post_type);

            }
        }
    }


}

add_action('delete_comment', 'geodir_comment_delete_comment');
/**
 * Delete review details when deleting comment.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $comment_id The comment ID.
 * @global object $wpdb WordPress Database object.
 */
function geodir_comment_delete_comment($comment_id)
{
    global $wpdb;

    $review_info = geodir_get_review($comment_id);
    if ($review_info) {
        geodir_update_postrating($review_info->post_id);
    }

    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM " . GEODIR_REVIEW_TABLE . " WHERE comment_id=%d",
            array($comment_id)
        )
    );

}

add_filter('comment_text', 'geodir_wrap_comment_text', 40, 2);
/**
 * Add rating information in comment text.
 *
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @package GeoDirectory
 * @param string $content The comment content.
 * @param object|string $comment The comment object.
 * @return string The comment content.
 */
function geodir_wrap_comment_text($content, $comment = '') {
    if (!empty($comment->comment_post_ID) && geodir_cpt_has_rating_disabled((int)$comment->comment_post_ID)) {
        if (!is_admin()) {
            return '<div class="description">' . $content . '</div>';
        } else {
            return $content;
        }
    } else {
        $rating = 0;
        if (!empty($comment))
            $rating = geodir_get_commentoverall($comment->comment_ID);
        if ($rating != 0 && !is_admin()) {
            return '<div><div class="gd-rating-text">' . __('Overall Rating', 'geodirectory') . ': <div class="rating">' . $rating . '</div></div>' . geodir_get_rating_stars($rating, $comment->comment_ID) . '</div><div class="description">' . $content . '</div>';
        } else
            return $content;
    }
}


/**
 * Update post overall rating and rating count.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int $post_id The post ID.
 * @param string $post_type The post type.
 * @param bool $delete Depreciated since ver 1.3.6.
 */
function geodir_update_postrating($post_id = 0, $post_type = '', $delete = false)
{
    global $wpdb, $plugin_prefix, $comment;
    if (!$post_type) {
        $post_type = get_post_type($post_id);
    }
    $detail_table = $plugin_prefix . $post_type . '_detail';
    $post_newrating = geodir_get_post_rating($post_id, 1);
    $post_newrating_count = geodir_get_review_count_total($post_id);


    //$post_newrating = ( (float)$post_oldrating - (float)$old_rating ) + (float)$overall_rating ;

    if ($wpdb->get_var("SHOW TABLES LIKE '" . $detail_table . "'") == $detail_table) {

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE " . $detail_table . " SET
						overall_rating = %f,
						rating_count = %f
						where post_id = %d",
                array($post_newrating, $post_newrating_count, $post_id)
            )
        );

        update_post_meta($post_id, 'overall_rating', $post_newrating);
        update_post_meta($post_id, 'rating_count', $post_newrating_count);
    }
    /**
     * Called after Updating post overall rating and rating count.
     *
     * @since 1.0.0
     * @since 1.4.3 Added `$post_id` param.
     * @package GeoDirectory
     * @param int $post_id The post ID.
     */
    do_action('geodir_update_postrating',$post_id);

}

/**
 * Get post overall rating.
 *
 * Returns overall rating of a post. If no rating returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @global string $plugin_prefix Geodirectory plugin table prefix.
 * @param int $post_id The post ID.
 * @return bool|mixed|null|string
 */
function geodir_get_postoverall($post_id = 0)
{
    global $wpdb, $plugin_prefix;

    $post_type = get_post_type($post_id);
    $detail_table = $plugin_prefix . $post_type . '_detail';

    if ($wpdb->get_var("SHOW TABLES LIKE '" . $detail_table . "'") == $detail_table) {

        $post_ratings = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT overall_rating FROM " . $detail_table . " WHERE post_id = %d",
                array($post_id)
            )
        );


    } else {
        $post_ratings = get_post_meta($post_id, 'overall_rating');
    }

    if ($post_ratings)
        return $post_ratings;
    else
        return false;
}


/**
 * Get review details using comment ID.
 *
 * Returns review details using comment ID. If no reviews returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $comment_id The comment ID.
 * @global object $wpdb WordPress Database object.
 * @return bool|mixed
 */
function geodir_get_review($comment_id = 0)
{
    global $wpdb;

    $reatings = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM " . GEODIR_REVIEW_TABLE . " WHERE comment_id = %d",
            array($comment_id)
        )
    );

    if (!empty($reatings))
        return $reatings;
    else
        return false;
}

/**
 * Get review total of a Post.
 *
 * Returns review total of a post. If no results returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_id The post ID.
 * @global object $wpdb WordPress Database object.
 * @return bool|null|string
 */
function geodir_get_review_total($post_id = 0)
{
    global $wpdb;

    $results = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT SUM(overall_rating) FROM " . GEODIR_REVIEW_TABLE . " WHERE post_id = %d AND status=1 AND overall_rating>0",
            array($post_id)
        )
    );

    if (!empty($results))
        return $results;
    else
        return false;
}

/**
 * Get review count by user ID.
 *
 * Returns review count of a user. If no results returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $user_id
 * @global object $wpdb WordPress Database object.
 * @return bool|null|string
 */
function geodir_get_review_count_by_user_id($user_id = 0)
{
    global $wpdb;
    $results = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(overall_rating) FROM " . GEODIR_REVIEW_TABLE . " WHERE user_id = %d AND status=1 AND overall_rating>0",
            array($user_id)
        )
    );

    if (!empty($results))
        return $results;
    else
        return false;
}

/**
 * Get average overall rating of a Post.
 *
 * Returns average overall rating of a Post. If no results, returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_id The post ID.
 * @param int $force_query Optional. Do you want force run the query? Default: 0.
 * @global object $wpdb WordPress Database object.
 * @global object $post The current post object.
 * @return array|bool|int|mixed|null|string
 */
function geodir_get_post_rating($post_id = 0, $force_query = 0)
{
    global $wpdb, $post;

    if (isset($post->ID) && $post->ID == $post_id && !$force_query) {
        if (isset($post->rating_count) && $post->rating_count > 0 && isset($post->overall_rating) && $post->overall_rating > 0) {
            return $post->overall_rating;
        } else {
            return 0;
        }
    }

    $results = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COALESCE(avg(overall_rating),0) FROM " . GEODIR_REVIEW_TABLE . " WHERE post_id = %d AND status=1 AND overall_rating>0",
            array($post_id)
        )
    );

    if (!empty($results))
        return $results;
    else
        return false;
}

/**
 * Get review count of a Post.
 *
 * Returns review count of a Post. If no results, returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_id The post ID.
 * @global object $wpdb WordPress Database object.
 * @return bool|null|string
 */
function geodir_get_review_count_total($post_id = 0)
{
    global $wpdb;

    $results = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(overall_rating) FROM " . GEODIR_REVIEW_TABLE . " WHERE post_id = %d AND status=1 AND overall_rating>0",
            array($post_id)
        )
    );

    if (!empty($results))
        return $results;
    else
        return false;
}

/**
 * Get comments count of a Post.
 *
 * Returns comments count of a Post. If no results, returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_id The post ID.
 * @global object $wpdb WordPress Database object.
 * @return bool|null|string
 * @todo It might be a duplicate function of geodir_get_review_count_total().
 */
function geodir_get_comments_number($post_id = 0)
{
    global $wpdb;

    $results = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(overall_rating) FROM " . GEODIR_REVIEW_TABLE . " WHERE post_id = %d AND status=1 AND overall_rating>0",
            array($post_id)
        )
    );


    if (!empty($results))
        return $results;
    else
        return false;
}

/**
 * Get overall rating of a comment.
 *
 * Returns overall rating of a comment. If no results, returns false.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $comment_id The comment ID.
 * @global object $wpdb WordPress Database object.
 * @return bool|null|string
 */
function geodir_get_commentoverall($comment_id = 0)
{
    global $wpdb;

    $reatings = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT overall_rating FROM " . GEODIR_REVIEW_TABLE . " WHERE comment_id = %d",
            array($comment_id)
        )
    );

    if ($reatings)
        return $reatings;
    else
        return false;
}

/**
 * Returns average overall rating of a Post. Depreciated since ver 1.3.6.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param int $post_id The post ID.
 * @internal Depreciated since ver 1.3.6.
 * @return array|bool|int|mixed|null|string
 */
function geodir_get_commentoverall_number($post_id = 0)
{
    return geodir_get_post_rating($post_id);
}


/**
 * Sets the comment template.
 *
 * Sets the comment template using filter {@see 'comments_template'}.
 *
 * @since 1.0.0
 * @since 1.5.1 Reviews template can be overridden from theme.
 * @package GeoDirectory
 * @global object $post The current post object.
 * @param string $comment_template Old comment template.
 * @return string New comment template.
 */
function geodir_comment_template($comment_template)
{
    global $post;

    $post_types = geodir_get_posttypes();

    if (!(is_singular() && (have_comments() || (isset($post->comment_status) && 'open' == $post->comment_status)))) {
        return;
    }
    if (in_array($post->post_type, $post_types)) { // assuming there is a post type called business
        if (geodir_cpt_has_rating_disabled($post->post_type)) {
            return $comment_template;
        }
        
        $template = locate_template(array("geodirectory/reviews.php")); // Use theme template if available
        if (!$template) {
            $template = dirname(__FILE__) . '/reviews.php';
        }
        return $template;
    }
}

add_filter("comments_template", "geodir_comment_template");


if (!function_exists('geodir_comment')) {
    /**
     * Comment HTML markup.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $post The current post object.
     * @param object $comment The comment object.
     * @param string|array $args {
     *     Optional. Formatting options.
     *
     *     @type object $walker            Instance of a Walker class to list comments. Default null.
     *     @type int    $max_depth         The maximum comments depth. Default empty.
     *     @type string $style             The style of list ordering. Default 'ul'. Accepts 'ul', 'ol'.
     *     @type string $callback          Callback function to use. Default null.
     *     @type string $end-callback      Callback function to use at the end. Default null.
     *     @type string $type              Type of comments to list.
     *                                     Default 'all'. Accepts 'all', 'comment', 'pingback', 'trackback', 'pings'.
     *     @type int    $page              Page ID to list comments for. Default empty.
     *     @type int    $per_page          Number of comments to list per page. Default empty.
     *     @type int    $avatar_size       Height and width dimensions of the avatar size. Default 32.
     *     @type string $reverse_top_level Ordering of the listed comments. Default null. Accepts 'desc', 'asc'.
     *     @type bool   $reverse_children  Whether to reverse child comments in the list. Default null.
     *     @type string $format            How to format the comments list.
     *                                     Default 'html5' if the theme supports it. Accepts 'html5', 'xhtml'.
     *     @type bool   $short_ping        Whether to output short pings. Default false.
     *     @type bool   $echo              Whether to echo the output or return it. Default true.
     * }
     * @param int $depth Depth of comment.
     */
    function geodir_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class('geodir-comment'); ?> id="comment-<?php comment_ID(); ?>">
                <p><?php _e('Pingback:', 'geodirectory'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'geodirectory'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
            <li <?php comment_class('geodir-comment'); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>" class="comment">
                    <header class="comment-meta comment-author vcard">
                        <?php
                        /**
                         * Filter to modify comment avatar size
                         *
                         * You can use this filter to change comment avatar size.
                         *
                         * @since 1.0.0
                         * @package GeoDirectory
                         */
                        $avatar_size = apply_filters('geodir_comment_avatar_size', 44);
                        echo get_avatar($comment, $avatar_size);
                        printf('<cite><b class="reviewer">%1$s</b> %2$s</cite>',
                            get_comment_author_link(),
                            // If current post author is also comment author, make it known visually.
                            ($comment->user_id === $post->post_author) ? '<span>' . __('Post author', 'geodirectory') . '</span>' : ''
                        );
                        echo "<span class='item'><small><span class='fn'>$post->post_title</span></small></span>";
                        printf('<a href="%1$s"><time datetime="%2$s" class="dtreviewed">%3$s<span class="value-title" title="%2$s"></span></time></a>',
                            esc_url(get_comment_link($comment->comment_ID)),
                            get_comment_time('c'),
                            /* translators: 1: date, 2: time */
                            sprintf(__('%1$s at %2$s', 'geodirectory'), get_comment_date(), get_comment_time())
                        );
                        ?>
                    </header>
                    <!-- .comment-meta -->

                    <?php if ('0' == $comment->comment_approved) : ?>
                        <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'geodirectory'); ?></p>
                    <?php endif; ?>

                    <section class="comment-content comment">
                        <?php comment_text(); ?>
                    </section>
                    <!-- .comment-content -->

                    <div class="comment-links">
                        <?php edit_comment_link(__('Edit', 'geodirectory'), '<p class="edit-link">', '</p>'); ?>
                        <div class="reply">
                            <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'geodirectory'), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div>
                    </div>

                    <!-- .reply -->
                </article>
                <!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }
}


add_filter('get_comments_number', 'geodir_fix_comment_count', 10, 2);
if (!function_exists('geodir_fix_comment_count')) {
    /**
     * Fix comment count by not listing replies as reviews
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @global object $post The current post object.
     * @param int $count The comment count.
     * @param int $post_id The post ID.
     * @todo $post is unreachable since the function return the count before that variable.
     * @return bool|null|string The comment count.
     */
    function geodir_fix_comment_count($count, $post_id)
    {
        if (!is_admin() || strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php')) {
            global $post;
            $post_types = geodir_get_posttypes();

            if (in_array(get_post_type($post_id), $post_types) && !geodir_cpt_has_rating_disabled((int)$post_id)) {
                $review_count = geodir_get_review_count_total($post_id);
                return $review_count;

                if ($post && isset($post->rating_count)) {
                    return $post->rating_count;
                } else {
                    return geodir_get_comments_number($post_id);
                }
            } else {
                return $count;
            }
        } else {
            return $count;
        }
    }
}

/**
 * HTML for rating stars
 *
 * This is the main HTML markup that displays rating stars.
 *
 * @since 1.0.0
 * @since 1.6.16 Changes for disable review stars for certain post type.
 * @package GeoDirectory
 * @param float $rating The post average rating.
 * @param int $post_id The post ID.
 * @param bool $small Optional. Display as small ratings? Default: false.
 * @return string Rating HTML.
 */
function geodir_get_rating_stars($rating, $post_id, $small = false)
{
    if (!empty($post_id) && geodir_cpt_has_rating_disabled((int)$post_id)) {
        return NULL;
    }
    $a_rating = $rating / 5 * 100;

    if ($small) {
        $r_html = '<div class="rating"><div class="gd_rating_map" data-average="' . $rating . '" data-id="' . $post_id . '"><div class="geodir_RatingColor" ></div><div class="geodir_RatingAverage_small" style="width: ' . $a_rating . '%;"></div><div class="geodir_Star_small"></div></div></div>';
    } else {
		if (function_exists('geodir_reviewrating_draw_overall_rating')) {
			// Show rating stars from review rating manager
			$r_html = geodir_reviewrating_draw_overall_rating($rating);
		} else {
			$rating_img = '<img alt="rating icon" src="' . get_option('geodir_default_rating_star_icon') . '" />';
			
			/* fix rating star for safari */
			$star_width = 23 * 5;
			
			if ($star_width > 0) {
				$attach_style = 'max-width:' . $star_width . 'px';
			} else {
				$attach_style = '';
			}
			$r_html = '<div class="geodir-rating" style="' . $attach_style . '"><div class="gd_rating_show" data-average="' . $rating . '" data-id="' . $post_id . '"><div class="geodir_RatingAverage" style="width: ' . $a_rating . '%;"></div><div class="geodir_Star">' . $rating_img . $rating_img . $rating_img . $rating_img . $rating_img . '</div></div></div>';
		}
    }
    return apply_filters('geodir_get_rating_stars_html', $r_html, $rating, 5);
}

/**
 * Check whether to display ratings or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $pageview The view template. Ex: listview, gridview etc.
 * @return mixed|void
 */
function geodir_is_reviews_show($pageview = '')
{

    $active_tabs = get_option('geodir_detail_page_tabs_excluded');

    $is_display = true;
    if (!empty($active_tabs) && in_array('reviews', $active_tabs))
        $is_display = false;

    /**
     * Filter to change display value.
     *
     * You can use this filter to change the is_display value.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @param bool $is_display Display ratings when set to true.
     * @param string $pageview The view template. Ex: listview, gridview etc.
     */
    return apply_filters('geodir_is_reviews_show', $is_display, $pageview);
}


/*
 * If Disqus plugin is active, do some fixes to show on blogs but no on GD post types
 */
if(function_exists('dsq_can_replace')) {
    remove_filter('comments_template', 'dsq_comments_template');
    add_filter('comments_template', 'dsq_comments_template', 100);
    add_filter('pre_option_disqus_active', 'geodir_option_disqus_active',10,1);
}



/**
 * Disable Disqus plugin on the fly when visiting GeoDirectory post types.
 *
 * @since 1.5.0
 * @package GeoDirectory
 * @param string $disqus_active Hook called before DB call for option so this is empty.
 * @return string `1` if active `0` if disabled.
 */
function geodir_option_disqus_active($disqus_active){
    global $post;
    $all_postypes = geodir_get_posttypes();

    if(isset($post->post_type) && is_array($all_postypes) && in_array($post->post_type,$all_postypes)){
        $disqus_active = '0';
    }

    return $disqus_active;
}

/**
 * Detail page change tab title from reviews to comments.
 *
 * @since 1.0.0
 * 
 * @package GeoDirectory
 * 
 * @param array $tabs_arr Tabs array {@see geodir_detail_page_tab_headings_change()}.
 * @return array Modified tabs array.
 */
function geodir_detail_reviews_tab_title($tabs_arr) {
    $post_type = geodir_get_current_posttype();

    if (!empty($tabs_arr) && !empty($tabs_arr['reviews']) && isset($tabs_arr['reviews']['heading_text']) && $post_type != '' && geodir_cpt_has_rating_disabled($post_type)) {
        $label_reviews = __('Comments', 'geodirectory');
        
        if (defined('GEODIR_CP_VERSION')) {
            $post_types = geodir_get_posttypes('array');
            
            if (!empty($post_types[$post_type]['labels']['label_reviews'])) {
                $label_reviews = stripslashes(__($post_types[$post_type]['labels']['label_reviews'], 'geodirectory'));
            }
        }
        
        $tabs_arr['reviews']['heading_text'] = $label_reviews;
    }
    
    return $tabs_arr;
}
add_filter('geodir_detail_page_tab_list_extend', 'geodir_detail_reviews_tab_title', 1000, 1);


/**
 * Disable JetPack comments on GD post types.
 *
 * @since 1.6.21
 */
function geodir_jetpack_disable_comments(){
    //only run if jetpack installed
    if(defined('JETPACK__VERSION')){
        $post_types = geodir_get_posttypes();
        foreach($post_types as $post_type){
            add_filter('jetpack_comment_form_enabled_for_' . $post_type, '__return_false');
        }
    }
}

add_action('plugins_loaded','geodir_jetpack_disable_comments');

/**
 * Check whether the current post is open for reviews.
 *
 * @since 1.6.22
 *
 * @param bool $open    Whether the current post is open for reviews.
 * @param int  $post_id The post ID.
 * @return bool True if allowed otherwise False.
 */
function geodir_check_reviews_open( $open, $post_id ) {
    if ( $open && $post_id && geodir_is_page( 'detail' ) ) {
        if ( in_array( get_post_status( $post_id ), array( 'draft', 'pending', 'auto-draft', 'trash' ) ) ) {
            $open = false;
        }
    }
    
    return $open;
}
add_filter( 'comments_open', 'geodir_check_reviews_open', 10, 2 );