<?php
	require_once 'todueQueries.php';
	
	$myList = new TodoList;

	$error = 0;

	# Setup page information and execute code based on which button was pressed
	switch ( $_POST["btn_action"] ):
		case "Add Item":
			$description = (!empty($_POST["Description"]) ? $_POST["Description"] : "");
			$priority = (!empty($_POST["Priority"]) ? $_POST["Priority"] : "");
			$complete = (!empty($_POST["Completed"]) ? $_POST["Completed"] : "");
			
			if (!empty($_POST)) {
				
			$myList->addTodoItem($description, $complete, $priority);
			
			}
			break;

		case "Delete":
			if ( !$_POST["ID"] ):
				header("location: index.php");
				exit;
			endif;

			$myList->removeListItem($_POST["ID"]);
			break;
		
		case "Edit To Do Item":
			if ( !$_POST["ID"] ):
				header("location: index.php");
				exit;
			endif;

			$btn_value = "Edit To Do Item"; 
			$action = "edit";

			$edit_ListItem = $myList->singleListItem($_POST["ID"]);

			foreach( $edit_ListItem as $key => $value ):
				${$key} = $value;
			endforeach;
			break;

		case "High": 
		case "Medium":
		case "Low":
			if ( $_POST["priority"] == 1){
				$priority = 2;
			}
			else if ($_POST["priority"] == 2) {
				$priority = 3;
			}
			else if ($_POST["priority"] == 3) {
				$priority = 1;
			}
			
			$myList->updatePriority($_POST["ID"], $priority);
			break;
		case "Incomplete":
		case "Complete":
			if ( $_POST["complete"] == 0){
				$complete = 1;
			}
			else if ($_POST["complete"] == 1) {
				$complete = 0;
			}
			
			$myList->updateComplete($_POST["ID"], $complete);
			break;

		default:
			header("location: index.php");
			break;
	endswitch;

	
?>
