//shortcode calculation script
jQuery('.offer_selector_shortcode').change(function() {			
	
	var offer_count = jQuery(this).val();						
	var offer_price = jQuery(this).parents('td').siblings( 'td.price-td' ).children('.price-span').text();
	var total_input = jQuery(this).parents('td').siblings( 'td.total-td' ).children('.offer-sum');
	var net_total_input = jQuery(this).parents('form.offer-form').children('div.total-offercalc-sum-div').children('span.total-offercalc-sum-span');
	
	var row_sum = parseFloat(offer_price) * parseFloat(offer_count);
	total_input.val(row_sum.toFixed(2));
	
	var net_total = 0;
	jQuery(this).parents('form.offer-form').find('.offer-sum').each(function() {				
		net_total = parseFloat(net_total) + parseFloat(jQuery(this).val());	
	});
	
	net_total_input.html(net_total.toFixed(2));
});