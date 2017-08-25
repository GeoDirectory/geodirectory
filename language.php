<?php
/**
 * Language constants used in the plugin
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/*
 * NOTE: when editing text in this file, a single apostrophe ( ' ) can cause the site to break
 * Use an escaped apostrophe ( \' ) inside text in ALL cases
 * Good Example: define('EXAMPLE_TEXT',__('It\'s a nice day.','geodirectory'));
 * Bad Example define('EXAMPLE_TEXT',__('It's a nice day.','geodirectory'));
 */

//header_searchform.php
define('NEAR_TEXT', __('Near', 'geodirectory'));
define('SEARCH_FOR_TEXT', __('Search for', 'geodirectory'));
define('SEARCH_FOR_MSG', __('food, products or place', 'geodirectory'));
define('SEARCH_NEAR_MSG', __('Zip code or address', 'geodirectory'));
define('SEARCH', __('Search', 'geodirectory'));


/* ---- Add Listing ---- */

define('INDICATES_MANDATORY_FIELDS_TEXT', __('Indicates mandatory fields', 'geodirectory'));

define('LOGINORREGISTER', __("Login or Register", 'geodirectory'));
define('IAM_TEXT', __("I am", 'geodirectory'));
define('EXISTING_USER_TEXT', __("Existing User", 'geodirectory'));
define('NEW_USER_TEXT', __("New User? Register Now", 'geodirectory'));
define('LOGIN_TEXT', __('Login', 'geodirectory'));
define('SUBMIT_BUTTON', __('Submit', 'geodirectory'));


define('CONTACT_DETAIL_TITLE', __('Publisher Information', 'geodirectory'));
define('CONTACT_NAME_TEXT', __('Name', 'geodirectory'));
define('CONTACT_TEXT', __('Contact Number', 'geodirectory'));
define('EMAIL_TEXT', __('Email', 'geodirectory'));

define('LISTING_DETAILS_TEXT', __('Enter Listing Details', 'geodirectory'));

define('PLACE_TITLE_TEXT', __('Listing Title', 'geodirectory')); // depreciated @since 1.6.3
define('PLACE_DESC_TEXT', __('Listing Description', 'geodirectory'));// depreciated @since 1.6.3

define('LISTING_ADDRESS_TEXT', __('Listing Address:', 'geodirectory'));
define('PLACE_ADDRESS', __('Address:', 'geodirectory'));
define('ADDRESS_MSG', __('Please enter listing address. eg. : 230 Vine Street', 'geodirectory'));
define('PLACE_CITY_TEXT', __('City:', 'geodirectory'));
define('PLACE_REGION_TEXT', __('Region:', 'geodirectory'));
define('PLACE_COUNTRY_TEXT', __('Country:', 'geodirectory'));
define('PLACE_ZIP_TEXT', __('Zip/PostCode:', 'geodirectory'));
define('PLACE_ADDRESS_LAT', __('Address Latitude', 'geodirectory'));
define('PLACE_ADDRESS_LNG', __('Address Longitude', 'geodirectory'));
define('PLACE_MAP_VIEW', __('Google Map View', 'geodirectory'));

define('NOT_WITH_PACKAGE', __('Not available with the selected package.', 'geodirectory'));
define('NO_CATEGORY_LISTINGS', __('<b>No Listings here</b>', 'geodirectory'));

define('PRO_DESCRIPTION_TEXT', __('Listing Description', 'geodirectory'));
define('PRO_FEATURE_TEXT', __('Special Offers', 'geodirectory'));
define('PLACE_TIMING', __('Time', 'geodirectory'));
define('PLACE_CONTACT_INFO', __('Phone', 'geodirectory'));
define('PLACE_CONTACT_EMAIL', __('Email', 'geodirectory'));
define('WEBSITE_TEXT', __('Website', 'geodirectory'));
define('TWITTER_TEXT', __('Twitter', 'geodirectory'));
define('FACEBOOK_TEXT', __('Facebook', 'geodirectory'));

