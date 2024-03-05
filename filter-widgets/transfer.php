<?php
/*
Plugin Name: Category Transfer
Description: Transfer child categories of the "Countries" parent category to a new taxonomy called "Country" and associate posts with the respective terms.
Version: 1.0
Author: Your Name
Author URI: Your Website
*/

// Plugin code will go here
// Add settings page under Tools menu
add_action( 'admin_menu', 'ct_add_settings_page' );

function ct_add_settings_page() {
    add_submenu_page(
        'tools.php',
        'Category Transfer',
        'Category Transfer',
        'manage_options',
        'category-transfer',
        'ct_render_settings_page'
    );
}

// Render settings page
function ct_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="">
            <?php
            wp_nonce_field( 'ct_transfer_categories', 'ct_transfer_categories_nonce' );
            wp_nonce_field( 'ct_transfer_sectors', 'ct_transfer_sectors_nonce' );

            ?>
            <button type="submit" name="ct_transfer_categories" class="button button-primary">Transfer Categories</button>
            <button type="submit" name="ct_transfer_sectors" class="button button-primary">Transfer Sectors</button>

        </form>
    </div>
    <?php
}

// Handle form submission
add_action( 'admin_init', 'ct_handle_form_submission' );

function ct_handle_form_submission() {
    if ( isset( $_POST['ct_transfer_categories'] ) && wp_verify_nonce( $_POST['ct_transfer_categories_nonce'], 'ct_transfer_categories' ) ) {
        ct_transfer_categories();
    }
    if ( isset( $_POST['ct_transfer_sectors'] ) && wp_verify_nonce( $_POST['ct_transfer_sectors_nonce'], 'ct_transfer_sectors' ) ) {
        ct_transfer_sectors();
    }
}

// Function to transfer categories and associate posts
function ct_transfer_categories() {
    // Load WordPress
    // require_once('wp-load.php');
    
    global $wpdb;
    
    // Get the ID of the parent category "Countries"
    // Get all child categories of the parent category
    $country_map = array(
        array(
            'name' => 'Bangladesh',
            'slug' => 'bangladesh'
        ),
        array(
            'name' => 'Cambodia',
            'slug' => 'cambodia'
        ),
        array(
            'name' => 'China',
            'slug' => 'china'
        ),
        array(
            'name' => 'India',
            'slug' => 'india'
        ),
        array(
            'name' => 'Indonesia',
            'slug' => 'indonesia'
        ),
        array(
            'name' => 'Kenya',
            'slug' => 'kenya'
        ),
        array(
            'name' => 'Malaysia',
            'slug' => 'malaysia'
        ),
        array(
            'name' => 'Myanmar',
            'slug' => 'myanmar'
        ),
        array(
            'name' => 'Nigeria',
            'slug' => 'nigeria'
        ),
        array(
            'name' => 'Philippines',
            'slug' => 'philippines'
        ),
        array(
            'name' => 'South Korea',
            'slug' => 'south-korea'
        ),
        array(
            'name' => 'Tanzania',
            'slug' => 'tanzania'
        ),
        array(
            'name' => 'Tanzania',
            'slug' => 'tanzania-countries'
        ),
        array(
            'name' => 'Thailand',
            'slug' => 'thailand'
        ),
        array(
            'name' => 'Uganda',
            'slug' => 'uganda'
        ),
        array(
            'name' => 'Ukraine',
            'slug' => 'ukraine'
        ),
        
        array(
            'name' => 'Ukraine',
            'slug' => 'ukraine-countries'
        ),
        array(
            'name' => 'Vietnam',
            'slug' => 'vietnam'
        ),
        array(
            'name' => 'South Korea',
            'slug' => 'south-korea'
        ),
        array(
            'name' => 'South Korea',
            'slug' => 'south-korea-countries'
        )
    );
    
    
    // Loop through each child category
    // Loop through each country
    foreach ( $country_map as $country ) {
        // Check if the term already exists in the "Country" taxonomy
        $existing_term = get_term_by( 'slug', $country['slug'], 'country' );
    
        // If the term doesn't exist, create it
        if ( ! $existing_term ) {
            // Insert the term into the "Country" taxonomy
            $term_id = wp_insert_term( $country['name'], 'country', array(
                'slug' => $country['slug'],
            ) );
    
            // Check if the term was inserted successfully
            if ( is_wp_error( $term_id ) ) {
                echo 'Error inserting term: ' . $term_id->get_error_message();
                continue;
            }
            
            // Get the newly created term ID
            $term_id = $term_id['term_id'];
        } else {
            // If the term already exists, use its ID
            $term_id = $existing_term->term_id;
        }
    
        // Get all posts assigned to the current country
        $cat_posts = get_posts( array(
            'category_name' => $country['slug'], // Fetch posts by category slug
            'numberposts' => -1,
        ) );
        
        // Array to hold term IDs
        $term_ids = array( $term_id );
    
        // Update the posts to associate them with the term in the "Country" taxonomy
        foreach ( $cat_posts as $post ) {
            wp_set_post_terms( $post->ID, $term_ids, 'country', true );
        }
    }
    
    echo 'Categories transferred successfully.';
    
}
// Function to transfer sectors and associate posts
function ct_transfer_sectors() {
    global $wpdb;
    
    // Define the sector map
    $sector_map = array(
        array(
            'name' => 'Aquaculture',
            'slug' => 'aquaculture'
        ),
        array(
            'name' => 'Bakery',
            'slug' => 'bakery'
        ),
        array(
            'name' => 'Horticulture',
            'slug' => 'horticulture'
        ),
        array(
            'name' => 'Piggery',
            'slug' => 'piggery'
        ),
        array(
            'name' => 'Poultry',
            'slug' => 'poultry'
        ),
        array(
            'name' => 'Rice',
            'slug' => 'rice'
        ),
        array(
            'name' => 'Water',
            'slug' => 'water-ppp-2'
        ),
        // Add other sectors here...
    );
    
    // Loop through each sector
    foreach ( $sector_map as $sector ) {
        // Check if the term already exists in the "Sector" taxonomy
        $existing_term = get_term_by( 'slug', $sector['slug'], 'sector' );
    
        // If the term doesn't exist, create it
        if ( ! $existing_term ) {
            // Insert the term into the "Sector" taxonomy
            $term_id = wp_insert_term( $sector['name'], 'sector', array(
                'slug' => $sector['slug'],
            ) );
    
            // Check if the term was inserted successfully
            if ( is_wp_error( $term_id ) ) {
                echo 'Error inserting term: ' . $term_id->get_error_message();
                continue;
            }
            
            // Get the newly created term ID
            $term_id = $term_id['term_id'];
        } else {
            // If the term already exists, use its ID
            $term_id = $existing_term->term_id;
        }
    
        // Get all posts assigned to the current sector
        $sector_posts = get_posts( array(
            'category_name' => $sector['slug'], // Fetch posts by category slug
            'numberposts' => -1,
        ) );
        
        // Array to hold term IDs
        $term_ids = array( $term_id );
    
        // Update the posts to associate them with the term in the "Sector" taxonomy
        foreach ( $sector_posts as $post ) {
            wp_set_post_terms( $post->ID, $term_ids, 'sector', true );
        }
    }
    
    echo 'Sectors transferred successfully.';
}
