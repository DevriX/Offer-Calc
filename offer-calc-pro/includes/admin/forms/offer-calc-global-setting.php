<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Global Setting page
 *   
 * @package Offer Calc Pro
 * @since 1.0.0
 */

global $offer_calc_model;
$this->model	= $offer_calc_model;

$page_lable 	= __('Email Global Settings', 'offercalc');

//product page submit button text either it is Add or Update
$page_btn = __('Save Changes', 'offercalc');
?>

<div class="wrap">

<?php echo screen_icon('options-general');?>

<h2><?php _e( $page_lable , 'offercalc'); ?></h2>
<?php
//all settings will reset as per default
if(isset($_POST['offer_calc_reset_settings']) && !empty($_POST['offer_calc_reset_settings']) && $_POST['offer_calc_reset_settings'] == __( 'Reset All Settings', 'wpdws' )) { //check click of reset button
	
	offer_calc_default_settings(); // set default settings
	
	echo '<div class="updated" id="message">
		<p><strong>'. __("All Settings Reset Successfully.",'offercalc') .'</strong></p>
	</div>';
	
}
	 
if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {		
	?><div class="updated below-h2" id="message"><p>
		<?php echo __( 'Your settings has been saved successfully.', 'offercalc' );?>
	</p></div><?php 
	
}?>

<div class="error wrong_setting" style="display:none;">
	<p>
		<?php echo __( 'Oops, Something happened Wrong.', 'offercalc' );?>
	</p>
</div>

<div class="offer-calc-reset-setting">
	<form method="post" action="">
		<div class="offer-calc-save-button">
			<input id="offer_calc_reset_all_options" type="submit" class="button-primary" name="offer_calc_reset_settings" value="<?php echo __( 'Reset All Settings', 'offercalc' ); ?>" />
		</div>
	</form>
</div>
<!-- beginning of the general settings -->

<div id="offer-calc-settings" class="post-box-container">

	<div class="metabox-holder">	

		<div class="meta-box-sortables ui-sortable">

			<div id="product" class="postbox">	

				<div class="handlediv" title="<?php _e( 'Click to toggle', 'offercalc' ) ;?>"><br /></div>

					<!-- product box title -->

					<h3 class="hndle">
						<span style="vertical-align: top;"><?php _e( 'General Settings', 'offercalc' );?></span>
					</h3>
					
					<div class="inside">

					<form method="post" action="options.php">
						<input type="hidden" name="page" value="offer_calc_add_form" /><?php 
						
							settings_fields( 'offer_calc_global_options' );
							$offer_calc_options = get_option( 'offer_calc_options' );
							
						?><table class="form-table"> 
							<tbody>
								<tr>
									<th scope="row">
										<label for="offer_calc_global_enable_offer"><strong><?php _e( 'Enable Email Offer:', 'offercalc' );?></label>
									</th>
									<td>
										<input type="checkbox" id="offer_calc_global_enable_offer" name="offer_calc_options[global_enable_offer]" value="1" <?php if(!empty($offer_calc_options['global_enable_offer'])) { echo checked( $offer_calc_options['global_enable_offer'], '1' ); } ?>/><br />
										<span class="description"><?php _e( 'Enable / Disable Email Offer', 'offercalc' );?></span>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="offer_calc_global_email_addr"><strong><?php _e( 'Email Address:', 'offercalc' );?></label>
									</th>
									<td>
										<input type="text" id="offer_calc_global_email_addr" name="offer_calc_options[global_email_addr]" value="<?php if(!empty($offer_calc_options['global_email_addr'])) { echo $this->model->offer_calc_escape_attr($offer_calc_options['global_email_addr']); } ?>" class="large-text" /><br />
										<span class="description"><?php _e( 'Enter Email Address to Send Notification. Use , Seperated to send Multiple Emails.', 'offercalc' );?></span>
									</td>
								</tr>
								
								<tr>
									<th scope="row">
										<label for="offer_calc_global_offer_value"><strong><?php _e( 'Minimum Offer Submission Value:', 'offercalc' );?></label>
									</th>
									<td>
										<input type="text" id="offer_calc_global_offer_value" name="offer_calc_options[global_offer_value]" value="<?php if(!empty($offer_calc_options['global_offer_value'])) { echo $this->model->offer_calc_escape_attr($offer_calc_options['global_offer_value']); } ?>" class="large-text check_global_offer_value" /><br />
										<span class="description"><?php _e( 'Enter Minimum offer submission value to submit the offer.', 'offercalc' );?></span>
									</td>
								</tr>
								
								<tr>
									<td colspan="3" class="offer-calc-save-button">
										<input type="submit" class="button-primary margin_button" name="offer_calc_global_setting_save" id="offer_calc_global_setting_save" value="<?php echo $page_btn; ?>" />
									</td>
								</tr>
							</tbody>
						</table>
					
			
				</div><!-- .inside -->
	
			</div><!-- #product -->

		</div><!-- .meta-box-sortables ui-sortable -->

	</div><!-- .metabox-holder -->

</div><!-- #wps-product-general -->
	
<!-- end of the general settings -->

<!-- beginning of the email settings -->

