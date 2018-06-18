<?php
	$wpj_request = new WPJ_request();

	$using_perm = wpjobster_using_permalinks();
	if($using_perm) $privurl_m = get_permalink(get_option('wpjobster_my_account_priv_mess_page_id')). "?";
	else $privurl_m = get_bloginfo('url'). "/?page_id=". get_option('wpjobster_my_account_priv_mess_page_id'). "&";
?>


<div class="right_container">

	<?php do_shortcode('[show_request_list]'); ?>

	<div class="ui hidden divider"></div>

	<div class="tax-request-form">
		<?php do_shortcode('[show_request_form]'); ?>
	</div>

</div>


<div class="new-left-sidebar">
	<?php do_shortcode('[request-category-lists]');?>

	<?php if ( is_active_sidebar( 'category-bottom-widgets-area' ) ) : ?>
		<div class="white-cnt smallpadding-cnt">
			<div id="category-bottom-widgets-area" class="primary-sidebar widget-area" role="complementary">
				<ul>
				<?php dynamic_sidebar( 'category-bottom-widgets-area' ); ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>

	<div class="ui segment">
		<?php wpj_taxonomy_request_search_form_filter(); ?>
	</div>
</div>

<div class="ui hidden divider"></div>

