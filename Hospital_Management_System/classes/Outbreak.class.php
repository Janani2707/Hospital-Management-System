<?php 

class Outbreak{
	// this is if there is any outbreak
	public static function add($token, $outbreakDisease, $comments, $location, $measures){
		if($token == ""){
			$time = time(); 
			$token = md5(uniqid().time().unixtojd()); 
			Db::insert( "outbreaks",
				array("outBreak", "comments", "location", "cTime", "measures", "token"), 
				array($outbreakDisease, $comments, $location, $time,  $measures, $token)
			);
			
			Messages::success("Outbreak has been added doctors will be able to see it and take necessary measures");
		} else {
			self::edit($token, $outbreakDisease, $comments, $location, $measures);
			Messages::success("Outbreak has been edited. <strong><a href='outbreaks.php'>View edited record</a></strong>");
		}
	}
	
	public static function load(){
		$query = Db::fetch("outbreaks", "", "", "", "id DESC","", "");
		if(!Db::count($query)){
			Messages::info("There is no outbreaks recorded in the moment");
			return; 
		}
		
		echo "<div class='form-holder'>";
		Table::start();
		$header = array("Outbreak", "Comments", "Location", "Recorded", "Measures", "Action"); 
		$body = array(); 
		Table::header($header); 
		
		while($data = Db::assoc($query) ){
			Table::body(array($data['outBreak'], $data['comments'] , $data['location'], SystemTime::getD($data['cTime']), $data['measures'], "<center><a href='add-outbreak.php?token=".$data['token']."'>Edit</a></center>"));
			//array_push($body, );
		}
		
		//Table::create($header, $body);
		Table::close();
		echo "</div>";
	}
	
	public static function delete($token){
		Db::delete("outbreaks", " token = ? ", $token); 
	}
	
	public static function get($token, $field){
		$query = Db::fetch("outbreaks", "$field", "token = ? ", "$token", "id DESC","", "");
		$data = Db::num($query); 
		return $data[0];
	}
	
	public static function edit($token, $outbreakDisease, $comments, $location, $measures){
		Db::update("outbreaks", 
			array("outBreak", "comments", "location",  "measures"), 
			array($outbreakDisease, $comments, $location, $measures),
			"token = ? ", $token);
	}
	
}