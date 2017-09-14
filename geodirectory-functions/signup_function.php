<?php
/**
 * Account Signup functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/**
 * Check whether the user is logged in or not.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $current_user Current user object.
 * @param bool $redirect Optional. Do you want to redirect to signup page, if user not logged in? Default: false.
 * @return bool
 */
function geodir_is_login($redirect = false)
{
    global $current_user;
    if (!$current_user->ID) {
        if ($redirect) {
            ?>
            <script type="text/javascript">
                window.location.href = '<?php echo geodir_login_url();?>';
            </script>
        <?php
        } else
            return false;
    } else
        return true;
}

/**
 * Redirect to SSL url, if SSL is being used.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @todo fix unknown $message variable
 */
function geodir_check_ssl()
{

// Redirect to https login if forced to use SSL
    if (force_ssl_admin() && !is_ssl()) {
        if (0 === strpos($_SERVER['REQUEST_URI'], 'http')) {
            wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']));
            exit();
        } else {
            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    /**
     * Filter the login message.
     *
     * @since 1.0.0
     *
     * @param string $message Login message.
     */
    $message = apply_filters('login_message', $message);
    if (!empty($message)) echo $message . "\n";

}

/**
 * Get site email ID or site admin email ID.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @return string|mixed|void The email ID.
 */
function geodir_get_site_email_id()
{
    if (get_option('site_email')) {

        return get_option('site_email');

    } else {

        return get_option('admin_email');

    }

}


if (!function_exists('get_site_emailName')) {
    /**
     * Get site name for sending emails.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @return string Site name.
     */
    function get_site_emailName()

    {

        if (get_option('site_email_name')) {

            return stripslashes(get_option('site_email_name'));

        } else {

            return stripslashes(get_option('blogname'));

        }

    }
}

if (!function_exists('is_allow_user_register')) {
    /**
     * Checks whether the site allowing user registration or not.
     *
     * @since 1.0.0
     * @package GeoDirectory
     * @return bool|string
     */
    function is_allow_user_register()

    {

        return get_option('users_can_register');

    }
}

/**
 * Handles sending password retrieval email to user.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @global object $wpdb WordPress Database object.
 * @return bool|WP_Error True: when finish. WP_Error on error.
 */
function geodir_retrieve_password()
{
    global $wpdb;

    $errors = new WP_Error();
    if (empty($_POST['user_login']) && empty($_POST['user_email']))
        $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.', 'geodirectory'));

    if (strpos($_POST['user_login'], '@')) {
        //$user_data = get_user_by_email(trim($_POST['user_login']));
        $user_data = get_user_by('email', trim($_POST['user_login']));
        if (empty($user_data))
            $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.', 'geodirectory'));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('email', $login);
    }

    /**
     * Called in the geodir_retrieve_password() function before any errors are set or any emails are sent.
     *
     * @since 1.0.0
     */
    do_action('lostpassword_post');

    if ($errors->get_error_code())
        return $errors;

    if (!$user_data) {
        $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', 'geodirectory'));
        return $errors;
    }

    // redefining user_login ensures we return the right case in the email
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    /**
     * Called in the geodir_retrieve_password() function before any emails are sent.
     *
     * @since 1.0.0
     * @param string $user_login The users username.
     */
    do_action('retrieve_password', $user_login);

    ////////////////////////////////////
    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
    $user_login = $_POST['user_login'];

    $user = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $wpdb->users WHERE user_login like %s or user_email like %s",
            array($user_login, $user_login)
        )
    );

    if (empty($user))
        return new WP_Error('invalid_key', __('Invalid key', 'geodirectory'));

    $new_pass = wp_generate_password(12, false);

    /**
     * Called in the geodir_retrieve_password() function before any emails are sent.
     *
     * @since 1.0.0
     * @param object $user The user object.
     * @param string $new_pass The new pass being sent to the user.
     */
    do_action('password_reset', $user, $new_pass);

    wp_set_password($new_pass, $user->ID);
    update_user_meta($user->ID, 'default_password_nag', true); //Set up the Password change nag.
    $message = '<p><b>' . __('Your login Information :', 'geodirectory') . '</b></p>';
    $message .= '<p>' . sprintf(__('Username: %s', 'geodirectory'), $user->user_login) . "</p>";
    $message .= '<p>' . sprintf(__('Password: %s', 'geodirectory'), $new_pass) . "</p>";
    //$message .= '<p>You can login to : <a href="'.home_url().'/?ptype=login' . "\">Login</a> or the URL is :  ".home_url()."/?ptype=login</p>";
    //$message .= '<p>Thank You,<br> '.get_option('blogname').'</p>';
    $user_email = $user_data->user_email;
    $user_name = geodir_get_client_name($user->ID);
    $fromEmail = geodir_get_site_email_id();
    $fromEmailName = get_site_emailName();
    $title = sprintf(__('[%s] Your new password', 'geodirectory'), get_option('blogname'));
    /**
     * Filter the password reset email subject part.
     *
     * @since 1.0.0
     *
     * @param string $title Password reset email subject.
     */
    $title = apply_filters('password_reset_title', $title);
    /**
     * Filter the password reset email message part.
     *
     * @since 1.0.0
     *
     * @param string $message Password reset email message.
     * @param string $new_pass The new password string.
     */
    $message = apply_filters('password_reset_message', $message, $new_pass);
    //geodir_sendEmail($fromEmail,$fromEmailName,$user_email,$user_name,$title,$message,$extra='');///forgot password email
    geodir_sendEmail($fromEmail, $fromEmailName, $user_email, $user_name, $title, $message, $extra = '', 'forgot_password', $post_id = '', $user->ID);///forgot password email

    return true;
}

