<?php
/**
 * Template for the message on the success page after submitting a listing.
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 */
?>
<div class="geodir_preview_section">
    <?php


    global $wpdb;

    $post_id = $_REQUEST['pid'];
    $post_info = get_post($post_id);

    $posted_date = $post_info->post_date;
    $productlink = get_permalink($post_id);
    $siteName = get_bloginfo('name');
    $siteurl = home_url();
    $siteurl_link = '<a href="' . $siteurl . '">' . $siteurl . '</a>';

    $loginurl = geodir_login_url();
    $loginurl_link = '<a href="' . $loginurl . '">login</a>';

    $post_author = $post_info->post_author;

    $user_info = get_userdata($post_author);
    $username = $user_info->user_login;
    $user_email = $user_info->user_email;

    $message = wpautop(stripslashes(__(get_option('geodir_post_added_success_msg_content'),'geodirectory')));

    $search_array = array('[#submited_information_link#]', '[#listing_link#]', '[#site_name_url#]', '[#post_id#]', '[#site_name#]', '[#user_email#]', '[#username#]', '[#login_url#]', '[#posted_date#]');
    $replace_array = array($productlink, $productlink, $siteurl_link, $post_id, $siteName, $user_email, $username, $loginurl_link, $posted_date);
    $message = str_replace($search_array, $replace_array, $message);


    ?>

    <?php

    echo '<h5 class="geodir_information">';
    echo $message;
    echo '</h5>';

    ?>

</div>