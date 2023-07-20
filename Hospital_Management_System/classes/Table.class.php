<?php 

class Table{
	
	public static function start(){
		echo "<table class='table table-bordered table-stripped' >"; 
	}
	
	public static function header(array $heading){
		$headingData = "<tr>";
		for($i = 0; $i < count($heading); $i++ ){
			$headingData .= "<td><strong>".$heading[$i]."</strong></td>";
		}
		$headingData .= "</tr>"; 
		
		echo $headingData;
	}
	
	public static function body(array $body){
		$bodyData = "<tr>";
		for($j = 0; $j < count($body); $j++ ){
			$bodyData .= "<td>".$body[$j]."</td>";
		}
		$bodyData .= "</tr>";
		
		echo $bodyData;
	}
	
	public static function create(array $heading, array $body){
		$headingData = "<tr>";
		for($i = 0; $i < count($heading); $i++ ){
			$headingData .= "<td><strong>".$heading[$i]."</strong></td>";
		}
		
		$headingData .= "</tr>"; 
		
		echo $headingData;
		
		$bodyData = "<tr>";
		for($j = 0; $j < count($body); $j++ ){
			$bodyData .= "<td>".$body[$j]."</td>";
		}
		$bodyData .= "</tr>";
		
		echo $bodyData;
	}
	
	public static function close(){
		echo "</table>"; 
	}
}