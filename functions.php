<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

//  Enqueue Styles
function enqueue_styles() {
    wp_enqueue_style('northern-shaolin-style', get_theme_file_uri('/style.css'), [], '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_styles');

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
    );
    register_post_type('instructor', $args);
}
add_action('init', 'register_instructors_cpt');

// Custom Post Type: Directors
function register_directors_cpt()
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
add_action('init', 'register_directors_cpt');

// CUSTOM TAXONOMY
// Custom Taxonomy: Pinetree
function register_location_taxonomy()
{
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

	$default_locations = array(
		'Pinetree',
		'Douglas Park',
        'Britannia',
        'Lochdale Hall',
        'Killarney'
	);

	foreach ($default_locations as $location) {
        if (!term_exists($location, 'location')) {
            wp_insert_term($location, 'location');
        }
    }
}
add_action('init', 'register_location_taxonomy');

// Custom Taxonomy: Status
function register_status_taxonomy() {
    register_taxonomy('status', 'instructor', array(
        'labels' => array(
            'name' => 'Status',
            'singular_name' => 'Status',
            'add_new_item' => 'Add New Status',
            'new_item_name' => 'New Status Name',
        ),
        'hierarchical' => false, // Like tags
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'status'),
        'show_in_rest' => true,
    ));

    $default_statuses = array('Current', 'Former');

    foreach ($default_statuses as $status) {
        if (!term_exists($status, 'status')) {
            wp_insert_term($status, 'status');
        }
    }
}
add_action('init', 'register_status_taxonomy');

// Shortcode
// Shortcode: Carousel
function luxeline_carousel_shortcode() {
    return '<div id="home-carousel" class="carousel"></div>';
}
add_shortcode('carousel', 'home_carousel_shortcode');