define('PLACE_CATEGORY_TEXT', __('Category', 'geodirectory'));
define('TAGKW_TEXT', __('Tag Keywords', 'geodirectory'));

define('PRO_PHOTO_TEXT', __('Add Images : <small>(You can upload images to create image gallery on detail page)</small>', 'geodirectory'));
define('PHOTOES_BUTTON', __('Select Images', 'geodirectory'));

define('PRO_VIDEO_TEXT', __('Video code', 'geodirectory'));
define('HTML_VIDEO_TEXT', __('Add video code here, YouTube etc', 'geodirectory'));

define('PRO_ADDFEATURE_TEXT', __('Add custom feature', 'geodirectory'));

define('MAP_NO_RESULTS', __('<h3>No Records Found</h3><p>Sorry, no records were found. Please adjust your search criteria and try again.</p>', 'geodirectory'));

define('LISTINGMAP_PAGE_VALIDATION_TEXT', __('<h3>No Record Found</h3><p>Sorry, This widget can only be used on listing page.</p>', 'geodirectory'));
define('SINGLEMAP_PAGE_VALIDATION_TEXT', __('<h3>No Record Found</h3><p>Sorry, This widget can only be used on place page.</p>', 'geodirectory'));


define('CAPTCHA_TITLE_TEXT', __('Captcha Verification', 'geodirectory'));
define('CAPTCHA', __('Word Verification', 'geodirectory'));


define('PRO_PREVIEW_BUTTON', __('Review Your Listing', 'geodirectory'));

define('GOING_TO_PAY_MSG', __('This is a preview of your listing, it has not been published yet. <br />If there is something wrong then "Go back and edit" or if you want to add the listing then click on "Publish".<br> You are going to pay <b>%s</b> &  alive days are <b>%s</b> as %s listing', 'geodirectory'));
define('GOING_TO_UPDATE_MSG', __('This is a preview of your listing, it has not been updated yet. <br />If there is something wrong then "Go back and edit" or if you want to update the listing then click on "Update now"', 'geodirectory'));
define('GOING_TO_FREE_MSG', __('This is a preview of your listing, it has not been published yet. <br />If there is something wrong then "Go back and edit" or if you want to add the listing then click on "Publish".<br> Your %s listing will published for <b>%s</b> days', 'geodirectory'));
define('UNLIMITED', __('Unlimited', 'geodirectory'));
define('BASIC_INFO_TEXT', __('Home Information', 'geodirectory'));
define('PRO_BACK_AND_EDIT_TEXT', __('&laquo; Go Back and Edit', 'geodirectory'));
define('PRO_UPDATE_BUTTON', __('Update Now', 'geodirectory'));
define('PRO_SUBMIT_BUTTON', __('Publish', 'geodirectory'));
define('PRO_CANCEL_BUTTON', __('Cancel', 'geodirectory'));


define('PUBLISH_DAYS_TEXT', __('%s : number of publish days are %s (<span id="%s">%s %s</span>)', 'geodirectory'));
define('SELECT_PAY_MEHTOD_TEXT', __('Select Payment Method', 'geodirectory'));


/* ---- favourite ---- */
define('FAVOURITE_TEXT', __('Favorite', 'geodirectory'));
define('UNFAVOURITE_TEXT', __('Unfavorite', 'geodirectory'));
define('MY_FAVOURITE_TEXT', __('My Favorites', 'geodirectory'));
define('ADD_FAVOURITE_TEXT', __('Add to Favorites', 'geodirectory'));
define('REMOVE_FAVOURITE_TEXT', __('Remove from Favorites', 'geodirectory'));
define('FAVOURITE_NOT_AVAIL_MSG', __('You have not added any favorites yet.', 'geodirectory'));


