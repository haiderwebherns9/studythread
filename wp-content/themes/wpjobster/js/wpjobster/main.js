var $ = jQuery.noConflict();

jQuery(document).ready(function($){

	// reCaptcha Responsive
	var width = $('.g-recaptcha-class').parent().width();
	var scale = width / 302;
	$('.g-recaptcha-class').css('transform', 'scale(' + scale + ')');
	$('.g-recaptcha-class').css('-webkit-transform', 'scale(' + scale + ')');
	$('.g-recaptcha-class').css('transform-origin', '0 0');
	$('.g-recaptcha-class').css('-webkit-transform-origin', '0 0');

	// Add Top Space For Admin Bar
	add_top_space($);

	$( window ).on( 'resize', function() {
		add_top_space($);
	});

	// Download attachments with token
	$( "a[href*='secure_download']" ).live( 'click', function( e ) {
		var url = ( $( this ).attr( 'href' ) );
		var auth_token = getURLParameter( url, 'auth_token' );
		var secure_download = getURLParameter( url, 'secure_download' );

		if ( secure_download ) {
			$.ajax({
				type: "POST",
				url: base_main.ajaxurl,
				data: {
					action: 'wpjobster_save_auth_token_transient',
					secure_download: secure_download,
					auth_token: auth_token,
				},
				success: function( msg ) {}
			});
		}
	});

	//YOUTUBE VALIDATION
	function ytVidId(url) {
		var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
		return (url.match(p)) ? RegExp.$1 : false;
	}

	$('#youtube_link').bind("change keyup input", function() {
		var url = $(this).val();
		if (ytVidId(url) === false) {
			$('#ytlInfo').html("<p class='lighter' style='color:#ff0000'>" + base_main.youtube_error + "</p>");
		}else{
			$('#ytlInfo').text("");
		}
		if(url == ''){
			$('#ytlInfo').text("");
		}
	});

	//PREVENT NEGATIVE NUMBER
	$('.decimal').keypress(function(event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

	//NOTIFICATIONS PAGE
	function check_notifications( id, cname ) {
		if( $( '.' + cname + ':checked' ).length == $( '.' + cname ).length ) {
			$( '#' + id ).prop( 'checked', true );
		}else{
			$( '#' + id ).prop( 'checked', false );
		}
	}

	//EMAIL
	check_notifications( 'all-email-notify', 'email_notifications' );

	$( '#all-email-notify' ).change( function() {
		var checkboxes = $( this ).closest( '.mail_notification' ).find( ':checkbox' );
		if( $( this ).is( ':checked' ) ) {
			checkboxes.prop( 'checked', true );
			jQuery( '.all-mail' ).text( base_main.uncheck_all_email );
		} else {
			checkboxes.prop( 'checked', false );
			jQuery( '.all-mail' ).text( base_main.check_all_email );
		}
	});

	$( '.mail_notification' ).change( function() {
		check_notifications( 'all-email-notify', 'email_notifications' );
	});

	//SMS
	check_notifications( 'all-sms-notify', 'sms_notifications' );

	$( '#all-sms-notify' ).change( function() {
		var checkboxes = $( this ).closest( '.sms_notification' ).find( ':checkbox' );
		if($( this ).is( ':checked' ) ) {
			checkboxes.prop( 'checked', true );
			jQuery( '.all-sms' ).text( base_main.uncheck_all_sms );
		} else {
			checkboxes.prop( 'checked', false );
			jQuery( '.all-sms' ).text( base_main.check_all_sms );
		}
	});

	$( '.sms_notification' ).change( function() {
		check_notifications( 'all-sms-notify', 'sms_notifications' );
	});

	//INTERNATIONAL PHONE
	var user_country = $(".cell_number").attr('data-country');
	if(user_country)
		user_country=user_country;
	else
		user_country='us';

	var default_country = $(".cell_number").attr('data-default-country');
	if(default_country)
		default_country=default_country;
	else
		default_country='us';

	$(".cell_number").intlTelInput({
		nationalMode: false,
		initialCountry: default_country,
		preferredCountries: [ user_country ],
	});
	if ($('body').hasClass("rtl")) {
		$(".cell_number").parent().addClass("iti-rtl");
	}

	//USER SEARCH
	if ( $('#searchInp').length > 0 ) {

		var user = $("#searchInp").val();
		var limitInp = base_main.posts_per_page;
		var initialLimitInp = base_main.posts_per_page;

		doneTyping();

		var typingTimer;
		var doneTypingInterval = 500;
		var $input = $('#searchInp, #locationInp, #radiusInp');

		$input.on('keyup keydown keypress blur change', function () {
			clearTimeout(typingTimer);
			typingTimer = setTimeout(doneTyping, doneTypingInterval);
		});

		$input.on('keydown', function () {
			clearTimeout(typingTimer);
			$('.wpj-search-user-load-more').show();
			limitInp = base_main.posts_per_page;
		});

		$(".wpj-search-user-load-more").click(function(){
			$(this).addClass('loading');
			initialLimitInp = Number(initialLimitInp) + Number(base_main.posts_per_page);
			limitInp = initialLimitInp;
			doneTyping();
		});

	}

	function doneTyping () {
		var searchInp   = $('#searchInp').val();
		var locationInp = $('#locationInp').val();
		var radiusInp   = $('#radiusInp').val();
		var longInp     = $('#user_long').val();
		var latInp      = $('#user_lat').val();

		$.post(base_main.ajaxurl, {
			action: 'search_users_ajax',
			searchInp: searchInp,
			limitInp: limitInp,
			locationInp: locationInp,
			radiusInp: radiusInp,
			longInp: longInp,
			latInp: latInp,
		},
		function(data) {
			obj = JSON.parse(data);

			$(".wpj-search-user-load-more").removeClass('loading');

			if(typeof(obj['usersInfo'][0]) != "undefined" && obj['usersInfo'][0] !== null) {
				if(obj['usersInfo'][0].queryresults < obj['usersInfo'][0].limit){
					$('.wpj-search-user-load-more').hide();
				}
			}else{
				$('.wpj-search-user-load-more').hide();
			}

			$('.searchUsername').removeClass('loading');
			$('#userInformations').empty();
			if (obj['usersInfo'].length) {
				$.each(obj['usersInfo'] , function( index, obj ) {
					if(obj.description){
						var desc = '<div class="userDescription">' + obj.description + '</div>';
					}else{
						var desc = '';
					}
					if(obj.current_user){
						var login_class = '';
					} else {
						var login_class = 'login-link';
					}
					if(obj.contact_url){
						var contact_button = '<a class="ui button ' + login_class + '" href="' + obj.contact_url + '">' + base_main.contact + '</a>';
					}else{
						var contact_button = '<span class="ui button disabled" href="#">' + base_main.contact + '</span>';
					}
					if(obj.company){
						var company = ' (' + obj.company + ')';
					}else{
						var company = '';
					}
					$('#userInformations').append(
						'<div class="ui segment">\
							<div class="ui three column stackable grid">\
								<div class="two wide column">\
									<div class="userImage">\
										<img width="100" height="100" border="0" class="user_img round-avatar" src="' + obj.avatar + '" />\
										<div class="userIcon">' + obj.level_icon + '</div>\
									</div>\
								</div>\
								<div class="twelve wide column">\
									<div class="userDetails">\
										<h2 class="userName"><a href="' + obj.addr + '">' + obj.username + company + '</a>\
											<div class="userBadges">' + obj.user_status + obj.country_flag + obj.badges_icons + obj.subscription_icon + '</div>\
										</h2>\
										' + desc + '\
										<div class="userExtraDetails">\
											<span class="userRating"><span class="labelDesc">' + base_main.rating + '</span> ' + obj.rating + '</span>\
											<span class="userCompletedOrders"><span class="labelDesc">' + base_main.completed_jobs + '</span> ' + obj.com_jb + '</span>\
											<span class="userCompany"><span class="labelDesc">' + base_main.registered + '</span>  ' + obj.joined + '</span>\
										</div>\
									</div>\
								</div>\
								<div class="two wide column">\
									<div class="vertical-center">' + contact_button + '</div>\
								</div>\
							</div>\
						</div>'
					);
				});
			}else {
				$('#userInformations').append(
					'<div class="no-results">' + base_main.nothing_found + '</div>');
			}

		});
	}

	function iOSversion() {
		if (/iP(hone|od|ad)/.test(navigator.platform)) {
			var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
			return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
		} else {
			return false;
		}
	}

	ver = iOSversion();

	if (ver[0] >= 11) {
		$("body").addClass("ios11");
	}

});

function wpj_enable_lazy_loading() {

	jQuery.fn.echoLazyLoadInit = function() {
		if ( jQuery.isFunction( echo.init ) ) {
			echo.init({
				offset: 100,
				throttle: 250,
				unload: false,
				callback: function (element, op) {
					setTimeout(function(){
						jQuery(element).addClass('echo-lazy-loaded');
					}, 2000);
				}
			});
		}
	}

	jQuery.fn.echoLazyLoadRender = function() {
		if ( jQuery.isFunction( echo.render ) ) {
			echo.render({
				offset: 100,
				throttle: 250,
				unload: false,
				callback: function (element, op) {
					setTimeout(function(){
						jQuery(element).addClass('echo-lazy-loaded');
					}, 2000);
				}
			});
		}
	}

	jQuery(document).ready(function(){
		jQuery.fn.echoLazyLoadInit();
	});

}

function wpj_big_search_top() {

	function suggest(inputString){

		if(inputString.length == 0) {
			jQuery('#suggestions').fadeOut();
		} else {
		jQuery('#big-search').addClass('load');
			jQuery.post("<?php echo get_admin_url(null, '/admin-ajax.php?action=autosuggest_it'); ?>", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					var stringa = data.charAt(data.length-1);
					if(stringa == '0') data = data.slice(0, -1);
					else data = data.slice(0, -2);
					jQuery('#suggestions').fadeIn();
					jQuery('#suggestionsList').html(data);
					jQuery('#big-search').removeClass('load');
				}
			});
		}
	}

	function fill(thisValue) {
		jQuery('#big-search').val(thisValue);
		setTimeout("jQuery('#suggestions').fadeOut();", 600);
	}

	jQuery(document).ready(function(){
		jQuery(".expnd_col").click(function() {
			var rels = jQuery(this).attr('rel');
			jQuery("#term_submenu" + rels).toggle();
			return false;

		});


		//Auto load script

		jQuery.fn.isOnScreen = function(){

			var win = jQuery(window);

			var viewport = {
				top : win.scrollTop(),
				left : win.scrollLeft()
			};
			viewport.right = viewport.left + win.width();
			viewport.bottom = viewport.top + win.height();

			var bounds = this.offset();
			bounds.right = bounds.left + this.outerWidth();
			bounds.bottom = bounds.top + this.outerHeight();

			return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

		};

		if (jQuery('.auto-load').length) {
			jQuery(window).on('scroll', function() {
				if (jQuery('.auto-load').isOnScreen() === true) {
					if ((jQuery('.auto-load').css('display') != 'none') && (!jQuery('.auto-load').hasClass('loading'))){
						jQuery("div.auto-load").trigger("click");
					}
				}

			});
		}
	});
}

