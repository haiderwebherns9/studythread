<?php wpj_custom_css(); ?>

<style>
/* Custom Styles from Admin */
<?php echo stripslashes(get_option('wpjobster_custom_css_code')); ?>
</style>

<?php do_action('wpjobster_before_head_tag_open'); ?>

<?php if (get_option('wpjobster_enable_lazy_loading') == 'yes') { ?>
<style>
	.echo-lazy-load {
		background: #FFF url(<?php echo get_template_directory_uri()."/images/ajax.gif"; ?>) no-repeat center center;
	}
	.echo-lazy-loaded {
		background: #FFF;
	}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		wpj_enable_lazy_loading();
	});
</script>
<?php } ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		wpj_big_search_top();
	});
</script>
