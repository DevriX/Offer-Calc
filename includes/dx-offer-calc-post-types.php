<?php
/**
 * Post Type Functions
 *
 * Handles all custom post types
 * 
 * @package Offer Calc
 * @since 1.0.0 
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Setup Offer Calc Post Type
 *
 * Registers the Offer Calc Post Types
 * 
 * @package Offer Calc
 * @since 1.0.0 
 */

function offer_calc_register_post_types() {
	
	if ( get_option( 'ofc_version' ) < 1  ) {
		$show_in_menu = false;
	} else {
		$show_in_menu = true;
	}
	
	$offer_labels =  array(
		'name' 				=> __( 'Offer Calc','offercalc' ),
		'singular_name' 	=> __( 'Offer','offercalc' ),
		'add_new' 			=> __( 'Add Offer', 'offercalc' ),
		'add_new_item' 		=> __( 'Add New Offer', 'offercalc' ),
		'edit_item' 		=> __( 'Edit Offer', 'offercalc' ),
		'new_item' 			=> __( 'New Offer', 'offercalc' ),
		'all_items' 		=> __( 'Offer Calc', 'offercalc' ),
		'view_item' 		=> __( 'View Offer', 'offercalc' ),
		'search_items' 		=> __( 'Search Offer', 'offercalc' ),
		'not_found' 		=> __( 'No offers found', 'offercalc' ),
		'not_found_in_trash'=> __( 'No offers found in Trash', 'offercalc' ), 
		'parent_item_colon' => '',
		'menu_name' 		=> __( 'Offer Calc', 'offercalc' )
	);
	
	$offer_args = array(
		'labels' 			=> $offer_labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true, 
		'show_in_menu' 		=> $show_in_menu, 
		'query_var' 		=> true,
		'rewrite' 			=> array( 'slug' => OFFER_CALC_POST_TYPE ),
		'capability_type' 	=> 'post',
		'has_archive' 		=> true, 
		'hierarchical' 		=> false,
		'supports' 			=> array( 'title', 'editor' ),		 
		'menu_icon'			=> OFFER_CALC_URL . '/images/offercalc-fav-icon.png'												
	);
						
	register_post_type( OFFER_CALC_POST_TYPE, $offer_args );
	
}
//register custom post type
add_action('init','offer_calc_register_post_types');


/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 *
 * @package Offer Calc
 * @since 1.0.0
 */  
function offer_calc_updated_messages( $messages ) {
		
	global $post, $post_ID;

	$messages[OFFER_CALC_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Offer updated. <a href="%s">View Offer</a>', 'offercalc' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'offercalc' ),
		3 => __( 'Custom field deleted.', 'offercalc' ),
		4 => __( 'Offer updated.', 'offercalc' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Offer restored to revision from %s', 'offercalc' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Offer published. <a href="%s">View Offer</a>', 'offercalc' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Offer saved.' ),
		8 => sprintf( __( 'Offer submitted. <a target="_blank" href="%s">Preview Offer</a>', 'offercalc' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Offer scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Review</a>', 'offercalc'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Offer draft updated. <a target="_blank" href="%s">Preview Offer</a>', 'offercalc' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		11 => __( 'Data migration done sucessfully!', 'offercalc' ),
	);

	return $messages;
}
	
add_filter( 'post_updated_messages', 'offer_calc_updated_messages' );

?>