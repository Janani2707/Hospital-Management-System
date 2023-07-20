<?php 
require_once "importance.php"; 

if(!User::loggedIn()){
	Config::redir("login.php"); 
}
?> 

<html>
<head>
	<title>HIV REPORTS<?php echo CONFIG::SYSTEM_NAME; ?></title>
	<?php require_once "inc/head.inc.php";  ?> 
</head>
<body>
	<?php require_once "inc/header.inc.php"; ?> 
	<div class='container-fluid'>
		<div class='row'>
			<div class='col-md-2'><?php require_once "inc/sidebar.inc.php"; ?></div> <!-- this should be a sidebar --> 
			<div class='col-md-10'>
				<div class='content-area'> 
				<div class='content-header'> 
					HIV Records <small>HIV Records</small>
				</div>
				<div class='content-body'> 
					<?php HIV::load(); ?>
				</div><!-- end of the content area --> 
				</div> 
				
			</div><!-- col-md-7 --> 

			
		</div> 
	</div> 
</body>
</html>
