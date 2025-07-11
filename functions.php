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

//  Enqueue Styles
function enqueueStyles() {
    wp_enqueue_style('northernShaolinStyle', get_theme_file_uri('/style.css'), [], '1.0');
}
add_action('wp_enqueue_scripts', 'enqueueStyles');

// Block editor support
add_theme_support('editor-styles');
add_editor_style('/assets/css/editor.css');

// CUSTOM CPTS
// Custom Post Type: Instructors
function registerInstructorsCpt() {
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
    );
    register_post_type('instructor', $args);
}
add_action('init', 'registerInstructorsCpt');

// Custom Post Type: Directors
function registerDirectorsCpt() {
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
function registerLocationTaxonomy() {
    register_taxonomy('location', 'instructor', array(
        'labels' => array(
            'name' => 'Locations',
            'singular_name' => 'Location',
            'add_new_item' => 'Add New Location',
            'new_item_name' => 'New Location Name',
        ),
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'pinetree'),
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
function registerStatusTaxonomy() {
    register_taxonomy('status', 'instructor', array(
        'labels' => array(
            'name' => 'Status',
            'singular_name' => 'Status',
            'add_new_item' => 'Add New Status',
            'new_item_name' => 'New Status Name',
        ),
        // set false for tags
        'hierarchical' => false, 
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'status'),
        'show_in_rest' => true,
    ));

    $defaultStatuses = array('Current', 'Former');

    foreach ($defaultStatuses as $status) {
        if (!term_exists($status, 'status')) {
            wp_insert_term($status, 'status');
        }
    }
}
add_action('init', 'registerStatusTaxonomy');

// Shortcode
// Shortcode and Enqueue: Carousel
function enqueueHeroCarousel() {
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

function heroCarousel() {
    // Grabbing images from ACF field w ID
    $images = get_field('carousel_images', get_the_ID()); 

    if (!$images) return '<p>No images found.</p>';

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
add_shortcode('hero_carousel', 'heroCarousel');
// To ensure images use BG
$images = get_field('group_68705bd0b583a'); 
if ($images) {
    foreach ($images as $image) {
        $image_url = wp_get_attachment_image_url($image['ID'], 'bg'); 
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image['alt']) . '" />';
    }
}

// Custom Image Sizes
function custom_image_sizes() {
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