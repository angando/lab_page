<?php
/**
 * educrat functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Educrat
 * @since Educrat 1.0.20
 */

define( 'EDUCRAT_THEME_VERSION', '1.0.20' );
define( 'EDUCRAT_DEMO_MODE', false );

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'educrat_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Educrat 1.0
 */
function educrat_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on educrat, use a find and replace
	 * to change 'educrat' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'educrat', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );
	add_image_size( 'educrat-course-list', 435, 330, true );
	add_image_size( 'educrat-course-grid', 410, 290, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'educrat' ),
		'mobile-primary' => esc_html__( 'Primary Mobile Menu', 'educrat' ),
		'vertical-menu' => esc_html__( 'Vertical Menu', 'educrat' ),
		'user-menu' => esc_html__( 'User Account Menu', 'educrat' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce", array('gallery_thumbnail_image_width' => 410) );
	
	add_theme_support( 'wc-product-gallery-slider' );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = educrat_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'educrat_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Enqueue editor styles.
	add_editor_style('css/style-editor.css');

	educrat_get_load_plugins();
}
endif; // educrat_setup
add_action( 'after_setup_theme', 'educrat_setup' );

/**
 * Load Google Front
 */
function educrat_get_fonts_url() {
    $fonts_url = '';

    $main_font = educrat_get_config('main-font');
	$main_font = !empty($main_font) ? json_decode($main_font, true) : array();
	if (  !empty($main_font['fontfamily']) ) {
		$main_font_family = $main_font['fontfamily'];
		$main_font_weight = !empty($main_font['fontweight']) ? $main_font['fontweight'] : '300,400,600,700';
		$main_font_subsets = !empty($main_font['subsets']) ? $main_font['subsets'] : 'latin,latin-ext';
	} else {
		$main_font_family = 'Muli';
		$main_font_weight = '300,400,600,700';
		$main_font_subsets = 'latin,latin-ext';
	}

	$heading_font = educrat_get_config('heading-font');
	$heading_font = !empty($heading_font) ? json_decode($heading_font, true) : array();
	if (  !empty($heading_font['fontfamily']) ) {
		$heading_font_family = $heading_font['fontfamily'];
		$heading_font_weight = !empty($heading_font['fontweight']) ? $heading_font['fontweight'] : '400,500,600,700';
		$heading_font_subsets = !empty($heading_font['subsets']) ? $heading_font['subsets'] : 'latin,latin-ext';
	} else {
		$heading_font_family = 'Jost';
		$heading_font_weight = '400,500,600,700';
		$heading_font_subsets = 'latin,latin-ext';
	}

	if ( $main_font_family == $heading_font_family ) {
		$font_weight = $main_font_weight.','.$heading_font_weight;
		$font_subsets = $main_font_subsets.','.$heading_font_subsets;
		$fonts = array(
			$main_font_family => array(
				'weight' => $font_weight,
				'subsets' => $font_subsets,
			),
		);
	} else {
		$fonts = array(
			$main_font_family => array(
				'weight' => $main_font_weight,
				'subsets' => $main_font_subsets,
			),
			$heading_font_family => array(
				'weight' => $heading_font_weight,
				'subsets' => $heading_font_subsets,
			),
		);
	}

	$font_families = array();
	$subset = array();

	foreach ($fonts as $key => $opt) {
		$font_families[] = $key.':'.$opt['weight'];
		$subset[] = $opt['subsets'];
	}



    $query_args = array(
        'family' => implode( '|', $font_families ),
        'subset' => urlencode( implode( ',', $subset ) ),
    );
		
	$protocol = is_ssl() ? 'https:' : 'http:';
    $fonts_url = add_query_arg( $query_args, $protocol .'//fonts.googleapis.com/css' );
    
 
    return esc_url( $fonts_url );
}

/**
 * Enqueue styles.
 *
 * @since Educrat 1.0
 */
