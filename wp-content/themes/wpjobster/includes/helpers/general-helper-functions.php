<?php
/**
 * General php and wp helper functions, not necessarily jobster specific functionality
 */

if ( ! function_exists( 'is_ajax' ) ) {
	function is_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'get_light_ajax_url' ) ) {
	function get_light_ajax_url() {
		$url = get_template_directory_uri() . '/ajax-functions/light-ajax.php';
		return $url;
	}
}

if ( ! function_exists( 'wpj_show_code_errors' ) ) {
	add_action( 'init', 'wpj_show_code_errors' );
	function wpj_show_code_errors() {
		// add the following to wp-config.php to enable debug:
		// define('WPJ_DEBUG', true);

		if ( ( defined( 'WP_JOBSTER_DEBUG' ) && WP_JOBSTER_DEBUG == true ) || ( defined( 'WPJ_DEBUG' ) && WPJ_DEBUG == true ) ) {
			ini_set( 'display_errors',1 );
			ini_set( 'display_startup_errors',1 );
			error_reporting( -1 );
		} else {
			ini_set( 'display_errors',0 );
			ini_set( 'display_startup_errors',0 );
			error_reporting( 0 );
		}
	}
}

if ( ! function_exists( 'wpj_disable_updates' ) ) {
	add_filter( 'pre_site_transient_update_themes', 'wpj_disable_updates', 10, 1 );
	function wpj_disable_updates( $array ) {
		// add the following to wp-config.php to disable theme updates:
		// define('WPJ_DISABLE_UPDATES', true);

		if ( defined( 'WPJ_DISABLE_UPDATES' ) && WPJ_DISABLE_UPDATES == true ) {
			global $wp_version;
			return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
		} else {
			return $array;
		}
	}
}

function list_options($a,$sel="",$def=""){
	if(!$sel && $def){$sel=$def;}
	if(is_assoc($a)){$assoc=1;}
	foreach($a as $k=>$v){
		if(!$assoc){} ?>
			<option value="<?php echo $k ?>" <?php if($sel==$k){echo "selected='selected'";} ?>><?php echo $v ?></option>
	<?php }
}

function is_assoc($arr){
	return array_keys($arr) !== range(0, count($arr) - 1);
}

//-----------------------------------------
// Better print_r & var_dump for debugging
//-----------------------------------------

function pre_print_r($var) {
	echo '<pre style="text-align: left;">';
	print_r($var);
	echo '</pre>';
}

function pre_var_dump($var) {
	echo '<pre style="text-align: left;">';
	var_dump($var);
	echo '</pre>';
}

function wpjobster_debug_add_action() {
	$debug_tags = array();
	add_action( 'all', function ( $tag ) {
		global $debug_tags;
		if ( in_array( $tag, $debug_tags ) ) {
			return;
		}
		echo "<pre style='margin-left: 210px;';>" . $tag . "</pre>";
		$debug_tags[] = $tag;
	} );
}

function wpjobster_debug_add_filter() {
	$debug_tags = array();
	add_filter( 'all', function ( $tag ) {
		global $debug_tags;
		if ( in_array( $tag, $debug_tags ) ) {
			return;
		}
		echo "<pre style='margin-left: 210px;';>" . $tag . "</pre>";
		$debug_tags[] = $tag;
	} );
}

function wpjobster_debug_list_all_filters() {

	$search = '';

	global $wp_filter;
	$all_filters = array ();
	$h1  = '<h1>Current Filters: ' . $search . '</h1>';
	$out = '';
	$toc = '<ul>';
	foreach ( $wp_filter as $key => $val ) {
		if ( ! $search || ( $search && FALSE !== strpos( $key, $search ) ) ) {
			$all_filters[$key][] = var_export( $val, TRUE );
		}
	}
	foreach ( $all_filters as $name => $arr_vals ) {
		$out .= "<h2 id=$name>$name</h2><pre>" . implode( "\n\n", $arr_vals ) . '</pre>';
		$toc .= "<li><a href='#$name'>$name</a></li>";
	}
	print "$h1$toc</ul>$out";
}

