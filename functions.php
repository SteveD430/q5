<?php
/**
 * Q5 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Q5
 * @since 5.0.2
 */

 /**
  * If you're building a theme based on Q5, use a find and replace
  * to change 'q5' to the name of your theme in all the template files.
 */
require_once (get_template_directory() .'/inc/q5-toc.php');
require_once (get_template_directory() .'/inc/q5-category-pages.php');
 
/**
 * Q5 only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

if ( ! function_exists( 'q5_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function q5_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'q5', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'header-menu' 	 => 'Header Menu', 
				'frontpage-menu' => 'Home Page Central Menu',
				'footer-menu' 	 => 'Footer Menu',  
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		
				/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'status',
				'audio',
				'chat',
			)
		);

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 48,
				'flex-width'  => false,
				'flex-height' => false,
			)
		);
		
		/*
		 * Set-up Color Scheme support.
		 */
		 
		$color_scheme  = q5_get_color_scheme();
		$default_color = trim( $color_scheme[0], '#' );
		
				/**
		 * Filter Q5 custom-header support arguments.
		 *
		 * @since Q5 1.0
		 *
		 * @param array $args {
		 *     An array of custom-header support arguments.
		 *
		 *     @type string $default-color          Default color of the header.
		 *     @type string $default-attachment     Default attachment of the header.
		 * }
		 */
		
		//
		// DO NOT ENABLE custom-background in Q5. We have our own Background image mechanism.
		// add_theme_support('custom-background');


		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'q5' ),
					'shortName' => __( 'S', 'q5' ),
					'size'      => 19.5,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Normal', 'q5' ),
					'shortName' => __( 'M', 'q5' ),
					'size'      => 22,
					'slug'      => 'normal',
				),
				array(
					'name'      => __( 'Large', 'q5' ),
					'shortName' => __( 'L', 'q5' ),
					'size'      => 36.5,
					'slug'      => 'large',
				),
				array(
					'name'      => __( 'Huge', 'q5' ),
					'shortName' => __( 'XL', 'q5' ),
					'size'      => 49.5,
					'slug'      => 'huge',
				),
			)
		);

		// Editor color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary', 'q5' ),
					'slug'  => 'primary',
					'color' => q5_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 33 ),
				),
				array(
					'name'  => __( 'Secondary', 'q5' ),
					'slug'  => 'secondary',
					'color' => q5_hsl_hex( 'default' === get_theme_mod( 'primary_color' ) ? 199 : get_theme_mod( 'primary_color_hue', 199 ), 100, 23 ),
				),
				array(
					'name'  => __( 'Dark Gray', 'q5' ),
					'slug'  => 'dark-gray',
					'color' => '#111',
				),
				array(
					'name'  => __( 'Light Gray', 'q5' ),
					'slug'  => 'light-gray',
					'color' => '#767676',
				),
				array(
					'name'  => __( 'White', 'q5' ),
					'slug'  => 'white',
					'color' => '#FFF',
				),
			)
		);

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
		
				
		// Register Customizer Extensions
		add_action('customize_register', 'q5_customize_register');
	
		// Allow categories with pages.
		new q5_category_pages();
	
		//Add site-page category for post-type 'page';
		if (file_exists (ABSPATH.'/wp-admin/includes/taxonomy.php')) 
		{
			require_once (ABSPATH.'/wp-admin/includes/taxonomy.php'); 
		}

		wp_insert_category(
			array(
				'cat_name' 				=> 'Site Page',
				'category_description'	=> 'This category identifies those pages that will use the site-page template',
				'category_nicename' 	=> 'site-page',
				'taxonomy' 				=> 'category'
			)
		);
	};
};
add_action( 'after_setup_theme', 'q5_setup' );

add_action('customize_register', 'q5_customize_register');

/**
 * Insert category-site-page template into template search heirarchy.
 */
function q5_category_site_page_template( $templates = '' ) 
{ 
    $page = get_queried_object(); 
	q5_debug('Entered q5_category_site_page_template');
	if (q5_is_site_page ($page))
	{
		if ( ! is_array( $templates ) && ! empty( $templates ) ) 
		{ 
			$templates = locate_template( array( "category-site-page.php", $templates ), false ); 
		} 
		elseif ( empty( $templates ) ) 
		{ 
			$templates = locate_template( "category-site-page.php", false ); 
		} 
		else 
		{ 
			$new_template = locate_template( array( "category-site-page.php" ) ); 
			if ( ! empty( $new_template ) ) 
			{ 
				array_unshift( $templates, $new_template ); 
			} 
		} 
	}
    return $templates; 
} 

