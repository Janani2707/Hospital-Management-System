<?php 
require_once "importance.php"; 

if(!User::loggedIn()){
	Config::redir("login.php"); 
}
?> 

<html>
<head>
	<title>Add Doctors - <?php echo CONFIG::SYSTEM_NAME; ?></title>
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
					<?php if(isset($_GET['token'])){ echo "Edit Doctor <small>Edit this doctor</small>"; } else { ?> Add Doctors <small>Add doctors into the system</small> <?php } ?> 
				</div>
				<?php require_once "inc/alerts.inc.php";  ?> 
				<div class='content-body'> 
						
					<?php Messages::info("The default password is <strong>hospital</strong>"); ?> 
					<div class='form-holder'>
						<?php 
							$firstName = ""; 
							$secondName = "";
							$email = ""; 
							$phone = ""; 
							$role = ""; 
							$gender = ""; 
							$token = "";
							if(isset($_GET['token'])){
								$token = $_GET['token'];
								$firstName = User::get($token, "firstName"); 
								$secondName = User::get($token, "secondName");
								$email = User::get($token, "email"); 
								$phone = User::get($token, "phone"); 
								$role = User::get($token, "role"); 
								$gender = User::get($token, "gender"); 
							}
							if(isset($_POST['fn'])){
								$firstName = $_POST['fn']; 
								$secondName = $_POST['sn']; 
								$email = $_POST['em']; 
								$phone = $_POST['phone']; 
								$role = $_POST['role']; 
								if($token == ""){
									$gender = $_POST['gender']; 
								} else {
									$gender = "$gender"; 
								}
								
								if($firstName == "" || $secondName == "" || $email == "" || $phone == "" || $role == "" || $gender == ""){
									Messages::error("You must fill in all the fields"); 
								} else if (strlen($phone) != 10) {
									Messages::error("Phone must be 10 characters");
								} else if (strpos($email, "@") === false && strpos($email, ".")) {
									Messages::error("You entered invalid email. Email must be inform of example@example.com");
								} else {
									Doctor::add($token, $firstName, $secondName, $email, $phone, $gender, $role);
								}
								
								
							}
							
							$form = new Form(3, "post");
							$form->init(); 
							$form->textBox("First Name", "fn", "text", "$firstName", "");
							$form->textBox("Second Name", "sn", "text", "$secondName", "");
							$form->textBox("Email", "em", "text", "$email", "");
							$form->textBox("Phone", "phone", "number", "$phone", "");
							$form->textBox("Role e.g <i>Surgeon</i>", "role", "text", "$role", "");
							if(isset($_GET['token'] )){
								$form->textBox("Gender", "", "text", "$gender", array("disabled"));
							} else {
								$form->select("Gender", "gender", "", array("Male", "Female", "Other"));
							}
							if(isset($_GET['token'] )){
								$form->close("Edit Doctor"); 
							} else {
								$form->close("Add Doctor"); 
							}
							
						/*Db::formSpecial(
							array("First Name", "Second Name", "Email", "Phone", "Role e.g <i>Surgeon</i>"),
							3,
							array("fn", "sn", "em", "phone", "role"), 
							array("text", "text", "text", "number", "text"),  
							"", 
							"",  
							array("Gender"), 
							array("gender"), 
							array("Male", "Female", "Other"), 
							"Add Doctor") */?> 
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