//--------------------------------------
// Get Host
//--------------------------------------

function get_host() {
	$possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
	$sourceTransformations = array(
		"HTTP_X_FORWARDED_HOST" => function($value) {
			$elements = explode(',', $value);
			return trim(end($elements));
		}
	);
	$host = '';
	foreach ($possibleHostSources as $source)
	{
		if (!empty($host)) break;
		if (empty($_SERVER[$source])) continue;
		$host = $_SERVER[$source];
		if (array_key_exists($source, $sourceTransformations))
		{
			$host = $sourceTransformations[$source]($host);
		}
	}

	// Remove port number from host
	$host = preg_replace('/:\d+$/', '', $host);

	return trim($host);
}


//--------------------------------------
// Get Host Without "www."
//--------------------------------------

function get_host_no_www() {
	$host = get_host();
	if (substr($host, 0, 4) == 'www.') {
		$domain = substr($host, 4);
	} else {
		$domain = $host;
	}

	return trim($domain);
}

function wpjobster_array_move( &$a, $oldpos, $newpos ) {
	if ( $oldpos == $newpos ) {
		return;
	}
	array_splice(
		$a,                                         // the array
		max( $newpos, 0 ),                          // location
		0,                                          // do not remove any element
		array_splice( $a, max( $oldpos, 0 ), 1 )    // remove this and insert it to location
	);
}

function wpj_scan_folders( $path = '', $return = array() ) {
	$path = $path == ''? dirname( __FILE__ ) : $path;
	$lists = @scandir( $path );

	if ( ! empty( $lists ) ) {
		foreach ( $lists as $f ) {
			if ( is_dir( $path . DIRECTORY_SEPARATOR . $f ) && $f != "." && $f != ".." ) {
				if ( ! in_array( $path . DIRECTORY_SEPARATOR . $f, $return ) )
					$return[] = trailingslashit( $path . DIRECTORY_SEPARATOR . $f );

				wpj_scan_folders( $path . DIRECTORY_SEPARATOR . $f, $return);
			}
		}
	}

	return $return;
}

function wpj_bool_option( $option, $default = false ) {
	// returns true and false for options set as "yes" and "no"
	// returns false for empty()

	$get_option = get_option( $option, $default );
	if ( $get_option == 'no' || empty( $get_option ) ) {
		return false;
	}

	return true;
}

function wpj_encode_emoji( $string ) {

	if ( function_exists( 'wp_encode_emoji' ) && function_exists( 'mb_convert_encoding' ) ) {
		$string = wp_encode_emoji( $string );
	}

	return $string;
}

function wpj_maybe_encode_emoji( $string, $table = false, $column = false ) {
	global $wpdb;

	if ( $table && $column ) {
		$charset = $wpdb->get_col_charset( $table, $column );
	} else {
		$charset = $wpdb->charset;
	}

	if ( $charset != 'utf8mb4' ) {
		if ( function_exists( 'wp_encode_emoji' ) && function_exists( 'mb_convert_encoding' ) ) {
			$string = wp_encode_emoji( $string );
		}
	}
	return $string;
}

function get_midnight_date_timestamp($timestamp) {
	return strtotime(date("d-m-Y 00:00:00", $timestamp));
}

if ( ! function_exists( 'get_current_page_url' ) ) {
	function get_current_page_url() {
		global $wp;
		return add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );
	}
}

