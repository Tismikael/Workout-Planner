<?php
	require "config.php"; // Make sure this includes database connection settings.

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
	    echo $mysqli->connect_error;
	    exit();
	}

	$mysqli->set_charset('utf8');

	// Retrieve form data
	$plan_id = $_POST['plan_id'] ?? null;
	$current_level_id = $_POST['current_level_id'] ?? null;
	$new_level_id = $_POST['new_level_id'] ?? null;

	if (!$plan_id || !$current_level_id || !$new_level_id) {
	    $error =  "Plan ID, current_level_id and new level ID are required.";
	}else if ($new_level_id == $current_level_id){
		$error = "Plan is already at this level. Please select another level.";
	}else {
		// SQL query to update the plan
		$update = "UPDATE Plan SET level_id = $new_level_id WHERE plan_id = $plan_id";

		$results = $mysqli->query($update);

		if (!$results){
			$error = "Unable to update plan " . $mysqli->error;
		}else {
			$isUpdated = true;
		}

	}

	$mysqli->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Easily update your workout plan to suit your progressing fitness level. Manage your exercises and ensure your workout remains challenging and effective. Stay committed to your fitness goals!">
    <title>Edit Confirmation | Workout Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="shared_background.css">
    <style>
 		#end-quote{
			text-decoration: none;
			color: #900;
		}   	

    </style>
</head>
<body>
    <div id="main">
        <div id="lookup">
            <div class="row">
                <div class="col-12">
                    <ul id="navbar">
                        <li><a href="landing_home_page.html">Home</a></li>
                        <li><a href="view_quotes_page.php">View Quotes</a></li>
                    </ul> <!-- navbar -->
                </div>
            </div><!--  row --> 
        </div> <!-- lookup -->
            <div class="text-center mt-4">      
                    <?php if (isset($error)): ?>
                        <div class="pb-2">
                            <h3><?php echo $error; ?> </h3> 
                        </div>
                    <?php elseif (isset($isUpdated)): ?>
                        <div class="pb-2">
                            <h3>The plan was successfully edited.</h3>
                        </div>
                    <?php endif; ?>

                <div class="pb-4"> 
                   <h4> Back to Workout  <a id="end-quote" href="view_workout_plan.php"> Plans</a> </h4>
                </div>

            </div>

    </div> <!-- main -->
</body>
</html>