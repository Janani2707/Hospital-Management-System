<?php 

class Appointment{
	public static function send($name, $number, $phone, $message, $doctor){
		if(Patient::isPatientIn()){
			$time = time(); 
			Db::insert("appointment", 
				array("name", "fromm", "phone", "message", "too", "cTime"), 
				array($name, $number, $phone, $message, $doctor, $time)
			);
			Messages::success("Your appointment has been sent");
			return; 
		} 
		Messages::error("You must be logged in");
	}
	
	public static function loadPatientSentAppointments(){
		
		if(Patient::isPatientIn()){
			$query = Db::fetch("appointment", "", "fromm = ?", $_SESSION['patient'], "", "", "" );
			if(!Db::count($query)){
				Messages::info("You currently have no sent or booked appointments");
				return; 
			}
			
			echo "<div class='form-holder'>";
			Table::start(); 
			$header = array("Doctor", "Message"); 
			Table::header($header);
			while($data = Db::assoc($query) ){
				$docToken = $data['too']; 
				$doctorName = User::get($docToken, "firstName")." ".User::get($docToken, "secondName");
				Table::body(array($doctorName, $data['message']));
			}
			//Table::create($header, $body);
			Table::close();
			echo "</div>"; 
			return; 
		} 
		Messages::error("You must be logged in");
	}
	
	
	public static function loadPatientRepliedAppointment(){
		
		if(Patient::isPatientIn()){
			$query = Db::fetch("appointment", "", "too = ?", $_SESSION['patient'], "", "", "" );
			if(!Db::count($query)){
				Messages::info("You currently have no received or booked appointments");
				return; 
			}
			
			echo "<div class='form-holder'>";
			Table::start(); 
			$header = array("Doctor", "Message"); 
			Table::header($header);
			while($data = Db::assoc($query) ){
				$docToken = $data['fromm']; 
				$doctorName = User::get($docToken, "firstName")." ".User::get($docToken, "secondName");
				//array_push($body,);
				Table::body(array( $doctorName, $data['message']));
			}
			
			Table::close();
			echo "</div>"; 
			return; 
		} 
		Messages::error("You must be logged in");
	}
	
	public static function loadDoctorAppointMents(){
		if(User::loggedIn()){
			$query = Db::fetch("appointment", "", "too = ?", User::getToken(), "", "", "" );
			if(!Db::count($query)){
				Messages::info("You currently have no received or booked appointments");
				return; 
			}
			
			echo "<div class='form-holder'>";
			Table::start(); 
			$header = array("Patient Number", "Name",  "Message", "Action"); 
			$body = array();
			Table::header($header); 
			while($data = Db::assoc($query) ){
				$number = $data['fromm']; 
				//array_push($body, ");
				Table::body(array($number, $data['name'], $data['message'], "<center><a href='reply-appointment.php?patient=$number'>Reply</a></center>")); 
			}
			
			Table::close();
			echo "</div>"; 
			return; 
		} 
		Messages::error("You must be logged in");
	}
	
	public static function loadDoctorRepliedAppointMents(){
		if(User::loggedIn()){
			$query = Db::fetch("appointment", "", "fromm = ?", User::getToken(), "", "", "" );
			if(!Db::count($query)){
				Messages::info("You've not replied to nay appointment in the moment");
				return; 
			}
			
			echo "<div class='form-holder'>";
			Table::start(); 
			$header = array("Patient Number", "Name",  "Message"); 
			$body = array();
			Table::header($header); 
			while($data = Db::assoc($query) ){
				$number = $data['too']; 
				//array_push($body, );
				Table::body(array($number, $data['name'], $data['message']));
			}
			//Table::create($header, $body);
			Table::close();
			echo "</div>"; 
			return; 
		} 
		Messages::error("You must be logged in");
	}
	
	public static function reply($message, $number){
		if(!User::loggedIn()){
			Messages::error("You must be logged in to complete this action");
			return; 
		}
		if($message == "" || $number == ""){
			Messages::error("Please enter a message");
			return; 
		}
		
		$time = time(); 
		$name = "Doctor";
		$phone = "0725895256"; 
		
		Db::insert("appointment", 
			array("name", "fromm", "phone", "message", "too", "cTime"), 
			array($name, User::getToken(), $phone, $message, $number, time() )
		);
		Messages::success("Reply sent");
		
	}
	
	
	
}