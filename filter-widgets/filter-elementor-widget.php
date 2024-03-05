<?php
/**
 * Plugin Name: Elementor oEmbed Widget
 * Description: Auto embed any embbedable content from external URLs into Elementor.
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      Elementor Developer
 * Author URI:  https://developers.elementor.com/
 * Text Domain: elementor-oembed-widget
 *
 * Elementor tested up to: 3.16.0
 * Elementor Pro tested up to: 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register oEmbed Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_oembed_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/oembed-widget.php' );

	$widgets_manager->register( new \Elementor_oEmbed_Widget() );

}
add_action( 'elementor/widgets/register', 'register_oembed_widget' );


// add_action('elementor/query/postfilter', function ($query) {
//     $taxonomy_data = $_POST['taxonomyData'];
    
//     $args = array(
//         'post_type' => 'post', // Change to your custom post type
//         'tax_query' => array(),
//     );

//     foreach ($taxonomy_data as $taxonomy => $term_id) {
//         if (!empty($term_id)) {
//             $args['tax_query'][] = array(
//                 'taxonomy' => $taxonomy,
//                 'field' => 'term_id',
//                 'terms' => $term_id,
//             );
//         }
//     }

//     $query->set('post_type', $args['post_type']);
//     $query->set('tax_query', $args['tax_query']);
// });

// AJAX handler for fetching custom query data and setting Elementor query
add_action('wp_ajax_fetch_custom_query', 'fetch_custom_query_callback');
add_action('wp_ajax_nopriv_fetch_custom_query', 'fetch_custom_query_callback');


function fetch_custom_query_callback() {
    $taxonomy_data = $_POST['taxonomyData'];
    // var_dump($taxonomy_data);
    if ($taxonomy_data !== '') {

    $args = array(
        'post_type' => 'post', // Change to your custom post type
        'tax_query' => array(),
        // 'posts_per_page' => '-1',

    );

    foreach ($taxonomy_data as $taxonomy => $term_id) {
        if (!empty($term_id)) {
            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_id,
            );
        }
    }
    var_dump($args['tax_query']);
}
else {
    $args = array(
        'post_type' => 'post',
        // 'posts_per_page' => '-1',
    );
}


$posts = new WP_Query($args);
if ($posts->have_posts()) {
    while ($posts->have_posts()) {
        $posts->the_post();
        // Output HTML for each post in the grid
        // echo '<div class="post">' . get_the_title() . '</div>';
        // echo do_shortcode('[INSERT_ELEMENTOR id="14139"]');
        echo do_shortcode('[elementor-template id="480"]');

    }
} else {
    echo 'No posts found';
}

wp_die();
    // Set the query arguments

    // Define Elementor query filter action

    // Output success or error response based on your requirements
    // wp_send_json_success($data); // Replace $data with your response data
}
//     margin-right: 10px;
//     background: transparent;
//     border-bottom: 2px solid black;
//     color: black;
//     font-weight: 700;
//     border-radius: 0px;