function assign_rand_value($num) {
	// accepts 1 - 36
	switch($num) {
		case "1"  : $rand_value = "a"; break;
		case "2"  : $rand_value = "b"; break;
		case "3"  : $rand_value = "c"; break;
		case "4"  : $rand_value = "d"; break;
		case "5"  : $rand_value = "e"; break;
		case "6"  : $rand_value = "f"; break;
		case "7"  : $rand_value = "g"; break;
		case "8"  : $rand_value = "h"; break;
		case "9"  : $rand_value = "i"; break;
		case "10" : $rand_value = "j"; break;
		case "11" : $rand_value = "k"; break;
		case "12" : $rand_value = "l"; break;
		case "13" : $rand_value = "m"; break;
		case "14" : $rand_value = "n"; break;
		case "15" : $rand_value = "o"; break;
		case "16" : $rand_value = "p"; break;
		case "17" : $rand_value = "q"; break;
		case "18" : $rand_value = "r"; break;
		case "19" : $rand_value = "s"; break;
		case "20" : $rand_value = "t"; break;
		case "21" : $rand_value = "u"; break;
		case "22" : $rand_value = "v"; break;
		case "23" : $rand_value = "w"; break;
		case "24" : $rand_value = "x"; break;
		case "25" : $rand_value = "y"; break;
		case "26" : $rand_value = "z"; break;
		case "27" : $rand_value = "0"; break;
		case "28" : $rand_value = "1"; break;
		case "29" : $rand_value = "2"; break;
		case "30" : $rand_value = "3"; break;
		case "31" : $rand_value = "4"; break;
		case "32" : $rand_value = "5"; break;
		case "33" : $rand_value = "6"; break;
		case "34" : $rand_value = "7"; break;
		case "35" : $rand_value = "8"; break;
		case "36" : $rand_value = "9"; break;
	}
	return $rand_value;
}

function get_rand_alphanumeric($length) {
	if ($length>0) {
		$rand_id="";
		for ($i=1; $i<=$length; $i++) {
			mt_srand((double)microtime() * 1000000);
			$num = mt_rand(1,36);
			$rand_id .= assign_rand_value($num);
		}
	}
	return $rand_id;
}

function get_rand_numbers($length) {
	if ($length>0) {
		$rand_id="";
		for($i=1; $i<=$length; $i++) {
			mt_srand((double)microtime() * 1000000);
			$num = mt_rand(27,36);
			$rand_id .= assign_rand_value($num);
		}
	}
	return $rand_id;
}

function get_rand_letters($length) {
	if ($length>0) {
		$rand_id="";
		for($i=1; $i<=$length; $i++) {
			mt_srand((double)microtime() * 1000000);
			$num = mt_rand(1,26);
			$rand_id .= assign_rand_value($num);
		}
	}
	return $rand_id;
}

function wpjobster_better_trim($text, $len = 150, $more = '...') {
	// wp_trim_words sucks because it counts the words
	// mb_strimwidth counts the characters but cuts words
	// wpjobster_better_trim counts the characters and doesn't cut words
	// when the text contains one word only, the word is cut if needed

	$parts = explode(' ', $text);
	$ic = count($parts);
	$tx = '';
	$txt = '';

	for ($i = 0; $i < $ic; $i++) {
		$tx = $txt;
		$txt .= $parts[$i].' ';
		if (mb_strlen($txt) >= $len) {
			break;
		}
	}

	$tx = trim($tx);
	$txt = trim($txt);

	if (mb_strlen($text) > $len) {
		if ($i == 0) {
			$txt = mb_strimwidth($txt, 0, $len, $more);
		} else {

			$txt = $tx . $more;
		}
	}

	return $txt;
}

function wpjobster_replace_stuff_for_me($find, $replace, $subject){
	$i = 0;
	foreach ($find as $item) {
		$replace_with = $replace[$i];
		$subject = str_replace($item, $replace_with, $subject);
		$i++;
	}

	return $subject;
}

if (!function_exists('wpjobster_prepare_seconds_to_words')) {

	function wpjobster_prepare_seconds_to_words($seconds)    {
		$res = wpjobster_seconds_to_words_new($seconds);

		if ($res == "Expired")            return __('Expired', 'wpjobster');

		if ($res[0] == 0)            return sprintf(__("%s hours, %s min, %s sec", 'wpjobster'), $res[1], $res[2], $res[3]);

		if ($res[0] == 1) {
			$plural = $res[1] > 1 ? __('days', 'wpjobster') :
			__('day', 'wpjobster');
			return sprintf(__("%s %s, %s hours, %s min", 'wpjobster'), $res[1], $plural, $res[2], $res[3]);
		}

	}

}