function q5_is_site_page ($page)
{
	if ($page == null || get_post_type($page) != 'page')
	{
			q5_debug ('Not a Page' );
		return false;
	}
		
	//Check category
	$categories = get_the_terms( $page, 'category' );
    if ( ! $categories || is_wp_error( $categories ) ) 
	{
		q5_debug ('Page does not have categories' );
		return false;
    }
	
	q5_debug ('Page has categories. Count == ' . count($categories) );
    $categories = array_values( $categories );
	if (count($categories) == 0)
	{
		return false;
	}
 
    foreach ( $categories as $value ) 
	{
       if ($value->slug == 'site-page')
	   {
		   	q5_debug ('Category value: ' . $value->slug);
		   return true;
	   }
    }
	return false;
}
add_filter( 'page_template', 'q5_category_site_page_template' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function q5_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Sidebar 1', 'q5' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'q5' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			)
		);
		
	register_sidebar(
		array(
			'name'          => __( 'Sidebar Page Right', 'q5' ),
			'id'            => 'sidebar-page-right',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'q5' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			)
		);
		
	register_sidebar(
		array(
			'name'          => __( 'Sidebar Page Left', 'q5' ),
			'id'            => 'sidebar-page-left',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'q5' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			)
		);
		
		register_widget ('q5_toc_widget');

}
add_action( 'widgets_init', 'q5_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function q5_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'q5_content_width', 640 );
}
add_action( 'after_setup_theme', 'q5_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function q5_scripts() {
	wp_enqueue_style( 'q5-colors', get_stylesheet_directory_uri() . '/styles/css_constants1.css' );
	wp_enqueue_style( 'q5-standard-htlm', get_stylesheet_directory_uri() . '/styles/css_constants2.css' );
	wp_enqueue_style( 'q5-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	wp_style_add_data( 'q5-style', 'rtl', 'replace' );

	if ( has_nav_menu( 'menu-1' ) ) {
		wp_enqueue_script( 'q5-priority-menu', get_theme_file_uri( '/js/priority-menu.js' ), array(), '1.0', true );
		wp_enqueue_script( 'q5-touch-navigation', get_theme_file_uri( '/js/touch-keyboard-navigation.js' ), array(), '1.0', true );
	}

	wp_enqueue_style( 'q5-print-style', get_template_directory_uri() . '/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_print_styles', 'q5_scripts', 99);

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function q5_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'q5_skip_link_focus_fix' );

/**
 * Enqueue supplemental block editor styles.
 */
function q5_editor_customizer_styles() {

	wp_enqueue_style( 'q5-editor-customizer-styles', get_theme_file_uri( '/style-editor-customizer.css' ), false, '1.0', 'all' );

	if ( 'custom' === get_theme_mod( 'primary_color' ) ) {
		// Include color patterns.
		require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
		wp_add_inline_style( 'q5-editor-customizer-styles', q5_custom_colors_css() );
	}
}
add_action( 'enqueue_block_editor_assets', 'q5_editor_customizer_styles' );

/**
 * function q5_colors_css_wrap
 * ======== ==================
 *
 * Decsription:
 * ------------
 * Display custom color CSS in customizer and on frontend.
 */
function q5_colors_css_wrap() {

	// Only include custom colors in customizer or frontend.
	if ( ( ! is_customize_preview() && 'default' === get_theme_mod( 'primary_color', 'default' ) ) || is_admin() ) {
		return;
	}

	require_once get_parent_theme_file_path( '/inc/color-patterns.php' );

	$primary_color = 199;
	if ( 'default' !== get_theme_mod( 'primary_color', 'default' ) ) {
		$primary_color = get_theme_mod( 'primary_color_hue', 199 );
	}
	?>

	<style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ? 'data-hue="' . absint( $primary_color ) . '"' : ''; ?>>
		<?php echo q5_custom_colors_css(); ?>
	</style>
<?php
}
add_action( 'wp_head', 'q5_colors_css_wrap' );

// use \DOMDocument;

/**
 * function insertAnchorPoints
 * ======== ==================
 *
 * Description:
 * ============
 * Add id attributes to the header elements that we wish to use as ToC elements.
 * Note: Anchor_id is passed in by Reference. This allows it to be modified at each recursive call.
 */
function insertAnchorPoints (&$anchor_id, DOMDocument $doc, $node)
{
	if (q5_toc_definition::is_toc_element($node))
	{
		if ($node->getAttribute('id') == null)
		{
			$node->setAttribute('id', q5_toc_definition::construct_anchor($anchor_id++));
		}
	}

	if ($node->childNodes != null)
	{
		foreach($node->childNodes as $childNode)
		{
			insertAnchorPoints ($anchor_id, $doc, $childNode);
		}
	}
}

/**
 * function q5_add_anchor_points_content_filter
 * ======== ===================================
 
 * Description:
 * ============
 * Add anchor points to the header elements that we wish to use as ToC.
 * This code is execute as a filter over the content.
*/

define ('TOP_MARKER', '<b id="q5_top"></b>');
define ('TAIL_MARKER', '<b id="q5_tail"></b>');

function q5_add_anchor_points_content_filter ( $content ) {
	
	// Only action single page entries that are not site-pages.
	if (! is_page() ||  ! is_main_query()) {
		return $content;
	}
	
	if ( has_term ('site-page', 'category', null)) {
		return $content;
	}

	// Use DOMDocument to parse content as HTML to determine anchor points.
	// However DOMDocument will top and tail content to create valid HTML document.
	// So we add marker points to enable us to return the extract we need.
	$anchor_id = 0;
	$dom = new DOMDocument();
	$dom->loadHTML(TOP_MARKER . $content . TAIL_MARKER);
	foreach ($dom->childNodes as $child)
	{
		insertAnchorPoints ($anchor_id, $dom, $child);
	}

	//Remove tags added by DOMDocument, plus the marker tags that we added.
	$content =  $dom->saveHTML();
	$content = substr ( $content, strpos( $content, TOP_MARKER, 0 ) + strlen(TOP_MARKER), -1 );
	$content = substr ( $content, 0, strpos( $content, TAIL_MARKER, 0 ) );
	
	return $content;
}
add_filter('the_content', 'q5_add_anchor_points_content_filter' );


/**
 * SVG Icons class.
 */
require get_template_directory() . '/classes/class-q5-svg-icons.php';

/**
 * Custom Comment Walker template.
 */
require get_template_directory() . '/classes/class-q5-walker-comment.php';

/**
 * Enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * SVG Icons related functions.
 */
require get_template_directory() . '/inc/icon-functions.php';

/**
 * Custom template tags for the theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Q5 functions
 */
require get_template_directory() . '/inc/q5-functions.php';


/**
 * Implement the Custom Header feature.
 *
 * @since Q5 1.0
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Q5 Debug - Removed during release.
 */
require get_template_directory() . '/inc/q5-debug.php';
?>


