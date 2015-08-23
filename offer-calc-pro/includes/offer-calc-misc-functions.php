<?php
/**
 * Misc functions file
 *
 * @package Offer Calc Pro 
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get AutoResponders.
 * 
 * @package Offer Calc Pro
 * @since 1.0.0
 */
function offer_calc_get_autores( $post_id = false ) {
	
	$offer_calc_autores_value	= false;
	
	if( !empty( $post_id ) ) {
		
		$offer_calc_autores_value	= get_post_meta( $post_id, '_offer_calc_autoresponder', true );
	}
	
	return $offer_calc_autores_value;
}

/**
 * Get Base Currency Code.
 * 
 * @package Offer Calc Pro
 * @since 1.0.0	
 */
function offer_calc_get_list_value( $post_id = false, $offer_code ) {
	
	$offer_calc_code_value	= false;
	
	if( !empty( $post_id ) ) {
		$offer_calc_code_value	= get_post_meta( $post_id, $offer_code, true );
	}
	
	return $offer_calc_code_value;
}