/**
 * Handles registering a new user.
 *
 * @since 1.0.0
 * @package GeoDirectory
 * @param string $user_login User's username for logging in.
 * @param string $user_email User's email address to send password and add.
 * @global object $wpdb WordPress Database object.
 * @return int|WP_Error Either user's ID or error on failure.
 */
function geodir_register_new_user($user_login, $user_email)
{
    global $wpdb;
    $errors = new WP_Error();


    $user_login = sanitize_user($user_login);
    $user_login = str_replace(",", "", $user_login);
    $user_email = str_replace(",", "", $user_email);
    /**
     * Filter the user registration email.
     *
     * @since 1.0.0
     *
     * @param string $user_email User registration email.
     */
    $user_email = apply_filters('user_registration_email', $user_email);


    if (get_option('geodir_allow_cpass')) {
        $user_pass = $_REQUEST['user_pass'];
        $user_pass2 = $_REQUEST['user_pass2'];
        // Check the password
        if ($user_pass != $user_pass2) {
            $errors->add('pass_match', __('ERROR: Passwords do not match.', 'geodirectory'));
        } elseif (strlen($user_pass) < 7) {
            $errors->add('pass_match', __('ERROR: Password must be 7 characters or more.', 'geodirectory'));
        }
    }

    // Check the username
    if ($user_login == '')
        $errors->add('empty_username', __('ERROR: Please enter a username.', 'geodirectory'));
    elseif (!validate_username($user_login)) {
        $errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.', 'geodirectory'));
        $user_login = '';
    } elseif (username_exists($user_login))
        $errors->add('username_exists', __('<strong>ERROR</strong>: This username is already registered, please choose another one.', 'geodirectory'));

    // Check the e-mail address
    if ($user_email == '') {
        $errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.', 'geodirectory'));
    } elseif (!is_email($user_email)) {
        $errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'geodirectory'));
        $user_email = '';
    } elseif (email_exists($user_email))
        $errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.', 'geodirectory'));

    /**
     * Called when registering a new user.
     *
     * This is a WordPress core hook.
     *
     * @link https://codex.wordpress.org/Plugin_API/Action_Reference/register_post
     * @since 1.0.0
     */
    do_action('register_post', $user_login, $user_email, $errors);
    /**
     * Filter the registration error messages.
     *
     * @since 1.0.0
     *
     * @param object $errors Registration error messages.
     */
    $errors = apply_filters('registration_errors', $errors,$user_login,$user_email);

    if ($errors->get_error_code())
        return $errors;


    if (!isset($user_pass) || $user_pass == '') {
        $user_pass = wp_generate_password(12, false);
    }
    $user_id = wp_create_user($user_login, $user_pass, $user_email);
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

    /**
     * Filter the submitted user meta.
     *
     * @since 1.0.0
     *
     * @param int $user_id User ID.
     */
    $user_address_info = apply_filters('geodir_manage_user_meta', array(
        "user_add1" => '',
        "user_add2" => '',
        "user_city" => '',
        "user_state" => '',
        "user_country" => '',
        "user_postalcode" => '',
        "user_phone" => '',
        "user_twitter" => '',
        "first_name" => $user_fname,
        "last_name" => '',
    ), $user_id);
    foreach ($user_address_info as $key => $val) {
        update_user_meta($user_id, $key, $val); // User Address Information Here
    }
    //update_user_meta($user_id, 'user_address_info', ($user_address_info)); // User Address Information Here
    $userName = $user_fname;
    update_user_meta($user_id, 'first_name', $userName); // User Address Information Here
    //update_user_meta($user_id, 'last_name', $_POST['user_lname']); // User Address Information Here

    // Changed by vikas sharma to enable all type of characters in author permalink...
    $user_nicename = sanitize_title($userName);

    $updateUsersql = $wpdb->prepare("update $wpdb->users set user_url=%s, user_nicename=%s, display_name=%s  where ID=%d", array($user_web, $user_nicename, $userName, $user_id));

    $wpdb->query($updateUsersql);

    if (!$user_id) {
        $errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'geodirectory'), get_option('admin_email')));
        return $errors;
    }
    global $upload_folder_path;

    if ($user_id) {

        /**
         * Called after registering a user and before the registration email is sent.
         *
         * @since 1.0.0
         * @param int $user_id The user ID of the registered user.
         */
        do_action('geodir_user_register', $user_id);
        ///////REGISTRATION EMAIL START//////
        $fromEmail = geodir_get_site_email_id();
        $fromEmailName = get_site_emailName();
        $message = __('<p><b>' . __('Your login Information :', 'geodirectory') . '</b></p>
<p>' . __('Username:', 'geodirectory') . ' ' . $user_login . '</p>
<p>' . __('Password:', 'geodirectory') . ' ' . $user_pass . '</p>');

        /////////////customer email//////////////
        //geodir_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$client_message,$extra='');///To client email
        geodir_sendEmail($fromEmail, $fromEmailName, $user_email, $userName, '', $message, '', 'registration', '', $user_id);/// registration email
        //////REGISTRATION EMAIL END////////
    }

    if (get_option('ptthemes_auto_login')) {
        $errors->add('auto_login', __('<strong>SUCCESS</strong>: Thank you for registering, please check your email for your login details.', 'geodirectory'));
        return $errors;
    }

    return array($user_id, $user_pass);
}

