
<?php 

    require "config.php";
 
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');

    $user_id = null;

    if (isset($_SESSION['user_id']) && $_SESSION['user_id']){
        $user_id = $_SESSION['user_id'];
        $plans_query = "SELECT Plan.plan_id, Level.name AS level_name, Level.level_id AS level_id, muscle_groups.name AS group_name, muscle_part.name AS part_name, exercise_names.name AS exercise_name, exercise_names.description
        FROM Plan LEFT JOIN Level ON Plan.level_id = Level.level_id  LEFT JOIN PlanMuscleGroup ON Plan.plan_id = PlanMuscleGroup.plan_id
        LEFT JOIN muscle_groups ON PlanMuscleGroup.group_id = muscle_groups.group_id
        LEFT JOIN muscle_part ON muscle_groups.group_id = muscle_part.group_id LEFT JOIN exercise_names ON muscle_part.part_id = exercise_names.part_id AND exercise_names.level_id = Plan.level_id WHERE Plan.user_id = $user_id;";

        $plans_result = $mysqli->query($plans_query);

        if (!$plans_result) {
            echo "Error fetching plans: " . $mysqli->error;
            exit();
        }

        $plans = [];
        while ($row = $plans_result->fetch_assoc()) {
            $plan_id = $row['plan_id'];
            $group_name = $row['group_name'];
            
            // Initialize plan and its exercises if not already set
            if (!isset($plans[$plan_id])) {
                $plans[$plan_id] = [
                    'level_id' => $row['level_id'],
                    'level_name' => $row['level_name'],
                    'exercises' => [],
                ];
            }

            if ($group_name) {
                $plans[$plan_id]['exercises'][] = [
                    'group_name' => $group_name,
                    'exercise_name' => $row['exercise_name'],
                    'part_name' => $row['part_name'],
                    'description' => $row['description'],
                ];
            }
        }

        $username_query = "SELECT fullname FROM user WHERE user_id = $user_id;";

        $results_username = $mysqli->query($username_query);
        if (!$results_username) {
            echo "Error fetching username: " . $mysqli->error;
            exit();
        }

        $name_row = $results_username->fetch_assoc();

        $username = $name_row['fullname'];

    } else{
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="description" content="Review and manage your personalized workout plans. Explore exercises organized by fitness levels and muscle groups, and adjust or delete plans as needed to suit your needs.">
            <title>Workout Plan Page</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <link rel="stylesheet" href="shared_background.css">
            <style>
                #plans {
                    max-width: 75%;
                }
                .update-delete {
                    border-style: ridge;
                    padding-left: 5%;
                    padding-right: 5%;
                    padding-top: 2%;
                    margin-bottom: 2%;
                }
                #redirect-quote {
                    text-decoration: none;
                    color: #900;       
                }

                #wrong-gym{
                    max-width: 70%;
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
                                <li><a href="view_quotes_page.html">View Quotes</a></li>
                            </ul> <!-- navbar -->
                        </div>
                    </div><!--  row --> 
                </div> <!-- lookup -->
                <div id="sample-plan-layout" class=" mt-3 mx-5 px-5 text-center">
                    <div id="plans" class="text-center">
                        <img id="wrong-gym" src="img/no-user.jpg" alt="wrong gym">

                        <h4 class="pt-3">Oops! Looks like we can't view your plan right now.</h4>
                        <h4 class="pb-5">Please click <a id="redirect-quote" href="start-new-session.html">this link</a> so we can get you started!</h4>  
                    </div> <!-- plans -->             
                </div> <!-- sample plan layout -->
            </div> <!-- main -->
        </body>
        </html>
        HTML;
        exit();
    }

    $mysqli->close();

 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Review and manage your personalized workout plans. Explore  exercises organized by fitness levels and muscle groups, and adjust or delete plans as needed to suit your needs.">
    <title>Workout Plan Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="shared_background.css">
    <style>

        #plans{
            max-width: 75%;

        }

        .update-delete{
            border-style: ridge;
            padding-left: 5%;
            padding-right: 5%;
            padding-top: 2%;
            margin-bottom: 2%;
        }

        #redirect-quote1, #redirect-quote2{
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
                        <li><a href="view_quotes_page.html">View Quotes</a></li>
                    </ul> <!-- navbar -->
                </div>
            </div><!--  row --> 
        </div> <!-- lookup -->

        <div id="sample-plan-layout" class=" mt-3 mx-5 px-5">
          <!--   <h3 class="text-center mt-3">Sample Plan Layout</h3> -->

            <div id="plans">
                <div id="sample-plan">

                   <?php if (!empty($plans)): ?>
                   <?php $index = 1;  ?> 
                   <h4 class="text-center">Welcome <?php echo $username; ?>! </h4>
                    <?php foreach ($plans as $plan_id => $plan_data): ?>
                        <div class="update-delete d-flex">
                            <div class="plan">
                               <!--  <h4>Plan <?php echo $plan_id ?> - <?php echo $plan_data['level_name'] ?></h4> -->
                               <h4>Plan <?php echo "#" .  $index ?> - <?php echo $plan_data['level_name'] ?></h4>                   
                                <?php foreach ($plan_data['exercises'] as $exercise): ?>
                                    <div class="target-area">
                                        <h6>Target Area: <?php echo $exercise['group_name'] ?></h6>      
                                        <p>Focus: <?php echo $exercise['part_name'] ?> </p>
                                        <p><?php echo $exercise['exercise_name'] ?>: </p>
                                        <p><em> <?php echo $exercise['description'] ?></em></p>
                                    </div>
                                <?php endforeach; ?>

                            </div> <!-- plan -->   

                            <?php $index++; ?>


                            <div class=" ps-5 update">
                                <form action="update_plan.php" method="POST">
                                    <input type="hidden" name="plan_id" value=" <?php echo $plan_id; ?> "> 
                                    <input type="hidden" name="current_level_id" value="<?php echo $plan_data['level_id']; ?>"> 
                                    <button type="submit" name="update" class="btn btn-outline-secondary">Edit Level</button>  
                                     <select name="new_level_id" class="level-select">
                                    <option value="-1" disabled selected>-- Select --</option>
                                        <option value="1">Beginner</option>
                                        <option value="2">Intermediate</option>
                                        <option value="3">Expert</option>
                                    </select>                      
                                </form>
                            </div> <!-- update   -->    

                            <div class=" ms-2 delete">
                                <form action="delete_plan.php" method="POST">
                                    <input type="hidden" name="plan_id" value=" <?php echo $plan_id ?> ">
                                    <button type="submit" name="delete" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete current plan?')">Delete Plan</button>
                                </form>
                            </div> <!-- delete -->

                        </div> <!-- update-delete -->

                    <?php endforeach; ?>
                        <div>
                            <h4 class="text-center mb-5 pb-5">Look's great! You got this!</h4>
                        </div>                    
                <?php else: ?>
                    <h4 class="text-center">No plans found for <?php echo $username; ?> </h4>

                    <h4 class="text-center"><a id="redirect-quote1" href="user-submission-form.php">Click here</a> if you would look to create a new plan</h4>

                    <h4 class="text-center pb-5"> If you want to look up another user, <a id="redirect-quote2" href="start-new-session.html"> click here</a></h4>
                <?php endif; ?>
                    
                </div>  <!-- sample plan -->    
            </div> <!-- plans -->
                        
        </div> <!-- sample plan layout -->

    </div> <!-- main -->
</body>
</html>


