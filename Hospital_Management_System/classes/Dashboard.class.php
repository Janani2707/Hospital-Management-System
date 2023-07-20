<?php 

class Dashboard extends DashboardUi{ // using inheritance
	public static function getPatientRecords(){
		$query = Db::fetch("patients", "", "", "", "", "", "");
		return Db::count($query);
	}
	
	public static function patients(){
		$query = Db::fetch("patients", "", "", "", "", "", "number");
		return Db::count($query);
	}
	
	public static function Appointments(){
		//array_push()
		$query = Db::fetch("appointment", "", "too = ? ", User::getToken(), "", "", "");
		return Db::count($query);
	}
	
	public static function repliedAppointMents(){
		$query = Db::fetch("appointment", "", "fromm = ? ", User::getToken(), "", "", "");
		return Db::count($query); 
	}
	
	public static function hivPatients(){
		$query = Db::fetch("hiv", "", "", "", "", "",  "");
		return Db::count($query);
	}
	
	public static function doctors(){
		$query = Db::fetch("users", "", "status = ? ", "2", "", "",  "");
		return Db::count($query);
	}
	
	public static function outbreaks(){
		$query = Db::fetch("outbreaks", "", "", "", "", "",  "");
		return Db::count($query);
	}
	
	
}