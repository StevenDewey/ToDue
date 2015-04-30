<?php 
	require_once 'class.PotionShop.php';
	$myList = new TodoList;

		$myList->removeListItem($_POST["ID"]);
		
		 ?>