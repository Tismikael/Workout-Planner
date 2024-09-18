<?php 

	require "config.php"; // Make sure this includes database connection settings.

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno) {
	    echo $mysqli->connect_error;
	    exit();
	}

	$mysqli->set_charset('utf8');

	$plan_id = $_POST['plan_id'] ?? null;

	if (!$plan_id){
		$error = "Plan id is required";
	}else{

		$delete_pmg = "DELETE FROM PlanMuscleGroup 
		WHERE plan_id = $plan_id";

		$results_pmg = $mysqli->query($delete_pmg);

		$delete_p = "DELETE FROM Plan 
		WHERE plan_id = $plan_id";

		$results_p = $mysqli->query($delete_p);

		if (!$results_pmg && !$results_p){
			$error = "Unable to delete plan " . $mysqli->error;
		}else{
			$isDeleted = true;
		}

	}

	$mysqli->close();

 ?>

 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                        <li><a href="landing_home_page.php">Home</a></li>
                        <li><a href="view_quotes_page.php">View Quotes</a></li>
                        <li><a href="view_workout_plan.php?user_id=4">Workout Plans</a></li>
                    </ul> <!-- navbar -->
                </div>
            </div><!--  row --> 
        </div> <!-- lookup -->

                <div class="text-center mt-4">
                    <?php if (isset($error)): ?>
                        <div class="pb-2">
                            <h4><?php echo $error; ?> </h4> 
                        </div>
                    <?php elseif (isset($isDeleted)): ?>
                        <div class="pb-2">
                            <h4>The plan was successfully deleted.</h4>
                        </div>
                    <?php endif; ?>

            <div class="pb-4">
                <div class="col-12">
                   <h4> Back to Workout  <a id="end-quote" href="view_workout_plan.php"> Plans</a> </h4>
                </div>
            </div>
        </div>
    </div>
</body>
</html>