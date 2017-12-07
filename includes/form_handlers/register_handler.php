<?php

//Declaring variable to prevent errors
$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = "";
$birthDate = "";
$error_array = array(); //hold any error messsages we might get 

if(isset($_POST['register_button'])){
	//Regsiteration form values
	//First Name
	$fname = strip_tags($_POST['reg_fname']); //Remove html tags
	$fname = str_replace(' ', '', $fname); // Remove spaces 
	$fname = ucfirst(strtolower($fname)); //Capitalize the first letter 
	$_SESSION['reg_fname'] =$fname; //Stores first name into session variable 

	//Last Name
	$lname = strip_tags($_POST['reg_lname']); //Remove html tags
	$lname = str_replace(' ', '', $lname); // Remove spaces 
	$lname = ucfirst(strtolower($lname)); //Capitalize the first letter 
	$_SESSION['reg_lname'] =$lname; //Stores last name into session variable 

	$birthDate = $_POST['reg_dob'];
	$_SESSION['reg_dob'] = $birthDate;

	//Email
	$em = strip_tags($_POST['reg_email']); //Remove html tags
	$em = str_replace(' ', '', $em); // Remove spaces 
	$em = ucfirst(strtolower($em)); //Capitalize the first letter 
	$_SESSION['reg_email'] =$em; //Stores email into session variable 

	//Email 2
	$em2 = strip_tags($_POST['reg_email2']); //Remove html tags
	$em2 = str_replace(' ', '', $em2); // Remove spaces 
	$em2 = ucfirst(strtolower($em2)); //Capitalize the first letter 
	$_SESSION['reg_email2'] =$em2; //Stores email2 into session variable 

	//Password
	$password = strip_tags($_POST['reg_password']); //Remove html tags
	$password2 = strip_tags($_POST['reg_password2']); //Remove html tags

	//Date
	$date = date("Y-m-d"); //Current date 

	if($em == $em2){
		//Check if email is in valid format 
		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);
			//check if email already exists 
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			//Count the number of rows returned 
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0){
				array_push($error_array, "Email already in use<br>");
			}


		}else{
			array_push($error_array,"Invalid emailformat<br>");
		}

	}else{
		array_push($error_array,"Emails don't match<br>");
	}


	if(strlen($fname)>25 || strlen($fname) < 2){
		array_push($error_array,"Your first name must be between 2 and 25 characters<br>");
	}

	if(strlen($lname)>25 || strlen($lname) < 2){
		array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
	}

	if($password != $password2){
		array_push($error_array,"Your passwords do not match<br>");
	}else{
		if(preg_match('/[^A-Za-z0-9]/', $password)){
			array_push($error_array,"Your password can only contain english characters or numbers<br>");
		}
	}

	if(strlen($password) > 30 || strlen($password) < 5){
		array_push($error_array,"Your password must be between 5 and 30 characters<br>");
	}

	if(empty($error_array)){
		$password = md5($password);//Encrypt password before sending to DB

		//Generate username by concatenating first name and last name
		$username = strtolower($fname. "_".$lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
		$i = 0;
		//if username exists add number to username
		while(mysqli_num_rows($check_username_query) != 0){
			$i++;
			$username = $username. "_". $i;
			$check_username_query= mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
		}

		//Profile picture assignment
		$rand = rand(1,2); // Rnadom number between 1 and 2
		if($rand == 1){
			$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
		}else{
			$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
		}

		$query = mysqli_query($con, "INSERT INTO users VALUES('', '$fname', '$lname','$birthDate', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no')");
		

		$rows = mysqli_affected_rows($con);
		if($rows == 1){
			array_push($error_array, "<span style = 'color: #14C800'> You're all set! Go ahead and login!</span><br>");
			$query_add_own_friend = mysqli_query($con, "INSERT INTO friends VALUES('', '$username', '$username')");
		}else{
			array_push($error_array, "<span style = 'color: #14C800'> User must be at least 18 years old!</span><br>");
		}
		



		//clear session variables

		$_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email2'] = "";
		$_SESSION['reg_email'] = "";

		


	}



}

?>