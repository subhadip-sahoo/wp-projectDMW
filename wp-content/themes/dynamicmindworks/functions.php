<?php
/**
 * Twenty Fourteen functions and definitions
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
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see twentyfourteen_content_width()
 *
 * @since Twenty Fourteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 474;
}

/**
 * Twenty Fourteen only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentyfourteen_setup' ) ) :
/**
 * Twenty Fourteen setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_setup() {

	/*
	 * Make Twenty Fourteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentyfourteen', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', twentyfourteen_font_url(), 'genericons/genericons.css' ) );

	// Add RSS feed links to <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252"> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'twentyfourteen-full-width', 1038, 576, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'twentyfourteen' ),
		'secondary' => __( 'Secondary menu in left sidebar', 'twentyfourteen' ),
		'footer' => __( 'Footer Menu', 'twentyfourteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'twentyfourteen_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'twentyfourteen_get_featured_posts',
		'max_posts' => 6,
	) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // twentyfourteen_setup
add_action( 'after_setup_theme', 'twentyfourteen_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'twentyfourteen_content_width' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return array An array of WP_Post objects.
 */
function twentyfourteen_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Twenty Fourteen.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'twentyfourteen_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return bool Whether there are featured posts.
 */
function twentyfourteen_has_featured_posts() {
	return ! is_paged() && (bool) twentyfourteen_get_featured_posts();
}

/**
 * Register three Twenty Fourteen widget areas.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_widgets_init() {
	require get_template_directory() . '/inc/widgets.php';
	register_widget( 'Twenty_Fourteen_Ephemera_Widget' );

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Content Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Additional sidebar that appears on the right.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'twentyfourteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears in the footer section of the site.', 'twentyfourteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );
	register_sidebar( array(
		'name'          => __( 'Top header', 'twentyfourteen' ),
		'id'            => 'sidebar-4',
		'description'   => __( 'Appears in the top right header section of the site.', 'twentyfourteen' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => '',
	) );
}
add_action( 'widgets_init', 'twentyfourteen_widgets_init' );

/**
 * Register Lato Google font for Twenty Fourteen.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return string
 */
function twentyfourteen_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'twentyfourteen' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_scripts() {
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.3' );

	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfourteen-style', get_stylesheet_uri(), array( 'genericons' ) );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentyfourteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentyfourteen-style', 'genericons' ), '20131205' );
	wp_style_add_data( 'twentyfourteen-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		wp_enqueue_script( 'twentyfourteen-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '20131205', true );
		wp_localize_script( 'twentyfourteen-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'twentyfourteen' ),
			'nextText' => __( 'Next', 'twentyfourteen' )
		) );
	}

	wp_enqueue_script( 'twentyfourteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20140616', true );
}
add_action( 'wp_enqueue_scripts', 'twentyfourteen_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_admin_fonts() {
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'twentyfourteen_admin_fonts' );

