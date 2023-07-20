<?php 

// this is the important file in the system, 
// it initializes all the classes and objects of the system


ob_start();
@session_start();

// classes
function __autoload($file){
	require_once "classes/$file.class.php"; 
}

// functions

// get all information about this user

if(User::loggedIn()){
	$token = $_COOKIE['emr-user']; 
	$userFirstName = User::get($token, "firstName");
	$userSecondName = User::get($token, "secondName");
	$userEmail = User::get($token, "email");
	$userPassword = User::get($token, "password");
	$userToken = User::get($token, "token");
	$userStatus = User::get($token, "status");
	$userPhone = User::get($token, "phone");
	$userProfile = User::get($token, "profile");
	$userGender = User::get($token, "gender");
	$userRole = User::get($token, "role");
	
	if($userStatus == 1){
		$userRole = "Admin";
	} else {
		$userRole = $userRole;
	}
}

