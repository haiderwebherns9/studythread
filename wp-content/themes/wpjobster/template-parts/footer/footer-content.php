</div>

<?php
global $no_header_footer;

if ($no_header_footer == false) {

	get_template_part('template-parts/footer/footer', 'secondary'); ?>

	<div class="footer-new">
		<?php get_template_part('template-parts/footer/footer', 'widgets'); ?>

		<div class="ui container new-footer-cols cf divider-footer">
			<?php
			get_template_part('template-parts/footer/footer', 'cart');
			get_template_part('template-parts/footer/footer', 'copyright');
			?>
		</div>
	</div>

<?php }

get_template_part('template-parts/footer/footer', 'analytics');
get_template_part('template-parts/footer/footer', 'back-to-top');
?>
<script>
  // $(function(){$('#time_zone').searchableSelect();});
</script>
<script>
   $(function(){
	   $("#shw_techer").click(function(){
		 if($(this).is(":checked")) {
		    var qrs=($(this).val());     
			 location.href = window.location+"?teacher="+qrs;
		   }else {
			   location.href ="https://www.studythread.com/";
		   }
		});
   });
</script>