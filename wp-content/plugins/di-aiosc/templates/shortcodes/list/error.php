<?php
global $aiosc_user;
$form_args = array(
    'echo'           => true,
    'redirect'       => aiosc_get_page_ticket_list(true),
    'form_id'        => 'loginform',
    'label_username' => __( 'Username', 'aiosc' ),
    'label_password' => __( 'Password', 'aiosc' ),
    'label_remember' => __( 'Remember Me', 'aiosc' ),
    'label_log_in'   => __( 'Log In', 'aiosc' ),
    'id_username'    => 'user_login',
    'id_password'    => 'user_pass',
    'id_remember'    => 'rememberme',
    'id_submit'      => 'wp-submit',
    'remember'       => true,
    'value_username' => NULL,
    'value_remember' => false
);
?>
<div class="aiosc-window">
    <?php if(is_user_logged_in()) : ?>
        <div class="aiosc-form-response error" style="display: block">
            <p>
                <?php _e('<strong>Error:</strong> You do not have permission to view this list.','aiosc')?>
            </p>
        </div>
        <p>
            <?php printf(__('« Go back to <a href="%s">Home</a>','aiosc'),get_bloginfo('url')); ?>
        </p>
    <?php else : ?>
        <div class="aiosc-form-response warning" style="display: block">
            <p>
                <?php _e('In order to view this list, you must login with your account first.','aiosc')?>
            </p>
        </div>
        <?php echo wp_login_form($form_args); ?>
        <?php if(aiosc_can_register()) : ?>
            <p>
                <?php printf(__("Don't have an account yet? <a href='%s'>Register Now!</a>",'aiosc'),wp_registration_url()) ?>
            </p>
        <?php else : ?>
            <p>
                <?php _e('<strong>Note:</strong> Registrations for new users are closed.','aiosc') ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>
</div>