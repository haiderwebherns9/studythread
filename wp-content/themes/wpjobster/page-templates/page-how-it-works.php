<?php get_header();

/*
Template Name: How it Works
*/

?>
</div>
    <?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

        <div class="top-section" style="background-image: url('<?php the_field('top_section_background'); ?>'); color: <?php the_field('top_section_text_color'); ?>;">
            <div class="top-section-color">
            </div>

            <div class="wrapper relative">
                <h1 class="page_title"><?php the_title() ?></h1>
                <div class="hiw-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>

<div class="ui container">

    <div class="ui hidden divider"></div>

    <div class="ui segment">

        <div class="ui two column stackable grid">
            <div class="six wide column">
                <h2 class="how-it-works-title"><?php the_field('buyer_title'); ?></h2>
            </div>
            <div class="ten wide column content-how-works">
                <?php the_field('buyer_description'); ?>
            </div>
        </div>

    </div>

    <div class="ui hidden divider"></div>

    <div class="ui segment">

        <div class="ui two column grid">

            <?php
            if( have_rows('buyer_repeater') ): ?>

                <?php
                while( have_rows('buyer_repeater') ): the_row(); ?>

                    <div class="column">
                        <div class="ui two column stackable grid">

                            <div class="four wide column">
                                <img src="<?php the_sub_field('b_icon'); ?>" alt="<?php the_sub_field('b_title'); ?>" class="hiw-icon" />
                            </div>
                            <div class="twelve wide column">
                                <h2 class=""><?php the_sub_field('b_title'); ?></h2>
                                <?php the_sub_field('b_text'); ?>
                            </div>

                        </div>
                    </div>

                <?php endwhile; ?>

            <?php endif; ?>

        </div>
    </div>

    <div class="ui hidden divider"></div>

    <div class="ui segment">

        <div class="ui two column stackable grid">

            <div class="six wide column">
                <h2 class="how-it-works-title"><?php the_field('seller_title'); ?></h2>
            </div>

            <div class="ten wide column content-how-works">
                <?php the_field('seller_description'); ?>
            </div>

        </div>

    </div>

    <div class="ui hidden divider"></div>

    <div class="ui segment">

        <div class="ui two column grid">

            <?php
            if( have_rows('seller_repeater') ): ?>

                <?php
                while( have_rows('seller_repeater') ): the_row(); ?>

                    <div class="column">

                        <div class="ui two column stackable grid">
                            <div class="four wide column">
                                <img src="<?php the_sub_field('s_icon'); ?>" alt="<?php the_sub_field('s_title'); ?>" class="hiw-icon" />
                            </div>

                            <div class="twelve wide column">
                                <h2 class=""><?php the_sub_field('s_title'); ?></h2>
                                <?php the_sub_field('s_text'); ?>
                            </div>
                        </div>

                    </div>

                <?php endwhile; ?>

            <?php endif; ?>

        </div>
    </div>

    <div class="ui hidden divider"></div>

</div>


    <?php endwhile; ?>

    <?php endif; ?>



    <!-- ################### -->



<?php



    get_footer();



?>
