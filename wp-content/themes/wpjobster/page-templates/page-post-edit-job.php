<?php
/**
 * Template Name: Post/Edit Job Template
 *
 * @package WPJobster
 * @subpackage Jobster
 * @since Jobster v3.5.0
 */

get_header(); ?>

    </div>

    <?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>
        <div class="cf main">
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>

<?php get_footer(); ?>
