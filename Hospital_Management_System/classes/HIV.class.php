<?php 

class HIV{
	public static function add($name, $age, $location, $MOD, $comments){
		$token = md5(uniqid().time().$name); 
		Db::insert("hiv", 
			array("name", "age", "location", "moc", "dComments", "cTime", "token"), 
			array($name, $age, $location, $MOD, $comments, time(), $token)
		);
		Messages::success("Data has been added, thanks");
	}
	
	public static function load(){
		$query = Db::fetch("hiv", "", "", "", "id DESC", "", "");

		if(!Db::count($query)){
			Messages::info("There is no HIV data in the moment.");
			return; 
		}
		echo "<div class='form-holder'>"; 
		Table::start();
		$header = array("Name", "Age", "Location", "Contracted Via", "Doctors Comments", "Recorded");
		$body = array();
		Table::header($header); 
		while($data = Db::assoc($query) ){
			$date = strftime(date("d-m-Y", $data['cTime']));
			/*array_push($body, $data['name'], 
						$data['age'], 
						$data['location'], 
						$data['moc'], 
						$data['dComments'], 
						$date); */
						
			Table::body(array($data['name'], 
						$data['age'], 
						$data['location'], 
						$data['moc'], 
						$data['dComments'], 
						$date)); 
		}
		
		//print_r($header);
		
		//Table::create($header, $body);
		Table::close();
		
		echo "</table>";
		
	}
}