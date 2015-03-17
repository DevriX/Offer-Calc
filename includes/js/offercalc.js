//shortcode calculation script
jQuery('.offer_selector_shortcode').change(function() {			
	
	var offer_count = jQuery(this).val();
	var offer_cls	= jQuery(this).closest('.offer_calc_price');	
	var offer_price = offer_cls.find( '.offer_calc_currency .price-span' ).text();	
	var total_input = offer_cls.find( '.offer_calc_orderlist .offer-sum' );
	
	var net_total_input = jQuery(this).parents('form.offer-form').children('div.total-offercalc-sum-div').children('span.total-offercalc-sum-span');
	
	offer_price = parseFloat(offer_price.replace(/[^0-9-.]/g, ''));	
	
	var row_sum = parseFloat(offer_price) * parseFloat(offer_count);
	total_input.val(row_sum.toFixed(2));
	
	var net_total = 0;
	jQuery(this).parents('form.offer-form').find('.offer-sum').each(function() {				
		net_total = parseFloat(net_total) + parseFloat(jQuery(this).val());			
	});
	
	net_total_input.html(net_total.toFixed(2));
});