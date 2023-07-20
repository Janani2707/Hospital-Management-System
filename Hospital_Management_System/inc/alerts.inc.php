<?php 

if(isset($_GET['message'])){
	Messages::info($_GET['message']);
}