<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode Class
 *
 * Handles adding shortcode functionality to the admin pages
 * as well as the front pages.
 *
 * @package Offer Calc
 * @since 1.0.0
 */
class Offer_Calc_Shortcodes {
	
	public function __construct(){
		global $offer_calc_model;
		$this->model = $offer_calc_model;
	}
	
	/**
	 * Replace Shortcode with Custom Content
	 *
	 * @param $atts this will handles to various attributes which are passed in shortcodes
	 * @param $content this will return the your replaced content
	 * 
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	public function offer_calc_shortcode( $atts, $content='' ) {
		
		global $wpdb;		
		
		$prefix	= OFFER_CALC_META_PREFIX;
		
		//content to replace with your content and with attributes
		extract( shortcode_atts( array(	
				'offer_slug'	=>	'offer_slug',
				
		), $atts ) );
				
		ob_start();
		
		if(!empty($offer_slug)){	    			
		
		$offer_post = $this->model->offer_calc_get_post($offer_slug);
		
		
		if(!empty($offer_post)){
			
			$service_fields = get_post_meta($offer_post[0]->ID, $prefix.'service_fields',true);		
				
			if( !empty($service_fields) ){
				ob_start();
				
				?><div class="offercalc_wrapper" id="offercalc_wrapper_<?php echo $offer_slug; ?>"><?php 
					
					if( isset( $_GET['calc_slug'], $_GET['email_send'] ) && $offer_slug == $_GET['calc_slug'] && $_GET['email_send'] ==1 ) {
						do_action( 'offer_calc_email_msg' ); 
						do_action ( 'ofer_calc_payment_messages' );
					} 
						
					?><form action="" name="frm_offer" method="POST" id="frm_offer_short" class="offer-form">
						<input type="hidden" name="calc-type" value="<?php echo ($content == 'widget' ) ? 'widget' : 'shortcode' ;?>"><?php 
							
						$count = 0;
						$total = '<span id="total_offercalc_sum_'.$offer_slug.'" class="total-offercalc-sum-span">0</span>';
						
						foreach ( $service_fields as $row ) :
						
							$count++;
							$row['price'] = isset($row['price']) && !empty($row['price']) ? number_format($row['price'], 2, '.', ',') : '';
							$price = "<span id='offer_price_".$offer_slug."_".$count."' class='price-span'>". (($row['price'] != '') ? $row['price'] : 0 ) ."</span>";
							
							if(isset($row['name']) && !empty($row['name'])){
							
								?><div class="offer_calc_wrapper" id="offer_calc_wrapper_test">
									
									<div class="offer_calc_order offer_calc_service">
										<div class="offer_calc_display_img"><?php do_action( 'offer_calc_show_service_image' , $row, $offer_post[0]->ID, 'td'); ?></div>
										<div class="offer_calc_ordertitle_service">
											<label class="offer_calc_service_name"><?php echo $row['name']; ?></label>
										</div>
										<p></p>
									</div>
									<div class="clr"></div>
										
									<div class="offer_calc_order offer_calc_price offer_calc_service-click">
										<div class="offer_calc_order offer_calc_service-next">
											<div class="offer_calc_ordertitle">
												<label><?php echo apply_filters ( 'offer_calc_dynamic_title', __('Price','offercalc'), $offer_post[0]->ID  );?></label>
											</div>
											<div>
												<span class="offer_calc_currency"><?php echo apply_filters('offer_calc_add_currency', $price, $offer_post[0]->ID);?></span>
											</div>
										</div>
										<div class="offer_calc_order offer_calc_service-next">
											<div class="offer_calc_ordertitle"><label><?php echo apply_filters ( 'offer_calc_dynamic_title', __('Count','offercalc'), $offer_post[0]->ID  );?></label></div>
											<div class="offer_calc_orderlist count-td"><?php 
												$id = $offer_slug.'_'.$count;
											
												if( !empty( $row['dropdown_limit'][0] ) && !empty($row['dropdown_limit'][1]) ){
								
													$start 	= ( !empty( $row['dropdown_limit'][0] ) ) ? $row['dropdown_limit'][0] : 0;
													if( $start > 0 && empty( $row['dropdown_limit'][1] ) ) {
														$end = $start + 50 ;					
													}else{					
														$end = $row['dropdown_limit'][1];
													}
													
												} else {
													
													$start = 0;
													$end = 40;
												}
												
												$service_cnt = '<select id="offer_count_'.$id.'" class="offer_selector_shortcode offer_selector_pro" name="'.$offer_slug.'_count[]">';
													for($i = $start; $i <= $end; $i++):
													
														$service_cnt .= '<option value="'.$i.'">'.$i.'</option>';
													endfor;
												$service_cnt .= '</select>';			
												
												echo apply_filters( 'offer_calc_replace_count_dropdown', $service_cnt, $row, $id, 'shortcode' );
											?></div>
										</div>
										<div class="offer_calc_order offer_calc_service-next">
										   <div class="offer_calc_ordertitle"><label><?php echo apply_filters ( 'offer_calc_dynamic_title', __('Total','offercalc'), $offer_post[0]->ID  );?></label></div>
										   <div class="offer_calc_orderlist"><input id="offer_sum_<?php echo $offer_slug; ?>_<?php echo $count; ?>" type="text" name="<?php echo $offer_slug;?>_sum[]" readonly="readonly" value="0" class="offer-sum" ></div>
										</div>
									</div>
								</div><?php 
							} 
						endforeach; 
						
						?><div class="error err-total" for="offer_total" style="display:none;">Total should be greater than zero.</div>
						<div class="error err-submission-value" for="offer_submission_value" style="display:none;"></div>
						<?php do_action( 'offer_calc_before_total',  $offer_post[0]->ID ) ;?>
						<div class="total-offercalc-sum-div total_offercalc_sum clr"><?php _e('Total: ','offercalc');?><?php echo apply_filters('offer_calc_add_currency', $total, $offer_post[0]->ID);?></div>						
						<div class="clr"></div>
						<div class="offer-calc-add-coupons-codes"><?php do_action ( 'offer_calc_add_coupons_codes', $offer_post[0]->ID, $offer_slug ); ?></div>
						<div class="clr"></div>
						<?php do_action( 'offer_calc_sent_email_btn',  $offer_post[0]->ID, 'shortcode') ;?>
						<div class="clr"></div>	
					</form>
				</div><?php
			    
				} else {
					?><div><em><?php _e('"Offer service not added"','offercalc');?><em></div><?php 
				}
			} else {
				?><div><em><?php _e('"Invalid offer calc ShortCode"','offercalc');?><em></div><?php 
			}
		} else {
			?><div><em><?php _e('"Invalid offer calc ShortCode"','offercalc');?><em></div><?php 
		}
	    $output = ob_get_contents();
	    ob_end_clean();
	    return $output;
	}
		
	/**
	 * Adding Hooks
	 *
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//replace shortcodes with custom content or HTML
		add_shortcode('ofc_shortcode',array($this, 'offer_calc_shortcode'));
	}
}
?>
