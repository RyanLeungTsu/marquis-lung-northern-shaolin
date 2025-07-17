<?php
/**
 * Northern Shaolin Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage northern-shaolin-theme
 * @since northern-shaolin-theme 1.0
 */
// Animate on Scroll from https://michalsnik.github.io/aos/

//  Enqueue Styles
function enqueueStyles()
{
    wp_enqueue_style('northernShaolinStyle', get_theme_file_uri('/style.css'), [], '1.0');

    wp_enqueue_style('ns-carousel-style', get_template_directory_uri() . '/assets/css/carousel.css', [], '1.0');

    wp_enqueue_style('ns-home-page-style', get_template_directory_uri() . '/assets/css/home.css', [], '1.0');

    wp_enqueue_style('ns-scroll-arrow-style', get_template_directory_uri() . '/assets/css/scroll-arrow.css', [], '1.0');

    wp_enqueue_style('ns-theme-toggle-style', get_template_directory_uri() . '/assets/css/theme-toggle.css', [], '1.0');
}
add_action('wp_enqueue_scripts', 'enqueueStyles');

// Block editor support
add_theme_support('editor-styles');
add_editor_style('/assets/css/editor.css');

// CUSTOM CPTS
// Custom Post Type: Instructors
function register_instructors_cpt()
{
    $args = array(
        'labels' => array(
            'name' => 'Instructors',
            'singular_name' => 'Instructor',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'instructors'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true,
    );

    register_post_type('instructor', $args);
}
add_action('init', 'register_instructors_cpt');

// Custom Post Type: Directors
function registerDirectorsCpt()
{
    $args = array(
        'labels' => array(
            'name' => 'Directors',
            'singular_name' => 'Director',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'directors'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
    );
    register_post_type('director', $args);
}
add_action('init', 'registerDirectorsCpt');

// CUSTOM TAXONOMY
// Custom Taxonomy: Location
function registerLocationTaxonomy()
{
    register_taxonomy('location', 'instructor', array(
        'labels' => array(
            'name' => 'Locations',
            'singular_name' => 'Location',
            'add_new_item' => 'Add New Location',
            'new_item_name' => 'New Location Name',
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'ns-location'),
        'show_in_rest' => true,
    ));

    $defaultLocations = array(
        'Pinetree',
        'Douglas Park',
        'Britannia',
        'Lochdale Hall',
        'Killarney'
    );

    foreach ($defaultLocations as $location) {
        if (!term_exists($location, 'location')) {
            wp_insert_term($location, 'location');
        }
    }
}
add_action('init', 'registerLocationTaxonomy');

// Custom Taxonomy: Status
function registerStatusTaxonomy()
{
    $taxonomy = 'ns-status';

    register_taxonomy($taxonomy, 'instructor', array(
        'labels' => array(
            'name' => 'Status',
            'singular_name' => 'Status',
            'add_new_item' => 'Add New Status',
            'new_item_name' => 'New Status Name',
        ),
        'hierarchical' => true, 
        'show_admin_column' => true,
        'rewrite' => array('slug' => $taxonomy),
        'show_in_rest' => true,
    ));

    $defaultStatuses = array('Current', 'Former');

    foreach ($defaultStatuses as $status) {
        if (!term_exists($status, $taxonomy)) {
            wp_insert_term($status, $taxonomy);
        }
    }
}
add_action('init', 'registerStatusTaxonomy');

// Shortcode
// Shortcode and Enqueue: Carousel
function enqueueHeroCarousel()
{
    wp_enqueue_script(
        'heroCarouselJs',
        get_template_directory_uri() . '/assets/js/carousel/carousel.js',
        array(),
        null,
        true
    );

    wp_enqueue_style(
        'hero-carousel-css',
        get_template_directory_uri() . '/assets/css/carousel.css'
    );
}
add_action('wp_enqueue_scripts', 'enqueueHeroCarousel');

function heroCarouselShortcode()
{
    // Grabbing images from ACF field w ID
    $images = get_field('carousel_images', get_the_ID());

    if (!$images)
        return '<p>No images found.</p>';

    ob_start(); ?>

    <div class="hero-carousel">
        <?php foreach ($images as $image): ?>
            <div class="carousel-slide">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($image['ID'], 'bg')); ?>"
                    alt="<?php echo esc_attr($image['alt']); ?>" />
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('hero_carousel', 'heroCarouselShortcode');


// Shortcode: Scroll Arrow
function enqueueScrollArrow()
{
    wp_register_script(
        'scroll-arrow-script',
        get_template_directory_uri() . '/assets/js/scroll-arrow/scroll-arrow.js',
        [],
        '1.0',
        true
    );
}
add_action('init', 'enqueueScrollArrow');

function scrollArrowShortcode()
{
    wp_enqueue_style('scroll-arrow-style');
    wp_enqueue_script('scroll-arrow-script');

    return '<div id="scroll-arrow-container"></div>';
}
add_shortcode('scroll_arrow', 'scrollArrowShortcode');

// Shortcode: theme toggle for light and dark mode
function enqueueThemeToggle()
{
    wp_register_script(
        'theme-toggle-script',
        get_template_directory_uri() . '/assets/js/theme-toggle/theme-toggle.js',
        [],
        '1.0',
        true
    );
}
add_action('init', 'enqueueThemeToggle');

function themeToggleShortcode()
{
    wp_enqueue_style('theme-toggle-style');
    wp_enqueue_script('theme-toggle-script');

    return '<button id="theme-toggle-btn" aria-label="Toggle Theme" class="toggleBtn"></button>';
}
add_shortcode('theme_toggle', 'ThemeToggleShortcode');

// Shortcode: Full Calendar Library Shortcode
function enqueueFullCalendar()
{
    // CDN used for css
    wp_enqueue_style(
        'fullcalendar-css',
        'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css',
        [],
        '6.1.11'
    );
    // js files locally
    wp_enqueue_script(
        'fullcalendar-js',
        get_template_directory_uri() . '/assets/js/full-calendar/index.global.min.js',
        [],
        '6.1.11',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueueFullCalendar');

function fullCalendarShortcode()
{
    // Enqueue styles & scripts
    wp_enqueue_style('fullcalendar-css');
    wp_enqueue_script('fullcalendar-js');

    // customization with custom init script
    wp_enqueue_script(
        'fullcalendar-init',
        get_template_directory_uri() . '/assets/js/full-calendar/full-calendar-init.js',
        ['fullcalendar-js'],
        '1.0',
        true
    );

    return '<div id="calendar"></div>';
}
add_shortcode('fullcalendar', 'fullCalendarShortcode');
// Custom Image Sizes
function custom_image_sizes()
{
    // Add custom image sizes
    add_image_size('small', 150, 150, true);
    add_image_size('medium', 300, 300, true);
    add_image_size('large', 600, 600, true);
    add_image_size('bg', 1920, 1080, true);

    // Add custom sizes to media selector dropdown in WP Admin
    add_filter('image_size_names_choose', function ($sizes) {
        return array_merge($sizes, [
            'small' => __('NS Small'),
            'medium' => __('NS Medium'),
            'large' => __('NS Large'),
            'bg' => __('NS Background'),
        ]);
    });
}
add_action('after_setup_theme', 'custom_image_sizes');
// To ensure images use BG
function useCustomImgSize()
{
    $group = get_field('group_68705bd0b583a');

    if ($group && isset($group['images'])) {
        foreach ($group['images'] as $image) {
            if (isset($image['ID'])) {
                $image_url = wp_get_attachment_image_url($image['ID'], 'bg');
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image['alt']) . '" />';
            }
        }
    }
}

