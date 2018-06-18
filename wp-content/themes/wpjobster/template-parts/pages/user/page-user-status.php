<?php
$vars = wpj_user_status_vars( $u_id );

if ( ! isset( $u_id ) ) {
	$u_id = $vars['u_id'];
}

$random_no = $vars['random_no'];

$os = $vars['wpjobster_en_user_online_status'];

$class_on = $os == 'yes_with_text' ? 'user-profile-online-text' : '';
$class_off = $os == 'yes_with_text' ? 'user-profile-offline-text' : '';

if( $os && $os != 'no' ){
	if ( $vars['mins']<=5 ) { ?>
		<!-- ONLINE -->
		<div class='<?php echo $class_on; ?>' style="display:inline-block">
			<?php if( $os == 'yes_with_text' ) { ?>
				<span class="user-profile-online-text"></span>
				<?php echo __( 'Online', 'wpjobster' ); ?>
			<?php } elseif ( $os == 'yes_with_icon' ) { ?>
				<span class="user-profile-online" onmouseover="showOnlineStatus<?php echo $u_id.'_'.$random_no; ?>()" onmouseout="hideOnlineStatus<?php echo $u_id.'_'.$random_no; ?>()"> &bull; </span>

				<div id="user_online_status_<?php echo $u_id.'_'.$random_no; ?>" class="tooltip user-profile-tooltip" style="">
					<span class="tool-status"><?php echo __(' Online ', 'wpjobster'); ?></span>
				</div>
			<?php } ?>
		</div>
	<?php } else { ?>
		<!-- OFFLINE -->
		<div class='<?php echo $class_off; ?>' style="display:inline-block">
			<?php if( $os == 'yes_with_text' ) { ?>
				<span class="user-profile-offline-text"></span>
				<?php echo __( 'Offline', 'wpjobster' ); ?>
			<?php } elseif ( $os == 'yes_with_icon' ) { ?>
				<span class="user-profile-offline" onmouseover="showOnlineStatus<?php echo $u_id.'_'.$random_no; ?>()" onmouseout="hideOnlineStatus<?php echo $u_id.'_'.$random_no; ?>()" style=""> &bull; </span>

				<div id="user_online_status_<?php echo $u_id.'_'.$random_no; ?>" class="tooltip user-profile-tooltip">
					<span class="tool-status"> <?php echo __(' Offline ', 'wpjobster'); ?> </span>
				</div>
			<?php } ?>
		</div>
	<?php } ?>

	<script>
		var mins = '<?php echo $vars['mins']; ?>';
		function showOnlineStatus<?php echo $u_id.'_'.$random_no; ?>() {
			$("#user_online_status_<?php echo $u_id.'_'.$random_no; ?>").show();
			$("#user_online_status_<?php echo $u_id.'_'.$random_no; ?> > span").show();
		}
		function hideOnlineStatus<?php echo $u_id.'_'.$random_no; ?>() {
			if( mins > 5 ){
				$("#user_online_status_<?php echo $u_id.'_'.$random_no; ?>").hide();
			}
			$("#user_online_status_<?php echo $u_id.'_'.$random_no; ?> > span").hide();
		}
	</script>

<?php }
