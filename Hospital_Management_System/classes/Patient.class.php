<?php 

class Patient{
	public static function add($name, $location, $age, $gender, $phone, $dateOfBirth, $diagnosis, $prescriptions, $doctor, $number, $condition){
		
		
		if($name == "" || $location == "" || $age == "" || $gender == "" || $phone == "" || $dateOfBirth == "" || $diagnosis == "" || $prescriptions == "" || $condition == ""){
			Messages::error("All fields are required"); 
			return; 
		}
		
		if(strlen($phone) != 10){
			Messages::error("Please note phone must be inform of 0712345678, i.e ten digits");
			return;
		}
		
		
		$today = strftime(date("d-m-Y", time()));
		$todayData = explode("-", $today);

		$dataYear = explode("-", $dateOfBirth); 
		$correctAge = (int) $todayData[2] -  $dataYear[0];
		
		$correctDateOfBirth = $dataYear[2]." - ".$dataYear[1]." - ".$dataYear[0];
		
		if($age != $correctAge){
			Messages::error("You have indicated that patient was born $correctDateOfBirth, therefore they cannot be of $age YRS, the correct age should be $correctAge YRS. (".$todayData[2]." - ".$dataYear[0]." = $correctAge YRS). Please correct the details ");
			return; 
		}
		
		$time = time(); 
		
		$patientToken = md5(uniqid().time().unixtojd().$name.$age.$phone); 
		
		$diagnosis = str_replace("\n", "<br />", $diagnosis); 
		$prescriptions = str_replace("\n", "<br />", $prescriptions);
		
		Db::insert("patients", 
				array("name", "location", "age", "gender", "phone", "dateOfBirth", "cTime", "diagnosis", "prescription", "token", "doctor", "number", "pcondition"), 
				array($name, $location, $correctAge, $gender, $phone, $correctDateOfBirth, $time, $diagnosis, $prescriptions, $patientToken, $doctor, $number, $condition )
		); 
		
		Config::redir("print.php?patient=$patientToken"); 
		
	}
	
	public static function get($token, $field){
		$query = Db::fetch("patients", "$field", "token = ? ", $token, "", "", ""); 
		if(Db::count($query)){
			$data = Db::num($query); 
			return $data[0];
		}
		
		Messages::error("Invalid patient token!"); 
	}
	
	public static function getP($number, $field){
		$query = Db::fetch("patients", "$field", "number = ? ", $number, "", "", 1); 
		if(Db::count($query)){
			$data = Db::num($query); 
			return $data[0];
		}
		
		Messages::error("Invalid patient token!"); 
	}
	
