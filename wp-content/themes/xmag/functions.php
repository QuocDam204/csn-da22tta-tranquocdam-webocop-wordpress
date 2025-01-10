<?php
/**
 * Theme functions and definitions
 *
 * @package Xmag
 * @since Xmag 1.0
 */
function custom_search_query($search, $query) {
    if ($query->is_search() && !is_admin()) {
        global $wpdb;

        // Lấy từ khóa tìm kiếm
        $search_term = $query->get('s');

        if (!empty($search_term)) {
            // Tìm kiếm trong tiêu đề bài viết
            $title_search = $wpdb->prepare(
                " {$wpdb->posts}.post_title LIKE %s ",
                '%' . $wpdb->esc_like($search_term) . '%'
            );

            // Tìm kiếm trong thẻ tag (taxonomy 'post_tag')
            $tags_search = $wpdb->prepare(
                " EXISTS (
                    SELECT 1
                    FROM {$wpdb->term_relationships} AS tr
                    INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
                    WHERE tt.taxonomy = 'post_tag'
                    AND tr.object_id = {$wpdb->posts}.ID
                    AND t.name LIKE %s
                ) ",
                '%' . $wpdb->esc_like($search_term) . '%'
            );

            // Kết hợp điều kiện tìm kiếm
            $search = " AND ({$title_search} OR {$tags_search})";
        }
    }

    return $search;
}
add_filter('posts_search', 'custom_search_query', 10, 2);
/*tìm kiếm chatgpt*/

if ( ! function_exists( 'xmag_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function xmag_setup() {

	// Make theme available for translation. Translations can be filed in the /languages/ directory.
	load_theme_textdomain( 'xmag', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnail.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'xmag-thumb', 1200, 520, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 740;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'main_navigation'   => __( 'Main Menu', 'xmag' ),
		'top_navigation'    => __( 'Top Menu', 'xmag' ),
		'footer_navigation' => __( 'Footer Menu', 'xmag' ),
		'social_navigation' => __( 'Social Menu', 'xmag' ),
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add AMP support
	add_theme_support( 'amp' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );

	// Set up the WordPress Custom Background Feature.
	$defaults = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);
	add_theme_support( 'custom-background', $defaults );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'inc/css/editor-style.css', xmag_fonts_url() ) );

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Small', 'xmag' ),
				'size' => 14,
				'slug' => 'small',
			),
			array(
				'name' => __( 'Normal', 'xmag' ),
				'size' => 16,
				'slug' => 'normal',
			),
			array(
				'name' => __( 'Large', 'xmag' ),
				'size' => 24,
				'slug' => 'large',
			),
			array(
				'name' => __( 'Huge', 'xmag' ),
				'size' => 32,
				'slug' => 'huge',
			),
		)
	);

	// Add support for custom color scheme.
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Dark Gray', 'xmag' ),
			'slug'  => 'dark-gray',
			'color' => '#222222',
		),
		array(
			'name'  => __( 'Medium Gray', 'xmag' ),
			'slug'  => 'medium-gray',
			'color' => '#333333',
		),
		array(
			'name'  => __( 'Gray', 'xmag' ),
			'slug'  => 'gray',
			'color' => '#555555',
		),
		array(
			'name'  => __( 'Light Gray', 'xmag' ),
			'slug'  => 'light-gray',
			'color' => '#999999',
		),
		array(
			'name'  => __( 'White', 'xmag' ),
			'slug'  => 'white',
			'color' => '#ffffff',
		),
		array(
			'name'  => __( 'Accent Color', 'xmag' ),
			'slug'  => 'accent',
			'color' => esc_attr( get_option( 'accent_color', '#e54e53' ) ),
		),
	) );

}
endif; // xmag_setup
add_action( 'after_setup_theme', 'xmag_setup' );


if ( ! function_exists( 'xmag_fonts_url' ) ) :
/**
 * Register Google fonts.
 *
 * @return string Google fonts URL for the theme.
 */
function xmag_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Open Sans: on or off', 'xmag' ) ) {
		$fonts[] = 'Open Sans:400,700,300,400italic,700italic';
	}

	/* translators: If there are characters in your language that are not supported by Roboto, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Roboto: on or off', 'xmag' ) ) {
		$fonts[] = 'Roboto:400,700,300';
	}

	/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'xmag' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family'  => urlencode( implode( '|', $fonts ) ),
			'subset'  => urlencode( $subsets ),
			'display' => 'swap',
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;


/**
 * Add preconnect for Google Fonts.
 *
 * @since Xmag 1.3.3
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function xmag_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'xmag-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'xmag_resource_hints', 10, 2 );


/**
 * Enqueues scripts and styles.
 */
function xmag_scripts() {

	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'xmag-fonts', xmag_fonts_url(), array(), null );

	// Add Icons font, used in the main stylesheet.
	wp_enqueue_style( 'xmag-icons', get_template_directory_uri() . '/assets/css/simple-line-icons.min.css', array(), '2.3.3' );

	// Main stylesheet.
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'xmag-style', get_stylesheet_uri(), array(), $theme_version );

	// Social icons
	wp_add_inline_style( 'xmag-style', xmag_social_icons() );

	if( ! xmag_is_amp() ) {
		// Main js.
		wp_enqueue_script( 'xmag-script', get_template_directory_uri() . '/assets/js/script.js', array(), '20210930', true );
		// Comment reply script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'xmag_scripts' );


/**
 * Register widget area
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function xmag_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'xmag' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your Sidebar.', 'xmag' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area Left', 'xmag' ),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area Center', 'xmag' ),
		'id'            => 'footer-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area Right', 'xmag' ),
		'id'            => 'footer-3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
}
add_action( 'widgets_init', 'xmag_widgets_init' );


/**
 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
 */


if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}