/* ---- Messages ---- */
define('REGISTRATION_DESABLED_MSG', __('New user registration disabled.', 'geodirectory'));
define('TIMING_MSG', __('Enter Business or Listing Timing Information. <br /> eg. : <b>10.00 am to 6 pm every day</b>', 'geodirectory'));
define('EMAIL_TEXT_MSG', __('Enter valid Email otherwise you will face an error.', 'geodirectory'));
define('GET_MAP_MSG', __('Click on "Set Address on Map" and then you can also drag pinpoint to locate the correct address', 'geodirectory'));
define('GET_LATITUDE_MSG', __('Please enter latitude for google map perfection. eg. : <b>39.955823048131286</b>', 'geodirectory'));
define('GET_LOGNGITUDE_MSG', __('Please enter longitude for google map perfection. eg. : <b>-75.14408111572266</b>', 'geodirectory'));
define('GEODIR_LATITUDE_ERROR_MSG', __('A numeric value is required. Please make sure you have either dragged the marker or clicked the button: Set Address On Map', 'geodirectory'));
define('GEODIR_LOGNGITUDE_ERROR_MSG', __('A numeric value is required. Please make sure you have either dragged the marker or clicked the button: Set Address On Map', 'geodirectory'));
define('CONTACT_MSG', __('You can enter phone number,cell phone number etc.', 'geodirectory'));
define('WEBSITE_MSG', __('Enter website URL. eg. : <b>http://myplace.com</b>', 'geodirectory'));
define('TWITTER_MSG', __('Enter twitter URL. eg. : <b>http://twitter.com/myplace</b>', 'geodirectory'));
define('FACEBOOK_MSG', __('Enter facebook URL. eg. : <b>http://facebook.com/myplace</b>', 'geodirectory'));
define('CATEGORY_MSG', __('Select listing category from here. Select at least one category', 'geodirectory'));
define('TAGKW_MSG', __('Tags are short keywords, with no space within.(eg: tag1, tag2, tag3) Up to 40 characters only.', 'geodirectory'));
define('HTML_TAGS_ALLOW_MSG', __('Note : Basic HTML tags are allowed', 'geodirectory'));
define('HTML_SPECIAL_TEXT', __('Note: List out any special offers (optional)', 'geodirectory'));
define('IMAGE_SAVE_ORDERING_MSG', __('Note : You can sort images once the post is saved by clicking on "Edit" on the listing', 'geodirectory'));


/* ---- Place Detail ---- */
define('SEND_TO_FRIEND', __('Send To Friend', 'geodirectory'));
define('VERIFY_PAGE_TITLE', __('Verify Listing', 'geodirectory'));
define('SEND_TO_FRIEND_SAMPLE_CONTENT', __('Hi there, check out this site, I think you might be interested in..', 'geodirectory'));
define('SEND_INQUIRY', __('Send Enquiry', 'geodirectory'));
define('SEND_INQUIRY_SAMPLE_CONTENT', __('Hi there, I would like to enquire about this place. I would like to ask more info about...', 'geodirectory'));
define('SEND_INQUIRY_SUCCESS', __('Enquiry sent successfully', 'geodirectory'));
define('SEND_FRIEND_SUCCESS', __('Email to Friend sent successfully', 'geodirectory'));
define('WRONG_CAPTCH_MSG', __('Enter correct verification code.', 'geodirectory'));