function educrat_enqueue_styles() {

	$main_font = educrat_get_config('main_font');
	$main_font = !empty($main_font['font-family']) ? $main_font['font-family'] : 'GT Walsheim Pro';

	$heading_font = educrat_get_config('heading_font');
	$heading_font = !empty($heading_font['font-family']) ? $heading_font['font-family'] : 'GT Walsheim Pro';

	if ( $main_font == 'GT Walsheim Pro' || $heading_font == 'GT Walsheim Pro' ) {
		wp_enqueue_style( 'educrat-webfont', get_template_directory_uri() . '/css/webfonts.css', array(), '1.0.0' );
	}
	
	// load font
	wp_enqueue_style( 'educrat-theme-fonts', educrat_get_fonts_url(), array(), null );

	//load font awesome
	wp_enqueue_style( 'all-awesome', get_template_directory_uri() . '/css/all-awesome.css', array(), '5.11.2' );

	//load font flaticon
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css', array(), '1.0.0' );

	// load font themify icon
	wp_enqueue_style( 'themify-icons', get_template_directory_uri() . '/css/themify-icons.css', array(), '1.0.0' );
			
	// load animate version 3.6.0
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '3.6.0' );

	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap-rtl', get_template_directory_uri() . '/css/bootstrap.rtl.css', array(), '5.0.2' );
	} else {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '5.0.2' );
	}
	// slick
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.8.0' );
	// magnific-popup
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '1.1.0' );
	// perfect scrollbar
	wp_enqueue_style( 'perfect-scrollbar', get_template_directory_uri() . '/css/perfect-scrollbar.css', array(), '0.6.12' );
	
	// mobile menu
	wp_enqueue_style( 'sliding-menu', get_template_directory_uri() . '/css/sliding-menu.min.css', array(), '0.3.0' );

	// main style
	if( is_rtl() ){
		wp_enqueue_style( 'educrat-template', get_template_directory_uri() . '/css/template.rtl.css', array(), '1.0' );
	} else {
		wp_enqueue_style( 'educrat-template', get_template_directory_uri() . '/css/template.css', array(), '1.0' );
	}
	
	$custom_style = educrat_custom_styles();
	if ( !empty($custom_style) ) {
		wp_add_inline_style( 'educrat-template', $custom_style );
	}
	wp_enqueue_style( 'educrat-style', get_template_directory_uri() . '/style.css', array(), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'educrat_enqueue_styles', 1000 );

/**
 * Enqueue scripts.
 *
 * @since Educrat 1.0
 */