/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 */
function xmag_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#content">' . __( 'Skip to content', 'xmag' ) . '</a>';
}
add_action( 'wp_body_open', 'xmag_skip_link', 5 );


/**
 * Determine whether the current response being served as AMP.
 */
function xmag_is_amp() {
	return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
}


/**
 * Adds a Sub Nav Toggle to the Mobile Menu.
 */
function xmag_add_sub_menu_toggles( $output, $item, $depth, $args ) {
	if( ! xmag_is_amp() ) {
		if ( isset( $args->show_sub_menu_toggles ) && $args->show_sub_menu_toggles && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$output = $output . '<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">' . __( 'Show sub menu', 'xmag' ) . '</span>' . '</button>';
		}
	} else {
		if ( isset( $args->show_sub_menu_toggles ) && $args->show_sub_menu_toggles && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$output = $output . '<button data-amp-bind-class="visible' . $item->ID . ' ? \'dropdown-toggle is-open\' : \'dropdown-toggle is-closed\'" on="tap:AMP.setState({visible' . $item->ID . ': !visible' . $item->ID . '})" class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">' . __( 'Show sub menu', 'xmag' ) . '</span>' . '</button>';
		}
	}
	return $output;
}
add_filter( 'walker_nav_menu_start_el', 'xmag_add_sub_menu_toggles', 10, 4 );


/**
 * Implement the WordPress Custom Header Feature.
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/customizer-css.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Main Menu Fallback.
 */
function xmag_fallback_menu() {
	echo '<ul class="main-menu">';
	wp_list_pages( 'title_li=' );
	echo '</ul>';
}


/*
 * Site Logo
 */
function xmag_site_logo() {

	$logo_url    = esc_url( get_theme_mod( 'xmag_logo' ) );
	$logo_height = absint ( get_theme_mod( 'xmag_logo_height', 40 ) );
	if ( ! empty( $logo_height ) ) {
		$logo_size = $logo_height . 'px';
	} else {
		$logo_size = '40px';
	}

	$logo_img = sprintf(
		'<img src="%s" style="max-height:%s" alt="%s">',
		$logo_url,
		$logo_size,
		get_bloginfo('name')
	);

	$link = sprintf(
		'<a href="%s" rel="home">%s</a>',
		esc_url(home_url('/')),
		$logo_img
	);

	if (is_front_page() || is_home()) {
		$logo_title = '<h1 id="logo">' . $link . '</h1>';
	} else {
		$logo_title = '<p id="logo">' . $link . '</p>';
	}

	echo $logo_title;
}


/**
 * Filter the except length.
 */
function xmag_custom_excerpt_length( $length ) {

	if ( is_home() ) {
		$excerpt_length = get_theme_mod( 'xmag_excerpt_size', 25 );
	} elseif ( is_archive() || is_search() ) {
		$excerpt_length = get_theme_mod( 'xmag_archive_excerpt_size', 25 );
	} else {
		$excerpt_length = 30;
	}
	return intval($excerpt_length);
}
add_filter( 'excerpt_length', 'xmag_custom_excerpt_length', 999 );


/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function xmag_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'xmag_excerpt_more' );


/**
 * Thumb size for Layout 1 and 2
 */
function xmag_thumb_size() {
	if ( is_home() ) {
		if ( get_theme_mod( 'xmag_blog', 'layout2' ) == 'layout1' ) {
			$thumb_size = 'thumbnail';
		} else {
			$thumb_size = 'medium';
		}
	} else {
		if ( get_theme_mod( 'xmag_archive', 'layout2' ) == 'layout1' ) {
			$thumb_size = 'thumbnail';
		} else {
			$thumb_size = 'medium';
		}
	}
	return esc_attr( $thumb_size );
}


/**
 * Add specific CSS class by filter.
 */
function xmag_custom_classes( $classes ) {
	// add 'class-name' to the $classes array
	$classes[] = get_option( 'xmag_layout_style', 'site-fullwidth' );

	// Adds a class to Homepage
	if ( is_home() ) {
		$classes[] = get_theme_mod( 'xmag_blog', 'layout2' );
	}

	// Adds a class to Archive and Search Pages
	if ( is_archive() || is_search() ) {
		$classes[] = get_theme_mod( 'xmag_archive', 'layout2' );
	}

	// return the $classes array
	return $classes;
}
add_filter( 'body_class', 'xmag_custom_classes' );


/*
 * Widget Style
 */
function xmag_widget_style() {
	$xmag_widget = get_theme_mod( 'xmag_widget_style', 'grey' );
	echo esc_attr( 'widget-' . $xmag_widget );
}


/**
 * Display Header Image.
 */
function xmag_header_image() {
	if ( get_theme_mod( 'xmag_show_header_image', 1 ) ) {

		if ( is_home() || is_front_page() ) { ?>
			<figure class="header-image">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
				</a>
			</figure>
		<?php }

	} else { ?>

		<figure class="header-image">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
			</a>
		</figure>

	<?php }
}


/**
 * Blog: Posts Templates
 */

function xmag_blog_post_template() {

	$blog_type = get_theme_mod( 'xmag_blog', 'layout2' );

	if ( $blog_type == 'layout3' || $blog_type == 'layout11' ) {
		// Layout 3,11
		$blog_template = 'content-large';
	} else {
		// Layout 1,2
		$blog_template = 'content';
	}
	return sanitize_file_name($blog_template);
}


/**
 * Archives: Posts Templates
 */