<div id="offer-calc-settings" class="post-box-container">

	<div class="metabox-holder">	

		<div class="meta-box-sortables ui-sortable">

			<div id="product" class="postbox">	

				<div class="handlediv" title="<?php _e( 'Click to toggle', 'offercalc' ) ;?>"><br /></div>

					<!-- product box title -->

					<h3 class="hndle">
						<span style="vertical-align: top;"><?php _e( 'Email Settings', 'offercalc' );?></span>
					</h3>
					
					<div class="inside">
						
						<table class="form-table"> 
							<tbody>									
								<tr>
									<th scope="row">
										<label for="offer_calc_admin_email_addr"><strong><?php _e( 'Admin Recipient:', 'offercalc' );?></label>
									</th>
									<td>
										<input type="text" id="offer_calc_admin_email_addr" name="offer_calc_options[admin_email_addr]" value="<?php if(!empty($offer_calc_options['admin_email_addr'])) { echo $this->model->offer_calc_escape_attr($offer_calc_options['admin_email_addr']); } ?>" class="large-text" /><br />
										<span class="description"><?php _e( 'Enter Admin Email Address to Send Notification. Use , Seperated to send Multiple Emails.', 'offercalc' );?></span>
									</td>
								</tr>
								<tr><?php /*
								<tr>
									<th scope="row">
										<label for="offer_calc_sender_name"><strong><?php _e( 'Sender Name:', 'offercalc' );?></label>
									</th>
									<td>
										<input type="text" id="offer_calc_sender_name" name="offer_calc_options[sender_name]" value="<?php if(!empty($offer_calc_options['sender_name'])) { echo $this->model->offer_calc_escape_attr($offer_calc_options['sender_name']); } ?>" class="large-text" /><br />
										<span class="description">												
											<?php _e( 'Sender Name in Email. Use this syntax : Sendername '.$this->model->offer_calc_allow_tags('<sender@mail.com>'), 'offercalc' );?><br />
											<?php _e( "<code>{blogname}</code> - Use for displays sender name.", "offercalc" )?><br />
											<?php _e( "<code>{sendermail}</code> - Use for displays sender email.", "offercalc" )?>
										</span>
									</td>
								</tr>  */ ?>
								<tr>
									<th scope="row">
										<label for="offer_calc_options_admin_email_subject"><strong><?php _e( 'Admin Email Subject:', 'offercalc' );?></label>
									</th>
									<td>
										<input type="text" id="offer_calc_options_admin_email_subject" name="offer_calc_options[admin_email_subject]" value="<?php if(!empty($offer_calc_options['admin_email_subject'])) { echo $this->model->offer_calc_escape_attr($offer_calc_options['admin_email_subject']); } ?>" class="large-text" /><br />
										<span class="description"><?php _e( 'Enter Admin Email Subject.', 'offercalc' );?></span>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><strong><?php _e( 'Admin Email Body:', 'offercalc' );?></label>
									</th>
									<td><?php
										wp_editor( $offer_calc_options['admin_email_body'], 'offer_calc_options_admin_email_body', array( 'textarea_rows' => '7', 'media_buttons' => true, 'teeny' => true, 'textarea_name' => 'offer_calc_options[admin_email_body]') );?>
										<span class="description">
											<?php _e( 'Enter Message For Admin', 'offercalc' ); ?><br />
											<?php _e( "<code>{offer}</code> - Use for displays the offer calculation results to the Admin.", "offercalc" )?>
										</span>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="offer_calc_options_user_email_subject"><strong><?php _e( 'User Email Subject:', 'offercalc' );?></strong></label>
									</th>
									<td>
										<input type="text" id="offer_calc_options_user_email_subject" name="offer_calc_options[user_email_subject]" value="<?php if(!empty($offer_calc_options['user_email_subject'])) { echo $this->model->offer_calc_escape_attr($offer_calc_options['user_email_subject']); } ?>" class="large-text" /><br />
										<span class="description"><?php _e( 'Enter User Email Subject.', 'offercalc' );?></span>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><strong><?php _e( 'User Email Body:', 'offercalc' );?></strong></label>
									</th>
									<td><?php
										wp_editor( $offer_calc_options['user_email_body'], 'offer_calc_options_user_email_body', array( 'textarea_rows' => '7', 'media_buttons' => true, 'teeny' => true, 'textarea_name' => 'offer_calc_options[user_email_body]') );?>											
										<span class="description">
											<?php _e( 'Enter Message For User', 'offercalc' );?><br />
											<?php _e( "<code>{offer}</code> - Use for displays the offer calculation results to the User.", "offercalc" )?>
										</span>
									</td>
								</tr>								
								<tr>
									<td colspan="3" class="offer-calc-save-button">
										<input type="submit" class="button-primary margin_button" name="offer_calc_global_setting_save" id="offer_calc_global_setting_save" value="<?php echo $page_btn; ?>" />
									</td>
								</tr>
							</tbody>
						</table>

			
				</div><!-- .inside -->
	
			</div><!-- #product -->

		</div><!-- .meta-box-sortables ui-sortable -->

	</div><!-- .metabox-holder -->

</div><!-- #wps-product-general -->
	
<!-- end of the email settings -->

<?php do_action('offer_calc_rurl_setting'); ?>

<?php do_action('offer_calc_vat_setting'); ?>

</form>