function wpjobster_seconds_to_words_new($seconds){

	if ($seconds < 0)        return 'Expired';
	/*** number of days ***/
	$days = (int) ($seconds / 86400);
	/*** if more than one day ***/
	$plural = $days > 1 ? 'days' :
	'day';
	/*** number of hours ***/
	$hours = (int) (($seconds - ($days * 86400)) / 3600);
	/*** number of mins ***/
	$mins = (int) (($seconds - $days * 86400 - $hours * 3600) / 60);
	/*** number of seconds ***/
	$secs = (int) ($seconds - ($days * 86400) - ($hours * 3600) - ($mins * 60));
	/*** return the string ***/

	if ($days == 0 || $days < 0) {
		$arr[0] = 0;
		$arr[1] = $hours;
		$arr[2] = $mins;
		$arr[3] = $secs;
		return $arr;
	} else {
		$arr[0] = 1;
		$arr[1] = $days;
		$arr[2] = $hours;
		$arr[3] = $mins;
		return $arr;
	}

}

function wpjobster_seconds_to_words_joined($seconds){

	if ($seconds < 0) return;

	$years = (int) ($seconds / (365 * 24 * 60 * 60));
	$months = (int) ($seconds / (30 * 24 * 60 * 60));
	$days = (int) ($seconds / (24 * 60 * 60));

	$years_word = _n("year", "years", $years, "wpjobster");
	$months_word = _n("month", "months", $months, "wpjobster");
	$days_word = _n("day", "days", $days, "wpjobster");

	if ($days <= 0) {
		return __("today", "wpjobster");
	}

	if ($months <= 0) {
		return $days.' '.$days_word;
	}

	if ($years <= 0) {
		return $months.' '.$months_word;
	}

	if (($months % 12) <= 0) {
		return $years.' '.$years_word;
	}

	return $years.' '.$years_word.', '.($months % 12).' '.$months_word;
}

function wpjobster_allow_me_ext($ext){
	global $allowed_files_in_conversation;
	foreach ($allowed_files_in_conversation as $r)
	if ($ext == $r) {
		return true;
	}

	return false;
}

function wpjobster_curPageURL_me(){
	$pageURL = 'http';

	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}

	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}

	return $pageURL;
}

function wpjobster_curPageURL(){
	$pageURL = 'http';

	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}

	$pageURL .= "://";

	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}

	return $pageURL;
}

function wpjobster_formats_mm($number, $cents = 1){
	// does nothing else than the regular function
	return wpjobster_formats($number, $cents);
}

function get_exchange_value($amount, $from, $to) {
	$from = strtoupper($from);
	$to   = strtoupper($to);
	$from = trim($from);
	$to   = trim($to);
	$json = get_option('exchange_rates');
	$exchangeRates = json_decode($json);

	$rate = $exchangeRates->rates->$from;

	if ($json = null) {
		exit('Currency exchange error. Please contact the administrators.');
	}

	elseif (!$rate) {
		return $amount;
	}

	elseif ($from == $to) {
		return $amount;
	}

	else {
		if ($from != 'USD') {
			$dollars = $amount / $rate;
		} else {
			$dollars = $amount;
		}

		if ($to != 'USD') {
			$return = round($exchangeRates->rates->$to * $dollars, 2);
		} else {
			$return = round($dollars, 2);
		}

		return $return;
	}

}

add_action( 'wp_ajax_wpj_show_exchange_values_for_js', 'wpj_show_exchange_values_for_js' );
add_action( 'wp_ajax_nopriv_wpj_show_exchange_values_for_js', 'wpj_show_exchange_values_for_js' );
function wpj_show_exchange_values_for_js(){
	$json = get_option('exchange_rates');
	echo $json;
	wp_die();
}

//----------------------------------------------------
// there are 4 functions used to format numbers
//----------------------------------------------------

