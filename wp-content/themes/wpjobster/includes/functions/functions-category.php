<?php
if (!function_exists('wpjobster_get_categories_slug')) {

	function wpjobster_get_categories_slug($taxo, $selected = "", $include_empty_option = "", $ccc = "")    {
		$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
		$terms = get_terms($taxo, $args);
		$ret = '<select name="' . $taxo . '_cat" class="' . $ccc . '" id="' . $ccc . '">';

		if (!empty($include_empty_option)) {

			if ($include_empty_option == "1")                $include_empty_option = "Select";
			$ret .= "<option value=''>" . $include_empty_option . "</option>";
		}


		if (empty($selected))            $selected = -1;
		foreach ($terms as $term) {
			$id = $term->slug;
			$ide = $term->term_id;
			$ret .= '<option ' . ($selected == $id ? "selected='selected'" :
			" ") . ' value="' . $id . '">' . $term->name . '</option>';
			$args = "orderby=name&order=ASC&hide_empty=0&parent=" . $ide;
			$sub_terms = get_terms($taxo, $args);
			foreach ($sub_terms as $sub_term) {
				$sub_id = $sub_term->slug;
				$ret .= '<option ' . ($selected == $sub_id ? "selected='selected'" :
				" ") . ' value="' . $sub_id . '">&nbsp; &nbsp;|&nbsp;  ' . $sub_term->name . '</option>';
				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=" . $sub_id;
				$sub_terms2 = get_terms($taxo, $args2);
				foreach ($sub_terms2 as $sub_term2) {
					$sub_id2 = $sub_term2->term_id;
					$ret .= '<option ' . ($selected == $sub_id2 ? "selected='selected'" :
					" ") . ' value="' . $sub_id2 . '">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
			' . $sub_term2->name . '</option>';
				}

			}

		}

		$ret .= '</select>';
		return $ret;
	}

}


if (!function_exists('wpjobster_get_categories_slug_2_top_header')) {

	function wpjobster_get_categories_slug_2_top_header($taxo, $selected = "", $include_empty_option = "", $ccc = "")    {
		$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
		$terms = get_terms($taxo, $args);
		$suidropdown = "ui search dropdown";
		$ret = '<select name="' . $taxo . '" class="' . $suidropdown . '" id="' . $ccc . '">';

		if (!empty($include_empty_option)) {

			if ($include_empty_option == "1")                $include_empty_option = "Select";
			$ret .= "<option value=''>" . $include_empty_option . "</option>";
		}


		if (empty($selected))            $selected = -1;
		foreach ($terms as $term) {
			$id = $term->slug;
			$ide = $term->term_id;
			$ret .= '<option ' . ($selected == $id ? "selected='selected'" :
			" ") . ' value="' . $id . '">' . $term->name . '</option>';
			$args = "orderby=name&order=ASC&hide_empty=0&parent=" . $ide;
			$sub_terms = get_terms($taxo, $args);
			foreach ($sub_terms as $sub_term) {
				$sub_id = $sub_term->slug;
				$ret .= '<option ' . ($selected == $sub_id ? "selected='selected'" :
				" ") . ' value="' . $sub_id . '">&nbsp; &nbsp;|&nbsp;  ' . $sub_term->name . '</option>';
				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=" . $sub_id;
				$sub_terms2 = get_terms($taxo, $args2);
				foreach ($sub_terms2 as $sub_term2) {
					$sub_id2 = $sub_term2->term_id;
					$ret .= '<option ' . ($selected == $sub_id2 ? "selected='selected'" :
					" ") . ' value="' . $sub_id2 . '">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
			' . $sub_term2->name . '</option>';
				}
			}
		}
		$ret .= '</select>';
		return $ret;
	}

}

if (!function_exists('wpjobster_get_categories_name_select')) {
	function wpjobster_get_categories_name_select($taxo, $selected = "", $include_empty_option = "", $ccc = "",$value="")    {
		$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
		$terms = get_terms($taxo, $args);
		$ret = '<select name="' . $taxo . '" class="ui selection fluid dropdown ' . $ccc . '" id="' . $ccc . '">';
		if (!empty($include_empty_option)) {
			if ($include_empty_option == "1")                $include_empty_option = "Select";
			$ret .= "<option value=''>" . $include_empty_option . "</option>";
		}
		if (empty($selected))            $selected = -1;
		foreach ($terms as $term) {
			if($value=='slug'){
				$id = $term->slug;
			}else{
				$id = $term->name;
			}
			$ide = $term->term_id;
			$ret .= '<option ' . ($selected == $id ? "selected='selected'" :
			" ") . ' value="' . $id . '">' . $term->name . '</option>';
			$args = "orderby=name&order=ASC&hide_empty=0&parent=" . $ide;
			$sub_terms = get_terms($taxo, $args);
			foreach ($sub_terms as $sub_term) {
				if($value=='slug'){
					$sub_id = $sub_term->slug;
				}else{
					$sub_id = $sub_term->name;
				}
				$ret .= '<option ' . ($selected == $sub_id ? "selected='selected'" :
				" ") . ' value="' . $sub_id . '">&nbsp; &nbsp;|&nbsp;  ' . $sub_term->name . '</option>';
				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=" . $sub_id;
				$sub_terms2 = get_terms($taxo, $args2);
				foreach ($sub_terms2 as $sub_term2) {
					$sub_id2 = $sub_term2->term_id;
					$ret .= '<option ' . ($selected == $sub_id2 ? "selected='selected'" :
					" ") . ' value="' . $sub_id2 . '">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
			' . $sub_term2->name . '</option>';
				}
			}
		}
		$ret .= '</select>';
		return $ret;
	}
}