	public static function printP($token){
		$name = self::get($token, "name");
		$location = self::get($token, "location"); 
		$age = self::get($token, "age"); 
		$phone = self::get($token, "phone"); 
		$dateOfBirth = self::get($token, "dateOfBirth"); 
		$time = self::get($token, "cTime"); 
		$diagnosis = self::get($token, "diagnosis"); 
		$prescription = self::get($token, "prescription");
		$date = strftime(date("d/m/Y", $time));
		$doctor = self::get($token, "doctor");
		$number = self::get($token, "number");
		
		
		echo "
			<div class='badge-header print-p-data' style='cursor: pointer;'>Print</div>
			<div class='print-data'>
				<div class='form-holder' style='background:#fff;'>
					
					<div class='row'>
						<div class='col-md-7 p-data'><div class='p-date'>$date</div></div>
						<div class='col-md-5 p-data'>
							<div><strong>Patient No:</strong> <span>$number</span></div>
							<div><strong>Name:</strong> <span>$name</span></div>
							<div><strong>Age:</strong> <span>$age</span></div>
							<div><strong>Contact:</strong> <span>$phone</span></div>
							<div><strong>Location:</strong> <span>$location</span></div>
						</div>
					</div><br /> 
					
					<div class='row'>
						<div class='col-md-7 p-ref'>
							<div>DETAILS</div>
							$diagnosis
						</div>
						
					</div>
					
					
					<div class='row'>
						<div class='col-md-7 p-ref'>
							<div>PRESCRIPTIONS</div>
							$prescription
						</div>
						
					</div><br />
					
					
					<div class='row'>
						<div class='col-md-7'>Murang'a Referral Hospital</div>
						<div class='col-md-5'>
							<strong>Served By: </strong> <span class='service-name'> ".User::get($doctor, "firstName")." ".User::get($doctor, "secondName")."</span> <span class='service-title'><i>".User::get($doctor, "role")."</i></span>
						</div>
						
					</div>
					
					
				</div> 
			</div> 
		";
	}
	
	
	public static function patientsBooks(){
		
		$query = Db::fetch("patients", "", "", "", "id DESC", "", ""); 
		
		if(Db::count($query)){
			if(Db::count($query) == 1){
				$countP = Db::count($query)." Record";
			} else {
				$countP = Db::count($query)." Records";
			}
			echo "<div class='badge-header'>$countP</div>"; 
			
			
			echo "<div class='form-holder'><table class='table table-bordered'>";
			
		    echo "
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Location</strong></td>
						<td><strong>Age</strong></td>
						<td><strong>Attended</strong></td>
						<td><strong>Doctor</strong></td>
						<td><strong>Print</strong></td>
						
					<tr>
			"; 
			while($data = Db::assoc($query)){
				$token = $data['token'];
				$name = self::get($token, "name");
				$location = self::get($token, "location"); 
				$age = self::get($token, "age"); 
				$phone = self::get($token, "phone"); 
				$dateOfBirth = self::get($token, "dateOfBirth"); 
				$time = self::get($token, "cTime"); 
				$diagnosis = self::get($token, "diagnosis"); 
				$prescription = self::get($token, "prescription");
				$date = strftime(date("d/m/Y", $time));
				$doctor = self::get($token, "doctor");
				$docName = User::get($doctor, "firstName")." ".User::get($doctor, "secondName");
				
				
				echo "
					<tr>
						<td>$name</td>
						<td>$location</td>
						<td>$age</td>
						<td>$date</td>
						<td>$docName</td>
						<td><a href='print.php?patient=$token'>Print</a></td>
						
					<tr>
			"; 
			}
			
			echo "</table></div>";
		} else {
			Messages::info("There is no record in the moment"); 
		}
	}
	
	
	public static function checkPatient($number){
		$query = Db::fetch("patients", "", "number =? ", $number, "", "", 1); 
		if(!Db::count($query)){
			Messages::error("This number does not exist in the system"); 
			return;
		}
		
		if($number == "" || strpos($number, " ") === true ){
			Messages::error("You must provide a number"); 
			return;
		}
		
		// user basic details
		$data = Db::assoc($query);
		
		$name = $data['name']; 
		$location = $data['location'];
		$age = $data['age']; 
		$phone = $data['phone'];
		$dateOfBirth = $data['dateOfBirth'];
		$gender = $data['gender']; 
		
		Config::redir("add-patient.php?p-number=$number&name=$name&location=$location&age=$age&phone=$phone&dateOfBirth=$dateOfBirth&gender=$gender");
		
	}
	
	
	public static function authorize($phone, $number){
		$query = Db::fetch("patients", "", "phone = ? AND number = ? ", array($phone, $number), "", "", 1); 
		if(!Db::count($query)){
			Messages::error("Details you entered could not match any of our records");
			return; 
		}
		
		if($phone == "" || $number == "" || strpos($phone, " ") === true || strpos($number, " ")  == true ){
			Messages::error("Details entered are not valid. Make sure you fill in all the fields or check for white spaces");
			return; 
		}
		
		@session_start();
		
		$_SESSION['patient'] = $number;
		
		Config::redir("patient-data.php");
		
	}
	
	public static function getPatientData($patient){
		$query = Db::fetch("patients", "", "number = ? ", "$patient", "", "", ""); 
		if(!Db::count($query)){
			Messages::info("You currently have not data in the system");
			return; 
		}
		
		Table::start();
		
		$heading = array("Name", "Location", "Age", "Phone", "Date of Birth", "Served On:", "Diagnosis", "Prescriptions", "Served By", "Print");
		$body = array();
		Table::header($heading); 
		
	    while($data = Db::assoc($query)){
			$token = $data['token']; 
			$name = self::get($token, "name");
			$location = self::get($token, "location"); 
			$age = self::get($token, "age"); 
			$phone = self::get($token, "phone"); 
			$dateOfBirth = self::get($token, "dateOfBirth"); 
			$time = self::get($token, "cTime"); 
			$diagnosis = self::get($token, "diagnosis"); 
			$prescription = self::get($token, "prescription");
			$date = strftime(date("d/m/Y", $time));
			$doctor = self::get($token, "doctor");
			
			$doctorFirstName = User::get($doctor, "firstName"); 
			$doctorSecondName = User::get($doctor, "secondName");
			$servedBy = "Served by $doctorFirstName $doctorSecondName";
			Table::body(array($name, $location, $age, $phone, $dateOfBirth, $date, $diagnosis, $prescription, $servedBy, "<a href='print.php?patient=$token'>Print & Download</a>"));
			//array_push($body, ); 
			
		}
		//Table::create($heading, $body); 
		Table::close();
		
		
	}
	
	public static  function isPatientIn(){
		if(isset($_SESSION['patient'])){
			return true; 
		}
		return; 
	}
	
}