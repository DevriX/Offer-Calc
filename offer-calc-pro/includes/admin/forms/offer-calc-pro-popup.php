<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortocde UI
 *
 * This is the code for the pop up editor, which shows up when an user clicks
 * on the arrow icon within the WordPress editor.
 *
 * @package Offer Calc Pro
 * @since 1.0.0
 */
$offer_slug_arr = array();
$args = array(	
				'post_type' => OFFER_CALC_POST_TYPE,
				'post_status' => 'publish'			
			);
			
global $offer_calc_model;
$offer_post_arr = $offer_calc_model->offer_calc_get_post();

?>

<div class="offer-calc-popup-content">

	<div class="offer-calc-header-popup">
		<div class="offer-calc-popup-header-title"><?php _e( 'Add a Shortcodes', 'offercalc' );?></div>
		<div class="offer-calc-popup-close"><a href="javascript:void(0);" class="offer-calc-popup-close-button"><img src="<?php echo OFFER_CALC_PRO_URL;?>includes/images/tb-close.png"></a></div>
	</div>
	<div class="offer-calc-popup-error"><?php _e( 'Select a Shortcode', 'offercalc' );?></div>
	<div class="offer-calc-popup-container">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php _e( 'Select Offer Form', 'offercalc' );?></label>						
					</th>
					<td>
						<select id="offer_calc_select_shortcode">				
							<option value="">--Select Offer Form--</option>
							<?php
							
								foreach ($offer_post_arr as $ofr_post){
									echo "<option value='$ofr_post->post_name'>$ofr_post->post_title</option>";
								}
							?>
						</select>		
					</td>
				</tr>
			</tbody>
		</table>	
		
		<div id="offer_calc_insert_container" >
			<input type="button" class="button-secondary" id="offer_calc_insert_shortcode" value="<?php _e( 'Insert Shortcode', 'offercalc' ); ?>">
		</div>
		 
	</div>	
	
</div><!--.offer-calc-popup-content-->
<div class="offer-calc-popup-overlay" ></div><!--.offer-calc-popup-overlay-->