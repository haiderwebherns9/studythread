<?php
//--------------------------------------
// Set default currency cookie
//--------------------------------------
add_action( 'init','default_currency_cookie' );
function default_currency_cookie(){
	global $wpjobster_currencies_array;

	if (!isset($_GET['site_currency']) && !isset($_COOKIE["site_currency"])) {

		$selected = $wpjobster_currencies_array[0];
		setcookie("site_currency", $selected, time() + 9993600, "/"); /* 1 hour */
	}

	elseif (isset($_GET['site_currency'])) {
		$selected = $_GET['site_currency'];
		setcookie("site_currency", $selected, time() + 9993600, "/"); /* 1 hour */
	}
}

//--------------------------------------
// Display Currency Select Main
//--------------------------------------

function display_currency_select_main() {
	$selected = get_cur(); ?>

	<div class="ui floating dropdown labeled icon basic button currency">
		<input type="hidden" name="currency">
		<i class="money icon"></i>
		<div href="#" class="default text"><?php echo $selected; ?></div>
		<div class="menu">
			<?php
			global $wpjobster_currencies_array;
			global $wpjobster_currencies_symbols_array;

			foreach ($wpjobster_currencies_array as $wpjobster_currency) { ?>
				<div class="item <?php if($selected == $wpjobster_currency) echo 'active selected'; ?>" data-currencyval="<?php echo $wpjobster_currency; ?>">
					<?php if ( $wpjobster_currencies_symbols_array[$wpjobster_currency] != $wpjobster_currency ) { ?>
					<span class="description">
						<?php echo $wpjobster_currencies_symbols_array[$wpjobster_currency]; ?>
					</span>
					<?php } ?>
					<?php echo $wpjobster_currency; ?>
				</div>
			<?php } ?>
		</div>
	</div>

	<?php
}

// Display Currency Select Mobile
//--------------------------------------

function display_currency_select_mobile() {

	global $wpjobster_currencies_array;
	if(count($wpjobster_currencies_array) > 1) {

	$selected = get_cur();
		?>
		<li class="nh-accordion-container">
			<a class="item nh-accordion-handler" href="#"><?php _e("Currency", "wpjobster"); ?></a>
			<div class="nh-accordion" style="display: none;">
				<ul class="reset currency-list-mobile" id="currency-selector-mobile-list">
				<?php
					global $wpjobster_currencies_array;
					global $wpjobster_currencies_symbols_array;

					foreach ($wpjobster_currencies_array as $wpjobster_currency) {
						?>
						<li data-currencyval="<?php echo $wpjobster_currency; ?>">
							<a class="item <?php if ( $selected == $wpjobster_currency ) { echo 'active'; } ?>">
								<?php echo $wpjobster_currency; ?>
								<?php if ( $wpjobster_currencies_symbols_array[$wpjobster_currency] != $wpjobster_currency ) { ?>
								<span class="description">
									<?php echo $wpjobster_currencies_symbols_array[$wpjobster_currency]; ?>
								</span>
								<?php } ?>
							</a>
						</li>
						<?php
					}
				?>
				</ul>
			</div>
		</li>
		<?php

	}

}

//--------------------------------------
// Display Currency Select Secondary
//--------------------------------------

function display_currency_select_secondary() {
	?>
	<form method="post" action="<?php echo get_permalink(get_option('wpjobster_advanced_search_id')); ?>">
		<div class="grey" style="position: relative; width: 70px;">
			<?php $selected = get_cur(); ?>

			<select id="my_select_purchase" class="ui dropdown purchase-job-currency" name="site_currency">

				<?php
				global $wpjobster_currencies_array;
				global $wpjobster_currencies_symbols_array;

				foreach ($wpjobster_currencies_array as $wpjobster_currency) {
					?>
					<option value="<?php echo $wpjobster_currency; ?>" <?php if($selected == $wpjobster_currency) echo 'selected'; ?>><?php echo $wpjobster_currency; ?></option>
					<?php
				}
				?>

			</select>
		</div>
	</form>
	<?php
}


//--------------------------------------
// Display Currency Select Tertiary
//--------------------------------------

function display_currency_select_tertiary() {
	?>
	<form method="post" action="<?php echo get_permalink(get_option('wpjobster_advanced_search_id')); ?>">
		<div class="grey" style="position: relative; width: 70px;">
			<?php $selected = get_cur(); ?>

			<select id="my_select_purchase2" class="my_select_purchase styledselect" name="site_currency">

				<?php
				global $wpjobster_currencies_array;
				global $wpjobster_currencies_symbols_array;

				foreach ($wpjobster_currencies_array as $wpjobster_currency) {
					?>
					<option value="<?php echo $wpjobster_currency; ?>" <?php if($selected == $wpjobster_currency) echo 'selected'; ?>><?php echo $wpjobster_currency; ?></option>
					<?php
				}
				?>

			</select>
		</div>
	</form>
	<?php
}

