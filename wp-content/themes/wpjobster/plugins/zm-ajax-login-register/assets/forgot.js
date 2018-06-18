function openForgotModal(e){

	var $ = jQuery;

	e.preventDefault();

	var data = {
		action: 'load_template',
		template: 'forgot-form',
		referer: 'forgot_form',
		security:  $('#ajax-login-register-forgot-dialog').attr('data-security')
	};

	$('.ui.modal.forgot')
		.modal( 'setting', 'transition', 'fly down' )
		.modal( 'show' )
		.modal('refresh');

	jQuery.ajax({
		type: 'post',
		url : base_main2.ajax_url,
		data: data,

		beforeSend: function() {
			$( '.login-input-wrappers' ).addClass( 'loading' );
		},

		success: function( data ) {
			$( '.content-forgot-form' ).html( data );
			$('.ui.modal.forgot').modal('refresh');
		},

		error: function( response ) {
			console.log( 'oops' );
		},

		complete: function() {
			$( '.login-input-wrappers' ).removeClass( 'loading' );
		}
	});
	return false;
}

jQuery( document ).ready(function( $ ){

	$(document).on('click', '.forgot-password-handle', function(e){
		openForgotModal(e);
	});

	/**
	 * Confirms that two input fields match
	 */
	$( document ).on('keyup', '.user_email', function(){
		$form = $(this).parents('form');

		if ( $(this).val() == '' ){
			$( '.forgot_button', $form ).attr('disabled',true);
			$( '.forgot_button', $form ).animate({ opacity: 0.5 });
		} else {
			$( '.forgot_button', $form ).removeAttr('disabled');
			$( '.forgot_button', $form ).animate({ opacity: 1 });
		}
	 });

	/**
	 * Our form is loaded via AJAX, so we need to attach our event to the document.
	 * When the form is submitted process the AJAX request.
	 */
	$( document ).on('submit', '.forgot_form', function( event ){
		event.preventDefault();
		var $this = $(this);
		$.ajax({
			data: "action=forgot_submit&" + $this.serialize(),
			type: "POST",
			url: _ajax_login_settings.ajaxurl,
			success: function( msg ){

				ajax_login_register_show_message( $this, msg );
			}
		});
	});

	$(document).on('click', '.already-registered-handle', function(event){
		openLoginModal(event);
	});

});
