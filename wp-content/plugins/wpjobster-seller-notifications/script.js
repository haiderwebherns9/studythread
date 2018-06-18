jQuery( document ).ready(function( $ ) {
	// Select all categories
	$('.ui.checkbox.select_all_user_categories').checkbox({
		onChecked() {
			const options = $('#user_category > option').toArray().map(
				(obj) => obj.value
			);
			$('#user_category').dropdown('set exactly', options);
		},
		onUnchecked() {
			$('#user_category').dropdown('clear');
		},
	});

	// Enable location
	$('.ui.toggle.checkbox.enable_location').checkbox({
		onChecked: function () {
			$( '.location-wrapper input' ).prop('disabled', false);
			$( '.lbl_location' ).text(sn.location_on);
		},
		onUnchecked: function () {
			$( '.location-wrapper input' ).prop('disabled', true);
			$( '.lbl_location' ).text(sn.location_off);
		}
	});

});
