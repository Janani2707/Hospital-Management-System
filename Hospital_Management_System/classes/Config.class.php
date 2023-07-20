<?php 
// this class contains configuration data

class Config{

	// database

	const DB_HOST = "localhost"; // database host or server
	const DB_NAME = "hospital"; // the actual name of the system's database [database name]
	const DB_USER = "root"; // the actual name of database user
	const DB_PASSWORD = ""; // database password


	// SYSTEM 

	const SYSTEM_NAME = "EMR";
	const SLOGAN = "EMR"; // THIS CAN BE SYSTEM'S TITLE\ 


	//METHODS
	// kindly do not edit methods
	// they are constant methods that are used all over in the system
	// editing these methods may lead to false data due to misconfiguration.
	public static function redir($page){
		header("Location: $page"); 
	}

	public static function includeD(){

	}


	public static function getMonth(){
		// pleae do not edit this
		// this represents the number of seconds ni a month
		// editing this will lead to false values
		return 2419200;
	}


	public static function getWeek(){
		// pleae do not edit this
		// this represents the number of seconds ni a month
		// editing this will lead to false values
		return 604800;
	}



}