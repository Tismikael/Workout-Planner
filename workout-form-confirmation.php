<?php 

	require "config.php";
 
 	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
	    echo $mysqli->connect_error;
	    exit();
	}

	$mysqli->set_charset('utf8');

	$name = $_POST['name']?? '';
	$age = $_POST['age']?? '';
	$email = $_POST['email']?? '';
	$sex = $_POST['sexChoice']?? '';

	$level_id = $_POST['levelChoice']?? '';
	$muscle_groups = $_POST['muscleGroup'] ?? [];

	$newUser = "INSERT INTO user(fullname, age, email, sex) 
	VALUES ('$name', $age, '$email', '$sex');";

	$result_newUser = $mysqli->query($newUser);

	if (!$result_newUser){
		echo $mysqli->error;
		exit();
	}

	$userid_lookup = "SELECT user_id FROM user WHERE fullname = '$name' AND email =  '$email' ";

	$result = $mysqli->query($userid_lookup);

	if (!$result){
		echo $mysqli->error;
		exit();
	}

	$user_data = $result->fetch_assoc();
	$_SESSION['user_id'] = $user_data['user_id'];

	// $user_id = $mysqli->insert_id;
	$user_id = $_SESSION['user_id'];
	
	$newPlan = "INSERT INTO Plan (user_id, level_id) VALUES ($user_id, $level_id);";

	$results_newPlan = $mysqli->query($newPlan);

	if (!$results_newPlan){
		echo $mysqli->error;
		exit();
	}

	$plan_id = $mysqli->insert_id;

	foreach ( $muscle_groups as $row){
		$newGroup = "INSERT INTO PlanMuscleGroup (plan_id, group_id) 
		VALUES ($plan_id, $row);";
		$mysqli->query($newGroup);
	}

 ?>
