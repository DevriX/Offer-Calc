<?php
if ( ! isset( $_SESSION ) ) {
	session_start();
}
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles all admin functinalities
 *
 * @package Offer Calc Pro 
 * @since 1.0.0
 */
class Offer_Calc_Pro_Public{
	
	public $model, $scripts, $error=array();
	
	function __construct(){
		
		global $offer_calc_model;
		$this->model = $offer_calc_model;
	}

	
	
	/**
	 * Show Image in calc
	 * 
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */
	public function offer_calc_pro_show_service_image( $service_fields, $post_id, $type ) {
		
		$prefix		= OFFER_CALC_META_PREFIX;
		
		$value_img	= $prefix. 'edit_image';
		$val		= get_post_custom( $post_id );
		
		if( isset( $val[$value_img] ) && !empty( $val[$value_img] ) ) {
			
			if( $type == 'th' ) {
				
				echo $service_head = '<th>'. apply_filters( 'offer_calc_dynamic_title', __( 'Image','offercalc' ), $post_id ) .'</th>';
				return;
				
			} else {
				
				if( !empty( $service_fields['offer_image']['src'] ) ) {
					
					echo $service_head = '<td align="center"><img src="'.$service_fields['offer_image']['src'].'" width="50px" height="50px" /></td>';
				} else{
					
					echo $service_image = '<td align="center"></td>';
				}
				
				return;
			}
		}
	}

