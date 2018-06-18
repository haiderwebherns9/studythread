<?php

/**
 * @todo Remove get_option() and settings_fields( 'my-settings-group' ); with do_settings_sections( 'my-plugin' );
 */

$a = New ajax_login_register_Admin;
$settings = $a->get_settings();
$style = get_option( 'ajax_login_register_default_style' );

?>

<div class="wrap" id="ajax-login-register-settings-wrapper">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e( 'AJAX Login &amp; Register Settings', 'wpjobster' );?></h2>
    <div class="main">
        <form action="options.php" method="post" class="form newsletter-settings-form">

            <h3><?php _e( 'General Settings', 'wpjobster' ); ?></h3>
            <table class="form-table">
                <?php $i=0; foreach( $settings['general'] as $setting ) : ?>
                    <tr valign="top">
                        <th scope="row"><?php print $setting['label']; ?></th>
                        <td>
                            <input type="checkbox" name="<?php print $setting['key']; ?>" id="<?php print $setting['key']; ?>" <?php checked( get_option( $setting['key'], "off" ), "on" ); ?> />
                            <label for="<?php print $setting['key']; ?>"><?php echo $setting['description']; ?></label>
                            <?php

                            if($i==2 && get_option( $setting['key'], "off" ) == "on"){
                                update_option('wpjobster_enable_phone_number', 'yes');
                                update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 1);
                            }
                            if($i==2 && get_option( $setting['key'], "off" ) != "on"){
                                update_option('wpjobster_enable_phone_number', 'no');
                                update_option('wsl_settings_bouncer_profile_completion_hook_extra_fields', 2);
                            }
                            ?>
                        </td>
                    </tr>
                <?php $i++; endforeach; ?>
            </table>

            <h3><?php _e( 'Advanced Usage', 'wpjobster' ); ?></h3>
            <table class="form-table">
                <?php foreach( $settings['advanced_usage'] as $setting ) : ?>
                    <tr valign="top">
                        <th scope="row"><?php print $setting['label']; ?></th>
                            <td>
                            <?php if ( $setting['key'] == 'ajax_login_register_default_style' ) : ?>
                                <select name="ajax_login_register_default_style">
                                    <?php foreach( array('default','wide') as $option ) : ?>
                                        <option value="<?php print $option; ?>" <?php selected( $style, $option ); ?>><?php print ucfirst( $option );?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?php echo $a->build_input( $setting['type'], $setting['key'] ); ?>
                                <p class="description"><?php echo $setting['description']; ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php settings_fields('wpjobster'); ?>
            <?php do_action('ajax_login_register_below_settings'); ?>

            <?php submit_button(); ?>
        </form>
    </div>


</div>
