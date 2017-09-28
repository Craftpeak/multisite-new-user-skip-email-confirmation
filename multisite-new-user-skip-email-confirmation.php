<?php
/*
Plugin Name: Multisite - New User Skip Email Confirmation
Plugin URI: https://github.com/Craftpeak/multisite-new-user-skip-email-confirmation/
Description: Mimic the super-admin "Skip Confirmation Email" checkbox for users that can create other users.
Author: Craftpeak
Version: 1.0.0
Author URI: https://craftpeak.com/
*/

function wnet_custom_user_profile_fields($user){
	if (!is_super_admin( get_current_user_id() )) {
?>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Skip Confirmation Email') ?></th>
			<td><input type="checkbox" name="skipconfirmation" value="1" <?php if (isset($_POST['skipconfirmation'])) checked( $_POST['skipconfirmation'], 1 ); ?> /> Add the user without sending an email that requires their confirmation.</td>
    </tr>
	</table>

<?php
	}
}

add_action( "user_new_form", "wnet_custom_user_profile_fields" );

add_filter('wpmu_signup_user_notification', 'wnet_auto_activate_users', 10, 4);
function wnet_auto_activate_users($user, $user_email, $key, $meta){

	if(!current_user_can('create_users'))
		return false;

	if (!empty($_POST['skipconfirmation']) && $_POST['skipconfirmation'] == 1) {
		$user_id = wpmu_activate_signup( $key );
			return false;
	}
}
