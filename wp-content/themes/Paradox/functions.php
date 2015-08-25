<?php
/**
 * Bootstrap Basic theme
 * 
 * @package bootstrap-basic
 */


/**
 * Required WordPress variable.
 */
if (!isset($content_width)) {
	$content_width = 1170;
}


/**
 * Setup theme and register support wp features.
 */
function bootstrapBasicSetup() 
{
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * 
	 * copy from underscores theme
	 */
	load_theme_textdomain('bootstrap-basic', get_template_directory() . '/languages');
	
	// add theme support post and comment automatic feed links
	add_theme_support('automatic-feed-links');
	
	// enable support for post thumbnail or feature image on posts and pages
	add_theme_support('post-thumbnails');
	
	// add support menu
	register_nav_menus(array(
		'primary' => __('Primary Menu', 'bootstrap-basic'),
	));
	
	// add post formats support
	add_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link'));
	
	// add support custom background
	add_theme_support(
		'custom-background', 
		apply_filters(
			'bootstrap_basic_custom_background_args', 
			array(
				'default-color' => 'ffffff', 
				'default-image' => ''
			)
		)
	);
}// bootstrapBasicSetup
add_action('after_setup_theme', 'bootstrapBasicSetup');


/**
 * Register widget areas
 */
function bootstrapBasicWidgetsInit() 
{
	register_sidebar(array(
		'name'          => __('Header left', 'bootstrap-basic'),
		'id'            => 'header-left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title lead">',
		'after_title'   => '</p>',
	));

	register_sidebar(array(
		'name'          => __('Header right', 'bootstrap-basic'),
		'id'            => 'header-right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title lead">',
		'after_title'   => '</p>',
	));
	
	register_sidebar(array(
		'name'          => __('Navigation bar right', 'bootstrap-basic'),
		'id'            => 'navbar-right',
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	));

    register_sidebar(array(
    	'id' => 'sidebar2', //
    	'name' => __('Home Bottom Section', 'bonestheme'),
    	'description' => __('The widget area for bottom of home page.', 'bonestheme'),
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));	
	
	// register_sidebar(array(
	// 	'name'          => __('Sidebar left', 'bootstrap-basic'),
	// 	'id'            => 'sidebar-left',
	// 	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	// 	'after_widget'  => '</aside>',
	// 	'before_title'  => '<h2 class="widget-title">',
	// 	'after_title'   => '</h2>',
	// ));
	
	register_sidebar(array(
		'name'          => __('Sidebar Default', 'bootstrap-basic'),
		'id'            => 'sidebar-default',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar(array(
		'name'          => __('Footer left', 'bootstrap-basic'),
		'id'            => 'footer-left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	
	register_sidebar(array(
		'name'          => __('Footer right', 'bootstrap-basic'),
		'id'            => 'footer-right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => __('Footer Credits', 'bootstrap-basic'),
		'id'            => 'footer-credits',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));	

}// bootstrapBasicWidgetsInit
add_action('widgets_init', 'bootstrapBasicWidgetsInit');


/**
 * Enqueue scripts & styles
 */
function load_header_scripts() 
{
	// Stylesheets

	wp_enqueue_style('shame-style', get_template_directory_uri() . '/assets/css/shame.css');	
	wp_enqueue_style('paradox-style', get_stylesheet_uri());
	// wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/assets/css/vendor/bootstrap.min.css');

}// bootstrapBasicEnqueueScripts
add_action('wp_enqueue_scripts', 'load_header_scripts');


function load_js(){
	// Primary JS
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/assets/js/vendor/bootstrap.min.js');
	wp_enqueue_script('modernizr-script', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js');
	wp_enqueue_script('html5-shiv-script', get_template_directory_uri() . '/assets/js/vendor/html5shiv.js');		

	// Custom JS
	wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/custom.js');

	// Flat UI JS
	// <script src="js/jquery-1.8.3.min.js"></script>
 //    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
 //    <script src="js/jquery.ui.touch-punch.min.js"></script>
 //    <script src="js/bootstrap.min.js"></script>
 //    <script src="js/bootstrap-select.js"></script>
 //    <script src="js/bootstrap-switch.js"></script>
 //    <script src="js/flatui-checkbox.js"></script>
 //    <script src="js/flatui-radio.js"></script>
 //    <script src="js/jquery.tagsinput.js"></script>
 //    <script src="js/jquery.placeholder.js"></script>

} 
add_action('wp_footer', 'load_js');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';


/**
 * Custom dropdown menu and navbar in walker class
 */
require get_template_directory() . '/inc/BootstrapBasicMyWalkerNavMenu.php';


/**
 * Template functions
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * --------------------------------------------------------------
 * Theme widget & widget hooks
 * --------------------------------------------------------------
 */
require get_template_directory() . '/inc/widgets/BootstrapBasicSearchWidget.php';
require get_template_directory() . '/inc/template-widgets-hook.php';

// Image Thumbnail Sizes
add_image_size( 'sidebar-thumb', 500, 9999 );
