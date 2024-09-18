<?php
	require "config.php";

	// Establish MySQL Connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);	

	if( $mysqli->connect_errno) {
	echo $mysqli->connect_error;
	exit();
	}

	$mysqli->set_charset('utf8');

	$sql_level = "SELECT * FROM Level;";

	$results_level = $mysqli->query( $sql_level);

	if (!$results_level ){
		echo $mysqli->error;
		exit();
	}

	$sql_groups = "SELECT * FROM muscle_groups;";

	$results_groups = $mysqli->query( $sql_groups);
	if (!$results_groups ){
		echo $mysqli->error;
		exit();
	}


	$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Create your workout plan using this submission form. Specify your fitness level, target muscle groups, and personal information to get a plan that matches your fitness goals.">
	<title>Submission Form</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="shared_background.css">

	<style>
		#main-form{
			display: flex;
			justify-content: center;
			align-items: center;
		}

		#success-message{
			display: none;
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

				<div id="title" class="d-flex justify-content-center mb-5">
					<h2>Please fill out the form below</h2>
				</div> <!-- title -->

				<div id="main-form">			
				
				<form id="registration-form" onsubmit="return validateForm()">
						<div class="d-flex justify-content-center"> <!--  contains name and age -->

							<div class="row justify-content-center"> 

							  <div class="col-md-6"> 
							    <div id="name">
							      <small class="name-text form-text text-muted"> Enter your full name</small>
							   <!--    <label for="inputName" class="form-label">Name:</label> -->
							      <input type="text" name="name" class="form-control" id="inputName"> <!-- Corrected id -->
							      <small id="name-error" class="form-text text-danger"></small>
							    </div>
							  </div> <!-- Name column -->

							  <div class="col-md-6"> 
							    <div id="age">
							      <small class="age-text form-text text-muted"> Enter your age</small>
							    <!--   <label for="inputAge" class="form-label">Age:</label> -->
							      <input type="text" name="age" class="form-control" id="inputAge"> <!-- Corrected id -->
							      <small id="age-error" class="form-text text-danger"></small>
							    </div>
							  </div> <!-- Age column -->

							</div> <!-- End of row -->

 
						</div>  <!-- name & age -->



							<div class="row justify-content-center mt-4"> 

								  <!-- Email Section -->
								  <div class="col-md-6">
								    <div id="email">
								      <small class="email-text form-text text-muted">Enter your email address</small>
								 
								      <input type="email" name="email" class="form-control" id="inputEmail">
								      <small id="email-error" class="form-text text-danger"></small>
								    </div>
								  </div> <!-- Email column -->

								  <!-- Sex Section -->
								  <div class="col-md-6">
								    <div id="sex">
								      <small class="sex-text form-text text-muted">Select your sex</small>
								   
								      <select class="form-select" id="sexChoice" name="sexChoice">
								        <option value="-1" disabled selected>-- Select --</option>
								        <option value="M">Male</option>
								        <option value="F">Female</option>
								        <option value="N/A">N/A</option>
								      </select>
								      <small id="sex-error" class="form-text text-danger"></small>
								    </div>
								  </div> <!-- Sex column -->

								</div> <!-- End of the container row -->


						<div class="row justify-content-center mt-4"> 

						  <!-- Level of Difficulty Section -->
						  <div class="col-md-6">
						    <div id="level-of-difficulty">
						      <small class="level-text form-text text-muted">Choose the level you're currently at</small>
						      <select class="form-select" id="levelChoice" name="levelChoice">
						        <option value="-1" disabled selected>-- Select --</option>
						        <?php while ( $row = $results_level->fetch_assoc() ) : ?>
						        	<option value="<?php echo $row['level_id']; ?>">
						        		<?php echo $row['name']; ?>
						        	</option>
						        <?php endwhile; ?>
						      </select>
						      <small id="level-error" class="form-text text-danger"></small>
						    </div>
						  </div> <!-- Level of Difficulty column -->



						  <!-- Target Muscle Groups Section -->
						  <div class="col-md-6">
						    <div id="target-muscle-groups">
						      <small class="tmg-text form-text text-muted">Choose as many muscle groups as desired</small>
						      <div class="mt-2">
						        <div class="form-check">
						          <input class="form-check-input" type="checkbox" name="muscleGroup[]" id="arms" value="1">
						          <label class="form-check-label" for="arms">Arms</label>
						        </div>
						        <div class="form-check">
						          <input class="form-check-input" type="checkbox" name="muscleGroup[]" id="legs" value="2">
						          <label class="form-check-label" for="legs">Legs</label>
						        </div>
						        <div class="form-check">
						          <input class="form-check-input" type="checkbox" name="muscleGroup[]" id="chest" value="3">
						          <label class="form-check-label" for="chest">Chest</label>
						        </div>
						        <div class="form-check">
						          <input class="form-check-input" type="checkbox" name="muscleGroup[]" id="abdominals" value="4">
						          <label class="form-check-label" for="abdominals">Abdominals</label>
						        </div>
						        <div class="form-check">
						          <input class="form-check-input" type="checkbox" name="muscleGroup[]" id="back" value="5">
						          <label class="form-check-label" for="back">Back</label>
						        </div>
						        <small id="muscle-group-error" class="form-text text-danger" ></small>
						      </div> <!--  mt-2 -->
						    </div>
						  </div> <!-- Target Muscle Groups column -->

						</div> <!-- End of the container row -->


						<div class="mt-4 d-flex justify-content-center">
							<button type="submit" class="border border-none mb-4"> Submit Form </button>
						</div> 
						
				</form> <!-- registration-form -->
				</div>  <!-- main-form -->

				<div id="success-message" class="text-center">
					<h5>Form has been received!</h5>
				</div>  <!-- success message -->

				
			</div> <!-- row -->
		</div> <!-- lookup -->
	</div> <!-- main -->

	<script src="validateEmail.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<script>
		
			// validate form fuction with proper input fields
			function validateForm(){
				event.preventDefault()

				let isValid = true

				const name = document.querySelector("#inputName").value.trim()

				if (name.length === 0){
					isValid = false
					document.querySelector("#name-error").innerHTML = "Name cannot be empty"
				}
				else if (name.length === 1){
					isValid = false
					document.querySelector("#name-error").innerHTML = "Please enter your full name"
				}
				else if(/^[A-Z][a-z]+(?:\s[A-Z][a-z]+)*$/.test(name) == false){
					isValid = false
					document.querySelector("#name-error").innerHTML = "Please enter a valid full name"
				}

				else {
					document.querySelector("#name-error").innerHTML = ""
				}

				const age = document.querySelector("#inputAge").value.trim()

				if (age.length === 0 ){
					isValid = false
					document.querySelector("#age-error").innerHTML = "Age cannot be empty"
				}

				else if (age <= 15){
						isValid = false
						document.querySelector("#age-error").innerHTML = "User must be at least 15 years old or older"
				}

				else if(/^\d{2}$/.test(age) == false){
					isValid = false
					document.querySelector("#age-error").innerHTML = "Must be a valid integer input"
				}

				else {
					document.querySelector("#age-error").innerHTML = ""
				}

				const email = document.querySelector("#inputEmail").value.trim()
				if (email.length === 0){
					isValid = false
					document.querySelector("#email-error").innerHTML = "email cannot be empty"
				}

				else if(isValidEmail(email) == false){
					isValid = false
					document.querySelector("#email-error").innerHTML = "Invalid email"
				}

				else {
					document.querySelector("#email-error").innerHTML = ""
				}

				const sex = document.querySelector("#sexChoice")
				if (sex.disabled == false && sex.value == -1){
					isValid = false
					document.querySelector("#sex-error").innerHTML = "Please select choice"
				}
				else {
					document.querySelector("#sex-error").innerHTML = ""
				}


				const levelOfDifficulty = document.querySelector("#levelChoice")

				if(levelOfDifficulty.disabled == false && levelOfDifficulty.value == -1){
					isValid = false
					document.querySelector("#level-error").innerHTML = "Please select a choice from menu"
				}
				else{
					document.querySelector("#level-error").innerHTML = ""
				}

				if (document.querySelector("#arms").checked == false && document.querySelector("#legs").checked == false && document.querySelector("#chest").checked == false && document.querySelector("#abdominals").checked == false &&document.querySelector("#back").checked == false){
					document.querySelector("#muscle-group-error").innerHTML =
					" Please select at least one muscle group "
				}
				else{
					document.querySelector("#muscle-group-error").innerHTML =""
				}

				if(isValid){
					const data = new FormData(document.getElementById("registration-form"))

					$.ajax({
						url: "workout-form-confirmation.php",
						type: "POST",
						data: data,
						processData: false,
						contentType: false,
						success:function(data){
							console.log(data)
							document.querySelector("#success-message").style.display = "block";
						},
						error: function(xhr, status, error) {

							console.log("Error: unable to submit form", error)
						}

					})
				}

				return isValid



			}

	</script>

</body>
</html>