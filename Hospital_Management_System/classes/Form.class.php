<?php 

class Form{
	// $form = new Form(3, "post"); 
	// $form->init();
	// $form->textBox("First Name", "fn"); 
	
	private $_labelDistance; 
	private $_method; 
	
	public function __construct($labelDistance, $method){
		$this->_labelDistance = $labelDistance;
		$this->_method = $method; 
	}
	
	public function init(){
			echo "<form class='form-horizontal' method='".$this->_method."' action='' >"; 
	}
	
	private function getDivDistance(){
		$divDistance = 12 - (int)$this->_labelDistance;
		return $divDistance;
	}
	
	public function textBox($label, $name, $type,  $value,  $additinalAttr){/* $additinalAttr: it can take an empty string or an array */
		if($additinalAttr == ""){
			echo "
			<div class='form-group'>
				<label class='col-md-".$this->_labelDistance."'>$label</label>
				<div class='col-md-".$this->getDivDistance()."'> 
					<input type='$type' name='$name' value='$value' class='form-control' /> 
				</div> 
			</div> 
		";
		} else {
			$formData = "
			<div class='form-group'>
				<label class='col-md-".$this->_labelDistance."'>$label</label>
				<div class='col-md-".$this->getDivDistance()."'> 
					<input type='$type' name='$name' value='$value' class='form-control' "; 
					$start = 0; 
					
					while($start < count($additinalAttr)){
						$formData .= $additinalAttr[$start]." ";
						$start ++; 
					}
					
			$formData .= " /></div> 
			</div> 
			
		";
		
		echo $formData; 
		}
		
	}
	
	public function select($label, $name, $value, array $options){
		$select =  "
			<div class='form-group'>
				<label class='col-md-".$this->_labelDistance."'>$label</label>
				<div class='col-md-".$this->getDivDistance()."'> 
					<select name='$name' value='$value'> <option value=''>--Select--</option>"; 
				
					$start = 0; 
					while($start < count($options)){
						$select .= "<option value='".$options[$start]."'>".$options[$start]."</option>";
						$start ++; 
					}
		$select .="
					</select>
				</div> 
			</div> 
		";
		
		echo $select; 
	}
	
	public function textarea($label, $name, $value){
		echo "
			<div class='form-group'>
				<label class='col-md-".$this->_labelDistance."'>$label</label>
				<div class='col-md-".$this->getDivDistance()."'> 
					<textarea class='form-control' name='$name' >$value</textarea>
				</div> 
			</div> 
		";
	}
	
	// form must be closed after it's initialized
	public function close($value){
		if($value == ""){
			echo "
			<div class='form-group'>
				<label class='col-md-".$this->_labelDistance."'></label>
				<div class='col-md-".$this->getDivDistance()."'></div> 
			</div> 
		";
		echo "</form>"; 
		} else {
			echo "
			<div class='form-group'>
				<label class='col-md-".$this->_labelDistance."'></label>
				<div class='col-md-".$this->getDivDistance()."'> 
					<input type='submit' value='$value' class='btn btn-primary' /> 
				</div> 
			</div> 
		";
		echo "</form>"; 
		}
	} 
}