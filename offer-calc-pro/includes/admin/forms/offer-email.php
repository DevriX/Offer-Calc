<?php ob_start();?>
<!--<style>
	table.widget_offercalc_table th {
	    background-color: #cccccc;
	    border-color: gray;
	    border-style: solid;
	    border-width: 1px;
	    padding: 10px;
	}
	.widget_offercalc_table img{
		border-radius: 3px;
	}		
</style>-->
<div id="offercalc_wrapper_myform" class="offercalc_wrapper">
	<div><h3 align="center"><?php echo $post->post_title;?></h3></div>
	<table id="widget_offercalc_table_myform" class="widget_offercalc_table" border="1px" style="width: 100%; border : 1px solid;">
	<tbody>
		<tr>
			<?php if(isset($offer_img) && !empty($offer_img)) 
				{ ?>
					<th><?php _e('Image', 'offercalc');?></th>
			<?php } ?>
			<th><?php _e('Service', 'offercalc');?></th>
			<th><?php _e('Price', 'offercalc');?></th>
			<th><?php _e('Count', 'offercalc');?></th>
			<th><?php _e('Total', 'offercalc');?></th>
		</tr>
		<?php
			$total = 0;
			 foreach( $offer_arr_final as $offer_key => $offer_value ){
			 	$total += $offer_value['sum'];
			 	
		?><tr>
	 		<?php if(isset($offer_img) && !empty($offer_img)) { 
	 			?><td align="center">
					<?php if(isset($offer_value['image']) && !empty($offer_value['image'])) { ?><img width="50px" height="50px" src="<?php echo $offer_value['image'];?>"> <?php } ?>
				</td><?php 
	 		}
	 		?><td align="center"><?php echo $offer_value['name'];?></td>
			<td align="center"><?php echo apply_filters('offer_calc_add_currency', $offer_value['price'], $_POST['offer_id']);?></td>
			<td align="center">
			<?php 
			if( is_array($offer_value['count']) ){
				$a = 0;							
				foreach( $offer_value['count'] as $k_c => $v_c){
					echo ( $a == 0 ) ? $offer_value['count_label'][$k_c].'-'.$v_c : ', '.$offer_value['count_label'][$k_c].'-'.$v_c;
					$a++;
				}
			}
			elseif(isset($offer_value['count_label']) && isset($offer_value['count'])){
				echo $offer_value['count_label'].'-'.$offer_value['count'];
			}
			else{
				echo $offer_value['count'];
			}
			 
			?></td>
			<td align="center"><?php echo apply_filters('offer_calc_add_currency', $offer_value['sum'], $_POST['offer_id']);?></td>
		</tr><?php 
	 	}
		?><tr>
			<td colspan="5" align="right">
			
				<?php
				//For VAT Display
				if (isset($offer_vat_enable) && !empty($offer_vat_enable)) {
															
					?><b style="float:right;margin-right:20px"><?php _e('Sub Total :', 'offercalc');?> <?php echo apply_filters('offer_calc_add_currency', $offer_vat_sub_total, $_POST['offer_id']); ?></b>
					<br /><br /><b style="float:right;margin-right:20px"><?php _e('VAT Amount :', 'offercalc');?> <?php echo apply_filters('offer_calc_add_currency', $offer_vat_amount, $_POST['offer_id']); ?></b><br /><br /><?php 
				} 
				?><b style="float:right;margin-right:20px"><?php _e('Total :', 'offercalc');?> <?php echo apply_filters('offer_calc_add_currency', $offer_total, $_POST['offer_id']);?></b>
				
				<?php
				//For coupon code discount display 
				if (isset($offer_coupon_code) && !empty($offer_coupon_code)) {
					
					$final_total = apply_filters( 'offer_calc_get_coupon_code_discount',  $offer_coupon_code, $offer_total) ;
					
					?><br /><br /><b style="float:right;margin-right:20px"><?php _e('Discount Price :', 'offercalc');?> <?php echo apply_filters('offer_calc_add_currency', $final_total['discountprice'], $_POST['offer_id']); ?></b>
					<br /><br /><b style="float:right;margin-right:20px"><?php _e('Final Total :', 'offercalc');?> <?php echo apply_filters('offer_calc_add_currency', $final_total['discounttotal'], $_POST['offer_id']); ?></b><?php 
				} 
			?></td>
		</tr>
	</tbody>
	</table><?php
	
	if (isset($offer_extra_note) && !empty($offer_extra_note)) {
		
		?><br /><div><b><?php 
			_e('Extra Note :', 'offercalc');?></b><br /><?php 
			echo $offer_extra_note;
		?></div><?php 
	}
		
?></div>
<?php $email_html = ob_get_clean();?>