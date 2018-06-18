<?php


function listing_buttons_jobs() { ?>
		<div class="grid-switch-absolute">
			<ul class="listing-job <?php echo wpj_get_cards_layout_class(); ?>">
				<li class="layout_jobs" data-value="grid"><i class="grid layout icon"></i></li>
				<li class="layout_jobs" data-value="list"><i class="list layout icon"></i></li>
			</ul>
		</div>
<?php }

add_action( 'wp_ajax_nopriv_fnc_layout_job', 'fnc_layout_job' );
add_action( 'wp_ajax_fnc_layout_job', 'fnc_layout_job' );
function fnc_layout_job() {

	if ( ! isset( $_SESSION ) ) { session_start(); }

	if ( isset( $_POST['layout'] ) && $_POST['layout'] == 'list' ) $_SESSION['wpj_jobs_layout'] = 'list';
	else $_SESSION['wpj_jobs_layout'] = 'grid';

	wp_die();

}
