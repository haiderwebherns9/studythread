<?php
echo wpj_sng_req_upd_custom_offer();

if(file_exists(get_template_directory()."/lib/my_account/pm_support_file.php")){
	require_once get_template_directory()."/lib/my_account/pm_support_file.php";
}

$wpjobster_adv_code_single_page_above_content = stripslashes(get_option('wpjobster_adv_code_single_page_above_content'));
$wpjobster_request_lets_meet = get_option('wpjobster_request_lets_meet');
$lets_meet = get_post_meta( get_the_ID(), 'request_lets_meet', true );
$wpjobster_request_date_display_condition = get_option('wpjobster_request_date_display_condition');
$wpjobster_request_location_display_map = get_option('wpjobster_request_location_display_map');

if(!empty($wpjobster_adv_code_single_page_above_content)){
	echo '<div class="full_width_a_div">';
		echo $wpjobster_adv_code_single_page_above_content;
	echo '</div>';
}

if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

	<div class="ui hidden divider"></div>

		<div class="white-cnt ui segment">

			<div class="single-req-title">
				<h1 class="heading-title"><?php the_title() ?></h1>
			</div>

			<div class="white-cnt overflow-visible">

				<?php echo wpj_sng_req_desc(); ?>

			</div>
		</div>

	<div class="ui hidden divider"></div>

<?php endwhile; endif; ?>
