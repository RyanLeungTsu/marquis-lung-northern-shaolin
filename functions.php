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
function ns_enqueue_assets() {
    // Main Styles
    wp_enqueue_style('northernShaolinStyle', get_theme_file_uri('/style.css'), [], '1.0');
    wp_enqueue_style('ns-theme-toggle', get_template_directory_uri() . '/assets/css/theme-toggle.css', [], '1.0');
    wp_enqueue_style('ns-carousel-style', get_template_directory_uri() . '/assets/css/carousel.css', ['ns-theme-toggle'], '1.0');
    wp_enqueue_style('ns-home-page-style', get_template_directory_uri() . '/assets/css/home.css', ['ns-theme-toggle'], '1.0');
    wp_enqueue_style('ns-myCalendar-style', get_template_directory_uri() . '/assets/css/myCalendar.css', ['ns-theme-toggle'], '1.0');
    wp_enqueue_style('ns-override', get_template_directory_uri() . '/assets/css/override.css', [], '1.0');
    wp_enqueue_style('scroll-arrow-style', get_template_directory_uri() . '/assets/css/scroll-arrow.css', [], '1.0');

    // Scripts
    wp_enqueue_script('heroCarouselJs', get_template_directory_uri() . '/assets/js/carousel/carousel.js', [], null, true);
    wp_enqueue_script('scroll-arrow-script', get_template_directory_uri() . '/assets/js/scroll-arrow/scroll-arrow.js', [], '1.0', true);
    wp_enqueue_script('theme-toggle-script', get_template_directory_uri() . '/assets/js/theme-toggle/theme-toggle.js', [], '1.0', true);
}
add_action('wp_enqueue_scripts', 'ns_enqueue_assets');

// Editor Styles
add_theme_support('editor-styles');
add_editor_style('/assets/css/editor.css');

// Expose ACF fields in REST API for Events
function expose_acf_fields_in_rest($data, $post, $request) {
    if ($post->post_type === 'event') {
        $acf_fields = get_fields($post->ID);
        if ($acf_fields) {
            $data->data['acf'] = $acf_fields;
        }
    }
    return $data;
}
add_filter('rest_prepare_event', 'expose_acf_fields_in_rest', 10, 3);

// Custom Post Types
function ns_register_cpts() {
    // Instructors
    register_post_type('instructor', [
        'labels' => ['name' => 'Instructors','singular_name' => 'Instructor'],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'instructors-archive'],
        'supports' => ['title','editor','thumbnail'],
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ]);

    // Directors
    register_post_type('director', [
        'labels' => ['name' => 'Directors','singular_name' => 'Director'],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'directors-archive'],
        'supports' => ['title','editor','thumbnail'],
        'menu_position' => 5,
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
    ]);

    // Photos
    register_post_type('photo', [
        'labels' => ['name' => 'Photos','singular_name' => 'Photo'],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'photos'],
        'supports' => ['title','editor','thumbnail'],
        'menu_position' => 6,
        'menu_icon' => 'dashicons-format-image',
        'show_in_rest' => true,
    ]);

    // Events
    register_post_type('event', [
        'labels' => ['name' => 'Events','singular_name' => 'Event'],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'events'],
        'supports' => ['title','editor','custom-fields'],
        'menu_position' => 7,
        'menu_icon' => 'dashicons-calendar-alt',
        'show_in_rest' => true,
    ]);
}
add_action('init', 'ns_register_cpts');

