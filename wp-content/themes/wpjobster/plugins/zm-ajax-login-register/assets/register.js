function openRegisterModal(e){

	var $ = jQuery;

	e.preventDefault();

	var data = {
		action: 'load_template',
		template: 'register-form',
		referer: 'register_form',
		security:  $('#ajax-login-register-dialog').attr('data-security')
	};

	$('.ui.modal.register')
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
			$( '.content-register-form' ).html( data );
			$('.ui.modal.register').modal('refresh');

			var width = $('#zm_register').parent().width();
			var scale = width / 302;
			$('#zm_register').css('transform', 'scale(' + scale + ')');
			$('#zm_register').css('-webkit-transform', 'scale(' + scale + ')');
			$('#zm_register').css('transform-origin', '0 0');
			$('#zm_register').css('-webkit-transform-origin', '0 0');
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

	$(document).on('click', '.register-link', function(e){
		openRegisterModal(e);
	});


	/**
	 * Confirms that two input fields match
	 */
	$( document ).on('keyup', '.user_confirm_password', function(){
		$form = $(this).parents('form');

		if ( $(this).val() == '' ){
			$( '.register_button', $form ).attr('disabled',true);
			$( '.register_button', $form ).animate({ opacity: 0.5 });
		} else {
			$( '.register_button', $form ).removeAttr('disabled');
			$( '.register_button', $form ).animate({ opacity: 1 });
		}
	 });


	/**
	 * Our form is loaded via AJAX, so we need to attach our event to the document.
	 * When the form is submitted process the AJAX request.
	 */
	$( document ).on('submit', '.register_form', function( event ){
		event.preventDefault();

		var values = {};
		$.each($(".register_form").serializeArray(), function (i, field) {
			values[field.name] = field.value;
		});

		//Value Retrieval Function
		var getValue = function (valueName) {
			return values[valueName];
		};

		//Retrieve the Values
		var password = getValue("password");
		var confirm_password = getValue("confirm_password");

		passwords_match = zMAjaxLoginRegister.confirm_password('.user_confirm_password', password, confirm_password);

		if ( passwords_match.code == 'error' ){
			ajax_login_register_show_message( $(this), msg );
		} else {
				jQuery(".register_button.green").attr('disabled','disabled');
				jQuery(".register_button.green").val('');
				jQuery(".register_button.green").addClass('loading');

			$.ajax({
				data: "action=register_submit&" + $( this ).serialize(),
				dataType: 'json',
				type: "POST",
				url: _ajax_login_settings.ajaxurl,
				success: function( msg ) {
					if( msg.code == 'success_registration'){
						ajax_login_register_show_message( $(this), msg );
					}else{
						jQuery(".register_button.green").val('Register');
						jQuery(".register_button.green").removeClass('loading');
						jQuery(".register_button.green").removeAttr('disabled');
						ajax_login_register_show_message( $(this), msg );
					}
				}
			});
		}
	});

	$(document).on('click', '.already-registered-handle', function(event){
		openLoginModal(event);
	});

});