/* ---------------------------------------------------------------------------

wpjobster_formats();           => both decimal and thousands separator from options
							   => foreign exchange

wpjobster_formats_classic();   => both decimal and thousands separator from options
							   => default currency

wpjobster_formats_special_exchange();   => for database, only decimal "." separator
										=> foreign exchange

wpjobster_formats_special();            => for database, only decimal "." separator
										=> default currency

--------------------------------------------------------------------------- */

function wpjobster_formats($number, $cents = 1, $currency = '') // cents: 0=never, 1=if needed, 2=always
	{

	if (isset($currency) && $currency != '') {
		$number = get_exchange_value($number, get_option('wpjobster_currency_1'), $currency);
	}

	elseif (isset($_GET['site_currency'])) {
		$number = get_exchange_value($number, get_option('wpjobster_currency_1'), $_GET['site_currency']);
	}

	elseif (isset($_COOKIE["site_currency"])) {
		$number = get_exchange_value($number, get_option('wpjobster_currency_1'), $_COOKIE["site_currency"]);
	}

	$dec_sep = get_option('wpjobster_decimal_sum_separator');

	if (empty($dec_sep))        $dec_sep = '.';
	$tho_sep = get_option('wpjobster_thousands_sum_separator');

	if (empty($tho_sep))        $tho_sep = ',';
	//dec,thou

	if (is_numeric($number)) {
		// a number

		if (!$number) {
			// zero
			$money = ($cents == 2 ? '0' . $dec_sep . '00' :
			'0');
			// output zero
		} else {
			// value

			if (floor($number) == $number) {
				// whole number
				$money = number_format($number, ($cents == 2 ? 2 :
				0), $dec_sep, $tho_sep);
				// format
			} else {
				// cents
				$money = number_format(round($number, 2), ($cents == 0 ? 0 :
				2), $dec_sep, $tho_sep);
				// format
			}

			// integer or decimal
		}

		// value
		return $money;
	}
	// numeric
}

// for display, both separators, no exchange currency
function wpjobster_formats_classic($number, $cents = 1) // cents: 0=never, 1=if needed, 2=always
	{
	$dec_sep = get_option('wpjobster_decimal_sum_separator');

	if (empty($dec_sep))        $dec_sep = '.';
	$tho_sep = get_option('wpjobster_thousands_sum_separator');

	if (empty($tho_sep))        $tho_sep = ',';
	//dec,thou

	if (is_numeric($number)) {
		// a number

		if (!$number) {
			// zero
			$money = ($cents == 2 ? '0' . $dec_sep . '00' :
			'0');
			// output zero
		} else {
			// value

			if (floor($number) == $number) {
				// whole number
				$money = number_format($number, ($cents == 2 ? 2 :
				0), $dec_sep, $tho_sep);
				// format
			} else {
				// cents
				$money = number_format(round($number, 2), ($cents == 0 ? 0 :
				2), $dec_sep, $tho_sep);
				// format
			}

			// integer or decimal
		}

		// value
		return $money;
	}
	// numeric
}

// for database, no thousands separator. eg: 100000.00 no exchange currency
function wpjobster_formats_special($number, $cents = 1) // cents: 0=never, 1=if needed, 2=always
	{
	$dec_sep = '.';
	$tho_sep = '';
	//dec,thou

	if (is_numeric($number)) {
		// a number

		if (!$number) {
			// zero
			$money = ($cents == 2 ? '0' . $dec_sep . '00' :
			'0');
			// output zero
		} else {
			// value

			if (floor($number) == $number) {
				// whole number
				$money = number_format($number, ($cents == 2 ? 2 :
				0), $dec_sep, '');
				// format
			} else {
				// cents
				$money = number_format(round($number, 2), ($cents == 0 ? 0 :
				2), $dec_sep, '');
				// format
			}

			// integer or decimal
		}

		// value
		return $money;
	}
	// numeric
}