function xmag_archive_post_template() {

	$archive_type = get_theme_mod( 'xmag_archive', 'layout2' );

	if ( $archive_type == 'layout3' ) {
		// Layout 3
		$archive_template = 'content-large';
	} else {
		// Layout 1,2
		if ( is_search() ) {
			$archive_template = 'content-search';
		} else {
			$archive_template = 'content';
		}
	}
	return sanitize_file_name($archive_template);
}


/**
 * Prints Credits in the Footer
 */
function xmag_credits() {
	$website_credits = '';
	$website_author = get_bloginfo('name');
	$website_date = date_i18n(__( 'Y', 'xmag' ) );
	$website_credits = '&copy; ' . $website_date . ' ' . $website_author;
	echo esc_html( $website_credits );
}


/**
 * Add styles for social icons
 */
function xmag_social_icons() {
	$social_icons = '
	html {
		--dl-icon-apple: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M15.8,2c0,0,0.1,0,0.1,0c0.1,1.4-0.4,2.5-1.1,3.2C14.3,6,13.4,6.7,12,6.6c-0.1-1.4,0.4-2.4,1.1-3.1 C13.7,2.8,14.8,2.1,15.8,2z"/><path d="M20.2,16.7C20.2,16.7,20.2,16.7,20.2,16.7c-0.4,1.2-1,2.3-1.7,3.2c-0.6,0.9-1.4,2-2.8,2c-1.2,0-2-0.8-3.2-0.8 c-1.3,0-2,0.6-3.2,0.8c-0.1,0-0.3,0-0.4,0c-0.9-0.1-1.6-0.8-2.1-1.4c-1.5-1.8-2.7-4.2-2.9-7.3c0-0.3,0-0.6,0-0.9 c0.1-2.2,1.2-4,2.6-4.8c0.7-0.5,1.8-0.8,2.9-0.7c0.5,0.1,1,0.2,1.4,0.4c0.4,0.2,0.9,0.4,1.4,0.4c0.3,0,0.7-0.2,1-0.3 c1-0.4,1.9-0.8,3.2-0.6c1.5,0.2,2.6,0.9,3.3,1.9c-1.3,0.8-2.3,2.1-2.1,4.2C17.6,14.9,18.8,16,20.2,16.7z"/></svg>\');
		--dl-icon-discord: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M18.9,5.7c-1.3-0.6-2.7-1-4.1-1.3c-0.2,0.3-0.4,0.7-0.5,1.1c-1.5-0.2-3.1-0.2-4.6,0C9.6,5.1,9.4,4.8,9.2,4.4 C7.8,4.7,6.4,5.1,5.1,5.7c-2.6,3.9-3.3,7.6-3,11.3l0,0c1.5,1.1,3.2,2,5.1,2.5c0.4-0.6,0.8-1.1,1.1-1.7c-0.6-0.2-1.2-0.5-1.7-0.8 c0.1-0.1,0.3-0.2,0.4-0.3c3.2,1.5,6.9,1.5,10.1,0c0.1,0.1,0.3,0.2,0.4,0.3c-0.5,0.3-1.1,0.6-1.7,0.8c0.3,0.6,0.7,1.2,1.1,1.7 c1.8-0.5,3.5-1.4,5.1-2.5l0,0C22.3,12.7,21.2,9,18.9,5.7z M8.7,14.8c-1,0-1.8-0.9-1.8-2s0.8-2,1.8-2s1.8,0.9,1.8,2 S9.7,14.8,8.7,14.8z M15.3,14.8c-1,0-1.8-0.9-1.8-2s0.8-2,1.8-2s1.8,0.9,1.8,2S16.3,14.8,15.3,14.8z"/></svg>\');
		--dl-icon-dribble: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12,22C6.486,22,2,17.514,2,12S6.486,2,12,2c5.514,0,10,4.486,10,10S17.514,22,12,22z M20.434,13.369 c-0.292-0.092-2.644-0.794-5.32-0.365c1.117,3.07,1.572,5.57,1.659,6.09C18.689,17.798,20.053,15.745,20.434,13.369z M15.336,19.876c-0.127-0.749-0.623-3.361-1.822-6.477c-0.019,0.006-0.038,0.013-0.056,0.019c-4.818,1.679-6.547,5.02-6.701,5.334 c1.448,1.129,3.268,1.803,5.243,1.803C13.183,20.555,14.311,20.313,15.336,19.876z M5.654,17.724 c0.193-0.331,2.538-4.213,6.943-5.637c0.111-0.036,0.224-0.07,0.337-0.102c-0.214-0.485-0.448-0.971-0.692-1.45 c-4.266,1.277-8.405,1.223-8.778,1.216c-0.003,0.087-0.004,0.174-0.004,0.261C3.458,14.207,4.29,16.21,5.654,17.724z M3.639,10.264 c0.382,0.005,3.901,0.02,7.897-1.041c-1.415-2.516-2.942-4.631-3.167-4.94C5.979,5.41,4.193,7.613,3.639,10.264z M9.998,3.709 c0.236,0.316,1.787,2.429,3.187,5c3.037-1.138,4.323-2.867,4.477-3.085C16.154,4.286,14.17,3.471,12,3.471 C11.311,3.471,10.641,3.554,9.998,3.709z M18.612,6.612C18.432,6.855,17,8.69,13.842,9.979c0.199,0.407,0.389,0.821,0.567,1.237 c0.063,0.148,0.124,0.295,0.184,0.441c2.842-0.357,5.666,0.215,5.948,0.275C20.522,9.916,19.801,8.065,18.612,6.612z"></path></svg>\');
		--dl-icon-facebook: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg>\');
		--dl-icon-flickr: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M6.5,7c-2.75,0-5,2.25-5,5s2.25,5,5,5s5-2.25,5-5S9.25,7,6.5,7z M17.5,7c-2.75,0-5,2.25-5,5s2.25,5,5,5s5-2.25,5-5 S20.25,7,17.5,7z"></path></svg>\');
		--dl-icon-github: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12,2C6.477,2,2,6.477,2,12c0,4.419,2.865,8.166,6.839,9.489c0.5,0.09,0.682-0.218,0.682-0.484 c0-0.236-0.009-0.866-0.014-1.699c-2.782,0.602-3.369-1.34-3.369-1.34c-0.455-1.157-1.11-1.465-1.11-1.465 c-0.909-0.62,0.069-0.608,0.069-0.608c1.004,0.071,1.532,1.03,1.532,1.03c0.891,1.529,2.341,1.089,2.91,0.833 c0.091-0.647,0.349-1.086,0.635-1.337c-2.22-0.251-4.555-1.111-4.555-4.943c0-1.091,0.39-1.984,1.03-2.682 C6.546,8.54,6.202,7.524,6.746,6.148c0,0,0.84-0.269,2.75,1.025C10.295,6.95,11.15,6.84,12,6.836 c0.85,0.004,1.705,0.114,2.504,0.336c1.909-1.294,2.748-1.025,2.748-1.025c0.546,1.376,0.202,2.394,0.1,2.646 c0.64,0.699,1.026,1.591,1.026,2.682c0,3.841-2.337,4.687-4.565,4.935c0.359,0.307,0.679,0.917,0.679,1.852 c0,1.335-0.012,2.415-0.012,2.741c0,0.269,0.18,0.579,0.688,0.481C19.138,20.161,22,16.416,22,12C22,6.477,17.523,2,12,2z"></path></svg>\');
		--dl-icon-instagram: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12,4.622c2.403,0,2.688,0.009,3.637,0.052c0.877,0.04,1.354,0.187,1.671,0.31c0.42,0.163,0.72,0.358,1.035,0.673 c0.315,0.315,0.51,0.615,0.673,1.035c0.123,0.317,0.27,0.794,0.31,1.671c0.043,0.949,0.052,1.234,0.052,3.637 s-0.009,2.688-0.052,3.637c-0.04,0.877-0.187,1.354-0.31,1.671c-0.163,0.42-0.358,0.72-0.673,1.035 c-0.315,0.315-0.615,0.51-1.035,0.673c-0.317,0.123-0.794,0.27-1.671,0.31c-0.949,0.043-1.233,0.052-3.637,0.052 s-2.688-0.009-3.637-0.052c-0.877-0.04-1.354-0.187-1.671-0.31c-0.42-0.163-0.72-0.358-1.035-0.673 c-0.315-0.315-0.51-0.615-0.673-1.035c-0.123-0.317-0.27-0.794-0.31-1.671C4.631,14.688,4.622,14.403,4.622,12 s0.009-2.688,0.052-3.637c0.04-0.877,0.187-1.354,0.31-1.671c0.163-0.42,0.358-0.72,0.673-1.035 c0.315-0.315,0.615-0.51,1.035-0.673c0.317-0.123,0.794-0.27,1.671-0.31C9.312,4.631,9.597,4.622,12,4.622 M12,3 C9.556,3,9.249,3.01,8.289,3.054C7.331,3.098,6.677,3.25,6.105,3.472C5.513,3.702,5.011,4.01,4.511,4.511 c-0.5,0.5-0.808,1.002-1.038,1.594C3.25,6.677,3.098,7.331,3.054,8.289C3.01,9.249,3,9.556,3,12c0,2.444,0.01,2.751,0.054,3.711 c0.044,0.958,0.196,1.612,0.418,2.185c0.23,0.592,0.538,1.094,1.038,1.594c0.5,0.5,1.002,0.808,1.594,1.038 c0.572,0.222,1.227,0.375,2.185,0.418C9.249,20.99,9.556,21,12,21s2.751-0.01,3.711-0.054c0.958-0.044,1.612-0.196,2.185-0.418 c0.592-0.23,1.094-0.538,1.594-1.038c0.5-0.5,0.808-1.002,1.038-1.594c0.222-0.572,0.375-1.227,0.418-2.185 C20.99,14.751,21,14.444,21,12s-0.01-2.751-0.054-3.711c-0.044-0.958-0.196-1.612-0.418-2.185c-0.23-0.592-0.538-1.094-1.038-1.594 c-0.5-0.5-1.002-0.808-1.594-1.038c-0.572-0.222-1.227-0.375-2.185-0.418C14.751,3.01,14.444,3,12,3L12,3z M12,7.378 c-2.552,0-4.622,2.069-4.622,4.622S9.448,16.622,12,16.622s4.622-2.069,4.622-4.622S14.552,7.378,12,7.378z M12,15 c-1.657,0-3-1.343-3-3s1.343-3,3-3s3,1.343,3,3S13.657,15,12,15z M16.804,6.116c-0.596,0-1.08,0.484-1.08,1.08 s0.484,1.08,1.08,1.08c0.596,0,1.08-0.484,1.08-1.08S17.401,6.116,16.804,6.116z"></path></svg>\');
		--dl-icon-linkedin: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M19.7,3H4.3C3.582,3,3,3.582,3,4.3v15.4C3,20.418,3.582,21,4.3,21h15.4c0.718,0,1.3-0.582,1.3-1.3V4.3 C21,3.582,20.418,3,19.7,3z M8.339,18.338H5.667v-8.59h2.672V18.338z M7.004,8.574c-0.857,0-1.549-0.694-1.549-1.548 c0-0.855,0.691-1.548,1.549-1.548c0.854,0,1.547,0.694,1.547,1.548C8.551,7.881,7.858,8.574,7.004,8.574z M18.339,18.338h-2.669 v-4.177c0-0.996-0.017-2.278-1.387-2.278c-1.389,0-1.601,1.086-1.601,2.206v4.249h-2.667v-8.59h2.559v1.174h0.037 c0.356-0.675,1.227-1.387,2.526-1.387c2.703,0,3.203,1.779,3.203,4.092V18.338z"></path></svg>\');
		--dl-icon-medium: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M20.962,7.257l-5.457,8.867l-3.923-6.375l3.126-5.08c0.112-0.182,0.319-0.286,0.527-0.286c0.05,0,0.1,0.008,0.149,0.02 c0.039,0.01,0.078,0.023,0.114,0.041l5.43,2.715l0.006,0.003c0.004,0.002,0.007,0.006,0.011,0.008 C20.971,7.191,20.98,7.227,20.962,7.257z M9.86,8.592v5.783l5.14,2.57L9.86,8.592z M15.772,17.331l4.231,2.115 C20.554,19.721,21,19.529,21,19.016V8.835L15.772,17.331z M8.968,7.178L3.665,4.527C3.569,4.479,3.478,4.456,3.395,4.456 C3.163,4.456,3,4.636,3,4.938v11.45c0,0.306,0.224,0.669,0.498,0.806l4.671,2.335c0.12,0.06,0.234,0.088,0.337,0.088 c0.29,0,0.494-0.225,0.494-0.602V7.231C9,7.208,8.988,7.188,8.968,7.178z"></path></svg>\');
		--dl-icon-pinterest: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12.289,2C6.617,2,3.606,5.648,3.606,9.622c0,1.846,1.025,4.146,2.666,4.878c0.25,0.111,0.381,0.063,0.439-0.169 c0.044-0.175,0.267-1.029,0.365-1.428c0.032-0.128,0.017-0.237-0.091-0.362C6.445,11.911,6.01,10.75,6.01,9.668 c0-2.777,2.194-5.464,5.933-5.464c3.23,0,5.49,2.108,5.49,5.122c0,3.407-1.794,5.768-4.13,5.768c-1.291,0-2.257-1.021-1.948-2.277 c0.372-1.495,1.089-3.112,1.089-4.191c0-0.967-0.542-1.775-1.663-1.775c-1.319,0-2.379,1.309-2.379,3.059 c0,1.115,0.394,1.869,0.394,1.869s-1.302,5.279-1.54,6.261c-0.405,1.666,0.053,4.368,0.094,4.604 c0.021,0.126,0.167,0.169,0.25,0.063c0.129-0.165,1.699-2.419,2.142-4.051c0.158-0.59,0.817-2.995,0.817-2.995 c0.43,0.784,1.681,1.446,3.013,1.446c3.963,0,6.822-3.494,6.822-7.833C20.394,5.112,16.849,2,12.289,2"></path></svg>\');
		--dl-icon-rss: url(\'data:image/svg+xml,<svg version="1.0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M2,8.7V12c5.5,0,10,4.5,10,10h3.3C15.3,14.6,9.4,8.7,2,8.7z M2,2v3.3c9.2,0,16.7,7.5,16.7,16.7H22C22,11,13,2,2,2z M4.5,17 C3.1,17,2,18.1,2,19.5S3.1,22,4.5,22S7,20.9,7,19.5S5.9,17,4.5,17z"/></svg>\');
		--dl-icon-share: url(\'data:image/svg+xml,<svg version="1.0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18,15c-1.1,0-2.1,0.5-2.8,1.2l-5.3-3.1C9.9,12.7,10,12.4,10,12c0-0.4-0.1-0.7-0.2-1.1l5.3-3.1C15.9,8.5,16.9,9,18,9 c2.2,0,4-1.8,4-4s-1.8-4-4-4s-4,1.8-4,4c0,0.4,0.1,0.7,0.2,1.1L8.8,9.2C8.1,8.5,7.1,8,6,8c-2.2,0-4,1.8-4,4c0,2.2,1.8,4,4,4 c1.1,0,2.1-0.5,2.8-1.2l5.3,3.1C14.1,18.3,14,18.6,14,19c0,2.2,1.8,4,4,4s4-1.8,4-4S20.2,15,18,15z M18,3c1.1,0,2,0.9,2,2 s-0.9,2-2,2s-2-0.9-2-2S16.9,3,18,3z M6,14c-1.1,0-2-0.9-2-2c0-1.1,0.9-2,2-2s2,0.9,2,2C8,13.1,7.1,14,6,14z M18,21 c-1.1,0-2-0.9-2-2c0-0.4,0.1-0.7,0.3-1c0,0,0,0,0,0c0,0,0,0,0,0c0.3-0.6,1-1,1.7-1c1.1,0,2,0.9,2,2S19.1,21,18,21z"/></svg>\');
		--dl-icon-spotify: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12,2C6.477,2,2,6.477,2,12c0,5.523,4.477,10,10,10c5.523,0,10-4.477,10-10C22,6.477,17.523,2,12,2 M16.586,16.424 c-0.18,0.295-0.563,0.387-0.857,0.207c-2.348-1.435-5.304-1.76-8.785-0.964c-0.335,0.077-0.67-0.133-0.746-0.469 c-0.077-0.335,0.132-0.67,0.469-0.746c3.809-0.871,7.077-0.496,9.713,1.115C16.673,15.746,16.766,16.13,16.586,16.424 M17.81,13.7 c-0.226,0.367-0.706,0.482-1.072,0.257c-2.687-1.652-6.785-2.131-9.965-1.166C6.36,12.917,5.925,12.684,5.8,12.273 C5.675,11.86,5.908,11.425,6.32,11.3c3.632-1.102,8.147-0.568,11.234,1.328C17.92,12.854,18.035,13.335,17.81,13.7 M17.915,10.865 c-3.223-1.914-8.54-2.09-11.618-1.156C5.804,9.859,5.281,9.58,5.131,9.086C4.982,8.591,5.26,8.069,5.755,7.919 c3.532-1.072,9.404-0.865,13.115,1.338c0.445,0.264,0.59,0.838,0.327,1.282C18.933,10.983,18.359,11.129,17.915,10.865"></path></svg>\');
		--dl-icon-telegram: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M4.2,11c4.8-2.1,8.1-3.5,9.7-4.2c4.6-1.9,5.6-2.2,6.2-2.3c0.1,0,0.4,0,0.6,0.2c0.2,0.1,0.2,0.3,0.2,0.4c0,0.1,0,0.4,0,0.7 c-0.2,2.6-1.3,9-1.9,11.9c-0.2,1.2-0.7,1.7-1.1,1.7c-1,0.1-1.7-0.6-2.6-1.2c-1.5-1-2.3-1.6-3.7-2.5C10,14.6,11.1,14,12,13.1 c0.2-0.3,4.5-4.1,4.6-4.5c0,0,0-0.2-0.1-0.3s-0.2-0.1-0.3,0c-0.1,0-2.5,1.6-7,4.6c-0.7,0.5-1.3,0.7-1.8,0.7c-0.6,0-1.7-0.3-2.6-0.6 c-1-0.3-1.9-0.5-1.8-1.1C3,11.6,3.5,11.3,4.2,11z"/></svg>\');
		--dl-icon-tiktok: url(\'data:image/svg+xml,<svg viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16.708 0.027c1.745-0.027 3.48-0.011 5.213-0.027 0.105 2.041 0.839 4.12 2.333 5.563 1.491 1.479 3.6 2.156 5.652 2.385v5.369c-1.923-0.063-3.855-0.463-5.6-1.291-0.76-0.344-1.468-0.787-2.161-1.24-0.009 3.896 0.016 7.787-0.025 11.667-0.104 1.864-0.719 3.719-1.803 5.255-1.744 2.557-4.771 4.224-7.88 4.276-1.907 0.109-3.812-0.411-5.437-1.369-2.693-1.588-4.588-4.495-4.864-7.615-0.032-0.667-0.043-1.333-0.016-1.984 0.24-2.537 1.495-4.964 3.443-6.615 2.208-1.923 5.301-2.839 8.197-2.297 0.027 1.975-0.052 3.948-0.052 5.923-1.323-0.428-2.869-0.308-4.025 0.495-0.844 0.547-1.485 1.385-1.819 2.333-0.276 0.676-0.197 1.427-0.181 2.145 0.317 2.188 2.421 4.027 4.667 3.828 1.489-0.016 2.916-0.88 3.692-2.145 0.251-0.443 0.532-0.896 0.547-1.417 0.131-2.385 0.079-4.76 0.095-7.145 0.011-5.375-0.016-10.735 0.025-16.093z"></path></svg>\');
		--dl-icon-twitch: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16.499,8.089h-1.636v4.91h1.636V8.089z M12,8.089h-1.637v4.91H12V8.089z M4.228,3.178L3,6.451v13.092h4.499V22h2.456 l2.454-2.456h3.681L21,14.636V3.178H4.228z M19.364,13.816l-2.864,2.865H12l-2.453,2.453V16.68H5.863V4.814h13.501V13.816z"></path></svg>\');
		--dl-icon-twitter: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M14,10.4l7.6-8.9h-1.8L13,9.1L7.9,1.6H1.7l8,11.7l-8,9.3h1.8l7-8.1l5.6,8.1h6.2L14,10.4L14,10.4z M11.4,13.3l-0.8-1.1 L4.1,2.9h2.8l5.3,7.5l0.8,1.1l6.8,9.6h-2.8L11.4,13.3L11.4,13.3z"/></svg>\');
		--dl-icon-whatsapp: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M 12.011719 2 C 6.5057187 2 2.0234844 6.478375 2.0214844 11.984375 C 2.0204844 13.744375 2.4814687 15.462563 3.3554688 16.976562 L 2 22 L 7.2324219 20.763672 C 8.6914219 21.559672 10.333859 21.977516 12.005859 21.978516 L 12.009766 21.978516 C 17.514766 21.978516 21.995047 17.499141 21.998047 11.994141 C 22.000047 9.3251406 20.962172 6.8157344 19.076172 4.9277344 C 17.190172 3.0407344 14.683719 2.001 12.011719 2 z M 12.009766 4 C 14.145766 4.001 16.153109 4.8337969 17.662109 6.3417969 C 19.171109 7.8517969 20.000047 9.8581875 19.998047 11.992188 C 19.996047 16.396187 16.413812 19.978516 12.007812 19.978516 C 10.674812 19.977516 9.3544062 19.642812 8.1914062 19.007812 L 7.5175781 18.640625 L 6.7734375 18.816406 L 4.8046875 19.28125 L 5.2851562 17.496094 L 5.5019531 16.695312 L 5.0878906 15.976562 C 4.3898906 14.768562 4.0204844 13.387375 4.0214844 11.984375 C 4.0234844 7.582375 7.6067656 4 12.009766 4 z M 8.4765625 7.375 C 8.3095625 7.375 8.0395469 7.4375 7.8105469 7.6875 C 7.5815469 7.9365 6.9355469 8.5395781 6.9355469 9.7675781 C 6.9355469 10.995578 7.8300781 12.182609 7.9550781 12.349609 C 8.0790781 12.515609 9.68175 15.115234 12.21875 16.115234 C 14.32675 16.946234 14.754891 16.782234 15.212891 16.740234 C 15.670891 16.699234 16.690438 16.137687 16.898438 15.554688 C 17.106437 14.971687 17.106922 14.470187 17.044922 14.367188 C 16.982922 14.263188 16.816406 14.201172 16.566406 14.076172 C 16.317406 13.951172 15.090328 13.348625 14.861328 13.265625 C 14.632328 13.182625 14.464828 13.140625 14.298828 13.390625 C 14.132828 13.640625 13.655766 14.201187 13.509766 14.367188 C 13.363766 14.534188 13.21875 14.556641 12.96875 14.431641 C 12.71875 14.305641 11.914938 14.041406 10.960938 13.191406 C 10.218937 12.530406 9.7182656 11.714844 9.5722656 11.464844 C 9.4272656 11.215844 9.5585938 11.079078 9.6835938 10.955078 C 9.7955938 10.843078 9.9316406 10.663578 10.056641 10.517578 C 10.180641 10.371578 10.223641 10.267562 10.306641 10.101562 C 10.389641 9.9355625 10.347156 9.7890625 10.285156 9.6640625 C 10.223156 9.5390625 9.737625 8.3065 9.515625 7.8125 C 9.328625 7.3975 9.131125 7.3878594 8.953125 7.3808594 C 8.808125 7.3748594 8.6425625 7.375 8.4765625 7.375 z"></path></svg>\');
		--dl-icon-vimeo: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M22.396,7.164c-0.093,2.026-1.507,4.799-4.245,8.32C15.322,19.161,12.928,21,10.97,21c-1.214,0-2.24-1.119-3.079-3.359 c-0.56-2.053-1.119-4.106-1.68-6.159C5.588,9.243,4.921,8.122,4.206,8.122c-0.156,0-0.701,0.328-1.634,0.98L1.594,7.841 c1.027-0.902,2.04-1.805,3.037-2.708C6.001,3.95,7.03,3.327,7.715,3.264c1.619-0.156,2.616,0.951,2.99,3.321 c0.404,2.557,0.685,4.147,0.841,4.769c0.467,2.121,0.981,3.181,1.542,3.181c0.435,0,1.09-0.688,1.963-2.065 c0.871-1.376,1.338-2.422,1.401-3.142c0.125-1.187-0.343-1.782-1.401-1.782c-0.498,0-1.012,0.115-1.541,0.341 c1.023-3.35,2.977-4.977,5.862-4.884C21.511,3.066,22.52,4.453,22.396,7.164z"></path></svg>\');
		--dl-icon-youtube: url(\'data:image/svg+xml,<svg viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M21.8,8.001c0,0-0.195-1.378-0.795-1.985c-0.76-0.797-1.613-0.801-2.004-0.847c-2.799-0.202-6.997-0.202-6.997-0.202 h-0.009c0,0-4.198,0-6.997,0.202C4.608,5.216,3.756,5.22,2.995,6.016C2.395,6.623,2.2,8.001,2.2,8.001S2,9.62,2,11.238v1.517 c0,1.618,0.2,3.237,0.2,3.237s0.195,1.378,0.795,1.985c0.761,0.797,1.76,0.771,2.205,0.855c1.6,0.153,6.8,0.201,6.8,0.201 s4.203-0.006,7.001-0.209c0.391-0.047,1.243-0.051,2.004-0.847c0.6-0.607,0.795-1.985,0.795-1.985s0.2-1.618,0.2-3.237v-1.517 C22,9.62,21.8,8.001,21.8,8.001z M9.935,14.594l-0.001-5.62l5.404,2.82L9.935,14.594z"></path></svg>\');
	}
	/* Social Menu icons */
	.social-links .social-menu,
	.social-navigation .social-menu {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		list-style-type: none;
		gap: 0.5rem;
	}
	.social-menu li a,
	.social-menu li {
		display: block;
		line-height: 1;
		height: auto;
		padding: 0;
		margin: 0;
	}
	.social-menu li a:before {
		content: "" !important;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-family: inherit;
		line-height: 1;
		vertical-align: unset;
		width: 18px;
		height: 18px;
		background-color: currentColor;
		-webkit-mask-repeat: no-repeat;
		mask-repeat: no-repeat;
	}
	.social-menu li a:before,
	.social-menu li a[href*="plus.google.com"]:before,
	.social-menu li a[href*="stumbleupon.com"]:before,
	.social-menu li a[href*="tumblr.com"]:before {
		-webkit-mask-image: var(--dl-icon-share);
		mask-image: var(--dl-icon-share);
	}
	.social-menu li a[href*="apple.com"]:before {
		-webkit-mask-image: var(--dl-icon-apple);
		mask-image: var(--dl-icon-apple);
	}
	.social-menu li a[href*="discord.com"]:before {
		-webkit-mask-image: var(--dl-icon-discord);
		mask-image: var(--dl-icon-discord);
	}
	.social-menu li a[href*="dribbble.com"]:before {
		-webkit-mask-image: var(--dl-icon-dribbble);
		mask-image: var(--dl-icon-dribbble);
	}
	.social-menu li a[href*="facebook.com"]:before {
		-webkit-mask-image: var(--dl-icon-facebook);
		mask-image: var(--dl-icon-facebook);
	}
	.social-menu li a[href*="flickr.com"]:before {
		-webkit-mask-image: var(--dl-icon-flickr);
		mask-image: var(--dl-icon-flickr);
	}
	.social-menu li a[href*="github.com"]:before {
		-webkit-mask-image: var(--dl-icon-github);
		mask-image: var(--dl-icon-github);
	}
	.social-menu li a[href*="instagram.com"]:before {
		-webkit-mask-image: var(--dl-icon-instagram);
		mask-image: var(--dl-icon-instagram);
	}
	.social-menu li a[href*="linkedin.com"]:before {
		-webkit-mask-image: var(--dl-icon-linkedin);
		mask-image: var(--dl-icon-linkedin);
	}

	.social-menu li a[href*="medium.com"]:before {
		-webkit-mask-image: var(--dl-icon-medium);
		mask-image: var(--dl-icon-medium);
	}
	.social-menu li a[href*="pinterest.com"]:before {
		-webkit-mask-image: var(--dl-icon-pinterest);
		mask-image: var(--dl-icon-pinterest);
	}
	.social-menu li a[href*="feed"]:before {
		-webkit-mask-image: var(--dl-icon-rss);
		mask-image: var(--dl-icon-rss);
	}
	.social-menu li a[href*="spotify.com"]:before {
		-webkit-mask-image: var(--dl-icon-spotify);
		mask-image: var(--dl-icon-spotify);
	}
	.social-menu li a[href*="telegram.org"]:before {
		-webkit-mask-image: var(--dl-icon-telegram);
		mask-image: var(--dl-icon-telegram);
	}
	.social-menu li a[href*="tiktok.com"]:before {
		-webkit-mask-image: var(--dl-icon-tiktok);
		mask-image: var(--dl-icon-tiktok);
	}
	.social-menu li a[href*="twitch.tv"]:before {
		-webkit-mask-image: var(--dl-icon-twitch);
		mask-image: var(--dl-icon-twitch);
	}
	.social-menu li a[href*="twitter.com"]:before {
		-webkit-mask-image: var(--dl-icon-twitter);
		mask-image: var(--dl-icon-twitter);
	}
	.social-menu li a[href*="whatsapp.com"]:before {
		-webkit-mask-image: var(--dl-icon-whatsapp);
		mask-image: var(--dl-icon-whatsapp);
	}
	.social-menu li a[href*="vimeo.com"]:before {
		-webkit-mask-image: var(--dl-icon-vimeo);
		mask-image: var(--dl-icon-vimeo);
	}
	.social-menu li a[href*="youtube.com"]:before {
		-webkit-mask-image: var(--dl-icon-youtube);
		mask-image: var(--dl-icon-youtube);
	}
	/* Fix Social menu */
	.social-links .social-menu {
		margin: 0;
		padding: 0;
	}
	.social-links .social-menu li a {
		width: 32px;
		height: 32px;
		text-align: center;
		border-radius: 32px;
	}
	.social-menu li a:before {
		margin-top: 7px;
	}
	.social-links li a[href*="facebook.com"] {
		background-color: #1778f2;
	}
	.social-links li a[href*="twitter.com"] {
		background-color: #000;
	}
	.social-links li a[href*="instagram.com"] {
		background-color: #f00075;
	}';

	return $social_icons;
}


/**
 * WooCommerce
 */

// Query WooCommerce activation
function xmag_is_woocommerce_active() {
	return class_exists( 'woocommerce' ) ? true : false;
}

if ( xmag_is_woocommerce_active() ) {

	// Declare WooCommerce support.
	function woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	}
	add_action( 'after_setup_theme', 'woocommerce_support' );

	// WooCommerce Hooks.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	add_action('woocommerce_before_main_content', 'xmag_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'xmag_wrapper_end', 10);

	function xmag_wrapper_start() {
	echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main"><div class="woocommerce-content">';
	}

	function xmag_wrapper_end() {
	echo '</div></main></div>';
	}
}


/**
 * Add About page class
 */
require_once get_template_directory() . '/inc/about-page/class-xmag-about-page.php';


/*
* Add About page instance
*/
$my_theme = wp_get_theme();
if ( is_child_theme() ) {
	$my_theme_name = $my_theme->parent()->get( 'Name' );
	$my_theme_slug = $my_theme->parent()->get_template();
} else {
	$my_theme_name = $my_theme->get( 'Name' );
	$my_theme_slug = $my_theme->get_template();
}

$config = array(
	// Pro Theme Name
	'theme_pro_name'  => $my_theme_name . ' Plus',
	// Pro Theme slug
	'theme_pro_slug'  => $my_theme_slug . '-plus',
	// Main welcome title
	'welcome_title'   => sprintf( __( 'Welcome to %s!', 'xmag' ), $my_theme_name ),
	// Main welcome sub title
	'welcome_content' => sprintf( __( 'You have successfully installed the %s WordPress theme.', 'xmag' ), $my_theme_name ),
	// Notification
	'notification'    => '<h2 class="welcome-title">' . sprintf( __( 'Welcome! Thank you for choosing %s', 'xmag' ), $my_theme_name ) . '</h2><p>To fully take advantage of the best our theme can offer please visit our Welcome Page.</p><p><a href="' . esc_url( admin_url( 'themes.php?page=' . $my_theme_slug . '-welcome' ) ) . '" class="button button-primary">' . sprintf( __( 'Get started with %s', 'xmag' ), $my_theme_name ) . '</a></p>',
	// Tabs
	'tabs'            => array(
		'getting_started' => __( 'Getting Started', 'xmag' ),
		'free_pro'        => __( 'Free vs Pro', 'xmag' ),
	),
	'utm'             => '?utm_source=WordPress&utm_medium=about_page&utm_campaign=' . $my_theme_slug . '_upsell',
);
Xmag_About_Page::init( $config );


/**
 * Add Upsell "pro" link to the customizer
 */
require_once( trailingslashit( get_template_directory() ) . '/inc/customize-pro/class-customize.php' );
