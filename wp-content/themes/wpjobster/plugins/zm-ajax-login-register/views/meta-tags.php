
<?php $styling = get_option('ajax_login_register_additional_styling');
if ( $styling ) : ?>
<!-- Start: Ajax Login Register Additional Styling -->
<style type="text/css">
    <?php echo wp_kses_stripslashes( $styling ); ?>
</style>
<!-- End: Ajax Login Register Additional Styling -->
<?php endif; ?>
