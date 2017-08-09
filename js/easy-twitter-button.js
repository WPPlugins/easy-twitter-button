jQuery(document).ready(function(){
	jQuery('.t1').click(function(){
		jQuery('.etb_buttons').fadeIn("slow");
		jQuery('.etb_tweet_text').hide();
		jQuery('.etb_language').hide();
		jQuery('.etb_tabs li').removeClass('active');
		jQuery('.t1').addClass('active');
	});
	jQuery('.t2').click(function(){
		jQuery('.etb_buttons').hide();
		jQuery('.etb_tweet_text').fadeIn("slow");
		jQuery('.etb_language').hide();
		jQuery('.etb_tabs li').removeClass('active');
		jQuery('.t2').addClass('active');
	});
	jQuery('.t3').click(function(){
		jQuery('.etb_buttons').hide();
		jQuery('.etb_tweet_text').hide();
		jQuery('.etb_language').fadeIn("slow");
		jQuery('.etb_tabs li').removeClass('active');
		jQuery('.t3').addClass('active');
	});

	jQuery('.etb_emailbutton').click(function(){
		jQuery('.etb_email_form').slideToggle();
		jQuery('.etb_email_buttons').slideToggle();
	});
	jQuery('.etb_closeemail').click(function(){
		jQuery('.etb_email_form').slideToggle();
		jQuery('.etb_email_buttons').slideToggle();
	});


	jQuery('.etb_v').click(function(){
		var lang = jQuery('#lang').val();
		jQuery('.etb_preview span').attr('class', 'etv' + lang);
		jQuery('.etb_displayhidden').val('etv');
//debug		jQuery('.etb_preview span').text('etv' + lang);
	});
	jQuery('#etb_horizontal').click(function(){
		var lang = jQuery('#lang').val();
		jQuery('.etb_preview span').attr('class', 'eth' + lang);
		jQuery('.etb_displayhidden').val('eth');
//debug		jQuery('.etb_preview span').text('eth' + lang);
	});
	jQuery('#etb_none').click(function(){
		var lang = jQuery('#lang').val();
		jQuery('.etb_preview span').attr('class', 'etn' + lang);
		jQuery('.etb_displayhidden').val('etn');
//debug		jQuery('.etb_preview span').text('etn' + lang);
	});
	jQuery('#lang').change(function(){
		var display = jQuery('.etb_displayhidden').val();
		var lang = jQuery('#lang').val();
		jQuery('.etb_preview span').attr('class', display + lang);
//debug		jQuery('.etb_preview span').text(display + lang);
	});
});