// PREVENT MULTIPLE CLICKS ON A LINK
function clickAndDisable(link) {
	link.onclick = function(event) {
		event.preventDefault();
	}
}

// GET URL PARAMETERS
var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : sParameterName[1];
		}
	}
};
function getURLParameter(url, name) {
	return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}

// Args allowed in description
function wpj_js_description_args_allowed(max_chr_description='', post_status='', rejected_description=''){
	var $ = jQuery;
	var wysihtml5ParserRules = {
		classes: "any",
		classes_blacklist: {
			"Apple-interchange-newline": 1,
			"MsoNormal": 1,
			"MsoPlainText": 1
		},
		tags: {
			strong: { rename_tag: "b" },
			b:      {},
			i:      {},
			em:     { rename_tag: "i" },
			u:      {},
			br:     {},
			p:      {},
			span:   {},
			ul:     {},
			ol:     {},
			li:     {},
			comment: { remove: 1 },
			style:   { remove: 1 },
		}
	};

	var editor = new wysihtml5.Editor('job_description', {
		toolbar: 'job_description_toolbar',
		autoLink: false,
		parserRules: wysihtml5ParserRules,
		stylesheets: [base_main.theme_path + '/css/wysiwyg.css']
	});

	editor.on("load", function() {
		var $iframe = $(this.composer.iframe);
		var $body = $(this.composer.element);
		var $html = $body.parent();

		$body.css({ 'min-height': '100px', 'overflow': 'hidden', 'height': 'auto' });
		$html.css({ 'height': 'auto', });

		var scrollHeightInit = $body[0].scrollHeight;
		var bodyHeightInit = $body.height();
		var heightInit = Math.min(scrollHeightInit, bodyHeightInit);
		$iframe.height(heightInit);

		$body.bind('keypress keyup keydown paste change focus blur', function(e) {
			var scrollHeight = $body[0].scrollHeight;
			var bodyHeight = $body.height();
			var height = Math.min(scrollHeight, bodyHeight);
			$iframe.height(height);
		});

		add_input_length_listener( editor, $( "#job_description" ).siblings( ".char-count" ), max_chr_description, "html" );

		if ( post_status == 'pending' && rejected_description == 1 ) {
			$('#job_description').parents(".input-block").eq(0).find('.hidden-tooltip').addClass("visible");
		}
	})
	.on("focus", function() {
		$('#job_description').parents(".input-block").eq(0).find('.hidden-tooltip').addClass("visible");
		$('#job_description').parents(".post-new-job-wrapper-x").eq(0).addClass('has-focus');
		$('#job_description').parents(".input-block").eq(0).find('iframe').addClass('focus');
	})
	.on("blur", function() {
		$('#job_description').parents(".input-block").eq(0).find('.hidden-tooltip').removeClass("visible");
		$('#job_description').parents(".post-new-job-wrapper-x").eq(0).removeClass('has-focus');
		$('#job_description').parents(".input-block").eq(0).find('iframe').removeClass('focus');
	})

	editor.observe( "load", function() {

	});
}

