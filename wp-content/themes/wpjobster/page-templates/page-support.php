<?php get_header();

/*
Template Name: Support
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

        <div class="blog_post white-cnt support-page">
            <div class="padding-cnt">


<?php
$support_page = get_option("wpjobster_ticket_list_page_id");
$new_ticket_page = get_option("wpjobster_new_ticket_page_id");
$single_ticket_page = get_option("wpjobster_single_ticket_page_id");

// pre_print_r($support_page->ID);
// pre_print_r($single_ticket_page->ID);
// pre_print_r($new_ticket_page->ID);
?>


    <?php if ( is_page($support_page) ) { ?>

        <a class="btn smaller right green uppercase" href="<?php echo get_permalink($new_ticket_page); ?>"><?php _e("New Ticket", "wpjobster"); ?></a>

    <?php } elseif ( is_page($new_ticket_page) || is_page($single_ticket_page) ) { ?>

        <a class="btn smaller right green uppercase" href="<?php echo get_permalink($support_page); ?>"><?php _e("All Tickets", "wpjobster"); ?></a>

    <?php } ?>






                <h1 class="page_title"><?php the_title() ?></h1>
                <?php the_content(); ?>
            </div>
        </div>



    </div>



        <?php endwhile; ?>

    <?php endif; ?>



    <!-- ################### -->



<?php



    get_footer();



?>
