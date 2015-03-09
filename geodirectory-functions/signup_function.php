<?php 

function geodir_is_login($redirect = false){
	global $current_user; 
	if(!$current_user->ID){
		if($redirect){
			?>
			<script type="text/javascript" > 
				window.location.href = '<?php echo home_url().'?geodir_signup=true';?>';
            </script>
			<?php
		}else
			return false;	
	}else
		return true;
}

function geodir_check_ssl(){

// Redirect to https login if forced to use SSL
if ( force_ssl_admin() && !is_ssl() ) {
	if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
		wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']));
		exit();
	} else {
		wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		exit();
	}
}

	$message = apply_filters('login_message', $message);
	if ( !empty( $message ) ) echo $message . "\n";

}

function geodir_get_site_email_id()
{
	if(get_option('site_email'))

	{

		return get_option('site_email');

	}else

	{

		return get_option('admin_email');

	}

}


if (!function_exists('get_site_emailName')) {
function get_site_emailName()

{

	if(get_option('site_email_name'))

	{

		return stripslashes(get_option('site_email_name'));

	}else

	{

		return stripslashes(get_option('blogname'));

	}

}}

if (!function_exists('is_allow_user_register')) {
function is_allow_user_register()

{

	return get_option('users_can_register');

}}

/**
 * Handles sending password retrieval email to user.
 *
 * @uses $wpdb WordPress Database object
 *
 * @return bool|WP_Error True: when finish. WP_Error on error
 */
function geodir_retrieve_password() {
	global $wpdb;

	$errors = new WP_Error();
	if ( empty( $_POST['user_login'] ) && empty( $_POST['user_email'] ) )
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.',GEODIRECTORY_TEXTDOMAIN));

	if ( strpos($_POST['user_login'], '@') ) {
		//$user_data = get_user_by_email(trim($_POST['user_login']));
		$user_data = get_user_by( 'email', trim($_POST['user_login']) );
		if ( empty($user_data) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.',GEODIRECTORY_TEXTDOMAIN));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('email', $login);
	}

	do_action('lostpassword_post');

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.',GEODIRECTORY_TEXTDOMAIN));
		return $errors;
	}

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action('retreive_password', $user_login);  // Misspelled and deprecated
	do_action('retrieve_password', $user_login);

	////////////////////////////////////
	$user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
	$user_login = $_POST['user_login'];
	
	$user =	$wpdb->get_row(
						$wpdb->prepare(
							"SELECT * FROM $wpdb->users WHERE user_login like %s or user_email like %s",
							array($user_login,$user_login)
						)
					);
	
	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key',GEODIRECTORY_TEXTDOMAIN));
		
	$new_pass = wp_generate_password(12,false);

	do_action('password_reset', $user, $new_pass);

	wp_set_password($new_pass, $user->ID);
	update_user_meta($user->ID, 'default_password_nag', true); //Set up the Password change nag.
	$message  = '<p><b>'.__('Your login Information :',GEODIRECTORY_TEXTDOMAIN).'</b></p>';
	$message  .= '<p>'.sprintf(__('Username: %s',GEODIRECTORY_TEXTDOMAIN), $user->user_login) . "</p>";
	$message .= '<p>'.sprintf(__('Password: %s',GEODIRECTORY_TEXTDOMAIN), $new_pass) . "</p>";
	//$message .= '<p>You can login to : <a href="'.home_url().'/?ptype=login' . "\">Login</a> or the URL is :  ".home_url()."/?ptype=login</p>";
	//$message .= '<p>Thank You,<br> '.get_option('blogname').'</p>';
	$user_email = $user_data->user_email;
	$user_name = $user_data->user_nicename;
	$fromEmail = geodir_get_site_email_id();
	$fromEmailName = get_site_emailName();
	$title = sprintf(__('[%s] Your new password',GEODIRECTORY_TEXTDOMAIN), get_option('blogname'));
	$title = apply_filters('password_reset_title', $title);
	$message = apply_filters('password_reset_message', $message, $new_pass);
	//geodir_sendEmail($fromEmail,$fromEmailName,$user_email,$user_name,$title,$message,$extra='');///forgot password email
	geodir_sendEmail($fromEmail,$fromEmailName,$user_email,$user_name,$title,$message,$extra='','forgot_password',$post_id='',$user->ID);///forgot password email

	return true;
}

