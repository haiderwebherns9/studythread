<?php
add_action( 'init', array( 'WPJ_Load_More_Posts', 'init' ) );

if ( ! class_exists( 'WPJ_Load_More_Posts' ) ) {
	class WPJ_Load_More_Posts {
          
		public $_current_user, $_wpdb, $_prefix, $_template_path;
            
		public function __construct() {
			global $current_user,$wpdb, $prefix,$_uid, $post_name;
			$uid = $current_user->ID;
	        $cid=$_COOKIE['country_name'];
             $query = "
             SELECT * FROM wp_87fsrr_country
               WHERE country_id =".$cid;
             $res=$wpdb->get_row($query, ARRAY_A);
			$this->post_type             = '';
			$this->page                  = '';
			$this->posts_per_page        = '';
			$this->post_status           = '';
			$this->orderby               = '';
			$this->order                 = '';
			$this->author                = '';
			$this->meta_query            = '';
			$this->meta_key              = '';
			$this->force_no_custom_order = '';
			$this->tax_query             = '';
			$this->year                  = '';
			$this->monthnum              = '';
			$this->tag                   = '';
			$this->term                  = '';
			$this->taxonomy              = '';
			$this->s                     = '';
			$this->container_class       = '';

			$this->_template_path = get_template_directory();
			$this->_current_user = wp_get_current_user();
			$this->_uid = $this->_current_user->ID;
			$prefix = $wpdb->prefix;

			$arguments = func_get_args();

			$_SESSION['class_arguments'] = $arguments;

			if(!empty($arguments)){
				foreach($arguments[0] as $key => $property){
					$this->{$key} = $property;
				}
			}

			$this->args = array(
				'post_type'             => $this->post_type,
				'posts_per_page'        => $this->posts_per_page,
				'post_status'           => $this->post_status,
				'orderby'               => $this->orderby,
				'order'                 => $this->order,
				'author'                => $this->author,
				'meta_query'            => $this->meta_query,
				'meta_key'              => $this->meta_key,
				'force_no_custom_order' => $this->force_no_custom_order,
				'tax_query'             => $this->tax_query,
				'year'                  => $this->year,
				'monthnum'              => $this->monthnum,
				'tag'                   => $this->tag,
				'term'                  => $this->term,
				'taxonomy'              => $this->taxonomy,
				's'                     => $this->s,
			);

			add_action('wp_ajax_nopriv_show_list_ajax',  array( $this, 'show_list_ajax') );
			add_action('wp_ajax_show_list_ajax',  array( $this, 'show_list_ajax') );
		}

		// START Search job filters //

		function wpjobster_job_posts_where( $where ) {
			global $wpdb;

			$term1 = $this->search_args['term1'];

			if(!is_array($term1)){
				$terms = trim($term1);
				$term1 = explode(" ",$terms);
			}else{
				$term1 = array();
			}
			$xl = '';

			foreach($term1 as $tt) {
				$xl .= " AND ({$wpdb->posts}.post_title LIKE '%$tt%' OR {$wpdb->posts}.post_content LIKE '%$tt%')";
			}

			$where .= " AND (1=1 $xl )";
			$where .= " AND wpj_country.meta_value=".$_COOKIE['country_name'];
			return $where;
		}

		function wpjobster_job_posts_fields( $select ){
			$units = get_option('wpjobster_locations_unit')=='kilometers' ? 6371 : 3959;
			$select = $select . ", {$units} * acos( cos( radians({$this->search_args['lat']}) ) * cos( radians( wpj_lat.meta_value ) ) * cos( radians ( wpj_long.meta_value ) - radians({$this->search_args['long']}) ) + sin( radians({$this->search_args['lat']}) ) * sin( radians ( wpj_lat.meta_value ) ) ) as distance";
			return $select;
		}

		function wpjobster_job_posts_join ( $join ){
			global $wpdb;
			$join = $join . "
			LEFT JOIN {$wpdb->prefix}postmeta AS wpj_lat ON ({$wpdb->prefix}posts.ID = wpj_lat.post_id AND wpj_lat.meta_key='lat')
			LEFT JOIN {$wpdb->prefix}postmeta AS wpj_long ON ({$wpdb->prefix}posts.ID = wpj_long.post_id AND wpj_long.meta_key='long')
			LEFT JOIN {$wpdb->prefix}postmeta AS wpj_country ON ({$wpdb->prefix}posts.ID = wpj_country.post_id AND wpj_country.meta_key='country_id')
			";
			return $join;
		}

		function wpjobster_job_posts_group_by( $groupby ){
			$rad = ! empty( $this->search_args['location_rad'] ) ? $this->search_args['location_rad'] : 1;
			$groupby = $groupby . "
			HAVING distance < {$rad}
			";
			return $groupby;
		}

		// END Search job filters //

		// START Search request filters //

		function wpjobster_request_posts_where( $where ) {
			global $wpdb;

			$term1 = $this->search_args['term1'];

			if(!is_array($term1)){
				$terms = trim($term1);
				$term1 = explode(" ",$terms);
			}else{
				$term1 = array();
			}
			$xl = '';

			foreach($term1 as $tt) {
				$xl .= " AND ({$wpdb->posts}.post_title LIKE '%$tt%' OR {$wpdb->posts}.post_content LIKE '%$tt%')";
			}

			$where .= " AND (1=1 $xl )";
			return $where;
		}

		function wpjobster_request_posts_fields( $select ){
			$units = get_option('wpjobster_locations_unit')=='kilometers' ? 6371 : 3959;
			$select = $select . ", {$units} * acos( cos( radians({$this->search_args['lat']}) ) * cos( radians( wpj_lat.meta_value ) ) * cos( radians ( wpj_long.meta_value ) - radians({$this->search_args['long']}) ) + sin( radians({$this->search_args['lat']}) ) * sin( radians ( wpj_lat.meta_value ) ) ) as distance";
			return $select;
		}

		function wpjobster_request_posts_join ( $join ){
			global $wpdb;
			$join = $join . "
			LEFT JOIN {$wpdb->prefix}postmeta AS wpj_lat ON ({$wpdb->prefix}posts.ID = wpj_lat.post_id AND wpj_lat.meta_key='request_lat')
			LEFT JOIN {$wpdb->prefix}postmeta AS wpj_long ON ({$wpdb->prefix}posts.ID = wpj_long.post_id AND wpj_long.meta_key='request_long')
			";
			return $join;
		}

		function wpjobster_request_posts_group_by( $groupby ){
			$rad = ! empty( $this->search_args['location_rad'] ) ? $this->search_args['location_rad'] : 1;
			$groupby = $groupby . "
			HAVING distance < {$rad}
			";
			return $groupby;
		}

		// END Search request filters //

		public static function init() {
			$arguments = isset($_SESSION['class_arguments']) ? $_SESSION['class_arguments'] : '';
			$arg = array();
			if(!empty($arguments)){
				foreach($arguments[0] as $key => $property){
					$arg[$key] = $property;
				}
			}

			$class = __CLASS__;
			new $class($arg);
		}

		public function show_list_item() {
			$display_content = $this->function_name;
			$display_content();
		}

		public function show_list_ajax() {

			if(!empty( $this->search_args['term1'] )) {
				if( $this->post_type == 'job' ){
					add_filter( 'posts_where' , array( $this, 'wpjobster_job_posts_where' ) );
				}elseif( $this->post_type == 'request' ){
					add_filter( 'posts_where' , array( $this, 'wpjobster_request_posts_where' ) );
				}
			}

			if(!empty( $this->search_args['location'] )) {
				if( $this->post_type == 'job' ){
					add_filter( 'posts_fields', array( $this, 'wpjobster_job_posts_fields' ) );
					add_filter( 'posts_join_paged', array( $this, 'wpjobster_job_posts_join' ) );
					add_filter( 'posts_groupby', array( $this, 'wpjobster_job_posts_group_by' ) );
				}elseif( $this->post_type == 'request' ){
					add_filter( 'posts_fields', array( $this, 'wpjobster_request_posts_fields' ) );
					add_filter( 'posts_join_paged', array( $this, 'wpjobster_request_posts_join' ) );
					add_filter( 'posts_groupby', array( $this, 'wpjobster_request_posts_group_by' ) );
				}
			}

			$page = WPJ_Form::post( 'page', 2 );
			$args = $this->args + array('paged' => $page);

			$the_query = new WP_Query( $args );

			// remove filters just after the WP_Query where we need them
			// and before other queries to prevent them being affected
			// especially when suppress_filters doesn't work
			remove_filter( 'posts_where' , array( $this, 'wpjobster_job_posts_where' ) );
			remove_filter( 'posts_where' , array( $this, 'wpjobster_request_posts_where' ) );

			remove_filter( 'posts_fields', array( $this, 'wpjobster_job_posts_fields' ) );
			remove_filter( 'posts_join_paged', array( $this, 'wpjobster_job_posts_join' ) );
			remove_filter( 'posts_groupby', array( $this, 'wpjobster_job_posts_group_by' ) );

			remove_filter( 'posts_fields', array( $this, 'wpjobster_request_posts_fields' ) );
			remove_filter( 'posts_join_paged', array( $this, 'wpjobster_request_posts_join' ) );
			remove_filter( 'posts_groupby', array( $this, 'wpjobster_request_posts_group_by' ) );

			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$this->show_list_item();
				}
			}

			if ( is_ajax() ) { wp_die(); }
		}

		public function have_rows(){

			$args = $this->args + array('paged' => 1);
			$the_query = new WP_Query( $args );

			if ( $the_query->have_posts() ) {
				return true;
			}else{
				return false;
			}
		}

		public function show_posts_list_func(){
			$args = $this->args + array('paged' => 1,);
			$the_query = new WP_Query( $args );
			remove_filter( 'posts_fields', 'wpjobster_job_posts_fields', 10 );
			remove_filter( 'posts_join_paged', 'wpjobster_job_posts_join', 10 );
			remove_filter( 'posts_groupby', 'wpjobster_job_posts_group_by', 10 );
			remove_filter( 'posts_fields', 'wpjobster_request_posts_fields', 10 );
			remove_filter( 'posts_join_paged', 'wpjobster_request_posts_join', 10 );
			remove_filter( 'posts_groupby', 'wpjobster_request_posts_group_by', 10 );
             
			if ( $the_query->have_posts() ) { ?>
       
				<div id="job_listings" class=" row wpj-load-more-target <?php echo wpj_get_cards_layout_class() . ' ' . $this->container_class; ?>">
				<?php
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$this->show_list_item();
					}
				?>
				</div>

				<?php
				// Do not DELETE these divs (LOAD MORE button out of container)
				if ( ! is_page( get_option( 'wpjobster_user_profile_page_id' ) ) && ! is_front_page() ) {
					echo '</div></div>';
				}
				// END LOAD MORE DIVS

				// Status Messages My Account Page
				if( is_page( get_option( 'wpjobster_my_account_page_id' ) ) ) {
					echo '<div class="my-account-right">';
						wpjobster_myjobs_statuses_info();
					echo '</div>';
				} elseif ( is_page( get_option( 'wpjobster_my_requests_page_id' ) ) ) {
					echo '<div class="right-my-requests">';
						wpjobster_myrequests_statuses_info();
					echo '</div>';
				}
				// END Status

				if( is_page( get_option( 'wpjobster_user_profile_page_id' ) ) ){
					$page_type = 'data-querytype="user_profile"';
				} elseif( is_front_page() ) {
					$page_type = 'data-querytype="homepage"';
				} else {
					$page_type = '';
				}
				if ( $the_query->max_num_pages > 1 ) { ?>
					<div class="load-more-button wpj-load-more <?php if (get_option('wpjobster_enable_auto-load') == "yes") { echo 'auto-load'; } ?>" <?php echo $page_type; ?> data-action="show_list_ajax" data-max="<?php echo $the_query->max_num_pages; ?>">
						<?php _e("Load More","wpjobster"); ?>
					</div>
				<?php }

				// Do not DELETE these divs (LOAD MORE button out of container)
				if ( ! is_page( get_option( 'wpjobster_user_profile_page_id' ) ) && ! is_front_page() ) {
					echo '<div><div>';
				}
				// END LOAD MORE DIVS
			}
		}
	}
}
