<?php
add_filter('pre_get_posts', 'wpjobster_my_get_posts');
function wpjobster_my_get_posts($query){
	if (is_home() && $query->is_main_query())
		$query->set('post_type', array(            'job'        ));
	return $query;
}

add_filter('term_link', 'wpjobster_post_tax_link_filter_function', 1, 3);
function wpjobster_post_tax_link_filter_function($post_link, $id = 0, $leavename = FALSE){
	global $category_url_link;

	if (!wpjobster_using_permalinks()) return $post_link;
	return str_replace("job_cat", $category_url_link, $post_link);
}

add_filter('post_type_link', 'wpjobster_post_type_link_filter_function', 1, 3);
function wpjobster_post_type_link_filter_function($post_link, $id = 0, $leavename = FALSE){
	global $category_url_link;

	if (strpos('%job_cat%', $post_link) === 'FALSE') {
		return $post_link;
	}

	$post = get_post($id);

	if (!is_object($post) || $post->post_type != 'job') {
		return str_replace("job_cat", $category_url_link, $post_link);
	}

	$terms = wp_get_object_terms($post->ID, 'job_cat');

	if (!$terms) {
		return str_replace('%job_cat%', 'uncategorized', $post_link);
	}

	return str_replace('%job_cat%', $terms[0]->slug, $post_link);
}

function wpjobster_job_fake_queue($pid) {
	$fake_queue = get_post_meta(get_the_ID(), "fake_queue", true);
	$fake_queue_rand = get_post_meta(get_the_ID(), "fake_queue_rand", true);
	$fake_queue_exp = get_post_meta(get_the_ID(), "fake_queue_exp", true);

	$tm = current_time('timestamp', 1);
	$exp = strtotime("+3 days");

	if ($fake_queue) {
		if ($fake_queue_exp > $tm) {
			return $fake_queue_rand;

		} else {
			$rand = $fake_queue + rand(0, round($fake_queue / 3));
			update_post_meta($pid, 'fake_queue_exp', $exp);
			update_post_meta($pid, 'fake_queue_rand', $rand);

			return $rand;
		}

	} else {
		return 0;
	}
}

add_action( 'init', 'wpj_submit_prepare_continue' );
function wpj_submit_prepare_continue(){
	if (isset($_POST['submit_prepare_continue'])) {
		$i_will = trim(htmlspecialchars($_POST['i_will']));

		if (isset($_POST['job_cost'])):
		$job_cost = trim(htmlspecialchars($_POST['job_cost']));
		$_SESSION['job_cost'] = $job_cost;
		endif;
		$_SESSION['i_will'] = $i_will;
		wp_redirect(get_permalink(get_option('wpjobster_post_new_page_id')));
		exit;
	}
}