if ( ! function_exists( 'twentyfourteen_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_the_attached_image() {
	$post                = get_post();
	/**
	 * Filter the default Twenty Fourteen attachment size.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
	$attachment_size     = apply_filters( 'twentyfourteen_attachment_size', array( 810, 810 ) );
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'twentyfourteen_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="button contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'twentyfourteen' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image except in Multisite signup and activate pages.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function twentyfourteen_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} elseif ( ! in_array( $GLOBALS['pagenow'], array( 'wp-activate.php', 'wp-signup.php' ) ) ) {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		//$classes[] = 'list-view';
	}

	if ( ( ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width.php' )
		|| is_page_template( 'page-templates/contributors.php' )
		|| is_attachment() ) {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		//$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'twentyfourteen_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function twentyfourteen_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'twentyfourteen_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Fourteen 1.0
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function twentyfourteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'twentyfourteen_wp_title', 10, 2 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';
}
function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
    if ( $attach_id ) {
        $image_src = wp_get_attachment_image_src( $attach_id, 'full' );
        $file_path = get_attached_file( $attach_id );
    } else if ( $img_url ) {
        $file_path = parse_url( $img_url );
        $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
        if(file_exists($file_path) === false){
            global $blog_id;
            $file_path = parse_url( $img_url );
            if (preg_match("/files/", $file_path['path'])) {
                $path = explode('/',$file_path['path']);
                foreach($path as $k=>$v){
                    if($v == 'files'){
                        $path[$k-1] = 'wp-content/blogs.dir/'.$blog_id;
                    }
                }
                $path = implode('/',$path);
            }
            $file_path = $_SERVER['DOCUMENT_ROOT'].$path;
        }
        $orig_size = getimagesize( $file_path );
        $image_src[0] = $img_url;
        $image_src[1] = $orig_size[0];
        $image_src[2] = $orig_size[1];
    }
    $file_info = pathinfo( $file_path );
    $base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
    if ( !file_exists($base_file) )
    return;
    $extension = '.'. $file_info['extension'];
    $no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
    $cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
    if ( $image_src[1] > $width ) {
        if ( file_exists( $cropped_img_path ) ) {
            $cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
            $vt_image = array (
                'url' => $cropped_img_url,
                'width' => $width,
                'height' => $height
            );
            return $vt_image;
        }
        if ( $crop == false OR !$height ) {
            $proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
            $resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;
            if ( file_exists( $resized_img_path ) ) {
                $resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
                $vt_image = array (
                'url' => $resized_img_url,
                'width' => $proportional_size[0],
                'height' => $proportional_size[1]
                );
                return $vt_image;
            }
        }
        $img_size = getimagesize( $file_path );
        if ( $img_size[0] <= $width ) $width = $img_size[0];
        if (!function_exists ('imagecreatetruecolor')) {
            echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
            return;
        }
        $new_img_path = image_resize( $file_path, $width, $height, $crop );	
        $new_img_size = getimagesize( $new_img_path );
        $new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
        $vt_image = array (
            'url' => $new_img,
            'width' => $new_img_size[0],
            'height' => $new_img_size[1]
        );
        return $vt_image;
    }
    $vt_image = array (
        'url' => $image_src[0],
        'width' => $width,
        'height' => $height
    );
    return $vt_image;
}
function custom_numeric_posts_nav() {
    if( is_singular() )
        return;
    global $wp_query;	
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
    /**	Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
    /**	Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
    echo '<div class="navigation"><ul>' . "\n";
    /**	Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li>%s</li>' . "\n", get_previous_posts_link() );
    /**	Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
        if ( ! in_array( 2, $links ) )
                echo '<li>�</li>';
    }
    /**	Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
    /**	Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) )
                    echo '<li>�</li>' . "\n";

            $class = $paged == $max ? ' class="active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
    /**	Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li>%s</li>' . "\n", get_next_posts_link() );
    echo '</ul></div>' . "\n";
}
add_action( 'init', 'video_post_type' );
function video_post_type() {
    register_post_type('videos',
            array(
                'public' => true,
                'label'  => 'Videos',
                'rewrite' => array("slug" => "videos"),
                'supports' => array('title', 'editor')
            )
    );
    flush_rewrite_rules();	
}
add_action( 'init', 'courses_post_type' );
function courses_post_type() {
    /*register_post_type('courses',
            array(
                'public' => true,
                'label'  => 'Courses',
                'rewrite' => array("slug" => "courses"),
                'supports' => array('title', 'editor')
            )
    );
    flush_rewrite_rules();
	register_post_type('course-types',
            array(
                'public' => true,
                'label'  => 'Course Types',
                'rewrite' => array("slug" => "course-types"),
                'supports' => array('title')
            )
    );
    flush_rewrite_rules();*/
	register_post_type('course-image-gallery',
            array(
                'public' => true,
                'label'  => 'Image Gallery',
                'rewrite' => array("slug" => "image-gallery"),
                'supports' => array('title', 'thumbnail')
            )
    );
    flush_rewrite_rules();
	register_taxonomy(
                'course-categories',
                array('testimonials','videos', 'events', 'course-image-gallery'),
                array(
                        'label' => __( 'Course Categories' ),
                        'rewrite' => array( 'slug' => 'course-categories' ),
                        'hierarchical' => true,
                )
    );
    flush_rewrite_rules();
}
add_action( 'init', 'events_post_type' );
function events_post_type() {
    register_post_type('events',
            array(
                'public' => true,
                'label'  => 'Events',
                'rewrite' => array("slug" => "events"),
                'supports' => array('title', 'thumbnail', 'editor')
            )
    );
    flush_rewrite_rules();
}
add_action( 'init', 'testimonials_post_type' );
function testimonials_post_type() {
    register_post_type('testimonials',
            array(
                'public' => true,
                'label'  => 'Testimonials',
                'rewrite' => array("slug" => "testimonials"),
                'supports' => array('title', 'thumbnail', 'editor')
            )
    );
    flush_rewrite_rules();
}
add_action( 'init', 'slider_post_type' );
function slider_post_type() {
    register_post_type('sliders',
            array(
                'public' => true,
                'label'  => 'Sliders',
                'rewrite' => array("slug" => "sliders"),
                'supports' => array('title', 'thumbnail', 'editor')
            )
    );
    flush_rewrite_rules();
}
add_action( 'init', 'services_post_type' );
function services_post_type() {
    register_post_type('services',
            array(
                'public' => true,
                'label'  => 'Services',
                'rewrite' => array("slug" => "services"),
                'supports' => array('title', 'thumbnail', 'editor', 'excerpt')
            )
    );
    flush_rewrite_rules();
}
function excerpt_to_charlength($charlength, $excerpt_n, $id = '') {
	//$excerpt = get_the_excerpt();
	//$charlength++;
	//echo $excerpt_n;
	if ( mb_strlen( $excerpt_n ) > $charlength ) {
		/*$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			return mb_substr( $subex, 0, $excut ).'....';
		} else {
			return $subex.'....';
		}
		return '...';*/
		$permanlink = '';
		if($id <> ''){
			$permanlink .= '<a href="'.get_the_permalink($id).'">More</a>';			
		}
		return substr($excerpt_n, 0, $charlength).'.... '.$permanlink;
	} else {
		return $excerpt_n;
	}
}
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
function get_post_comment($post_id){
	$num_comments = get_comments_number($post_id); // get_comments_number returns only a numeric value
	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = __('No Comments');
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments . __(' Comments');
		} else {
			$comments = __('1 Comment');
		}
		$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
	} else {
		$write_comments =  __('Comments are off for this post.');
	}
	return $write_comments;
}
function the_post_thumbnail_caption($post_id = '') {
	//echo $post_id;exit();
	global $post;
	$id = ($post_id == '')? $post->ID: $post_id;
	$thumb_id = get_post_thumbnail_id($id);

  $args = array(
	'post_type' => 'attachment',
	'post_status' => null,
	'post_parent' => $id,
	'include'  => $thumb_id
	); 

   $thumbnail_image = get_posts($args);

   if ($thumbnail_image && isset($thumbnail_image[0])) {
     //show thumbnail title
     echo $thumbnail_image[0]->post_title; 

     //Uncomment to show the thumbnail caption
     //echo $thumbnail_image[0]->post_excerpt; 

     //Uncomment to show the thumbnail description
     //echo $thumbnail_image[0]->post_content; 

     //Uncomment to show the thumbnail alt field
     //$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
     //if(count($alt)) echo $alt;
  }
}
function wp_get_child_term_count($term_id){
	global $wpdb;
	$get_chid_terms = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}term_taxonomy` WHERE `parent` = {$term_id}", ARRAY_A);
	if(!empty($get_chid_terms)){
		return $wpdb->num_rows;
	}
}
function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}
function get_taxonomy_post_type_posts_count($post_type, $term_id){
	$term = get_term($term_id, 'course-categories');
	$query = new WP_Query(array('post_type' => $post_type, 'taxonomy' => 'course-categories', 'course-categories' => $term->slug));
	return $query->post_count;
}
add_action('admin_menu','wphidenag');
function wphidenag() {
    remove_action( 'admin_notices', 'update_nag', 3 );
}

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php header_image(); ?>);
            padding-bottom: 30px;
            background-size: 277px 115px;
            width:277px;
            height:115px;
        }
    </style>
<?php }
add_action( 'login_head', 'my_login_logo' );
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Dynamic Mind Works';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
function my_footer_shh() {
    remove_filter( 'update_footer', 'core_update_footer' ); 
}
add_action( 'admin_menu', 'my_footer_shh' );

function my_footer_text() {
	echo '';
}
add_action('admin_footer_text', 'my_footer_text');
function comment_validation_init() {
	if(is_single() && comments_open() ) { ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#commentform').validate({		 
				rules: {
				  author: {
					required: true,
					minlength: 2
				  },				 
				  email: {
					required: true,
					email: true
				  },				 
				  comment: {
					required: true,
					minlength: 20
				  }
				},		 
				messages: {
				  author: "Please enter in your name.",
				  email: "Please enter a valid email address.",
				  comment: "Message box can't be empty!"
				},		 
				errorElement: "div",
				errorPlacement: function(error, element) {
					element.before(error);
				}
		 
			});
		});
		</script>
	<?php
	}
}
add_action('wp_footer', 'comment_validation_init');
function remove_dashboard_meta() {
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );	
}
add_action( 'admin_init', 'remove_dashboard_meta' );
function remove_welcome_panel() {
	echo '<style>#welcome-panel{display:none;}</style>';
}
add_action('admin_head', 'remove_welcome_panel');
function new_excerpt_more( $more ) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');
function attending_callback(){	
	$price_arr = get_price_combo($_POST['course'], $_POST['option'], $_POST['booking_code'], $_POST['percentage']);	
	if(count($price_arr) == 3){
		$price = $price_arr[0];
		$actual_price = $price_arr[1];
		$updated_price = ($price_arr[2] == '$')?'':$price_arr[2];			
	}else if(count($price_arr) == 1){
		$price = $price_arr[0];
	}
	echo json_encode(array('price' => $price, 'actual_price' => $actual_price, 'updated_price' => $updated_price));
	exit();	
}
add_action('wp_ajax_attending_callback', 'attending_callback');
add_action('wp_ajax_nopriv_attending_callback', 'attending_callback');
function get_factor_by_booking_code($code){
	$booking_code_factors = get_field('booking_code_factors', 'option');
	foreach($booking_code_factors as $cf){
		if(strcasecmp($cf['code'], $code) == 0){
			return abs($cf['discount_factor']);
		}
	}
	return 0;
}
function get_price_combo($course, $option, $booking_code, $percentage){	
	if(is_numeric($course) && $course <> ''){		
		$actual_price = $price = str_replace('$', '', round(get_field('price', $course, true)));		
		if($option <> 'First time'){						
			$updated_price = $price = round(($price - (($price * $percentage)/100)));			
			$price = $actual_price.' { Refreshing Fees Due: $'.$price.' }';
			return array('$'.$price, '$'.$actual_price, '$'.$updated_price);
			exit();
		}else if(get_factor_by_booking_code($booking_code) != 0 ){
			$discount_factor = get_factor_by_booking_code($booking_code);
			$discounted_price = round(($price - (($price * $discount_factor)/100)));
			$price = $actual_price.' { Special discounted price: $'.$discounted_price.' }';
			return array('$'.$price, '$'.$actual_price, '$'.$discounted_price);
			exit();			
		}else if($option == 'First time'){			
			$cut_off_date = get_field('early_bird_cut_off_date', $course, true);
			$cut_off_price = str_replace('$', '', round(get_field('early_bird_price', $course, true)));			
			if(date('Y-m-d',strtotime($cut_off_date)) >= date('Y-m-d', strtotime('now'))){				
				$price = $actual_price.' { $'.$cut_off_price.' Early Bird offer to be paid by: '.date('d F Y', strtotime($cut_off_date)).' }';
				$discounted_price = $cut_off_price;
			}else{				
				$price = $actual_price;
				$discounted_price = 'N/A';
			}
			return array('$'.$price, '$'.$actual_price, '$'.$discounted_price);
			exit();
		}
	}else{		
		return array('Please select a course');
		exit();
	}
}
function book_me_now(){    		
	$price_arr = get_price_combo($_POST['course'], $_POST['option'], $_POST['booking_code'], $_POST['percentage']);	
	if(count($price_arr) == 3){
		$price = $price_arr[0];
		$actual_price = $price_arr[1];
		$updated_price = ($price_arr[2] == '$')?'':$price_arr[2];			
	}else if(count($price_arr) == 1){
		$price = $price_arr[0];
	}
	if(is_numeric($_POST['course']) && $_POST['course'] <> ''){
		$location_options = '';
		$date_options = '';
		$location = get_field('location', $_POST['course'], true);
		$location_options .= '<option value="'.$location.'">'.$location.'</option>';
		$dates = get_field('dates', $_POST['course'], true);
		aasort($dates, 'start_date');
		if(!empty($dates)){			
		   foreach($dates as $date){				
				if(strtotime($date['start_date']) >= strtotime('now')){					
				   $date_options .= '<option value="'.date('d F Y', strtotime($date['start_date'])).'">'.date('d F Y', strtotime($date['start_date'])).'</option>';
					break;
				}
			}
		}		
		$terms_and_cond = (get_field('terms_and_conditions', $_POST['course'], true) <> '')? get_field('terms_and_conditions', $_POST['course'], true): get_field('terms_and_conditions', 'option');		
		echo json_encode(array('location' => $location_options, 'dates' => $date_options, 'price' => $price, 'actual_price' => $actual_price, 'updated_price' => $updated_price, 'terms_and_cond' => $terms_and_cond));
		exit();
	}else{
		$location_options = '<option value="">-- Select location --</option>';
		$date_options = '<option value="">-- Select course start date --</option>';		
		echo json_encode(array('location' => $location_options, 'dates' => $date_options, 'price' => $price, 'actual_price' => '', 'updated_price' => '', 'terms_and_cond' => get_field('terms_and_conditions', 'option')));
		exit();
	}		
}
add_action('wp_ajax_book_me_now', 'book_me_now');
add_action('wp_ajax_nopriv_book_me_now', 'book_me_now');