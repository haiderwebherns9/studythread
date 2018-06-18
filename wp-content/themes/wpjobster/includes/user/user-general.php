<?php
function wpjobster_get_userid_from_username($user){
	$user = get_user_by('login', $user);

	if (empty($user->ID)) return false;
	return $user->ID;
}

add_filter('validate_username' , 'custom_validate_username', 10, 2);
function custom_validate_username($valid, $username ) {
	if (preg_match("/\\s/", $username) || preg_match("/@/", $username)) {
		return $valid = false;
	}
	return $valid;
}

if ( ! function_exists( 'get_current_user_role' ) ) {
	function get_current_user_role() {
		global $current_user;
		$user_roles = $current_user->roles;
		if ( isset( $user_roles[0] ) ) {
			return $user_roles[0];
		}
		return false;
	}
}

//UPDATE USER LATITUDE AND LONGITUDE
function update_user_lat_and_long(){
	global $current_user;
	$city = get_user_meta( $current_user->ID, 'city', true );
	$country = get_user_meta( $current_user->ID, 'country', true );
	$location = $city;
	if( $country && $country !='' ){
		$location .= ', ' . $country;
	}
	if( $location && $location !='' ){
		$user_latitude = get_user_meta( $current_user->ID, 'wpj_user_latitude', true );
		if( ! $user_latitude && $user_latitude == '' ){
			$loc = get_lat_long_by_address( $location );
			update_user_meta( $current_user->ID, 'wpj_user_latitude', $loc['lat'] );
		}
		$user_longitude = get_user_meta( $current_user->ID, 'wpj_user_longitude', true );
		if( ! $user_longitude && $user_longitude == '' ){
			$loc = get_lat_long_by_address( $location );
			update_user_meta( $current_user->ID, 'wpj_user_longitude', $loc['long'] );
		}
	}
}

// USER COMPANY
add_action( 'zm_ajax_login_register_below_email_field', 'wpj_user_company_field_on_register_page', 1 );
function wpj_user_company_field_on_register_page(){
	if (get_option('wpjobster_enable_user_company') == 'yes') {
		?><input type="text" name="user_company" id="user_company" placeHolder="<?php echo __( 'Company', 'wpjobster' ); ?>" style="margin-bottom:20px;" /><?php
	}
}

add_action( 'personal_options', 'wpj_user_company_on_backend_user_edit', 1 );
function wpj_user_company_on_backend_user_edit( $user ) {
	if (get_option('wpjobster_enable_user_company') == 'yes') {
		?>
			<table class="form-table">
				<tr>
					<th><label for="user_company"><?php _e("Company Name"); ?></label></th>
					<td>
						<input type="text" name="user_company" id="user_company" class="regular-text"
							value="<?php echo esc_attr( get_the_author_meta( 'user_company', $user->ID ) ); ?>" /><br />
						<span class="description"><?php _e("Please enter your company name."); ?></span>
					</td>
				</tr>
			</table>
		<?php
	}
}

add_action('zm_ajax_login_after_successfull_registration', 'save_user_company_frontend');
function save_user_company_frontend( $user_id ) {
	if (get_option('wpjobster_enable_user_company') == 'yes') {
		if( isset( $_POST['user_company'] ) ){
			update_user_meta( $user_id, 'user_company', $_POST['user_company'] );
		}
	}
}

add_action( 'personal_options_update', 'save_user_company_backend' );
add_action( 'edit_user_profile_update', 'save_user_company_backend' );
function save_user_company_backend( $user_id ) {
	if (get_option('wpjobster_enable_user_company') == 'yes') {
		$saved = false;
		if ( current_user_can( 'edit_user', $user_id ) ) {
			if( isset( $_POST['user_company'] ) ){
				update_user_meta( $user_id, 'user_company', $_POST['user_company'] );
			}
			$saved = true;
		}
		return true;
	}
}
