jQuery(document).ready(function($){
	//FILTER FOR E-MAIL DEMO ACCOUNT
	if(base_main_admin.current_role == "demo_admin"){
		$('[data-colname="Email"]').html('demo@wpjobster.com');
		$('#admin_email').val('demo@wpjobster.com');
	}

	if ($.fn.accordion) {
		jQuery('.ui.accordion').accordion();
	}

	$('.acc-nav .item').on( "click", function(e) {
		e.preventDefault();

		$( '.acc-nav .item' ).removeClass('active');
		$( '.hidden-tab' ).removeClass('active');
		$( this ).addClass('active');
		$( '#' + $( this ).data('item') ).addClass('active');
	});

	//PAGE ASSIGNMENTS
	$('.wpj-page-assignments').on('change', function() {
		var id = 'span#'+$(this).attr('id')+' a';
		var addr = 'post.php?post='+$(this).val()+'&action=edit';
		$(id).attr('href', addr);
	});

	// LESS COMPILER
	function wpj_compile_less(file){
		var lessCode = '';
		var xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = function(){
			if( xmlhttp.status == 200 && xmlhttp.readyState == 4 ) {
				var options = {}
				lessCode = xmlhttp.responseText;
				less.render(lessCode, options, function (error, output) {
					if(!error) {
						jQuery(document).ready(function($){
							data = output.css;
							$.ajax({
								type: "POST",
								url: base_main_admin.ajax_url,
								data: {
									action: 'wpjobster_save_less_css_file',
									content: data,
								},
								success: function(msg) {
									$('.spinner').css('visibility','hidden');
									$('#save').val('Compilation Completed');
									$('.save-notify').html('Done.<br>You can close the windows now.');
								}
							});
						});
					}
				});
			}
		};

		xmlhttp.open("GET",file,true);
		xmlhttp.send();
	}

	function wpj_save_button_action(){
		$('#save').on( "click", function(e) {
			var style = '\
				<style>\
					.save-notify {\
						position: absolute;\
						top: 55px;\
						line-height: 15px;\
						text-align: left;\
						left: 12px;\
					}\
					#customize-info,\
					li.customize-section-description-container.section-meta {\
						margin-top: 50px;\
					}\
				</style>\
			';
			$('.spinner').css('visibility','visible');
			if ( $( ".save-notify" ).length ) {
				$('.save-notify').html('Please wait...<br />(up to 1 minute)');
			} else {
				$( '<span class="save-notify">Please wait...<br />(up to 1 minute)</span>' ).insertAfter('#save');
			}

			$( style ).insertAfter('#save');

			wpj_compile_less(base_main_admin.semantic_file_url);
		});
	}

	if ( window.location.href.indexOf("color_options") >= 0){
		wpj_save_button_action();
	}

	$( ".customize-pane-parent li" ).click(function() {
		if( $( '#sub-accordion-section-color_options' ).hasClass( 'open' ) || $( '#sub-accordion-section-typography_options' ).hasClass( 'open' ) ){
			wpj_save_button_action();
		}
	});
});
