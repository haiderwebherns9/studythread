<?php
// GENERAL FUNCTIONS
if(!function_exists('wpjobster_max_days_to_deliver')){
	function wpjobster_max_days_to_deliver($name, $selected = "", $include_empty_option = "", $ccc = "",$value=""){
		$ret = '<select name="' . $name . '" class="ui fluid selection dropdown ' . $ccc . '" id="' . $name . '">';
		if (!empty($include_empty_option)) {

			if ($include_empty_option == "1") $include_empty_option = "Select";
			$ret .= "<option value='' disabled selected hidden>" . $include_empty_option . "</option>";
		}


		if (empty($selected))            $selected = -1;
			for($i=1;$i<=get_option( 'wpjobster_request_max_delivery_days' );$i++){
				$days_plural = sprintf( _n( '%d day', '%d days', $i, 'wpjobster' ), $i );
				$ret .= '<option ' . ($selected == $i ? "selected='selected'" : " ") . ' value="' . $i . '">' . $days_plural . '</option>';
			}
		$ret .= '</select>';
		return $ret;
	}
}

// FUNCTIONS
include_once get_template_directory() . '/includes/request/request-post.php';
include_once get_template_directory() . '/includes/request/request-ajax.php';
include_once get_template_directory() . '/includes/request/request-status.php';
