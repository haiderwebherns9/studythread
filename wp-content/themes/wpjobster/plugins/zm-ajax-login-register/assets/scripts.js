var zMAjaxLoginRegister = {

	reload: function( my_obj ){
		if ( my_obj.hasClass('login_form') &&  typeof _ajax_login_settings.redirect.login !== 'undefined' ){
			location.href = _ajax_login_settings.redirect.login.url;
		} else if ( my_obj.hasClass('register_form') && typeof _ajax_login_settings.redirect.registration !== 'undefined' ){
			location.href = _ajax_login_settings.redirect.registration.url;
		} else if ( my_obj.hasClass('forgot_form') && typeof _ajax_login_settings.redirect.forgot !== 'undefined' ){
			location.href = _ajax_login_settings.redirect.forgot.url;
		} else {
			location.href = _ajax_login_settings.redirect;
		}
	},

	// Confirm passwords match
	confirm_password: function( my_obj, pass1='', pass2='' ){

		if( pass1 != null && pass2 != null ){
			value = pass2;
			match_value = pass1;
		}else{
			$obj = jQuery( my_obj );
			value = $obj.val().trim();

			if ( value == '' ) return;

			$form = $obj.parents('form');

			match_value = jQuery('.user_password', $form).val();
		}

		if ( value == match_value ) {
			msg = {
				"cssClass": "noon",
				"description": null,
				"code": "success"
			};
		} else {
			msg = {
				"cssClass": "error-container",
				"description": _ajax_login_settings.match_error,
				"code": "error"
			};
		}

		return msg;
	}

};


jQuery( document ).ready(function( $ ){

	window.ajax_login_register_show_message = function( form_obj, msg ) {

		if( msg.code == 'success') {
			jQuery('.ajax-login-register-status-container').hide();
		}
		if ( msg.code == 'error' ) {
			jQuery('.ajax-login-register-status-container').show();

			jQuery('.ajax-login-register-msg-target').addClass( msg.cssClass );
			jQuery('.ajax-login-register-msg-target').fadeIn().html( msg.description );
			return false;
		}
		if ( msg.code == 'success_login' || msg.code == 'success_registration' ){
			jQuery('.ajax-login-register-msg-target', form_obj).addClass( msg.cssClass );
			jQuery('.ajax-login-register-msg-target', form_obj).fadeIn().html( msg.description );
			if(msg.redirect != "" && typeof msg.redirect != "undefined"){ window.location = msg.redirect; return;}
			zMAjaxLoginRegister.reload( form_obj );

		} else if ( msg.code == 'success_reset' ){
			jQuery('.ajax-login-register-msg-target', form_obj).addClass( msg.cssClass );
			jQuery('.ajax-login-register-msg-target', form_obj).fadeIn().html( msg.description );
			jQuery('.ajax-login-register-status-container').show();

		} else if ( msg.description == '' ){
			zMAjaxLoginRegister.reload( form_obj );

		} else {
			if ( msg.cssClass == 'noon' ){
				jQuery('.ajax-login-register-status-container').hide();

			} else {
				jQuery('.ajax-login-register-status-container').show();

			}

			jQuery('.ajax-login-register-msg-target', form_obj).addClass( msg.cssClass );
			jQuery('.ajax-login-register-msg-target', form_obj).fadeIn().html( msg.description );
		}
	};

	/**
	 * Server side email validation.
	 */
	window.ajax_login_register_validate_email = function( myObj ){
		$this = myObj;

		if ( $.trim( $this.val() ) == '' ) return;

		$form = $this.parents('form');

		$.ajax({
			data: "action=validate_email&email=" + $this.val(),
			dataType: 'json',
			type: "POST",
			url: _ajax_login_settings.ajaxurl,
			success: function( msg ){
				ajax_login_register_show_message( $form, msg );
			}
		});
	}

	/**
	 * Validate email
	 */
	$( document ).on('blur', '.ajax-login-register-validate-email', function(){
		ajax_login_register_validate_email( $(this) );
	});

	/**
	 * Server side email exists validation.
	 */
	window.ajax_login_register_validate_email_exists = function( myObj ){
		$this = myObj;

		if ( $.trim( $this.val() ) == '' ) return;

		$form = $this.parents('form');

		$.ajax({
			data: "action=validate_email_exists&email=" + $this.val(),
			dataType: 'json',
			type: "POST",
			url: _ajax_login_settings.ajaxurl,
			success: function( msg ){
				ajax_login_register_show_message( $form, msg );
			}
		});
	}

	/**
	 * Validate email exists
	 */
	$( document ).on('blur', '.ajax-login-register-validate-email-exists', function(){
		ajax_login_register_validate_email_exists( $(this) );
	});

	/**
	 * Server side phone empty validation.
	 */
	window.ajax_login_register_validate_phone_number = function( myObj ){
		$this = myObj;
		$form = $this.parents('form');
		$.ajax({
				data: "action=validate_phone_number&cell_number=" + $this.val(),
				dataType: 'json',
				type: "POST",
				url: _ajax_login_settings.ajaxurl,
				success: function( msg ){
					ajax_login_register_show_message( $form, msg );
				}
		});
		if (typeof msg !== 'undefined') {
			if(msg.code=='error') {
				return false;
			}
		}
	}

	/**
	 * Server side phone validation.
	 */
	window.cell_number = function( myObj ){
		$this = myObj;

		if ( $.trim( $this.val() ) == '' ) return;

		$form = $this.parents('form');
		$.ajax({
			data: "action=validate_phone_exists&cell_number=" + $this.val(),
			dataType: 'json',
			type: "POST",
			url: _ajax_login_settings.ajaxurl,
			success: function( msg ){
				ajax_login_register_show_message( $form, msg );
			}
		});
	}

	/**
	 * Validate phone exists
	 */
	$( document ).on('blur', '.cell_number', function(){
		cell_number( $(this) );
	});

	/**
	 * Validate captcha
	 */
	window.ajax_login_register_validate_captcha = function( myObj ){
		$this = myObj;

		if ( $.trim( $this.val() ) == '' ) return;

		$form = $this.parents('form');

		$.ajax({
				data: "action=validate_reCaptcha&g-recaptcha-response=" + $this.val(),
				dataType: 'json',
				type: "POST",
				url: _ajax_login_settings.ajaxurl,
				success: function( msg ){
					ajax_login_register_show_message( $form, msg );
				}
		});
		if (typeof msg !== 'undefined') {
			if(msg.code=='error') {
				return false;
			}
		}
	}

	/**
	 * Check that username is valid
	 */
	window.ajax_login_register_validate_username = function( myObj ){
		$this = myObj;

		if ( $.trim( $this.val() ) == '' ) return;

		$form = $this.parents('form');

		$.ajax({
			data: "action=validate_username&login=" + $this.val(),
			dataType: 'json',
			type: "POST",
			url: _ajax_login_settings.ajaxurl,
			success: function( msg ){
				ajax_login_register_show_message( $form, msg );
			}
		});
		if (typeof msg !== 'undefined') {
			if(msg.code=='error') {
				return false;
			}
		}
	}

	/**
	 * Validate username
	 */
	$( document ).on('blur', '.user_login', function(){
		ajax_login_register_validate_username( $(this) );
	});

	$( document ).on('click', '.register_button', function(){
		ajax_login_register_validate_username( $("#login") );
		ajax_login_register_validate_captcha( $( "#captcha_response" ) );
		ajax_login_register_validate_phone_number( $("#cell_number") );
	});

});
