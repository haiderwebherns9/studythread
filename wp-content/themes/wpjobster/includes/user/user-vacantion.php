<?php
if ( ! function_exists( 'get_vacation_reason' ) ) {
	function get_vacation_reason( $reason_id = false ) {

		$reasons = array(
			1 => __( "I'm overbooked", "wpjobster" ),
			2 => __( "I'll be back soon", "wpjobster" ),
			3 => __( "I'm on vacation", "wpjobster" ),
		);

		if ( $reason_id === false ) {
			return $reasons;
		}

		return isset( $reasons[$reason_id] ) ? $reasons[$reason_id] : '';
	}
}

if ( ! function_exists( 'get_user_vacation' ) ) {
	function get_user_vacation( $uid = false ) {
		if ( ! $uid ) {
			$current_user = wp_get_current_user();
			$uid = $current_user->ID;
		}

		if ( ! $uid ) {
			return false;
		}

		global $wpdb;

		$vacations = $wpdb->get_results(
			"
			SELECT *
			FROM {$wpdb->prefix}job_uservacation
			WHERE user_id = '$uid'
				AND vacation_mode = '1'
			ORDER BY id DESC
			"
		);

		if ( count($vacations) > 0 ) {
			$vacation = $vacations[0];
			$return = array(
				"reason" => get_vacation_reason( $vacation->away_reason ),
				"start" => $vacation->duration_start_ts,
				"end" => $vacation->duration_end_ts,
			);

			return $return;
		}

		return false;
	}
}

add_action( 'wp', 'wpjobster_setup_daily_vacation_check' );
if ( ! function_exists( 'wpjobster_setup_daily_vacation_check' ) ) {
	function wpjobster_setup_daily_vacation_check() {
		if ( ! wp_next_scheduled( 'wpjobster_daily_vacation_check' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'wpjobster_daily_vacation_check' );
		}
	}
}

add_action( 'wpjobster_daily_vacation_check', 'wpjobster_vacation_check' );
if ( ! function_exists( 'wpjobster_vacation_check' ) ) {
	function wpjobster_vacation_check(){
		global $wpdb;
		$today = time();
		$active_vacations = $wpdb->get_results(
			"
			SELECT *
			FROM {$wpdb->prefix}job_uservacation
			WHERE vacation_mode = '1'
				AND duration_end_ts <= {$today}
			"
		);

		if ( $active_vacations > 0 ) {
			foreach ( $active_vacations as $index => $vacation ) {
				$id = $vacation->id;

				$wpdb->query(
					"
					UPDATE {$wpdb->prefix}job_uservacation
					SET duration_end_actual_ts = duration_end_ts,
						duration_end_actual = duration_end,
						vacation_mode = '0'
					WHERE id = '$id'
					"
				);
			}
		}
	}
}

// get start date for last x months of active period, without vacations
if ( ! function_exists( 'get_start_date_for_active_period' ) ) {
	function get_start_date_for_active_period( $user_id, $months ) {
		global $wpdb;

		$vacations = $wpdb->get_results(
			"
			SELECT *
			FROM {$wpdb->prefix}job_uservacation
			WHERE user_id ='$user_id'
			"
		);

		$today = time();
		$start_date = strtotime( '- ' . $months . ' months', $today );
		$period_wanted = $today - $start_date; // in seconds

		$vacations_number = count( $vacations );
		if ( $vacations_number > 0 ) {
			// recalculate $start_date if we have at least one vacation

			$i = $vacations_number - 1; // keys start from 0
			$period_between = 0;
			$i_day = $today; // index/pointer day

			while ( ( $period_between < $period_wanted ) && ( $i >= 0 ) ) {
				// reverse cycle through vacations
				// stop when we get enough period between vacations
				// stop when we don't have more vacations

				$v_start = $vacations[$i]->duration_start_ts;
				$v_end = $vacations[$i]->duration_end_actual_ts;
				if ( ! $v_end ) {
					$v_end = $vacations[$i]->duration_end_ts;
				}

				if ( $i_day > $v_end ) {
					$period_between = $period_between + ($i_day - $v_end);
				}

				$i_day = $v_start; // how far we got with checking
				$i--;
			}

			$start_date = $i_day - $period_wanted + $period_between;
		}

		return $start_date;
	}
}
