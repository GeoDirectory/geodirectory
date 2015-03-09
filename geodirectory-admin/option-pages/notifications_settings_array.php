<?php

global $geodir_settings;


$geodir_settings['notifications_settings'] = apply_filters('geodir_notifications_settings', array(

	
	array( 'name' => __( 'Options', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'notification_options' ),
	
	
	array( 'name' => __( 'Notification Options', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'id' => 'notification_options'),
	
	array(  
		'name' => __( 'List of usable shortcodes', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( '[#client_name#],[#listing_link#],[#posted_date#],[#number_of_days#],[#number_of_grace_days#],[#login_url#],[#username#],[#user_email#],[#site_name_url#],[#renew_link#],[#post_id#],[#site_name#]', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_list_of_usable_shordcodes',
		'type' 		=> 'html_content',
		'css' 		=> 'min-width:300px;',
		'std' 		=> 'All Places' // Default value for the page title - changed in settings
	),
	
	array(  
		'name' => __( 'Use advanced editor? (slow loading)', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Yes', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_tiny_editor',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '1',
		'radiogroup'		=> 'start'
	),
	array(  
		'name' => __( 'Use advanced editor?(slow loading)', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'No', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_tiny_editor',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '0',
		'radiogroup'		=> 'end'
	),
	
	
	array( 'type' => 'sectionend', 'id' => 'notification_options'),
	
	
	array( 	'name' => __( 'Site Bcc Options', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'site_bcc_options' ),
	
	array( 'name' => __( 'Site Bcc Options', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'id' => 'site_bcc_options'),
	
	array(  
		'name' => __( 'New user registration', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Yes', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_bcc_new_user',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '1',
		'radiogroup'		=> 'start'
	),
	array(  
		'name' => __( 'New user registration', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'No', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_bcc_new_user',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '0',
		'radiogroup'		=> 'end'
	),
	
	array(  
		'name' => __( 'Send to friend', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Yes', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_bcc_friend',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '1',
		'radiogroup'		=> 'start'
	),
	array(  
		'name' => __( 'Send to friend', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'No', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_bcc_friend',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '0',
		'radiogroup'		=> 'end'
	),
	
	array(  
		'name' => __( 'Send enquiry', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'Yes', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_bcc_enquiry',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '1',
		'radiogroup'		=> 'start'
	),
	array(  
		'name' => __( 'Send enquiry', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> __( 'No', GEODIRECTORY_TEXTDOMAIN ),
		'id' 		=> 'geodir_bcc_enquiry',
		'std' 		=> 'yes',
		'type' 		=> 'radio',
		'value'		=> '0',
		'radiogroup'		=> 'end'
	),
	
	
	array( 'type' => 'sectionend', 'id' => 'site_bcc_options'),
	
	
	
	array( 	'name' => __( 'Admin Emails', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'admin_emails' ),
	
	array( 'name' => __( 'Admin Emails', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'id' => 'admin_emails'),
	
	array(  
		'name' => __( 'Post submit success to admin email', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_post_submited_success_email_subject_admin',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> 'Post Submitted Successfully' // Default value for the page title - changed in settings
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_post_submited_success_email_content_admin',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  '<p>Dear Admin,</p><p>A new  listing has been published [#listing_link#]. This email is just for your information.</p><br><p>[#site_name#]</p>'
	),
	

	array( 'type' => 'sectionend', 'id' => 'admin_emails'),

	
	array( 'name' => __( 'Client Emails', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'client_emails' ),

	array( 'name' => __( 'Client Emails', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'id' => 'client_emails'),
	
	array(  
		'name' => __( 'Post submit success to client email', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_post_submited_success_email_subject',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> 'Post Submitted Successfully' // Default value for the page title - changed in settings
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_post_submited_success_email_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  '<p>Dear [#client_name#],</p><p>You submitted the below listing information. This email is just for your information.</p><p>[#listing_link#]</p><br><p>Thank you for your contribution.</p><p>[#site_name#]</p>'
	),
	
	
	array(  
		'name' => __( 'User forgot password email', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_forgot_password_subject',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> '[#site_name#] - Your new password' // Default value for the page title - changed in settings
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_forgot_password_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  "<p>Dear [#client_name#],<p><p>You requested a new password for [#site_name_url#]</p><p>[#login_details#]</p><p>You can login here: [#login_url#]</p><p>Thank you,<br /><br />[#site_name_url#].</p>"
	),
	
	array(  
		'name' => __( 'Registration success email', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_registration_success_email_subject',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> 'Your Log In Details' // Default value for the page title - changed in settings
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_registration_success_email_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  "<p>Dear [#client_name#],</p><p>You can log in  with the following information:</p><p>[#login_details#]</p><p>You can login here: [#login_url#]</p><p>Thank you,<br /><br />[#site_name_url#].</p>"
	),
	array(  
		'name' => __( 'Listing published email', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_post_published_email_subject',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> __('Listing Published Successfully', GEODIRECTORY_TEXTDOMAIN) // Default value for the page title - changed in settings
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_post_published_email_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  __("<p>Dear [#client_name#],</p><p>Your listing [#listing_link#] has been published. This email is just for your information.</p><p>[#listing_link#]</p><br><p>Thank you for your contribution.</p><p>[#site_name#]</p>", GEODIRECTORY_TEXTDOMAIN)
	),
	
	array( 'type' => 'sectionend', 'id' => 'client_emails'),
	
	array( 'name' => __( 'Other Emails', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'other_emails' ),

	array( 'name' => __( 'Other Emails', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'sectionstart', 'id' => 'other_emails'),
	
	array(  
		'name' => __( 'Send to friend', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_email_friend_subject',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> '[#from_name#] thought you might be interested in..'
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_email_friend_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  "<p>Dear [#to_name#],<p><p>Your friend has sent you a message from <b>[#site_name#]</b> </p><p>===============================</p><p><b>Subject : [#subject#]</b></p><p>[#comments#] [#listing_link#]</p><p>===============================</p><p>Thank you,<br /><br />[#site_name#].</p>"
	),
	
	array(  
		'name' => __( 'Email enquiry', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_email_enquiry_subject',
		'type' 		=> 'text',
		'css' 		=> 'min-width:300px;',
		'std' 		=> __('Website Enquiry',GEODIRECTORY_TEXTDOMAIN)
	),
	array(  
		'name' => '',
		'desc' 		=> '',
		'id' 		=> 'geodir_email_enquiry_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  "<p>Dear [#to_name#],<p><p>An enquiry has been sent from <b>[#listing_link#]</b></p><p>===============================</p><p>[#comments#]</p><p>===============================</p><p>Thank you,<br /><br />[#site_name_url#].</p>"
	),
	
	array( 'type' => 'sectionend', 'id' => 'other_emails'),
	
	
	
	array( 'name' => __( 'Messages', GEODIRECTORY_TEXTDOMAIN ), 'type' => 'title', 'desc' => '', 'id' => 'messages' ),
	
	array('name' => __( 'Messages', GEODIRECTORY_TEXTDOMAIN ),  'type' => 'sectionstart', 'id' => 'messages'),
	
	array(  
		'name' => __( 'Post submitted success', GEODIRECTORY_TEXTDOMAIN ),
		'desc' 		=> '',
		'id' 		=> 'geodir_post_added_success_msg_content',
		'css' 		=> 'width:500px; height: 150px;',
		'type' 		=> 'textarea',
		'std' 		=>  '<p>Thank you, your information has been successfully received.</p><p><a href="[#submited_information_link#]" >View your submitted information &raquo;</a></p><p>Thank you for visiting us at [#site_name#].</p>'
	),
	
	
	array( 'type' => 'sectionend', 'id' => 'messages'),
	
)); // End Manage NOtifications settings

//$_SESSION['geodir_settings']['notifications_settings'] = $geodir_settings['notifications_settings'];