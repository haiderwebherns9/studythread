jQuery(document).ready(function() {

	function wpj_add_hash( hash ) {
		if ( history.pushState ) {
			history.pushState( null, null, hash );
		} else {
			location.hash = hash;
		}
	}


	// select correct tab from hash
	var tab_id = window.location.hash.replace("#step", "#tab");
	if ( tab_id ) {
		$('.tabs-wrapper .navigator .tab-selector.active').removeClass('active');
		$(tab_id).addClass('active');
		$('.tabs-wrapper .tab-wrapper .tab.active').removeClass('active');
		var this_tab = $($(".tabs-wrapper .tab-wrapper .tab").get( $(tab_id).index() ) );
		this_tab.addClass("active");
	}

	// change tab when ckicking on tab header
	jQuery('.tabs-wrapper .navigator .tab-selector').on('click', function() {
		$('.tabs-wrapper .navigator .tab-selector.active').removeClass('active');
		$(this).addClass('active');
		$('.tabs-wrapper .tab-wrapper .tab.active').removeClass('active');
		var this_tab = $($('.tabs-wrapper .tab-wrapper .tab').get($(this).index()));
		this_tab.addClass('active');
		var hash = $(this).attr("id").replace("tab", "#step");
		console.log(hash);
		wpj_add_hash( hash );
	});

	// change tab when clicking next
	jQuery('.post-new-job-wrapper .tab-controls a.right').click(function(e){
		e.preventDefault();
		$('.tabs-wrapper .tab-wrapper .tab.active').removeClass('active');
		var curent_tab = $(this).parent().parent().index();
		$($('.tabs-wrapper .tab-wrapper .tab').get(curent_tab + 1)).addClass('active');
		$('.tabs-wrapper .navigator .tab-selector.active').removeClass('active');
		var this_tab = $($('.tabs-wrapper .navigator .tab-selector').get(curent_tab + 1));
		this_tab.addClass('active');
		var hash = this_tab.attr("id").replace("tab", "#step");
		wpj_add_hash( hash );
	});

	// change tab when clicking previous
	jQuery('.post-new-job-wrapper .tab-controls a.left').click(function(e){
		e.preventDefault();
		$('.tabs-wrapper .tab-wrapper .tab.active').removeClass('active');
		var curent_tab = $(this).parent().parent().index();
		$($('.tabs-wrapper .tab-wrapper .tab').get(curent_tab - 1)).addClass('active');
		$('.tabs-wrapper .navigator .tab-selector.active').removeClass('active');
		var this_tab = $($('.tabs-wrapper .navigator .tab-selector').get(curent_tab - 1));
		this_tab.addClass('active');
		var hash = this_tab.attr("id").replace("tab", "#step");
		wpj_add_hash( hash );
	});

	jQuery('#suggest_job_btn').click(function(e){
		e.preventDefault();

		var thebutton = jQuery(this);
		thebutton.prop('disabled', true);
		thebutton.addClass('loading');
		var newlogin = jQuery(this).parents('form').serialize();

		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: 'json',
			data: newlogin,
			success: function (data) {
				for(var id in data)
				{
					if(id=='success')
					{
						jQuery('.success-response').slideDown('slow');
						thebutton.prop('disabled', false);
						thebutton.removeClass('loading');
						jQuery('#request-error-show').hide();
						jQuery('#request-error-notify').hide();

						window.setTimeout(function(){
							window.location.href = my_script_vars.my_request_url;
						}, 1000);
					}
					else
					{
						jQuery('#request-error-show').show();
						jQuery('.request-error-show').html(data[id]);
						jQuery('#request-error-notify').show();
						jQuery('.request-error-notify').html(my_script_vars.scroll_up);
						thebutton.prop('disabled', false);
						thebutton.removeClass('loading');
					}
				}

			}
		});
		return false;
	});


	jQuery('.showmemorecategories li a').click(function(e)
	{
		e.preventDefault();
		jQuery('.show-me-later-on').slideToggle();
	});

	function move_cloud_left(el)
	{
		el.animate( {"left": "-=50px"}, 6000, "linear");
		setTimeout(function(){ move_cloud_right(el) },6000);
	}
	function move_cloud_right(el)
	{
		el.animate( {"left": "+=50px"}, 6000, "linear");
		setTimeout(function(){ move_cloud_left(el) },6000);

	}
	function rmove_cloud_left(el)
	{
		el.animate( {"right": "-=50px"}, 6000, "linear");
		setTimeout(function(){ rmove_cloud_right(el) },6000);
	}
	function rmove_cloud_right(el)
	{
		el.animate( {"right": "+=50px"}, 6000, "linear");
		setTimeout(function(){ rmove_cloud_left(el) },6000);

	}
	move_cloud_left(jQuery('.cloud1'));
	move_cloud_right(jQuery('.cloud4'));
	move_cloud_left(jQuery('.cloud6'));
	rmove_cloud_right(jQuery('.cloud2'));
	rmove_cloud_left(jQuery('.cloud3'));
	rmove_cloud_left(jQuery('.cloud5'));

	jQuery(".cancel_order").click(function (){
		var id = jQuery(this).attr('rel');
		jQuery("#cancel_order_div_id_" + id).toggle('slow');

		return false;
	});

	jQuery("#my_select_purchase").change(function(e) {
		jQuery('#big-search').attr('name','');

		vars=getUrlVars();
		var url = window.location.pathname+'?'+vars+'site_currency='+jQuery('select[id="my_select_purchase"]').val();

		var extraitems = "";
		$.each($(".extra-item"), function() {
			extraitems += '<input type="hidden" name="extra' + $(this).data("extranr") + '" value="' + $(this).data("extraamount") + '" />';

		});

		var mainitem = '<input type="hidden" name="main_value_inp" value="' + $(".payment-main-item").data("mainamount") + '" />';
		var form = $('<form action="' + url + '" method="post">' +
		  '<input type="hidden" name="purchaseformvalidation" value="ok" />' + mainitem + extraitems +
		  '</form>');
		$('body').append(form);
		form.submit();
	});

	jQuery("#my_select_purchase2").change(function(e) {
		jQuery('#big-search').attr('name','');

		vars=getUrlVars();
		var url = window.location.pathname+'?'+vars+'site_currency='+jQuery('select[id="my_select_purchase2"]').val();

		var extraitems = "";
		$.each($(".extra-item"), function() {
			extraitems += '<input type="hidden" name="extra' + $(this).data("extranr") + '" value="' + $(this).data("extraamount") + '" />';

		});

		var mainitem = '<input type="hidden" name="main_value_inp" value="' + $(".payment-main-item").data("mainamount") + '" />';
		var form = $('<form action="' + url + '" method="post">' +
		  '<input type="hidden" name="purchaseformvalidation" value="ok" />' + mainitem + extraitems +
		  '</form>');
		$('body').append(form);
		form.submit();

	});

	//currency switcher
	jQuery(".ui.dropdown.currency .item").click(function(e) {
		vars = getUrlVars();
		addressbar = window.location.href;
		if (addressbar.indexOf('?') > -1){
			window.location = window.location.pathname + '?' + vars + 'site_currency=' + jQuery(this).data("currencyval");
		}else{
			window.location = window.location.pathname + '?' + 'site_currency=' + jQuery(this).data("currencyval");
		}
	});

	jQuery(document).ready(function(){
		jQuery(".currency-list-mobile li").click(function(e) {
			vars = getUrlVars();
			addressbar = window.location.href;
			if (addressbar.indexOf('?') > -1){
				window.location = window.location.pathname + '?' + vars + 'site_currency=' + jQuery(this).data("currencyval");
			}else{
				window.location = window.location.pathname + '?' + 'site_currency=' + jQuery(this).data("currencyval");
			}
		});
	});


	jQuery('html').click(function() {
		jQuery("ul.currency-list").css("display","none");
	});

	jQuery('.selected-currency').click(function(event){
		event.preventDefault();
		if (jQuery("ul.currency-list").css("display") == "block") {
			jQuery("ul.currency-list").css("display","none");
			event.stopPropagation();
		}
		else {
			jQuery("ul.currency-list").css("display","block");
			event.stopPropagation();
		}
	});

	jQuery("ul.currency-list").css("display","none");
	jQuery('.selected-currency').addClass(jQuery('.currency-list .active a').attr('class'));
	jQuery('.selected-currency').html(jQuery('.currency-list .selected').html());

	jQuery("#my_select_header").change(function(e) {
		jQuery('#big-search').attr('name','');
		vars=getUrlVars();
		window.location=window.location.pathname+'?'+vars+'site_currency='+jQuery('select[id="my_select_header"]').val();
	});

	jQuery("#my_select_header2").change(function(e) {
		jQuery('#big-search').attr('name','');
		vars=getUrlVars();
		window.location=window.location.pathname+'?'+vars+'job_cat='+jQuery('select[name="job_cat"]').val();
	});

	jQuery('#big-search-submit').click(function(e){
		e.preventDefault();

		jQuery('#my_select_header').attr('name','');
		jQuery(this).parents('form').trigger('submit');
	});
});

function getUrlVars(){
	var vars = [], hash;
	var thisurl = window.location.href.split('#')[0];
	var hashes = thisurl.slice(window.location.href.indexOf('?') + 1).split('&');

	if(typeof hashes!=='undefined'){

		for(var i = 0; i < hashes.length; i++){
			hash = hashes[i].split('=');
			if(hash[0]=='site_currency'){
				toreplace='site_currency='+hash[1];
			}
		}

		if(typeof toreplace ==='undefined'){
			toreplace='';
		}

		hashes=hashes.join('&');
	}else{
		hashes='';
	}

	loc=hashes.replace(toreplace,'');
	loc=loc.replace(my_script_vars.homeUrl,'');
	loc=loc+'&';
	loc=loc.replace('&&','&');

	return loc;
}