// for database, no thousands separator. eg: 100000.00 + exchange foreign currency
function wpjobster_formats_special_exchange($number, $cents = 1, $currency = ''){ // cents: 0=never, 1=if needed, 2=always

	if (isset($currency) && $currency != '') {
		$number = get_exchange_value($number, get_option('wpjobster_currency_1'), $currency);
	}

	elseif (isset($_GET['site_currency'])) {
		$number = get_exchange_value($number, get_option('wpjobster_currency_1'), $_GET['site_currency']);
	}

	elseif (isset($_COOKIE["site_currency"])) {
		$number = get_exchange_value($number, get_option('wpjobster_currency_1'), $_COOKIE["site_currency"]);
	}

	$dec_sep = '.';
	$tho_sep = '';
	//dec,thou

	if (is_numeric($number)) { // a number
		if (!$number) { // zero
			$money = ($cents == 2 ? '0' . $dec_sep . '00' :
			'0'); // output zero
		} else { // value
			if (floor($number) == $number) { // whole number
				$money = number_format($number, ($cents == 2 ? 2 :
				0), $dec_sep, ''); // format
			} else { // cents
				$money = number_format(round($number, 2), ($cents == 0 ? 0 :
				2), $dec_sep, ''); // format
			} // integer or decimal
		} // value
		return $money;
	} // numeric
} // formatMoney

function wpjobster_parseHyperlinks($string){
	// Add <a> tags around all hyperlinks in $string
	return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "[link_removed]", $string);
}

