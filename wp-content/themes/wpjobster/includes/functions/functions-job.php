<?php
// Functions //
include_once get_template_directory() . '/includes/job/job-abort-mutual-cancelation.php';
include_once get_template_directory() . '/includes/job/job-answer-mutual-cancellation.php';
include_once get_template_directory() . '/includes/job/job-cron.php';
include_once get_template_directory() . '/includes/job/job-edit.php';
include_once get_template_directory() . '/includes/job/job-activate-deactivate.php';
include_once get_template_directory() . '/includes/job/job-bookmark.php';
include_once get_template_directory() . '/includes/job/job-cleared.php';
include_once get_template_directory() . '/includes/job/job-count.php';
include_once get_template_directory() . '/includes/job/job-delete.php';
include_once get_template_directory() . '/includes/job/job-extra.php';
include_once get_template_directory() . '/includes/job/job-feature.php';
include_once get_template_directory() . '/includes/job/job-general.php';
include_once get_template_directory() . '/includes/job/job-image.php';
include_once get_template_directory() . '/includes/job/job-like.php';
include_once get_template_directory() . '/includes/job/job-mark-completed.php';
include_once get_template_directory() . '/includes/job/job-mark-delivered.php';
include_once get_template_directory() . '/includes/job/job-pay.php';
include_once get_template_directory() . '/includes/job/job-post-new.php';
include_once get_template_directory() . '/includes/job/job-ratings.php';
include_once get_template_directory() . '/includes/job/job-request-modification.php';
include_once get_template_directory() . '/includes/job/job-request-mutual-cancelation.php';
include_once get_template_directory() . '/includes/job/job-report.php';
include_once get_template_directory() . '/includes/job/job-sales.php';
include_once get_template_directory() . '/includes/job/job-shopping.php';
include_once get_template_directory() . '/includes/job/job-latest.php';

// Views //
get_template_part('template-parts/pages/job/page', 'new-job');