if (!function_exists('wpjobster_get_categories')) {

	function wpjobster_get_categories($taxo, $selected = "", $include_empty_option = "", $ccc = "")    {
		$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
		$terms = get_terms($taxo, $args);
		$ret = '<select name="' . $taxo . '_cat" class="' . $ccc . '" id="' . $ccc . '">';

		if (!empty($include_empty_option))            $ret .= "<option value=''>" . $include_empty_option . "</option>";

		if (empty($selected))            $selected = -1;
		foreach ($terms as $term) {
			$id = $term->term_id;
			$ret .= '<option ' . ($selected == $id ? "selected='selected'" :
			" ") . ' value="' . $id . '">' . $term->name . '</option>';
			$args = "orderby=name&order=ASC&hide_empty=0&parent=" . $id;
			$sub_terms = get_terms($taxo, $args);
			foreach ($sub_terms as $sub_term) {
				$sub_id = $sub_term->term_id;
				$ret .= '<option ' . ($selected == $sub_id ? "selected='selected'" :
				" ") . ' value="' . $sub_id . '">&nbsp; &nbsp;|&nbsp;  ' . $sub_term->name . '</option>';
				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=" . $sub_id;
				$sub_terms2 = get_terms($taxo, $args2);
				foreach ($sub_terms2 as $sub_term2) {
					$sub_id2 = $sub_term2->term_id;
					$ret .= '<option ' . ($selected == $sub_id2 ? "selected='selected'" :
					" ") . ' value="' . $sub_id2 . '">&nbsp; &nbsp; &nbsp; &nbsp;|&nbsp;
			 ' . $sub_term2->name . '</option>';
				}

			}

		}

		$ret .= '</select>';
		return $ret;
	}

}

if (!function_exists('wpjobster_get_categories_clck')) {

	function wpjobster_get_categories_clck( $name, $selected = "", $include_empty_option = "", $ccc = "", $xx = "" ) {
		$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
		$terms = get_terms('job_cat', $args);
		$ret = '<select name="' . $name . '" class="ui dropdown new-post-category ' . $ccc . '" id="' . $name . '" ' . $xx . '>';

		if ( ! empty( $include_empty_option ) ) {
			$ret .= "<option value=''>" . $include_empty_option . "</option>";
		}

		if (empty($selected))            $selected = -1;
		foreach ($terms as $term) {
			$id = $term->term_id;
			$ret .= '<option ' . ($selected == $id ? "selected='selected'" :
			" ") . ' value="' . $id . '">' . $term->name . '</option>';
		}

		$ret .= '</select>';
		return $ret;
	}

}

if ( ! function_exists( 'wpjobster_get_subcategories_clck' ) ) {
	function wpjobster_get_subcategories_clck( $name, $selected_cat = "", $selected_subcat = "", $include_empty_option = "", $ccc = "", $xx = "" ) {

		if ( $selected_cat ) {
			$args2 = "orderby=name&order=ASC&hide_empty=0&parent=" . $selected_cat;
			$sub_terms2 = get_terms( 'job_cat', $args2 );

			$ret = '<select name="' . $name . '" class="ui dropdown new-post-category ' . $ccc . '" id="' . $name . '" ' . $xx . '>';

			if ( ! empty( $include_empty_option ) ) {
				$ret .= '<option value="" disabled selected hidden>' . $include_empty_option . '</option>';
			}

			foreach ( $sub_terms2 as $sub_term2 ) {
				$sub_id2 = $sub_term2->term_id;
				$ret .= '<option ' . ( $selected_subcat == $sub_id2 ? "selected='selected'" : " " ) . ' value="' . $sub_id2 . '">' . $sub_term2->name . '</option>';
			}

			$ret .= "</select>";
			return $ret;
		}

	}
}

function show_popular_terms( $taxonomy, $number = 10 ) {
	$terms = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => $number, 'hierarchical' => false ) );
	if( $terms ){ ?>
		<div class="ui segment">
			<div class="most-popular-terms">
			<h4><?php _e('Subject', 'wpjobster'); ?></h4>
			<?php foreach ( $terms as $term ) { ?>

				<a href="<?php echo get_term_link( $term->slug, $taxonomy ); ?>" title="<?php echo $term->name; ?>"><?php echo $term->name; ?></a>

				<?php
			} ?>
			</div>
		</div>
	<?php }
}

