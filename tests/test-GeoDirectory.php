<?php
class GeoDirectoryTests extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        wp_set_current_user(1);

        geodir_register_sidebar();
    }

    public function testBreadcrumbs() {
        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        $this->go_to( get_permalink($post_id) );

        ob_start();
        geodir_breadcrumb();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Places', $output);
    }

    public function testAdminSettingForms() {
        $_REQUEST['listing_type'] = 'gd_place';
        ob_start();
        geodir_admin_option_form('general_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('General Options', $output);

        ob_start();
        geodir_admin_option_form('design_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Home Top Section Settings', $output);

        ob_start();
        geodir_admin_option_form('permalink_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Listing Detail Permalink Settings', $output);

        ob_start();
        geodir_admin_option_form('title_meta_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Homepage Meta Settings', $output);

        ob_start();
        geodir_admin_option_form('notifications_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Notification Options', $output);

        ob_start();
        geodir_admin_option_form('default_location_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Set Default Location', $output);

        ob_start();
        geodir_admin_option_form('tools_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('GD Diagnostic Tools', $output);

        ob_start();
        geodir_admin_option_form('compatibility_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Theme Compatability Settings', $output);

        ob_start();
        geodir_admin_option_form('import_export');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('GD Import & Export CSV', $output);

        $_REQUEST['subtab'] = 'custom_fields';
        ob_start();
        geodir_admin_option_form('gd_place_fields_settings');
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('general-form-builder-frame', $output);

    }

    public function testPopPostWidget() {
        $args = array(
            'before_widget' => '<ul>',
            'after_widget' => '<ul>',
            'before_title' => '<ul>',
            'after_title' => '<ul>'
        );
        ob_start();
        geodir_popular_post_category_output($args);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Popular Categories', $output);
    }

    public function texstBestOfWidget() {
//        $args = array(
//            'before_widget' => '<ul>',
//            'after_widget' => '<ul>',
//            'before_title' => '<ul>',
//            'after_title' => '<ul>'
//        );
        include_once geodir_plugin_path() . "/geodirectory-widgets/geodirectory_cpt_categories_widget.php";
        $params = array(
            'title' => '',
            'post_type' => array(), // NULL for all
            'hide_empty' => '',
            'show_count' => '',
            'hide_icon' => '',
            'cpt_left' => '',
            'sort_by' => 'count',
            'max_count' => 'all',
            'max_level' => '1'
        );
        ob_start();
        geodir_cpt_categories_output($params);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('gd-cptcat-title', $output);
    }

    public function testGDWidgetListView() {
        global $gridview_columns_widget, $geodir_is_widget_listing;

        $_REQUEST['sgeo_lat'] = '40.7127837';
        $_REQUEST['sgeo_lon'] = '-74.00594130000002';
        $query_args = array(
            'posts_per_page' => 1,
            'is_geodir_loop' => true,
            'gd_location' => false,
            'post_type' => 'gd_place',
        );
        $widget_listings = geodir_get_widget_listings($query_args);
        $template = apply_filters("geodir_template_part-widget-listing-listview", geodir_locate_template('widget-listing-listview'));
        global $post, $map_jason, $map_canvas_arr;

        $current_post = $post;
        $current_map_jason = $map_jason;
        $current_map_canvas_arr = $map_canvas_arr;
        $geodir_is_widget_listing = true;

        ob_start();
        include($template);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-entry-content', $output);

        $geodir_is_widget_listing = false;

        $GLOBALS['post'] = $current_post;
        if (!empty($current_post))
            setup_postdata($current_post);
        $map_jason = $current_map_jason;
        $map_canvas_arr = $current_map_canvas_arr;

    }

    public function testRegTemplate() {
//        var_dump(geodir_login_page_id());
        wp_set_current_user(0);
        $template = geodir_plugin_path() . '/geodirectory-templates/geodir-signup.php';

        ob_start();
        include($template);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Sign In', $output);
    }

    public function testInfoTemplate() {
        wp_set_current_user(1);
        global $information;
        $information = "hello world";

        $template = geodir_plugin_path() . '/geodirectory-templates/geodir-information.php';

        ob_start();
        include($template);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('hello world', $output);
    }

    public function testSuccessPage() {
        wp_set_current_user(1);

        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        $_REQUEST['listing_type'] = 'gd_place';
        $_REQUEST['pid'] = $post_id;
        ob_start();
        $this->go_to( get_permalink(geodir_success_page_id()) );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('View your submitted information', $output);
    }

    public function testPreviewPage() {
        wp_set_current_user(1);

        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        $_REQUEST = array(
            'pid' => $post_id,
            'listing_type' => 'gd_place',
            'post_title' => 'Test Listing Title Modified',
            'post_desc' => 'Test Desc',
            'post_tags' => 'test1,test2',
            'post_address' => 'New York City Hall',
            'post_zip' => '10007',
            'post_latitude' => '40.7127837',
            'post_longitude' => '-74.00594130000002',
            'post_mapview' => 'ROADMAP',
            'post_mapzoom' => '10',
            'geodir_timing' => '10.00 am to 6 pm every day',
            'geodir_contact' => '1234567890',
            'geodir_email' => 'test@test.com',
            'geodir_website' => 'http://test.com',
            'geodir_twitter' => 'http://twitter.com/test',
            'geodir_facebook' => 'http://facebook.com/test',
            'geodir_special_offers' => 'Test offer',
            'preview' => true,
            'post_category' => array(
                'gd_placecategory' => ''
            )
        );

        ob_start();
        $this->go_to( get_permalink(geodir_preview_page_id()) );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('This is a preview of your listing', $output);
    }

    public function testAddListingPage() {
        wp_set_current_user(1);

        ob_start();
        $this->go_to( get_permalink(geodir_add_listing_page_id()) );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Enter Listing Details', $output);
    }

    public function testLocationPage() {
        wp_set_current_user(1);

        ob_start();
        $this->go_to( get_permalink(geodir_location_page_id()) );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Location', $output);
    }

    public function testTemplates() {

        global $current_user;

        $user_id = $current_user->ID;

        $this->setPermalinkStructure();

        $homepage = get_page_by_title( 'GD Home page' );
        if ( $homepage )
        {
            update_option( 'page_on_front', $homepage->ID );
            update_option( 'show_on_front', 'page' );
        }

        ob_start();
        $this->go_to( home_url('/') );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('body class="home', $output);


        ob_start();
        $this->go_to( home_url('/?post_type=gd_place') );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('All Places', $output);

        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        global $preview;
        $preview = false;
        ob_start();
        $this->go_to( get_permalink($post_id) );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('post_profileTab', $output);

        ob_start();
        $_REQUEST['stype'] = 'gd_place';
        $_REQUEST['geodir_dashbord'] = true;
        $this->go_to( get_author_posts_url($user_id) );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('gd_list_view', $output);

    }

    public function testSearchPage() {
        ob_start();
        $_REQUEST['geodir_search'] = "1";
        $_REQUEST['stype'] = 'gd_place';
        $this->go_to( home_url('/?geodir_search=1&stype=gd_place&s=test&snear=&sgeo_lat=&sgeo_lon=') );
        $this->load_template();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Search Results for', $output);

    }

    public function testPopupSendEnquiryTemplate() {

        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        $_REQUEST['popuptype'] = 'b_send_inquiry';
        $_REQUEST['post_id'] = $post_id;
        add_filter('wp_redirect', '__return_false');
        ob_start();
        geodir_ajax_handler();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('send_inqury', $output);
        remove_filter('wp_redirect', '__return_false');

    }

    public function texstPopupSendToFrndTemplate() {

        //include template problem. its already loaded. find a way to fix this

        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        $_REQUEST['popuptype'] = 'b_sendtofriend';
        $_REQUEST['post_id'] = $post_id;
        add_filter('wp_redirect', '__return_false');
        ob_start();
        geodir_ajax_handler();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('send_inqury', $output);
        remove_filter('wp_redirect', '__return_false');
    }

    public function testNavMenus() {
        $menuname = 'Primary Menu';
        $menulocation = 'primary';
        // Does the menu exist already?
        $menu_exists = wp_get_nav_menu_object( $menuname );

        // If it doesn't exist, let's create it.
        if( !$menu_exists){
            $menu_id = wp_create_nav_menu($menuname);

            // Set up default BuddyPress links and add them to the menu.
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' =>  __('Home'),
                'menu-item-classes' => 'home',
                'menu-item-url' => home_url( '/' ),
                'menu-item-status' => 'publish'));

            if( !has_nav_menu( $menulocation ) ){
                $locations = get_theme_mod('nav_menu_locations');
                $locations[$menulocation] = $menu_id;
                set_theme_mod( 'nav_menu_locations', $locations );
            }

            update_option('geodir_theme_location_nav', array('primary'));

            $menu = wp_nav_menu(array(
                'theme_location' => 'primary',
                'echo' => false,
            ));

            $this->assertContains('Add Listing', $menu);


        }
    }

    public function testBestOfWidget() {
        $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_bestof_widget.php';
        include_once($template);

        ob_start();
        $instance = array();
        $instance['use_viewing_post_type'] = '1';
        $instance['excerpt_type'] = 'show-reviews';
        the_widget( 'geodir_bestof_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('bestof-widget-tab-layout', $output);

        ob_start();
        $this->the_widget_form( 'geodir_bestof_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Number of categories', $output);

        $new_instance = array(
            'title' => '',
            'post_type' => '',
            'post_limit' => '5',
            'categ_limit' => '3',
            'character_count' => '20',
            'add_location_filter' => '1',
            'tab_layout' => 'bestof-tabs-on-top',
            'excerpt_type' => 'show-desc',
            'use_viewing_post_type' => '1'
        );
        $output = $this->the_widget_form_update( 'geodir_bestof_widget', $new_instance );
        $this->assertContains('20', $output['character_count']);

        ob_start();
        geodir_bestof_js();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('bestof-widget-tab-layout', $output);

    }

    public function testCptCatsWidget() {
        $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_cpt_categories_widget.php';
        include_once($template);

        ob_start();
        the_widget( 'geodir_cpt_categories_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir_cpt_categories_widget', $output);

        ob_start();
        $this->the_widget_form( 'geodir_cpt_categories_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Select CPT', $output);

        $new_instance = array(
            'title' => '',
            'post_type' => array(), // NULL for all
            'hide_empty' => '',
            'show_count' => '',
            'hide_icon' => '',
            'cpt_left' => '',
            'sort_by' => 'count',
            'max_count' => 'all',
            'max_level' => '1'
        );
        $output = $this->the_widget_form_update( 'geodir_cpt_categories_widget', $new_instance );
        $this->assertContains('count', $output['sort_by']);

    }

    public function testFeaturesWidget() {
        $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_features_widget.php';
        include_once($template);

        $instance = array(
            'title' => 'Features',
            'icon_color' => '',
            'title1' => 'Hello World',
            'image1' => 'fa-recycle',
            'desc1' => 'cool',
        );

        ob_start();
        the_widget( 'Geodir_Features_Widget', $instance);
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('widget_gd_features', $output);

        ob_start();
        $this->the_widget_form( 'Geodir_Features_Widget', $instance );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Font Awesome Icon Color', $output);

        $new_instance = array(
            'title' => 'Features',
            'icon_color' => '',
            'title1' => 'Hello World',
            'image1' => 'fa-recycle',
            'desc1' => 'cool',
        );
        $output = $this->the_widget_form_update( 'Geodir_Features_Widget', $new_instance );
        $this->assertContains('Features', $output['title']);

    }

    public function testSliderWidget() {
        $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_listing_slider_widget.php';
        include_once($template);

        ob_start();
        the_widget( 'geodir_listing_slider_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir_listing_slider_view', $output);

        ob_start();
        $this->the_widget_form( 'geodir_listing_slider_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Slide Show Speed', $output);

        $new_instance = array(
            'title' => '',
            'post_type' => '',
            'category' => '',
            'post_number' => '5',
            'max_show' => '1',
            'slide_width' => '',
            'show_title' => '',
            'slideshow' => '',
            'animationLoop' => '',
            'directionNav' => '',
            'slideshowSpeed' => 5000,
            'animationSpeed' => 600,
            'animation' => '',
            'list_sort' => 'latest',
            'show_featured_only' => '',
        );
        $output = $this->the_widget_form_update( 'geodir_listing_slider_widget', $new_instance );
        $this->assertContains('latest', $output['list_sort']);

    }

    public function testPopularWidget() {
        $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_popular_widget.php';
        include_once($template);

        ob_start();
        the_widget( 'geodir_popular_post_category' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir_popular_post_category', $output);

        ob_start();
        $this->the_widget_form( 'geodir_popular_post_category' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Default post type to use', $output);

        $new_instance = array(
            'title' => '',
            'category_limit' => 15,
            'default_post_type' => ''
        );
        $output = $this->the_widget_form_update( 'geodir_popular_post_category', $new_instance );
        $this->assertEquals(15, $output['category_limit']);

        ob_start();
        $instance = array();
        $instance['category_title'] = '';
        the_widget( 'geodir_popular_postview', $instance );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir_popular_post_view', $output);

        ob_start();
        $this->the_widget_form( 'geodir_popular_postview' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Post Category', $output);

        $new_instance = array(
            'title' => '',
            'post_type' => '',
            'category' => array(),
            'category_title' => '',
            'list_sort' => '',
            'list_order' => '',
            'post_number' => '5',
            'layout' => 'gridview_onehalf',
            'listing_width' => '',
            'add_location_filter' => '1',
            'character_count' => '20',
            'show_featured_only' => '',
            'show_special_only' => '',
            'with_pics_only' => '',
            'with_videos_only' => '',
            'use_viewing_post_type' => ''
        );
        $output = $this->the_widget_form_update( 'geodir_popular_postview', $new_instance );
        $this->assertContains('gridview_onehalf', $output['layout']);

    }

    public function testRelatedWidget() {
        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            global $post;
            $post_id = get_the_ID();
            $post = geodir_get_post_info($post->ID);

//            $term_list = wp_get_post_terms($post->ID, 'gd_placecategory');
//            $post->gd_placecategory = (string) $term_list[0]->term_id;

            $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_related_listing_widget.php';
            include_once($template);

            ob_start();
            the_widget( 'geodir_related_listing_postview' );
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertContains('Related Listing', $output);

            ob_start();
            $this->the_widget_form( 'geodir_related_listing_postview' );
            $output = ob_get_contents();
            ob_end_clean();
            $this->assertContains('Relate to', $output);

            $new_instance = array(
                'title' => '',
                'list_sort' => '',
                'list_order' => '',
                'post_number' => '5',
                'relate_to' => '',
                'layout' => 'gridview_onehalf',
                'listing_width' => '',
                'add_location_filter' => '1',
                'character_count' => '20'
            );
            $output = $this->the_widget_form_update( 'geodir_related_listing_postview', $new_instance );
            $this->assertContains('gridview_onehalf', $output['layout']);
        endwhile;

        $this->assertTrue(is_int($post_id));


    }

    public function testReviewsWidget() {

        $time = current_time('mysql');

        $args = array(
            'listing_type' => 'gd_place',
            'post_title' => 'Test Listing Title',
            'post_desc' => 'Test Desc',
            'post_tags' => 'test1,test2',
            'post_address' => 'New York City Hall',
            'post_zip' => '10007',
            'post_latitude' => '40.7127837',
            'post_longitude' => '-74.00594130000002',
            'post_mapview' => 'ROADMAP',
            'post_mapzoom' => '10',
            'geodir_timing' => '10.00 am to 6 pm every day',
            'geodir_contact' => '1234567890',
            'geodir_email' => 'test@test.com',
            'geodir_website' => 'http://test.com',
            'geodir_twitter' => 'http://twitter.com/test',
            'geodir_facebook' => 'http://facebook.com/test',
            'geodir_special_offers' => 'Test offer'
        );
        $post_id = geodir_save_listing($args, true);

        $data = array(
            'comment_post_ID' => $post_id,
            'comment_author' => 'admin',
            'comment_author_email' => 'admin@admin.com',
            'comment_author_url' => 'http://wpgeodirectory.com',
            'comment_content' => 'content here',
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => 1,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );

        $comment_id = wp_insert_comment($data);

        $_REQUEST['geodir_overallrating'] = 5.0;
        geodir_save_rating($comment_id);

        $this->assertTrue(is_int($comment_id));

        $template = geodir_plugin_path() . '/geodirectory-widgets/geodirectory_reviews_widget.php';
        include_once($template);

        ob_start();
        the_widget( 'geodir_recent_reviews_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir_recent_reviews', $output);

        ob_start();
        $this->the_widget_form( 'geodir_recent_reviews_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Number of Reviews', $output);

        $new_instance = array(
            'title' => '',
            't1' => '',
            't2' => '',
            't3' => '',
            'img1' => '',
            'count' => ''
        );
        $output = $this->the_widget_form_update( 'geodir_recent_reviews_widget', $new_instance );
        $this->assertTrue(empty($output['count']));
    }

    public function testHomeMapWidget() {
        $template = geodir_plugin_path() . '/geodirectory-widgets/home_map_widget.php';
        include_once($template);

        ob_start();
        $instance = array();
        $args = array();
        $args["widget_id"] = "geodir_map_v3_home_map-2";
        the_widget( 'geodir_homepage_map', $instance, $args );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-map-home-page', $output);

        ob_start();
        $this->the_widget_form( 'geodir_homepage_map' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Map Zoom level', $output);

        $new_instance = array(
            'width' => '',
            'heigh' => '',
            'maptype' => '',
            'zoom' => '',
            'autozoom' => '',
            'child_collapse' => '0',
            'scrollwheel' => '0'
        );
        $output = $this->the_widget_form_update( 'geodir_homepage_map', $new_instance );
        $this->assertContains('0', $output['scrollwheel']);

    }

    public function testLoginWidget() {

        register_geodir_widgets();

        ob_start();
        the_widget( 'geodir_loginwidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir_loginbox', $output);

        ob_start();
        $this->the_widget_form( 'geodir_loginwidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Widget Title', $output);

        $new_instance = array(
            'title' => 'Login',
            't1' => '',
            't2' => '',
            't3' => '',
            'img1' => '',
            'desc1' => ''
        );
        $output = $this->the_widget_form_update( 'geodir_loginwidget', $new_instance );
        $this->assertContains('Login', $output['title']);

    }

    public function testSocialWidget() {

        ob_start();
        the_widget( 'geodir_social_like_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('twitter.com', $output);

        ob_start();
        $this->the_widget_form( 'geodir_social_like_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('No settings for this widget', $output);

        $new_instance = array(
            'title' => 'Social',
        );
        $output = $this->the_widget_form_update( 'geodir_social_like_widget', $new_instance );
        $this->assertContains('Social', $output['title']);

    }

    public function testSubscribeWidget() {

        ob_start();
        the_widget( 'geodirsubscribeWidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-subscribe-form', $output);

        ob_start();
        $this->the_widget_form( 'geodirsubscribeWidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Feedburner ID', $output);

        $new_instance = array(
            'title' => 'Subscribe',
            'id' => '',
            'advt1' => '',
            'text' => '',
            'twitter' => '',
            'facebook' => '',
            'digg' => '',
            'myspace' => ''
        );
        $output = $this->the_widget_form_update( 'geodirsubscribeWidget', $new_instance );
        $this->assertContains('Subscribe', $output['title']);

    }

    public function testAdvWidget() {

        ob_start();
        $instance = array();
        $instance['desc1'] = 'hello';
        the_widget( 'geodiradvtwidget', $instance );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('hello', $output);

        ob_start();
        $this->the_widget_form( 'geodiradvtwidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Your Advt code', $output);

        $new_instance = array(
            'desc1' => 'Advertise'
        );
        $output = $this->the_widget_form_update( 'geodiradvtwidget', $new_instance );
        $this->assertContains('Advertise', $output['desc1']);

    }

    public function testFlickrWidget() {

        ob_start();
        the_widget( 'GeodirFlickrWidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-flickr', $output);

        ob_start();
        $this->the_widget_form( 'GeodirFlickrWidget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Flickr ID', $output);

        $new_instance = array(
            'id' => '',
            'number' => '34'
        );
        $output = $this->the_widget_form_update( 'GeodirFlickrWidget', $new_instance );
        $this->assertContains('34', $output['number']);

    }

    public function testTwitterWidget() {

        ob_start();
        $instance = array();
        $instance['gd_tw_desc1'] = 'hello';
        the_widget( 'geodir_twitter', $instance );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('hello', $output);

        ob_start();
        $this->the_widget_form( 'geodir_twitter' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Your twitter code', $output);

        $new_instance = array(
            'gd_tw_desc1' => 'hello'
        );
        $output = $this->the_widget_form_update( 'geodir_twitter', $new_instance );
        $this->assertContains('hello', $output['gd_tw_desc1']);

    }

    public function testAdvSearchWidget() {

        ob_start();
        the_widget( 'geodir_advance_search_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-loc-bar', $output);

        ob_start();
        $this->the_widget_form( 'geodir_advance_search_widget' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('This is a search widget', $output);

//        $new_instance = array(
//        );
//        $output = $this->the_widget_form_update( 'geodir_advance_search_widget', $new_instance );
//        $this->assertContains('Subscribe', $output['title']);

    }

    public function testListingMapWidget() {

        $template = geodir_plugin_path() . '/geodirectory-widgets/listing_map_widget.php';
        include_once($template);

        //listing page
        $this->go_to( home_url('/?post_type=gd_place') );

        ob_start();
        $instance = array();
        $args = array();
        $args["widget_id"] = "geodir_map_v3_listing_map-2";
        the_widget( 'geodir_map_listingpage', $instance, $args );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-map-listing-page', $output);

        // detail page
        $query_args = array(
            'post_status' => 'publish',
            'post_type' => 'gd_place',
            'posts_per_page' => 1,
        );

        $all_posts = new WP_Query( $query_args );
        $post_id = null;
        while ( $all_posts->have_posts() ) : $all_posts->the_post();
            $post_id = get_the_ID();
        endwhile;

        $this->assertTrue(is_int($post_id));

        $this->go_to( get_permalink($post_id) );

        ob_start();
        $instance = array();
        $args = array();
        $args["widget_id"] = "geodir_map_v3_listing_map-2";
        the_widget( 'geodir_map_listingpage', $instance, $args );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('geodir-map-listing-page', $output);

        ob_start();
        $this->the_widget_form( 'geodir_map_listingpage' );
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains('Select Map View', $output);

        $new_instance = array(
            'width' => '',
            'heigh' => '',
            'maptype' => '',
            'zoom' => '',
            'autozoom' => '',
            'sticky' => '',
            'scrollwheel' => '0',
            'showall' => '0'
        );
        $output = $this->the_widget_form_update( 'geodir_map_listingpage', $new_instance );
        $this->assertContains('0', $output['scrollwheel']);


    }

    private function load_template() {
        do_action( 'template_redirect' );
        $template = false;
        if	 ( is_404()			&& $template = get_404_template()			) :
        elseif ( is_search()		 && $template = get_search_template()		 ) :
        elseif ( is_front_page()	 && $template = get_front_page_template()	 ) :
        elseif ( is_home()		   && $template = get_home_template()		   ) :
        elseif ( is_post_type_archive() && $template = get_post_type_archive_template() ) :
        elseif ( is_tax()			&& $template = get_taxonomy_template()	   ) :
        elseif ( is_attachment()	 && $template = get_attachment_template()	 ) :
            remove_filter('the_content', 'prepend_attachment');
        elseif ( is_single()		 && $template = get_single_template()		 ) :
        elseif ( is_page()		   && $template = get_page_template()		   ) :
        elseif ( is_category()	   && $template = get_category_template()	   ) :
        elseif ( is_tag()			&& $template = get_tag_template()			) :
        elseif ( is_author()		 && $template = get_author_template()		 ) :
        elseif ( is_date()		   && $template = get_date_template()		   ) :
        elseif ( is_archive()		&& $template = get_archive_template()		) :
        elseif ( is_paged()		  && $template = get_paged_template()		  ) :
        else :
            $template = get_index_template();
        endif;
        /**
         * Filter the path of the current template before including it.
         *
         * @since 3.0.0
         *
         * @param string $template The path of the template to include.
         */
        if ( $template = apply_filters( 'template_include', $template ) ) {
            $template_contents = file_get_contents( $template );
            $included_header = $included_footer = false;
            if ( false !== stripos( $template_contents, 'get_header();' ) ) {
                do_action( 'get_header', null );
                locate_template( 'header.php', true, false );
                $included_header = true;
            }
            include( $template );
            if ( false !== stripos( $template_contents, 'get_footer();' ) ) {
                do_action( 'get_footer', null );
                locate_template( 'footer.php', true, false );
                $included_footer = true;
            }
            if ( $included_header && $included_footer ) {
                global $wp_scripts;
                $wp_scripts->done = array();
            }
        }
        return;
    }

    public static function setPermalinkStructure( $struc = '/%postname%/' ) {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( $struc );
        $wp_rewrite->flush_rules();
        update_option( 'permalink_structure', $struc );
        flush_rewrite_rules( true );
    }

    function the_widget_form( $widget, $instance = array() ) {
        global $wp_widget_factory;

        $widget_obj = $wp_widget_factory->widgets[$widget];
        if ( ! ( $widget_obj instanceof WP_Widget ) ) {
            return;
        }

        $widget_obj->_set(-1);
        $widget_obj->form($instance);
    }

    function the_widget_form_update( $widget, $new_instance = array(), $old_instance = array() ) {
        global $wp_widget_factory;

        $widget_obj = $wp_widget_factory->widgets[$widget];
        if ( ! ( $widget_obj instanceof WP_Widget ) ) {
            return;
        }

        $widget_obj->_set(-1);
        return $widget_obj->update($new_instance, $old_instance);
    }


    public function tearDown()
    {
        parent::tearDown();
    }
}
?>