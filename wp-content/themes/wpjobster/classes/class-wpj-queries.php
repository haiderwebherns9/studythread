<?php
add_action( 'init', array( 'WPJ_Load_More_Queries', 'init' ) );

if ( ! class_exists( 'WPJ_Load_More_Queries' ) ) {
	class WPJ_Load_More_Queries {

		public function __construct( $args = array() ) {

			$defaults = array(
				'query_type'     => '',
				'query_status'   => '',
				'function_name'  => '',
				'posts_per_page' => '10',
				'new_class_row' => ''
			);
			$args = wp_parse_args( $args, $defaults );
			$this->query_type     = $args['query_type'];
			$this->query_status   = $args['query_status'];
			$this->function_name  = $args['function_name'];
			$this->posts_per_page = $args['posts_per_page'];
			$this->new_class_row = $args['new_class_row'];
           
		   
			// check post of the args for ajax
			$this->page           = WPJ_Form::post( 'page', 1 );

			$this->query_type     = WPJ_Form::post( 'query_type', $args['query_type'] );
			$this->query_status   = WPJ_Form::post( 'query_status', $args['query_status'] );
			$this->function_name  = WPJ_Form::post( 'function_name', $args['function_name'] );
			$this->posts_per_page = WPJ_Form::post( 'posts_per_page', $args['posts_per_page'] );

			$this->query = $this->get_query( array(
				'query_type'   => $this->query_type,
				'query_status' => $this->query_status,
			) );

			$this->r = array();
			$this->total_results = 0;
			$this->number_of_clicks = 0;

           
		   
			global $wpdb;
			if ( $this->query !== false && $this->query !== '' && $this->function_name !== '' ) {
				
				$this->limit            = $this->posts_per_page * ( $this->page - 1 );

				$this->query_limited    = $this->query . " LIMIT " . $this->limit . ", " . $this->posts_per_page;
				$this->r                = $wpdb->get_results( $this->query_limited );

				$this->total_results    = count( $wpdb->get_results( $this->query ) );
				$this->number_of_clicks = ceil( ( $this->total_results / $this->posts_per_page ) - 1 );
			}

			add_action( 'wp_ajax_nopriv_show_list_ajax_q', array( $this, 'show_list_ajax_q' ) );
			add_action( 'wp_ajax_show_list_ajax_q', array( $this, 'show_list_ajax_q' ) );
		}

		public static function init() {
			$class = __CLASS__;
			new $class( array() );
		}

		public function show_list_item( $row ) {
			
			$display_content = $this->function_name;
			$display_content( $row );
		}

		public function have_rows() {
			
			if ( $this->r )
				return true;
			else
				return false;
		}

		public function show_list_ajax_q() {
           
			if ( $this->r ) {
				foreach ( $this->r as $row ) {
					$this->show_list_item($row);
				}
			}
			if ( is_ajax() ) { wp_die(); }
		}

		public function show_queries_list_func() {
		
			if ( $this->r ) { ?>

				<div class="row special wpj-load-more-target cf <?php echo wpj_get_cards_layout_class() . ' ' . $this->new_class_row; ?>">
				<?php
					$posts_nr = 0;
					foreach ( $this->r as $row ) {
						$this->show_list_item($row);
						$posts_nr++;
					}
				?>
				</div>

				<!-- Do not DELETE this divs(LOAD MORE button out of container)-->
				</div></div>
				<!-- END LOAD MORE DIVS -->

				<?php if( is_page( get_option( 'wpjobster_my_account_shopping_page_id' ) ) ) {
					echo '<div class="right-shopping">';
						wpjobster_shopping_statuses_info();
					echo '</div>';
				} elseif ( is_page( get_option( 'wpjobster_my_account_sales_page_id' ) ) ) {
					echo '<div class="right-sales">';
						wpjobster_sales_statuses_info();
					echo '</div>';
				}

				if ( $posts_nr == $this->posts_per_page ) { ?>
					<div id="wpjobster-query-load-more-button"
						class="load-more-button wpj-load-more <?php if (get_option('wpjobster_enable_auto-load') == "yes") { echo 'auto-load'; } ?>"
						data-clicks="<?php echo $this->number_of_clicks; ?>"
						data-initial="1"
						data-action="show_list_ajax_q"
						data-max="<?php echo $this->posts_per_page; ?>"
						data-querytype="<?php echo $this->query_type; ?>"
						data-querystatus="<?php echo $this->query_status; ?>"
						data-functionname="<?php echo $this->function_name; ?>"
					>
						<?php _e( "Load More","wpjobster" ); ?>
					</div>
				<?php } ?>
				<div><div>
				<?php
			}
		}

		public static function get_query( $args ) {
			$defaults = array(
				'uid'          => false,
				'query_type'   => false,
				'query_status' => false,
			);

			$args = wp_parse_args( $args, $defaults );
             
			$uid          = $args['uid'];
			$query_type   = $args['query_type'];
			$query_status = $args['query_status'];

			if ( ! $uid ) {
				global $current_user;
				$current_user = wp_get_current_user();
				$uid = $current_user->ID;
			}

			if ( ! $uid || $query_type === false || $query_status === false ) {
				return false;
			}


			// the queries
			global $wpdb;
			$query = false;
             
			if ( $query_type == 'transactions' && $query_status == 'all' ) {
			
				$query = $wpdb->prepare(
					"
					SELECT *
					FROM {$wpdb->prefix}job_payment_transactions
					WHERE uid = %d
					ORDER BY id DESC
					",
					$uid
				);


			} elseif ( $query_type == 'payments' && $query_status == 'pending' ) {
					
				$query = $wpdb->prepare(
					"
					SELECT DISTINCT *
					FROM {$wpdb->prefix}job_orders orders,
						 {$wpdb->prefix}posts posts
					WHERE posts.post_author = %d
						AND posts.ID = orders.pid
						AND orders.clearing_period = '2'
						AND orders.closed = '0'
					ORDER BY orders.id DESC
					",
					$uid
				);


			} elseif ( $query_type == 'withdrawals' && $query_status == 'pending' ) {
				
				$query = $wpdb->prepare(
					"
					SELECT * FROM {$wpdb->prefix}job_withdraw
					WHERE done='0'
						AND uid = %d
					ORDER BY id DESC
					",
					$uid
				);


			} elseif ( $query_type == 'reviews' ) {
				
				if ( $query_status == 'to_award' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *,
							ratings.id ratid
						FROM {$wpdb->prefix}job_ratings ratings,
							 {$wpdb->prefix}job_orders orders
						WHERE ratings.awarded = '0'
							AND orders.id = ratings.orderid
							AND orders.uid = %d
							AND orders.closed != 1
							AND orders.completed = 1
							AND orders.done_seller = 1
						ORDER BY ratid DESC
						",
						$uid
					);

				} elseif ( $query_status == 'to_receive' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *
						FROM {$wpdb->prefix}job_ratings ratings,
							 {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.ID = orders.pid
							AND ratings.awarded = '0'
							AND orders.id = ratings.orderid
							AND posts.post_author = %d
							AND orders.closed != 1
						ORDER BY ratings.id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'received' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *,
							ratings.id ratid
						FROM {$wpdb->prefix}job_ratings ratings,
							 {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.ID = orders.pid
							AND ratings.awarded = '1'
							AND orders.id = ratings.orderid
							AND posts.post_author = %d
						UNION
						SELECT DISTINCT *,
							ratings.id ratid
						FROM {$wpdb->prefix}job_ratings_by_seller ratings,
							 {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.ID = orders.pid
							AND ratings.awarded = '1'
							AND orders.id = ratings.orderid
							AND ratings.uid = %d
						ORDER BY datemade DESC
						",
						$uid, $uid
					);
				}


			} elseif ( $query_type == 'sales' ) {
				
				if ( $query_status == 'active' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *
						FROM {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.post_author = %d
							AND posts.ID = orders.pid
							AND orders.done_seller = '0'
							AND orders.done_buyer = '0'
							AND orders.date_finished = '0'
							AND orders.closed = '0'
							AND payment_status != 'pending'
						ORDER BY orders.id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'pending_payment' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *
						FROM {$wpdb->prefix}job_orders orders,
						     {$wpdb->prefix}posts posts
						WHERE posts.post_author = %d
							AND posts.ID = orders.pid
							AND orders.done_seller = '0'
							AND orders.done_buyer = '0'
							AND orders.date_finished = '0'
							AND orders.closed = '0'
							AND ( payment_status = 'pending'
								OR payment_status = 'processing' )
						ORDER BY orders.id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'delivered' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *
						FROM {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.post_author = %d
							AND posts.ID = orders.pid
							AND orders.done_seller = '1'
							AND orders.done_buyer = '0'
							AND orders.closed = '0'
						ORDER BY orders.id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'cancelled' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *
						FROM {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.post_author = %d
							AND posts.ID = orders.pid
							AND orders.closed = '1'
						ORDER BY orders.id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'completed' ) {
					$query = $wpdb->prepare(
						"
						SELECT DISTINCT *
						FROM {$wpdb->prefix}job_orders orders,
							 {$wpdb->prefix}posts posts
						WHERE posts.post_author = %d
							AND posts.ID = orders.pid
							AND orders.done_seller = '1'
							AND orders.done_buyer = '1'
							AND orders.closed = '0'
						ORDER BY orders.id DESC
						",
						$uid
					);
				}


			} elseif ( $query_type == 'shopping' ) {
				
				if ( $query_status == 'active' ) {
					$query = $wpdb->prepare(
						"
						SELECT *
						FROM {$wpdb->prefix}job_orders
						WHERE uid = %d
							AND done_seller = '0'
							AND done_buyer = '0'
							AND date_finished = '0'
							AND closed = '0'
							AND payment_status != 'pending'
						ORDER BY id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'pending_payment' ) {
					$query = $wpdb->prepare(
						"
						SELECT *
						FROM {$wpdb->prefix}job_orders
						WHERE uid = %d
							AND done_seller = '0'
							AND done_buyer = '0'
							AND date_finished = '0'
							AND closed = '0'
							AND ( payment_status = 'pending'
								OR payment_status = 'processing' )
						ORDER BY id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'pending_review' ) {
					$query = $wpdb->prepare(
						"
						SELECT *
						FROM {$wpdb->prefix}job_orders
						WHERE uid = %d
							AND done_seller = '1'
							AND done_buyer = '0'
							AND closed = '0'
						ORDER BY id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'cancelled' ) {
					$query = $wpdb->prepare(
						"
						SELECT *
						FROM {$wpdb->prefix}job_orders
						WHERE uid = %d
							AND closed = '1'
						ORDER BY id DESC
						",
						$uid
					);

				} elseif ( $query_status == 'completed' ) {
					$query = $wpdb->prepare(
						"
						SELECT *
						FROM {$wpdb->prefix}job_orders
						WHERE uid = %d
							AND completed = '1'
						ORDER BY id DESC
						",
						$uid
					);
				}
			} elseif ( $query_type == 'affiliate' && $query_status == 'all' ) {
				
				$query = $wpdb->prepare(
					"
					SELECT DISTINCT *
					FROM {$wpdb->prefix}job_affiliate_transactions
					WHERE user_id=%d
					ORDER BY date DESC
					",
					$uid
				);
			}
           
			return $query;
		}
	}
}