function wpj_js_safe_date_format( date ) {
	var date    = new Date(date),

	yr      = date.getFullYear(),
	month   = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
	day     = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate(),

	newDate = yr + '-' + ( month +1 ) + '-' + day;

	return newDate;
}

// WPJ Semantic UI Calendar

(function ( $ ) {
$.fn.wpjcalendar = function () {
	var today = new Date();

	var inp_val_ts = $(this).find("input").val();

	var inp_val_ts_js = inp_val_ts * 1000;
	var new_date = wpj_js_safe_date_format(inp_val_ts_js);
	if( inp_val_ts ){
		$(this).find("input").val(new_date);
	}

	var inp_id = $(this).find("input").attr('id');
	var inp_name = $("#" + inp_id).attr('name');
	$("#" + inp_id).attr('name', inp_name + '-old');

	var new_input = $( '<input type="hidden" name="'+inp_name+'" value="'+inp_val_ts+'" />' );
	new_input.insertAfter( $(this) );

	$(this).calendar({
		type: 'date',
		firstDayOfWeek : modals.starting_day,
		minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
		popupOptions: {
			position: 'top left',
			lastResort: 'bottom left'
		},
		text: {
			days: [modals.days.sun, modals.days.mon, modals.days.tue, modals.days.wed, modals.days.thu, modals.days.fri, modals.days.sat],
			months: [modals.months.jan, modals.months.feb, modals.months.mar, modals.months.apr, modals.months.may, modals.months.jun, modals.months.jul, modals.months.aug, modals.months.sep, modals.months.oct, modals.months.nov, modals.months.dec],
			monthsShort: [modals.monthsShort.jan, modals.monthsShort.feb, modals.monthsShort.mar, modals.monthsShort.apr, modals.monthsShort.may, modals.monthsShort.jun, modals.monthsShort.jul, modals.monthsShort.aug, modals.monthsShort.sep, modals.monthsShort.oct, modals.monthsShort.nov, modals.monthsShort.dec],
			today: modals.today,
			now: modals.now,
			am: modals.am,
			pm: modals.pm
		},
		monthFirst: false,
		onChange: function(date, text, mode) {
			var timestamp = '';
			if (date) {
				timestamp = Math.floor(date.getTime() / 1000);
			}
			$(this).find("input").attr('data-timestamp', timestamp);

			$(this).next('input[type=hidden]').attr('data-timestamp', timestamp);
			$(this).next('input[type=hidden]').val(timestamp);
		}
	});

	new_input.attr('data-timestamp', inp_val_ts);

	if ( inp_val_ts ) {
		new_input.val(inp_val_ts);
	}

	return this;
}
}( jQuery ));

function add_top_space($) {
	if ( $( "#wpadminbar:visible" ).length != 0 ) {
		$( '.background-top-menu' ).css( 'margin-top', '34px' );
		$( '.top-menu-responsive' ).css( 'margin-top', '34px' );
	} else {
		$( '.background-top-menu' ).css( 'margin-top', '0px' );
		$( '.top-menu-responsive' ).css( 'margin-top', '0px' );
	}
}