function educrat_enqueue_scripts() {
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	// bootstrap
	wp_enqueue_script( 'bootstrap-bundle', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.1.3', true );
	// slick
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	// countdown
	wp_register_script( 'countdown', get_template_directory_uri() . '/js/countdown.js', array( 'jquery' ), '20150315', true );
	wp_localize_script( 'countdown', 'educrat_countdown_opts', array(
		'days' => esc_html__('Days', 'educrat'),
		'hours' => esc_html__('Hrs', 'educrat'),
		'mins' => esc_html__('Mins', 'educrat'),
		'secs' => esc_html__('Secs', 'educrat'),
	));
	wp_enqueue_script( 'countdown' );
	
	// popup
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	// unviel
	wp_enqueue_script( 'jquery-unveil', get_template_directory_uri() . '/js/jquery.unveil.js', array( 'jquery' ), '1.1.0', true );
	
	// perfect scrollbar
	wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar.min.js', array( 'jquery' ), '1.5.0', true );
	
	if ( educrat_get_config('keep_header') ) {
		wp_enqueue_script( 'sticky', get_template_directory_uri() . '/js/sticky.min.js', array( 'jquery', 'elementor-waypoints' ), '4.0.1', true );
	}

	// mobile menu script
	wp_enqueue_script( 'sliding-menu', get_template_directory_uri() . '/js/sliding-menu.min.js', array( 'jquery' ), '0.3.0', true );

	// main script
	wp_register_script( 'educrat-functions', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
	wp_localize_script( 'educrat-functions', 'educrat_opts', array(
		'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
		'previous' => esc_html__('Previous', 'educrat'),
		'next' => esc_html__('Next', 'educrat'),
		'menu_back_text' => esc_html__('Back', 'educrat')
	));
	wp_enqueue_script( 'educrat-functions' );
	
	wp_add_inline_script( 'educrat-functions', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'educrat_enqueue_scripts', 1 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Educrat 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function educrat_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'educrat_search_form_modify' );


function educrat_get_config($name, $default = '') {
	global $educrat_theme_options;
	
	if ( empty($educrat_theme_options) ) {
		$educrat_theme_options = get_option('educrat_theme_options');
	}

    if ( isset($educrat_theme_options[$name]) ) {
        return $educrat_theme_options[$name];
    }
    return $default;
}

function educrat_set_exporter_ocdi_settings_option_keys($option_keys) {
	return array(
		'educrat_theme_options',
		'elementor_disable_color_schemes',
		'elementor_disable_typography_schemes',
		'elementor_allow_tracking',
		'elementor_cpt_support',
		'tutor_option'
	);
}
add_filter( 'apus_exporter_ocdi_settings_option_keys', 'educrat_set_exporter_ocdi_settings_option_keys' );

function educrat_disable_one_click_import() {
	return false;
}
add_filter('apus_frammework_enable_one_click_import', 'educrat_disable_one_click_import');

function educrat_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'educrat' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Home 9', 'educrat' ),
		'id'            => 'sidebar-menuprimary',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Home 10', 'educrat' ),
		'id'            => 'sidebar-menuprimary2',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Courses sidebar', 'educrat' ),
		'id'            => 'courses-filter',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Courses top sidebar', 'educrat' ),
		'id'            => 'courses-top-filter',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Course single sidebar', 'educrat' ),
		'id'            => 'course-single-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Event sidebar', 'educrat' ),
		'id'            => 'event-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Event Single sidebar', 'educrat' ),
		'id'            => 'event-single-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Blog sidebar', 'educrat' ),
		'id'            => 'blog-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Shop sidebar', 'educrat' ),
		'id'            => 'shop-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'educrat' ),
		'before_widget' => '<aside class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name' 				=> esc_html__( 'Header Mobile Bottom', 'educrat' ),
		'id' 				=> 'header-mobile-bottom',
		'description'   => esc_html__( 'Add widgets here to appear in your header mobile.', 'educrat' ),
		'before_widget' => '<aside class="%2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="title"><span>',
		'after_title'   => '</span></h2>',
	));
}
add_action( 'widgets_init', 'educrat_widgets_init' );

