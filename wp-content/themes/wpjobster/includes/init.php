<?php
/**
 * WPJobster Engine Room.
 * This is where all Theme Functions runs.
 *
 * @package wpjobster
 */

// Setup
include_once get_template_directory() . '/includes/functions/functions-setup.php';

// Plugins //
include_once get_template_directory() . '/plugins/plugins.php';

// Functions //
include_once get_template_directory() . '/includes/functions/functions-admin-menu.php';
include_once get_template_directory() . '/includes/functions/functions-category.php';
include_once get_template_directory() . '/includes/functions/functions-custom-css.php';
include_once get_template_directory() . '/includes/functions/functions-custom-login.php';
include_once get_template_directory() . '/includes/functions/functions-customizer.php';
include_once get_template_directory() . '/includes/functions/functions-currency.php';
include_once get_template_directory() . '/includes/functions/functions-footer.php';
include_once get_template_directory() . '/includes/functions/functions-gateways.php';
include_once get_template_directory() . '/includes/functions/functions-graph.php';
include_once get_template_directory() . '/includes/functions/functions-header.php';
include_once get_template_directory() . '/includes/functions/functions-helpers.php';
include_once get_template_directory() . '/includes/functions/functions-job.php';
include_once get_template_directory() . '/includes/functions/functions-language-country.php';
include_once get_template_directory() . '/includes/functions/functions-media.php';
include_once get_template_directory() . '/includes/functions/functions-menu-links.php';
include_once get_template_directory() . '/includes/functions/functions-metaboxes.php';
include_once get_template_directory() . '/includes/functions/functions-modals.php';
include_once get_template_directory() . '/includes/functions/functions-my-account.php';
include_once get_template_directory() . '/includes/functions/functions-notifications.php';
include_once get_template_directory() . '/includes/functions/functions-order.php';
include_once get_template_directory() . '/includes/functions/functions-payments.php';
include_once get_template_directory() . '/includes/functions/functions-pm.php';
include_once get_template_directory() . '/includes/functions/functions-posts.php';
include_once get_template_directory() . '/includes/functions/functions-posts-listings.php';
include_once get_template_directory() . '/includes/functions/functions-price.php';
include_once get_template_directory() . '/includes/functions/functions-request.php';
include_once get_template_directory() . '/includes/functions/functions-schedule.php';
include_once get_template_directory() . '/includes/functions/functions-search.php';
include_once get_template_directory() . '/includes/functions/functions-shortcodes.php';
include_once get_template_directory() . '/includes/functions/functions-sms-mail.php';
include_once get_template_directory() . '/includes/functions/functions-subscription.php';
include_once get_template_directory() . '/includes/functions/functions-total.php';
include_once get_template_directory() . '/includes/functions/functions-user.php';
include_once get_template_directory() . '/includes/functions/functions-wpjobster.php';

// Classes //
include_once get_template_directory() . '/classes/class-wpj-form.php';
include_once get_template_directory() . '/classes/class-wpj-requests.php';
include_once get_template_directory() . '/classes/class-wpj-posts.php';
include_once get_template_directory() . '/classes/class-wpj-queries.php';
include_once get_template_directory() . '/classes/ip2locationlite.class.php';

// First Run //
add_action( 'wp_loaded', 'wpj_first_run_include' );
function wpj_first_run_include(){
	include_once get_template_directory() . '/includes/first-run/first_run.php';
	include_once get_template_directory() . '/includes/first-run/first_run_democontent.php';
}

// Ajax //
include_once get_template_directory() . '/ajax-functions/light-ajax-functions.php';

// SMS //
include_once get_template_directory() . '/vendor/twilio/Twilio.php';
include_once get_template_directory() . '/vendor/cafetwentyfour/smssend.php';

// Libraries //
include_once get_template_directory() . '/lib/dropzone/dropzone.php';

// Custom Offer //
include_once get_template_directory() . '/lib/custom_offers/custom_offers.php';

// Admin //
include_once get_template_directory() . '/vendor/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
add_action( 'admin_init', array( 'PAnD', 'init' ) );
