<?php

// dashboard widgets

add_action('wp_dashboard_setup', 'pippin_most_popular_dashboard_widgets');

function pippin_most_popular_dashboard_widgets() {
   global $wp_meta_boxes;

   wp_add_dashboard_widget('pippin_dashboard_widget', 'Most Bookmarked', 'pippin_most_popular_dashboard_widget_render');
}

function pippin_most_popular_dashboard_widget_render() {
  
  echo upb_most_bookmarked(10, true);
  
}
