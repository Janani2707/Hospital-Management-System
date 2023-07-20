<?php 

/*
  This database class contains various db operations in the system .
  Development of this class was inspired by EMR project (presented by JOYCE NASHA)
  it contains complex structures that ease database operations in the whole system
  
  Copy Righted to MURANGA UNIVERSITY OF TECHNOLOGY
  Credits to: 
	TOTONA JOYCE NASHA - MUT STUDENT
					(BACHELOR OF BUSINESS INFORMATION TECHNOLOGY)
	
	GLORY BE TO GOD
*/
class Db{

	public static function returnedSQlError($e){
		// $e should be exception raised if the sql fails
		// you can edit this to decide what happens if anything goes wrong within your queries
		// this method should not return anything, it will be treated as void method
		// $e can be any message, four our case were are relying on default sql error behavior
		// you can use die($e->getMessage()); to get definite message
		die($e);
	}

	public static function connect(){

		try{
			$db = new PDO("mysql:host=".Config::DB_HOST.";dbname=".Config::DB_NAME,Config::DB_USER, Config::DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(Exception $e){
			self::returnedSQlError($e); 
		}

		return $db;// always connect to the database when necessary and kill the  connection when done please;
	
	}

	public static function insert($table, array $columns, array $colValues ){
		$con = self::connect(); 
		try{
			$test = "INSERT INTO $table("; 
				foreach ($columns as $key => $value) {
					
					if($key == (count($columns) - 1)){
						$test.= $value; 
					} else {
						$test.= $value.", "; 
					}
				}
			$test.=	") VALUES("; 
			    foreach ($columns as $key => $value) {
			    	if($key == (count($columns) - 1)){
						$test.= "?"; 
					} else {
						$test.= "?, "; 
					}
			    }
			$test.= ") ";

             $query = $con->prepare($test);

            $paramsCount = count($colValues); 

            $start = 0; 
			while($start < $paramsCount){
				$keyData = $start +1; 
				$lastKey = count($colValues) + 1; 

				if($start == count($colValues)){
					$query->bindParam($lastKey, $colValues[$start]);
				} else {
					$query->bindParam($keyData, $colValues[$start]);
				}

				$start ++; 
			} 


			$query->execute(); 
			return 1; // this means the data has been updated
		} catch(Exception $e){
			die($e->getMessage()); 
		}
	}


	public static function update($table, array $columns,  array $colValues, $where, $whereValue){
		// whereValue can take an array if where clause has more than one data
        // Db::update("test", array("email", "name", "location"), array("bensonkarue30@gmail.com.com", "benson", "karatina"), "hotelId = ? ", "hotelId"); 
        // update table set col1 = 2, col2 = 3, col4 =5 WHERE ( WHERE  some = ? and another some  = ? )
        $con = Db::connect(); 
        try{

			$sql = "UPDATE $table SET "; 
			     foreach ($columns as $key => $data) {
			     	if($key == (count($columns)- 1)){
			     		$sql.= " $data = ? "; 
			     	} else {
			     		$sql.= " $data = ?, "; 
			     	}
			     } 
			$sql.= "WHERE ";

			$sql.= $where;

			$query = $con->prepare($sql); 

			// we can use our sql a query data

			if(!is_array($whereValue)){

				$paramsCount = count($columns) + 1; // updating where there is only one condition 


				$start = 0; 
				while($start < $paramsCount){
					$keyData = $start +1; 
					$lastKey = count($colValues) + 1; 

					if($start == count($colValues)){
						$query->bindParam($lastKey, $whereValue);
					} else {
						$query->bindParam($keyData, $colValues[$start]);
					}

					$start ++; 
				} 

			} else {
				$start = 0; 
				$paramsCount = count($whereValue) + count($colValues); 

				while($start < $paramsCount){
					$keyData = $start + 1;
					if($keyData <= count($colValues)){
						// here we should first call our values
						$query->bindParam($keyData, $colValues[$start]);
					} else {
						$newKey = $start - count($colValues);
						$query->bindParam($keyData, $whereValue[$newKey]);
					}

					$start ++;
				}
			}

			$query->execute(); 

			return 1; // this means the data has been updated;



        } catch(Exception $e){
        	die($e->getMessage());
        } 
	}

	public static function delete($table, $where, $whereValue){
		// Db::fetchAll("tableName", "col = ? ", "id DESC");
		// this just gives you flexibility
		// if where clause contains more than one condition, then where values must be an array
		// check this
		$con = self::connect();
		try{
			
			if($where != ""){
				$query = $con->prepare("DELETE FROM $table WHERE $where ");
				if(is_array($whereValue)){
					//echo "it's array";
					$start = 0; 
					while($start < count($whereValue)){
						$paramKey = $start + 1; 
						$query->bindParam($paramKey, $whereValue[$start]); 
						$start ++;
					}
				} else {
					//echo "its not array";
					$query->bindParam(1, $whereValue);
				}
			} else {
				// delete from the full table
				$query = $con->prepare("DELETE FROM $table ");
			}
			$query->execute(); 
		} catch(Exception $e){
			die($e->getMessage()); 
		}
	}


	// this method (Db::fetchAll(params)) is deprecated, use Db::fetch(params) instead
	public static function fetchAll($table, $where, $whereValue, $order,$limit){
		// Db::fetchAll("tableName", "col = ? ", "id DESC");

		$con = self::connect();
		if($limit == ""){
			$limit = ""; 
		} else {
			$limit = " LIMIT $limit "; 

		}


		if($order == ""){
			$order = "";
		} else {
			$order = "ORDER BY $order"; 
		}
		try{
			
			if($where != ""){
				$query = $con->prepare("SELECT * FROM $table WHERE $where  $order  $limit ");
				if(!is_array($whereValue)){
					$query->bindParam(1, $whereValue);
				} else {
					$count = count($whereValue); 
					$start = 0; 
					while($start < $count){

						$paramKey = $start + 1; 

						$query->bindParam($paramKey, $whereValue[$start]);

						$start ++;
					}
				}
			}  else {
				$query = $con->prepare("SELECT * FROM $table  $order  $limit");
			}
			$query->execute(); 

			return $query; 
		} catch(Exception $e){
			die($e); 
		}
	}


	// this method (Db::fetchSpecial(params)) is deprecated, use Db::fetch(params) instead
	public static function fetchSpecial($table, $where, $whereValue, $groupBy, $order,$limit){
		// Db::fetchAll("tableName", "col = ? ", "id DESC");
		if($limit == ""){
			$limit = ""; 
		} else {
			$limit = " LIMIT $limit "; 
		}
		if($order == ""){
			$order = "";
		} else {
			$order = "ORDER BY $order"; 
		}
		if($groupBy == ""){
			$groupBy = "";
		} else {
			$groupBy = "GROUP BY $groupBy"; 
		}
		$con = self::connect();
		try{
			
			if($where != ""){
				$query = $con->prepare("SELECT * FROM $table WHERE $where $groupBy  $order $limit ");
				if(!is_array($whereValue)){
					$query->bindParam(1, $whereValue);
				} else {
					$count = count($whereValue); 
					$start = 0; 
					while($start < $count){
						$paramKey = $start + 1; 
						$query->bindParam($paramKey, $whereValue[$start]);
						$start ++;
					}
				}
			}  else {
				$query = $con->prepare("SELECT * FROM $table $groupBy  $order $limit");
			}
			$query->execute(); 
			return $query; 
		} catch(Exception $e){
			die($e); 
		}
	}


	// this method (Db::fetchCols(params)) is deprecated, use Db::fetch(params) instead
	public static function fetchCols($table, array $cols,  $where, $whereValue, $order,$limit){
		// Db::fetchAll("tableName", "col = ? ", "id DESC");
		$con = self::connect();
		if($limit == ""){
			$limit = ""; 
		} else {
			$limit = " LIMIT $limit "; 
		}
		if($order == ""){
			$order = "";
		} else {
			$order = "ORDER BY $order"; 
		}
		try{ 
			
			if($where != ""){
				$sql ="SELECT "; 

				foreach ($cols as $key => $value) {
					$lastCol = count($cols) - 1;  
					if($key == $lastCol){
						$sql .= $value;
					} else {
						$sql .= $value+", "; 
					}
				}
				 
				$sql .=  " FROM $table WHERE $where  $order  $limit "; 
				$query = $con->prepare($sql);
				if(!is_array($whereValue)){
					$query->bindParam(1, $whereValue);
				} else {
					$count = count($whereValue); 
					$start = 0; 
					while($start < $count){

						$paramKey = $start + 1; 

						$query->bindParam($paramKey, $whereValue[$start]);

						$start ++;
					}
				}
			}  else {
				$sql ="SELECT "; 

				foreach ($cols as $key => $value) {
					$lastCol = count($cols) - 1;  
					if($key == $lastCol){
						$sql .= $value;
					} else {
						$sql .= $value+", "; 
					}
				}
				 
				$sql .=  " FROM $table  $order $limit "; 
				$query = $con->prepare($sql);
			}
			$query->execute(); 

			return $query; 
		} catch(Exception $e){
			die($e); 
		}
	}


	// fetch method should be used instead of fetchSpeical, fetchAll and fetchCols. 
	// fetch is the valid function that does the work of 'fetchSpecial', 'fetchAll', and 'fetchCols', 
	// always use this method when fetching anything from your database
	public static function fetch($table, $columns,  $whereClause, $whereValue, $orderBy, $limit, $groupBy){
		if($limit == ""){
			$limit = ""; 
		} else {
			$limit = " LIMIT $limit "; 
		}
		if($orderBy == ""){
			$orderBy = "";
		} else {
			$orderBy = " ORDER BY $orderBy "; 
		}
		if($groupBy == ""){
			$groupBy = "";
		} else {
			$groupBy = "GROUP BY $groupBy"; 
		}
		$con = self::connect(); 
		if($columns == ""){
			// select * from table where (email = ? AND data = ? or username = ? )
			$query = $con->prepare("SELECT * FROM $table WHERE $whereClause $groupBy $orderBy $limit");
			if($whereValue != ""){
				if(is_array($whereValue)){
					$n = 0; 
					$countWhereValue = count($whereValue);
					while($n < $countWhereValue){
						$paramsCount = $n + 1; 
						$query->bindParam($paramsCount, $whereValue[$n]);
						$n++;
					}
				} else {
					$query->bindParam(1, $whereValue); 
				}
			} else {
				$query = $con->prepare("SELECT * FROM $table  $groupBy $orderBy  $limit");
			}
			
			
		} else {
			// $query = $con->prepare("SELECT email, password.. FROM table $whereClause")
			// here means the columns are in array
			$sql = "SELECT "; 
			if(is_array($columns)){
				$colsCount = count($columns); 
				$start = 0; 
				while($start < $colsCount){
					$commas = $start + 1; 
					if($commas == $colsCount){
						$sql .= $columns[$start]." ";
					} else {
						$sql .= $columns[$start].", "; 
					}
					$start ++; 
				}
			} else {
				$sql .= "$columns ";
			}
			
			if($whereValue != ""){
				$sql .= "FROM $table WHERE $whereClause $groupBy $orderBy $limit"; 
				$query = $con->prepare($sql); 
				if(is_array($whereValue)){
					$n = 0; 
					$countWhereValue = count($whereValue);
					while($n < $countWhereValue){
						$paramsCount = $n + 1; 
						$query->bindParam($paramsCount, $whereValue[$n]);
						$n++;
					}
				} else {
					$query->bindParam(1, $whereValue); 
				}	
			} else {
				$sql .= "FROM $table $groupBy $orderBy $limit"; 
				$query = $con->prepare($sql); 
				//$query = $con->prepare("SELECT * FROM $table  $groupBy $orderBy  $limit");
			}
		}
		$query->execute(); 
		return $query; 
	}
	
	public static function table($table, array $cols){
		// coming soon
	}


	public static function count($query){
		return $query->rowCount();
	}

	public static function assoc($query){
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public static function num($query){
		return $query->fetch(PDO::FETCH_NUM);
	}

	public static function getCount($table, $where, $whereValue, $order, $limit){
		return self::fetchAll($table, $where, $whereValue, $order, $limit)->rowCount();
	}



	public static function get($table, $colToken, $tokenValue, $field){
		$con = Db::connect(); 
		try{
			$query = $con->prepare("SELECT $field FROM $table WHERE $colToken  ");
			$query->bindParam(1, $tokenValue);
			$query->execute();
			if($query->rowCount()){
				$data = $query->fetch(PDO::FETCH_NUM);
				return $data[0];
			}
		} catch(Exception $e){
			die($e->getMessage());
		}
	}

	
	// other important functions that can potentially ease your work


	//user interface

	public static function form(array $label, $labelDistance, array $name, array $type, $submit){
		// input is for example, name='email' type='text '

		$form = "<form method='post' class='form-horizontal' action='' style='margin-top: 20px;'>";
		$startForm = 0; 
		$totalFields = count($name); 
		while($startForm < $totalFields){
			$inputDistance = 12 - (int)$labelDistance; 
			$form .="<div class='form-group'>";
			$form .="<label class='col-md-$labelDistance'>".$label[$startForm]."</label>";
			$form .="<div class='col-md-$inputDistance'><input type='".$type[$startForm]."' name='".$name[$startForm]."' class='form-control'/></div>";
			$form .="</div>";

			$startForm ++;
		}

		$form .= "<div class='form-group'>
					<div class='col-md-$labelDistance'></div> 
					<div class='col-md-$inputDistance'> 
						<input type='submit' value='$submit' class='btn btn-primary' />
					</div> 
		          </div> "; 
		$form .= "</form>"; 

		echo $form; 

	}
	
	
	
	public static function formSpecial($label, $labelDistance,  $name, $type,  $textAreaLabel, $textAreaName,  $selectLabel, $select, $selectOptions,  $submit){
		// input is for example, name='email' type='text '

		$form = "<form method='post' class='form-horizontal' action='' style='margin-top: 20px;'>";
		$startForm = 0; 
		$totalFields = count($name); 
		while($startForm < $totalFields){
			$inputDistance = 12 - (int)$labelDistance; 
			$form .="<div class='form-group'>";
			$form .="<label class='col-md-$labelDistance'>".$label[$startForm]."</label>";
			$form .="<div class='col-md-$inputDistance'><input type='".$type[$startForm]."' name='".$name[$startForm]."' class='form-control'/></div>";
			$form .="</div>";

			$startForm ++;
		}
		
		if($textAreaLabel == "" || $textAreaLabel == null){
			$form .= "";
		} else {
			$startTextArea = 0; 
			
			while($startTextArea < count($textAreaLabel)){
				
				$form .="<div class='form-group'>";
				$form .="<label class='col-md-$labelDistance'>".$textAreaLabel[$startTextArea]."</label>";
				$form .="<div class='col-md-$inputDistance'><textarea name='".$textAreaName[$startTextArea]."' class='form-control'></textarea></div>";
				$form .="</div>";
				
				$startTextArea ++; 
			}
		}
		
		if($select == "" || $select == null){
			$form .= "";
		} else {
			$startSelect = 0; 
			
			while($startSelect < count($selectLabel)){
				
				$form .="<div class='form-group'>";
				$form .="<label class='col-md-$labelDistance'>".$selectLabel[$startSelect]."</label>";
				$form .="<div class='col-md-$inputDistance'><select name='".$select[$startSelect]."' class='form-control'><option value=''>--Select--</option>";
								
								$startOptions = 0; 
								while($startOptions < count($selectOptions)){
									$form .= "<option value='".$selectOptions[$startOptions]."'>".$selectOptions[$startOptions]."</option>";
									$startOptions ++; 
								}
								
				$form .="</select></div>";
				$form .="</div>";
				
				$startSelect ++; 
			}
		}
		
		

		$form .= "<div class='form-group'><div class='col-md-$labelDistance'></div><div class='col-md-$inputDistance'><input type='submit' value='$submit' class='btn btn-primary' /></div></div> "; 
		$form .= "</form>"; 

		echo $form; 

	}

	
}