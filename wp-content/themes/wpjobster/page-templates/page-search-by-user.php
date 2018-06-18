<?php /* Template Name: Search By User Page */ ?>
<?php get_header(); ?>

<?php
if(isset($_GET['user'])){
	$username = $_GET['user'];
	$username = str_replace('-', ' ', $username);
}
if(isset($_GET['location'])){
	$location = $_GET['location'];
	$location = str_replace('-', ' ', $location);
}
if(isset($_GET['location_rad'])){
	$location_rad = $_GET['location_rad'];
	$location_rad = str_replace('-', ' ', $location_rad);
}
global $post;
$page_url = get_permalink( $post );
?>

<script>
jQuery(document).ready(function($){
	$('#searchInp, #locationInp, #radiusInp').on('keyup keydown keypress blur change', function() {
		var strName        = '';
		var strLocation    = '';
		var strLocationRad = '';
		var strLong        = '';
		var strLat         = '';
		var params         = '';

		if ( $('#searchInp').val().length !==0 )   {
			strName        = $('#searchInp').val();
			strName        = strName.replace(/\s+/g, '-').toLowerCase();
			params = params + '&user=' + strName;
		}
		if ( $('#locationInp').val().length !==0 ) {
			strLocation    = $('#locationInp').val();
			strLocation    = strLocation.replace(/\s+/g, '-').toLowerCase();
			params = params + '&location=' + strLocation;

			strLong        = $('#user_long').val();
			params = params + '&long=' + strLong;

			strLat         = $('#user_lat').val();
			params = params + '&lat=' + strLat;
		}
		if ( $('#radiusInp').val().length !==0 )   {
			strLocationRad = $('#radiusInp').val();
			strLocationRad = strLocationRad.replace(/\s+/g, '-').toLowerCase();
			params = params + '&location_rad=' + strLocationRad;
		}

		if(params != ''){
			params = '?' + params.substring(1);
		}

		$('.searchUsername').addClass('loading');
		var page_url = <?php echo json_encode($page_url); ?>;
		history.pushState( null, "page 2", page_url + params );
	});
});
</script>
<div id="content-full-ov">
	<div class="ui basic notpadded segment">
		<h1 class="ui header">
			<?php echo get_the_title(); ?>
		</h1>
	</div>

	<div class="searchUsername ui segment">
		<?php if( get_option( 'wpjobster_enable_user_company' ) == 'yes' ){
			$title_place_holder = __('Username, First Name, Last Name, Company or Description', 'wpjobster');
		} else {
			$title_place_holder = __('Username, First Name, Last Name or Description', 'wpjobster');
		} ?>
		<input value="<?php if(isset($username)){ echo $username; } ?>" style="width: 100%;" type="text" name="searchInp" id="searchInp" class="searchInp grey_input white lighter w100" placeHolder="<?php echo $title_place_holder; ?>" />
		<i class="autocomplete-icon notched circle loading icon" style="display: none"></i>

		<?php if ( get_option( 'wpjobster_enable_search_user_location' ) == 'yes' ) { ?>
		<div class="advanced-user-search">
			<input id="user_lat" type="hidden" name="lat" value="<?php echo isset( $_GET['lat'] ) ? $_GET['lat'] : ''; ?>">
			<input id="user_long" type="hidden" name="long" value="<?php echo isset($_GET['long']) ? $_GET['long'] : ''; ?>">
			<div class="bs-col-container cf">
				<div class="bs-col2">
					<input class="grey_input white lighter w100" value="<?php if(isset($location)){ echo ucfirst( $location ); } ?>" placeHolder="<?php echo __('City or Country','wpjobster'); ?>" type="text" id="locationInp" name="locationInp" size="40" />
				</div>

				<div class="bs-col2">
					<?php
					if (get_option('wpjobster_locations_unit') == 'miles') {
						$radius_placeholder = __("Radius (miles)", "wpjobster");
					} else {
						$radius_placeholder = __("Radius (kilometers)", "wpjobster");
					}
					?>
					<input value="<?php if(isset($location_rad)){ echo $location_rad; } ?>" class="grey_input white lighter w100" type="text" id="radiusInp" placeHolder="<?php echo $radius_placeholder; ?>" name="radiusInp" size="40"  />
				</div>
			</div>
		</div>
		<?php } ?>

	</div>

	<div id="userInformations">
		<div class="search-user-status white-cnt padding-cnt">
			<i class="search-user-icon big notched circle loading icon"></i>
		</div>
	</div>

	<div class="load-more-button wpj-search-user-load-more">
		<?php _e("Load More","wpjobster"); ?>
	</div>

	<div class="ui hidden divider"></div>

</div>
<?php get_footer(); ?>
