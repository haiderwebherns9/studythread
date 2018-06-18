<?php get_header();

/*
Template Name: Jobster_Special_Page
*/

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>