function wpjobster_get_custom_taxonomy_count($ptype, $pterm){
	global $wpdb;

	$s = "select * from " . $wpdb->prefix . "terms where slug='$pterm'";
	$r = $wpdb->get_results($s);
	$r = $r[0];
	$term_id = $r->term_id;

	$s = "select * from " . $wpdb->prefix . "term_taxonomy where term_id='$term_id'";
	$r = $wpdb->get_results($s);
	$r = $r[0];
	$term_taxonomy_id = $r->term_taxonomy_id;

	$s = "select distinct posts.ID from " . $wpdb->prefix . "term_relationships rel, $wpdb->postmeta wpostmeta, $wpdb->posts posts where rel.term_taxonomy_id='$term_taxonomy_id' AND rel.object_id = wpostmeta.post_id AND posts.ID = wpostmeta.post_id AND posts.post_status = 'publish' AND posts.post_type = 'job' AND wpostmeta.meta_key = 'closed' AND wpostmeta.meta_value = '0'";
	$r = $wpdb->get_results($s);

	return count($r);
}

//--------------------------------------
// Get taxonomies terms links
//--------------------------------------

function wpjobster_display_job_categories($pid = 0) {

	if ($pid > 0) {
		$post = get_post($pid);
	} else {
		global $post, $post_id;
		$post = get_post($post->ID);
	}

	// get post type by post
	$post_type = $post->post_type;
	// get post type taxonomies
	$taxonomy = 'job_cat';
     $other_text = get_post_meta($post->ID, 'subcat_text_field', true );
		// Check if the custom field has a value.
	 /*  if ( ! empty( $other_text ) ) {
		    $serial_data=unserialize($other_text);
			 
	   }*/
	
	$terms = get_the_terms( $post->ID, $taxonomy );

	if(!isset($out))$out="";
	if(!isset($out1))$out1="";

	if ( !empty( $terms ) ) {
		foreach ( $terms as $term ) {	
				
			if ( is_rtl() ) {
				if($term->parent != 0){
					$out .= '<a href="' .get_term_link($term->term_id, $taxonomy) .'">'.$term->name.'</a> ';
					if($term->term_id == '3')
					{
						if(count($other_text) > 0)
						 {
							 $serial_data = unserialize($other_text);
							 $out1 .= $serial_data[0];
						 }
						}
				}
				else{
					$out1 .= '<a href="' .get_term_link($term->term_id, $taxonomy) .'">'.$term->name.'</a> ';
				}
			}else{
				if($term->parent == 0){
					$out .= '<a href="' .get_term_link($term->term_id, $taxonomy) .'">'.$term->name.'</a> ';
				  if($term->term_id == '3')
					{
						if(count($other_text) > 0)
						 {
							 $serial_data = unserialize($other_text);
							 $out1 .= $serial_data[0];
						 }
						}
				}
				else{
					$out1 .= '<a href="' .get_term_link($term->term_id, $taxonomy) .'">'.$term->name.'</a> ';
				}
			}
		}
	}
    if($out1 == '')
	{
         if(count($other_text) > 0)
		 {
			 $serial_data = unserialize($other_text);
			 $out1 = $serial_data[0];
		 }
	}
	if( $out && $out1 ){
		$line = '/ ';
	}else{
		$line = '';
	}

	return $out.$line.$out1;
}


//--------------------------------------
// Get taxonomies terms links
//--------------------------------------

function wpjobster_display_job_categories_text($pid = 0) {

	if ($pid > 0) {
		$post = get_post($pid);
	} else {
		global $post, $post_id;
		// get post by post id
		$post = &get_post($post->ID);
	}

	// get post type by post
	$post_type = $post->post_type;
	// get post type taxonomies
	$taxonomy = 'job_cat';

	$terms = get_the_terms( $post->ID, $taxonomy );

	if ( !empty( $terms ) ) {
		$i = 0;
		foreach ( $terms as $term ) {
			if ($i > 0) {
				$out .= ' / ';
			}
			$out .= $term->name;
			$i++;
		}
	}

	return $out;
}

//--------------------------------------
// Resolve conflit with ADS PRO Plugin
//--------------------------------------
add_action( 'template_redirect', 'wpj_create_fake_query' );
function wpj_create_fake_query() {
	global $wp, $wp_query, $post;

	if( ! $post ){
		$post_id = -99; // negative ID, to avoid clash with a valid post
		$post = new stdClass();
		$post->ID = $post_id;
		$post->post_author = 1;
		$post->post_date = current_time( 'mysql' );
		$post->post_date_gmt = current_time( 'mysql', 1 );
		$post->post_title = 'Fake post';
		$post->post_content = 'This is a fake post...';
		$post->post_status = 'publish';
		$post->comment_status = 'closed';
		$post->ping_status = 'closed';
		$post->post_name = 'fake-page-' . rand( 1, 99999 );
		$post->post_type = 'page';
		$post->filter = 'raw'; // important

		$wp_post = new WP_Post( $post );
	}
}
