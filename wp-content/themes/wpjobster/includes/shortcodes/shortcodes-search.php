<?php
// Advanced Search Bar for the Slider [advanced-search-slider]
if (!function_exists('advanced_search_slider')) {
	function advanced_search_slider($atts) {
		$a = shortcode_atts( array(
			'search-style' => 'white'
		), $atts );
		$search_style = $a['search-style'];
		$search_style_class = ($search_style == 'black') ? "advanced-search-black" : "advanced-search-white";
		$keyword_placeholder    = __('Keyword', 'wpjobster');
		$invalid_placeholder    = __('Invalid Location','wpjobster');
		$location_placeholder   = __('Location', 'wpjobster');
		$search_placeholder     = __('Search', 'wpjobster');
		$search_page_url = get_default_search();
		$selected = WPJ_Form::get( 'job_cat', '' );
		$category_select = wpjobster_get_categories_name_select('job_cat', $selected, __("Categories",'wpjobster'), "ui dropdown adv-srh", "slug");

$search_html = <<<HTML
	<div class="advanced-search-section {$search_style_class}">
		<div class="wrapper">
			<form class="hd-advanced-search" method="get" action="{$search_page_url}">
				<div class="ui large action input dbw100">
					<div class="ui icon input wpj-search-autocomplete dbw100">
						<input type="text" class="dbw100" placeholder="{$keyword_placeholder}" id="term1" name="term1">
						<i class="icon"></i>
					</div>

					<div class="ui input dbw100">
						<input type="text" class="dbw100" placeholder="{$location_placeholder}" data-replaceplaceholder="{$invalid_placeholder}" id="location_input" value="" name="location">
						<input type="hidden" name="lat" id="lat" value="">
						<input type="hidden" name="long" id="long" value="">
					</div>

					{$category_select}

					<button class="ui right labeled dbw100 primary icon button">
						<i class="search icon"></i>
						{$search_placeholder}
					</button>
				</div>
			</form>
		</div>
	</div>

HTML;
		return $search_html;
	}
}
add_shortcode( 'advanced-search-slider', 'advanced_search_slider' );

// Advanced Search Bar [advanced-search]
if (!function_exists('wpj_advanced_search_shortcode')) {
	function wpj_advanced_search_shortcode($atts) {
		$a = shortcode_atts( array(
			'search-style' => 'white'
		), $atts );

		$search_style = $a['search-style'];
		$search_style_class = ($search_style == 'black') ? "advanced-search-black" : "advanced-search-white";

		$keyword_placeholder    = __('Keyword', 'wpjobster');
		$invalid_placeholder    = __('Invalid Location','wpjobster');
		$location_placeholder   = __('Location', 'wpjobster');
		$search_placeholder     = __('Search', 'wpjobster');

		$search_page_url = get_default_search();

		$selected = WPJ_Form::get( 'job_cat', '' );

		$category_select = wpjobster_get_categories_name_select('job_cat', $selected, __("Categories",'wpjobster'), "categories_select styledselect", "slug");

$search_html = <<<HTML
	<div class="advanced-search-section advanced-search-regular {$search_style_class}">
		<div class="cf">
			<div class="advanced-search-wrapper">
				<form class="hd-advanced-search" method="get" action="{$search_page_url}">
					<div class="text-block">
						<input type="text" placeholder="{$keyword_placeholder}" class="hd-big-search" id="term1" name="term1">
					</div>
					<div class="text-block">
						<input type="text" data-replaceplaceholder="{$invalid_placeholder}" placeholder="{$location_placeholder}" class="hd-big-search" id="location_input" value="" name="location">
						<input type="hidden" name="lat" id="lat" value="">
						<input type="hidden" name="long" id="long" value="">
					</div>
					<div class="select-block">
						{$category_select}
					</div>
					<input type="submit" value="{$search_placeholder}">
				</form>
			</div>
		</div>
	</div>
HTML;

		return $search_html;
	}
}
add_shortcode( 'advanced-search', 'wpj_advanced_search_shortcode' );