function wpjobster_myjobs_statuses_info() { ?>
	<a class="button-modal-open" href="#"><?php _e('Status Messages Legend', 'wpjobster'); ?></a>
	<div class="ui small modal legend">
		<i class="close icon"></i>
		<div class="header">
			<?php _e('Status Messages Legend', 'wpjobster'); ?>
		</div>
		<div class="content my-account">
			<div class="position-relative">
				<span class="oe-status-btn oe-green oe-full"><?php _e('published', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your Job is visible and may be ordered by all users.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-yellow oe-full"><?php _e('paused', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('No one can see your Job. You may reactivate it whenever you like.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-orange oe-full"><?php _e('pending', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your Job is pending review and is not yet available.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red oe-full"><?php _e('rejected', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your job failed to pass our review and is not visible to anyone.', 'wpjobster'); ?>
				</div>
			</div>
			<div class="position-relative">
				<span class="oe-status-btn oe-red oe-full"><?php _e('disabled', 'wpjobster'); ?></span>
				<div class="oe-status-content">
				<?php _e('Your job was automatically disabled.', 'wpjobster'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function wpjobster_myjobs_embed_code($uzid) {
	$http_host = $_SERVER['HTTP_HOST']; ?>

	<div class="embed-root" data-uzid="<?php echo $uzid; ?>">
		<a href="#" class="link<?php echo $uzid; ?> btn grey_btn lighter smallest p5r"><?php _e('Embed', 'wpjobster'); ?></a>

		<div class="embed<?php echo $uzid; ?>" title="<?php _e('Get the embed code', 'wpjobster'); ?>" style="display: none;">
			<div class="main-cnt">
				<div class="main-cnt">
					<?php _e("Embed a cool button on your site which will allow your visitors to buy your gig.", "wpjobster"); ?>
					<?php _e("Choose the style and copy the code below on your site.", "wpjobster"); ?>
				</div>
				<div class="main-cnt">

				</div>
				<div class="main-cnt bs-col-container">
					<div class="uz-btns-container">
						<div class="uz-ui-demo uz-btn-style-fat"><div class="uz-btn" data-id="1"></div></div>
						<div class="uz-ui-demo uz-btn-style-big"><div class="uz-btn" data-id="2"></div></div>
						<div class="uz-ui-demo uz-btn-style-small"><div class="uz-btn" data-id="3"></div></div>
					</div>
				</div>

				<div class="uz-tabs-container">
					<div id="uz-tab1" class="uz-tab-active uz-tab">
						<textarea readonly onclick="this.select()" style="width: 100%; height: 150px; box-sizing: border-box;">
<?php $the_embed = <<<EOT
<script type="text/javascript">
document.write('<div class="uz-ui-root uz-btn-style-fat" id="uzroot{$uzid}" data-uzid="{$uzid}" data-host="{$http_host}"></div>');
var uzHeadTag = document.getElementsByTagName("head")[0];
if (!uzScriptTag) {
	var uzScriptTag = document.createElement('script');
	uzScriptTag.type = 'text/javascript';
	uzScriptTag.src = '//{$http_host}/widget/assets/js/scripts.js';
	uzHeadTag.appendChild(uzScriptTag); }
</script>
EOT;
$the_embed = htmlspecialchars($the_embed);
echo $the_embed;
?>
						</textarea>
					</div>
					<div id="uz-tab2" class="uz-tab">
						<textarea readonly onclick="this.select()" style="width: 100%; height: 150px; box-sizing: border-box;">
<?php $the_embed = <<<EOT
<script type="text/javascript">
document.write('<div class="uz-ui-root uz-btn-style-big" id="uzroot{$uzid}" data-uzid="{$uzid}" data-host="{$http_host}"></div>');
var uzHeadTag = document.getElementsByTagName("head")[0];
if (!uzScriptTag) {
	var uzScriptTag = document.createElement('script');
	uzScriptTag.type = 'text/javascript';
	uzScriptTag.src = '//{$http_host}/widget/assets/js/scripts.js';
	uzHeadTag.appendChild(uzScriptTag); }
</script>
EOT;
$the_embed = htmlspecialchars($the_embed);
echo $the_embed;
?>
						</textarea>
					</div>
					<div id="uz-tab3" class="uz-tab">
						<textarea readonly onclick="this.select()" style="width: 100%; height: 150px; box-sizing: border-box;">
<?php $the_embed = <<<EOT
<script type="text/javascript">
document.write('<div class="uz-ui-root uz-btn-style-small" id="uzroot{$uzid}" data-uzid="{$uzid}" data-host="{$http_host}"></div>');
var uzHeadTag = document.getElementsByTagName("head")[0];
if (!uzScriptTag) {
	var uzScriptTag = document.createElement('script');
	uzScriptTag.type = 'text/javascript';
	uzScriptTag.src = '//{$http_host}/widget/assets/js/scripts.js';
	uzHeadTag.appendChild(uzScriptTag); }
</script>
EOT;
$the_embed = htmlspecialchars($the_embed);
echo $the_embed;
?>
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

add_filter( 'qm/process', 'my_site_process_query_monitor_stats', 100, 2 );
function my_site_process_query_monitor_stats( $show_stats, $is_admin_bar_showing ) {
	if ( ! $is_admin_bar_showing )
		return false;

	return $show_stats;
}
