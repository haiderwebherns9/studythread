<?php
add_action( 'wp_ajax_search_autocomplete_ajax', 'wpjobster_search_autocomplete_ajax' );
add_action( 'wp_ajax_nopriv_search_autocomplete_ajax', 'wpjobster_search_autocomplete_ajax' );
function wpjobster_search_autocomplete_ajax() {
	global $wpdb;

	$input = $_POST['input'];
	$input = strtolower( $input );
	$input = esc_sql( $input );

	$job_array = array();
	$request_array = array();
	$user_array = array();
	$company_array = array();

	if ( $input != '' && $input != ' ' ) {

		// search jobs
		$job_query = $wpdb->get_results(
			"
			SELECT ID, post_title, post_content
			FROM {$wpdb->prefix}posts p
			LEFT JOIN {$wpdb->prefix}postmeta AS wpm ON ( p.ID = wpm.post_ID AND wpm.meta_key='active' )
			WHERE ( (post_title LIKE '%" . $input . "%')
					OR (post_content LIKE '%" . $input . "%') )
				AND post_type = 'job'
				AND post_status = 'publish'
				AND wpm.meta_value = '1'
			ORDER BY post_title ASC
			LIMIT 6
			"
		);

		foreach ( $job_query as $job ) {
			preg_match( "/\b(\w*" . $input . "\w*)\b/", strtolower( $job->post_title ), $matches );
			preg_match( "/\b(\w*" . $input . "\w*)\b/", strtolower( $job->post_content ), $matches2 );
			if ( isset( $matches[0] ) && ! in_array( $matches[0], $job_array ) ) {
				$job_array[] = $matches[0];
				if ( count( $job_array ) >= 6 ) {
					break;
				}
			}
			if ( isset( $matches2[0] ) && ! in_array( $matches2[0], $job_array ) ) {
				$job_array[] = $matches2[0];
				if ( count( $job_array ) >= 6 ) {
					break;
				}
			}
		}

		// search requests
		$request_query = $wpdb->get_results(
			"
			SELECT ID, post_title, post_content
			FROM {$wpdb->prefix}posts wp_posts
			WHERE ( (post_title LIKE '%" . $input . "%')
					OR (post_content LIKE '%" . $input . "%') )
				AND post_type = 'request'
				AND post_status = 'publish'
			ORDER BY post_title ASC
			LIMIT 6
			"
		);

		foreach ( $request_query as $request ) {
			preg_match( "/\b(\w*" . $input . "\w*)\b/", strtolower( $request->post_title ), $matches );
			preg_match( "/\b(\w*" . $input . "\w*)\b/", strtolower( $request->post_content ), $matches2 );
			if ( isset( $matches[0] ) && ! in_array( $matches[0], $request_array ) ) {
				$request_array[] = $matches[0];
				if ( count( $request_array ) >= 6 ) {
					break;
				}
			}
			if ( isset( $matches2[0] ) && ! in_array( $matches2[0], $request_array ) ) {
				$request_array[] = $matches2[0];
				if ( count( $request_array ) >= 6 ) {
					break;
				}
			}
		}

		// search users
		$user_query = $wpdb->get_results(
			"
			SELECT *
			FROM {$wpdb->prefix}users wu, {$wpdb->prefix}usermeta wum
			WHERE wu.ID = wum.user_ID
				AND ( ( wum.meta_key='first_name'
						AND wum.meta_value LIKE '%" . $input . "%' )
					OR ( wum.meta_key='last_name'
						AND wum.meta_value LIKE '%" . $input . "%' )
					OR ( wum.meta_key='description'
						AND wum.meta_value LIKE '%" . $input . "%' )
					OR ( wum.meta_key='user_company'
						AND wum.meta_value LIKE '%" . $input . "%' )
					OR wu.user_login LIKE '%" . $input . "%' )
			GROUP BY user_login
			ORDER BY wum.meta_key='completed_sales' DESC
			LIMIT 3
			"
		);

		foreach ( $user_query as $user ) {
			$user_array[] = $user->user_nicename;

			// add company if enabled, ready to display
			if ( wpj_bool_option( 'wpjobster_enable_user_company' ) ) {
				$user_company = get_user_meta( $user->ID, 'user_company', true );
				if ( $user_company ) {
					$company_array[] = ' (' . $user_company . ')';
				} else {
					$company_array[] = '';
				}
			}

			if ( count( $user_array ) >= 3 ) {
				break;
			}
		}
	}

	// return

	$return = array();
	$return['jobs'] = $job_array;
	$return['requests'] = $request_array;
	$return['users'] = $user_array;
	$return['companies'] = $company_array;
	echo json_encode( $return );

	wp_die();
}

