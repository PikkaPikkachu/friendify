<?php

include("includes/header.php");

if(isset($_POST['update_details'])){
	header("Location: settings.php");

}

if(isset($_POST['close_account'])){
	$close_query = mysqli_query($con, "UPDATE users SET user_closed = 'yes' WHERE username = '$userLoggedIn'");
	session_destroy();
	header("Location: register.php");
}

?>

<div class = 'main_column column'>
		<h4> Close Account </h4>
		Are sure you want to close your account? <br><br>
		<form action = "close_account.php" method = "POST">
			<input type = 'submit' name = 'close_account' id = 'close_account' value = "Yes" class = 'danger settings_submit'> 
			<input type = 'submit' name = 'update_details' id = 'update_details' value = "Cancel" class = 'info settings_submit'> 

		</form>
	</div>