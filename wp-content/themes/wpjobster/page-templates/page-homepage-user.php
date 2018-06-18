<?php get_header();
/*
Template Name: Homepage User
*/
?>
<div id="content-full-ov">
<?php
	if(is_user_logged_in()){
	    // TO SHOW THE PAGE CONTENTS
	    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
	        <div class="entry-content-page">
	            <?php the_content(); ?> <!-- Page Content -->
	        </div><!-- .entry-content-page -->

	    <?php
	    endwhile; //resetting the page loop
	    wp_reset_query(); //resetting the page query
	 } else {
	 	wp_redirect ( home_url());
	 }
    ?>
</div>
<?php get_footer(); ?>
