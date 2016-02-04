<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @since 1.0.0
 * @since 1.5.4 Modified to fix review sorting.
 *
 * @package GeoDirectory
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required())
    return;
?>

<div id="comments" class="comments-area">

    <?php // You can start editing here -- including this comment! ?>

    <?php do_action('geodir_before_review_list'); ?>

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            printf(_n('1 Review <span class="r-title-on">on</span> <span class="r-title">&ldquo;%2$s&rdquo;</span>', '%1$s Reviews <span>on</span> <span class="r-title"> &ldquo;%2$s&rdquo;</span>', get_comments_number(), 'geodirectory'),
                number_format_i18n(get_comments_number()), get_the_title());
            ?>
        </h2>

        <?php do_action('geodir_after_review_list_title'); ?>

        <ol class="commentlist">
            <?php $reverse_top_level = is_plugin_active('geodir_review_rating_manager/geodir_review_rating_manager.php') ? false : null; ?>
			<?php wp_list_comments(array('callback' => 'geodir_comment', 'reverse_top_level' => $reverse_top_level, 'style' => 'ol'));
            ?>
        </ol><!-- .commentlist -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
            <nav id="comment-nav-below" class="navigation" role="navigation">
                <h1 class="assistive-text section-heading"><?php _e('Comment navigation', 'geodirectory'); ?></h1>

                <div
                    class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'geodirectory')); ?></div>
                <div
                    class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'geodirectory')); ?></div>
            </nav>
        <?php endif; // check for comment navigation ?>

        <?php
        /* If there are no comments and comments are closed, let's leave a note.
         * But we only want the note on posts and pages that had comments in the first place.
         */
        if (!comments_open() && get_comments_number()) : ?>
            <p class="nocomments"><?php _e('Comments are closed.', 'geodirectory'); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments() ?>

    <?php do_action('geodir_after_review_list'); ?>
    <?php do_action('geodir_before_review_form'); ?>

    <?php
    $args = apply_filters('geodir_review_form_args', array(
        'title_reply' => __('Leave a Review', 'geodirectory'),
        'label_submit' => __('Post Review', 'geodirectory'),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . __('Review text', 'geodirectory') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.', 'geodirectory'), geodir_login_url()) . '</p>'
    ));
    comment_form($args);
    ?>

    <?php do_action('geodir_after_review_form'); ?>

</div><!-- #comments .comments-area -->