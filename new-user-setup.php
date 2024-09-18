<?php 
	
	require "config.php";

	 $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
	    echo $mysqli->connect_error;
	    exit();
	}

	$mysqli->set_charset('utf8');


	$username = $_POST['username'];
	$email = $_POST['email'];

	$userid_lookup = "SELECT user_id FROM user WHERE fullname = '$username' AND email =  '$email' ";

	$result = $mysqli->query($userid_lookup);

	if (!$result){
		echo $mysqli->error;
		exit();
	}

	
	$user_data = $result->fetch_assoc();
	$_SESSION['user_id'] = $user_data['user_id'];


	header("Location: start-new-session.html");

	$mysqli->close();

 ?>