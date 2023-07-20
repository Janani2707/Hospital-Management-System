<?php 
require_once "importance.php"; 

if(!User::loggedIn()){
	Config::redir("login.php"); 
}
?> 

<html>
<head>
	<title>Add Patients - <?php echo CONFIG::SYSTEM_NAME; ?></title>
	<?php require_once "inc/head.inc.php";  ?> 
	
	 
</head>
<body>
	<?php require_once "inc/header.inc.php"; ?> 
	<div class='container-fluid'>
		<div class='row'>
			<div class='col-md-2'><?php require_once "inc/sidebar.inc.php"; ?></div> <!-- this should be a sidebar --> 
			<div class='col-md-7'>
				<div class='content-area'> 
				<div class='content-header'> 
					Add Patient <small>New patient? Add them here</small>
				</div>
				<?php require_once "inc/alerts.inc.php";  ?> 
				<div class='content-body'>
					<div class='form-holder' style='margin-top: 50px;'>
					    <div class='badge-header'>Patient Details</div> 
						
						
					
						<?php
						
						if(isset($_GET['p-number'])){
							$patientNumber = $_GET['p-number'];
							echo "<h3>Patient Number: $patientNumber</h3>";
							
							$name = $_GET['name'];
							$location = $_GET['location'];
							$age = $_GET['age'];
							$gender = $_GET['gender'];
							$phone = $_GET['phone'];
							$dateOfBirth = $_GET['dateOfBirth'];
							
							$dataBirth = explode("-", $dateOfBirth);
							
							$dateOfBirth = preg_replace("#[^0-9-]#", "", $dataBirth[2]."-".$dataBirth[1]."-".$dataBirth[0]);
							
							$diagnosis = "";
							$prescription = "";
							$condition = "";
						} else {
							$patientNumber = substr(preg_replace("#[^0-9]#", "", md5(uniqid().time())), 0, 4);
							echo "<h3 style='color: #EF3235;'>Patient Number: <strong>$patientNumber</strong></h3>";
							$name = "";
							$location = "";
							$age = "";
							$gender = "";
							$phone = "";
							$dateOfBirth = "";
							$diagnosis = "";
							$prescription = "";
							$condition = "";
						}
						
						
						
						
						if(isset($_POST['p-name'])){
							$name = $_POST['p-name'];
							$location = $_POST['p-location']; 
							$age = $_POST['p-age']; 
							$phone = $_POST['p-phone'];
							$dateOfBirth = $_POST['p-birth'];
							$diagnosis = $_POST['p-diagnosis']; 
							$prescription = $_POST['p-prescription'];
							$gender = $_POST['gender']; 
							$condition = $_POST['condition'];
							
							Patient::add($name, $location, $age, $gender, $phone, $dateOfBirth, $diagnosis, $prescription, User::getToken(), $patientNumber, $condition); 
						}
						
						$form = new Form(3, "post");
						$form->init(); 
						$form->textBox("Full Name", "p-name", "text",  "$name", ""); 
						$form->textBox("Location", "p-location", "text",  "$location", "");
						$form->textBox("Age", "p-age", "number",  "$age", ""); 
						$form->textBox("Phone", "p-phone", "number",  "$phone", "");
						$form->textBox("Date of Birth", "p-birth", "date", "$dateOfBirth", "");	
						$form->textarea("Diagnosis/ Symptoms", "p-diagnosis", "$diagnosis");
						$form->textarea("Prescription", "p-prescription", "$prescription");
						$form->select("Gender", "gender", "$gender", array("Male", "Female", "Other") );
						$form->select("Condition", "condition", "$condition", array("Inpatient", "Outpatient") );
						$form->close("Submit and Print"); 
						
						
						?>
					</div> 
				</div><!-- end of the content area --> 
				</div> 
				
			</div><!-- col-md-7 --> 

			<div class='col-md-3'>
				<img src='images/doc-background-one.png' class='img-responsive' /> 
			</div> <!-- this should be a sidebar -->
				
		</div> 
	</div> 
</body>
</html>
