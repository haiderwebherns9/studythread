<!DOCTYPE html>

<html>

<head>

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php
	wp_head();
	get_template_part('template-parts/header/header', 'head');
	?>

</head>

<body <?php body_class(wpj_header_body_class()); ?> >
	<?php get_template_part('template-parts/header/header', 'body'); ?>