// GET DATA WITH cURL
function wpjobster_get_cURL_data($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

// GET LAT AND LONG BY LOCATION ADDRESS
function get_lat_long_by_address( $location ){
	$address = $location; // Google HQ
	$prepAddr = str_replace(' ','+',$address);
	$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
	$output= json_decode($geocode);
	$latitude = isset( $output->results[0]->geometry->location->lat ) ? $output->results[0]->geometry->location->lat : '';
	$longitude = isset( $output->results[0]->geometry->location->lng ) ? $output->results[0]->geometry->location->lng : '';

	return array(
		'lat' => $latitude,
		'long' => $longitude
	);
}

add_action( 'wp_ajax_get_wpjobster_graph', 'wpjobster_get_graph_ajax' );
if(!function_exists('wpjobster_get_unix_timestamp')){
	function wpjobster_get_unix_timestamp($date){
		$date = new DateTime($date);
		return  $date->getTimestamp();
	}
}

function count_newline_as_one_char($str) {
	$str = stripslashes($str);
	$str = str_replace(array("<br>","<br />","<br/>"),'', $str);
	$str = str_replace(array("\r\n","\n","\r"),' ', $str);
	$str = html_entity_decode($str, ENT_QUOTES, "UTF-8");
	return $str;
}

function get_youtube_id( $youtube_url ) {
	$url = parse_url($youtube_url);

	if( $url['host'] !== 'youtube.com' &&
		$url['host'] !== 'www.youtube.com'&&
		$url['host'] !== 'youtu.be'&&
		$url['host'] !== 'www.youtu.be')
	return '';

	if( $url['host'] === 'youtube.com' || $url['host'] === 'www.youtube.com' ) :
		parse_str(parse_url($youtube_url, PHP_URL_QUERY), $query_string);
		return $query_string["v"];
	endif;

	$youtube_id = substr( $url['path'], 1 );
	if( strpos( $youtube_id, '/' ) )
		$youtube_id = substr( $youtube_id, 0, strpos( $youtube_id, '/' ) );

	return $youtube_id;
}

add_filter('oembed_result', 'iweb_modest_youtube_player', 10, 3);
function iweb_modest_youtube_player($html, $url, $args) {
	return str_replace('?feature=oembed', '?feature=oembed&modestbranding=0&showinfo=0&rel=0&iv_load_policy=3', $html);
}

function wpjobster_insert_widget_in_sidebar( $widget_id, $widget_data, $sidebar ) {
	// Retrieve sidebars, widgets and their instances
	$sidebars_widgets = get_option( 'sidebars_widgets', array() );
	$widget_instances = get_option( 'widget_' . $widget_id, array() );

	// Retrieve the key of the next widget instance
	$numeric_keys = array_filter( array_keys( $widget_instances ), 'is_int' );
	$next_key = $numeric_keys ? max( $numeric_keys ) + 1 : 2;

	// Add this widget to the sidebar
	if ( ! isset( $sidebars_widgets[ $sidebar ] ) ) {
		$sidebars_widgets[ $sidebar ] = array();
	}
	$sidebars_widgets[ $sidebar ][] = $widget_id . '-' . $next_key;

	// Add the new widget instance
	$widget_instances[ $next_key ] = $widget_data;

	// Store updated sidebars, widgets and their instances
	update_option( 'sidebars_widgets', $sidebars_widgets );
	update_option( 'widget_' . $widget_id, $widget_instances );
}

function wpjobster_get_menu_id_by_slug( $menu ){
	$menu_object = wp_get_nav_menu_object( $menu );
	return $menu_object->term_id;
}

function wpjobster_get_menu_id_by_location( $location ) {
	$locations = get_nav_menu_locations();
	if ( in_array( $location, $locations ) ) {
		$menu_id = $locations[ $location ];
		return $menu_id;
	} else {
		return false;
	}
}

// IN_ARRAY FOR MULTIDIMENSIONAL ARRAYS
function in_array_r($item , $array) {
	return preg_match('/"'.$item.'"/i' , json_encode($array));
}

function wpjobster_parameter_exist( $parameter, $has_value='yes' ){
	if( $has_value && $has_value != '' && $has_value != 'no' ){
		if( isset( $_GET[$parameter] ) && $_GET[$parameter] && $_GET[$parameter] != '' ){
			return 1;
		}else{
			return 0;
		}
	}else{
		if( isset( $_GET[$parameter] ) ){
			return 1;
		}else{
			return 0;
		}
	}
}

function wpjobster_is_json($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

// REDIRECT_TO AFTER LOGIN
function wpjobster_login_redirect( $redirect_to,$request='',$user=null ){
	if ( isset($_REQUEST['redirect_to'] ) ) {
		$redirect_to = $_REQUEST['redirect_to'];
	}
	return $redirect_to;
}
add_filter( 'login_redirect','wpjobster_login_redirect',999 );

function wpj_make_links_clickable( $text, $class='', $target='_blank' ){
	$content = preg_replace( '$(\s|^)(http?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" class="'.$class.'" target="'.$target.'">$2</a> ', $text." " );
	$content = preg_replace( '$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" class="'.$class.'" target="'.$target.'">$2</a> ', $content." " );
	$content = preg_replace( '$(\s|^)(www\.[a-z0-9_./?=&-]+)(?![^<>]*>)$i', '<a href="http://$2" class="'.$class.'" target="'.$target.'">$2</a> ', $content." " );

	return htmlspecialchars_decode( $content );
}

function wpjobster_crypto_rand_secure( $min, $max ) {
	$range = $max - $min;
	if ($range < 0) return $min; // not so random...
	$log = log($range, 2);
	$bytes = (int) ($log / 8) + 1; // length in bytes
	$bits = (int) $log + 1; // length in bits
	$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
	do {
		$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
		$rnd = $rnd & $filter; // discard irrelevant bits
	} while ($rnd >= $range);

	return $min + $rnd;
}

function wpjobster_get_token( $length=32 ){
	$token = "";
	$codeAlphabet  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet .= "0123456789";

	for($i=0;$i<$length;$i++){
		$token .= $codeAlphabet[wpjobster_crypto_rand_secure(0,strlen($codeAlphabet))];
	}

	return '&auth_token=' . $token;
}

function wpjobster_copy_directory( $src, $dst ) {
	$dir = opendir($src);

	@mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				wpjobster_copy_directory($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}

	closedir($dir);
}

function wpjobster_url_exist( $url = '' ) {
	$exists = true;
	$file_headers = @get_headers( $url );
	$InvalidHeaders = array( '404', '403', '500' );
	foreach( $InvalidHeaders as $HeaderVal ) {
		if( strstr( $file_headers[0], $HeaderVal ) ) {
			$exists = false;
			break;
		}
	}

	return $exists;
}
