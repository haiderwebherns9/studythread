			<?php
			wp_reset_query();
			get_template_part('template-parts/footer/footer', 'content');
			wp_footer();
			?>
		</div>
      <script>
         $(document).ready(function() {
            $("#myModal").show();
			$("#jbpop").click(function(){
				 $("#job_Modal").show();
			});
			$(".cclose").click(function(){
				var video = $("#job_Modal iframe").attr("src");
				$("#job_Modal iframe").attr("src","");
				$("#job_Modal iframe").attr("src",video);
				 $("#job_Modal").hide();
			});
			 $('.js-example-basic-single').select2();
       });
		</script>
		
<div>	
<div class="pst_job">	
<div id="job_Modal" class="jmodal">
  <!-- Modal content -->
  <div class="jmodal-content">
  <span class="cclose">&times;</span>
    <div class="jmodal-body">
	     <?php if ( is_active_sidebar( 'posting_job_video' ) ) : ?>
                                <?php dynamic_sidebar( 'posting_job_video' ); ?>
                       <?php endif; ?>
	</div>
	</div>
 </div>
 </div>
 
	</body>
</html>