function wpjobster_get_currency(){
	// returns current currency

	global $wpjobster_currencies_array;
	$currencies = $wpjobster_currencies_array;

	if (isset($_GET['site_currency'])) {
		$currency_get = strtoupper($_GET['site_currency']);

		if (in_array($currency_get, $currencies)) {
			return $currency_get;
		}
	}

	elseif (isset($_COOKIE['site_currency'])) {
		$currency_cookie = strtoupper($_COOKIE["site_currency"]);

		if (in_array($currency_cookie, $currencies)) {
			return $currency_cookie;
		}
	}

	return $currencies[0];
}

function wpjobster_get_currency_classic() {
	$c = trim(get_option('wpjobster_currency_1'));
	return $c;
}

function wpjobster_get_currency_symbol($currency_iso) {
	global $wpjobster_currencies_array;
	global $wpjobster_currencies_symbols_array;

	if( $wpjobster_currencies_array ) {
		if (in_array($currency_iso, $wpjobster_currencies_array)) {
			return $wpjobster_currencies_symbols_array[$currency_iso];
		}
	}

	return $currency_iso;
}

add_action( 'init', 'wpjobster_currencies_symbols' );
function wpjobster_currencies_symbols(){
	global $wpjobster_currencies_array;
	$wpjobster_currencies_array = array();

	global $wpjobster_currencies_symbols_array;
	$wpjobster_currencies_symbols_array = array();

	$is_allowed_multi_currency = wpj_is_allowed( 'multi_currency' );

	for ($i = 1; $i <= 10; $i++) {
		$currency_x = 'wpjobster_currency_' . $i;
		$wpjobster_currency_x = trim(get_option($currency_x));

		if ( ! $is_allowed_multi_currency && $i > 1 ) {
			$wpjobster_currency_x = '';
		}

		if ($wpjobster_currency_x) {
			array_push($wpjobster_currencies_array, $wpjobster_currency_x);

			$currency_symbol_x = 'wpjobster_currency_symbol_' . $i;
			$wpjobster_currency_symbol_x = trim(get_option($currency_symbol_x));

			if ($wpjobster_currency_symbol_x) {
				$wpjobster_currencies_symbols_array[$wpjobster_currency_x] = $wpjobster_currency_symbol_x;
			} else {
				$wpjobster_currencies_symbols_array[$wpjobster_currency_x] = $wpjobster_currency_x;
			}
		}
	}

	if (isset($_COOKIE["site_currency"]) && !in_array($_COOKIE["site_currency"], $wpjobster_currencies_array)) {

		$selected = $wpjobster_currencies_array[0];
		$_COOKIE['site_currency'] = $wpjobster_currencies_array[0];

		setcookie("site_currency", $selected, time() + 9993600, "/"); /* 1 hour */
	}

	if (isset($_GET["site_currency"]) && !in_array($_GET["site_currency"], $wpjobster_currencies_array)) {

		$_GET['site_currency'] = $wpjobster_currencies_array[0];
	}
}

//--------------------------------------
// The real currency check, no need for others
//--------------------------------------
add_action('template_redirect', 'check_if_post_new_on_template');
function check_if_post_new_on_template() {
	global $wpjobster_currencies_array;
	$post_new_job_page = get_option( 'wpjobster_post_new_page_id' );
	if ( $post_new_job_page ) {
		if( wpjobster_is_json( $post_new_job_page ) == 1 ){
			$post_new_job_page = json_decode($post_new_job_page);
		}
		$post_new_job_page_id = $post_new_job_page;
	} else {
		$post_new_job_page_id = get_option( 'wpjobster_how_it_works_page_id', false );
	}
	if ( is_page( $post_new_job_page_id ) ) {
		if (isset($_GET['site_currency']) && $_GET['site_currency'] != $wpjobster_currencies_array[0]) {
			$_GET['site_currency'] = $wpjobster_currencies_array[0];
		}
		elseif (isset($_COOKIE['site_currency']) && $_COOKIE['site_currency'] != $wpjobster_currencies_array[0]) {
			$_COOKIE['site_currency'] = $wpjobster_currencies_array[0];

			setcookie("site_currency", $wpjobster_currencies_array[0], time() + 9993600, "/"); /* 1 hour */
		}
	}
}

function get_cur() {
	global $wpjobster_currencies_array;
	global $wpjobster_currencies_symbols_array;

	if (isset($_GET['site_currency'])) {
		$site_currency = $_GET['site_currency'];
	}
	elseif (isset($_COOKIE['site_currency'])) {
		$site_currency = $_COOKIE['site_currency'];
	}

	if (isset($site_currency) && in_array($site_currency, $wpjobster_currencies_array)) {
		$selected = $site_currency;
	}
	else {
		$selected = $wpjobster_currencies_array[0];
	}

	return $selected;
}
