<?php
// FUNCTIONS
include_once get_template_directory() . '/includes/my-account/functions-reviews.php';
include_once get_template_directory() . '/includes/my-account/functions-pm-support-file.php';
include_once get_template_directory() . '/includes/my-account/functions-my-favorites.php';
include_once get_template_directory() . '/includes/my-account/functions-my-account.php';
include_once get_template_directory() . '/includes/my-account/functions-personal-info.php';
include_once get_template_directory() . '/includes/my-account/functions-payments.php';
include_once get_template_directory() . '/includes/my-account/functions-private-messages.php';
include_once get_template_directory() . '/includes/my-account/functions-all-notifications.php';

// VIEWS
get_template_part('template-parts/my-account/html', 'shopping');
get_template_part('template-parts/my-account/html', 'sales');
get_template_part('template-parts/my-account/html', 'reviews');
get_template_part('template-parts/my-account/html', 'my-favorites');
get_template_part('template-parts/my-account/html', 'my-account');
get_template_part('template-parts/my-account/html', 'personal-info');
get_template_part('template-parts/my-account/html', 'payments');
get_template_part('template-parts/my-account/html', 'private-messages');
get_template_part('template-parts/my-account/html', 'all-notifications');
