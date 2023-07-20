<?php 

require_once "importance.php"; 

if(isset($_GET['action'])){
	$action = $_GET['action'];
}

if(isset($_POST['action'])){
	$action = $_POST['action'];
}

if($action == "remove-doc"){
	$doc = $_GET['token'];
	Doctor::delete($doc);
	Config::redir("doctors-record.php?message=Doctor has been removed!"); 
}

if($action == "delete-outbreak"){
	$token = $_GET['token'];
	Outbreak::delete($token);
	Config::redir("outbreaks.php?message=OUtbreak has been deleted!"); 
}