add_action('wp_ajax_autosuggest_it', 				'wpjobster_autosuggest_it');
add_action('wp_ajax_nopriv_autosuggest_it', 		'wpjobster_autosuggest_it');
function wpjobster_autosuggest_it(){
	include('classes/stem.php');
	include('classes/cleaner.php');
	global $wpdb;

	$string = $_POST['queryString'];
	$stemmer = new Stemmer;
	$stemmed_string = $stemmer->stem ( $string );

	$clean_string = new jSearchString();
	$stemmed_string = $clean_string->parseString ( $stemmed_string );

	$new_string = '';
	foreach ( array_unique ( split ( " ",$stemmed_string ) ) as $array => $value ){
		if(strlen($value) >= 1){
			$new_string .= ''.$value.' ';
		}
	}

	$new_string = htmlspecialchars($_POST['queryString']);

	if ( strlen ( $new_string ) > 0 ){

		$split_stemmed = split ( " ",$new_string );

		$sql = "SELECT DISTINCT COUNT(*) as occurences, ".$wpdb->prefix."posts.post_title, ".$wpdb->prefix."posts.ID FROM ".$wpdb->prefix."posts,
		".$wpdb->prefix."postmeta WHERE ".$wpdb->prefix."posts.post_status='publish' and
		".$wpdb->prefix."posts.post_type='job'

				AND ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id
				AND ".$wpdb->prefix."postmeta.meta_key = 'closed'
				AND ".$wpdb->prefix."postmeta.meta_value = '0'

		AND (";

		while ( list ( $key,$val ) = each ( $split_stemmed ) ){
			if( $val!='' && strlen ( $val ) > 0 ){
				$sql .= "(".$wpdb->prefix."posts.post_title LIKE '%".$val."%' OR ".$wpdb->prefix."posts.post_content LIKE '%".$val."%') OR";
			}
		}

		$sql=substr ( $sql,0, ( strlen ( $sql )-3 ) );//this will eat the last OR
		$sql .= ") GROUP BY ".$wpdb->prefix."posts.post_title ORDER BY occurences DESC LIMIT 10";

		$r = $wpdb->get_results($sql, ARRAY_A );

		if(count($r) > 0){
			foreach ( $r as $row ){
				echo '<ul id="sk_auto_suggest">';
						$prm = get_permalink($row['ID']);

						echo '<li onClick="window.location=\''.$prm.'\';">'.wpjobster_wrap_the_title($row['post_title'], $row['ID']).'</li>';

				echo '</ul>';

			}
		}else{
			echo '<ul>';
				echo '<li onClick="fill(\''.$new_string.'\');">'.__('No results found','wpjobster').'</li>';
			echo '</ul>';
		}
	}
}

//Display Search Form Top Menu
function wpjobster_display_top_search_form() { ?>

	<form method="get" action="<?php echo get_default_search(); ?>">

		<div class="ui input">
			<input type="text" id="big-search" name="term1" autocomplete="off" onkeyup="<?php /*suggest(this.value); */ ?>"   value="<?php if(!empty($term_search)) echo htmlspecialchars($term_search); ?>" placeholder="<?php _e('Search','wpjobster'); ?>" />
		</div>

		<div id="big-search-submit" class="new-search-icon"></div>

	</form>

<?php }

function wpjobster_display_reponsive_search() { ?>
	<form method="get" action="<?php echo get_permalink(get_option('wpjobster_advanced_search_id')); ?>">
		<div class="new-search-input">
			<input type="text" id="responsive-search" name="term1" autocomplete="off" onkeyup="<?php /*suggest(this.value); */ ?>"   value="<?php if(!empty($term_search)) echo htmlspecialchars($term_search); ?>" placeholder="<?php _e('Search','wpjobster'); ?>" />
		</div>
		<div class="new-search-icon">
		</div>
	</form>
<?php }