	/**
	 * Replace service dropdwn with textbox
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function offer_calc_pro_replace_count_dropdown( $service_fields, $row, $id, $place) {
		
		$slug = explode( '_', $id );
		
		if( !empty( $id ) && !empty( $row['replace_count_dropdown'] ) && $row['replace_count_dropdown'] == 1 ){			
													
			if( !empty($row['count_field_type']) && $row['count_field_type'] == 1 ){
				
				$radio_id = array_reverse($slug);
				$radio_id = $radio_id[0];
				$class = ( strpos( $place,'widget' ) !== false ) ? 'ofc_cnt_radio_widget' : 'ofc_cnt_radio_shortcode' ;
				
				$radio_arr = isset($row['count_field_type_opt']['radio']) ? $row['count_field_type_opt']['radio'] : array();
				$radio_prc_arr = isset($row['count_field_type_opt']['radio_price']) ? $row['count_field_type_opt']['radio_price'] : '';
				$service_fields = '';
				$count = 1;
				foreach ( $radio_arr as $k_r => $v_r ) {
				 	$service_fields .= '<input type="radio" id="offer_count_'.$id.'_'.$count.'" class="'.$class.'" name="'.$slug[0].'_count['.$radio_id.']" value="'.$radio_prc_arr[$k_r].'"><label for="offer_count_'.$id.'_'.$count.'" class="ofc-lbl">'.$v_r.'</label>';
				 	$count++;
				}
				return $service_fields;
			}
			
			if( !empty( $row['count_field_type'] ) && $row['count_field_type'] == 2 ){				
				
				$class = ( strpos( $place,'widget' ) !== false ) ? 'ofc_cnt_chk_widget' : 'ofc_cnt_chk_shortcode' ;
				
				$val = $row['count_field_type_opt']['checkbox'];
				return $service_fields = '<input type="checkbox" id="offer_count_'.$id.'" class="'.$class.'" name="'.$slug[0].'_count[]" value="1"><label for="offer_count_'.$id.'" class="ofc-lbl">'.$val.'</label>';
			}
			
			if( !empty( $row['count_field_type'] ) && $row['count_field_type'] == 3 ){
				
			    $class = ( strpos( $place,'widget' ) !== false ) ? 'ofc_cnt_txt_widget' : 'ofc_cnt_txt_shortcode' ;
			
				$label = $row['count_field_type_opt']['text'];
				return $service_fields = '<label for="offer_count_'.$id.'" class="ofc-lbl">'.$label.'</label><input type="text" id="offer_count_'.$id.'" class="'.$class.'" name="'.$slug[0].'_count[]" value="">';
			}
			
			if( !empty( $row['count_field_type'] ) && $row['count_field_type'] == 4 ){
				
			    $class = ( strpos( $place,'widget' ) !== false ) ? 'ofc_cnt_txt_widget' : 'ofc_cnt_txt_shortcode' ;
			
				$label = $row['count_field_type_opt']['textarea'];
				return $service_fields = '<label for="offer_count_'.$id.'" class="ofc-lbl">'.$label.'</label><textarea col="2" row="1" id="offer_count_'.$id.'" class="'.$class.'" name="'.$slug[0].'_count[]" value=""></textarea>';
			}
			
			if( !empty($row['count_field_type']) && $row['count_field_type'] == 5 ){
				
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
				
				$class = ( strpos( $place,'widget' ) !== false ) ? 'offer_selector' : 'offer_selector_pro' ;
				$label = $row['count_field_type_opt']['select'];
				$service_cnt = '<select id="offer_count_'.$id.'" class="'.$class.'" name="'.$slug[0].'_count[]">';					
					for( $i=$start; $i <= $end; $i++ ):
						$service_cnt .= '<option value="'.$i.'">'.$i.'</option>';
					endfor;
				$service_cnt .= '</select>';
				return $service_fields = '<label for="offer_count_'.$id.'" class="ofc-lbl">'.$label.'</label>'.$service_cnt;
			}
			
			if( !empty($row['count_field_type']) && $row['count_field_type'] == 6 ){
				
				$class = ( strpos( $place,'widget' ) !== false ) ? 'offer_multi_selector multi' : 'offer_selector_multi_shortcode multi' ;
				$label = $row['count_field_type_opt']['select'];
				$select_arr = $row['count_field_type_opt']['multi_select'];
				
				
				
				$select_prc_arr = $row['count_field_type_opt']['multi_select_price'];
				
				$service_fields = '';
				
				$service_cnt = '<select id="offer_count_'.$id.'" class="'.$class.'" name="'.$slug[0].'_count_multiple[]" multiple>';		
					foreach ( $select_arr as $k_r=>$v_r ){
						
						if( $v_r != '' )
						{
							if($select_prc_arr[$k_r] == '')
							{
								$service_cnt .= '<option value="0">'.$v_r.'</option>';
							}	
							else
							{
								$service_cnt .= '<option value="'.$select_prc_arr[$k_r].'">'.$v_r.'</option>';	
							}
						}
					}
				$service_cnt .= '</select>';
				$service_cnt .= '<input type="hidden" name="'.$slug[0].'_count[]">';
				return $service_fields = '<label for="offer_count_'.$id.'" class="ofc-lbl">'.$label.'</label>'.$service_cnt;
			}
			
			if( !empty( $row['count_field_type'] ) && $row['count_field_type'] == 7 ){
				
			    $class = ( strpos( $place,'widget' ) !== false ) ? 'offer_selector' : 'offer_selector_pro' ;			
				$label = $row['count_field_type_opt']['number'];
				return $service_fields = '<label for="offer_count_'.$id.'" class="ofc-lbl">'.$label.'</label><input id="offer_count_'.$id.'" class="'.$class.'" type="number" name="'.$slug[0].'_count[]" min="0" max="40" value="0">';
			}
			
		}
		else {			
			
			if( !empty( $row['dropdown_limit'][0] ) && !empty($row['dropdown_limit'][1]) ){
								
				$start 	= ( !empty( $row['dropdown_limit'][0] ) ) ? $row['dropdown_limit'][0] : 0;
				if( $start > 0 && empty( $row['dropdown_limit'][1] ) ) {
					$end = $start + 50 ;					
				}else{					
					$end = $row['dropdown_limit'][1];
				}
				
				$class = ( strpos( $place,'widget' ) !== false ) ? 'offer_selector' : 'offer_selector_pro' ;
				
				$service_cnt = '<select id="offer_count_'.$id.'" class="'.$class.'" name="'.$slug[0].'_count[]">';
					$service_cnt .= '<option value="0">-Select-</option>';
					for( $start; $start <= $end; $start++ ):
						$service_cnt .= '<option value="'.$start.'">'.$start.'</option>';
					endfor;
				$service_cnt .= '</select>';
				return $service_cnt;
				
			}			
			return $service_fields;
		}	
		return $service_fields;	
		
	}
	
	/**
	 * Create Calc titles dynamic
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function offer_calc_pro_dynamic_title( $ofr_field, $post_id ) {	
			
		$prefix = OFFER_CALC_META_PREFIX;
		if( !empty( $post_id ) ){
					
			if( $ofr_field == 'Image' ){				
				$ofr_meta = get_post_meta( $post_id, $prefix.'image_title', true );				
			}
			elseif( $ofr_field == 'Service' ){
				$ofr_meta = get_post_meta( $post_id, $prefix.'service_title', true );				
			}
			elseif( $ofr_field == 'Price' ){
				$ofr_meta = get_post_meta( $post_id, $prefix.'price_title', true );				
			}
			elseif( $ofr_field == 'Count' ){
				$ofr_meta = get_post_meta( $post_id, $prefix.'count_title', true );				
			}
			elseif( $ofr_field == 'Total' ){
				$ofr_meta = get_post_meta( $post_id, $prefix.'total_title', true );				
			}	
			
			if( !empty($ofr_meta) ){
				return $ofr_meta;
			}
			else{
				return $ofr_field;
			}
		}	
	}
	
	/**
	 * Show send email button
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function offer_calc_pro_sent_email_btn( $post_id, $type ) {	
				
		$prefix = OFFER_CALC_META_PREFIX;
		$enable_email_offer = $prefix. 'enable_email_offer';
		$value_img = $prefix. 'email';		
		$val = get_post_custom( $post_id );			
		
		$type = "'$type'";
		
		$rnd = rand();
		$_SESSION['offer_calc_captcha_code'][$rnd] = '';
		
		$submission_offer_value = get_post_meta( $post_id, $prefix.'submission_offer_value', true );
		//$submission_offer_value = !empty($submission_offer_value) ? $submission_offer_value : '';
		
		// Condition for global minimum submission offer value
		if (empty($submission_offer_value)) {
			
			$offer_calc_options = get_option( 'offer_calc_options' );
			$submission_offer_value = !empty($offer_calc_options['global_offer_value']) ? $offer_calc_options['global_offer_value'] : '';
		}
		
		// Condition for global email enable settings
		if(empty($val[$enable_email_offer])){
			
			$offer_calc_options = get_option( 'offer_calc_options' );
			$enable_email_offer = !empty($offer_calc_options['global_enable_offer']) ? $offer_calc_options['global_enable_offer'] : '';
		
		} else {
			$enable_email_offer = $val[$enable_email_offer];
		}
		
		// Condition for global email enable settings
		if(empty($val[$value_img])){
			
			$offer_calc_options = get_option( 'offer_calc_options' );
			$enable_email_field = !empty($offer_calc_options['global_email_addr']) ? $offer_calc_options['global_email_addr'] : '';
		
		} else {
			$enable_email_field = $val[$value_img];
		}
		
		if(isset($enable_email_offer) && !empty($enable_email_offer)) {		
			
			echo '<input type="hidden" name="offer_id" class="offer-id-hidden" value="'.$post_id.'" >';	
			echo '<input type="hidden" name="offer_total" class="offer_total" value="" >';			
			echo '<input type="hidden" name="offer_submission_value" class="offer_submission_value" value="'.$submission_offer_value.'" >';		 
			echo '<div class="flt_lt captcha-content" > <input type="text" name="offer_captcha" id="offer_captcha"  placeholder="Enter Captcha" autocomplete="off">
			<img id="ofc-captcha-refresh" src="'. OFFER_CALC_PRO_URL . 'images/refresh-icon.png" class="offer-captcha-refresh"/>
			<br/>';
				//echo '<img src="'. OFFER_CALC_PRO_URL . 'includes/offer-calc-captcha-code.php?ofc_id='.$post_id.'&rand='.$rnd.'" id="ofc-captcha-img-'.trim($type,"'").'" alt="" class="ofc-captcha-img" />';
				echo '<img src="'. site_url().'/?offer_calc_captcha=true&ofc_id='.$post_id.'&rand='.$rnd.'" id="ofc-captcha-img-'.trim($type,"'").'" alt="" class="ofc-captcha-img" />';
			if ( isset($_POST['offer_id'], $this->error['captcha']) && $_POST['offer_id'] == $post_id ) {
				echo '<label for="offer_total" class="error err-wrong-captcha">'.$this->error['captcha'].'</lagel>';
			}
			echo '<input type="hidden" name="captcha_id" class="captcha_id" value="'.$rnd.'" >';	
			echo '</div>';	
			
			echo '<div class="error err-captcha" for="offer_captcha"  style="display:none;">Captcha is required.</div>';
			
			if(isset($enable_email_field) && !empty($enable_email_field)){
			
				echo '<div class="flt_lt offer_email" > <input type="text" name="offer_email" value="" placeholder="Email Address"/></div>';
				echo '<div class="error err-email" for="offer_email"  style="display:none;">Invalid Email Address.</div>';	
				
				echo '<div class="error err-empty-email" for="offer_email"  style="display:none;">Email Address is required.</div>';
				echo '<div class="flt_lt offer_extra_note" ><textarea name="offer_extra_note" placeholder="Extra Note"></textarea></div>';				
				//echo '<div class="flt_rt offer_email" ><input type="submit" name="submit_offer" value="Send Offer Email" class="flt_rt"></div>';		
				echo '<div class="flt_rt offer_email" ><input type="button" name="submit_offer" value="Send Offer Email" class="submit-offer"></div>';
			}
		}		
	}
	
	/**
	 * Send email when offer submits
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function offer_calc_pro_sent_email() {		
				
		$prefix = OFFER_CALC_META_PREFIX;
		if( isset( $_POST['calc-type']) && !empty($_POST['calc-type']) ){	
			
			if( $_POST['offer_captcha'] == $_SESSION['offer_calc_captcha_code'][$_POST['captcha_id']][$_POST['offer_id']] ){
			//if( true ){
							
				unset($_SESSION['offer_calc_captcha_code']);
				if( isset( $_POST['offer_id'] ) ){
					$post = get_post( $_POST['offer_id'] );
				}
				
				if( isset( $_POST['offer_email'] ) ){
					$offer_user_email =  $_POST['offer_email'] ;
				}
					
				if( isset( $_POST['offer_extra_note'] ) && !empty($_POST['offer_extra_note']) ){
					$offer_extra_note = nl2br($_POST['offer_extra_note']) ;
				}
				
				//For VAT
				$offer_total			= isset( $_POST['offer_total'] ) && !empty($_POST['offer_total']) ? $_POST['offer_total'] : '';
				$offer_vat_amount		= isset( $_POST['offer_hidden_vat_amount'] ) && !empty($_POST['offer_hidden_vat_amount']) ? $_POST['offer_hidden_vat_amount'] : '';
				$offer_vat_sub_total	= isset( $_POST['offer_hidden_vat_sub_total'] ) && !empty($_POST['offer_hidden_vat_sub_total']) ? $_POST['offer_hidden_vat_sub_total'] : '';
				$offer_vat_enable		= isset( $_POST['offer_hidden_vat_enable'] ) && !empty($_POST['offer_hidden_vat_enable']) ? $_POST['offer_hidden_vat_enable'] : '';
				
				//For Coupon Code
				$offer_coupon_code = isset( $_POST['offer_calc_coupon_discount'] ) && !empty($_POST['offer_calc_coupon_discount']) ? trim($_POST['offer_calc_coupon_discount']) : '';
						
				$offer_fields			= get_post_meta( $_POST['offer_id'], $prefix.'service_fields', true );
				$offer_email			= get_post_meta( $_POST['offer_id'], $prefix.'email', true );		
				
				
				$offer_img = get_post_meta( $_POST['offer_id'], $prefix.'edit_image', true );
				
				foreach( $offer_fields as $f_key => $field ){
					
					if (isset($field['name']) && !empty($field['name'])) {
						
						if( isset($field['count_field_type']) && $field['count_field_type'] == 1 ){																
							$count = (!empty($_POST[$post->post_name.'_count'][$f_key + 1])) ? $_POST[$post->post_name.'_count'][$f_key + 1] : 0;
						}elseif( isset($field['count_field_type']) && $field['count_field_type'] == 6 ){												
							$count = (!empty($_POST[$post->post_name.'_count_multiple'])) ? $_POST[$post->post_name.'_count_multiple'] : 0;
						}
						else {
							$count = (!empty($_POST[$post->post_name.'_count'][$f_key])) ? $_POST[$post->post_name.'_count'][$f_key] : 0;
												
						}
						
						if(isset($offer_img) && !empty($offer_img)){
							
							$offer_arr['image'] = $field['offer_image']['src'];
						}					
						$offer_arr['name']  = $field['name'];
						$offer_arr['price'] = $field['price'];
						$offer_arr['count'] = $count;				
						$offer_arr['sum']   = $_POST[$post->post_name.'_sum'][$f_key];				
						$offer_arr_final[] 	= $offer_arr;
					}
				}		
				
				$meta = get_post_meta($post->ID, '_offercalc_service_fields',true);
				
				foreach ($meta as $mk => $m ){
					
					if (isset($m['count_field_type'])) {
					
						if($m['count_field_type'] == 1) {
							$label_key = array_search($offer_arr_final[$mk]['count'], $m['count_field_type_opt']['radio_price']);
							$offer_arr_final[$mk]['count_label']=$m['count_field_type_opt']['radio'][$label_key];
						}				
						if( $m['count_field_type'] == 2) {
							$offer_arr_final[$mk]['count_label'] = $m['count_field_type_opt']['checkbox'];
						}
						if( $m['count_field_type'] == 3) {
							$offer_arr_final[$mk]['count_label'] = $m['count_field_type_opt']['text'];
						}
						if( $m['count_field_type'] == 4) {
							$offer_arr_final[$mk]['count_label'] = $m['count_field_type_opt']['textarea'];
						}
						if( $m['count_field_type'] == 5) {
							$offer_arr_final[$mk]['count_label']= $m['count_field_type_opt']['select'];
						}
						if( $m['count_field_type'] == 6) {
							
							if (isset($offer_arr_final[$mk]['count']) && !empty($offer_arr_final[$mk]['count'])) {
								foreach($offer_arr_final[$mk]['count'] as $cnt_k => $cnt_v){
									$label_key = array_search($offer_arr_final[$mk]['count'][$cnt_k], $m['count_field_type_opt']['multi_select_price']);
									$offer_arr_final[$mk]['count_label'][$cnt_k]=$m['count_field_type_opt']['multi_select'][$label_key];									
								}		
							}
						}	
						if( $m['count_field_type'] == 7) {
							$offer_arr_final[$mk]['count_label'] = $m['count_field_type_opt']['number'];
						}	
					}
								
				}	
				
				include_once( OFFER_CALC_PRO_ADMIN . '/forms/offer-email.php' );
				
				$offer_calc_options = get_option( 'offer_calc_options' );
				
				$offer_calc_global_email_addr	= !empty($offer_calc_options['global_email_addr']) ? $offer_calc_options['global_email_addr'] : '';
				
				
				$offer_calc_admin_email_addr	= !empty($offer_calc_options['admin_email_addr']) ? $offer_calc_options['admin_email_addr'] : get_option('admin_email');
				$offer_calc_sender_name			= !empty($_POST['offer_email']) ? trim($_POST['offer_email']) : '';
				$offer_calc_admin_email_subject	= !empty($offer_calc_options['admin_email_subject']) ? $offer_calc_options['admin_email_subject'] : 'Offer calc';
				$offer_calc_admin_email_body	= !empty($offer_calc_options['admin_email_body']) ? $offer_calc_options['admin_email_body'] : '';
				$offer_calc_user_email_subject	= !empty($offer_calc_options['user_email_subject']) ? $offer_calc_options['user_email_subject'] : 'Offer calc';
				$offer_calc_user_email_body		= !empty($offer_calc_options['user_email_body']) ? $offer_calc_options['user_email_body'] : '';
				
				//$offer_calc_sender_name			= str_replace( '{blogname}', get_option( 'blogname' ), $offer_calc_sender_name );
				//$offer_calc_sender_name			= str_replace( '{sendermail}', get_option( 'admin_email' ), $offer_calc_sender_name );
				$offer_calc_user_email_body		= str_replace( '{offer}', $email_html, $offer_calc_user_email_body );
				$offer_calc_admin_email_body	= str_replace( '{offer}', $email_html, $offer_calc_admin_email_body ); 
				
				if(empty($offer_calc_sender_name)) {
					
					$headers[]	= 'From: '.get_option( 'blogname' ).'<'.get_option( 'admin_email' ).'>';
				} else {
					$headers[]	= 'From: '.$offer_calc_sender_name;
				}
								
				$headers[]		= "Content-type:text/html;charset=UTF-8" . "\r\n";
								
				$offer_calc_admin_email_addr	= explode(",", $offer_calc_admin_email_addr);
				
				foreach ($offer_calc_admin_email_addr as $key){
					
					$admin_email_to[] = trim($key);
				}
				
				// Condition for global email address send to user
				if(empty($offer_email)){
			
					$offer_calc_global_email_addr = explode(",", $offer_calc_global_email_addr);
				
					foreach ($offer_calc_global_email_addr as $key){
						
						$global_email_to[] = trim($key);
					}
				} else {
					$global_email_to = $offer_email;
				}
				
				
								
				// Send Email To User
				wp_mail( $offer_email, 'Offer calc', $email_html, $headers );
				wp_mail( $global_email_to, $offer_calc_user_email_subject, $offer_calc_user_email_body, $headers );
				
				// Send Email To Admin
				//wp_mail( $admin_email_to, $offer_calc_admin_email_subject, $offer_calc_admin_email_body, $headers );
												
				if( $_POST['calc-type'] == 'shortcode' ){
					$msg = 1;	
				}
				elseif( $_POST['calc-type'] == 'widget' ){
					$msg = 2;				
				}
				else {
					$msg = 0;
				}
				
				do_action( 'offer_calc_save_offer' , $post, $offer_arr_final, $offer_user_email, $offer_extra_note, $offer_coupon_code, $offer_vat_enable, $offer_vat_sub_total, $offer_vat_amount, $offer_total );
				
				unset($_SESSION['offer_calc_captcha_code']);
				
				$redirect_custom_url = add_query_arg(  array( 'email_send' => $msg , 'calc_slug' => $post->post_name ), $_SERVER['HTTP_REFERER'] );
				
				$redirect_url = apply_filters('offer_calc_success_url', $redirect_custom_url, $_POST['offer_id'], $post);
				
				wp_redirect( $redirect_url );
				exit;
				
				//do_action('offer_calc_rurl_get_url',$_POST['offer_id'], $msg, $post);		
			}
			else {
				$this->error['captcha'] = "Wrong captcha code.";		
			}
		}
	}	
	
	/**
	 * HTML email option
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function offer_calc_pro_email_msg(  ) {			
		echo '<div>
				<font color="green">Offer email send successfully.</font>
			</div>';
	}
	
	public function offer_calc_get_captcha_code() {
		
		if( isset( $_GET['offer_calc_captcha'] ) && $_GET['offer_calc_captcha'] == 'true' ){
			
			include_once( OFFER_CALC_PRO_DIR . '/includes/offer-calc-captcha-code.php' );
			exit;
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Offer Calc Pro
	 * @since 1.0.0
	 */	
	public function add_hooks() {
		
		//custom action / filter
		add_filter( 'offer_calc_replace_count_dropdown', array( $this, 'offer_calc_pro_replace_count_dropdown' ), 10, 4 );
		add_filter( 'offer_calc_dynamic_title', array( $this, 'offer_calc_pro_dynamic_title' ), 10, 2 );
		
		add_action( 'offer_calc_show_service_image', array( $this, 'offer_calc_pro_show_service_image' ), 10, 3 );	
		
		add_action( 'offer_calc_sent_email_btn', array( $this, 'offer_calc_pro_sent_email_btn' ), 10 ,2);	
		add_action( 'init', array( $this, 'offer_calc_pro_sent_email' ), 10 ,1 );		
		add_action( 'offer_calc_email_msg', array( $this, 'offer_calc_pro_email_msg' ), 10 );
		
		add_action( 'init', array( $this, 'offer_calc_get_captcha_code' ));
	}
}
?>