/**
 * Handles user Authentication actions Ex. login, register, logout etc.
 *
 * @since 1.0.0
 * @since 1.5.7 It allows login by email also if site is using SSL.
 * @package GeoDirectory
 * @global object $errors WordPress Error object.
 */
function geodir_user_signup()
{
    global $errors;
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

    $errors = new WP_Error();

    if (isset($_GET['key']))
        $action = 'resetpass';

    // validate action so as to default to the login screen
    if (!in_array($action, array('logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register', 'login')) && false === has_filter('login_form_' . $action))
        $action = 'login';

    nocache_headers();

    if (defined('RELOCATE')) { // Move flag is set
        if (isset($_SERVER['PATH_INFO']) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']))
            $_SERVER['PHP_SELF'] = str_replace($_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF']);

        $schema = (isset($_SERVER['HTTPS']) && geodir_strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        if (dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != home_url())
            update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']));
    }

    //Set a cookie now to see if they are supported by the browser.
    //setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
    if (SITECOOKIEPATH != COOKIEPATH)
        setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

    /**
     * Allow plugins to override the default actions, and to add extra actions if they want on the register/signin page.
     *
     * Used dynamic hook login_form_$action
     *
     * @since 1.0.0
     */
    do_action('login_form_' . $action);

    $http_post = ('POST' == $_SERVER['REQUEST_METHOD']);

    switch ($action):

        case 'logout' :
            //check_admin_referer('log-out');
            wp_logout();

            $redirect_to = $_SERVER['HTTP_REFERER'];
            //$redirect_to = home_url().'/?ptype=login&loggedout=true';
            if (isset($_REQUEST['redirect_to']))
                $redirect_to = $_REQUEST['redirect_to'];
            $redirect_to = home_url();
            wp_safe_redirect($redirect_to);
            exit();

            break;

        case 'lostpassword' :
        case 'retrievepassword' :
            if ($http_post) {
                $errors = geodir_retrieve_password();
                $error_message = isset($errors->errors['invalid_email'][0]) ? $errors->errors['invalid_email'][0] : '';
                if (!is_wp_error($errors)) {
                    wp_redirect(geodir_login_url(array('checkemail'=>'confirm')));
                    gd_die();
                } else {
                    wp_redirect(geodir_login_url(array('forgot' => 1, 'emsg'=>'fw')));
                    gd_die();
                }
            }
            if (isset($_GET['error']) && 'invalidkey' == $_GET['error']) $errors->add('invalidkey', __('Sorry, that key does not appear to be valid.', 'geodirectory'));
        /**
         * Called in the geodir_user_signup() function during the lostpassword case.
         *
         * @since 1.0.0
         */
        do_action('lost_password');
            $message = '<div class="sucess_msg">' . ENTER_USER_EMAIL_NEW_PW_MSG . '</div>';
            $user_login = isset($_POST['user_login']) ? stripslashes($_POST['user_login']) : '';

            break;

        case 'resetpass' :
        case 'rp' :
            $errors = reset_password($_GET['key'], $_GET['login']);

            if (!is_wp_error($errors)) {
                wp_redirect(geodir_login_url(array('checkemail'=>'newpass')));
                exit();
            }

            wp_redirect(geodir_login_url(array('error'=>'invalidkey','action'=>'lostpassword')));
            exit();

            break;

        case 'register' :
            ############################### fix by Stiofan -  HebTech.co.uk ### SECURITY FIX ##############################
            if (!get_option('users_can_register')) {
                wp_redirect(geodir_login_url(array('emsg'=>'regnewusr')));
                exit();
            }
            ############################### fix by Stiofan -  HebTech.co.uk ### SECURITY FIX ##############################
            global $user_email, $user_fname;
            $user_login = '';
            $user_email = '';
            if ($http_post) {
                $user_login = $_POST['user_email'];
                $user_email = $_POST['user_email'];
                $user_fname = $_POST['user_fname'];

                $errors = geodir_register_new_user($user_login, $user_email);

                /* display error in registration form */
                if (is_wp_error($errors)) {
                    $error_code = $errors->get_error_code();
                    $error_message = $errors->get_error_message($error_code);
                    if (!isset($_POST['user_login']) && ($error_code == 'empty_username' || $error_code == 'invalid_username' || $error_code == 'username_exists')) {
                        if ($error_code == 'empty_username') {
                            $error_code = 'empty_email';
                        } else if ($error_code == 'invalid_username') {
                            $error_code = 'invalid_email';
                        } else if ($error_code == 'username_exists') {
                            $error_code = 'email_exists';
                        }

                        $error_message = $errors->get_error_message($error_code);
                    }
                    global $geodir_signup_error;
                    $geodir_signup_error = $error_message;
                }

                if (!is_wp_error($errors)) {
                    $_POST['log'] = $user_login;
                    $_POST['pwd'] = $errors[1];
                    $_POST['testcookie'] = 1;

                    $secure_cookie = '';
                    // If the user wants ssl but the session is not ssl, force a secure cookie.
                    if (!empty($_POST['log'])) {
                        $user_name = sanitize_user($_POST['log']);
                        if ($user = get_user_by('email', $user_name)) {
                            if (get_user_option('use_ssl', $user->ID)) {
                                $secure_cookie = true;
                                force_ssl_admin(true);
                            }
                        }
                    }

                    $redirect_to = $_REQUEST['redirect_to'];

                    if (!isset($_REQUEST['redirect_to']) || $_REQUEST['redirect_to'] == '') {
                        if (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], home_url())) {
                            $redirect_to = $_SERVER['HTTP_REFERER'];
                        } else {
                            $redirect_to = home_url();
                        }

                    }

                    if (isset($_REQUEST['redirect_add_listing']) && $_REQUEST['redirect_add_listing'] != '') {

                        $redirect_to = $_REQUEST['redirect_add_listing'];
                    }


                    if (!$secure_cookie && is_ssl() && force_ssl_admin() && !force_ssl_admin() && (0 !== strpos($redirect_to, 'https')) && (0 === strpos($redirect_to, 'http')))
                        $secure_cookie = false;

                    $user = wp_signon('', $secure_cookie);

                    $requested_redirect_to = isset($_REQUEST['redirect_add_listing']) && $_REQUEST['redirect_add_listing'] != '' ? $_REQUEST['redirect_add_listing'] : (isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '');
                    /**
                     * Filter the login redirect URL.
                     *
                     * @since 1.4.9
                     * @param string $redirect_to The redirect destination URL.
                     * @param string $requested_redirect_to The requested redirect destination URL passed as a parameter.
                     * @param WP_User|WP_Error $user WP_User object if login was successful, WP_Error object otherwise.
                     */
                    $redirect_to = apply_filters('login_redirect', $redirect_to, $requested_redirect_to, $user);


                    if (!is_wp_error($user)) {
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

            if (!empty($_POST['log'])) {
                $user_name = sanitize_user($_POST['log']);
                if ($user = get_user_by('login', $user_name)) {

                    if (get_user_option('use_ssl', $user->ID)) {
                        $secure_cookie = true;
                        force_ssl_admin(true);
                    }
                } elseif ($user = get_user_by('email', $user_name)) {
                    $_POST['log'] = $user->user_login; // If signing in by email, set the username for normal WP login
                    if (get_user_option('use_ssl', $user->ID)) {
                        $secure_cookie = true;
                        force_ssl_admin(true);
                    }
                }
            }
            ///////////////////////////
            if (isset($_REQUEST['redirect_add_listing'])) {
                $_REQUEST['redirect_to'] = $_REQUEST['redirect_add_listing'];
            }


            if (!isset($_REQUEST['redirect_to']) || $_REQUEST['redirect_to'] == '') {
                if (is_user_logged_in()) :
                    $user_ID = !empty($user->ID) ? $user->ID : get_current_user_id();
                    $post_types = geodir_get_posttypes();
                    
                    if ( !empty( $_REQUEST['stype'] ) ) {
                        $dashboard_post_type = sanitize_text_field($_REQUEST['stype']);
                    } else {
                        $user_listings = geodir_user_post_listing_count( $user_ID );
                        if ( !empty( $user_listings ) && $dashboard_post_types = array_keys( $user_listings ) ) {
                            $dashboard_post_type = $dashboard_post_types[0];
                        }
                    }
                    if ( !( !empty( $dashboard_post_type ) && in_array( $dashboard_post_type, $post_types ) ) ) {
                        $dashboard_post_type = $post_types[0];
                    }
                    
                    $author_link = get_author_posts_url($user_ID);
                    $default_author_link = geodir_getlink($author_link, array('geodir_dashbord' => 'true', 'stype' => $dashboard_post_type), false);

                    /**
                     * Filter the author link.
                     *
                     * @since 1.0.0
                     *
                     * @param string $default_author_link Default author link.
                     * @param int $user_ID The user ID.
                     */
                    $default_author_link = apply_filters('geodir_dashboard_author_link', $default_author_link, $user_ID);

                    $_REQUEST['redirect_to'] = $default_author_link;
                else:
                    $_REQUEST['redirect_to'] = home_url();
                endif;

            }
            if (isset($_REQUEST['redirect_to'])) {
                $redirect_to = $_REQUEST['redirect_to'];
                // Redirect to https if user wants ssl
                if ($secure_cookie && false !== strpos($redirect_to, 'wp-admin'))
                    $redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
            } else {
                $redirect_to = admin_url();
            }

            if (!$secure_cookie && is_ssl() && force_ssl_admin() && !force_ssl_admin() && (0 !== strpos($redirect_to, 'https')) && (0 === strpos($redirect_to, 'http')))
                $secure_cookie = false;
            $user = wp_signon('', $secure_cookie);


            /**
             * Filter the login redirect URL.
             *
             * @since 1.4.9
             * @param string $redirect_to The redirect destination URL.
             * @param WP_User|WP_Error $user WP_User object if login was successful, WP_Error object otherwise.
             */
            $redirect_to = apply_filters('login_redirect', $redirect_to, isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '', $user);

            if (is_wp_error($user)) {
                if (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], 'ptype=property_submit') && $_POST['log'] != '' && $_POST['pwd'] != '') {
                    wp_redirect($_SERVER['HTTP_REFERER'] . '&emsg=1');
                }
            }
            if (!is_wp_error($user)) {

                // Some servers are not logging the user in properly after wp_signon, se we set the user here.
                //wp_set_current_user($user->ID);
                //echo '###';exit;

                if ($redirect_to) {
                    wp_redirect($redirect_to);
                } else {
                    wp_redirect(home_url());
                }
                gd_die();
            }

            $errors = $user;

            // Clear errors if loggedout is set.
            if (!empty($_GET['loggedout']))
                $errors = new WP_Error();
            // If cookies are disabled we can't log in even with a valid user+pass
            if (isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]))
                $errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress.", 'geodirectory'));

            // Some parts of this script use the main login form to display a message
            if (isset($_GET['loggedout']) && TRUE == $_GET['loggedout']) {
                $successmsg = '<div class="sucess_msg">' . YOU_ARE_LOGED_OUT_MSG . '</div>';
            } elseif (isset($_GET['registration']) && 'disabled' == $_GET['registration']) {
                $successmsg = USER_REG_NOT_ALLOW_MSG;
            } elseif (isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail']) {
                $successmsg = EMAIL_CONFIRM_LINK_MSG;
            } elseif (isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail']) {
                $successmsg = NEW_PW_EMAIL_MSG;
            } elseif (isset($_GET['checkemail']) && 'registered' == $_GET['checkemail']) {
                $successmsg = REG_COMPLETE_MSG;
            }

            if ((isset($_POST['log']) && $_POST['log'] != '' && $errors) || ((!isset($_POST['log']) || $_POST['log'] == '') && isset($_REQUEST['testcookie']) && $_REQUEST['testcookie'])) {
                if (isset($_REQUEST['pagetype']) && $_REQUEST['pagetype'] != '') {
                    wp_redirect($_REQUEST['pagetype'] . '&emsg=1');
                } else {
                    wp_redirect(geodir_login_url(array('logemsg'=>'1','redirect_to'=>urlencode($_REQUEST['redirect_to']))));
                }
                gd_die();
            }
            break;
    endswitch; // end action switch
}