/**
 * Handles registering a new user.
 *
 * @param string $user_login User's username for logging in
 * @param string $user_email User's email address to send password and add
 * @return int|WP_Error Either user's ID or error on failure.
 */
function geodir_register_new_user($user_login, $user_email) {
	global $wpdb;
	$errors = new WP_Error();


	$user_login = sanitize_user( $user_login );
	$user_login = str_replace(",", "", $user_login);
	$user_email = str_replace(",", "", $user_email);
	$user_email = apply_filters( 'user_registration_email', $user_email );


	if(get_option('geodir_allow_cpass')){
	$user_pass  = $_REQUEST['user_pass'] ;
	$user_pass2 = $_REQUEST['user_pass2'] ;
	// Check the password
	if ( $user_pass != $user_pass2){
		$errors->add('pass_match', __('ERROR: Passwords do not match.',GEODIRECTORY_TEXTDOMAIN));
		}
	elseif(strlen($user_pass)<7){
	$errors->add('pass_match', __('ERROR: Password must be 7 characters or more.',GEODIRECTORY_TEXTDOMAIN));
	}}

	// Check the username
	if ( $user_login == '' )
		$errors->add('empty_username', __('ERROR: Please enter a username.',GEODIRECTORY_TEXTDOMAIN));
	elseif ( !validate_username( $user_login ) ) {
		$errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.',GEODIRECTORY_TEXTDOMAIN));
		$user_login = '';
	} elseif ( username_exists( $user_login ) )
		$errors->add('username_exists', __('<strong>ERROR</strong>: This username is already registered, please choose another one.',GEODIRECTORY_TEXTDOMAIN));

	// Check the e-mail address
	if ($user_email == '') {
		$errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.',GEODIRECTORY_TEXTDOMAIN));
	} elseif ( !is_email( $user_email ) ) {
		$errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.',GEODIRECTORY_TEXTDOMAIN));
		$user_email = '';
	} elseif ( email_exists( $user_email ) )
		$errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.',GEODIRECTORY_TEXTDOMAIN));

	do_action('register_post', $user_login, $user_email, $errors);

	$errors = apply_filters( 'registration_errors', $errors );

	if ( $errors->get_error_code() )
		return $errors;

	
	if(!isset($user_pass) || $user_pass == ''){$user_pass = wp_generate_password(12,false);}
	$user_id = wp_create_user( $user_login, $user_pass, $user_email );
	$user_web = '';
	/*$user_add1 = $_POST['user_add1'];
	$user_add2 = $_POST['user_add2'];
	$user_city = $_POST['user_city'];
	$user_state = $_POST['user_state'];
	$user_country = $_POST['user_country'];
	$user_postalcode = $_POST['user_postalcode'];
	$user_web = $_POST['user_web'];
	$user_phone = $_POST['user_phone'];
	$user_twitter = $_POST['user_twitter'];	*/
	$user_fname = sanitize_user($_POST['user_fname']);
	$user_fname = str_replace(",", "", $user_fname);
	
	$user_address_info = apply_filters('geodir_manage_user_meta' , array(
						"user_add1"		=> '',
						"user_add2"		=> '',
						"user_city"		=> '',
						"user_state"	=> '',
						"user_country"	=> '',
						"user_postalcode"=> '',
						"user_phone"	=>	'',
						"user_twitter"	=>	'',
						"first_name"	=>	$user_fname,
						"last_name"		=>	'',
						) , $user_id );
	foreach($user_address_info as $key=>$val)
	{
		update_user_meta($user_id, $key, $val); // User Address Information Here
	}
	//update_user_meta($user_id, 'user_address_info', ($user_address_info)); // User Address Information Here
	$userName = $user_fname;
	update_user_meta($user_id, 'first_name', $userName); // User Address Information Here
	//update_user_meta($user_id, 'last_name', $_POST['user_lname']); // User Address Information Here
	
	// Changed by vikas sharma to enable all type of characters in author permalink...
		$user_nicename = sanitize_title($userName);
	
	$updateUsersql = $wpdb->prepare("update $wpdb->users set user_url=%s, user_nicename=%s, display_name=%s  where ID=%d", array($user_web,$user_nicename,$userName,$user_id));
	
	$wpdb->query($updateUsersql);
	
	if ( !$user_id ) {
		$errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !',GEODIRECTORY_TEXTDOMAIN), get_option('admin_email')));
		return $errors;
	}
	global $upload_folder_path;
	
	if ( $user_id ) 
	{
		do_action('geodir_user_register',$user_id);
		///////REGISTRATION EMAIL START//////
		$fromEmail = geodir_get_site_email_id();
		$fromEmailName = get_site_emailName();
		$message = __('<p><b>'.__('Your login Information :',GEODIRECTORY_TEXTDOMAIN).'</b></p>
<p>'.__('Username:',GEODIRECTORY_TEXTDOMAIN).' '.$user_login.'</p>
<p>'.__('Password:',GEODIRECTORY_TEXTDOMAIN).' '.$user_pass.'</p>');
		
		/////////////customer email//////////////
		//geodir_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$client_message,$extra='');///To client email
		geodir_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,'',$message,$extra='','registration',$post_id='','');/// registration email
		//////REGISTRATION EMAIL END////////
	}
	
	if(get_option('ptthemes_auto_login')){
	$errors->add('auto_login', __('<strong>SUCCESS</strong>: Thank you for registering, please check your email for your login details.',GEODIRECTORY_TEXTDOMAIN));
	return $errors;}
	
	return array($user_id,$user_pass);
}