//comments.php
define('COMMENTS_TITLE_PLACE', __('Place Your Review', 'geodirectory'));
define('COMMENTS_TITLE_BLOG', __('Your Comments', 'geodirectory'));
define('RATING_MSG', __('Rate this place by clicking a star below :', 'geodirectory'));
define('COMMENT_TEXT', __('Comment', 'geodirectory'));
define('REVIEW_TEXT', __('Review', 'geodirectory'));
define('COMMENT_TEXT2', __('Comments', 'geodirectory'));
define('REVIEW_TEXT2', __('Reviews', 'geodirectory'));
define('REVIEW_SUBMIT_BTN', __('Submit Review', 'geodirectory'));
//comments_functions.php
define('OWNER_TEXT', __('Business Owner', 'geodirectory'));
define('SITE_ADMIN', __('Site Admin', 'geodirectory'));
define('COMMENT_TRACKBACKS', __('Trackbacks For This Post', 'geodirectory'));
define('COMMENT_MODERATION', __('Your review is awaiting moderation.', 'geodirectory'));
define('COMMENTS_CLOSED', __('Review are closed.', 'geodirectory'));
define('COMMENT_REPLY', __('Post Your Review', 'geodirectory'));
define('COMMENT_MUSTBE', __('You must be', 'geodirectory'));
define('COMMENT_LOGGED_IN', __('logged in', 'geodirectory'));
define('COMMENT_POST_REVIEW', __('to post a review.', 'geodirectory'));
define('COMMENT_LOGOUT', __('Logout', 'geodirectory'));
define('COMMENT_NAME', __('Name', 'geodirectory'));
define('COMMENT_EMAIL', __('Email', 'geodirectory'));
define('COMMENT_WEBSITE', __('Website', 'geodirectory'));
define('COMMENT_ADD_COMMENT', __('Add Comment', 'geodirectory'));
define('COMMENT_REPLY_NAME', __('Reply', 'geodirectory'));
define('COMMENT_EDIT_NAME', __('Edit', 'geodirectory'));
define('COMMENT_DELETE_NAME', __('Delete', 'geodirectory'));
define('COMMENT_SPAM_NAME', __('Spam', 'geodirectory'));
//widget_functios.php
define('ENTER_LOCATION_TEXT', __('Enter Your Location', 'geodirectory'));
define('READMORE_TEXT', __('Read More...', 'geodirectory'));
//dashboard.php
define('DASHBOARD_TEXT', __('Dashboard', 'geodirectory'));
define('EDIT_PROFILE_PAGE_TITLE', __('Edit Profile', 'geodirectory'));
define('CHANGE_PW_TEXT', __('Change Password', 'geodirectory'));
define('LOGOUT_TEXT', __('Logout', 'geodirectory'));
define('WELCOME_TEXT', __('Welcome', 'geodirectory'));


//registration.php
define('FORGOT_PW_TEXT', __('Forgot Password?', 'geodirectory'));
define('USERNAME_EMAIL_TEXT', __('E-mail', 'geodirectory'));
define('USERNAME_TEXT', __('Email', 'geodirectory'));
define('PASSWORD_TEXT', __('Password', 'geodirectory'));
define('CONFIRM_PASSWORD_TEXT', __('Confirm Password', 'geodirectory'));

define('PASSWORD_LENGTH_MSG', __('Enter your user password, must be 7 characters or more', 'geodirectory'));
define('PASSWORD_LENGTH_TEXT', __('Password must be 7 characters or more', 'geodirectory'));
define('PASSWORD_MATCH_TEXT', __('Passwords do not match', 'geodirectory'));

define('REMEMBER_ON_COMPUTER_TEXT', __('Remember me on this computer', 'geodirectory'));
define('GET_NEW_PW_TEXT', __('Get New Password', 'geodirectory'));