function educrat_get_load_plugins() {
	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Framework For Themes', 'educrat' ),
        'slug'                     => 'apus-frame',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-frame.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Elementor Page Builder', 'educrat' ),
	    'slug'                     => 'elementor',
	    'required'                 => true,
	);
	
	$plugins[] = array(
		'name'                     => esc_html__( 'Apus Simple Event', 'educrat' ),
        'slug'                     => 'apus-simple-event',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/apus-simple-event.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Revolution Slider', 'educrat' ),
        'slug'                     => 'revslider',
        'required'                 => true ,
        'source'				   => get_template_directory() . '/inc/plugins/revslider.zip'
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Cmb2', 'educrat' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'educrat' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	);

	$plugins[] = array(
		'name'                     => esc_html__( 'Contact Form 7', 'educrat' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	);

	// woocommerce plugins
	$plugins[] = array(
		'name'                     => esc_html__( 'Woocommerce', 'educrat' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	);
	
	$plugins[] = array(
        'name'                  => esc_html__( 'One Click Demo Import', 'educrat' ),
        'slug'                  => 'one-click-demo-import',
        'required'              => false,
    );

	$plugins[] = array(
        'name'                  => esc_html__( 'SVG Support', 'educrat' ),
        'slug'                  => 'svg-support',
        'required'              => false,
        'force_activation'      => false,
        'force_deactivation'    => false,
    );

	tgmpa( $plugins );
}

require get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';
get_template_part( '/inc/functions-helper' );
get_template_part( '/inc/functions-frontend' );

/**
 * Implement the Custom Header feature.
 *
 */
get_template_part( '/inc/custom-header' );
get_template_part( '/inc/classes/megamenu' );
get_template_part( '/inc/classes/mobilemenu' );
get_template_part( '/inc/classes/mobileverticalmenu' );
get_template_part( '/inc/classes/userinfo' );

/**
 * Custom template tags for this theme.
 *
 */
get_template_part( '/inc/template-tags' );

/**
 * Customizer additions.
 *
 */
get_template_part( '/inc/customizer/font/custom-controls' );
get_template_part( '/inc/customizer/customizer-custom-control' );
get_template_part( '/inc/customizer/customizer' );


if( educrat_is_cmb2_activated() ) {
	get_template_part( '/inc/vendors/cmb2/page' );
}

if( educrat_is_woocommerce_activated() ) {
	get_template_part( '/inc/vendors/woocommerce/functions' );
	get_template_part( '/inc/vendors/woocommerce/customizer' );
}

if ( educrat_is_learnpress_activated() ) {
	get_template_part( '/inc/vendors/learnpress/customizer' );
	get_template_part( '/inc/vendors/learnpress/functions' );
	get_template_part( '/inc/vendors/learnpress/functions-review' );
	get_template_part( '/inc/vendors/learnpress/functions-wishlist' );
}

if ( educrat_is_tutor_activated() ) {
	get_template_part( '/inc/vendors/tutor/customizer' );
	get_template_part( '/inc/vendors/tutor/functions' );
	get_template_part( '/inc/vendors/tutor/functions-review' );
}

if( educrat_is_simple_event_activated() ) {
	get_template_part( '/inc/vendors/simple-event/customizer' );
	get_template_part( '/inc/vendors/simple-event/functions' );
}

function educrat_register_load_widget() {
	get_template_part( '/inc/widgets/custom_menu' );
	get_template_part( '/inc/widgets/recent_post' );
	get_template_part( '/inc/widgets/search' );
	get_template_part( '/inc/widgets/socials' );
	
	get_template_part( '/inc/widgets/elementor-template' );
	
	if ( educrat_is_learnpress_activated() ) {
		get_template_part( '/inc/widgets/course-info' );

		get_template_part( '/inc/widgets/filter-category' );
		get_template_part( '/inc/widgets/filter-instructor' );
		get_template_part( '/inc/widgets/filter-level' );
		get_template_part( '/inc/widgets/filter-price' );
		get_template_part( '/inc/widgets/filter-rating' );
		get_template_part( '/inc/widgets/filter-keywords' );
	}
	
	if( educrat_is_simple_event_activated() ) {
		get_template_part( '/inc/widgets/events-tags' );
		get_template_part( '/inc/widgets/events-list' );
		get_template_part( '/inc/widgets/events-categories' );
		if( educrat_is_woocommerce_activated() ) {
			get_template_part( '/inc/widgets/event-product' );
		}
	}
}
add_action( 'widgets_init', 'educrat_register_load_widget' );


get_template_part( '/inc/vendors/elementor/functions' );
get_template_part( '/inc/vendors/one-click-demo-import/functions' );


/**
 * Custom Styles
 *
 */
get_template_part( '/inc/custom-styles' );


function enqueue_labs_assets() {
    wp_enqueue_style('labs-style', get_template_directory_uri() . '/css/labs.css', array(), '1.0.0');
    wp_enqueue_script('jquery');
    wp_enqueue_script('labs-script', get_template_directory_uri() . '/js/labs.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_labs_assets');

// Ajouter les champs personnalisés ACF pour les laboratoires
function add_acf_fields_for_labs() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_labs',
            'title' => 'Labs',
            'fields' => array(
                array(
                    'key' => 'field_lab_title',
                    'label' => 'Titre',
                    'name' => 'lab_title',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_lab_description',
                    'label' => 'Description',
                    'name' => 'lab_description',
                    'type' => 'textarea',
                ),
                array(
                    'key' => 'field_lab_technology',
                    'label' => 'Technologie',
                    'name' => 'lab_technology',
                    'type' => 'select',
                    'choices' => array(
                        'AWS' => 'AWS',
                        'Azure' => 'Azure',
                        'Google Cloud' => 'Google Cloud',
                        'Docker' => 'Docker',
                        'Kubernetes' => 'Kubernetes',
                    ),
                ),
                array(
                    'key' => 'field_lab_level',
                    'label' => 'Niveau',
                    'name' => 'lab_level',
                    'type' => 'select',
                    'choices' => array(
                        'Débutant' => 'Débutant',
                        'Intermédiaire' => 'Intermédiaire',
                        'Confirmé' => 'Confirmé',
                        'Expert' => 'Expert',
                    ),
                ),
                array(
                    'key' => 'field_lab_domain',
                    'label' => 'Domaine',
                    'name' => 'lab_domain',
                    'type' => 'select',
                    'choices' => array(
                        'DevOps' => 'DevOps',
                        'Sécurité' => 'Sécurité',
                        'Administration Système' => 'Administration Système',
                        'Administration Réseau' => 'Administration Réseau',
                        'Cybersécurité' => 'Cybersécurité',
                        'Cloud' => 'Cloud',
                    ),
                ),
                array(
                    'key' => 'field_lab_duration',
                    'label' => 'Durée (en minutes)',
                    'name' => 'lab_duration',
                    'type' => 'number',
                ),
                array(
                    'key' => 'field_lab_logo',
                    'label' => 'Logo',
                    'name' => 'lab_logo',
                    'type' => 'image',
                    'return_format' => 'id',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'lab',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'add_acf_fields_for_labs');

// Afficher les laboratoires
// Afficher les laboratoires
function display_labs_shortcode() {
    ob_start();
    ?>
    <div class="header">
        <div class="header-left">
            <div class="main-title">
                <img src="https://teachmemore.fr/wp-content/uploads/2024/07/icons8-chimiste-96.png" alt="Logo">
                Hands-on Labs
            </div>
            <?php if (current_user_can('manage_options')) : ?>
                <button class="add-lab" onclick="toggleForm()">+</button>
            <?php endif; ?>
        </div>
        <div class="search-bar-container">
            <div class="search-bar">
                <input type="text" id="search" placeholder="Browse the Labs Library...">
                <button id="search-button" onclick="handleSearch()">Search</button>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="filters">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="technology">Technologie</label>
                    <select id="technology">
                        <option value="">Sélectionner une Technologie</option>
                        <option value="AWS">AWS</option>
                        <option value="Azure">Azure</option>
                        <option value="Google Cloud">Google Cloud</option>
                        <option value="Docker">Docker</option>
                        <option value="Kubernetes">Kubernetes</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="skill-level">Niveau</label>
                    <select id="skill-level">
                        <option value="">Sélectionner un Niveau</option>
                        <option value="Débutant">Débutant</option>
                        <option value="Intermédiaire">Intermédiaire</option>
                        <option value="Confirmé">Confirmé</option>
                        <option value="Expert">Expert</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="domain">Domaine</label>
                    <select id="domain">
                        <option value="">Sélectionner un Domaine</option>
                        <option value="DevOps">DevOps</option>
                        <option value="Sécurité">Sécurité</option>
                        <option value="Administration Système">Administration Système</option>
                        <option value="Administration Réseau">Administration Réseau</option>
                        <option value="Cybersécurité">Cybersécurité</option>
                        <option value="Cloud">Cloud</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-container" id="form-container" style="display: none;">
            <div class="form-group">
                <label for="lab-title">Titre</label>
                <input type="text" id="lab-title" placeholder="Entrez le titre du lab">
            </div>
            <div class="form-group">
                <label for="lab-description">Description</label>
                <textarea id="lab-description" placeholder="Entrez la description du lab"></textarea>
            </div>
            <div class="form-group">
                <label for="lab-technology">Technologie</label>
                <select id="lab-technology">
                    <option value="">Sélectionner une Technologie</option>
                    <option value="AWS">AWS</option>
                    <option value="Azure">Azure</option>
                    <option value="Google Cloud">Google Cloud</option>
                    <option value="Docker">Docker</option>
                    <option value="Kubernetes">Kubernetes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lab-level">Niveau</label>
                <select id="lab-level">
                    <option value="">Sélectionner un Niveau</option>
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Confirmé">Confirmé</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lab-domain">Domaine</label>
                <select id="lab-domain">
                    <option value="">Sélectionner un Domaine</option>
                    <option value="DevOps">DevOps</option>
                    <option value="Sécurité">Sécurité</option>
                    <option value="Administration Système">Administration Système</option>
                    <option value="Administration Réseau">Administration Réseau</option>
                    <option value="Cybersécurité">Cybersécurité</option>
                    <option value="Cloud">Cloud</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lab-duration">Durée (en minutes)</label>
                <input type="number" id="lab-duration" placeholder="Entrez la durée du lab">
            </div>
            <div class="form-group">
                <label for="lab-logo">Logo</label>
                <input type="file" id="lab-logo" accept="image/*">
            </div>
            <div class="form-group">
                <button onclick="saveLab()">Enregistrer</button>
                <button onclick="toggleForm()">Annuler</button>
            </div>
        </div>
        <div id="labs-list">
            <!-- Les laboratoires seront chargés ici -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Document ready');
            window.toggleForm = function() {
                document.getElementById('form-container').classList.toggle('hidden');
            };

            window.handleSearch = function() {
                var searchTerm = document.getElementById('search').value;
                console.log('Search term:', searchTerm);
                if (!searchTerm) {
                    console.log("Please enter a term to search or clearing search will show all labs.");
                    loadLabsFromServer(); // Reload all labs if search is cleared or empty
                    return;
                }

                fetch('/wp-json/labs/v1/get?search=' + searchTerm)
                    .then(response => response.json())
                    .then(response => {
                        console.log('Labs received:', response);
                        var labsList = document.getElementById('labs-list');
                        labsList.innerHTML = ''; // Clear existing labs
                        if (response && response.length > 0) {
                            response.forEach(function(lab) {
                                addLabToPage(lab);
                            });
                        } else {
                            console.log("No labs found matching your criteria.");
                            labsList.innerHTML = '<p>No labs found.</p>'; // Display not found message
                        }
                    })
                    .catch(error => {
                        console.error("Error retrieving labs:", error);
                    });
            };

            function addLabToPage(lab) {
                var logoURL = lab.logo ? lab.logo : 'https://teachmemore.fr/wp-content/uploads/2024/07/icons8-chimiste-96.png'; // Fallback image URL
                var labItem = document.createElement('div');
                labItem.className = 'lab-item';
                labItem.setAttribute('data-id', lab.id);
                labItem.innerHTML = `
                    <img src="${logoURL}" alt="${lab.technology} logo">
                    <div>
                        <h3>${lab.title}</h3>
                        <p>${lab.description}</p>
                        <div class="details">
                            <span> 🟢 ${lab.technology}</span>
                            <span> 🟢 ${lab.level}</span>
                            <span> 🕒 ${lab.duration} mins</span>
                        </div>
                    </div>
                `;
                document.getElementById('labs-list').appendChild(labItem);
            }

            function loadLabsFromServer() {
                fetch('/wp-json/labs/v1/get')
                    .then(response => response.json())
                    .then(response => {
                        console.log('Labs loaded from server:', response);
                        var labsList = document.getElementById('labs-list');
                        labsList.innerHTML = ''; // Clear existing labs
                        response.forEach(function(lab) {
                            addLabToPage(lab);
                        });
                    })
                    .catch(error => {
                        console.error("Error loading labs:", error);
                        document.getElementById('labs-list').innerHTML = '<p>Error loading labs.</p>';
                    });
            }

            loadLabsFromServer(); // Call this function to load labs when the page is ready
        });
    </script>
    <?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode('display_labs', 'display_labs_shortcode');


add_action('rest_api_init', function() {
    register_rest_route('labs/v1', '/add', array(
        'methods' => 'POST',
        'callback' => 'add_lab',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));

    register_rest_route('labs/v1', '/get', array(
        'methods' => 'GET',
        'callback' => 'get_labs',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('labs/v1', '/update', array(
        'methods' => 'POST',
        'callback' => 'update_lab',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));

    register_rest_route('labs/v1', '/delete', array(
        'methods' => 'POST',
        'callback' => 'delete_lab',
        'permission_callback' => function () {
            return current_user_can('delete_posts');
        }
    ));
});

function handle_lab_image_upload($post_id, $params) {
    if (!empty($_FILES['lab-logo']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('lab-logo', $post_id);
        if (is_wp_error($attachment_id)) {
            error_log('Error uploading image: ' . $attachment_id->get_error_message());
            return false;
        }
        update_field('field_lab_logo', $attachment_id, $post_id);
        return true;
    } elseif (!empty($params['logo_url'])) {
        update_post_meta($post_id, '_lab_logo_url', sanitize_text_field($params['logo_url']));
        return true;
    }
    return false;
}

function add_lab($data) {
    $params = $data->get_params();

    $post_id = wp_insert_post(array(
        'post_title' => sanitize_text_field($params['title']),
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'lab'
    ));

    if (is_wp_error($post_id)) {
        error_log('Error creating lab: ' . $post_id->get_error_message());
        return new WP_Error('lab_error', 'Error creating lab', array('status' => 500));
    }

    update_field('lab_title', $params['title'], $post_id);
    update_field('lab_description', $params['description'], $post_id);
    update_field('lab_technology', $params['technology'], $post_id);
    update_field('lab_level', $params['level'], $post_id);
    update_field('lab_domain', $params['domain'], $post_id);
    update_field('lab_duration', $params['duration'], $post_id);

    handle_lab_image_upload($post_id, $params);

    return new WP_REST_Response('Lab added successfully', 200);
}

function update_lab($data) {
    $params = $data->get_params();
    $post_id = intval($params['id']);

    wp_update_post(array(
        'ID' => $post_id,
        'post_title' => sanitize_text_field($params['title']),
    ));

    update_field('lab_title', $params['title'], $post_id);
    update_field('lab_description', $params['description'], $post_id);
    update_field('lab_technology', $params['technology'], $post_id);
    update_field('lab_level', $params['level'], $post_id);
    update_field('lab_domain', $params['domain'], $post_id);
    update_field('lab_duration', $params['duration'], $post_id);

    handle_lab_image_upload($post_id, $params);

    return new WP_REST_Response('Lab updated successfully', 200);
}

function get_labs() {
    $args = [
        'post_type' => 'lab',
        'post_status' => 'publish',
        'numberposts' => -1,
        's' => isset($_GET['search']) ? $_GET['search'] : '',  // Search parameter
        'meta_query' => [
            'relation' => 'AND',
            // Technology filter
            [
                'key' => 'lab_technology',
                'value' => isset($_GET['technology']) && $_GET['technology'] !== '' ? $_GET['technology'] : '',
                'compare' => 'LIKE'
            ],
            // Level filter
            [
                'key' => 'lab_level',
                'value' => isset($_GET['level']) && $_GET['level'] !== '' ? $_GET['level'] : '',
                'compare' => 'LIKE'
            ],
            // Domain filter
            [
                'key' => 'lab_domain',
                'value' => isset($_GET['domain']) && $_GET['domain'] !== '' ? $_GET['domain'] : '',
                'compare' => 'LIKE'
            ]
        ]
    ];
    $labs = get_posts($args);

    $response = array();
    foreach ($labs as $lab) {
        $logo_url = wp_get_attachment_url(get_field('lab_logo', $lab->ID));
        $paid_page_url = get_field('lab_paid_page_url', $lab->ID);
        $response[] = array(
            'id' => $lab->ID,
            'title' => get_field('lab_title', $lab->ID),
            'description' => get_field('lab_description', $lab->ID),
            'technology' => get_field('lab_technology', $lab->ID),
            'level' => get_field('lab_level', $lab->ID),
            'domain' => get_field('lab_domain', $lab->ID),
            'duration' => get_field('lab_duration', $lab->ID),
            'logo' => $logo_url ? $logo_url : 'https://example.com/path/to/default-image.png',
            'paid_page_url' => $paid_page_url,
        );
    }

    return new WP_REST_Response($response, 200);
}
