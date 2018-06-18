<div id="content-full-ov">
	<div class="ui basic notpadded segment">
		<?php wpj_taxonomy_request_title_filter(); ?>
	</div>

	<div class="new-left-sidebar">
		<?php if ( is_active_sidebar( 'category-top-widgets-area' ) ) { ?>
			<div class="white-cnt ui segment">
				<div id="category-top-widgets-area" class="primary-sidebar widget-area" role="complementary">
					<ul>
						<?php dynamic_sidebar( 'category-top-widgets-area' ); ?>
					</ul>
				</div>
			</div>
		<?php } ?>

		<div class="ui segment">
			<?php wpj_taxonomy_request_category_list_sidebar(); ?>
		</div>

		<div class="ui segment">
			<?php wpj_taxonomy_request_search_form_filter(); ?>
		</div>

		<div class="ui hidden divider"></div>

	</div>

	<div class="right_container">
		<?php wpj_taxonomy_request_posts(); ?>
		<div class="ui hidden divider"></div>
	</div>
</div>

<?php if ( is_active_sidebar( 'category-bottom-widgets-area' ) ) { ?>
	<div class="white-cnt smallpadding-cnt">
		<div id="category-bottom-widgets-area" class="primary-sidebar widget-area" role="complementary">
			<ul>
				<?php dynamic_sidebar( 'category-bottom-widgets-area' ); ?>
			</ul>
		</div>
	</div>
<?php } ?>