define('REGISTRATION_NOW_TEXT', __('Sign Up Now', 'geodirectory'));
define('PERSONAL_INFO_TEXT', __('Personal Information', 'geodirectory'));
define('FIRST_NAME_TEXT', __('Full Name', 'geodirectory'));
define('REGISTRATION_MESSAGE', __('(Note: A password will be e-mailed to you for future usage)', 'geodirectory'));
define('REGISTER_NOW_TEXT', __('Register Now', 'geodirectory'));
define('SIGN_IN_BUTTON', __('Sign In', 'geodirectory'));
define('REGISTER_BUTTON', __('Register', 'geodirectory'));
define('SIGN_IN_PAGE_TITLE', __('Sign In', 'geodirectory'));
define('INVALID_USER_PW_MSG', __('Invalid Username/Password.', 'geodirectory'));
define('REG_COMPLETE_MSG', __('Registration complete. Please check your e-mail for login details.', 'geodirectory'));
define('NEW_PW_EMAIL_MSG', __('We just sent you a new password. Kindly check your e-mail now.', 'geodirectory'));
define('EMAIL_CONFIRM_LINK_MSG', __('A confirmation link has been sent to you via email. Kindly check your e-mail now.', 'geodirectory'));
define('USER_REG_NOT_ALLOW_MSG', __('User registration has been disabled by the admin.', 'geodirectory'));
define('YOU_ARE_LOGED_OUT_MSG', __('You are now logged out.', 'geodirectory'));
define('ENTER_USER_EMAIL_NEW_PW_MSG', __('Please enter your e-mail address as username. You will receive a new password via e-mail.', 'geodirectory'));
define('INVALID_USER_FPW_MSG', __('Invalid Email, Please check', 'geodirectory'));
define('PW_SEND_CONFIRM_MSG', __('Check your e-mail for your new password.', 'geodirectory'));
define('AUTO_INSATALL_MSG', __('How many sample data you would like to populate on your site?', 'geodirectory'));
define('INSERT_BTN_SAMPLE_MSG', __('Insert sample data please', 'geodirectory'));
define('DELETE_BTN_SAMPLE_MSG', __('Yes Delete Please!', 'geodirectory'));
define('SAMPLE_DATA_SHOW_MSG', __('GeoDirectory sample data has been populated on your site. Wish to delete sample data?', 'geodirectory'));
define('GEODIR_SAMPLE_DATA_IMPORT_MSG', __('Please Wait! We are importing dummy data.', 'geodirectory'));
define('GEODIR_SAMPLE_DATA_DELETE_MSG', __('Please Wait! We are deleting dummy data.', 'geodirectory'));
define('READ_MORE_TXT', __('read more', 'geodirectory'));
define('POST_TYPE_DESELECT_MSG', __('Are you sure you want to deselect this post type?', 'geodirectory'));
define('DESIGN_POST_TYPE_SNO', __('S.No.', 'geodirectory'));
define('DESIGN_POST_TYPE', __('Post Type', 'geodirectory'));
define('DESIGN_POST_TYPE_CAT', __('Category', 'geodirectory'));
define('CSV_INSERT_DATA', __('Data inserted successfully.', 'geodirectory'));
define('CSV_TOTAL_RECORD', __('%s record(s) inserted.', 'geodirectory'));


define('CSV_INVALID_TOTAL_RECORD', __('%s out of %s record(s) could not be inserted due to blank address. Address fields post_address, post_country, post_region, post_city, post_latitude and post_longitude are mandatory fields.', 'geodirectory'));

define('CSV_INVALID_DEFUALT_ADDRESS', __('%s out of %s record(s) could not be inserted due to invalid address. You can import data in default location only as you do not have multilocation ad-on enabled.', 'geodirectory'));


define('CSV_INVALID_POST_TYPE', __('%s out of %s record(s) could not be inserted due to invalid/blank post type. Only use geodirectory\'s post type.', 'geodirectory'));


define('CSV_BLANK_POST_TITLE', __('%s out of %s record(s) could not be inserted due to blank post title.', 'geodirectory'));

define('CSV_TRANSFER_IMG_FOLDER', __("Please transfer all images in <b>'%s'</b> folder.", 'geodirectory'));
define('CSV_INVAILD_FILE', __('File you are uploading is not valid. First colum should be "Post Title".', 'geodirectory'));
define('CSV_UPLOAD_ONLY', __('Please upload CSV file only.', 'geodirectory'));
define('SELECT_CSV_FILE', __('Select CSV file to upload', 'geodirectory'));
define('SELECT_UPLOAD_CSV', __('Select & Upload CSV', 'geodirectory'));
define('CSV_IMPORT_DATA', __('Import data now', 'geodirectory'));
define('PLZ_SELECT_CSV_FILE', __('Please select csv file.', 'geodirectory'));

define('FEATURED_IMG_CLASS', __('featured_img_class', 'geodirectory'));

define('TAGKW_TEXT_COUNT', 40);


/*
 * Here we declare every country name so that is can be read by PO editors and translated easily.
 */
