<?php 
require_once "importance.php"; 

if(!User::loggedIn()){
	Config::redir("login.php"); 
}
?> 

<html>
<head>
	<title><?php echo CONFIG::SYSTEM_NAME; ?> | Home </title>
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
					Dashboard <small>View your dashboard</small>
				</div>
				<div class='content-body'>
					<div class='row'>
						<?php Dashboard::draw("Outbreaks", Dashboard::outbreaks(),  "outbreaks.php") ?>
						<?php if($userStatus == 1){ Dashboard::draw("Doctors", Dashboard::doctors(),  "doctors-record.php"); } ?>
						<?php Dashboard::draw("Patients", Dashboard::patients(),  "patients.php") ?>
						<?php Dashboard::draw("Patient Book", Dashboard::getPatientRecords(),  "patients.php") ?>
						<?php Dashboard::draw("Appointments", Dashboard::Appointments(),  "appointments.php") ?>
						<?php Dashboard::draw("Replied Appts.", Dashboard::repliedAppointMents(),  "appointments.php") ?>
						<?php Dashboard::draw("HIV Record", Dashboard::hivPatients(),  "hiv.php") ?>
						<?php Dashboard::draw("Change Password", "",  "change-password.php"); ?>
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
