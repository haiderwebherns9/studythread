<?php
/**
 * This is the template for our register form. It should contain as less logic as possible
 */

?>
<script type="text/javascript">
	jQuery( document ).ready(function($){
		var default_country = $(".cell_number").attr('data-default-country');
		if(default_country)
			default_country=default_country;
		else
			default_country='us';

		jQuery(".cell_number").intlTelInput({
			nationalMode: false,
			initialCountry: default_country
		});
		jQuery('select.register-segregate').on('change', function() {
          if(this.value=='seller'){
			  jQuery('.teacher_field').show();
		  }else{
			  jQuery('.teacher_field').hide();
		  }
        });
		$('select[name="wpjobster_user_type"]').on('change', function(){    
              $usr_type= $(this).val();    
         });
		jQuery(".ajax-login-default-form-container").submit(function(e){
			if($usr_type=="seller"){
		    if(($('#tfname').val()=="")) {
            $('#tfname').focus();
			$(".error-container").show();
			$('.error-container' ).html("Error: first name is required");
		    e.preventDefault();
			return false;
            }
			if(($('#tlname').val()=="")) {
            $('#tlname').focus();
			$(".error-container").show();
			$('.error-container' ).html("Error: last name is required ");
		    e.preventDefault();
			return false;
            }
		    if(($('#tcollege').val()=="")) {
            $('#tcollege').focus();
			$(".error-container").show();
			$('.error-container' ).html("Error:  college or university attending required");
		    e.preventDefault();
			return false;
            }
			if(($('#tedu').val()=="")) {
            $('#tedu').focus();
			$(".error-container").show();
			$('.error-container' ).html("Error:  level of education required");
		    e.preventDefault();
			return false;
            }
			if(($('#tdegre').val()=="")) {
            $('#tdegre').focus();
			$(".error-container").show();
			$('.error-container' ).html("Error:  studying or earned degree required");
		    e.preventDefault();
			return false;
            }
		}
         });
	});
</script>

<style type="text/css">
	.intl-tel-input {
		display: block
	}
</style>

<!-- Register Modal -->
<?php if ( get_option('users_can_register') ) : ?>
	<div class="ajax-login-register-register-container">
		<?php if ( is_user_logged_in() ) : ?>
			<p><?php printf('%s <a href="%s" title="%s">%s</a>', __('You are already registered','wpjobster'), wp_logout_url( site_url() ), __('Logout', 'wpjobster'), __('Logout', 'wpjobster') ); ?></p>
		<?php else : ?>
			<div class="header modal-login">
				<?php _e('Register','wpjobster'); ?>
			</div>
			<form action="javascript://" name="registerform" class="ui form ajax-login-default-form-container register_form <?php print get_option('ajax_login_register_default_style'); ?>">

				<div class="form-wrapper register">
					<?php
					wp_nonce_field( 'facebook-nonce', 'facebook_security' );
					wp_nonce_field( 'register_submit', 'security' );
					?>
					  <div class="error-container" style="display:none"></div>
					<div class="ajax-login-register-status-container">
						<div class="ajax-login-register-msg-target"></div>
					</div>
					<div class="field"><input type="text" required name="login" id="login" placeholder="<?php _e('User Name', 'wpjobster'); ?>" class="user_login" autofocus /></div>
					<div class="field"><input type="text" required name="email" class="user_email ajax-login-register-validate-email" placeholder="<?php _e('Email', 'wpjobster'); ?>" /></div>

					<?php do_action( 'zm_ajax_login_register_below_email_field' );
                    global $wpdb;
					$query = "SELECT * FROM 	wp_87fsrr_country";
					$res= $wpdb->get_results($query);?>
					<div class="field">
					<select id="cuntry_id" name="country_name" onchange="get_currency();">
						<option value="">Select One</option>
						<?php foreach($res as $res_val){ ?>
							<option value="<?php echo $res_val->country_id;?>"><?php echo $res_val->country_name;?></option>
						 <?php }?>
						</select>	
						</div>
						<div class="teacher_field" style="display:none;">
						     <div class="field">
							     <input type="text" id="tfname" name="tchr_fname" placeholder="First Name">
		                   </div>
							 <div class="field">
							     <input type="text" id="tlname" name="tchr_lname" placeholder="Last Name">							    
							</div>
							 <div class="field">
							     <input type="text" id="tcollege" name="tchr_atnd_colg" placeholder="College or University Attending(ed)">							    
							</div>
							 <div class="field">
							     <input type="text" id="tedu" name="tchr_edu" placeholder="Level of Education">							    
							 </div>
							 <div class="field">
							     <input type="text" id="tdegre" name="tchr_degree" placeholder="Studying or Earned Degree">						  
							 </div>
							  <div class="field">
							     <input type="text" id="tbksh" name="tchr_bksh_no" placeholder="Enter Bkash Number(Only for Bangladesh)">
							 </div>
							 <div class="field"></div>
						</div>
						<?php
					if (get_option('ajax_login_register_phone_number') == 'on') { ?>
						<div class="field"><input data-default-country="<?php echo get_option('wpjobster_phone_country_select'); ?>" type="text" name="cell_number" id="cell_number" placeholder="<?php _e('Phone Number', 'wpjobster'); ?>" class="cell_number" /></div>
					<?php }

					do_action( 'zm_ajax_login_register_extra_fields_display' ); ?>

					<div class="field"><input type="password" required name="password" placeholder="<?php _e('Password', 'wpjobster'); ?>" class="user_password" /></div>
					<div class="field"><input type="password" required name="confirm_password" placeholder="<?php _e('Confirm Password', 'wpjobster'); ?>" class="user_confirm_password" /></div>

					<div class="field"><?php wpj_recaptcha_form( 'zm_register' ) ?></div>

					<div class="button-container">
						<button class="ui fluid button register_button green" type="submit" name="register" disabled>
							<?php _e('Register','wpjobster'); ?>
						</button>
					</div>

					<div class="divider">
						<span><?php _e("or", 'wpjobster'); ?></span>
					</div>

					<?php do_action( 'wordpress_social_login' ); ?>

					<div class="field"><a href="#" class="already-registered-handle"><?php echo apply_filters( 'ajax_login_register_already_registered_text', __('Already registered?','wpjobster') ); ?></a></div>
				</div>
			</form>
		<?php endif; ?>
	</div>
<?php else : ?>
	<p><?php _e('Registration is currently closed.','wpjobster'); ?></p>
<?php endif; ?>
<!-- End 'modal' -->
