<?php
/**
 * Notification tab settings.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global array $geodir_settings Geodirectory settings array.
 */
global $geodir_settings;

/**
 * Filter GD Notifications Settings array.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */
$geodir_settings['notifications_settings'] = apply_filters('geodir_notifications_settings', array(


    array('name' => __('Options', 'geodirectory'), 'type' => 'title', 'desc' => '', 'id' => 'notification_options'),


    array('name' => __('Notification Options', 'geodirectory'), 'type' => 'sectionstart', 'id' => 'notification_options'),

    array(
        'name' => __('List of usable shortcodes', 'geodirectory'),
        'desc' => __('[#client_name#],[#listing_link#],[#posted_date#],[#number_of_days#],[#number_of_grace_days#],[#login_url#],[#username#],[#user_email#],[#site_name_url#],[#renew_link#],[#post_id#],[#site_name#],[#from_email#](in most cases this will be the admin email, except for popup forms)', 'geodirectory'),
        'id' => 'geodir_list_of_usable_shordcodes',
        'type' => 'html_content',
        'css' => 'min-width:300px;',
        'std' => 'All Places' // Default value for the page title - changed in settings
    ),

    array(
        'name' => __('Use advanced editor? (slow loading)', 'geodirectory'),
        'desc' => __('Yes', 'geodirectory'),
        'id' => 'geodir_tiny_editor',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Use advanced editor?(slow loading)', 'geodirectory'),
        'desc' => __('No', 'geodirectory'),
        'id' => 'geodir_tiny_editor',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),


    array('type' => 'sectionend', 'id' => 'notification_options'),


    array('name' => __('Site Bcc Options', 'geodirectory'), 'type' => 'title', 'desc' => '', 'id' => 'site_bcc_options'),

    array('name' => __('Site Bcc Options', 'geodirectory'), 'type' => 'sectionstart', 'id' => 'site_bcc_options'),

    array(
        'name' => __('New user registration', 'geodirectory'),
        'desc' => __('Yes', 'geodirectory'),
        'id' => 'geodir_bcc_new_user',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('New user registration', 'geodirectory'),
        'desc' => __('No', 'geodirectory'),
        'id' => 'geodir_bcc_new_user',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),

    array(
        'name' => __('Send to friend', 'geodirectory'),
        'desc' => __('Yes', 'geodirectory'),
        'id' => 'geodir_bcc_friend',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Send to friend', 'geodirectory'),
        'desc' => __('No', 'geodirectory'),
        'id' => 'geodir_bcc_friend',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),

    array(
        'name' => __('Send enquiry', 'geodirectory'),
        'desc' => __('Yes', 'geodirectory'),
        'id' => 'geodir_bcc_enquiry',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Send enquiry', 'geodirectory'),
        'desc' => __('No', 'geodirectory'),
        'id' => 'geodir_bcc_enquiry',
        'std' => 'yes',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),


    array('type' => 'sectionend', 'id' => 'site_bcc_options'),


    array('name' => __('Admin Emails', 'geodirectory'), 'type' => 'title', 'desc' => '', 'id' => 'admin_emails'),

    array('name' => __('Admin Emails', 'geodirectory'), 'type' => 'sectionstart', 'id' => 'admin_emails'),

    array(
        'name' => __('Notify to admin on post submit', 'geodirectory'),
        'desc' => __('Yes', 'geodirectory'),
        'id' => 'geodir_notify_post_submit',
        'std' => '1',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Notify to admin on post submit', 'geodirectory'),
        'desc' => __('No', 'geodirectory'),
        'id' => 'geodir_notify_post_submit',
        'std' => '1',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),
    array(
        'name' => __('Post submit success to admin email', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_post_submited_success_email_subject_admin',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('Post Submitted Successfully','geodirectory') // Default value for the page title - changed in settings
    ),
     array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_post_submited_success_email_content_admin',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __('<p>Dear Admin,</p><p>A new  listing has been published [#listing_link#]. This email is just for your information.</p><br><p>[#site_name#]</p>','geodirectory')
    ),
    array(
        'name' => __('Notify Admin when listing edited by Author', 'geodirectory'),
        'desc' => __('Yes', 'geodirectory'),
        'id' => 'geodir_notify_post_edited',
        'std' => '0',
        'type' => 'radio',
        'value' => '1',
        'radiogroup' => 'start'
    ),
    array(
        'name' => __('Notify Admin when listing edited by Author', 'geodirectory'),
        'desc' => __('No', 'geodirectory'),
        'id' => 'geodir_notify_post_edited',
        'std' => '0',
        'type' => 'radio',
        'value' => '0',
        'radiogroup' => 'end'
    ),
    array(
        'name' => __('Listing edited by Author', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_post_edited_email_subject_admin',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('[[#site_name#]] Listing edited by Author', 'geodirectory')
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_post_edited_email_content_admin',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __('<p>Dear Admin,</p><p>A listing [#listing_link#] has been edited by it\'s author [#post_author_name#].</p><br><p><b>Listing Details:</b></p><p>Listing ID: [#post_id#]</p><p>Listing URL: [#listing_link#]</p><p>Date: [#current_date#]</p><br><p>This email is just for your information.</p><p>[#site_name#]</p>', 'geodirectory')
    ),


    array('type' => 'sectionend', 'id' => 'admin_emails'),


    array('name' => __('Client Emails', 'geodirectory'), 'type' => 'title', 'desc' => '', 'id' => 'client_emails'),

    array('name' => __('Client Emails', 'geodirectory'), 'type' => 'sectionstart', 'id' => 'client_emails'),

    array(
        'name' => __('Post submit success to client email', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_post_submited_success_email_subject',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('Post Submitted Successfully','geodirectory') // Default value for the page title - changed in settings
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_post_submited_success_email_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __('<p>Dear [#client_name#],</p><p>You submitted the below listing information. This email is just for your information.</p><p>[#listing_link#]</p><br><p>Thank you for your contribution.</p><p>[#site_name#]</p>','geodirectory')
    ),


    array(
        'name' => __('User forgot password email', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_forgot_password_subject',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('[#site_name#] - Your new password', 'geodirectory') // Default value for the page title - changed in settings
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_forgot_password_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __("<p>Dear [#client_name#],<p><p>You requested a new password for [#site_name_url#]</p><p>[#login_details#]</p><p>You can login here: [#login_url#]</p><p>Thank you,<br /><br />[#site_name_url#].</p>",'geodirectory')
    ),

    array(
        'name' => __('Registration success email', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_registration_success_email_subject',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('Your Log In Details', 'geodirectory') // Default value for the page title - changed in settings
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_registration_success_email_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __("<p>Dear [#client_name#],</p><p>You can log in  with the following information:</p><p>[#login_details#]</p><p>You can login here: [#login_url#]</p><p>Thank you,<br /><br />[#site_name_url#].</p>",'geodirectory')
    ),
    array(
        'name' => __('Listing published email', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_post_published_email_subject',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('Listing Published Successfully', 'geodirectory') // Default value for the page title - changed in settings
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_post_published_email_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __("<p>Dear [#client_name#],</p><p>Your listing [#listing_link#] has been published. This email is just for your information.</p><p>[#listing_link#]</p><br><p>Thank you for your contribution.</p><p>[#site_name#]</p>", 'geodirectory')
    ),

    array('type' => 'sectionend', 'id' => 'client_emails'),

    array('name' => __('Other Emails', 'geodirectory'), 'type' => 'title', 'desc' => '', 'id' => 'other_emails'),

    array('name' => __('Other Emails', 'geodirectory'), 'type' => 'sectionstart', 'id' => 'other_emails'),

    array(
        'name' => __('Send to friend', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_email_friend_subject',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('[#from_name#] thought you might be interested in..', 'geodirectory')
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_email_friend_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __("<p>Dear [#to_name#],<p><p>Your friend has sent you a message from <b>[#site_name#]</b> </p><p>===============================</p><p><b>Subject : [#subject#]</b></p><p>[#comments#] [#listing_link#]</p><p>===============================</p><p>Thank you,<br /><br />[#site_name#].</p>",'geodirectory')
    ),

    array(
        'name' => __('Email enquiry', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_email_enquiry_subject',
        'type' => 'text',
        'css' => 'min-width:300px;',
        'std' => __('Website Enquiry', 'geodirectory')
    ),
    array(
        'name' => '',
        'desc' => '',
        'id' => 'geodir_email_enquiry_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __("<p>Dear [#to_name#],<p><p>An enquiry has been sent from <b>[#listing_link#]</b></p><p>===============================</p><p>[#comments#]</p><p>===============================</p><p>Thank you,<br /><br />[#site_name_url#].</p>",'geodirectory')
    ),

    array('type' => 'sectionend', 'id' => 'other_emails'),


    array('name' => __('Messages', 'geodirectory'), 'type' => 'title', 'desc' => '', 'id' => 'messages'),

    array('name' => __('Messages', 'geodirectory'), 'type' => 'sectionstart', 'id' => 'messages'),

    array(
        'name' => __('Post submitted success', 'geodirectory'),
        'desc' => '',
        'id' => 'geodir_post_added_success_msg_content',
        'css' => 'width:500px; height: 150px;',
        'type' => 'textarea',
        'std' => __('<p>Thank you, your information has been successfully received.</p><p><a href="[#submited_information_link#]" >View your submitted information &raquo;</a></p><p>Thank you for visiting us at [#site_name#].</p>','geodirectory')
    ),


    array('type' => 'sectionend', 'id' => 'messages'),

)); // End Manage NOtifications settings
