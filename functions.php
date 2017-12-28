<?php

function change_password_form() { ?>
	<form action="" method="post">
        <label for="current_password">Enter your current password:</label>
        <input id="current_password" type="password" name="current_password" title="current_password" placeholder="" required>
        <label for="new_password">New password:</label>
        <input id="new_password" type="password" name="new_password" title="new_password" placeholder="" required>
        <label for="confirm_new_password">Confirm new password:</label>
        <input id="confirm_new_password" type="password" name="confirm_new_password" title="confirm_new_password" placeholder="" required>
        <input type="submit" value="Change Password">
    </form>
<?php }

function change_password(){
	if(isset($_POST['current_password'])){
		$_POST = array_map('stripslashes_deep', $_POST);
		$current_password = sanitize_text_field($_POST['current_password']);
		$new_password = sanitize_text_field($_POST['new_password']);
		$confirm_new_password = sanitize_text_field($_POST['confirm_new_password']);
		$user_id = get_current_user_id();
		$errors = array();
		$current_user = get_user_by('id', $user_id);
		// Check for errors
		if (empty($current_password) && empty($new_password) && empty($confirm_new_password) ) {
		$errors[] = 'All fields are required';
		}
		if($current_user && wp_check_password($current_password, $current_user->data->user_pass, $current_user->ID)){
		//match
		} else {
			$errors[] = 'Password is incorrect';
		}
		if($new_password != $confirm_new_password){
			$errors[] = 'Password does not match';
		}
		if(strlen($new_password) < 6){
			$errors[] = 'Password is too short, minimum of 6 characters';
		}
		if(empty($errors)){
			wp_set_password( $new_password, $current_user->ID );
			echo '<h2>Password successfully changed!</h2>';
		} else {
			// Echo Errors
			echo '<h3>Errors:</h3>';
		    foreach($errors as $error){
		        echo '<p>';
		        echo "<strong>$error</strong>";
		        echo '</p>';
		    }
		}

    }
}

function cp_form_shortcode(){
	    change_password();
        change_password_form();
}
add_shortcode('changepassword_form', 'cp_form_shortcode');
