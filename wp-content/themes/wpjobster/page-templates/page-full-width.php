<?php get_header();

/*
Template Name: Full Width
*/

$wpjobster_adv_code_single_page_above_content = stripslashes(get_option('wpjobster_adv_code_single_page_above_content'));

if(!empty($wpjobster_adv_code_single_page_above_content)):
    echo '<div class="full_width_a_div">';
    echo $wpjobster_adv_code_single_page_above_content;
    echo '</div>';
endif;

?>
<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

    <div id="content-full">
        <div class="blog_post white-cnt">
            <div class="padding-cnt">
                <h1 class="page_title"><?php the_title() ?></h1>
                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
