<?php 

class SystemTime{
	public static function getD($timestamp){ // getDate
		return strftime(date("d-m-Y", $timestamp));
	}
	
	public static function getT($timestamp){ // gets time
		return date("g:i a", $timestamp); 
	}
}