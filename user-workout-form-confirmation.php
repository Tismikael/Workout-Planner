<?php 


	require "config.php";
 
 	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
	    echo $mysqli->connect_error;
	    exit();
	}

	$mysqli->set_charset('utf8');

	$level_id = $_POST['levelChoice']?? '';
	$muscle_groups = $_POST['muscleGroup'] ?? [];

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