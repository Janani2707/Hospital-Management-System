<?php 
require_once "importance.php"; 

if(!User::loggedIn()){
	Config::redir("login.php"); 
}
?> 

<html>
<head>
	<title><?php echo CONFIG::SYSTEM_NAME; ?></title>
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
					Outbreak <small>Record an outbreak</small>
				</div>
				<?php require_once "inc/alerts.inc.php";  ?> 
				<div class='content-body'> 
					<div class='form-holder'> <br />
						<?php 
							if(isset($_GET['token'])){
								$token = $_GET['token']; 
								$outbreak = Outbreak::get($token, "outBreak");
								$comments = Outbreak::get($token, "comments");
								$location = Outbreak::get($token, "location");
								$measures = Outbreak::get($token, "measures");
							} else {
								$token = ""; 
								$outbreak = "";
								$comments = ""; 
								$location = "";
								$measures = ""; 
							}
							
							
							if(isset($_POST['outbreak'])){
								$outbreak = $_POST['outbreak']; 
								$comments = $_POST['comments'];
								$location = $_POST['location']; 
								$measures = $_POST['measures']; 
								
								if($outbreak == "" || $comments == "" || $location == "" || $measures == ""){
									Messages::error("You must fill in all the fields");
								} else {
									Outbreak::add($token, $outbreak, $comments, $location, $measures);
								}
							}
							
							
							
							$form = new Form(2, "post"); 
							$form->init(); 
							$form->textBox("OutBreak", 'outbreak', 'text', "$outbreak", '');
							$form->textarea("Comments", "comments", "$comments");
							$form->textBox("Location", 'location', 'text', "$location", '');
							$form->textarea("Measures", "measures", "$measures");
							if($token == ""){
								$form->close("Add an outbreak");
							} else {
								$form->close("Edit Outbreak");
							}
							
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