__('Afghanistan', 'geodirectory');
__('Albania', 'geodirectory');
__('Algeria', 'geodirectory');
__('American Samoa', 'geodirectory');
__('Andorra', 'geodirectory');
__('Angola', 'geodirectory');
__('Anguilla', 'geodirectory');
__('Antarctica', 'geodirectory');
__('Antigua and Barbuda', 'geodirectory');
__('Argentina', 'geodirectory');
__('Armenia', 'geodirectory');
__('Aruba', 'geodirectory');
__('Ashmore and Cartier', 'geodirectory');
__('Australia', 'geodirectory');
__('Austria', 'geodirectory');
__('Azerbaijan', 'geodirectory');
__('Bahrain', 'geodirectory');
__('Baker Island', 'geodirectory');
__('Bangladesh', 'geodirectory');
__('Barbados', 'geodirectory');
__('Bassas da India', 'geodirectory');
__('Belarus', 'geodirectory');
__('Belgium', 'geodirectory');
__('Belize', 'geodirectory');
__('Benin', 'geodirectory');
__('Bermuda', 'geodirectory');
__('Bhutan', 'geodirectory');
__('Bolivia', 'geodirectory');
__('Bosnia and Herzegovina', 'geodirectory');
__('Botswana', 'geodirectory');
__('Bouvet Island', 'geodirectory');
__('Brazil', 'geodirectory');
__('British Indian Ocean Territory', 'geodirectory');
__('British Virgin Islands', 'geodirectory');
__('Brunei Darussalam', 'geodirectory');
__('Bulgaria', 'geodirectory');
__('Burkina Faso', 'geodirectory');
__('Burma', 'geodirectory');
__('Burundi', 'geodirectory');
__('Cambodia', 'geodirectory');
__('Cameroon', 'geodirectory');
__('Canada', 'geodirectory');
__('Cape Verde', 'geodirectory');
__('Cayman Islands', 'geodirectory');
__('Central African Republic', 'geodirectory');
__('Chad', 'geodirectory');
__('Chile', 'geodirectory');
__('China', 'geodirectory');
__('Christmas Island', 'geodirectory');
__('Clipperton Island', 'geodirectory');
__('Cocos (Keeling) Islands', 'geodirectory');
__('Colombia', 'geodirectory');
__('Comoros', 'geodirectory');
__('Congo, Democratic Republic of the', 'geodirectory');
__('Congo, Republic of the', 'geodirectory');
__('Cook Islands', 'geodirectory');
__('Coral Sea Islands', 'geodirectory');
__('Costa Rica', 'geodirectory');
__('Cote d\'Ivoire', 'geodirectory');
__('Croatia', 'geodirectory');
__('Cuba', 'geodirectory');
__('Cyprus', 'geodirectory');
__('Czech Republic', 'geodirectory');
__('Czechia', 'geodirectory');
__('Denmark', 'geodirectory');
__('Djibouti', 'geodirectory');
__('Dominica', 'geodirectory');
__('Dominican Republic', 'geodirectory');
__('East Timor', 'geodirectory');
__('Ecuador', 'geodirectory');
__('Egypt', 'geodirectory');
__('El Salvador', 'geodirectory');
__('Equatorial Guinea', 'geodirectory');
__('Eritrea', 'geodirectory');
__('Estonia', 'geodirectory');
__('Ethiopia', 'geodirectory');
__('Europa Island', 'geodirectory');
__('Falkland Islands (Islas Malvinas)', 'geodirectory');
__('Faroe Islands', 'geodirectory');
__('Fiji', 'geodirectory');
__('Finland', 'geodirectory');
__('France', 'geodirectory');
__('France, Metropolitan', 'geodirectory');
__('French Guiana', 'geodirectory');
__('French Polynesia', 'geodirectory');
__('French Southern and Antarctic Lands', 'geodirectory');
__('Gabon', 'geodirectory');
__('Gaza Strip', 'geodirectory');
__('Georgia', 'geodirectory');
__('Germany', 'geodirectory');
__('Ghana', 'geodirectory');
__('Gibraltar', 'geodirectory');
__('Glorioso Islands', 'geodirectory');
__('Greece', 'geodirectory');
__('Greenland', 'geodirectory');
__('Grenada', 'geodirectory');
__('Guadeloupe', 'geodirectory');
__('Guam', 'geodirectory');
__('Guatemala', 'geodirectory');
__('Guernsey', 'geodirectory');
__('Guinea', 'geodirectory');
__('Guinea-Bissau', 'geodirectory');
__('Guyana', 'geodirectory');
__('Haiti', 'geodirectory');
__('Heard Island and McDonald Islands', 'geodirectory');
__('Holy See (Vatican City)', 'geodirectory');
__('Honduras', 'geodirectory');
__('Hong Kong (SAR)', 'geodirectory');
__('Howland Island', 'geodirectory');
__('Hungary', 'geodirectory');
__('Iceland', 'geodirectory');
__('India', 'geodirectory');
__('Indonesia', 'geodirectory');
__('Iran', 'geodirectory');
__('Iraq', 'geodirectory');
__('Ireland', 'geodirectory');
__('Israel', 'geodirectory');
__('Italy', 'geodirectory');
__('Jamaica', 'geodirectory');
__('Jan Mayen', 'geodirectory');
__('Japan', 'geodirectory');
__('Jarvis Island', 'geodirectory');
__('Jersey', 'geodirectory');
__('Johnston Atoll', 'geodirectory');
__('Jordan', 'geodirectory');
__('Juan de Nova Island', 'geodirectory');
__('Kazakhstan', 'geodirectory');
__('Kenya', 'geodirectory');
__('Kingman Reef', 'geodirectory');
__('Kiribati', 'geodirectory');
__('Korea, North', 'geodirectory');
__('Korea, South', 'geodirectory');
__('Kuwait', 'geodirectory');
__('Kyrgyzstan', 'geodirectory');
__('Laos', 'geodirectory');
__('Latvia', 'geodirectory');
__('Lebanon', 'geodirectory');
__('Lesotho', 'geodirectory');
__('Liberia', 'geodirectory');
__('Libya', 'geodirectory');
__('Liechtenstein', 'geodirectory');
__('Lithuania', 'geodirectory');
__('Luxembourg', 'geodirectory');
__('Macao', 'geodirectory');
__('Macedonia, The Former Yugoslav Republic of', 'geodirectory');
__('Madagascar', 'geodirectory');
__('Malawi', 'geodirectory');
__('Malaysia', 'geodirectory');
__('Maldives', 'geodirectory');
__('Mali', 'geodirectory');
__('Malta', 'geodirectory');
__('Man, Isle of', 'geodirectory');
__('Marshall Islands', 'geodirectory');
__('Martinique', 'geodirectory');
__('Mauritania', 'geodirectory');
__('Mauritius', 'geodirectory');
__('Mayotte', 'geodirectory');
__('Mexico', 'geodirectory');
__('Micronesia, Federated States of', 'geodirectory');
__('Midway Islands', 'geodirectory');
__('Miscellaneous (French)', 'geodirectory');
__('Moldova', 'geodirectory');
__('Monaco', 'geodirectory');
__('Mongolia', 'geodirectory');
__('Montenegro', 'geodirectory');
__('Montserrat', 'geodirectory');
__('Morocco', 'geodirectory');
__('Mozambique', 'geodirectory');
__('Myanmar', 'geodirectory');
__('Namibia', 'geodirectory');
__('Nauru', 'geodirectory');
__('Navassa Island', 'geodirectory');
__('Nepal', 'geodirectory');
__('Netherlands', 'geodirectory');
__('Netherlands Antilles', 'geodirectory');
__('New Caledonia', 'geodirectory');
__('New Zealand', 'geodirectory');
__('Nicaragua', 'geodirectory');
__('Niger', 'geodirectory');
__('Nigeria', 'geodirectory');
__('Niue', 'geodirectory');
__('Norfolk Island', 'geodirectory');
__('Northern Mariana Islands', 'geodirectory');
__('Norway', 'geodirectory');
__('Oman', 'geodirectory');
__('Pakistan', 'geodirectory');
__('Palau', 'geodirectory');
__('Palestinian Territory, Occupied', 'geodirectory');
__('Palmyra Atoll', 'geodirectory');
__('Panama', 'geodirectory');
__('Papua New Guinea', 'geodirectory');
__('Paracel Islands', 'geodirectory');
__('Paraguay', 'geodirectory');
__('Peru', 'geodirectory');
__('Philippines', 'geodirectory');
__('Pitcairn Islands', 'geodirectory');
__('Poland', 'geodirectory');
__('Portugal', 'geodirectory');
__('Puerto Rico', 'geodirectory');
__('Qatar', 'geodirectory');
__('Romania', 'geodirectory');
__('Russia', 'geodirectory');
__('Rwanda', 'geodirectory');
__('Saint Helena', 'geodirectory');
__('Saint Kitts and Nevis', 'geodirectory');
__('Saint Lucia', 'geodirectory');
__('Saint Pierre and Miquelon', 'geodirectory');
__('Saint Vincent and the Grenadines', 'geodirectory');
__('Samoa', 'geodirectory');
__('San Marino', 'geodirectory');
__('Saudi Arabia', 'geodirectory');
__('Senegal', 'geodirectory');
__('Serbia', 'geodirectory');
__('Serbia and Montenegro', 'geodirectory');
__('Seychelles', 'geodirectory');
__('Sierra Leone', 'geodirectory');
__('Singapore', 'geodirectory');
__('Slovakia', 'geodirectory');
__('Slovenia', 'geodirectory');
__('Solomon Islands', 'geodirectory');
__('Somalia', 'geodirectory');
__('South Africa', 'geodirectory');
__('South Georgia and the South Sandwich Islands', 'geodirectory');
__('Spain', 'geodirectory');
__('Spratly Islands', 'geodirectory');
__('Sri Lanka', 'geodirectory');
__('Sudan', 'geodirectory');
__('Suriname', 'geodirectory');
__('Svalbard', 'geodirectory');
__('Swaziland', 'geodirectory');
__('Sweden', 'geodirectory');
__('Switzerland', 'geodirectory');
__('Syria', 'geodirectory');
__('Taiwan', 'geodirectory');
__('Tajikistan', 'geodirectory');
__('Tanzania', 'geodirectory');
__('Thailand', 'geodirectory');
__('The Bahamas', 'geodirectory');
__('The Gambia', 'geodirectory');
__('Togo', 'geodirectory');
__('Tokelau', 'geodirectory');
__('Tonga', 'geodirectory');
__('Trinidad and Tobago', 'geodirectory');
__('Tromelin Island', 'geodirectory');
__('Tunisia', 'geodirectory');
__('Turkey', 'geodirectory');
__('Turkmenistan', 'geodirectory');
__('Turks and Caicos Islands', 'geodirectory');
__('Tuvalu', 'geodirectory');
__('Uganda', 'geodirectory');
__('Ukraine', 'geodirectory');
__('United Arab Emirates', 'geodirectory');
__('United Kingdom', 'geodirectory');
__('United States', 'geodirectory');
__('United States Minor Outlying Islands', 'geodirectory');
__('Uruguay', 'geodirectory');
__('Uzbekistan', 'geodirectory');
__('Vanuatu', 'geodirectory');
__('Venezuela', 'geodirectory');
__('Vietnam', 'geodirectory');
__('Virgin Islands', 'geodirectory');
//__('Virgin Islands (UK)','geodirectory'); // duplicate removed
//__('Virgin Islands (US)','geodirectory'); // duplicate removed
__('Wake Island', 'geodirectory');
__('Wallis and Futuna', 'geodirectory');
__('West Bank', 'geodirectory');
__('Western Sahara', 'geodirectory');
__('Western Samoa', 'geodirectory');
__('Yemen', 'geodirectory');
__('Yugoslavia', 'geodirectory');
__('Zaire', 'geodirectory');
__('Zambia', 'geodirectory');
__('Zimbabwe', 'geodirectory');
__('Curaçao', 'geodirectory');
__('Caribbean Netherlands', 'geodirectory');
