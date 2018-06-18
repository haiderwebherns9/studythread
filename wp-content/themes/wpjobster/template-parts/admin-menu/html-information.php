<?php

function wpjobster_information() {
	$id_icon      = 'icon-options-general-info';
	$ttl_of_stuff = 'Jobster - '.__('Information','wpjobster');

  //------------------------------------------------------

	echo '<div class="wrap">';
	echo '<div class="icon32" id="'.$id_icon.'"><br/></div>';
	echo '<h2 class="my_title_class_sitemile">'.$ttl_of_stuff.'</h2>';

?>

<div id="usual2" class="usual">
	<ul>
		<li><a href="#tabs1" class="selected"><?php _e('Main Information','wpjobster'); ?></a></li>
	</ul>
	<div id="tabs1" style="display: block; ">
		<table width="100%" class="sitemile-table">

			<tr>
				<td width="260"><?php _e('WPJobster Version:','wpjobster'); ?></td>
				<td><?php echo wpjobster_VERSION; ?></td>
			</tr>

			<tr>
				<td width="160"><?php _e('WPJobster Latest Release:','wpjobster'); ?></td>
				<td><?php echo wpjobster_RELEASE; ?></td>
			</tr>

			<tr>
				<td width="160"><?php _e('WordPress Version:','wpjobster'); ?></td>
				<td><?php bloginfo('version'); ?></td>
			</tr>

		</table>
	</div>
</div>

<?php
  echo '</div>';
}