// Custom Taxonomies
function ns_register_taxonomies() {
    // Location
    register_taxonomy('location', 'instructor', [
        'labels' => ['name'=>'Locations','singular_name'=>'Location','add_new_item'=>'Add New Location','new_item_name'=>'New Location Name'],
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug'=>'ns-location'],
        'show_in_rest' => true,
    ]);

    // Status
    register_taxonomy('ns-status', ['instructor','director'], [
        'labels' => ['name'=>'Status','singular_name'=>'Status','add_new_item'=>'Add New Status','new_item_name'=>'New Status Name'],
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug'=>'ns-status'],
        'show_in_rest' => true,
    ]);

    // Director Title
    register_taxonomy('director-title', 'director', [
        'labels' => ['name'=>'Title','singular_name'=>'Title','add_new_item'=>'Add New Title','new_item_name'=>'New Title Name'],
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug'=>'director-title'],
        'show_in_rest' => true,
    ]);

    // Gallery Filter
    register_taxonomy('gallery-filter', 'photo', [
        'labels' => ['name'=>'Photo Types','singular_name'=>'Photo Type','add_new_item'=>'Add New Photo Type','new_item_name'=>'New Photo Type Name'],
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug'=>'gallery-filter'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'ns_register_taxonomies');

// Default Terms (Locations, Status, Director Title, Gallery Filter)
function ns_add_terms() {
    $locations = ['Pinetree','Douglas Park','Britannia','Lochdale Hall','Killarney','Sifu'];
    foreach ($locations as $loc) if (!term_exists($loc,'location')) wp_insert_term($loc,'location');

    $statuses = ['Current','Former','Student Instructor'];
    foreach ($statuses as $status) if (!term_exists($status,'ns-status')) wp_insert_term($status,'ns-status');

    $titles = ['President','Vice President','Treasurer','Secretary','Member'];
    foreach ($titles as $title) if (!term_exists($title,'director-title')) wp_insert_term($title,'director-title');

    $photoTypes = ['General','Dragon Dance','Annual Performance','Trips','Training','Events','Tournaments'];
    foreach ($photoTypes as $type) if (!term_exists($type,'gallery-filter')) wp_insert_term($type,'gallery-filter');
}
add_action('init', 'ns_add_terms', 20);

// Exclude specific post
function ns_exclude_post($query) {
    if (!is_admin() && is_page('instructors')) {
        $excluded = $query->get('post__not_in') ?: [];
        $excluded[] = 208;
        $query->set('post__not_in', $excluded);
    }
}
add_action('pre_get_posts', 'ns_exclude_post');

// Shortcodes
// Shortcode: Carousel
function heroCarouselShortcode() {
    $images = get_field('carousel_images', get_the_ID());
    if (!$images) return '<p>No images found.</p>';

    ob_start(); ?>
    <div class="hero-carousel">
        <?php foreach ($images as $img): ?>
            <div class="carousel-slide">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($img['ID'],'bg')); ?>"
                     alt="<?php echo esc_attr($img['alt']); ?>" />
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('hero_carousel','heroCarouselShortcode');

// Shortcode: Scroll Arrow
function scrollArrowShortcode() {
    wp_enqueue_script('scroll-arrow-script');
    wp_enqueue_style('scroll-arrow-style');

    return '<div id="scroll-arrow-container"></div>';
}
add_shortcode('scroll_arrow','scrollArrowShortcode');

// Shortcode: Theme Toggle
function themeToggleShortcode() {
    return '<button id="theme-toggle-btn" aria-label="Toggle Theme" class="toggleBtn"></button>';
}
add_shortcode('theme_toggle','themeToggleShortcode');

// Other
// Custom Image Sizes
add_theme_support('post-thumbnails');
function ns_custom_image_sizes() {
    add_image_size('small',150,150,true);
    add_image_size('medium',300,300,true);
    add_image_size('large',600,600,true);
    add_image_size('bg',1920,1080,true);
    add_image_size('gallery',960,960,true);

    add_filter('image_size_names_choose', function($sizes){
        return array_merge($sizes,[
            'small'=>'NS Small',
            'medium'=>'NS Medium',
            'large'=>'NS Large',
            'bg'=>'NS Background',
            'gallery'=>'NS Gallery Photo',
        ]);
    });
}
add_action('after_setup_theme','ns_custom_image_sizes');

// Replace default featured image block with gallery size
add_filter('render_block', function($content, $block){
    if($block['blockName']==='core/post-featured-image'){
        if(isset($block['attrs']['sizeSlug']) && $block['attrs']['sizeSlug']==='thumbnail'){
            $post_id = get_the_ID();
            $img_id = get_post_thumbnail_id($post_id);
            if($img_id){
                return wp_get_attachment_image($img_id,'gallery',['class'=>'wp-block-post-featured-image galleryImg']);
            }
        }
    }
    return $content;
},10,2);

// Password expire set to 1 hour
add_filter('post_password_expires', fn($expires)=>3600);