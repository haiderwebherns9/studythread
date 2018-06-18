<?php
$wpjobster_adv_code_cat_page_above_content = stripslashes(get_option('wpjobster_adv_code_cat_page_above_content'));
if(!empty($wpjobster_adv_code_cat_page_above_content)){ ?>
	<div class="full_width_a_div">
	<?php echo $wpjobster_adv_code_cat_page_above_content; ?>
	</div>
<?php } ?>
<div id="content-full-ov">
	<div class="ui basic notpadded segment">
		<?php wpj_taxonomy_title_filter(); ?>
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

		<?php if ( ! is_subcategory() ) { ?>

			<div class="ui segment">
				<?php wpj_taxonomy_category_list_sidebar(); ?>
			</div>

		<?php } ?>

		<div class="ui segment">
			<?php wpj_taxonomy_search_form_filter(); ?>
		</div>

		<div class="ui hidden divider"></div>

	</div>

	<div class="right_container">
		<div class="cf <?php if(is_subcategory()) echo ' subcategory_jobs '; else echo ' category_jobs '; ?>">
			<?php wpj_display_thumbs_taxonomy(); ?>
			<div class="ui hidden divider"></div>
		</div>
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