function geodir_user_signup(){
	global $errors;
	
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';
	
	$errors = new WP_Error();
	
	if ( isset($_GET['key']) )
		$action = 'resetpass';
	
	// validate action so as to default to the login screen
	if ( !in_array($action, array('logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register', 'login')) && false === has_filter('login_form_' . $action) )
		$action = 'login';
	
	nocache_headers();
	
	if ( defined('RELOCATE') ) { // Move flag is set
		if ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
			$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );
	
		$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
		if ( dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != home_url() )
			update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) );
	}
	
	//Set a cookie now to see if they are supported by the browser.
	//setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
	if ( SITECOOKIEPATH != COOKIEPATH )
		setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);
	
	// allow plugins to override the default actions, and to add extra actions if they want
	do_action('login_form_' . $action);
	
	$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
	
	switch ($action):
		
		case 'logout' :
			//check_admin_referer('log-out');
			wp_logout();
		
			$redirect_to =  $_SERVER['HTTP_REFERER'];
			//$redirect_to = home_url().'/?ptype=login&loggedout=true';
			if ( isset( $_REQUEST['redirect_to'] ) )
				$redirect_to = $_REQUEST['redirect_to'];
			$redirect_to = home_url();
			wp_safe_redirect($redirect_to);
			exit();
		
		break;
		
		case 'lostpassword' :
		case 'retrievepassword' :
			if ( $http_post ) {
				$errors = geodir_retrieve_password();
				$error_message = isset($errors->errors['invalid_email'][0]) ? $errors->errors['invalid_email'][0] : '';
				if ( !is_wp_error($errors) ) {
					wp_redirect(home_url().'/?geodir_signup=true&amp;checkemail=confirm');
					exit();
				}else
				{
					wp_redirect(home_url().'/?geodir_signup=true&emsg=fw');
					exit();
				}
			}
			if ( isset($_GET['error']) && 'invalidkey' == $_GET['error'] ) $errors->add('invalidkey', __('Sorry, that key does not appear to be valid.',GEODIRECTORY_TEXTDOMAIN));
			do_action('lost_password');
			$message = '<div class="sucess_msg">'.ENTER_USER_EMAIL_NEW_PW_MSG.'</div>';
			$user_login = isset($_POST['user_login']) ? stripslashes($_POST['user_login']) : '';
		
		break;
		
		case 'resetpass' :
		case 'rp' :
			$errors = reset_password($_GET['key'], $_GET['login']);
		
			if ( ! is_wp_error($errors) ) {
				wp_redirect(home_url().'/?geodir_signup=true&amp;action=login&amp;checkemail=newpass');
				exit();
			}
		
			wp_redirect(home_url().'/?geodir_signup=true&amp;action=lostpassword&amp;page1=sign_in&amp;error=invalidkey');
			exit();
		
		break;
		
		case 'register' :
		############################### fix by Stiofan -  HebTech.co.uk ### SECURITY FIX ##############################
			if ( !get_option('users_can_register') ) {
				wp_redirect(home_url().'?geodir_signup=true&emsg=regnewusr');
				exit();
			}
		############################### fix by Stiofan -  HebTech.co.uk ### SECURITY FIX ##############################
			global $user_email, $user_fname;
			$user_login = '';
			$user_email = '';
			if ( $http_post ) {
				$user_login = $_POST['user_email'];
				$user_email = $_POST['user_email'];
				$user_fname = $_POST['user_fname'];
				
				$errors = geodir_register_new_user($user_login, $user_email);
				
				/* display error in registration form */
				if ( is_wp_error($errors) ) {
					$error_code 	= $errors->get_error_code();
					$error_message	= $errors->get_error_message( $error_code );
					if ( !isset( $_POST['user_login'] ) && ( $error_code == 'empty_username' || $error_code == 'invalid_username' || $error_code == 'username_exists' ) ) {
						if ( $error_code == 'empty_username' ) {
							$error_code = 'empty_email';
						} else if ( $error_code == 'invalid_username' ) {
							$error_code = 'invalid_email';
						} else if ( $error_code == 'username_exists' ) {
							$error_code = 'email_exists';
						}
						
						$error_message	= $errors->get_error_message( $error_code );
					}
					global $geodir_signup_error;
					$geodir_signup_error = $error_message;
				}
			
				if ( !is_wp_error($errors) ) 
				{
					$_POST['log'] = $user_login;
					$_POST['pwd'] = $errors[1];
					$_POST['testcookie'] = 1;
					
					$secure_cookie = '';
					// If the user wants ssl but the session is not ssl, force a secure cookie.
					if ( !empty($_POST['log']) && !force_ssl_admin() )
					{
						$user_name = sanitize_user($_POST['log']);
						if ( $user = get_user_by('email',$user_name) )
						{
							if ( get_user_option('use_ssl', $user->ID) )
							{
								$secure_cookie = true;
								force_ssl_admin(true);
							}
						}
					}
					
					$redirect_to = $_REQUEST['redirect_to'];
					
					if(!isset($_REQUEST['redirect_to']) || $_REQUEST['redirect_to']=='')
					{
						if(isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],home_url()))
						{
							$redirect_to = $_SERVER['HTTP_REFERER'];
						}else{
							$redirect_to = home_url();
						}
						
					}
					
					if(isset($_REQUEST['redirect_add_listing']) || $_REQUEST['redirect_add_listing']!=''){
						
						$redirect_to = $_REQUEST['redirect_add_listing'];
					}
					
					
					if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
						$secure_cookie = false;
				
					$user = wp_signon('', $secure_cookie);
				
					if ( !is_wp_error($user) ) 
					{
						wp_safe_redirect($redirect_to);
						exit();
					}
					exit();
				}
			}
		
		break;
		
		case 'login' :
		default:
			$secure_cookie = '';
		
			if ( !empty($_POST['log']) && !force_ssl_admin() ) {
				$user_name = sanitize_user($_POST['log']);
				if ( $user = get_user_by('login', $user_name) ) {		
		
					if ( get_user_option('use_ssl', $user->ID) ) {
						$secure_cookie = true;
						force_ssl_admin(true);
					}
				}elseif ( $user = get_user_by('email', $user_name) ) {		
					$_POST['log']=$user->user_login; // If signing in by email, set the username for normal WP login
					if ( get_user_option('use_ssl', $user->ID) ) {
						$secure_cookie = true;
						force_ssl_admin(true);
					}
				}
			}
			///////////////////////////
			if(isset($_REQUEST['redirect_add_listing']) ){
				$_REQUEST['redirect_to'] = $_REQUEST['redirect_add_listing'];
			}
			
			
			if(!isset($_REQUEST['redirect_to']) || $_REQUEST['redirect_to']=='')
			{
				if ( is_user_logged_in() ) :
					 $user_ID  = isset( $user->ID ) ?  $user->ID : '';
					$author_link = get_author_posts_url( $user_ID );
					$default_author_link = geodir_getlink($author_link,array('geodir_dashbord'=>'true','stype'=>'gd_place'),false);
					
					// author page link
					$default_author_link = apply_filters( 'geodir_dashboard_author_link', $default_author_link, $user_ID );
					
					$_REQUEST['redirect_to']=$default_author_link;
				else:
					$_REQUEST['redirect_to']= home_url();
				endif;
				
			}
			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_to = $_REQUEST['redirect_to'];
				// Redirect to https if user wants ssl
				if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
					$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
			} else {
				$redirect_to = admin_url();
			}
		
			if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
				$secure_cookie = false;
		
			$user = wp_signon('', $secure_cookie);
		
			$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);
		
			if(is_wp_error($user))
			{
				if(isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'],'ptype=property_submit') && $_POST['log']!='' && $_POST['pwd']!='')
				{
					wp_redirect($_SERVER['HTTP_REFERER'].'&emsg=1');
				}
			}
			if ( !is_wp_error($user) ) {
				if($redirect_to)
				{
					wp_redirect($redirect_to);
				}else
				{
					wp_redirect(home_url());
				}	exit();
			}
		
			$errors = $user;
			
			// Clear errors if loggedout is set.
			if ( !empty($_GET['loggedout']) )
				$errors = new WP_Error();
			// If cookies are disabled we can't log in even with a valid user+pass
			if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
				$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress.",GEODIRECTORY_TEXTDOMAIN));
		
			// Some parts of this script use the main login form to display a message
			if( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )
			{
				$successmsg = '<div class="sucess_msg">'.YOU_ARE_LOGED_OUT_MSG.'</div>';
			}
			elseif( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )
			{
				$successmsg = USER_REG_NOT_ALLOW_MSG;
			}
			elseif( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )
			{
				$successmsg = EMAIL_CONFIRM_LINK_MSG;
			}
			elseif( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )
			{
				$successmsg = NEW_PW_EMAIL_MSG;
			}
			elseif( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
			{
				$successmsg = REG_COMPLETE_MSG;
			}
			
			if((isset($_POST['log']) && $_POST['log'] != '' && $errors) || ((!isset($_POST['log']) || $_POST['log']=='') && isset($_REQUEST['testcookie']) && $_REQUEST['testcookie']))
			{
				if(isset($_REQUEST['pagetype']) && $_REQUEST['pagetype'] != '')
				{
					wp_redirect($_REQUEST['pagetype'].'&emsg=1');
				}else
				{
					wp_redirect(home_url().'?geodir_signup=true&amp;logemsg=1&amp;redirect_to='.urlencode($_REQUEST['redirect_to']));
				}
				exit;
			}
		break;
	endswitch; // end action switch
}
?>